<?php

/*****************************************

	* POST TYPES

******************************************/


 add_action('admin_init','mab_add_role_caps',999);
    function mab_add_role_caps() {

//remove_role( 'mab_app_manager' );


if(get_role('mab_app_manager')  ) {
}else{
	  	
add_role( 'mab_app_manager', 'App Builder', 
array(
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => true,
) );
}

		// Add the roles you'd like to administer the custom post types
		//$roles = array('mab_app_manager');
		
		// Loop through each role and assign capabilities
		

		    $role = get_role('mab_app_manager');
			
	             $role->add_cap( 'read' );
	             $role->add_cap( 'read_mab_apps');
	             $role->add_cap( 'read_private_mab_apps' );
	             $role->add_cap( 'edit_mab_apps' );
	             $role->add_cap( 'edit_published_mab_apps' );
	             $role->add_cap( 'publish_mab_apps' );
	             $role->add_cap( 'delete_mab_apps' );
		
	             $role->add_cap( 'read' );
	             $role->add_cap( 'read_mab_apps_pages');
	             $role->add_cap( 'read_private_mab_apps_pages' );
	             $role->add_cap( 'edit_mab_apps_pages' );
	             $role->add_cap( 'edit_published_mab_apps_pages' );
	             $role->add_cap( 'publish_mab_apps_pages' );
	             $role->add_cap( 'delete_mab_apps_pages' );
		
		

}

add_filter( 'ajax_query_attachments_args', 'mab_show_current_user_attachments' );

