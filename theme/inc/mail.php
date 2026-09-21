<?php
function webula_validate_form( $form_data ) {
	// check for nounce
	// if ( ! isset( $form_data['ajax_form_nonce'] ) || ! wp_verify_nonce( $form_data['ajax_form_nonce'], 'ajax_form' ) ) {
	// 	return [
	// 		'status'  => false,
	// 		'message' => 'Invalid nonce',
	// 	];
	// }

	// spam check
	if ( ! isset( $form_data['encoded_num1'] ) || ! isset( $form_data['encoded_num2'] ) ) {
		return [
			'status'  => false,
			'message' => 'Invalid spam check',
		];
	}

	if ( intval( $form_data['check'] ) !== intval( base64_decode( $form_data['encoded_num1'] ) ) + intval( base64_decode( $form_data['encoded_num2'] ) ) ) {
		return [
			'status'  => false,
			'message' => 'Invalid spam check',
		];
	}

	// check for required fields
	if ( ! isset( $form_data['your-name'] ) || ! isset( $form_data['phone'] ) || ! isset( $form_data['email'] ) ) {
		return [
			'status'  => false,
			'message' => 'Required fields are missing',
		];
	}

	return [ 'status' => true ];
}

/**
 * Get the current date and time.
 *
 * @return string Date and time in format H:i:s / m.d.y
 */
function webula_get_current_date() {
	return date( 'H:i:s / m.d.y' );
}

/**
 * Get IP address of the visitor.
 *
 * @return string IP address of the visitor
 */
function webula_get_client_ip() {
	return filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP ) ? $_SERVER['REMOTE_ADDR'] : '';
}

function webula_get_form_keys_whitelist() {
	return [
		'your-name',
		'last-name',
		'company',
		'file',
		'jobtitle',
		'phone',
		'department',
		'email',
		'message',
		'checkbox',
		'utm_source',
		'utm_medium',
		'utm_campaign',
		'utm_content',
		'utm_term',
		'form_page_url',
		'first_page_url',
	];
}

/**
 * Maps the form data to the whitelisted keys and changes the label of specific keys.
 *
 * @param array $form_data The form data to be mapped.
 * @param array $keys_whitelist The whitelisted keys to be mapped.
 * @param array $tarifs_map The map of tarifs to be used for mapping the tarif key.
 *
 * @return array The mapped form data.
 */
function webula_map_form_data( $form_data, $keys_whitelist ) {
	$mapped_data = [];

	foreach ( $form_data as $key => $value ) {
		if ( ! empty( $value ) && in_array( $key, $keys_whitelist ) && $key !== 'offer' ) {
			// Map specific keys to new labels
			switch ( $key ) {
				case 'jobtitle':
					// Change the label of the message key
					$key = 'Job';
					break;
				case 'file':
					// Change the label of the company key
					$key = 'File';
					break;
				case 'department':
					// Change the label of the department key
					$key   = 'Р”РµРїР°СЂС‚Р°РјРµРЅС‚';
					break;
				case 'message':
					// Change the label of the dispatch key
					$key = 'РЎРѕРѕР±С‰РµРЅРёРµ';
					break;
				case 'company':
					// Change the label of the post key
					$key = 'Company';
					break;
				case 'last-name':
					// Change the label of the type key
					$key = 'Last name';
					break;
				case 'your-name':
					// Change the label of the your-name key
					$key = 'Name';
					break;
				case 'form_page_url':
					// Change the label of the form_page_url key
					$key = 'РЎС‚СЂР°РЅРёС†Р° РѕС‚РїСЂР°РІРєРё С„РѕСЂРјС‹';
					break;
				case 'first_page_url':
					// Change the label of the first_page_url key
					$key = 'РЎС‚СЂР°РЅРёС†Р° РїРµСЂРІРѕРіРѕ РїРѕСЃРµС‰РµРЅРёСЏ';
					break;
			}

			// Sanitize the value
			$sanitized_value = htmlspecialchars( $value );

			// Store in mapped data
			$mapped_data[ $key ] = $sanitized_value;
		}
	}

	return $mapped_data;
}

