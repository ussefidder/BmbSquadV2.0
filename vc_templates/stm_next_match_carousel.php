<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
if ($view_style == 'style_2' || $view_style == 'style_3') {
    splash_enqueue_modul_scripts_styles('stm_next_match_carousel2');
} else {
    splash_enqueue_modul_scripts_styles('stm_next_match_carousel');
}
if (empty($slide_count)) {
    $slide_count = 3;
}
if (empty($navs_)) {
    $navs_ = 'disable';
}
if (splash_is_layout("basketball_two")) $dots_ = 'enable';
if (empty($dots_)) {
    $dots_ = 'disable';
}
wp_localize_script('stm_next_match_carousel2', 'slides_', array('items' => $slide_count, 'navs_' => $navs_, 'dots_' => $dots_));


if (!empty($count)) $count = -1;
$next_match_args = array(
    'post_status' => 'future',
    'posts_per_page' => intval($count),
    'post_type' => 'sp_event',
    'order' => 'ASC'
);

if (!empty($pick_team)) {
    $next_match_args['meta_query'][] = array(
        'key' => 'sp_team',
        'value' => intval($pick_team),
        'compare' => 'IN'
    );
}
$next_match_query = new WP_Query($next_match_args);

$past_match_args = array(
    'post_status' => 'closed',
    'posts_per_page' => 1,
    'post_type' => 'sp_event',
    'order' => 'ASC'
);
?>
<div class="stm-next-match-carousel-wrap <?php echo esc_attr($view_style); ?>">
    <h2 class="stm-carousel-title">
        <?php echo esc_html($title); ?>
    </h2>
    <div class=" <?php echo esc_attr($view_style != 'style_1') ? esc_attr("stm-next-match-carousel2") : esc_attr("stm-next-match-carousel"); ?>">
        <?php if ($next_match_query->have_posts()): ?>
            <?php while ($next_match_query->have_posts()):
                $next_match_query->the_post(); ?>
                <div class="stm-next-match-carousel__item">
                    <div class="event-results">
                        <?php
                        $id = get_the_ID();
                        $event = new SP_Event($id);
                        $data = $event->results();
                        unset($data[0]);
                        $teams = get_post_meta(get_the_id(), 'sp_team', false);
                        $team_results = get_post_meta(get_the_ID(), 'sp_results', false);
                        $team_1_id = $teams[0];
                        $team_2_id = $teams[1];
                        $team_1_url = get_permalink($team_1_id);
                        $team_2_url = get_permalink($team_2_id);
                        $team_1_title = get_the_title($team_1_id);
                        $team_2_title = get_the_title($team_2_id);
                        $team_1_img_url = splash_get_thumbnail_url($team_1_id, '', 'thumbnail');
                        $team_2_img_url = splash_get_thumbnail_url($team_2_id, '', 'thumbnail');
                        $sp_league = wp_get_post_terms($id, 'sp_league');
                        $league = $sp_league[0]->name;
                        $venues = get_the_terms($id, 'sp_venue');
                        $venue = $venues[0]->name;
                        ?>
                        <?php if ($view_style != 'style_3'): ?>
                            <a href="<?php echo esc_url($team_1_url); ?>">
                                <img src="<?php echo esc_url($team_1_img_url); ?>"
                                     alt="<?php echo esc_attr($team_1_title); ?>">
                            </a>
                        <?php endif; ?>
                        <?php if ($view_style == 'style_3'): ?>
                            <div class="event-data">
                                <a href="<?php echo esc_url($team_1_url); ?>">
                                    <div class="teams-titles">
                                        <?php echo esc_attr($team_1_title); ?>
                                        <img src="<?php echo esc_url($team_1_img_url); ?>"
                                             alt="<?php echo esc_attr($team_1_title); ?>">
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if ($view_style == 'style_3'): ?>
                                <?php echo esc_html_e('vs', 'splash'); ?>
                        <?php endif; ?>
                        <?php if ($view_style != 'style_3'): ?>
                            <span class="post-score heading-font">
                                <?php echo esc_html_e('vs', 'splash'); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($view_style == 'style_3'): ?>
                            <div class="event-data">
                                <a href="<?php echo esc_url($team_2_url); ?>">
                                    <div class="teams-titles">
                                        <?php echo esc_attr($team_2_title); ?>
                                        <img src="<?php echo esc_url($team_2_img_url); ?>"
                                             alt="<?php echo esc_attr($team_2_title); ?>">
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if ($view_style != 'style_3'): ?>
                            <a href="<?php echo esc_url($team_2_url); ?>">
                                <img src="<?php echo esc_url($team_2_img_url); ?>"
                                     alt="<?php echo esc_attr($team_2_title); ?>">
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($view_style == 'style_3'): ?>
                        <div class="event-data-style3">
                            <div class="event-league">
                                <?php echo esc_html($league); ?>
                            </div>
                            <?php
                            echo esc_html(' ' . $venue.' ');
                            echo get_the_date();
                            ?>
                        </div>
                        <div class="more_">
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html__('Learn more', 'splash'); ?><i
                                        class="fa fa-angle-right"></i></a>
                        </div>
                    <?php endif; ?>
                    <?php if ($view_style != 'style_3'): ?>
                        <div class="event-data">
                            <a href="<?php the_permalink(); ?>">
                                <div class="teams-titles">
                                    <?php the_title(); ?>
                                </div>
                            </a>
                            <div class="event-league">
                                <?php echo esc_html($league); ?>
                            </div>
                        </div>
                        <div class="event-date">
                            <?php
                            echo get_the_date();
                            echo esc_html(' ' . $venue)
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>
</div>
