<?php

/**
 * The Product Post Type class file
 *
 * Defines the functions necessary to register the 'product' post type with
 * WordPress.
 *
 * @since      1.0.0
 *
 * @package    Wp_Ali_Info
 * @subpackage Wp_Ali_Info/admin
 * @author     Bob van Donselaar
 */

require_once('class-aliexpress-client.php');

class product_post_type {



    public function init() {
        add_action( 'init', array( $this, 'register_products' ) );
        add_action('admin_menu', array( $this, 'add_products_box' ) );
    }

    /**
    * Registers the Product Post type.
    *
    * @since    1.0.0
    */
    public function register_products() {
        if ( !post_type_exists( 'products' ) ) {
            register_post_type( 'products',
                array(
                'labels' => array(
                    'name' => __( 'Products' ),
                    'singular_name' => __( 'Product' )
                ),
                'public' => true,
                'has_archive' => true,
                )
            );
         }
    }


    public function add_products_box() {
        add_meta_box('product_box_ID', __('AliExpress Product Information'), array( $this, 'product_box_styling' ), 'products', 'side', 'core');
    }

    // This function gets called in edit-form-advanced.php
    public function product_box_styling($post) {
        ?>
        <p>
            <label class="movie-info-search-label" for="post_product"><?php _e( "Search for a Product:", 'wp-ali-info' ); ?></label>
            <br />
            <input
                class="wp-ali-info-search-field"
                type="text"
                placeholder="<?php _e( "AliExpress Product ID", 'wp-ali-info' ); ?>"
                id="post_product"
                value="32774104722"
                size="16" />
            <a class="button" id="wp-ali-info-search-button">
                <span class="dashicons dashicons-search"></span>
                <?php _e( "Search", 'wp-ali-info' ); ?>
            </a>
        </p>
        <?php
   }




}


