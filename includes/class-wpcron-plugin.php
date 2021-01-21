<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wpcron_Plugin
 * @subpackage Wpcron_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpcron_Plugin
 * @subpackage Wpcron_Plugin/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Wpcron_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpcron_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'WPCRON_PLUGIN_VERSION' ) ) {

			$this->version = WPCRON_PLUGIN_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'wpcron-plugin';

		$this->wpcron_plugin_dependencies();
		$this->wpcron_plugin_locale();
		$this->wpcron_plugin_admin_hooks();
		$this->wpcron_plugin_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpcron_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Wpcron_Plugin_i18n. Defines internationalization functionality.
	 * - Wpcron_Plugin_Admin. Defines all hooks for the admin area.
	 * - Wpcron_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wpcron_plugin_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpcron-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpcron-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpcron-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpcron-plugin-public.php';

		$this->loader = new Wpcron_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpcron_Plugin_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wpcron_plugin_locale() {

		$plugin_i18n = new Wpcron_Plugin_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wpcron_plugin_admin_hooks() {

		$wp_plugin_admin = new Wpcron_Plugin_Admin( $this->wp_get_plugin_name(), $this->wp_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $wp_plugin_admin, 'wp_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $wp_plugin_admin, 'wp_admin_enqueue_scripts' );

		// Add settings menu for wpcron plugin.
		$this->loader->add_action( 'admin_menu', $wp_plugin_admin, 'wp_options_page' );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $wp_plugin_admin, 'wp_admin_submenu_page', 15 );
		$this->loader->add_filter( 'wp_general_settings_array', $wp_plugin_admin, 'wp_admin_general_settings_page', 10 );

		// Register custom post type 'product'.
		$this->loader->add_action( 'init', $wp_plugin_admin, 'product_custom_post_type' );

		// Add custom box for product post type.
		$this->loader->add_action( 'add_meta_boxes', $wp_plugin_admin, 'product_add_custom_box' );

		// Add custom Box's Field's data into db.
		$this->loader->add_action( 'save_post', $wp_plugin_admin, 'product_save_postdata' );

		// Automatic Flush rewrite rules.
		$this->loader->add_action( 'init', $wp_plugin_admin, 'flush_rewrite_rules' );

		// Custom 5 min Scheduler.
		$this->loader->add_filter( 'cron_schedules', $wp_plugin_admin, 'bl_add_cron_intervals' );

		$this->loader->add_action( 'init', $wp_plugin_admin, 'check_next_schedule' );

		// Custom hook use to execute bl_cron_exec.
		$this->loader->add_action( 'bl_cron_hook', $wp_plugin_admin, 'bl_cron_exec' );

		//$this->loader->add_action( 'init', $wp_plugin_admin, 'add_complete_post' );	

		// Add export button into post product type.
		$this->loader->add_action( 'manage_posts_extra_tablenav', $wp_plugin_admin, 'admin_post_list_add_export_button', 20, 1 );

		$this->loader->add_action( 'init', $wp_plugin_admin, 'func_export_all_posts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wpcron_plugin_public_hooks() {

		$wp_plugin_public = new Wpcron_Plugin_Public( $this->wp_get_plugin_name(), $this->wp_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $wp_plugin_public, 'wp_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $wp_plugin_public, 'wp_public_enqueue_scripts' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wp_run() {
		$this->loader->wp_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function wp_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wpcron_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function wp_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function wp_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_wp_plug tabs.
	 *
	 * @return  Array       An key=>value pair of wpcron plugin tabs.
	 */
	public function mwb_wp_plug_default_tabs() {

		$wp_default_tabs = array();

		$wp_default_tabs['wpcron-plugin-general'] = array(
			'title'       => esc_html__( 'General Setting', 'wpcron-plugin' ),
			'name'        => 'wpcron-plugin-general',
		);
		$wp_default_tabs = apply_filters( 'mwb_wp_plugin_standard_admin_settings_tabs', $wp_default_tabs );

		$wp_default_tabs['wpcron-plugin-system-status'] = array(
			'title'       => esc_html__( 'System Status', 'wpcron-plugin' ),
			'name'        => 'wpcron-plugin-system-status',
		);

		return $wp_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_wp_plug_load_template( $path, $params = array() ) {

		$wp_file_path = WPCRON_PLUGIN_DIR_PATH . $path;

		if ( file_exists( $wp_file_path ) ) {

			include $wp_file_path;
		} else {

			/* translators: %s: file path */
			$wp_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'wpcron-plugin' ), $wp_file_path );
			$this->mwb_wp_plug_admin_notice( $wp_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $wp_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_wp_plug_admin_notice( $wp_message, $type = 'error' ) {

		$wp_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$wp_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$wp_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$wp_classes .= 'notice-success is-dismissible';
				break;

			default:
				$wp_classes .= 'notice-error is-dismissible';
		}

		$wp_notice  = '<div class="' . esc_attr( $wp_classes ) . '">';
		$wp_notice .= '<p>' . esc_html( $wp_message ) . '</p>';
		$wp_notice .= '</div>';

		echo wp_kses_post( $wp_notice );
	}


	/**
	 * Show wordpress and server info.
	 *
	 * @return  Array $wp_system_data       returns array of all wordpress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_wp_plug_system_status() {
		global $wpdb;
		$wp_system_status = array();
		$wp_wordpress_status = array();
		$wp_system_data = array();

		// Get the web server.
		$wp_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$wp_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'wpcron-plugin' );

		// Get the server's IP address.
		$wp_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$wp_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$wp_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'wpcron-plugin' );

		// Get the server path.
		$wp_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'wpcron-plugin' );

		// Get the OS.
		$wp_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'wpcron-plugin' );

		// Get WordPress version.
		$wp_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'wpcron-plugin' );

		// Get and count active WordPress plugins.
		$wp_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'wpcron-plugin' );

		// See if this site is multisite or not.
		$wp_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'wpcron-plugin' ) : __( 'No', 'wpcron-plugin' );

		// See if WP Debug is enabled.
		$wp_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'wpcron-plugin' ) : __( 'No', 'wpcron-plugin' );

		// See if WP Cache is enabled.
		$wp_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'wpcron-plugin' ) : __( 'No', 'wpcron-plugin' );

		// Get the total number of WordPress users on the site.
		$wp_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'wpcron-plugin' );

		// Get the number of published WordPress posts.
		$wp_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'wpcron-plugin' );

		// Get PHP memory limit.
		$wp_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'wpcron-plugin' );

		// Get the PHP error log path.
		$wp_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'wpcron-plugin' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$wp_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'wpcron-plugin' );

		// Get PHP max post size.
		$wp_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'wpcron-plugin' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$wp_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$wp_system_status['php_architecture'] = '64-bit';
		} else {
			$wp_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$wp_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'wpcron-plugin' );

		// Show the number of processes currently running on the server.
		$wp_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'wpcron-plugin' );

		// Get the memory usage.
		$wp_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$wp_system_status['is_windows'] = true;
			$wp_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'wpcron-plugin' );
		}

		// Get the memory limit.
		$wp_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'wpcron-plugin' );

		// Get the PHP maximum execution time.
		$wp_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'wpcron-plugin' );

		// Get outgoing IP address.
		$wp_system_status['outgoing_ip'] = function_exists( 'file_get_contents' ) ? file_get_contents( 'http://ipecho.net/plain' ) : __( 'N/A (file_get_contents function does not exist)', 'wpcron-plugin' );

		$wp_system_data['php'] = $wp_system_status;
		$wp_system_data['wp'] = $wp_wordpress_status;

		return $wp_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $wp_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_wp_plug_generate_html( $wp_components = array() ) {
		if ( is_array( $wp_components ) && ! empty( $wp_components ) ) {
			foreach ( $wp_components as $wp_component ) {
				switch ( $wp_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'password':
					case 'email':
					case 'text':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $wp_component['id'] ); ?>"><?php echo esc_html( $wp_component['title'] ); // WPCS: XSS ok. ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $wp_component['type'] ) ); ?>">
								<input
								name="<?php echo esc_attr( $wp_component['id'] ); ?>"
								id="<?php echo esc_attr( $wp_component['id'] ); ?>"
								type="<?php echo esc_attr( $wp_component['type'] ); ?>"
								value="<?php echo esc_attr( $wp_component['value'] ); ?>"
								class="<?php echo esc_attr( $wp_component['class'] ); ?>"
								placeholder="<?php echo esc_attr( $wp_component['placeholder'] ); ?>"
								/>
								<p class="wp-descp-tip"><?php echo esc_html( $wp_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'textarea':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $wp_component['id'] ); ?>"><?php echo esc_html( $wp_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $wp_component['type'] ) ); ?>">
								<textarea
								name="<?php echo esc_attr( $wp_component['id'] ); ?>"
								id="<?php echo esc_attr( $wp_component['id'] ); ?>"
								class="<?php echo esc_attr( $wp_component['class'] ); ?>"
								rows="<?php echo esc_attr( $wp_component['rows'] ); ?>"
								cols="<?php echo esc_attr( $wp_component['cols'] ); ?>"
								placeholder="<?php echo esc_attr( $wp_component['placeholder'] ); ?>"
								><?php echo esc_textarea( $wp_component['value'] ); // WPCS: XSS ok. ?></textarea>
								<p class="wp-descp-tip"><?php echo esc_html( $wp_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $wp_component['id'] ); ?>"><?php echo esc_html( $wp_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $wp_component['type'] ) ); ?>">
								<select
								name="<?php echo esc_attr( $wp_component['id'] ); ?><?php echo ( 'multiselect' === $wp_component['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $wp_component['id'] ); ?>"
								class="<?php echo esc_attr( $wp_component['class'] ); ?>"
								<?php echo 'multiselect' === $wp_component['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ( $wp_component['options'] as $wp_key => $wp_val ) {
									?>
									<option value="<?php echo esc_attr( $wp_key ); ?>"
										<?php
										if ( is_array( $wp_component['value'] ) ) {
											selected( in_array( (string) $wp_key, $wp_component['value'], true ), true );
										} else {
											selected( $wp_component['value'], (string) $wp_key );
										}
										?>
										>
										<?php echo esc_html( $wp_val ); ?>
									</option>
									<?php
								}
								?>
								</select> 
								<p class="wp-descp-tip"><?php echo esc_html( $wp_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'checkbox':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo esc_html( $wp_component['title'] ); ?></th>
							<td class="forminp forminp-checkbox">
								<label for="<?php echo esc_attr( $wp_component['id'] ); ?>"></label>
								<input
								name="<?php echo esc_attr( $wp_component['id'] ); ?>"
								id="<?php echo esc_attr( $wp_component['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $wp_component['class'] ) ? $wp_component['class'] : '' ); ?>"
								value="1"
								<?php checked( $wp_component['value'], '1' ); ?>
								/> 
								<span class="wp-descp-tip"><?php echo esc_html( $wp_component['description'] ); // WPCS: XSS ok. ?></span>

							</td>
						</tr>
						<?php
						break;

					case 'radio':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $wp_component['id'] ); ?>"><?php echo esc_html( $wp_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $wp_component['type'] ) ); ?>">
								<fieldset>
									<span class="wp-descp-tip"><?php echo esc_html( $wp_component['description'] ); // WPCS: XSS ok. ?></span>
									<ul>
										<?php
										foreach ( $wp_component['options'] as $wp_radio_key => $wp_radio_val ) {
											?>
											<li>
												<label><input
													name="<?php echo esc_attr( $wp_component['id'] ); ?>"
													value="<?php echo esc_attr( $wp_radio_key ); ?>"
													type="radio"
													class="<?php echo esc_attr( $wp_component['class'] ); ?>"
												<?php checked( $wp_radio_key, $wp_component['value'] ); ?>
													/> <?php echo esc_html( $wp_radio_val ); ?></label>
											</li>
											<?php
										}
										?>
									</ul>
								</fieldset>
							</td>
						</tr>
						<?php
						break;

					case 'button':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="button" class="button button-primary" 
								name="<?php echo esc_attr( $wp_component['id'] ); ?>"
								id="<?php echo esc_attr( $wp_component['id'] ); ?>"
								value="<?php echo esc_attr( $wp_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					case 'submit':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo esc_attr( $wp_component['id'] ); ?>"
								id="<?php echo esc_attr( $wp_component['id'] ); ?>"
								value="<?php echo esc_attr( $wp_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}
}
