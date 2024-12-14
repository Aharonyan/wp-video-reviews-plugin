<?php

namespace VDRV;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
class VDRVSettings {
    private $video_reviews_options;

    private $premium_text;

    private $have_license;

    public function __construct() {
        $this->premium_text = sprintf( __( 'Please <a href="%s">upgrade</a> your plan to edit this settings', 'video-reviews' ), admin_url( 'admin.php?page=video-reviews-pricing' ) );
        $this->video_reviews_options = get_option( 'vdrv_settings' );
        add_action( 'admin_menu', array($this, 'video_reviews_add_plugin_page') );
        add_action( 'admin_init', array($this, 'video_reviews_page_init') );
        add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts') );
    }

    public function admin_scripts( $hook ) {
        if ( $hook == 'toplevel_page_video-reviews' ) {
            wp_register_script( 'vdrv_settings', VDRV_PLUGIN_URL . '/build/admin.js', array('jquery') );
            wp_register_style( 'vdrv_settings', VDRV_PLUGIN_URL . '/build/adminStyle.css' );
            wp_enqueue_style( 'vdrv_settings' );
            wp_enqueue_script( 'vdrv_settings' );
            wp_enqueue_media();
        }
    }

    public function video_reviews_add_plugin_page() {
        add_menu_page(
            'Video Reviews Pro',
            // page_title
            'Video Reviews',
            // menu_title
            'manage_options',
            // capability
            'video-reviews',
            // menu_slug
            array($this, 'video_reviews_create_admin_page'),
            // function
            'dashicons-media-video',
            // icon_url
            154
        );
    }

    public function video_reviews_create_admin_page() {
        $this->video_reviews_options = get_option( 'vdrv_settings' );
        ?>
		<div class="wrap">
			<h2>Video Reviews</h2>
			<?php 
        if ( !vd_rv()->is_paying_or_trial() ) {
            ?>
				<p class="warning"><?php 
            printf( __( 'Awesome Professional Features <a href="%s" class="trial">Start Free Trial</a>', VDRV_TEXT_DOMAIN ), admin_url( 'admin.php?page=video-reviews-pricing&trial=true' ) );
            ?></p>
			<?php 
        }
        ?>
			<?php 
        settings_errors();
        ?>

			<form method="post" action="options.php">
				<?php 
        settings_fields( 'video_reviews_option_group' );
        do_settings_sections( 'video-reviews-admin' );
        submit_button();
        ?>
			</form>
		</div>
<?php 
    }

