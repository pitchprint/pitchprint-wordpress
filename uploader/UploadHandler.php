<?php
/**
 * PitchPrint Upload Handler
 * Minimal file upload with thumbnail generation. No delete, no download, no bloat.
 */

error_reporting(E_ERROR | E_PARSE);

class PitchPrintUploader {

    private $options;

    function __construct($options) {
        $this->options = $options;
    }

    public function handle() {
        $upload = isset($_FILES['files']) ? $_FILES['files'] : null;

        if (!$upload || !is_array($upload['tmp_name'])) {
            return array('files' => array(array('error' => 'No files uploaded')));
        }

        $results = array();
        foreach ($upload['tmp_name'] as $index => $tmp) {
            $results[] = $this->process_file(
                $tmp,
                $upload['name'][$index],
                $upload['size'][$index],
                $upload['type'][$index],
                $upload['error'][$index]
            );
        }

        return array('files' => $results);
    }

    private function process_file($tmp_file, $original_name, $size, $type, $error) {
        $file = new stdClass();

        if ($error !== UPLOAD_ERR_OK) {
            $file->error = $this->upload_error_message($error);
            return $file;
        }

        if (!is_uploaded_file($tmp_file)) {
            $file->error = 'Invalid upload';
            return $file;
        }

        // Sanitize and generate unique filename
        $name = $this->sanitize_filename($original_name);
        if (!preg_match($this->options['accept_types'], $name)) {
            $file->error = 'File type not allowed';
            return $file;
        }

        if ($this->options['max_file_size'] && $size > $this->options['max_file_size']) {
            $file->error = 'File is too big';
            return $file;
        }

        // Ensure upload directories exist
        if (!is_dir($this->options['upload_dir'])) {
            mkdir($this->options['upload_dir'], 0755, true);
        }
        if (!is_dir($this->options['thumb_dir'])) {
            mkdir($this->options['thumb_dir'], 0755, true);
        }

        $name = $this->unique_filename($name);
        $file_path = $this->options['upload_dir'] . $name;

        if (!move_uploaded_file($tmp_file, $file_path)) {
            $file->error = 'Failed to save file';
            return $file;
        }

        $file->name = $name;
        $file->size = filesize($file_path);
        $file->type = $type;
        $file->url  = $this->options['upload_url'] . rawurlencode($name);

        // Generate thumbnail for images
        if ($this->is_image($file_path, $name)) {
            if ($this->create_thumbnail($file_path, $name)) {
                $file->thumbnailUrl = $this->options['thumb_url'] . rawurlencode($name);
            }
        }

        return $file;
    }

    private function sanitize_filename($name) {
        // Strip path info, control chars, dots at edges
        $name = trim(basename(stripslashes($name)), ".\x00..\x20");
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        return $name;
    }

    private function unique_filename($name) {
        $parts = pathinfo($name);
        $base  = isset($parts['filename']) ? $parts['filename'] : $name;
        $ext   = isset($parts['extension']) ? '.' . $parts['extension'] : '';
        return $base . '_' . uniqid() . '_' . time() . $ext;
    }

    private function is_image($file_path, $name) {
        if (!preg_match('/\.(gif|jpe?g|png)$/i', $name)) {
            return false;
        }
        if (function_exists('exif_imagetype')) {
            return (bool) @exif_imagetype($file_path);
        }
        $info = @getimagesize($file_path);
        return $info && $info[0] && $info[1];
    }

    private function create_thumbnail($file_path, $name) {
        $max = $this->options['thumb_max'];
        $info = @getimagesize($file_path);
        if (!$info) return false;

        list($width, $height) = $info;
        $type = $info[2];

        $scale = min($max / max($width, 1), $max / max($height, 1));
        if ($scale >= 1) {
            // Image is already small enough, just copy
            return copy($file_path, $this->options['thumb_dir'] . $name);
        }

        $new_w = (int) ($width * $scale);
        $new_h = (int) ($height * $scale);

        switch ($type) {
            case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($file_path); break;
            case IMAGETYPE_PNG:  $src = @imagecreatefrompng($file_path);  break;
            case IMAGETYPE_GIF:  $src = @imagecreatefromgif($file_path);  break;
            default: return false;
        }
        if (!$src) return false;

        $dst = imagecreatetruecolor($new_w, $new_h);

        // Preserve transparency for PNG/GIF
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagecolortransparent($dst, imagecolorallocate($dst, 0, 0, 0));
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height);

        $thumb_path = $this->options['thumb_dir'] . $name;
        switch ($type) {
            case IMAGETYPE_JPEG: $ok = imagejpeg($dst, $thumb_path, 75); break;
            case IMAGETYPE_PNG:  $ok = imagepng($dst, $thumb_path, 9);   break;
            case IMAGETYPE_GIF:  $ok = imagegif($dst, $thumb_path);      break;
            default: $ok = false;
        }

        imagedestroy($src);
        imagedestroy($dst);
        return $ok;
    }

    private function upload_error_message($code) {
        $messages = array(
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload limit',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload',
        );
        return isset($messages[$code]) ? $messages[$code] : 'Unknown upload error';
    }
}
