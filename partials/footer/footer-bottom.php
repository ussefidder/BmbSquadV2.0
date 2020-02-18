<?php
	$footer_bottom_menu = get_theme_mod('enable_footer_bottom_menu', false);

	$footer_left_text = get_theme_mod('footer_left_text', esc_html__('Copyright (c) 2016 Splash.', 'splash'));
	$footer_right_text = get_theme_mod('footer_right_text', esc_html__('Theme by Stylemix Themes.', 'splash'));

	if(splash_is_af()){
		$footer_left_text = str_replace("Splash Theme", "<span class='stm-white'>Splash Theme</span>", get_theme_mod('footer_left_text', esc_html__('Copyright (c) 2016 Splash.', 'splash')));
		$footer_right_text = str_replace("Stylemix Themes", "<a target='_blank' href='https://themeforest.net/item/splash-basketball-sports-wordpress-theme/16751749'><span class='stm-white'>StylemixThemes</span></a>", get_theme_mod('footer_right_text', esc_html__('Theme by Stylemix Themes.', 'splash')));
	} else if(splash_is_layout("sccr")) {
		$footer_left_text = str_replace("Stylemix Themes", "<a target='_blank' href='https://themeforest.net/item/splash-basketball-sports-wordpress-theme/16751749'><span class='stm-red'> Stylemix Themes</span></a>", get_theme_mod('footer_left_text', esc_html__('Theme by Stylemix Themes.', 'splash')));
	} else {
		$footer_right_text = str_replace("Stylemix Themes", "<a target='_blank' href='https://themeforest.net/item/splash-basketball-sports-wordpress-theme/16751749'><span class='stm-white'>StylemixThemes</span></a>", get_theme_mod('footer_right_text', esc_html__('Theme by Stylemix Themes.', 'splash')));
	}

	$stm_socials = splash_socials('footer_socials');
	$footer_socials_text = get_theme_mod('footer_socials_text', esc_html__('Follow Us:', 'splash'));
?>
<div id="stm-footer-bottom">
	<div class="container">
		<div class="clearfix">

			<div class="footer-bottom-left">
				<?php if(!empty($footer_left_text)): ?>
					<div class="footer-bottom-left-text">
						<?php echo wp_kses_post($footer_left_text); ?>
					</div>
				<?php endif; ?>
			</div>


			<div class="footer-bottom-right">
				<div class="clearfix">

					<?php if(!empty($footer_right_text)): ?>
						<div class="footer-bottom-right-text">
							<?php echo wp_kses_post($footer_right_text); ?>
						</div>
					<?php endif; ?>

					<?php if(!empty($stm_socials) && !get_theme_mod("show_socials_after_footer_img", false) && !splash_is_layout('esport')): ?>

						<div class="footer-socials-unit">
							<?php if(!empty($footer_socials_text)): ?>
								<div class="h6 footer-socials-title">
									<?php echo esc_html($footer_socials_text); ?>
								</div>
							<?php endif; ?>
							<ul class="footer-bottom-socials stm-list-duty">
								<?php foreach($stm_socials as $key => $value): ?>
									<li class="stm-social-<?php echo esc_attr($key); ?>">
										<a href="<?php echo esc_url($value); ?>" target="_blank">
											<i class="fa fa-<?php echo esc_attr($key); ?>"></i>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
					<?php if($footer_bottom_menu && !splash_is_layout('soccer_two')): ?>
						<div class="stm-bottom-menu">
							<ul class="footer-menu stm-list-duty heading-font clearfix">
								<?php
								wp_nav_menu( array(
										'menu'              => 'bottom_menu',
										'depth'             => 5,
										'container'         => false,
										'menu_class'        => 'header-menu clearfix',
										'items_wrap'        => '%3$s',
										'link_before'            => '<span>',
										'link_after'             => '</span>',
										'fallback_cb' => false,
										'stm_megamenu' => false
									)
								);
								?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</div>