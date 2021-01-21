<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wpcron_Plugin
 * @subpackage Wpcron_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpcron_Plugin
 * @subpackage Wpcron_Plugin/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Wpcron_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function wp_admin_enqueue_styles( $hook ) {

		wp_enqueue_style( 'mwb-wp-select2-css', WPCRON_PLUGIN_DIR_URL . 'admin/css/wpcron-plugin-select2.css', array(), time(), 'all' );

		wp_enqueue_style( $this->plugin_name, WPCRON_PLUGIN_DIR_URL . 'admin/css/wpcron-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function wp_admin_enqueue_scripts( $hook ) {

		wp_enqueue_script( 'mwb-wp-select2', WPCRON_PLUGIN_DIR_URL . 'admin/js/wpcron-plugin-select2.js', array( 'jquery' ), time(), false );

		wp_register_script( $this->plugin_name . 'admin-js', WPCRON_PLUGIN_DIR_URL . 'admin/js/wpcron-plugin-admin.js', array( 'jquery', 'mwb-wp-select2' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'admin-js',
			'wp_admin_param',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'reloadurl' => admin_url( 'admin.php?page=wpcron_plugin_menu' ),
			)
		);

		wp_enqueue_script( $this->plugin_name . 'admin-js' );
	}

	/**
	 * Adding settings menu for wpcron plugin.
	 *
	 * @since    1.0.0
	 */
	public function wp_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'wpcron-plugin' ), __( 'MakeWebBetter', 'wpcron-plugin' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), WPCRON_PLUGIN_DIR_URL . 'admin/images/mwb-logo.png', 15 );
			$wp_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $wp_menus ) && ! empty( $wp_menus ) ) {
				foreach ( $wp_menus as $wp_key => $wp_value ) {
					add_submenu_page( 'mwb-plugins', $wp_value['name'], $wp_value['name'], 'manage_options', $wp_value['menu_link'], array( $wp_value['instance'], $wp_value['function'] ) );
				}
			}
		}
	}


	/**
	 * wpcron plugin wp_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function wp_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'            => __( 'wpcron plugin', 'wpcron-plugin' ),
			'slug'            => 'wpcron_plugin_menu',
			'menu_link'       => 'wpcron_plugin_menu',
			'instance'        => $this,
			'function'        => 'wp_options_menu_html',
		);
		return $menus;
	}


	/**
	 * wpcron plugin mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require WPCRON_PLUGIN_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * wpcron plugin admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function wp_options_menu_html() {

		include_once WPCRON_PLUGIN_DIR_PATH . 'admin/partials/wpcron-plugin-admin-display.php';
	}

	/**
	 * wpcron plugin admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $wp_settings_general Settings fields.
	 */
	public function wp_admin_general_settings_page( $wp_settings_general ) {
		$wp_settings_general = array(
			array(
				'title' => __( 'Text Field Demo', 'wpcron-plugin' ),
				'type'  => 'text',
				'description'  => __( 'This is text field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_text_demo',
				'value' => '',
				'class' => 'wp-text-class',
				'placeholder' => __( 'Text Demo', 'wpcron-plugin' ),
			),
			array(
				'title' => __( 'Number Field Demo', 'wpcron-plugin' ),
				'type'  => 'number',
				'description'  => __( 'This is number field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_number_demo',
				'value' => '',
				'class' => 'wp-number-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Password Field Demo', 'wpcron-plugin' ),
				'type'  => 'password',
				'description'  => __( 'This is password field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_password_demo',
				'value' => '',
				'class' => 'wp-password-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Textarea Field Demo', 'wpcron-plugin' ),
				'type'  => 'textarea',
				'description'  => __( 'This is textarea field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_textarea_demo',
				'value' => '',
				'class' => 'wp-textarea-class',
				'rows' => '5',
				'cols' => '10',
				'placeholder' => __( 'Textarea Demo', 'wpcron-plugin' ),
			),
			array(
				'title' => __( 'Select Field Demo', 'wpcron-plugin' ),
				'type'  => 'select',
				'description'  => __( 'This is select field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_select_demo',
				'value' => '',
				'class' => 'wp-select-class',
				'placeholder' => __( 'Select Demo', 'wpcron-plugin' ),
				'options' => array(
					'INR' => __( 'Rs.', 'wpcron-plugin' ),
					'USD' => __( '$', 'wpcron-plugin' ),
				),
			),
			array(
				'title' => __( 'Multiselect Field Demo', 'wpcron-plugin' ),
				'type'  => 'multiselect',
				'description'  => __( 'This is multiselect field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_multiselect_demo',
				'value' => '',
				'class' => 'wp-multiselect-class mwb-defaut-multiselect',
				'placeholder' => __( 'Multiselect Demo', 'wpcron-plugin' ),
				'options' => array(
					'INR' => __( 'Rs.', 'wpcron-plugin' ),
					'USD' => __( '$', 'wpcron-plugin' ),
				),
			),
			array(
				'title' => __( 'Checkbox Field Demo', 'wpcron-plugin' ),
				'type'  => 'checkbox',
				'description'  => __( 'This is checkbox field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_checkbox_demo',
				'value' => '',
				'class' => 'wp-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'wpcron-plugin' ),
			),

			array(
				'title' => __( 'Radio Field Demo', 'wpcron-plugin' ),
				'type'  => 'radio',
				'description'  => __( 'This is radio field demo follow same structure for further use.', 'wpcron-plugin' ),
				'id'    => 'wp_radio_demo',
				'value' => '',
				'class' => 'wp-radio-class',
				'placeholder' => __( 'Radio Demo', 'wpcron-plugin' ),
				'options' => array(
					'yes' => __( 'YES', 'wpcron-plugin' ),
					'no' => __( 'NO', 'wpcron-plugin' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'wp_button_demo',
				'button_text' => __( 'Button Demo', 'wpcron-plugin' ),
				'class' => 'wp-button-class',
			),
		);
		return $wp_settings_general;
	}

	/**
	 * Function to register custom post type 'product'
	 *
	 * @return void
	 */
	public function product_custom_post_type() {
		$labels = array(
			'name'                  => __( 'Products', 'wpcron-plugin' ),
			'singular_name'         => __( 'Product', 'wpcron-plugin' ),
			'featured_image'        => __( 'Product Logo', 'wpcron-plugin' ),
			'set_featured_image'    => __( 'Set Product Logo', 'wpcron-plugin' ),
			'remove_featured_image' => __( 'Remove Product Logo', 'wpcron-plugin' ),
			'use_featured_image'    => __( 'Use Logo', 'wpcron-plugin' ),
			'add_new'               => __( 'Add New Product', 'wpcron-plugin' ),
			'add_new_item'          => __( 'Add New Product', 'wpcron-plugin' ),
			'archives'              => __( 'Product Directory ', 'wpcron-plugin' ),
		);
		$args   = array(
			'labels'      => $labels,
			'public'      => true,
			'has_archive' => 'products',
			'rewrite'     => array( 'slug' => 'product' ),
			'menu_icon'   => 'dashicons-products',
			'supports'    => array( 'title', 'editor', 'thumbnail' ),
		);

		register_post_type( 'product', $args );
	}

	/**
	 * Function to flush rewrite rule during registration of custom post type.
	 *
	 * @return void
	 */
	public function flush_rewrite_rules() {
		$this->product_custom_post_type();
		flush_rewrite_rules();
	}

	/**
	 * Add custom box for product post type.
	 */
	public function product_add_custom_box() {

		add_meta_box(
			'product_box_id', // Unique ID.
			'Product Meta Field', // Box title.
			array( $this, 'product_custom_box_html' ), // Content callback, must be of type callable.
			'product', // Post type.
		);

	}

	/**
	 * Callback function to show required field into custom box.
	 *
	 * @param [type] $post is current post id.
	 * @return void
	 */
	public function product_custom_box_html( $post ) {
		$sku    = get_post_meta( $post->ID, '_product_sku_meta_key', true );
		$price  = get_post_meta( $post->ID, '_product_price_meta_key', true );
		$review = get_post_meta( $post->ID, '_product_review_meta_key', true );
		?>
			<label for="sku"><?php esc_html_e( 'SKU', 'wpcron-plugin' ); ?></label>
			<input type="text" name="sku" placeholder="Please Enter Product SKU" value="<?php ( $sku ) ? ( esc_html_e( $sku ) ) : ( '' ); ?>"><br><br>
			<label for="price"><?php esc_html_e( 'Price', 'wpcron-plugin' ); ?></label>
			<input type="text" name="price" placeholder="Please Enter Product Price" value="<?php ( $price ) ? ( esc_html_e( $price ) ) : ( '' ); ?>"><br><br>
			<label for="review"><?php esc_html_e( 'Reviews', 'wpcron-plugin' ); ?></label>
			<textarea name="review" placeholder="Please Write Review about Product" ><?php ( $review ) ? ( esc_html_e( $review ) ) : ( '' ); ?></textarea><br><br>	
		<?php
	}

	/**
	 * Function to save meta data in to post_meta table.
	 *
	 * @param [type] $post_id is current post id.
	 * @return void
	 */
	public function product_save_postdata( $post_id ) {
		if ( array_key_exists( 'sku', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_product_sku_meta_key',
				sanitize_text_field( wp_unslash( $_POST['sku'] ) ),
			);
		}
		if ( array_key_exists( 'price', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_product_price_meta_key',
				sanitize_text_field( wp_unslash( $_POST['price'] ) ),
			);
		}
		if ( array_key_exists( 'review', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_product_review_meta_key',
				sanitize_text_field( wp_unslash( $_POST['review'] ) ),
			);
		}
	}

	/**
	 * Function to add post through xml file.
	 *
	 * @return void
	 */
	public function add_complete_post(){
			$xml = simplexml_load_file( plugin_dir_path( dirname( __FILE__ ) ) . 'Fileread/dataset.xml' );
			$data = $xml->record;
			foreach ( $data as $record ) {
				$title  = strval( $record->name );
				$sku    = strval( $record->sku );
				$price  = strval( $record->price );
				$review = strval( $record->reviews );

				// Create post object.
				$my_post = array(
					'post_title'  => $title,
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'   => 'product',

				);
				// Insert the post into the database.
				$post_id = wp_insert_post( $my_post );
				add_post_meta( $post_id, '_product_sku_meta_key', $sku );
				add_post_meta( $post_id, '_product_price_meta_key', $price );
				add_post_meta( $post_id, '_product_review_meta_key', $review );

			}
	}

	/**
	 * Function for event interval.
	 *
	 * @param [type] $schedules store info aabout scheduler.
	 * @return $schedules
	 */
	public function bl_add_cron_intervals( $schedules ) {
		$schedules['5min'] = array( // Provide the programmatic name to be used in code.
			'interval' => 300, // Intervals are listed in seconds.
			'display'  => __( 'Every 5 Mins' ), // Easy to read display name.
		);
		return $schedules; // Do not forget to give back the list of schedules!
	}

	// check if event in queue.
	public function check_next_schedule(){
		if ( ! wp_next_scheduled( 'bl_cron_hook' ) ) {
			wp_schedule_event( time(), '5min', 'bl_cron_hook' ); // 5 min scheduler for event bl_cron_hook
		}
	}

	/**
	 * Function to insert batch post in DB.
	 */
	public function bl_cron_exec() {
		$handle = fopen( plugin_dir_path( dirname( __FILE__ ) ) . 'Fileread/MOCK_DATA.csv', 'r' );
		$array = array();

		$countterm = get_option( 'countterm' );
		if ( $countterm == '' ) {
			add_option( 'countterm', '0' );
		}

		$totalele = '0';

		while ( ! feof( $handle ) ) {
			$current = fgetcsv( $handle );
			array_push( $array, $current );
			$totalele++;
		}

		$count = get_option( 'countterm' );
		for ( $i = $count; $i < ( $count + 10 ); $i++ ) {
			if ( $i < $totalele ) {
				// Add post.
				$my_post = array(
					'post_title'  => $array[$i][1],
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'   => 'product',
				);
				// Insert the post meta into the database.
				$post_id = wp_insert_post( $my_post );

				add_post_meta( $post_id, '_product_sku_meta_key', $array[$i][2] );
				add_post_meta( $post_id, '_product_price_meta_key',$array[$i][3] );
				add_post_meta( $post_id, '_product_review_meta_key',$array[$i][4] );
				$rcount = get_option( 'countterm' );
				update_option( 'countterm', $rcount + 1 );
			}

		}
		fclose( $handle );
	}

	/**
	 * Check if schedule in queue.
	 *
	 * @return void
	 */
	public function export_csv_hook() {
		if ( ! wp_next_scheduled( 'export_all_posts' ) ) {
			wp_schedule_event( time(), '5min', 'export_all_posts' ); // 5 min scheduler for event bl_cron_hook
		}
	}

	/**
	 * Function to add a button in product post type page.
	 *
	 * @param [type] $which
	 * @return void
	 */
	public function admin_post_list_add_export_button( $which ) {
		global $typenow;
 
		if ( 'product' === $typenow && 'top' === $which ) {
			?>
			<input type="submit" name="export_all_posts" class="button button-primary" value="<?php _e( 'Export All Posts' ); ?>" />
			<?php
		}
	}

	// Function to export post.
	public function func_export_all_posts() {
		if ( isset ( $_GET['export_all_posts'] ) ) {
			$arg = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
			global $post;
			$arr_post = get_posts( $arg );
			if ( $arr_post ) {
				header( 'Content-type: text/csv' );
				header( 'Content-Disposition: attachment; filename="wp-posts.csv"' );
				header( 'Pragma: no-cache' );
				header( 'Expires: 0' );

				$file = fopen( plugin_dir_path( dirname( __FILE__ ) ) . 'Fileread/csvfile.csv', 'w');

				fputcsv( $file, array( 'Post Title', 'Price', 'Price', 'Review' ) );

				foreach ( $arr_post as $post ) {
					setup_postdata( $post );

					$sku = get_post_meta( $post->ID, '_product_sku_meta_key', true);
					$price = get_post_meta( $post->ID, '_product_price_meta_key', true);
					$review = get_post_meta( $post->ID, '_product_review_meta_key', true);

					fputcsv($file, array(get_the_title(), $sku, $price, $review ) );
				}

				exit();
			}
		}
	}
}
