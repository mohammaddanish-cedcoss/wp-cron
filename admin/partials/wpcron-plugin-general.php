<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
global $wp_mwb_wp_obj;
$wp_genaral_settings = apply_filters( 'wp_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="wp-secion-wrap">
	<table class="form-table wp-settings-table">
		<?php
			$wp_general_html = $wp_mwb_wp_obj->mwb_wp_plug_generate_html( $wp_genaral_settings );
			echo esc_html( $wp_general_html );
		?>
	</table>
</div>
