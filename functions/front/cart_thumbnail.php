<?php

namespace pitchprint\functions\front;

function cart_thumbnail($img, $val) {
	if (!empty($val['_w2p_set_option'])) {
		$itm = $val['_w2p_set_option'];
		$itm = json_decode(rawurldecode($itm), true);
		if ($itm['type'] == 'p') {
			$ppImagePrev = ' pp-cart-image-preview="' . PP_IOBASE . '/previews/' . $itm['projectId'] . '_1.jpg" ';
			$pattern = '/\s(?!\")/';
			$img = preg_replace( $pattern, $ppImagePrev, $img, 1 );
			$pattern = '/src="(.+?(?="))/';
			$ppImageSrc = 'src="' . PP_IOBASE . '/previews/' . $itm['projectId'] . '_1.jpg" ';
			$img = preg_replace( $pattern, $ppImageSrc, $img, 1 );
		} else {
			$img = '<img style="width:90px" src="' . $itm['previews'][0] . '" >';
		}
	}
	return $img;
}