    public function video_reviews_page_init() {
        register_setting( 
            'video_reviews_option_group',
            // option_group
            'vdrv_settings',
            // option_name
            array($this, 'video_reviews_sanitize')
         );
        add_settings_section(
            'video_reviews_setting_section',
            // id
            __( 'Settings', 'video-reviews' ),
            // title
            array($this, 'video_reviews_section_info'),
            // callback
            'video-reviews-admin'
        );
        add_settings_field(
            'video_data',
            // id
            __( 'Video and place to show', 'video-reviews' ),
            // title
            array($this, 'video_data_callback'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'start_video',
            // id
            __( 'Start video from start', 'video-reviews' ),
            // title
            array($this, 'start_video_from_start'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'cta_button',
            // id
            __( 'Call to action button', 'video-reviews' ),
            // title
            array($this, 'cta_button_callback'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'widget_devices',
            // id
            __( 'Disable on devices', 'video-reviews' ),
            // title
            array($this, 'devices_callback'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'widget_location',
            // id
            __( 'Widget location', 'video-reviews' ),
            // title
            array($this, 'widget_location_callback'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'widget_border_color',
            // id
            __( 'Widget border color', 'video-reviews' ),
            // title
            array($this, 'widget_border_color_callback'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'cta_button_color',
            // id
            __( 'CTA button color', 'video-reviews' ),
            // title
            array($this, 'cta_button_color_callback'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
        add_settings_field(
            'powered_by_text',
            // id
            __( 'Show powered by text', 'video-reviews' ),
            // title
            array($this, 'powered_by_text'),
            // callback
            'video-reviews-admin',
            // page
            'video_reviews_setting_section'
        );
    }

    public function video_reviews_sanitize( $input ) {
        if ( isset( $input['video_data'] ) ) {
            $input['video_data'] = sanitize_text_field( $input['video_data'] );
        }
        if ( isset( $input['cta_type'] ) ) {
            $input['cta_type'] = $input['cta_type'];
        }
        if ( isset( $input['cta_text'] ) ) {
            $input['cta_text'] = sanitize_text_field( $input['cta_text'] );
        }
        if ( isset( $input['cta_link']['url'] ) ) {
            $input['cta_link']['url'] = esc_url_raw( $input['cta_link']['url'] );
        }
        return $input;
    }

    public function video_reviews_section_info() {
    }

    /**
     * Select video
     *
     * @return void
     */
    public function video_data_callback() {
        require VDRV_Template_PATH . '/admin/video-settings.php';
    }

    /**
     * Play video from the beginning when it is clicked/tapped
     *
     * @return void
     */
    public function start_video_from_start() {
        $options = $this->video_reviews_options;
        $start = ( !empty( $options['s_video_from_start'] ) ? 'checked' : '' );
        printf( '<input type="checkbox" name="vdrv_settings[s_video_from_start]" value="1" %s>', $start );
        printf( '<p>%s</p>', __( 'Play video from the beginning when it is clicked/tapped', 'video-reviews' ) );
    }

    /**
     * powered by text
     *
     * @return void
     */
    public function powered_by_text() {
        $options = $this->video_reviews_options;
        $start = ( !empty( $options['powered_by_text'] ) ? 'checked' : '' );
        $disabled = 'disabled';
        if ( vd_rv()->is_paying_or_trial() ) {
            $disabled = '';
        }
        printf( '<input type="checkbox" name="vdrv_settings[powered_by_text]" value="1" %s %s>', $start, $disabled );
        printf( '<p>%s</p>', $this->premium_text );
    }

    /**
     * Show or hide on some devices
     *
     * @return void
     */
    public function devices_callback() {
        $options = $this->video_reviews_options;
        $desktop = ( !empty( $options['d_desktop'] ) ? 'checked' : '' );
        printf( '<input type="checkbox" name="vdrv_settings[d_desktop]" value="1" %s><label style="margin-right:10px;">%s</label>', $desktop, __( 'Desktop', 'video-reviews' ) );
        $mobile = ( !empty( $options['d_mobile'] ) ? 'checked' : '' );
        printf( '<input type="checkbox" name="vdrv_settings[d_mobile]" value="1" %s><label>%s</label>', $mobile, __( 'Mobile', 'video-reviews' ) );
    }

    /**
     * Select widget location
     *
     * @return void
     */
    public function widget_location_callback() {
        $options = $this->video_reviews_options;
        $selected = ( isset( $options['w_location'] ) && $options['w_location'] === 'right_bottom' ? 'selected' : '' );
        printf(
            '<select name="vdrv_settings[w_location]">
		<option value="left_bottom">%s</option>
		<option value="right_bottom" %s>%s</option>
		</select>',
            __( 'Left Bottom', 'video-reviews' ),
            $selected,
            __( 'Right Bottom', 'video-reviews' )
        );
    }

    /**
     * Widget border color
     *
     * @return void
     */
    public function widget_border_color_callback() {
        $options = $this->video_reviews_options;
        $color = ( !empty( $options['default_widget_border'] ) ? $options['default_widget_border'] : '#ffffff' );
        printf( '<input type="color" name="vdrv_settings[default_widget_border]" value="%s"><label style="margin-right:10px;">%s</label>', $color, __( 'Default widget border', 'video-reviews' ) );
        $color = ( !empty( $options['on_hover_widget_border'] ) ? $options['on_hover_widget_border'] : '#131344' );
        printf( '<input type="color" name="vdrv_settings[on_hover_widget_border]" value="%s"><label>%s</label>', $color, __( 'On hover widget border', 'video-reviews' ) );
    }

    /**
     * CTA button colors
     *
     * @return void
     */
    public function cta_button_color_callback() {
        $options = $this->video_reviews_options;
        printf( '<h5>%s</h5>', __( 'Call to action button colors', 'video-reviews' ) );
        $color = ( !empty( $options['cta_button_background'] ) ? $options['cta_button_background'] : '#0000FF' );
        printf( '<input type="color" name="vdrv_settings[cta_button_background]" value="%s"><label style="margin-right:10px;">%s</label>', $color, __( 'Background color', 'video-reviews' ) );
        $color = ( !empty( $options['cta_button_text_color'] ) ? $options['cta_button_text_color'] : '#ffffff' );
        printf( '<input type="color" name="vdrv_settings[cta_button_text_color]" value="%s"><label>%s</label>', $color, __( 'Text color', 'video-reviews' ) );
    }

    /**
     * CtA Settings
     *
     * @return void
     */
    public function cta_button_callback() {
        require VDRV_Template_PATH . '/admin/cta-settings.php';
    }

}

if ( is_admin() ) {
    $video_reviews = new VDRVSettings();
}