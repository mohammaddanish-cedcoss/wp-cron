<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wpcron_Plugin
 * @subpackage Wpcron_Plugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $wp_mwb_wp_obj;
$wp_active_tab   = isset( $_GET['wp_tab'] ) ? sanitize_key( $_GET['wp_tab'] ) : 'wpcron-plugin-general';
$wp_default_tabs = $wp_mwb_wp_obj->mwb_wp_plug_default_tabs();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-wp-main-wrapper">
	<div class="mwb-wp-go-pro">
		<div class="mwb-wp-go-pro-banner">
			<div class="mwb-wp-inner-container">
				<div class="mwb-wp-name-wrapper">
					<p><?php esc_html_e( 'wpcron plugin', 'wpcron-plugin' ); ?></p></div>
					<div class="mwb-wp-static-menu">
						<ul>
							<li>
								<a href="<?php echo esc_url( 'https://makewebbetter.com/contact-us/' ); ?>" target="_blank">
									<span class="dashicons dashicons-phone"></span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( 'https://docs.makewebbetter.com/hubspot-woocommerce-integration/' ); ?>" target="_blank">
									<span class="dashicons dashicons-media-document"></span>
								</a>
							</li>
							<?php $wp_plugin_pro_link = apply_filters( 'wp_pro_plugin_link', '' ); ?>
							<?php if ( isset( $wp_plugin_pro_link ) && '' != $wp_plugin_pro_link ) { ?>
								<li class="mwb-wp-main-menu-button">
									<a id="mwb-wp-go-pro-link" href="<?php echo esc_url( $wp_plugin_pro_link ); ?>" class="" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'wpcron-plugin' ); ?></a>
								</li>
							<?php } else { ?>
								<li class="mwb-wp-main-menu-button">
									<a id="mwb-wp-go-pro-link" href="#" class="" title=""><?php esc_html_e( 'GO PRO NOW', 'wpcron-plugin' ); ?></a>
								</li>
							<?php } ?>
							<?php $wp_plugin_pro = apply_filters( 'wp_pro_plugin_purcahsed', 'no' ); ?>
							<?php if ( isset( $wp_plugin_pro ) && 'yes' == $wp_plugin_pro ) { ?>
								<li>
									<a id="mwb-wp-skype-link" href="<?php echo esc_url( 'https://join.skype.com/invite/IKVeNkLHebpC' ); ?>" target="_blank">
										<img src="<?php echo esc_url( WPCRON_PLUGIN_DIR_URL . 'admin/images/skype_logo.png' ); ?>" style="height: 15px;width: 15px;" ><?php esc_html_e( 'Chat Now', 'wpcron-plugin' ); ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="mwb-wp-main-template">
			<div class="mwb-wp-body-template">
				<div class="mwb-wp-navigator-template">
					<div class="mwb-wp-navigations">
						<?php
						if ( is_array( $wp_default_tabs ) && ! empty( $wp_default_tabs ) ) {

							foreach ( $wp_default_tabs as $wp_tab_key => $wp_default_tabs ) {

								$wp_tab_classes = 'mwb-wp-nav-tab ';

								if ( ! empty( $wp_active_tab ) && $wp_active_tab === $wp_tab_key ) {
									$wp_tab_classes .= 'wp-nav-tab-active';
								}
								?>
								
								<div class="mwb-wp-tabs">
									<a class="<?php echo esc_attr( $wp_tab_classes ); ?>" id="<?php echo esc_attr( $wp_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=wpcron_plugin_menu' ) . '&wp_tab=' . esc_attr( $wp_tab_key ) ); ?>"><?php echo esc_html( $wp_default_tabs['title'] ); ?></a>
								</div>

								<?php
							}
						}
						?>
					</div>
				</div>

				<div class="mwb-wp-content-template">
					<div class="mwb-wp-content-container">
						<?php
							// if submenu is directly clicked on woocommerce.
						if ( empty( $wp_active_tab ) ) {

							$wp_active_tab = 'mwb_wp_plug_general';
						}

							// look for the path based on the tab id in the admin templates.
						$wp_tab_content_path = 'admin/partials/' . $wp_active_tab . '.php';

						$wp_mwb_wp_obj->mwb_wp_plug_load_template( $wp_tab_content_path );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
