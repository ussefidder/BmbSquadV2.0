<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);

include(locate_template("partials/vc_templates_views/stm_latest_results_carousel.php"));

wp_reset_postdata();
?>