function webula_build_email_message( $mail_data, $date, $ip_client, $form_data ) {
	$td = '<td style="padding: 10px; border: #e9e9e9 1px solid;">';

	$message  = '<table style="width: 100%; background-color: #f8f8f8;">';
	$message .= '<tr style="background-color: #d6d6d6">'
		. $td . 'Р’СЂРµРјСЏ/Р”Р°С‚Р° РѕС‚РїСЂР°РІРєРё СЃРѕРѕР±С‰РµРЅРёСЏ</td>'
		. $td . $date . '</td></tr>';

	// Add form data to message
	foreach ( $mail_data as $key => $value ) {
		$message .= '<tr>'
			. $td . preg_replace( '/_/', ' ', $key ) . '</td>'
			. $td . $value . '</td></tr>';
	}

	// Add IP address
	$message .= '<tr>'
		. $td . 'IP РїРѕСЃРµС‚РёС‚РµР»СЏ</td>'
		. $td . $ip_client . '</td></tr>';

	$message .= '</table>';

	return $message;
}

/**
 * Prepare mail data to be sent.
 *
 * @param array $form_data The form data to be sent in email.
 *
 * @return array Array of data to be sent in email.
 */
function webula_prepare_full_mail_data( $form_data ) {
	$date      = webula_get_current_date();
	$ip_client = webula_get_client_ip();

	$keys_whitelist = webula_get_form_keys_whitelist();

	$mapped_data = webula_map_form_data( $form_data, $keys_whitelist );
	$message     = webula_build_email_message( $mapped_data, $date, $ip_client, $form_data );
	$mail_data   = array_merge(
		$mapped_data,
		[
			'ip_client' => $ip_client,
			'date'      => $date,
		]
	);

	return [
		'message'   => $message,
		'mail_data' => $mail_data,
	];
}

function webula_send_email( $to, $subject, $message, $attachments = [] ) {
	$mail_headers  = "MIME-Version: 1.0;\r\n";
	$mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail_headers .= "From: Noreply <noreply@webula.com> \r\n";

	return wp_mail(
		$to,
		$subject,
		$message,
		$mail_headers,
		$attachments
	);
}

/**
 * Handles the AJAX form submission.
 *
 * This function is called when the client submits the form using AJAX.
 * It validates the form data, prepares the mail data, sends the email and
 * returns the result to the client.
 */
function webula_ajax_form() {
	// Validate the form data
	$result = webula_validate_form( $_POST );

	if ( ! $result['status'] ) {
		wp_send_json_error( $result['message'] );
	}

	// Handle file upload if present
	$attachments = [];
	if ( isset( $_FILES['file'] ) && ! empty( $_FILES['file']['tmp_name'] ) ) {
		$file = $_FILES['file'];

		// Sanitize file name and move it to a temporary location
		$upload = wp_handle_upload( $file, [ 'test_form' => false ] );

		if ( isset( $upload['file'] ) ) {
			$attachments[] = $upload['file']; // Add the file path to the attachments array
		} else {
			wp_send_json_error( 'File upload error: ' . $upload['error'] );
		}
	}

	// Prepare the mail data
	$full_mail_data = webula_prepare_full_mail_data( $_POST );

	$to      = 'info@webula.com';
	$subject = 'Application from the webula';
	$message = $full_mail_data['message'];

	// Send the email
	$result = webula_send_email( $to, $subject, $message, $attachments );

	// Return the result to the client
	if ( $result ) {
		wp_send_json_success( 'Message sent' );
	} else {
		wp_send_json_error( 'Error sending message' );
	}
}
add_action( 'wp_ajax_ajax_form', 'webula_ajax_form' );
add_action( 'wp_ajax_nopriv_ajax_form', 'webula_ajax_form' );
