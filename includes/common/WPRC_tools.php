<?php

/**
 * The file that defines the core tools plugin classes
 *
 * A class definition that includes attributes and functions used in
 * public side and the admin side.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPRC
 * @subpackage WPRC/includes/common
 * @author     Dariusz Andryskowski
 */
class WPRC_Tools extends WPRC_Detail {


	/**
	 * Function get path from url eg. wp-admin; wp-login.php
	 * @return mixed
	 */
	public static function request_uri() {
		$part = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$part = trim($part, "/");
		$part = strtolower($part);
		$part = explode("/", $part);
		return $part[0];
	}



	/** Display output reCAPTCHA in form field. */
	public function display_captcha_form() {

		if ( isset( $_GET['captcha'] ) && $_GET['captcha'] == 'failed' ) {
			echo self::$error_message;
		}

		echo '<div class="g-recaptcha" data-sitekey="' . self::$site_key . '" data-theme="light" style="transform:scale(0.90);-webkit-transform:scale(0.90);transform-origin:0 0;-webkit-transform-origin:0 0;" ></div>';
	}


	/**
	 * Function veryfication captcha
	 *
	 * @since    1.0.0
	 * @return mixed info about validate captcha
	 */
	public function verification_captcha() {

		$remoteIp = $_SERVER["REMOTE_ADDR"];
		$response = isset( $_POST['g-recaptcha-response'] ) ? esc_attr( $_POST['g-recaptcha-response'] ) : '';

		// create GET request to the Google reCAPTCHA Server
		$request = wp_remote_get(
			'https://www.google.com/recaptcha/api/siteverify?secret=' . self::$secret_key . '&response=' . $response . '&remoteip=' . $remoteIp
		);

		// get the request response (example: { "success": true, "challenge_ts": "2017-01-10T23:08:23Z", "hostname": "your-domain.com" })
		$response = wp_remote_retrieve_body( $request );

		// decode json
		$result = json_decode( $response, true );

		return $result['success'];
	}


	/**
	 * Validate captcha in comment
	 * @param $user
	 * @param $password
	 * @return WP_Error
	 */
	public function validate_captcha_comment( $commentdata ) {

		if ( !isset($_POST['g-recaptcha-response'])
			|| !$this->verification_captcha()
		) {
			wp_die( self::$error_message );
		}
		return $commentdata;
	}

	/**
	 * Validate captcha in login/register/lost password
	 * @param $user
	 * @param $password
	 * @return WP_Error
	 */
	public function validate_captcha( $user, $password ) {

		if ( !isset( $_POST['g-recaptcha-response'] )
			|| !$this->verification_captcha()
		) {
			// show error
			return new WP_Error( 'empty_captcha', self::$error_message );
		}

		return $user;
	}

	/**
	 * Validate captcha in lost password
	 * @param $result
	 * @param $user_id
	 * @return WP_Error
	 */
	function validate_captcha_lost_password( $result, $user_id ) {
		if ( !isset( $_POST['g-recaptcha-response'] )
			|| !$this->verification_captcha()
		) {
			$result = new WP_Error( 'invalid_captcha', __("<strong>ERROR</strong>: Use a captcha to validate ", $this->plugin_name) );
		}

		return $result;
	}

	/**
	 * Add api google  recaptcha
	 *
	 * @since    1.0.0
	 */
	public function add_header_script_recaptcha() {

		$lang = (isset( self::$language  ) && !empty( self::$language  ) ) ? "?hl=" . self::$language  : '';

		echo '<script src="https://www.google.com/recaptcha/api.js' . $lang . '" async defer></script>' . "\r\n";
	}
}