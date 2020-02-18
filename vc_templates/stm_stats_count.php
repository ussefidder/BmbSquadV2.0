<?php
$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$icon = wp_get_attachment_image_src($atts["stat_icon"], 'full');
?>

<div class="stm-stats-wrapp">
    <?php if(!empty($icon)): ?>
        <img src="<?php echo esc_url($icon[0]);?>" />
    <?php endif; ?>
    <div class="stm-stat-info-wrapp">
        <span class="stm-stat-points heading-font"><?php echo esc_html($atts["stat_points"]); ?></span>
        <span class="stm-stat-title normal_font"><?php echo esc_html($atts["stat_title"]); ?></span>
    </div>
</div>
