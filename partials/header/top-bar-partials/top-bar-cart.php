<?php
if( function_exists('WC')) {
	$woocommerce_shop_page_id = wc_get_cart_url();
	$top_bar_enable_cart = get_theme_mod('top_bar_enable_cart', true);
}
?>

<?php if(!empty($woocommerce_shop_page_id) and $top_bar_enable_cart): ?>
	<?php
    $items = WC()->cart->cart_contents_count;
    $totalPrice = WC()->cart->get_totals();
    ?>
	<!--Shop archive-->
    <?php if(splash_is_layout('magazine_one') || splash_is_layout('magazine_two') || splash_is_layout('soccer_news')) : ?>
		<div class="help-bar-shop normal_font">
			<a href="<?php echo esc_url($woocommerce_shop_page_id); ?>" title="<?php esc_html_e('Watch shop items', 'splash'); ?>">
				<div class="items-info-wrap">
					<span class="total-price heading-font"><?php echo wc_price($totalPrice['total']); ?></span>
					<span class="normal_font"><span class="stm-current-items-in-cart"><?php echo esc_attr($items)?></span> <?php echo esc_html__('items', 'splash'); ?></span>
				</div>
				<i class="icon-mg-icon-shoping-cart"></i>
			</a>
		</div>
    <?php else: ?>
		<div class="help-bar-shop normal_font stm-cart-af">
			<a href="<?php echo esc_url($woocommerce_shop_page_id); ?>" title="<?php esc_html_e('Watch shop items', 'splash'); ?>">
				<i class="fa fa-shopping-cart"></i>
				<span class="list-label"><?php esc_html_e('Cart', 'splash'); ?></span>
				<span class="list-badge"><span class="stm-current-items-in-cart"><?php echo esc_attr($items); ?></span></span>
			</a>
		</div>
    <?php endif; ?>
<?php endif; ?>