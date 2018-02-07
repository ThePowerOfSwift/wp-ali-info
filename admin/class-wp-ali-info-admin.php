<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/BobvD
 * @since      1.0.0
 *
 * @package    Wp_Ali_Info
 * @subpackage Wp_Ali_Info/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Ali_Info
 * @subpackage Wp_Ali_Info/admin
 * @author     Bob van Donselaar <b.vandonselaar@student.fontys.nl>
 */

require_once( dirname( __FILE__ ) . '/class-aliexpress-client.php' );

class Wp_Ali_Info_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->create_product_post_type();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ali_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ali_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-ali-info-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ali_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ali_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'moment', plugin_dir_url( __FILE__ ) . 'js/moment.js', true );
		wp_enqueue_script( 'ChartJS', plugin_dir_url( __FILE__ ) . 'js/Chart.min.js', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-ali-info-admin.js', array( 'jquery' ), $this->version, true );

	}


	public function create_product_post_type(){

		/** Loads the products post type class file. */
		require_once( dirname( __FILE__ ) . '/class-product-post-type.php' );
		$product = new product_post_type();
		$product->init();

	}

	public function ajax_product_search() {

		$aliexpress_client = new AliExpressClient();

		$productId = $_POST['product'];

		echo json_encode($aliexpress_client->searchProductByID($productId));

		 //encode into JSON format and output
		die(); //stop "0" from being output
	}



	function wp_ali_product_prices_cron_function(){
		//send email
		wp_mail('bobvandonselaar@gmail.com', 'Clivern', 'Well Done!');
		// Update post 37
		$this->update_product_prices();
	}

	function create_wp_ali_product_prices_schedule(){
		//check if event scheduled before
		if(!wp_next_scheduled('wp_ali_product_prices_cron_job'))
		//shedule event to run after every hour
		wp_schedule_event (time(), 'daily', 'wp_ali_product_prices_cron_job');
	}

	function update_product_prices(){

		$aliexpress_client = new AliExpressClient();

		$products = get_posts(array('post_type' => 'products'));
		foreach($products as $product){
			$productId = get_post_meta($product->ID, 'product-id', true);
			update_post_meta( $product->ID, 'product-price',
				$aliexpress_client->getProductPrice($productId));
		}
	}


}
