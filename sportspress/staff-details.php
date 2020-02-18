<?php
/**
 * Staff Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_staff_show_details', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$defaults = array(
	'show_nationality' => get_option( 'sportspress_staff_show_nationality', 'yes' ) == 'yes' ? true : false,
	'show_current_teams' => get_option( 'sportspress_staff_show_current_teams', 'yes' ) == 'yes' ? true : false,
	'show_past_teams' => get_option( 'sportspress_staff_show_past_teams', 'yes' ) == 'yes' ? true : false,
	'show_nationality_flags' => get_option( 'sportspress_staff_show_flags', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'abbreviate_teams' => get_option( 'sportspress_abbreviate_teams', 'yes' ) === 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$countries = SP()->countries->countries;

$staff = new SP_Staff( $id );

$nationalities = $staff->nationalities();
$current_teams = $staff->current_teams();
$past_teams = $staff->past_teams();

$data = array();
if ( $show_nationality && $nationalities && is_array( $nationalities ) ):
	$values = array();
	foreach ( $nationalities as $nationality ):
		if ( 2 == strlen( $nationality ) ):
			$legacy = SP()->countries->legacy;
			$nationality = strtolower( $nationality );
			$nationality = sp_array_value( $legacy, $nationality, null );
		endif;
		$country_name = sp_array_value( $countries, $nationality, null );
		$values[] = $country_name ? ( $show_nationality_flags ? '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/flags/' . strtolower( $nationality ) . '.png" alt="' . $nationality . '"> ' : '' ) . $country_name : '&mdash;';
	endforeach;
	$data[ esc_html__( 'Nationality', 'splash' ) ] = implode( '<br>', $values );
endif;

if ( $show_current_teams && $current_teams ):
	$teams = array();
	foreach ( $current_teams as $team ):
		$team_name = sp_team_short_name( $team );
		if ( $link_teams ) $team_name = '<a href="' . get_post_permalink( $team ) . '">' . $team_name . '</a>';
		$teams[] = $team_name;
	endforeach;
	$data[ esc_html__( 'Current Team', 'splash' ) ] = implode( ', ', $teams );
endif;

if ( $show_past_teams && $past_teams ):
	$teams = array();
	foreach ( $past_teams as $team ):
		$team_name = sp_team_short_name( $team );
		if ( $link_teams ) $team_name = '<a href="' . get_post_permalink( $team ) . '">' . $team_name . '</a>';
		$teams[] = $team_name;
	endforeach;
	$data[ esc_html__( 'Past Teams', 'splash' ) ] = implode( ', ', $teams );
endif;

$data = apply_filters( 'sportspress_staff_details', $data, $id );

if ( empty( $data ) )
	return; ?>
<div class="sp-template sp-template-staff-details sp-template-details">
	<div class="sp-list-wrapper">
		<div class="sp-staff-details">

			<?php foreach ( $data as $label => $value ): ?>
				<div class="single-info">
					<div class="st-label"><?php echo wp_kses_post( $label ); ?></div>
					<div class="st-value"><?php echo wp_kses_post( $value ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div class="clearfix"></div>