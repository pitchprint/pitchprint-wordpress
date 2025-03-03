<?php

	namespace pitchprint\functions\admin;

	function format_pitchprint_order_key($display_value, $meta, $order_item) {
		
		if ($meta->key === 'preview' || $meta->key === '_w2p_set_option') return 'PitchPrint Customization';
		return $display_value;
		
	}

	function format_pitchprint_order_value($formatted_meta, $order_item) {

		foreach ($formatted_meta as $meta) {
			if ($meta->key === PITCHPRINT_CUSTOMIZATION_PREVIEWS_KEY) {

				$item_meta_data = $order_item->get_meta_data();
				if (empty($item_meta_data)) return $formatted_meta;

				foreach ($item_meta_data as $item_meta) {
					if ($item_meta->key === PITCHPRINT_CUSTOMIZATION_KEY) {
						$pitchprint_customization = $item_meta->value;

						if (!empty($pitchprint_customization['projectId'])) {
							$project_id = $pitchprint_customization['projectId'];
							$previews = '';
							$num_pages = $pitchprint_customization['numPages'];
							$distiller = $pitchprint_customization['distiller'];
							for ($i = 0; $i < $num_pages; $i++) {
								$previews .= '<img src="' . PITCHPRINT_PREVIEWS_BASE . $project_id . '_' . ($i + 1) . '.jpg" width="180px; margin-right:10px;"/>';
							}

							$display = '
								<div class="pitchprint_order_meta" style="display: flex;">
									<div data-project-id="' . $pitchprint_customization["projectId"] . '" class="pda_show_preview" style="display:flex; flex-wrap: wrap;">
										' . $previews . '
									</div>
									<div>
										<a target="_blank" href="'. $distiller . '/' . $project_id .'">• Download PDF</a><br/>
										<a target="_blank" href="https://png.pitchprint.com/'. $project_id .'">• Download PNG</a><br/>
										<a target="_blank" href="https://jpg.pitchprint.com/'. $project_id .'">• Download JPEG</a><br/>
										<a target="_blank" href="https://tiff.pitchprint.com/'. $project_id .'">• Download TIFF</a><br/>
										<a target="_blank" href="https://admin.pitchprint.com/projects#'. $project_id .'">• Modify Project</a>
									</div>
								</div>';

							$meta->display_value = $display;

						} else if (isset($pitchprint_customization['files']) && is_array($pitchprint_customization['files'])) {
							$display = '<div class="pitchprint_order_meta" style="display: flex;">';

							$count = 1;
							foreach ($pitchprint_customization['files'] as $file) {
								$display .= '<div style="margin-right: 10px;"><a target="_blank" href="' . $file . '">• Download File ' . $count . '</a></div>';
								$count++;
							}

							$display .= '<div>';
							$meta->display_value = $display;
						}
						
						break;
					}
				}

				break;
			}
		}
		
		return $formatted_meta;
	}

	// this is for backward compatibility v1, to display old orders pre-installing this version
	function pitchprint_filter_wc_order_item_display_meta_value( $display_value, $meta ) {
		if ( $meta->key === '_w2p_set_option' ) {
			$pda_data = json_decode($display_value, true);
			
			$auth_key = get_option('pitchprint_secret_key');

			if (!empty($pda_data['projectId'])) {
				$hash = md5( $pda_data['projectId'] . $auth_key );
				$post_fix = $pda_data['projectId'] . '!' . $hash;

				return '
					<div class="pitchprint_order_meta" style="display: flex;">
						<div onclick="pda_show_preview(this)" data-project-id="' . $pda_data["projectId"] . '" class="pda_show_preview" style="margin-right: 10px;">
							<img src="' . $pda_data['previews'][0]['url'] . '" width="180px"/>
							<div>
								<svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler icon-tabler-search" width="22px" height="22px" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" data-v-09078359="">   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>   <circle cx="10" cy="10" r="7"></circle>   <line x1="21" y1="21" x2="15" y2="15"></line> </svg>
							</div>
						</div>
						<div>
							<a target="_blank" href="https://pdf.print.app/'. $post_fix .'">Download PDF</a><br/>
							<a target="_blank" href="https://png.print.app/'. $post_fix .'">Download PNG</a><br/>
							<a target="_blank" href="https://jpg.print.app/'. $post_fix .'">Download JPEG</a><br/>
							<a target="_blank" href="https://tiff.print.app/'. $post_fix .'">Download TIFF</a><br/>
							<a target="_blank" href="https://admin.print.app/projects/'. $pda_data['projectId'] .'">Modify Project</a>
						</div>
					</div>';
			} else if (!empty($pda_data['form'])) {
				print_r($pda_data);
			}
		}
		return $display_value;  
	}


	function order_status_completed($order_id, $status_from, $status_to) {
		$pp_webhookUrl = false;
		
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