<?php

namespace VDRV;

$options = $this->video_reviews_options;
$video_provider = ['local', 'vimeo', 'youtube'];
$video_formats = ['vertical', 'horizontal'];
$selected = ( isset( $options['display_video'] ) && $options['display_video'] === 'advanced' ? 'selected' : '' );
printf(
    '<select id="d_video_type" name="vdrv_settings[display_video]">
<option value="on_all_pages">%s</option>
<option value="advanced" %s>%s</option>
</select>',
    __( 'Display video on all pages', VDRV_TEXT_DOMAIN ),
    $selected,
    __( 'Select videos and pages manually', VDRV_TEXT_DOMAIN )
);
$video_name = '';
if ( isset( $options['video_data'] ) ) {
    $video_data = json_decode( $options['video_data'], true );
    if ( is_array( $video_data ) ) {
        $video_name = $video_data['filename'];
    }
}
?>

<div id="video_on_all_pages" class="video-tab">
    <div class="video-settings" id="all_pages_video">
        <div class="video-source">
            <label><?php 
echo __( 'Video Source', VDRV_TEXT_DOMAIN );
?></label>
            <select class="video-provider" name="<?php 
printf( 'vdrv_settings[video_provider]', $key );
?>">
                <?php 
$selected_source = ( isset( $options['video_provider'] ) ? esc_attr( $options['video_provider'] ) : 'local' );
?>
                <?php 
foreach ( $video_provider as $source ) {
    ?>
                    <option value="<?php 
    echo $source;
    ?>" <?php 
    echo ( $selected_source === $source ? 'selected' : '' );
    ?>><?php 
    echo $source;
    ?></option>
                <?php 
}
?>
            </select>
        </div>

        <div class="video-selector">
            <div class="wordpress-loader">
                <div class="video_loader"><?php 
echo __( 'Select Video' );
?></div>
                <div class="video_selected"><?php 
echo $video_name;
?></div>
            </div>
            <div class="source-id">
                <label><?php 
echo __( 'Video Embed Id', VDRV_TEXT_DOMAIN );
?></label>
                <input type="text" name="vdrv_settings[video_data]" class="video_data" value="<?php 
echo ( isset( $options['video_data'] ) ? esc_attr( $options['video_data'] ) : '' );
?>">
            </div>
        </div>
    </div>
</div>

<div id="video_advanced" class="video-tab">
    <?php 
?>
        <h3><?php 
echo $this->premium_text;
?></h3>
        <img src="<?php 
echo VDRV_PLUGIN_URL . '/assets/img/premium/advanced_video_selector.png';
?>" class="premium">
    <?php 
?>
</div><?php 