function mab_show_current_user_attachments( $query ) {
    $user_id = get_current_user_id();
     global $wp_roles;

    $current_user = wp_get_current_user();
    $roles = $current_user->roles;
    $role = array_shift( $roles );
    if($role == 'mab_app_manager'){
    
    if ( $user_id ) {
        $query['author'] = $user_id;
    }
    return $query;
    }
    return $query;
}
/*
	APPS

*/
add_action( 'init', 'mab_create_posttype' );
function mab_create_posttype() {

	/*
	  APPS

  */
	register_post_type( 'mab_apps',
		array(
			'labels' => array(
				'name' => __( 'Apps' ),
				'singular_name' => __( 'Apps' )
			),
			'public' => true,
				'menu_icon'           => MAB_DIR_URL.'/static/img/app20x20.png',
			'has_archive' => true,
			'rewrite' => array('slug' => 'apps'),
			'capability_type'     => array('mab_app','mab_apps'),
            'map_meta_cap'        => true,
		)
	);

	/*
	  APP Pages

  	*/
	register_post_type( 'mab_apps_pages',
		array(
			'labels' => array(
				'name' => __( 'Apps Pages' ),
				'singular_name' => __( 'Apps Page' )
			),
			'public' => true,
			'menu_icon'           => MAB_DIR_URL.'/static/img/icon-page.png',
			'has_archive' => true,
			'rewrite' => array('slug' => 'appspages'),
			'capability_type'     => array('mab_app_page','mab_apps_pages'),
            'map_meta_cap'        => true,
		)
	);
	/*
	  APP THEMES

  */
	register_post_type( 'mab_apps_themes',
		array(
			'supports' => array( 'title','editor','thumbnail' ),
			'labels' => array(
				'name' => __( 'Apps Themes' ),
				'singular_name' => __( 'Apps Theme' )
			),
			'public' => true,
			'menu_icon'           => MAB_DIR_URL.'/static/img/icon-paint.png',
			'has_archive' => true,
			'rewrite' => array('slug' => 'appsthemes'),
		)
	);


	/*
	  APP Plugins

  	*/
	register_post_type( 'mab_apps_plugins',
		array(
			'supports' => array( 'title','editor' ),
			'labels' => array(
				'name' => __( 'Apps plugins' ),
				'singular_name' => __( 'Apps Plugin' )
			),
			'public' => true,
			'menu_icon'           => MAB_DIR_URL.'/static/img/icon-plug.png',
			'has_archive' => true,
			'rewrite' => array('slug' => 'appsplugins'),
		)
	);

}
/*

	Columns

*/
if(DEBUG_MAB == '0'){
	add_filter( 'manage_edit-mab_apps_columns', 'my_edit_mab_apps_columns' ) ;

	function my_edit_mab_apps_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'titled' => __( 'App Name' ),
			'date' => __( 'Date' ),
			'publish' => __( 'Publish App' ),
		);

		return $columns;
	}

	add_filter( 'manage_edit-mab_apps_pages_columns', 'my_edit_mab_apps_pages_columns' ) ;

	function my_edit_mab_apps_pages_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'pagetitled' => __( 'Page Name' ),
			'appname' => __( 'App Name' ),
			'date' => __( 'Date' )
		);

		return $columns;
	}


	add_action( 'manage_mab_apps_pages_posts_custom_column', 'my_manage_mab_apps_pages_columns', 10, 2 );

	function my_manage_mab_apps_pages_columns( $column, $post_id ) {
		global $post;

		switch( $column ) {

			/* If displaying the 'duration' column. */
		case 'appname' :
			$parent_id = $post->_connect;

			$edit_link = get_permalink(get_option('mab_edit_page_id')).'?mab_action=edit&mab_id='.$parent_id;
			echo $post->_connect .' - '. get_the_title($post->_connect);
			echo ' - <a href="'.$edit_link.'" a>Edit App</a>';

			break;
		case 'pagetitled' :

			echo $post->post_title;
			$parent_id = $post->_connect;
			//$jso = json_decode($post->_json,true);


			$edit_link = get_permalink(get_option('mab_edit_page_id')).'?mab_action=edit&mab_id='.$parent_id;
			$preview_link = get_permalink($parent_id).'?mab_preview_app=1';
			echo '<div class="row-actions"><span class="edit"><a href="'.$edit_link.'" a>Edit App</a> | </span><span class="edit"><a href="'.$preview_link.'" a>Preview App</a> | </span><span class="edit"><a href="http://app-developers.biz/mab-specials/?id='.$parent_id.'" style="color:#36c300;font-weight: bold;">'.__('Publish this app','mobile-app-builder').'</a> </span> </div>';
			break;



			/* Just break out of the switch statement for everything else. */
		default :
			break;
		}
	}

	add_action( 'manage_mab_apps_posts_custom_column', 'my_manage_mab_apps_columns', 10, 2 );

	function my_manage_mab_apps_columns( $column, $post_id ) {
		global $post;

		switch( $column ) {

			/* If displaying the 'duration' column. */
		case 'publish' :

			/* Get the post meta. */
			//$duration = get_post_meta( $post_id, 'duration', true );
			echo '<a href="http://app-developers.biz/mab-specials/?id='.$post_id.'" style="color:#36c300;font-weight: bold;">'.__('Publish this app','mobile-app-builder').'</a>';

			break;
		case 'pagetitled' :

			echo $post->post_title;
			$edit_link = get_permalink(get_option('mab_edit_page_id')).'?mab_action=edit&mab_id='.$post_id;
			$preview_link = get_permalink($post_id).'?mab_preview_app=1';

			//http://localhost/wp/blog/apps/le-rosbif-4/?mab_preview_app=1

			echo '<div class="row-actions"><span class="edit"><a href="'.$edit_link.'" a>Edit App</a> | </span><span class="edit"><a href="'.$preview_link.'" a>Preview App</a> | </span><span class="edit"><a href="http://app-developers.biz/mab-specials/?id='.$post_id.'" style="color:#36c300;font-weight: bold;">'.__('Publish this app','mobile-app-builder').'</a> </span> </div>';
			break;


			/* If displaying the 'genre' column. */
		case 'titled' :

			echo $post->post_title;
			$edit_link = get_permalink(get_option('mab_edit_page_id')).'?mab_action=edit&mab_id='.$post_id;
			$preview_link = get_permalink($post_id).'?mab_preview_app=1';

			//http://localhost/wp/blog/apps/le-rosbif-4/?mab_preview_app=1

			echo '<div class="row-actions"><span class="edit"><a href="'.$edit_link.'" a>Edit App</a> | </span><span class="edit"><a href="'.$preview_link.'" a>Preview App</a> | </span><span class="edit"><a href="http://app-developers.biz/mab-specials/?id='.$post_id.'" style="color:#36c300;font-weight: bold;">'.__('Publish this app','mobile-app-builder').'</a> </span> </div>';
			break;

			/* Just break out of the switch statement for everything else. */
		default :
			break;
		}
	}


	function mab_disable_new_posts() {
		// Hide sidebar link
		global $submenu;
		unset($submenu['edit.php?post_type=mab_apps'][10]);
		unset($submenu['edit.php?post_type=mab_apps_pages'][10]);
		// Hide link on listing page
		if (isset($_GET['post_type']) && ($_GET['post_type'] == 'mab_apps' || $_GET['post_type'] == 'mab_apps_pages')) {
			echo '<style type="text/css">
.page-title-action { display:none; }
</style>';
		}
	}
	add_action('admin_menu', 'mab_disable_new_posts');
}
/*

	Columns

*/

