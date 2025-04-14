<?php

namespace pitchprint\functions\general;

function order_email(\WC_Order $order, bool $sent_to_admin, bool $plain_text, $email): string {
    $html = '';
    $items = $order->get_items();

    foreach ($items as $item_id => $item) {
        $product = $item->get_product();
        $pitchprint_customization = $item->get_meta(PITCHPRINT_CUSTOMIZATION_KEY, true);
        if (empty($pitchprint_customization)) continue;

        $project_id = $pitchprint_customization['projectId'] ?? '';
        if (empty($project_id)) continue;

        $num_pages = $pitchprint_customization['numPages'] ?? 0;
        $distiller = $pitchprint_customization['distiller'] ?? 'https://pdf.pitchprint.com';

        for ($i = 0; $i < $num_pages; $i++) {
            $html .= '<tr><td colspan="2" style="text-align:left; padding: 10px 0;"><img src="' . PITCHPRINT_PREVIEWS_BASE . $project_id . '_' . ($i + 1) . '.jpg" width="180px; margin-right:10px;"/></td></tr>';
        }

        $include_download_link = get_option('ppa_email_download_link') === 'on';

        if ($sent_to_admin || $include_download_link) {
            $html .= '<tr><td colspan="2" style="text-align:left; padding: 10px 0;"><a href="' . $distiller . '/' . $project_id . '">Download Customization PDF</a></td></tr>';
        }
    }

    return $html;
}