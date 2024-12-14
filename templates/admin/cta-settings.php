<?php

namespace VDRV;

$select_array = [
    'disable' => __( 'Disable Button', VDRV_TEXT_DOMAIN ),
    'link'    => __( 'Add url to open other page', VDRV_TEXT_DOMAIN ),
    'action'  => __( 'Opening your form on the site, calling a popup window, launching a quiz, etc.', VDRV_TEXT_DOMAIN ),
    'scroll'  => __( 'Scroll to a block on a site page', VDRV_TEXT_DOMAIN ),
];
$options = $this->video_reviews_options;
?>
<div class="cta-button-settings-wrap">
    <select name="vdrv_settings[cta_type]" id="cta_type">
        <?php 
foreach ( $select_array as $key => $option ) {
    ?>
            <?php 
    $selected = ( isset( $options['cta_type'] ) && $options['cta_type'] === $key ? 'selected' : '' );
    ?>
            <option value="<?php 
    echo $key;
    ?>" <?php 
    echo $selected;
    ?>><?php 
    echo $option;
    ?></option>
        <?php 
}
?>
    </select>

    <div class="cta-settings">
        <?php 
?>
            <h3><?php 
echo $this->premium_text;
?></h3>
            <img src="<?php 
echo VDRV_PLUGIN_URL . '/assets/img/premium/cta_action.png';
?>" class="premium">
        <?php 
?>
    </div>
</div><?php 