add_action( 'add_meta_boxes', 'mab_add_metaboxes' );
function mab_add_metaboxes() {
	add_meta_box('mab_json_app', 'JSON for fields', 'mab_json_themes', 'mab_apps', 'normal', 'default');

	add_meta_box('mab_background', 'App Background image', 'mab_background', 'mab_apps', 'side', 'default');

	add_meta_box('mab_mab_icon', 'App Icon', 'mab_icon', 'mab_apps', 'side', 'default');

	add_meta_box('mab_splash', 'App Splash', 'mab_splash', 'mab_apps', 'side', 'default');
	add_meta_box('mab_page_name', 'Page name', 'mab_page_name', 'mab_apps', 'side', 'default');
	add_meta_box('mab_page_id', 'Page ID', 'mab_page_id', 'mab_apps', 'side', 'default');



	add_meta_box('mab_json_themes', 'JSON for fields', 'mab_json_themes', 'mab_apps_themes', 'normal', 'default');

	add_meta_box('mab_setup_themes', 'Theme Setup', 'mab_setup_themes', 'mab_apps_themes', 'side', 'default');


	add_meta_box('mab_json_plugins', 'JSON for fields', 'mab_json_themes', 'mab_apps_plugins', 'normal', 'default');
	add_meta_box('mab_json_pages', 'JSON for fields', 'mab_json_themes', 'mab_apps_pages', 'normal', 'default');

	add_meta_box('mab_default_plugins', 'Use in default install', 'mab_plugin_defaults', 'mab_apps_plugins', 'side', 'default');


	add_meta_box('mab_theme_css', 'Extra CSS', 'mab_theme_css', 'mab_apps_themes', 'normal', 'default');


	add_meta_box('mab_json_pages_connect', 'connect to post', 'mab_connect', 'mab_apps_pages', 'side', 'default');

}

/* CONNECT */
function mab_connect() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="meta_noncename" id="meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$value = get_post_meta($post->ID, '_connect', true);
	// Echo out the field
	echo '<input type="text" name="_connect"  class="widefat" value="'.$value.'">';

	$valueOrder = get_post_meta($post->ID, '_order', true);
	if($valueOrder == ''){ $valueOrder = '0';}
	echo '<input type="text" name="_order"  class="widefat" value="'.$valueOrder.'">';

}


/* JSON */



/* JSON */
function mab_json_themes() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="meta_noncename" id="meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$value = get_post_meta($post->ID, '_json', true);
	// Echo out the field
	echo '<textarea type="text" name="_json"  class="widefat" />'.$value.'</textarea>';

}


/* CSS */
function mab_theme_css() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="meta_noncename" id="meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$value = get_post_meta($post->ID, '_css', true);
	// Echo out the field
	echo '<textarea type="text" name="_css"  class="widefat" />'.$value.'</textarea>';

}


/* Theme setup */
function mab_setup_themes() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="meta_noncename" id="meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered

	// Echo out the field

	_e('NavBar ? ','mobile-app-builder');
	$mab_themes= array('top','bottom','none');
	$value = get_post_meta($post->ID, '_navbar', true);
	echo '<select name="_navbar" >';
	foreach($mab_themes as $val):
		if($value == $val) $n = "selected";
		else $n = "";

		echo '<option value="'.$val.'" '.$n.'>'.$val.'</option>';
	endforeach;

	echo '</select><br />';



	_e('Top Bar ? ','mobile-app-builder');
	$mab_themes= array('yes','no');
	$value = get_post_meta($post->ID, '_top', true);
	echo '<select name="_top" >';


	foreach($mab_themes as $val):
		if($value == $val) $n = "selected";
		else $n = "";

		echo '<option value="'.$val.'" '.$n.'>'.$val.'</option>';
	endforeach;

	echo '</select><br />';



	_e('Side Bar ? ','mobile-app-builder');
	$mab_themes= array('yes','no');
	$value = get_post_meta($post->ID, '_side', true);
	echo '<select name="_side" >';


	foreach($mab_themes as $val):
		if($value == $val) $n = "selected";
		else $n = "";

		echo '<option value="'.$val.'" '.$n.'>'.$val.'</option>';
	endforeach;

	echo '</select><br />';


	_e('Cover Photo ? ','mobile-app-builder');
	$mab_themes= array('yes','no');
	$value = get_post_meta($post->ID, '_cover', true);
	echo '<select name="_cover" >';


	foreach($mab_themes as $val):
		if($value == $val) $n = "selected";
		else $n = "";

		echo '<option value="'.$val.'" '.$n.'>'.$val.'</option>';
	endforeach;

	echo '</select><br />';



}

