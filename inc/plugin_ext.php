<?php

add_action( 'tgmpa_register', 'mab_register_required_plugins' );


function mab_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(


		array(
			'name'      => 'GMap Shortcode',
			'slug'      => 'gmap-shortcode',
			'required'  => true,
		),
		array(
			'name'      => 'Instagram Feed',
			'slug'      => 'instagram-feed',
			'required'  => true,
		),
		array(
			'name'      => 'WP Embed Facebook',
			'slug'      => 'wp-embed-facebook',
			'required'  => true,
		),
		array(
			'name'      => 'Youtube Channel Gallery',
			'slug'      => 'youtube-channel-gallery',
			'required'  => true,
		),
		array(
			'name'      => 'Twitter Feed',
			'slug'      => 'social-shortcodes',
			'required'  => true,
		),


	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'mobile-app-builder',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => __( '<h3>Install Required Plugins for Mobile App Builder</h3>To use the mobile app builder and to give your users some awesome features you will need to install the following plugins.', 'mobile-app-builder' ),                      // Message to output right before the plugins table.


		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'mobile-app-builder' ),
			'menu_title'                      => __( 'Install Plugins', 'mobile-app-builder' ),
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'mobile-app-builder'
			),
			'notice_can_install_required'     => _n_noop(
				'MOBILE APP BUILDER requires the following plugin: %1$s.',
				'MOBILE APP BUILDER requires the following plugins: %1$s.',
				'mobile-app-builder'
			),
		),

	);

	tgmpa( $plugins, $config );
}