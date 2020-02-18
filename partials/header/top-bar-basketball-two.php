<?php
if(get_theme_mod("header_type", 'header_2') == 'header_4'):
?>
<div class="stm-menu-toggle-baseball stm-menu-toggle-af">
	<span></span>
	<span></span>
	<span></span>
</div>
<?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="stm-top-bar_left">
                <div class="stm-top-switcher-holder">
                    <?php get_template_part('partials/header/top-bar-partials/top-bar-switcher'); ?>
                </div>
                <?php
                if(get_theme_mod('top_bar_enable_tickets')): ?>
                    <?php get_template_part('partials/header/top-bar-partials/top-bar-tickets'); ?>
                <?php endif;?>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="clearfix">
                <div class="stm-top-bar_right">
                    <div class="clearfix">
                        <div class="stm-top-profile-holder">
                            <?php get_template_part('partials/header/top-bar-partials/top-bar-profile'); ?>
                        </div>
                        <div class="stm-top-cart-holder">
                            <span>cart</span><?php get_template_part('partials/header/top-bar-partials/top-bar-cart'); ?>
                        </div>
                        <div class="stm-top-socials-holder">
                            <?php get_template_part('partials/header/top-bar-partials/top-bar-socials'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>