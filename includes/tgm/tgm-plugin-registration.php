<?php

require_once get_template_directory() . '/includes/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'stm_require_plugins' );

function stm_require_plugins() {
    $plugins_path = 'https://splash.stylemixthemes.com/demo-plugins';
	$plugins = array(
		array(
			'name'               => 'STM Configurations',
			'slug'               => 'stm-configurations',
			'source'             => $plugins_path . '/stm-configurations.zip',
			'required'           => true,
			'force_activation'   => false,
			'version'            => '4.0.2'
		),
        array(
            'name'               => 'STM Importer',
            'slug'               => 'stm_importer',
            'source'             => $plugins_path . '/stm_importer.zip',
            'required'           => false,
            'force_activation'   => false,
            'version'			 => '4.0.2'
        ),
        array(
            'name'               => 'WPBakery Visual Composer',
            'slug'               => 'js_composer',
            'source'             => $plugins_path . '/js_composer.zip',
            'required'           => true,
            'force_activation'   => false,
            'version'            => '6.0.5',
            'external_url'       => 'http://vc.wpbakery.com',
        ),
        array(
            'name'               => 'Revolution Slider',
            'slug'               => 'revslider',
            'source'             => $plugins_path . '/revslider.zip',
            'required'           => false,
            'version'            => '6.1.4',
            'external_url'       => 'http://www.themepunch.com/revolution/'
        ),
        array(
            'name'              => 'Breadcrumb NavXT',
            'slug'              => 'breadcrumb-navxt',
            'required'          => false,
            'force_activation'  => false,
        ),
        array(
            'name'              => 'Contact Form 7',
            'slug'              => 'contact-form-7',
            'required'          => false,
            'force_activation'  => false,
        ),
        array(
            'name'              => 'Woocommerce',
            'slug'              => 'woocommerce',
            'required'          => false,
            'force_activation'  => false,
        ),
        array(
            'name'              => 'SportsPress',
            'slug'              => 'sportspress',
            'required'          => true,
            'force_activation'  => false,
        ),
		array(
			'name'         => 'Instagram Feed',
			'slug'         => 'instagram-feed',
			'required'     => false,
			'external_url' => 'http://smashballoon.com/instagram-feed/'
		),
		array(
			'name'         => 'MailChimp for WordPress',
			'slug'         => 'mailchimp-for-wp',
			'required'     => false,
			'external_url' => 'https://mc4wp.com/'
		),
        array(
            'name' => 'AddToAny Share Buttons',
            'slug' => 'add-to-any',
            'required' => false,
            'external_url' => 'https://www.addtoany.com/'
        ),
	);

	$config = array(
		'id' => 'tgm_message_update',
		'strings' => array(
			'nag_type' => 'update-nag'
		)
	);
	
	if (splash_is_layout('af') || splash_is_layout("sccr") || splash_is_layout("hockey")) {
		$plugins[] = array(
			'name' => 'Latest Tweets Widget',
			'slug' => 'latest-tweets-widget',
			'required' => false,
			'external_url' => 'http://timwhitlock.info/'
		);
	}

    if (splash_is_layout('magazine_one') || splash_is_layout('soccer_news')) {
            $plugins[] = array(
                'name' => 'AccessPress Social Counter',
                'slug' => 'accesspress-social-counter',
                'required' => false,
                'external_url' => 'http://accesspressthemes.com'
            );
        }

	tgmpa( $plugins, $config );

}