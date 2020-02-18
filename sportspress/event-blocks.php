<?php
/**
 * Event Blocks
 *
 * @author        ThemeBoy
 * @package    SportsPress/Templates
 * @version   2.6.11
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Default template or sportpress */
$event_list_template = get_theme_mod( 'event_block_template', 'theme' );

$defaults = array(
    'id' => null,
    'event' => null,
    'title' => false,
    'status' => 'default',
    'format' => 'all',
    'date' => 'default',
    'date_from' => 'default',
    'date_to' => 'default',
    'date_past' => 'default',
    'date_future' => 'default',
    'date_relative' => 'default',
    'day' => 'default',
    'league' => null,
    'season' => null,
    'venue' => null,
    'team' => null,
    'teams_past' => null,
    'date_before' => null,
    'player' => null,
    'number' => -1,
    'show_team_logo' => get_option( 'sportspress_event_blocks_show_logos', 'yes' ) == 'yes' ? true : false,
    'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
    'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
    'paginated' => get_option( 'sportspress_event_blocks_paginated', 'yes' ) == 'yes' ? true : false,
    'rows' => get_option( 'sportspress_event_blocks_rows', 5 ),
    'orderby' => 'default',
    'order' => 'default',
    'show_all_events_link' => false,
    'show_title' => get_option( 'sportspress_event_blocks_show_title', 'no' ) == 'yes' ? true : false,
    'show_league' => get_option( 'sportspress_event_blocks_show_league', 'no' ) == 'yes' ? true : false,
    'show_season' => get_option( 'sportspress_event_blocks_show_season', 'no' ) == 'yes' ? true : false,
    'show_matchday' => get_option( 'sportspress_event_blocks_show_matchday', 'no' ) == 'yes' ? true : false,
    'show_venue' => get_option( 'sportspress_event_blocks_show_venue', 'no' ) == 'yes' ? true : false,
    'hide_if_empty' => false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );
if( $status != 'default' )
    $calendar->status = $status;
if( $format != 'all' )
    $calendar->event_format = $format;
if( $date != 'default' )
    $calendar->date = $date;
if( $date_from != 'default' )
    $calendar->from = $date_from;
if( $date_to != 'default' )
    $calendar->to = $date_to;
if( $date_past != 'default' )
    $calendar->past = $date_past;
if( $date_future != 'default' )
    $calendar->future = $date_future;
if( $date_relative != 'default' )
    $calendar->relative = $date_relative;
if( $event )
    $calendar->event = $event;
if( $league )
    $calendar->league = $league;
if( $season )
    $calendar->season = $season;
if( $venue )
    $calendar->venue = $venue;
if( $team )
    $calendar->team = $team;
if( $teams_past )
    $calendar->teams_past = $teams_past;
if( $date_before )
    $calendar->date_before = $date_before;
if( $player )
    $calendar->player = $player;
if( $order != 'default' )
    $calendar->order = $order;
if( $orderby != 'default' )
    $calendar->orderby = $orderby;
if( $day != 'default' )
    $calendar->day = $day;
$data = $calendar->data();

if( $hide_if_empty && empty( $data ) ) return false;

if( $show_title && false === $title && $id ):
    $caption = $calendar->caption;
    if( $caption )
        $title = $caption;
    else
        $title = get_the_title( $id );
endif;

if( $title )
    echo '<h4 class="sp-table-caption">' . $title . '</h4>';

