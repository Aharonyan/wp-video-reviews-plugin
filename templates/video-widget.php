<?php

namespace VDRV;

$vdrv_settings['cta_type'] = 'disable';
$video_format = 'vertical';
/**
 * If user select multiple videos for multiple pages
 */
if ( $vdrv_settings['display_video'] === 'advanced' && vd_rv()->is_paying_or_trial__premium_only( 'pro', $exact = false ) ) {
    if ( !empty( $vdrv_settings['video_to_show_on_this_page'] ) ) {
        $vdrv_settings['video_data'] = $vdrv_settings['video_to_show_on_this_page']['video_data'];
        $vdrv_settings['video_provider'] = $vdrv_settings['video_to_show_on_this_page']['video_provider'];
        $video_format = $vdrv_settings['video_to_show_on_this_page']['video_format'];
    }
}
if ( empty( $vdrv_settings['video_data'] ) ) {
    return;
}
$video_data = $vdrv_settings['video_data'];
/**
 * Custom class
 */
$class = '';
if ( isset( $vdrv_settings['d_desktop'] ) ) {
    $class .= ' d_desktop';
}
if ( isset( $vdrv_settings['d_mobile'] ) ) {
    $class .= ' d_mobile';
}
/**
 * Custom style
 */
$wrapper_style = 'style="';
if ( isset( $vdrv_settings['default_widget_border'] ) ) {
    $wrapper_style .= sprintf( 'border-color:%s;', esc_html( $vdrv_settings['default_widget_border'] ) );
}
$wrapper_style .= '"';
?>
<div id="vdrv-widget" class="vdrv_hidden <?php 
echo ( $vdrv_settings['w_location'] == 'right_bottom' ? 'right_bottom' : '' );
?>">
    <div id="vdrv-middle-wrapper" class="<?php 
echo $video_format;
?>">
        <div id="vdrv-widget-video-wrapper" class="<?php 
echo $video_format;
?> <?php 
echo esc_html( $class );
?> <?php 
echo $vdrv_settings['video_provider'];
?>" <?php 
echo esc_html( $wrapper_style );
?>>
            <?php 
if ( isset( $vdrv_settings['video_provider'] ) && ($vdrv_settings['video_provider'] === 'vimeo' || $vdrv_settings['video_provider'] === 'youtube') ) {
    ?>
                <div id="player" class="plyr__video-embed vdrv-video-widget" data-plyr-provider="<?php 
    echo $vdrv_settings['video_provider'];
    ?>" data-plyr-embed-id="<?php 
    echo $video_data;
    ?>" playsinline></div>
            <?php 
} else {
    $video_data = json_decode( $video_data, true );
    $video = get_post( $video_data['id'] );
    ?>
                <video id="player" class="vdrv-video-widget">
                    <source src="<?php 
    echo esc_url( $video->guid );
    ?>" type="<?php 
    echo esc_attr( $video->post_mime_type );
    ?>">
                </video>
            <?php 
}
?>
            <?php 
if ( !empty( $vdrv_settings['cta_type'] ) && $vdrv_settings['cta_type'] !== 'disable' ) {
    $cta_button_style = 'style="';
    $cta_button_style .= sprintf( 'background-color:%s;', esc_html( $vdrv_settings['cta_button_background'] ) );
    $cta_button_style .= sprintf( 'color:%s;', esc_html( $vdrv_settings['cta_button_text_color'] ) );
    $cta_button_style .= '"';
    printf( '<div id="vdrv-cta-button" %s><div class="vdrv-cta-button">%s</div></div>', $cta_button_style, esc_html( $vdrv_settings['cta_text'] ) );
}
?>
            <div mode="close" class="vdrv-widget-close"></div>
            <?php 
if ( isset( $vdrv_settings['powered_by_text'] ) ) {
    ?>
                <a href="https://vreviews.io/" target="_blank" id="video-widget-bottom-text">
                    <?php 
    esc_html_e( 'Powered by Video Reviews', 'video-reviews' );
    ?>
                </a>
            <?php 
}
?>
        </div>
    </div>
</div><?php 