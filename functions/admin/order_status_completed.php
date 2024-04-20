<?php

namespace pitchprint\functions\admin;

function order_status_completed($order_id, $status_from, $status_to) {
    $pp_webhookUrl = false;
    
    // Clear Old Projects
    if ( date('j') == '1' ) 
        \pitchprint\functions\general\clearOldProjects();
    
    if ( $status_to === "completed" ) $pp_webhookUrl = 'order-complete';
    if ( $status_to === "processing" ) $pp_webhookUrl = 'order-processing';
    
    if ( $pp_webhookUrl ) {
        
        $order = wc_get_order($order_id);
        $order_data = $order->get_data();

        $billing = $order_data['billing'];
        $billingEmail = $billing['email'];
        $billingPhone = $billing['phone'];
        $billingName = $billing['first_name'] . " " . $billing['last_name'];
        
        $addressArr = ['address_1', 'address_2', 'city', 'postcode', 'country'];
        $billingAddress = '';
        foreach ($addressArr as $addKey) 
            if (!empty($billing[$addKey])) 
                $billingAddress .= $billing[$addKey].", ";
        $billingAddress = substr($billingAddress, 0, strlen($billingAddress) -2);

        $shippingName = $order_data['shipping']['first_name'] . " " . $order_data['shipping']['last_name'];
        $shippingAddress = $order->get_formatted_shipping_address();
        $status = $order_data['status'];

        $products = $order->get_items();
        $userId = $order_data['customer_id'];
        $items = array();

        foreach ($products as $item_key => $item_values) {
            $item_data = $item_values->get_data();
            $items[] = array(
                'name' => $item_data['name'],
                'id' => $item_data['product_id'],
                'qty' => $item_data['quantity'],
                'pitchprint' => wc_get_order_item_meta($item_key, '_w2p_set_option')
            );
        }

        // If empty Pitchprint value, then we won't trigger the webhook.
        $pp_empty = true;
        foreach ($items as $item) {
            if (!empty($item['pitchprint'])) {
                $pp_empty = false;
                break;
            }
        }

        if (!$pp_empty) {
        
            $items = json_encode($items);
            $cred = \pitchprint\functions\general\fetch_credentials();
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, "https://api.pitchprint.io/runtime/$pp_webhookUrl");
            curl_setopt($ch, CURLOPT_POST, true);
            
            $opts =  array (
                        'products' => $items,
                        'client' => 'wp',
                        'billingEmail' => $billingEmail,
                        'billingPhone' => $billingPhone,
                        'billingName' => $billingName,
                        'billingAddress' => $billingAddress,
                        'shippingName' => $shippingName,
                        'shippingAddress' => $shippingAddress,
                        'orderId' => $order_id,
                        'customer' => $userId,
                        'status' => $status,
                        'apiKey' => get_option('ppa_api_key'),
                        'signature' => $cred['signature'],
                        'timestamp' => $cred['timestamp']
                    );
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($opts));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $output = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $curlerr = curl_error($ch);
            curl_close($ch);

            if ($curlerr) {
               error_log(print_r($curlerr, true));
            }
        }
    }
}