function mab_plugin_defaults() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="meta_noncename" id="meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$value = get_post_meta($post->ID, '_default', true);
	// Echo out the field
	_e('Install this as a default? ','mobile-app-builder');

	if($value == 'y'){
		$y = "selected";
		$n = "";
	}else{
		$y = "";
		$n = "selected";
	}
	echo '<select name="_default" >';
	echo '<option value="y" '.$y.'>YES</option>';
	echo '<option value="n" '.$n.'>NO</option>';
	echo '</select>';

	//echo '<textarea type="text" name="_json"  class="widefat" />'..'</textarea>';

}

function mab_icon() {
	global $post;
	// Noncename needed to verify where the data originated

	$icon = get_post_meta($post->ID, 'icon', true);

	echo '<input type="text" name="icon"  class="widefat" value="'.$icon.'">';

}

function mab_splash() {
	global $post;
	// Noncename needed to verify where the data originated

	$splash = get_post_meta($post->ID, 'splash', true);

	echo '<input type="text" name="splash"  class="widefat" value="'.$splash.'">';

	//echo '<textarea type="text" name="_json"  class="widefat" />'.$value.'</textarea>';

}
function mab_page_name() {
	global $post;
	// Noncename needed to verify where the data originated

	$mab_page_name = get_post_meta($post->ID, 'mab_page_name', true);

	echo '<input type="text" name="mab_page_name"  class="widefat" value="'.$mab_page_name.'">';

	//echo '<textarea type="text" name="_json"  class="widefat" />'.$value.'</textarea>';
}
function mab_page_id() {
	global $post;
	// Noncename needed to verify where the data originated

	$mab_page_name = get_post_meta($post->ID, 'mab_page_id', true);

	echo '<input type="text" name="mab_page_id"  class="widefat" value="'.$mab_page_name.'">';

	//echo '<textarea type="text" name="_json"  class="widefat" />'.$value.'</textarea>';
}



function mab_background() {
	global $post;
	// Noncename needed to verify where the data originated

	$background = get_post_meta($post->ID, 'background', true);

	echo '<input type="text" name="background"  class="widefat" value="'.$background.'">';

}


function mab_save_meta($post_id, $post) {

	if(!isset($_POST['meta_noncename'])){$_POST['meta_noncename'] = '';}
	if ( !wp_verify_nonce( $_POST['meta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	$json_meta['_json'] = sanitize_text_field($_POST['_json']);

	foreach ($json_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, sanitize_text_field($value) );
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, sanitize_text_field($value) );
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}


	$json_meta['_css'] = sanitize_text_field($_POST['_css']);

	foreach ($json_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
	/* THEMES */


	$json_meta['_navbar'] = sanitize_text_field($_POST['_navbar']);
	$json_meta['_top'] = sanitize_text_field($_POST['_top']);
	$json_meta['_side'] = sanitize_text_field($_POST['_side']);
	$json_meta['_cover'] = sanitize_text_field($_POST['_cover']);

	foreach ($json_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}


	/* THEMES */


	$json_meta['_default'] = sanitize_text_field($_POST['_default']);

	foreach ($json_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}


	$json_meta['_connect'] = sanitize_text_field($_POST['_connect']);

	foreach ($json_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}




	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	if(get_post_meta($post->ID, 'mab_page_name', FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, 'mab_page_name', $_POST['mab_page_name']);
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, 'mab_page_name', $_POST['mab_page_name']);
	}
	if(!$_POST['mab_page_name']) delete_post_meta($post->ID, $_POST['mab_page_name']); // Delete if blank


	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	if(get_post_meta($post->ID, 'mab_page_id', FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, 'mab_page_id', $_POST['mab_page_id']);
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, 'mab_page_id', $_POST['mab_page_id']);
	}
	if(!$_POST['mab_page_id']) delete_post_meta($post->ID, $_POST['mab_page_id']); // Delete if blank




	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	if(get_post_meta($post->ID, 'background', FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, 'background', sanitize_text_field($_POST['background']));
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, 'background', sanitize_text_field($_POST['background']));
	}
	if(!$_POST['background']) delete_post_meta($post->ID, sanitize_text_field($_POST['background'])); // Delete if blank


	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	if(get_post_meta($post->ID, 'splash', FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, 'splash', sanitize_text_field($_POST['splash']));
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, 'splash', sanitize_text_field($_POST['splash']));
	}
	if(!$_POST['splash']) delete_post_meta($post->ID, sanitize_text_field($_POST['splash'])); // Delete if blank


	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	if(get_post_meta($post->ID, 'icon', FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, 'icon', $_POST['icon']);
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, 'icon', $_POST['icon']);
	}
	if(!$_POST['icon']) delete_post_meta($post->ID, $_POST['icon']); // Delete if blank



	$json_meta['_order'] = sanitize_text_field($_POST['_order']);

	foreach ($json_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'mab_save_meta', 1, 2); // save the custom fields

?>