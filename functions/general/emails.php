<?php

namespace pitchprint\functions\general;

function order_email(\WC_Order $order, bool $sent_to_admin, bool $plain_text, $email) {
    $html = '';
    $items = $order->get_items();

    foreach ($items as $item_id => $item) {
        $product = $item->get_product();
        $pitchprint_customization = $item->get_meta(PITCHPRINT_CUSTOMIZATION_KEY, true);
        if (empty($pitchprint_customization)) continue;

        $project_id = $pitchprint_customization['projectId'] ?? '';
        if (empty($project_id)) continue;

        $num_pages = $pitchprint_customization['numPages'] ?? 0;
        
        for ($i = 0; $i < $num_pages; $i++) {
            $html .= '<tr><td colspan="2" style="text-align:left; padding: 10px 0;"><img src="' . PITCHPRINT_PREVIEWS_BASE . $project_id . '_' . ($i + 1) . '.jpg" style="width:180px; margin-right:10px;"/></td></tr>';
        }
        
        $include_download_link = get_option('ppa_email_download_link') === 'on';
        
        if ($sent_to_admin || $include_download_link) {
            $distiller = $pitchprint_customization['distiller'] ?? 'https://pdf.pitchprint.com';
            $html .= '<tr><td colspan="2" style="text-align:left; padding: 10px 0;"><a href="' . $distiller . '/' . $project_id . '">Download Customization PDF</a></td></tr>';
        }
    }

    if (!empty($html)) {
        echo $html;
    }
}
