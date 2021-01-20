<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the welcome html.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wpcron_Plugin
 * @subpackage Wpcron_Plugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-wp-main-wrapper">
	<div class="mwb-wp-go-pro">
		<div class="mwb-wp-go-pro-banner">
			<div class="mwb-wp-inner-container">
				<div class="mwb-wp-name-wrapper" id="mwb-wp-page-header">
					<h3><?php esc_html_e( 'Welcome To MakeWebBetter', 'wpcron-plugin' ); ?></h4>
					</div>
				</div>
			</div>
			<div class="mwb-wp-inner-logo-container">
				<div class="mwb-wp-main-logo">
					<img src="<?php echo esc_url( WPCRON_PLUGIN_DIR_URL . 'admin/images/logo.png' ); ?>">
					<h2><?php esc_html_e( 'We make the customer experience better', 'wpcron-plugin' ); ?></h2>
					<h3><?php esc_html_e( 'Being best at something feels great. Every Business desires a smooth buyer’s journey, WE ARE BEST AT IT.', 'wpcron-plugin' ); ?></h3>
				</div>
				<div class="mwb-wp-active-plugins-list">
					<?php
					$mwb_wp_all_plugins = get_option( 'mwb_all_plugins_active', false );
					if ( is_array( $mwb_wp_all_plugins ) && ! empty( $mwb_wp_all_plugins ) ) {
						?>
						<table class="mwb-wp-table">
							<thead>
								<tr class="mwb-plugins-head-row">
									<th><?php esc_html_e( 'Plugin Name', 'wpcron-plugin' ); ?></th>
									<th><?php esc_html_e( 'Active Status', 'wpcron-plugin' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( is_array( $mwb_wp_all_plugins ) && ! empty( $mwb_wp_all_plugins ) ) { ?>
									<?php foreach ( $mwb_wp_all_plugins as $wp_plugin_key => $wp_plugin_value ) { ?>
										<tr class="mwb-plugins-row">
											<td><?php echo esc_html( $wp_plugin_value['plugin_name'] ); ?></td>
											<?php if ( isset( $wp_plugin_value['active'] ) && '1' != $wp_plugin_value['active'] ) { ?>
												<td><?php esc_html_e( 'NO', 'wpcron-plugin' ); ?></td>
											<?php } else { ?>
												<td><?php esc_html_e( 'YES', 'wpcron-plugin' ); ?></td>
											<?php } ?>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
						<?php
					}
					?>
				</div>
			</div>
		</div>
