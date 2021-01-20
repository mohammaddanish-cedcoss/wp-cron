<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
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
// Template for showing information about system status.
global $wp_mwb_wp_obj;
$wp_default_status = $wp_mwb_wp_obj->mwb_wp_plug_system_status();
$wp_wordpress_details = is_array( $wp_default_status['wp'] ) && ! empty( $wp_default_status['wp'] ) ? $wp_default_status['wp'] : array();
$wp_php_details = is_array( $wp_default_status['php'] ) && ! empty( $wp_default_status['php'] ) ? $wp_default_status['php'] : array();
?>
<div class="mwb-wp-table-wrap">
	<div class="mwb-wp-table-inner-container">
		<table class="mwb-wp-table" id="mwb-wp-wp">
			<thead>
				<tr>
					<th><?php esc_html_e( 'WP Variables', 'wpcron-plugin' ); ?></th>
					<th><?php esc_html_e( 'WP Values', 'wpcron-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $wp_wordpress_details ) && ! empty( $wp_wordpress_details ) ) { ?>
					<?php foreach ( $wp_wordpress_details as $wp_key => $wp_value ) { ?>
						<?php if ( isset( $wp_key ) && 'wp_users' != $wp_key ) { ?>
							<tr>
								<td><?php echo esc_html( $wp_key ); ?></td>
								<td><?php echo esc_html( $wp_value ); ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="mwb-wp-table-inner-container">
		<table class="mwb-wp-table" id="mwb-wp-php">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Sysytem Variables', 'wpcron-plugin' ); ?></th>
					<th><?php esc_html_e( 'System Values', 'wpcron-plugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $wp_php_details ) && ! empty( $wp_php_details ) ) { ?>
					<?php foreach ( $wp_php_details as $php_key => $php_value ) { ?>
						<tr>
							<td><?php echo esc_html( $php_key ); ?></td>
							<td><?php echo esc_html( $php_value ); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
