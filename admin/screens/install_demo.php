<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}

$check_token = stm_check_token();

/*Check if demo import plugin installed*/
$demo_plugin_installed = false;
if (in_array('stm_importer/stm-importer.php', apply_filters('active_plugins', get_option('active_plugins'))) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network('stm_importer/stm-importer.php'))) {
	$demo_plugin_installed = true;
}
?>
<div class="wrap about-wrap stm-admin-wrap  stm-admin-demos-screen">
	<?php stm_get_admin_tabs('demos'); ?>
	
	<?php if ($demo_plugin_installed): ?>
		<?php
		if ($check_token) {
			stm_demo_import();
		} else {
			echo '<div class="stm-admin-message">';
			esc_html_e('Please make sure you have purchased this theme with the account you registered current token', 'splash');
			echo '</div>';
		}
		?>
	<?php else: ?>
		<?php $plugin = stm_get_plugin_tgm_link('stm_importer/stm-importer.php', 'stm_importer'); ?>
		<div class="theme-browser">
			<div class="theme">
				<div class="theme-wrapper">
					<div class="theme-screenshot">
						<img src="<?php echo stm_get_admin_images_url('importer.svg'); ?>"/>
					</div>
					<h3 class="theme-name"><?php echo esc_html($plugin['name'] . ' ' . $plugin['version']); ?></h3>
					<div class="theme-actions">
						<?php echo wp_kses_post($plugin['plugin_url_activate']); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>