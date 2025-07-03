<?php

	namespace pitchprint\functions\admin;
	function format_pitchprint_order_key($display_value, $meta, $order_item) {
		
		if ($meta->key === 'preview' || $meta->key === '_w2p_set_option') {
			// Check if the current request is for an email or packing slip
			if (did_action('woocommerce_email_order_details') || 
				did_action('wpo_wcpdf_process_template') || 
				(isset($_GET['wpo_wcpdf_document_type']) && $_GET['wpo_wcpdf_document_type'] === 'packing-slip') ||
				(isset($_GET['action']) && strpos($_GET['action'], 'packing') !== false)) {
				return '';
			}
			return 'PitchPrint Customization';
		}
		return $display_value;
	}

	function legacy_order_value() {
		global $post, $woocommerce;

		$cred = \pitchprint\functions\general\fetch_credentials();
		wp_enqueue_script('pitchprint_admin', 'https://pitchprint.io/rsc/js/a.wp.js');
		wc_enqueue_js( "var PPADMIN = window.PPADMIN; if (typeof PPADMIN === 'undefined') window.PPADMIN = PPADMIN = { version: '9.0.0', readyFncs: [] };" . "
			PPADMIN.vars = {
				credentials: { timestamp: '" . $cred['timestamp'] . "', apiKey: '" . get_option('ppa_api_key') . "', signature: '" . $cred['signature'] . "'}
			};
			PPADMIN.readyFncs.push('init');
			if (typeof PPADMIN.start !== 'undefined') PPADMIN.start();
		");
	}
	function format_pitchprint_order_value($formatted_meta, $order_item) {
		// Check if the current request is for an email or packing slip
		if (did_action('woocommerce_email_order_details') || 
			did_action('wpo_wcpdf_process_template') || 
			(isset($_GET['wpo_wcpdf_document_type']) && $_GET['wpo_wcpdf_document_type'] === 'packing-slip') ||
			(isset($_GET['action']) && strpos($_GET['action'], 'packing') !== false)) {
			return $formatted_meta;
		}

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

							if (!isset($distiller) || empty($distiller)) {
								$distiller = 'https://pdf.pitchprint.com';
							}

							if ($num_pages > 4) $num_pages = 4;

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
										<a target="_blank" href="https://jpeg.pitchprint.com/'. $project_id .'">• Download JPEG</a><br/>
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

	function handle_new_order( $order_id, $order ) {
		$items = $order->get_items();
		$should_update_user_id = false;
		$project_ids = [];
		$user_id = $order->get_user_id();
	
		foreach ( $items as $item ) {
			foreach ( $item->get_meta_data() as $meta ) {
				if ( $meta->key === PITCHPRINT_CUSTOMIZATION_KEY ) {
					$pitchprint_customization = $meta->value;

					if ( is_string($pitchprint_customization) ) {
						$pitchprint_customization = json_decode( $pitchprint_customization, true );
					}

					if ( !empty($pitchprint_customization['projectId']) ) {
						if ( $user_id && $pitchprint_customization['userId'] === 'guest' ) {
							$should_update_user_id = true;
						}
						$project_ids[] = $pitchprint_customization['projectId'];
					}
				}
			}
		}

		if ( count($project_ids) ) {
			$auth_key = get_option( 'ppa_secret_key' );
			// Append order ID
			$response1 = wp_remote_post( 'https://api.pitchprint.com/runtime/append-project-order-id', [
				'headers' => [ 'Authorization' => $auth_key ],
				'body'    => json_encode( [
					'projectIds' => $project_ids,
					'orderId'    => $order_id,
				] ),
				'method'  => 'POST',
			] );

			if ( is_wp_error( $response1 ) || wp_remote_retrieve_response_code( $response1 ) !== 200 ) {
				error_log( '[PitchPrint] Failed to append order ID to projects: ' . print_r( $response1, true ) );
			}
	
			// Append user ID if needed
			if ( $should_update_user_id ) {
				$response2 = wp_remote_post( 'https://api.pitchprint.com/runtime/append-project-user-id', [
					'headers' => [ 'Authorization' => $auth_key ],
					'body'    => json_encode( [
						'projectIds' => $project_ids,
						'userId'     => $user_id,
					] ),
					'method'  => 'POST',
				] );

				if ( is_wp_error( $response2 ) || wp_remote_retrieve_response_code( $response2 ) !== 200 ) {
					error_log( '[PitchPrint] Failed to append user ID to projects: ' . print_r( $response2, true ) );
				}
			}
		}
	}
	

	function order_status_completed($order_id, $status_from, $status_to) {
		$pp_webhook_url = false;

		if ($status_to === "completed") $pp_webhook_url = 'order-complete';
		if ($status_to === "processing") $pp_webhook_url = 'order-processing';

		if ($pp_webhook_url) {
			$order = wc_get_order($order_id);
			$order_data = $order->get_data();
			$billing = $order_data['billing'];
			$billingEmail = $billing['email'];
			$billingPhone = $billing['phone'];
			$billingName = $billing['first_name'] . " " . $billing['last_name'];

			$addressArr = ['address_1', 'address_2', 'city', 'postcode', 'country'];
			$billingAddress = '';
			foreach ($addressArr as $addKey) {
				if (!empty($billing[$addKey])) {
					$billingAddress .= $billing[$addKey] . ", ";
				}
			}
			$billingAddress = rtrim($billingAddress, ', ');

			$shippingName = $order_data['shipping']['first_name'] . " " . $order_data['shipping']['last_name'];
			$shippingAddress = $order->get_formatted_shipping_address();
			$status = $order_data['status'];
			$products = $order->get_items();
			$userId = $order_data['customer_id'];
			$items = array();

			foreach ($products as $item_key => $item_values) {
				$item_data = $item_values->get_data();
				$pprint = null;
				
				foreach ($item_values->get_meta_data() as $meta) {
					if ($meta->key === PITCHPRINT_CUSTOMIZATION_KEY) {
						$pitchprint_customization = $meta->value;
						if (is_string($pitchprint_customization)) {
							$pitchprint_customization = json_decode($pitchprint_customization, true);
						}
						if (!empty($pitchprint_customization)) {
							$pprint = json_encode($pitchprint_customization);
						}
						break;
					}
				}

				$items[] = array(
					'name' => $item_data['name'],
					'id' => $item_data['product_id'],
					'qty' => $item_data['quantity'],
					'pitchprint' => $pprint,
					'sku' => $item_data['sku'],
				);
			}

			// If empty Pitchprint value, then we won't trigger the webhook.
			$should_send = false;
			foreach ($items as $item) {
				if (!empty($item['pitchprint'])) {
					$should_send = true;
					break;
				}
			}

			if ($should_send) {
				$cred = \pitchprint\functions\general\fetch_credentials();
				$opts = array(
					'products' => json_encode($items),
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

				$response = wp_remote_post("https://api.pitchprint.io/runtime/$pp_webhook_url", array(
					'headers' => array(
						'Content-Type' => 'application/json'
					),
					'body' => json_encode($opts),
					'method' => 'POST',
					'data_format' => 'body',
					'timeout' => 20
				));

				if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
					error_log('[PitchPrint] Webhook failed: ' . print_r($response, true));
				}
			}
		}
	}