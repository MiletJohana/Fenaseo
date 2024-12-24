<?php

/**
 * WooCommerce B2B Common hooks to improve compatibility with third parts assets
 *
 * @version 3.3.7
 */

defined( 'ABSPATH' ) || exit;

/**
 * WCB2B_Compatibility Class
 */
class WCB2B_Compatibility {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Init current class hooks
     */
    public function init_hooks() {
        add_filter( 'wcb2b_always_visible_pages', array( $this, 'wpml_always_visible_pages' ), 10 ); // WPML
        add_action( 'wp', array( $this, 'elementor_preview_mode' ) ); // Elementor
    }

    /**
     * Used to fix WPML problem with always visible pages
     *
     * @param array $args Always visible pages
     * @return array
     */
    public function wpml_always_visible_pages( $pages ) {
        if ( function_exists( 'wpml_object_id' ) ) {
            array_walk( $pages, function( &$item ) {
                $item = apply_filters( 'wpml_object_id', $item, 'post', false );
            } );
        }
        return $pages;
    }

    /**
     * Remove redirect in login forms when in Elementor preview mode
     */
    public function elementor_preview_mode() {
        if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            global $wp_filter;
            if ( isset( $wp_filter['template_redirect']->callbacks[5] ) && !empty( $wp_filter['template_redirect']->callbacks[5] ) ) {
                $wp_filter['template_redirect']->callbacks[5] = array_filter( $wp_filter['template_redirect']->callbacks[5], function( $v, $k ) {
                    return ( stripos( $k, 'redirect_login_forms' ) === false );
                }, ARRAY_FILTER_USE_BOTH );
            }
        }
    }

}

return new WCB2B_Compatibility();