<?php
/**
 * Team Player Lists
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();
$team = new SP_Team( $id );
$lists = $team->lists();
if(!splash_is_layout("sccr")) :
	foreach ( $lists as $list ):
		$id = $list->ID;
		$grouping = get_post_meta( $id, 'sp_grouping', true );
	
		if ( $grouping == 0 && sizeof( $lists ) > 1 ):
			?>
			<h4 class="sp-table-caption"><?php echo wp_kses_post($list->post_title); ?></h4>
			<?php
		endif;
		$format = get_post_meta( $id, 'sp_format', true );
		if ($format == "list") {
			sp_get_template('player-list.php', array('id' => $id));
		} else {
			sp_get_template('player-gallery.php', array('id' => $id));
		}
	endforeach;
else :
?>
<div class="stm-tabs-wrap stm-team-tabs-wrap">
	<ul class="nav nav-tabs" role="tablist">
		<?php $i = 0;  foreach ( $lists as $list ) { $i++;
			$id = $list->ID;
			$grouping = get_post_meta($id, 'sp_grouping', true);
			if($i==1){
				$tab_class = ' class="active"';
			}else{
				$tab_class = '';
			}
			?>

			<li<?php echo esc_attr($tab_class); ?>><a class="heading-font" href="#<?php echo 'tab_'.esc_attr( $id );?>" role="tab" data-toggle="tab"><?php echo esc_html( $list->post_title ); ?></a></li>
		
		<?php } ?>
	</ul>
	<div class="tab-content">
		<?php $i = 0; foreach ( $lists as $list ) { $i++;
			$id = $list->ID;
			$grouping = get_post_meta($id, 'sp_grouping', true);
			$format = get_post_meta($id, 'sp_format', true);
			if($i==1){
				$tab_class = ' active';
			}else{
				$tab_class = '';
			}
			?>
			<div class="tab-pane fade in<?php echo esc_attr( $tab_class ); ?>" id="<?php echo 'tab_'.esc_attr( $id );?>">
				<?php
				if (array_key_exists($format, SP()->formats->list)) {
					if($format == 'list') sp_get_template('player-list.php', array('id' => $id));
					else sp_get_template('player-' . $format . '-sccr.php', array('id' => $id));
				}else {
					sp_get_template('player-list.php', array('id' => $id));
				}
				?>
			</div>
		<?php } ?>
	</div>

</div>
<?php endif; ?>