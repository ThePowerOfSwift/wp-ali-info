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
        add_action("save_post", array( $this, 'save_products_box' ) );
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
                        'singular_name' => __( 'Product' ),
                        'all_items'           => __( 'All Products')
                    ),
                    'public' => true,
                    'taxonomies'  => array( 'category' ),
                    'has_archive' => true,
                    'rewrite' => array('slug' => 'products'),
                    'supports' => array( 'title', 'thumbnail' ),
                    'menu_icon'           => 'dashicons-cart',
                    'menu_position'       => 4
                )
            );
         }
    }


    public function add_products_box() {
        add_meta_box('product_box_ID', __('AliExpress Product Information'), array( $this, 'product_box_styling' ), 'products', 'side', 'core');
    }

    // This function gets called in edit-form-advanced.php
    public function product_box_styling($post) {
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");
        global $post;

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
        <!--- Product ID -->
        <div style="margin-top: 10px">
            <label for="product-id" style="display: inline-block; width: 20%!important;
				 margin-right: 20px; vertical-align: top;"><b>Product ID </b></label>
            <input name="product-id" type="text"
                value="<?php echo get_post_meta($post->ID, "product-id", true); ?>"
                style="display: inline-block; width: 30%; margin-right: 20px;" />
            <a href="#">link to product image</a>
        </div>
        <!--- Product Title -->
        <div style="margin-top: 10px">
            <label for="product-name" style="display: inline-block; width: 20%!important;
				 margin-right: 20px; vertical-align: top;"><b>Product Name </b></label>
            <input name="product-name" type="text"
            value="<?php echo get_post_meta($post->ID, "product-name", true); ?>"
                style="display: inline-block; width: 60%;" />
        </div>
        <!--- Product Description -->
        <div style="margin-top: 10px">
            <label for="product-description" style="display: inline-block; width: 20%!important;
				 margin-right: 20px; vertical-align: top;"><b>Description </b></label>
            <textarea rows=4 name="product-description" type="text" style="display: inline-block; width: 60%;" ><?php
                echo get_post_meta($post->ID, "product-description", true);
            ?></textarea>
        </div>
        <!--- Price -->
        <div style="margin-top: 10px">
            <label for="product-price" style="display: inline-block; width: 20%!important;
            margin-right: 20px; vertical-align: top;"><b>Price </b></label>
            <input name="product-price" type="text"
                value="<?php echo get_post_meta($post->ID, "product-price", true); ?>"
                style="display: inline-block; width: 30%;" />
        </div>
        <!--- Discount -->
        <div style="margin-top: 10px">
            <label for="product-discount" style="display: inline-block; width: 20%!important;
            margin-right: 20px; vertical-align: top;"><b>Discount </b></label>
            <input name="product-discount" type="text"
            value="<?php echo get_post_meta($post->ID, "product-discount", true); ?>"
                style="display: inline-block; width: 30%;" />
        </div>
        <!--- Volume (Sales in past 24hr) -->
        <div style="margin-top: 10px">
            <label for="product-volume" style="display: inline-block; width: 20%!important;
            margin-right: 20px; vertical-align: top;"><b>Volume (Sales in past 24hr) </b></label>
            <input name="product-volume" type="text"
                value="<?php echo get_post_meta($post->ID, "product-volume", true); ?>"
                style="display: inline-block; width: 30%;" />
        </div>
        <!--- Affiliate Link -->
        <div style="margin-top: 10px">
            <label for="product-affiliate-link" style="display: inline-block; width: 20%!important;
            margin-right: 20px; vertical-align: top;"><b>Affiliate Link </b></label>
            <input name="product-affiliate-link" type="text"
            value="<?php echo get_post_meta($post->ID, "product-affiliate-link", true); ?>"
                style="display: inline-block; width: 60%;" />
        </div>
        <!--- Product Link -->
        <div style="margin-top: 10px">
            <label for="product-product-link" style="display: inline-block; width: 20%!important;
            margin-right: 20px; vertical-align: top;"><b>Product Link </b></label>
            <input name="product-product-link" type="text"
            value="<?php echo get_post_meta($post->ID, "product-product-link", true); ?>"
                style="display: inline-block; width: 60%;" />
        </div>
        <!--- Short Affiliate Link -->
                <div style="margin-top: 10px">
            <label for="product-short-affiliate-link" style="display: inline-block; width: 20%!important;
            margin-right: 20px; vertical-align: top;"><b>Short Affiliate Link </b></label>
            <input name="product-short-affiliate-link" type="text"
            value="<?php echo get_post_meta($post->ID, "product-short-affiliate-link", true); ?>"
                style="display: inline-block; width: 60%;" />
        </div>

        <?php
    }

    function save_products_box($post_id, $post, $update ) {

        if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

        if(!current_user_can("edit_post", $post_id))
            return $post_id;

        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
            return $post_id;

        /*
        $slug = "products";
        if($slug != $post->post_type)
            return $post_id;
        */

        $meta_box_text_value = "";

        // Product ID
        if(isset($_POST["product-id"]))
        {
            $meta_box_text_value = $_POST["product-id"];
        }
        update_post_meta($post_id, "product-id", $meta_box_text_value);
        // Product Title
        if(isset($_POST["product-title"]))
        {
            $meta_box_text_value = $_POST["product-title"];
        }
        update_post_meta($post_id, "product-title", $meta_box_text_value);
        // Product Name
        if(isset($_POST["product-name"]))
        {
            $meta_box_text_value = $_POST["product-name"];
        }
        update_post_meta($post_id, "product-name", $meta_box_text_value);
        // Product Description
        if(isset($_POST["product-description"]))
        {
            $meta_box_text_value = $_POST["product-description"];
        }
        update_post_meta($post_id, "product-description", $meta_box_text_value);
        // Price
        if(isset($_POST["product-price"]))
        {
            $meta_box_text_value = $_POST["product-price"];
        }
        update_post_meta($post_id, "product-price", $meta_box_text_value);
        // Discount
        if(isset($_POST["product-discount"]))
        {
            $meta_box_text_value = $_POST["product-discount"];
        }
        update_post_meta($post_id, "product-discount", $meta_box_text_value);
        // Volume (Sales in past 24hr)
        if(isset($_POST["product-volume"]))
        {
            $meta_box_text_value = $_POST["product-volume"];
        }
        update_post_meta($post_id, "product-volume", $meta_box_text_value);
        // Affiliate Link
        if(isset($_POST["product-affiliate-link"]))
        {
            $meta_box_text_value = $_POST["product-affiliate-link"];
        }
        update_post_meta($post_id, "product-affiliate-link", $meta_box_text_value);
        // Product Link
        if(isset($_POST["product-product-link"]))
        {
            $meta_box_text_value = $_POST["product-product-link"];
        }
        update_post_meta($post_id, "product-product-link", $meta_box_text_value);
        // Short Affiliate Link
        if(isset($_POST["product-short-affiliate-link"]))
        {
            $meta_box_text_value = $_POST["product-short-affiliate-link"];
        }
        update_post_meta($post_id, "product-short-affiliate-link", $meta_box_text_value);

    }

}


