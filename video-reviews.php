<?php

/**
 * Plugin Name: Video Reviews
 * Plugin URI: #
 * Description: Video widget at the bottom of your website. An easy way to stand out
 * Author: Aleksan Aharonyan
 * Author URI: https://github.com/Aharonyan/
 * Developer: Aleksan Aharonyan
 * Developer URI: https://github.com/Aharonyan/
 * Text Domain: video-reviews
 * Domain Path: /languages
 * PHP requires at least: 5.7
 * WP requires at least: 5.0
 * Tested up to: 6.7.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.5.3
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
/**
 * Activating plugin after freemius logic
 */
if ( function_exists( 'vd_rv' ) ) {
    vd_rv()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'vd_rv' ) ) {
        // Create a helper function for easy SDK access.
        function vd_rv() {
            global $vd_rv;
            if ( !isset( $vd_rv ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $vd_rv = fs_dynamic_init( array(
                    'id'              => '8452',
                    'slug'            => 'video-reviews',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_99d4298e873ae34cb137dd88edc54',
                    'is_premium'      => false,
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                        'days'               => 3,
                        'is_require_payment' => true,
                    ),
                    'has_affiliation' => 'customers',
                    'menu'            => array(
                        'slug'       => 'video-reviews',
                        'first-path' => 'admin.php?page=video-reviews',
                    ),
                    'is_live'         => true,
                ) );
            }
            return $vd_rv;
        }

        // Init Freemius.
        vd_rv();
        // Signal that SDK was initiated.
        do_action( 'vd_rv_loaded' );
    }
    require_once dirname( __FILE__ ) . '/VideoReviewsInit.php';
}