<?php

namespace VDRV;

// Exit if accessed directly
defined('ABSPATH') || exit;

register_activation_hook(__FILE__, ['VDRV\VideoReviewsInit', 'vr_plugin_activate']);

register_deactivation_hook(__FILE__, ['VDRV\VideoReviewsInit', 'vr_plugin_deactivate']);

/**
 * Core
 */
class VideoReviewsInit
{

    /**
     * The init
     */
    public static function init()
    {

        define('VDRV_PLUGIN_URL', plugins_url('', __FILE__));
        define('VDRV_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
        define('VDRV_Template_PATH', plugin_dir_path(__FILE__) . 'templates/');
        define('VDRV_TEXT_DOMAIN', 'video-reviews');

        add_action('plugins_loaded', [__CLASS__, 'true_load_plugin_textdomain']);

        add_action('plugins_loaded', [__CLASS__, 'add_components']);

        add_filter('network_admin_plugin_action_links_video-reviews/video-reviews.php', [__CLASS__, 'filter_plugin_action_links']);
        add_filter('plugin_action_links_video-reviews/video-reviews.php',               [__CLASS__, 'filter_plugin_action_links']);

        /**
         * Redirect to settings page after installation
         */
        add_action('admin_init', [__CLASS__, 'my_plugin_redirect']);

        add_filter('vdrv_get_widget_settings', [__CLASS__, 'get_widget_settings']);

        add_action('rest_api_init', function () {
            register_rest_route('vdrv/v1', '/widget', [
                'methods'  => 'POST',
                'callback' => [__CLASS__, 'return_widget_html'],
                'permission_callback' => '__return_true'
            ]);
        });
    }


    public static function return_widget_html(\WP_REST_Request $request)
    {
        $body = $request->get_body();
        $vdrv_settings = json_decode($request->get_body(), true);

        ob_start();
        require VDRV_Template_PATH . 'video-widget.php';
        $widget = ob_get_clean();

        wp_send_json_success($widget);
    }

    /**
     * On plugin activate
     *
     * @return void
     */
    public static function vr_plugin_activate()
    {
        add_option('vdrv_plugin_do_activation_redirect', true);
        do_action('VDRV_activate');
    }

    /**
     * my_plugin_redirect
     */
    public static function my_plugin_redirect()
    {
        if (get_option('vdrv_plugin_do_activation_redirect', false)) {
            delete_option('vdrv_plugin_do_activation_redirect');
            $page = admin_url('admin.php?page=video-reviews');
            wp_redirect($page);
        }
    }

    /**
     * Add settings page link
     *
     * @param array $actions
     * @return void
     */
    public static function filter_plugin_action_links(array $actions)
    {
        $page = admin_url('admin.php?page=video-reviews');
        return array_merge(array(
            'settings' => sprintf('<a href="%s">%s</a>', $page, esc_html__('Settings', 'VDRV_TEXT_DOMAIN')),
        ), $actions);
    }

    /**
     * On plugin deactivate
     *
     * @return void
     */
    public static function vr_plugin_deactivate()
    {
        do_action('VDRV_deactivate');
        delete_option('vdrv_settings');
    }

    /**
     * Add Components
     */
    public static function add_components()
    {

        require VDRV_PLUGIN_DIR_PATH . '/includes/VDRVSettings.php';
        add_action('wp_enqueue_scripts', [__CLASS__, 'add_scripts']);
    }


    /**
     * Add languages
     *
     * @return void
     */
    public static function true_load_plugin_textdomain()
    {
        load_plugin_textdomain(VDRV_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Show widget or not
     *
     * @return array
     */
    public static function get_widget_settings($val)
    {
        $vdrv_settings = get_option('vdrv_settings');

        if (
            $vdrv_settings['display_video'] === 'advanced' &&
            !vd_rv()->is_paying_or_trial__premium_only()
        ) {
            $vdrv_settings['display_video'] = 'on_all_pages';
            update_option('vdrv_settings', $vdrv_settings);
        }

        if ($vdrv_settings['display_video'] === 'on_all_pages') {
            if (isset($vdrv_settings['video_to_show_on_this_page'])) {
                $vdrv_settings['video_to_show_on_this_page'] = '';
            }

            return ['success' => true, 'settings' => $vdrv_settings];
        }

        if ($vdrv_settings['display_video'] === 'advanced') {

            if (empty($vdrv_settings['vd_advanced'])) {
                return ['success' => false];
            }

            if (!is_array($vdrv_settings['vd_advanced'])) {
                return ['success' => false];
            }

            global $wp;
            $current_url = home_url(add_query_arg(array(), $wp->request));

            foreach ($vdrv_settings['vd_advanced'] as $video) {
                if (empty($video['page_url'])) {
                    continue;
                }
                if (self::are_urls_the_same($video['page_url'], $current_url)) {
                    $vdrv_settings['video_to_show_on_this_page'] = $video;
                    return ['success' => true, 'settings' => $vdrv_settings];
                }
            }
        }

        return ['success' => false];
    }

    /**
     * Check if urls the same
     *
     * @param string $url1
     * @param string $url2
     * @return bool
     */
    public static function are_urls_the_same($url_1, $url_2)
    {
        $url_1 = rtrim($url_1, "/");
        $url_2 = rtrim($url_2, "/");
        $mustMatch = array_flip(['host', 'port', 'path']);
        $defaults = ['path' => '/']; // if not present, assume these (consistency)
        $url_1 = array_intersect_key(parse_url($url_1), $mustMatch);
        $url_2 = array_intersect_key(parse_url($url_2), $mustMatch);

        if ($url_1 === $url_2) {
            return true;
        }

        if ($url_1['host'] !== $url_2['host']) {
            return false;
        }

        if (!isset($url_2['path'])) {
            if ($url_1['path'] === '/') {
                return true;
            }
        } else {
            $url_2['path'] = $url_2['path'] . '/';
            if ($url_1 === $url_2) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add scripts
     */
    public static function add_scripts()
    {
        $show_widget = apply_filters('vdrv_get_widget_settings', true);

        if (!$show_widget['success']) {
            return;
        }

        $asset = require VDRV_PLUGIN_DIR_PATH . 'build/front.asset.php';
        $settings = $show_widget['settings'];
        $settings['rest_api_url'] = get_rest_url(null, 'vdrv/v1/widget');
        $settings['css_link'] = sprintf('%s/build/frontStyle.css?%s', VDRV_PLUGIN_URL, $asset['version']);

        wp_enqueue_script('vdrv-plugin-js', VDRV_PLUGIN_URL . '/build/front.js', array('jquery'), $asset['version'], true);

        wp_localize_script('vdrv-plugin-js', 'vdrv_settings', [
            'widget_html' => $widget,
            'settings' => $settings
        ]);
    }
}

VideoReviewsInit::init();
