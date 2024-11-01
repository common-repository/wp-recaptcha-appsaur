<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin aspects of the plugin.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/admin
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<div class="wprc-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<div class="wprc-postbox">
			<h2 class="nav-tab-wrapper"><?php _e('Google settings', $this->plugin_name);?></h2>

			<form method="POST" name="<?php echo $this->plugin_name;?>" action="options.php">

				<?php
				//Get all plugin options
				$options = get_option($this->plugin_name);

				// settings google
				$wpcr_site_key    = isset( $options['site_key'] ) ? $options['site_key'] : '';
				$wpcr_secret_key = isset( $options['secrete_key'] ) ? $options['secrete_key'] : '';

				// settings plugin reCaptcha
				$wpcr_captcha_login =  (!empty($options['captcha_login']) ? $options['captcha_login'] : '');
				$wpcr_captcha_registration = isset( $options['captcha_registration'] ) ? $options['captcha_registration'] : '';
				$wpcr_captcha_reset_pass = isset( $options['captcha_reset_pass'] ) ? $options['captcha_reset_pass'] : '';
				$wpcr_captcha_comment = isset( $options['captcha_comment'] ) ? $options['captcha_comment'] : '';

				$wpcr_error_message = isset( $options['error_message'] ) ? $options['error_message'] : '';
				?>

				<?php
				settings_fields( $this->plugin_name );
				do_settings_sections( $this->plugin_name );
				?>
				<div class="wprc-postbox-inside">
					<table class="form-table">
						<tbody>
						<tr valign="top">
							<th scope="row"> <label for="wpcr_site_ley">
									<?php _e( 'Site key', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<input class="regular-text" type="text" name="<?php echo $this->plugin_name;?>[site_key]" id="wpcr_site_key" value="<?php echo esc_attr($wpcr_site_key); ?>">
								<p class="description">
									<?php echo sprintf(
										__( 'Please add site key. You can generate %1$shere%2$s', $this->plugin_name ),
										'<a href="https://www.google.com/recaptcha/admin" target="_blank">',
										'</a>'
									); ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"> <label for="wpc_secret_key">
									<?php _e( 'Secrete key', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<input class="regular-text" type="text" name="<?php echo $this->plugin_name;?>[secrete_key]" id="wpcr_secret_key" value="<?php echo esc_attr($wpcr_secret_key); ?>">
								<p class="description">
									<?php echo sprintf(
										__( 'Please add secret key. You can generate %1$shere%2$s', $this->plugin_name ),
										'<a href="https://www.google.com/recaptcha/admin" target="_blank">',
										'</a>'
									); ?>
								</p>
							</td>
						</tr>
						</tbody>
					</table>
				</div>

				<h2 class="nav-tab-wrapper"><?php _e('Display reCaptcha in form', $this->plugin_name);?></h2>
				<div class="wprc-postbox-inside">
					<table class="form-table">
						<tbody>
						<tr valign="top">
							<th scope="row"> <label for="wpc_enable_login_form">
									<?php _e( 'Login Form', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<p class="description">
									<input type="checkbox" name="<?php echo $this->plugin_name;?>[captcha_login]" value="true" <?php if( !empty($wpcr_captcha_login) ) echo 'checked="checked" ';?> >
									<?php _e( 'Activate reCaptcha for Login Form.', $this->plugin_name ); ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"> <label for="wpc_enable_registration_form">
									<?php _e( 'Registration Form', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<p class="description">
									<input type="checkbox" name="<?php echo $this->plugin_name;?>[captcha_registration]" value="true" <?php if( !empty($wpcr_captcha_registration) ) echo 'checked="checked" ';?> >
									<?php _e( 'Activate reCaptcha for Registration Form.', $this->plugin_name ); ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"> <label for="wpc_enable_registration_form">
									<?php _e( 'Reset password Form', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<p class="description">
									<input type="checkbox" name="<?php echo $this->plugin_name;?>[captcha_reset_pass]" value="true" <?php if( !empty($wpcr_captcha_reset_pass) ) echo 'checked="checked" ';?> >
									<?php _e( 'Activate reCaptcha for Reset password Form.', $this->plugin_name ); ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"> <label for="wpc_enable_registration_form">
									<?php _e( 'Comment Form', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<p class="description">
									<input type="checkbox" name="<?php echo $this->plugin_name;?>[captcha_comment]" value="true" <?php if( !empty($wpcr_captcha_comment) ) echo 'checked="checked" ';?> >
									<?php _e( 'Activate reCaptcha for Comment Form.', $this->plugin_name ); ?>
								</p>
							</td>
						</tr>
						</tbody>
					</table>
				</div>

				<h2 class="nav-tab-wrapper"><?php _e('Settings', $this->plugin_name);?></h2>
				<div class="wprc-postbox-inside">
					<table class="form-table">
						<tbody>
						<tr valign="top">
							<th scope="row"> <label for="wpcr_site_ley">
									<?php _e( 'Error message', $this->plugin_name ); ?>
								</label>
							</th>
							<td>
								<input class="regular-text" type="text" name="<?php echo $this->plugin_name;?>[error_message]" id="wpcr_error_message" value="<?php echo esc_attr($wpcr_error_message); ?>">
								<p class="description">
									<?php echo sprintf(
										__( 'Please add the error message displayed when an incorrect validation reCaptcha.', $this->plugin_name )
									); ?>
								</p>
							</td>
						</tr>

						<tr valign="top">
							<td colspan="2">
								<input type="submit" id="wpcr_captcha_settings" name="<?php echo $this->plugin_name;?>[captcha_settings]" class="button button-primary" value="<?php echo __('Save', $this->plugin_name)?>">
						</tr>
						</tbody>
					</table>
				</div>


				<!-- remove some meta and generators from the <head> -->
			</form>
		</div>
	</div>
	<div class="wprc-wrapp-right">
		<a href="http://appsaur.co" title="Appsaur.co Your Team" target="_blank" >
			<img src="<?php echo plugins_url( '/assets/img/appsaur_dont_click.png', dirname(dirname(__FILE__)) ); ?>" title="Appsaur.co plugins" >
		</a>
	</div>
</div>

