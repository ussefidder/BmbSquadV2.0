<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$base_color = get_theme_mod("site_style_base_color", "");
splash_enqueue_modul_scripts_styles('stm_latest_tweets');
if(!empty($base_color)) :
?>

<style type="text/css">
    .stm-tweets-wrapp .stm-latest-tweets .latest-tweets ul li:before, .stm-tweets-wrapp .stm-latest-tweets .latest-tweets ul li .tweet-details a{
        color: <?php echo esc_attr($base_color); ?>;
    }
</style>

<?php endif; ?>

<div class="container stm_latest_tweets <?php if($carousel) echo 'style_carousel'; ?>">
	<div class="stm-tweets-wrapp">
		<div class="clearfix">
			<<?php echo esc_html(getHTag()); ?>><?php echo esc_html($atts["latest_tweets_title"]); ?></<?php echo esc_html(getHTag()); ?>>
		</div>
		<div class="stm-latest-tweets normal_font">
			<?php if(function_exists('latest_tweets_render_html')) echo latest_tweets_render_html(esc_html($atts['latest_tweets_name']), 3); ?>
		</div>
	</div>
</div>