if( $event_list_template == 'theme' ) { ?>
    <div class="sp-template sp-template-event-blocks sp-stm-template-event-blocks">
        <div class="sp-table-wrapper">

            <?php
            $i = 0;

            if( intval( $number ) > 0 )
                $limit = $number;
            foreach( $data as $event ):
                if( isset( $limit ) && $i >= $limit ) continue;
                $postStatus = strtolower( $event->post_status );
                $teams = array_unique( get_post_meta( $event->ID, 'sp_team' ) );
                $teams = array_filter( $teams, 'sp_filter_positive' );
                $team_results = get_post_meta( $event->ID, 'sp_results', false );
                $permalink = get_post_permalink( $event, false, true );
                $results = get_post_meta( $event->ID, 'sp_results', true );
                $point_system = splash_get_sportpress_points_system();

                if( count( $teams ) > 1 ):
                    $team_1_id = $teams[ 0 ];
                    $team_2_id = $teams[ 1 ];

                    $city_1 = wp_get_post_terms( $team_1_id, 'sp_venue' );
                    $city_2 = wp_get_post_terms( $team_2_id, 'sp_venue' );

                    $logos = array();

                    $j = 0;
                    foreach( $teams as $team ):
                        $j++;
                        if( has_post_thumbnail( $team ) ):
                            $tId = ( $j == 1 ) ? $team_1_id : $team_2_id;
                            if( $link_teams ):
                                if( splash_is_layout( "bb" ) ) {
                                    $logo = '<a class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" href="' . get_permalink( $team, false, true ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'stm-200-200' ) . '</a>';
                                }
                                else {
                                    $src = "";
                                    if( get_post_meta( $tId, 'team_helm_image' ) != null ) {
                                        $postMeta = get_post_meta( $tId, 'team_helm_image' );
                                        $attachImg = wp_get_attachment_image_src( $postMeta[ 0 ], 'full' );
                                        $src = $attachImg[ 0 ];
                                    }
                                    if( !splash_is_layout( 'baseball' ) && !splash_is_layout( 'magazine_one' ) && !splash_is_layout( 'hockey' ) ) {
                                        $logo = '<div class="stm-team-l-h-wrapp">
												<div class="stm-team-helm">
													<img src="' . esc_url( $src ) . '" />
													<a class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" href="' . get_permalink( $team, false, true ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'stm-200-200' ) . '</a>
												</div>
											</div>';
                                    }
                                    else {
                                        $logo = '<div class="stm-team-l-h-wrapp">
												<div class="stm-team-helm">
													<a class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" href="' . get_permalink( $team, false, true ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'full' ) . '</a>
												</div>
											</div>';
                                    }
                                }
                            else:
                                if( splash_is_layout( "bb" ) ) {
                                    $logo = '<span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'stm-200-200' ) . '</span>';
                                }
                                else {
                                    $src = "";
                                    if( get_post_meta( $tId, 'team_helm_image' ) != null ) {
                                        $postMeta = get_post_meta( $tId, 'team_helm_image' );
                                        $attachImg = wp_get_attachment_image_src( $postMeta[ 0 ], 'full' );
                                        $src = $attachImg[ 0 ];
                                    }

                                    if( !splash_is_layout( 'baseball' ) && !splash_is_layout( 'basketball_two' ) && !splash_is_layout( 'magazine_one' ) && !splash_is_layout( "hockey" ) ) {
                                        $logo = '<div class="stm-team-l-h-wrapp">
                                                    <div class="stm-team-helm">';
                                        if( !empty( $src ) ) {
                                            $logo .= '<img src="' . esc_url( $src ) . '" />';
                                        }
                                        $logo .= '<span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'stm-200-200' ) . '</span>
                                                    </div>
                                                </div>';
                                    }
                                    else {
                                        $logo = '<div class="stm-team-l-h-wrapp">
                                                    <span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'full' ) . '</span>
												</div>';
                                    }
                                }
                            endif;
                            $logos[] = $logo;
                        endif;
                    endforeach; ?>

                    <div class="stm-single-block-event-list sp-stm-template-event-blocks-<?php echo esc_attr( strtolower( $event->post_status ) ); ?>">
                        <?php if( !splash_is_layout( "sccr" ) && !splash_is_layout( 'soccer_two' ) ) : ?>
                            <?php if( splash_is_layout( "bb" ) || splash_is_layout( "magazine_one" ) ) : ?>
                                <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>" class="stm-no-decor">
                            <?php endif; ?>
                            <?php if( splash_is_layout( "bb" ) || splash_is_layout( "magazine_one" ) || splash_is_layout( "hockey" ) ): ?><!--is not americanfootball-->
                            <div class="stm-single-block-event-list-top">
                                <div class="time h6"><?php echo esc_attr( get_the_time( get_option( 'date_format' ), $event ) ); ?></div>
                                <?php
                                if( splash_is_layout( "bb" ) || splash_is_layout( "magazine_one" ) || splash_is_layout( "hockey" ) ):
                                    if( $show_venue ):
                                        $venues = get_the_terms( $event, 'sp_venue' );
                                        if( $venues ): $venue = array_shift( $venues );
                                            ?>
                                            <div class="venue h6"><?php echo sanitize_text_field( $venue->name ); ?></div>
                                        <?php
                                        endif;
                                    endif;
                                else:
                                    $venues = get_the_terms( $event, 'sp_venue' );
                                    ?>
                                    <div class="venue h6"><?php echo sanitize_text_field( $venue->name ); ?></div>
                                <?php endif; ?>
                                <?php if( strtolower( $event->post_status ) == 'future' ): ?>
                                    <div class="stm-future-event-list-time">
                                        <?php
                                        $date = new DateTime( get_the_time( 'Y/m/d H:i:s', $event->ID ) );
                                        $date = $date->format( 'Y-m-d H:i:s' );
                                        ?>
                                        <time class="heading-font" datetime="<?php echo esc_attr( $date ) ?>"
                                              data-countdown="<?php echo esc_attr( str_replace( "-", "/", $date ) ) ?>"></time>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                            <div class="stm-single-block-unit">
                                <div class="stm-team-logo left">
                                    <?php
                                    if( !empty( $logos[ 0 ] ) ):
                                        echo wp_kses_post( $logos[ 0 ] );
                                    endif;
                                    ?>
                                    <!-- hockey -->
                                    <?php if( splash_is_layout( 'hockey' ) ): ?>
                                        <div class="stm-title-team">
                                            <span class="stm-team-name"><?php echo esc_html( get_the_title( $team_1_id ) ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- hockey End-->
                                </div>

                                <div class="stm-teams-info heading-font">
                                    <div class="stm-title-team">
                                        <?php if( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) || splash_is_layout( "basketball_two" ) || splash_is_layout( "hockey" ) ): ?>
                                            <span class="stm-team-city"><?php echo esc_html( $city_1[ 0 ]->name ); ?></span>
                                            <span class="stm-team-name"><?php echo esc_html( get_the_title( $team_1_id ) ); ?></span>
                                            <?php if( splash_is_layout( 'baseball' ) && !empty( $team_results[ 0 ] ) && !empty( $team_results[ 0 ][ $team_1_id ] ) && isset( $team_results[ 0 ][ $team_1_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                <div class="<?php

                                                echo esc_attr( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ) ? esc_attr( 'stm-latest-result-win-label' ) : esc_attr( 'stm-latest-result-lose-label' ); ?> heading-font">
                                                    <?php
                                                    printf( _x( '%s', 'Outcome for team', 'splash' ), $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] );
                                                    ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php echo esc_attr( get_the_title( $team_1_id ) ); ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="stm-team-results-outer">
                                        <?php if( splash_is_layout( "bb" ) ): ?>
                                            <?php if( !empty( $team_results[ 0 ] ) ): ?>
                                                <?php if( !empty( $team_results[ 0 ][ $team_1_id ] ) ): ?>
                                                    <?php if( isset( $team_results[ 0 ][ $team_1_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                        <?php if( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ): ?>
                                                            <div class="stm-latest-result-win-label <?php echo ( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) || splash_is_layout( "basketball_two" ) ) ? "heading-font" : "normal-font" ?>"><?php esc_html_e( 'win', 'splash' ) ?></div>
                                                        <?php else: ?>
                                                            <div class="stm-latest-result-lose-label <?php echo ( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) || splash_is_layout( "basketball_two" ) ) ? "heading-font" : "normal-font" ?>"><?php esc_html_e( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ], 'splash' ) ?></div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="stm-latest-result-lose-label"><?php esc_html_e( '- -', 'splash' ) ?></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="stm-latest-result-lose-label"><?php esc_html_e( '- -', 'splash' ) ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if( !empty( $team_results[ 0 ] ) ): ?>

                                            <?php if( !empty( $team_results[ 0 ][ $team_1_id ] ) and !empty( $team_results[ 0 ][ $team_2_id ] ) ): ?>
                                                <?php if( isset( $team_results[ 0 ][ $team_1_id ][ $point_system ] ) and isset( $team_results[ 0 ][ $team_2_id ][ $point_system ] ) ): ?>
                                                    <?php if( empty( $team_results[ 0 ][ $team_1_id ][ $point_system ] ) and empty( $team_results[ 0 ][ $team_2_id ][ $point_system ] ) ): ?>
                                                        <div class="stm-latest-result_result"><?php ( splash_is_layout( "bb" ) || splash_is_layout( "esport" ) ) ? esc_html_e( '- VS -', 'splash' ) : esc_html_e( 'VS', 'splash' ); ?></div>
                                                    <?php else: ?>
                                                        <?php if( splash_is_layout( "bb" ) ): ?>
                                                            <div class="stm-latest-result_result"><?php echo esc_attr( $team_results[ 0 ][ $team_1_id ][ $point_system ] . ' / ' . $team_results[ 0 ][ $team_2_id ][ $point_system ] ); ?></div>
                                                        <?php else: ?>
                                                            <div class="stm-latest-result_result">
                                                                <?php if( !splash_is_layout( 'baseball' ) ) : ?>
                                                                    <span class="stm-res-left <?php if( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ) echo 'res-win-label'; ?>">
                                                                            <?php if( !empty( $team_results[ 0 ][ $team_1_id ] ) ): ?>
                                                                                <?php if( isset( $team_results[ 0 ][ $team_1_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                                                    <span class="<?php echo esc_attr( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ) ? esc_attr( 'stm-win' ) : esc_attr( 'stm-lose' ); ?>"><?php echo esc_attr( $team_results[ 0 ][ $team_1_id ][ $point_system ] ); ?></span>
                                                                                    <div class="<?php echo esc_attr( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ) ? esc_attr( 'stm-latest-result-win-label' ) : esc_attr( 'stm-latest-result-lose-label' ); ?> <?php echo ( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) ) ? "heading-font" : "normal-font" ?>"><?php
                                                                                        printf( _x( '%s', 'Outcome', 'splash' ), $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] );
                                                                                        ?></div>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </span>
                                                                    <?php if( splash_is_layout( 'magazine_one' ) || splash_is_layout( 'soccer_news' ) || splash_is_layout( 'magazine_two' ) || splash_is_layout( 'hockey' ) ) echo ' : '; ?>
                                                                    <span class="stm-res-right <?php if( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] == 'win' ) echo 'res-win-label'; ?>">
                                                                            <?php if( !empty( $team_results[ 0 ] ) ): ?>
                                                                                <?php if( !empty( $team_results[ 0 ][ $team_2_id ] ) ): ?>
                                                                                    <?php if( isset( $team_results[ 0 ][ $team_2_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                                                        <span class="<?php echo esc_attr( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] == 'win' ) ? 'stm-win' : 'stm-lose'; ?>"><?php echo esc_attr( $team_results[ 0 ][ $team_2_id ][ $point_system ] ); ?></span>
                                                                                        <div class="<?php echo esc_attr( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] == 'win' ) ? 'stm-latest-result-win-label' : 'stm-latest-result-lose-label'; ?> <?php echo ( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) ) ? "heading-font" : "normal-font" ?>"><?php printf( _x( '%s', 'Outcome for team', 'splash' ), $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ); ?></div>
                                                                                    <?php else: ?>
                                                                                        <div class="stm-latest-result-lose-label"><?php esc_html_e( '- -', 'splash' ) ?></div>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </span>
                                                                <?php else : ?>
                                                                    <span class="stm-res-outer">
                                                                            <?php if( !empty( $team_results[ 0 ][ $team_1_id ] ) ): ?>
                                                                                <?php if( isset( $team_results[ 0 ][ $team_1_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                                                    <span class="<?php echo esc_attr( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ) ? 'stm-win' : 'stm-lose'; ?>">
                                                                                        <?php echo esc_attr( $team_results[ 0 ][ $team_1_id ][ $point_system ] ); ?>
                                                                                        : <?php echo esc_attr( $team_results[ 0 ][ $team_2_id ][ $point_system ] ); ?>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if( splash_is_layout( 'hockey' ) ): ?>
                                                        <div class="stm-latest-result_result_vs">
                                                            <?php echo esc_html__( 'VS', 'splash' ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="stm-latest-result_result"><?php ( splash_is_layout( "bb" ) ) ? esc_html_e( '- VS -', 'splash' ) : esc_html_e( 'VS', 'splash' ); ?></div>
                                        <?php endif; ?>

                                        <?php if( splash_is_layout( "bb" ) ): ?>
                                            <?php if( !empty( $team_results[ 0 ] ) ): ?>
                                                <?php if( !empty( $team_results[ 0 ][ $team_2_id ] ) ): ?>
                                                    <?php if( isset( $team_results[ 0 ][ $team_2_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                        <?php if( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] == 'win' ): ?>
                                                            <div class="stm-latest-result-win-label <?php echo ( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) || splash_is_layout( "basketball_two" ) || splash_is_layout( "hockey" ) ) ? "heading-font" : "normal-font" ?>"><?php esc_html_e( 'win', 'splash' ) ?></div>
                                                        <?php else: ?>
                                                            <div class="stm-latest-result-lose-label <?php echo ( splash_is_layout( "af" ) || splash_is_layout( "baseball" || splash_is_layout( "basketball_two" ) || splash_is_layout( "hockey" ) ) ) ? "heading-font" : "normal-font" ?>"><?php printf( _x( '%s', 'Outcome', 'splash' ), $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ); ?></div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="stm-latest-result-lose-label"><?php esc_html_e( '- -', 'splash' ) ?></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="stm-latest-result-lose-label"><?php esc_html_e( '- -', 'splash' ) ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="stm-title-team opponent">
                                        <?php if( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) || splash_is_layout( "basketball_two" ) || splash_is_layout( "hockey" ) ): ?>
                                            <span class="stm-team-city"><?php echo esc_html( $city_2[ 0 ]->name ); ?></span>
                                            <span class="stm-team-name"><?php echo esc_attr( get_the_title( $team_2_id ) ); ?></span>
                                            <?php if( splash_is_layout( 'baseball' ) && !empty( $team_results[ 0 ] ) && !empty( $team_results[ 0 ][ $team_2_id ] ) && isset( $team_results[ 0 ][ $team_2_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                <div class="<?php echo esc_attr( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] == 'win' ) ? 'stm-latest-result-win-label' : 'stm-latest-result-lose-label'; ?> heading-font"><?php esc_html_e( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ], 'splash' ) ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php echo esc_attr( get_the_title( $team_2_id ) ); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(splash_is_layout('esport')): ?>
                                    <div class="date-place">
                                        <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>">
                                            <div class="time h6"><?php echo esc_attr( get_the_time( get_option( 'date_format' ), $event ) ); ?></div>
                                        </a>
                                        <?php
                                            $venues = get_the_terms( $event, 'sp_venue' );
                                            if( $venues ): $venue = array_shift( $venues );
                                                ?>
                                                <div class="venue h6"><?php echo sanitize_text_field( $venue->name ); ?></div>
                                            <?php
                                            endif; ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if( wp_is_mobile() ) echo '</a>'; ?>

                                <div class="stm-team-logo right">
                                    <?php
                                    if( !empty( $logos[ 1 ] ) ):
                                        echo wp_kses_post( $logos[ 1 ] );
                                    endif;
                                    ?>
                                    <!-- hockey -->
                                    <?php if( splash_is_layout( 'hockey' ) ): ?>
                                        <div class="stm-title-team opponent">
                                            <span class="stm-team-name"><?php echo esc_attr( get_the_title( $team_2_id ) ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- hockey End -->
                                </div>
                            </div>
                            <?php if( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) || splash_is_layout( "basketball_two" ) ): ?><!--is americanfootball-->
                            <div class="stm-single-block-event-list-top">
                                <div class="time"><?php echo esc_html( get_the_time( get_option( 'date_format' ), $event ) ); ?> </div>
                                <?php if( strtolower( $event->post_status ) == 'future' ): ?>
                                    <div class="stm-future-event-list-time">
                                        <?php
                                        $date = new DateTime( get_the_time( 'Y/m/d H:i:s', $event->ID ) );
                                        $time = $date->format( get_option( 'time_format' ) );
                                        $date = $date->format( 'Y-m-d H:i:s' );
                                        ?>
                                        <?php if( splash_is_layout( "af" ) || splash_is_layout( "baseball" ) ): ?>
                                            <span class="time"><?php echo esc_html( $time ); ?></span>
                                        <?php else: ?>
                                            <time class="heading-font" datetime="<?php echo esc_attr( $date ) ?>"
                                                  data-countdown="<?php echo esc_attr( str_replace( "-", "/", $date ) ) ?>"></time>
                                        <?php endif; ?>
                                    </div>
                                <?php

                                endif;

                                if( splash_is_layout( "bb" ) ):
                                    if( $show_venue ):
                                        $venues = get_the_terms( $event, 'sp_venue' );
                                        if( $venues ): $venue = array_shift( $venues );
                                            ?>
                                            <div class="venue h6"><?php echo sanitize_text_field( $venue->name ); ?></div>
                                        <?php
                                        endif;
                                    endif;
                                else:
                                    $venues = get_the_terms( $event, 'sp_venue' );
                                    ?>
                                    <div class="venue"><?php echo sanitize_text_field( $venues[ 0 ]->name ); ?></div>
                                <?php
                                endif;
                                ?>

                                <div class="stm-link-wrapp">
                                    <?php
                                    $btnClass = ( splash_is_layout( 'baseball' ) ) ? 'bsbl-btn' : 'button';


                                    echo '<a class="' . $btnClass . '" href="' . get_post_permalink( $event->ID, false, true ) . '">';
                                    if( !splash_is_layout( "baseball" ) ) echo '<i class="fa stm-icon-ico_camera" aria-hidden="true"></i>';
                                    if( $event->post_content !== null ):
                                        if( strtolower( $event->post_status ) == 'publish' ):
                                            esc_html_e( 'Recap', 'splash' );
                                        else:
                                            esc_html_e( 'Preview', 'splash' );
                                        endif;
                                    endif;
                                    echo '</a>';
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                            <?php if( splash_is_layout( "bb" ) || splash_is_layout( "magazine_one" ) ): ?></a><?php endif; ?>
                        <?php elseif( splash_is_layout( "sccr" ) || splash_is_layout( 'soccer_two' ) ) : ?><!--LAYOUT SOCCER-->
                            <div class="vc_latest_result">
                                <div class="fixture_detail clearfix <?php if( empty( $team_results[ 0 ] ) ) echo "stm-event-feature"; ?>">
                                    <div class="command_left">
                                        <div class="command_info <?php echo esc_attr( $postStatus ); ?>">
                                            <div class="logo">
                                                <a href="<?php echo esc_url( get_the_permalink( $team_1_id ) ); ?>"><?php echo get_the_post_thumbnail( $team_1_id, "team_logo" ); ?></a>
                                            </div>
                                            <?php if( !empty( $team_results[ 0 ] ) && $postStatus == "publish" ): ?>
                                                <div class="score heading-font"><?php echo ( isset( $team_results[ 0 ][ $team_1_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] ) ) ? esc_attr( $team_results[ 0 ][ $team_1_id ][ $point_system ] ) : 0; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="goals">
                                            <h2>
                                                <a href="<?php echo esc_url( get_the_permalink( $team_1_id ) ); ?>"><?php echo esc_html( get_the_title( $team_1_id ) ); ?></a>
                                            </h2>
                                            <?php if( !empty( $team_results[ 0 ] ) && $postStatus == "publish" ): ?>
                                                <?php if( !empty( $team_results[ 0 ][ $team_1_id ] ) ): ?>
                                                    <?php if( isset( $team_results[ 0 ][ $team_1_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                        <?php if( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ] == 'win' ): ?>
                                                            <div class="stm-latest-result-win-label heading-font"><?php esc_html_e( 'win', 'splash' ) ?></div>
                                                        <?php else: ?>
                                                            <div class="stm-latest-result-lose-label heading-font"><?php esc_html_e( $team_results[ 0 ][ $team_1_id ][ 'outcome' ][ 0 ], 'splash' ) ?></div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if( $postStatus == "future" ): ?>
                                        <div class="stm-event-vs heading-font">vs</div>
                                    <?php endif; ?>
                                    <div class="command_right">
                                        <div class="command_info <?php echo esc_attr( $postStatus ); ?>">
                                            <div class="logo">
                                                <a href="<?php echo esc_url( get_the_permalink( $team_2_id ) ); ?>"><?php echo get_the_post_thumbnail( $team_2_id, "team_logo" ); ?></a>
                                            </div>
                                            <?php if( !empty( $team_results[ 0 ][ $team_2_id ] ) && $postStatus == "publish" ): ?>
                                                <span class="score heading-font"><?php echo ( isset( $team_results[ 0 ][ $team_2_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ) ) ? esc_attr( $team_results[ 0 ][ $team_2_id ][ $point_system ] ) : 0; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="goals">
                                            <h2>
                                                <a href="<?php echo esc_url( get_the_permalink( $team_2_id ) ); ?>"><?php echo esc_html( get_the_title( $team_2_id ) ); ?></a>
                                            </h2>
                                            <?php if( !empty( $team_results[ 0 ] ) ): ?>
                                                <?php if( !empty( $team_results[ 0 ][ $team_2_id ] ) ): ?>
                                                    <?php if( isset( $team_results[ 0 ][ $team_2_id ][ 'outcome' ] ) and !empty( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] ) ): ?>
                                                        <?php if( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ] == 'win' ): ?>
                                                            <div class="stm-latest-result-win-label heading-font"><?php esc_html_e( 'win', 'splash' ) ?></div>
                                                        <?php else: ?>
                                                            <div class="stm-latest-result-lose-label heading-font"><?php esc_html_e( $team_results[ 0 ][ $team_2_id ][ 'outcome' ][ 0 ], 'splash' ) ?></div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="fixture_info">
                                    <?php
                                    $date = get_the_time( get_option( 'date_format' ), $event->ID );
                                    $time = get_the_time( get_option( 'time_format' ), $event->ID );
                                    ?>

                                    <div class="date"><i class="fa fa-calendar-o"
                                                         aria-hidden="true"></i><?php echo esc_html( $date ); ?></div>
                                    <div class="time">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <?php echo esc_html( $time ); ?>
                                    </div>
                                    <?php
                                    if( $show_venue ):
                                        $venues = get_the_terms( $event, 'sp_venue' );

                                        foreach( $venues as $venue ): ?>
                                            <div class="venue"><i class="fa fa-location-arrow"
                                                                  aria-hidden="true"></i><?php echo esc_html( $venue->name ); ?>
                                            </div>
                                        <?php
                                        endforeach;
                                    endif; ?>
                                    <a class="button button-gray btn-only-border"
                                       href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>">
                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        <span><?php echo ( strtolower( $event->post_status ) !== 'publish' ) ? esc_html__( 'PREVIEW', 'splash' ) : esc_html__( 'RECAP', 'splash' ); ?></span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php } else { ?>
    <div class="sp-template sp-template-event-blocks">
        <div class="sp-table-wrapper">
            <table class="sp-event-blocks sp-data-table<?php if( $paginated ) { ?> sp-paginated-table<?php } ?>"
                   data-sp-rows="<?php echo esc_attr( $rows ); ?>">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead> <?php # Required for DataTables ?>
                <tbody>
                <?php
                $i = 0;

                if( intval( $number ) > 0 )
                    $limit = $number;

                foreach( $data as $event ):
                    if( isset( $limit ) && $i >= $limit ) continue;

                    $permalink = get_post_permalink( $event, false, true );
                    $results = get_post_meta( $event->ID, 'sp_results', true );

                    $teams = array_unique( get_post_meta( $event->ID, 'sp_team' ) );
                    $teams = array_filter( $teams, 'sp_filter_positive' );
                    $logos = array();

                    if( $show_team_logo ):
                        $j = 0;
                        foreach( $teams as $team ):
                            $j++;
                            if( has_post_thumbnail( $team ) ):
                                if( $link_teams ):
                                    $logo = '<a class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" href="' . get_permalink( $team, false, true ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'sportspress-fit-icon' ) . '</a>';
                                else:
                                    $logo = '<span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'sportspress-fit-icon' ) . '</span>';
                                endif;
                                $logos[] = $logo;
                            endif;
                        endforeach;
                    endif;

                    if( 'day' === $calendar->orderby ):
                        $event_group = get_post_meta( $event->ID, 'sp_day', true );
                        if( !isset( $group ) || $event_group !== $group ):
                            $group = $event_group;
                            echo '<tr><th><strong class="sp-event-group-name">', esc_html__( 'Match Day', 'splash' ), ' ', $group, '</strong></th></tr>';
                        endif;
                    endif;
                    ?>
                    <tr class="sp-row heading-font sp-post<?php echo esc_attr( $i % 2 == 0 ? ' alternate' : '' ); ?>">
                        <td>
                            <?php echo implode( ' ', $logos  ); ?>
                            <time class="sp-event-date" datetime="<?php echo esc_attr( $event->post_date ); ?>">
                                <?php echo sp_add_link( get_the_time( get_option( 'date_format' ), $event ), $permalink, $link_events ); ?>
                            </time>
                            <h5 class="sp-event-results">
                                <?php echo sp_add_link( '<span class="sp-result">' . implode( '</span> - <span class="sp-result">', apply_filters( 'sportspress_event_blocks_team_result_or_time', sp_get_main_results_or_time( $event ), $event->ID ) ), $permalink, $link_events . '</span>' ); ?>
                            </h5>
                            <h4 class="sp-event-title">
                                <?php echo sp_add_link( $event->post_title, $permalink, $link_events ); ?>
                            </h4>
                            <?php if( $show_league ): $leagues = get_the_terms( $event, 'sp_league' );
                                if( $leagues ): $league = array_shift( $leagues ); ?>
                                    <div class="sp-event-league"><?php echo esc_attr( $league->name ); ?></div>
                                <?php endif; endif; ?>
                            <?php if( $show_season ): $seasons = get_the_terms( $event, 'sp_season' );
                                if( $seasons ): $season = array_shift( $seasons ); ?>
                                    <div class="sp-event-season"><?php echo esc_attr( $season->name ); ?></div>
                                <?php endif; endif; ?>
                            <?php if( $show_venue ): $venues = get_the_terms( $event, 'sp_venue' );
                                if( $venues ): $venue = array_shift( $venues ); ?>
                                    <div class="sp-event-venue"><?php echo sanitize_text_field( $venue->name ); ?></div>
                                <?php endif; endif; ?>


                        </td>
                    </tr>
                    <?php
                    $i++;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
        <?php
        if( $id && $show_all_events_link )
            echo '<div class="sp-calendar-link sp-view-all-link heading-font"><a href="' . get_permalink( $id ) . '">' . esc_html__( 'View all events', 'splash' ) . '</a></div>';
        ?>
    </div>
<?php }