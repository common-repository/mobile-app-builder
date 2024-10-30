<?php
if (isset($_GET['mabinstall']) && is_admin()){

	add_action('init', 'mab_setup_pages');

}
function mab_setup_pages()
	{       global $wp_rewrite;
	$u = 0;
	$new_page = array();


	/*
	      	0 - Facebook
      	*/
	$new_page['title'][$u]= 'Facebook';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[facebook {facebookurl} social_plugin="false" posts={num}]';
	$new_page['json'][$u]=  '{	"icon":"facebook","name":"facebook","url":[{ "name": "facebookurl", "friendly": "Facebook URL", "type":"text" }],"num":[{ "name": "num", "friendly": "Number of Post", "type":"text" }]}';
	$new_page['default'][$u]= 'y';
	$u++;

	/*
	      	1 - YouTube
      	*/
	$new_page['title'][$u]= 'Youtube';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[Youtube_Channel_Gallery user="{youtubeuser}" key="AIzaSyC3hOY-8jhpBZCOvLaVgWejMMhdt41Hb6o" maxitems="{youtubemaxitems}" title="{youtubetitle}"]';
	$new_page['default'][$u]= 'n';
	$new_page['json'][$u]=  '{	"icon":"youtube",
"name":"youtube",
	"youtubeuser":[{ "name": "youtubeuser", "friendly": "Youtube Username", "type":"text" }],
	"youtubemaxitems":[{ "name": "youtubemaxitems", "friendly": "Max items", "type":"text" }],
	"youtubetitle":[{ "name": "youtubetitle", "friendly": "Title", "type":"text" }]
}';
	$u++;


	/*
	      	2 - Map
      	*/
	$new_page['title'][$u]= 'Map';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[gmap_mc lat="{gaddresslat}" long="{gaddresslong}" width="{width}" height="{height}"]';
	$new_page['default'][$u]= 'y';
	$new_page['json'][$u]=  '{	"icon":"map",
"name":"map",
	"gaddresslat":[{ "name": "gaddresslat", "friendly": "Pointer LATITUDE", "type":"text" }],
"gaddresslong":[{ "name": "gaddresslong", "friendly": "Pointer LONGITUDE", "type":"text" }],
	"width":[{ "name": "width", "friendly": "Map Width", "type":"text" }],
	"height":[{ "name": "height", "friendly": "Map Height", "type":"text" }]
}';
	$u++;


	/*
	      	3 - Instagram
      	*/
	$new_page['title'][$u]= 'Instagram';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[instagram-feed id="{instauser}" num={instamax} cols={instacols}]';
	$new_page['default'][$u]= 'n';
	$new_page['json'][$u]=  '{	"icon":"instagram",
"name":"instagram",
	"instauser":[{ "name": "instauser", "friendly": "Instagram Username ID", "type":"text","hint":"The ID not the username.<a href=\'https://smashballoon.com/instagram-feed/find-instagram-user-id/\' target=\'_blank\'>Find ID</a>"}],
	"instamax":[{ "name": "instamax", "friendly": "Max photos", "type":"text" }],
	"instacols":[{ "name": "instacols", "friendly": "Number of columns ", "type":"text" }]
}';
	$u++;

	/*
	      	4 - Facebook Gallery
      	*/
	$new_page['title'][$u]= 'Facebook Gallery';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[facebook {facebookurl}  photos={num}]';
	$new_page['default'][$u]= 'y';
	$new_page['json'][$u]=  '{	"icon":"photo",
"name":"fbgallery",
	"url":[{ "name": "facebookurl", "friendly": "Facebook Gallery URL", "type":"text", "hint":"https://www.facebook.com/media/set/?set=a.534936146671269.1073741869.185183464979874" }],
	"num":[{ "name": "num", "friendly": "Number of Post", "type":"text" }]
}';
	$u++;

	/*
	      	5 - Facebook Event
      	*/
	$new_page['title'][$u]= 'Facebook Event';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[facebook {facebookurlevent} posts={num}]';
	$new_page['default'][$u]= 'n';
	$new_page['json'][$u]=  '{	"icon":"calendar",
"name":"fbevents",
	"url":[{ "name": "facebookurlevent", "friendly": "Facebook Events URL", "type":"text", "hint":"https://www.facebook.com/AppDevelopersBiz/events/" }],
"num":[{ "name": "num", "friendly": "Number of events", "type":"text" }]
}';
	$u++;

	/*
	      	7 - Call Us
      	*/
	$new_page['title'][$u]= 'Call Us';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '{textbefore}

<a class="ui-btn" href="tel:{telnumber}">Call us : {telnumber}</a>

{textafter}';
	$new_page['default'][$u]= 'y';
	$new_page['json'][$u]=  '{	"icon":"phone",
"name":"phone",
	"textbefore":[{ "name": "textbefore", "friendly": "Text before button", "type":"text", "hint":"e.g. tap the button below to call us!" }],
"telnumber":[{ "name": "telnumber", "friendly": "Full Telephone Number", "type":"text", "hint":"No spaces"  }],
"textafter":[{ "name": "textafter", "friendly": "Text after button", "type":"text" }]
}';
	$u++;

	/*
	      	8 - Email Us
      	*/
	$new_page['title'][$u]= 'Email Us';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '{textbefore}

<a class="ui-btn" href="mailto:{emailaddress}">{emailaddress}</a>

{textafter}';
	$new_page['default'][$u]= 'n';
	$new_page['json'][$u]=  '{	"icon":"envelope",
"name":"email",
	"textbefore":[{ "name": "textbefore", "friendly": "Text before button", "type":"text", "hint":"e.g. tap the button below to email us!" }],
"emailaddress":[{ "name": "emailaddress", "friendly": "Full Email Address", "type":"text" }],
"textafter":[{ "name": "textafter", "friendly": "Text after button", "type":"text" }]
}';
	$u++;

	/*
	      	9 - Twitter
      	*/
	$new_page['title'][$u]= 'Twitter';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '[twitter title="Title For Tweets" user="{twitterusername}" count="{num}"]';
	$new_page['default'][$u]= 'n';
	$new_page['json'][$u]=  '{	"icon":"twitter",
"name":"twitter",
	"twitterusername":[{ "name": "twitterusername", "friendly": "Twitter Username", "type":"text" }],
	"num":[{ "name": "num", "friendly": "Number of Tweets", "type":"text" }]
}';
	$u++;

	/*
	      	9 - HTML
      	*/
	$new_page['title'][$u]= 'Custom Page';
	$new_page['post_type'][$u]= 'mab_apps_plugins';
	$new_page['content'][$u]= '{htmlelement}';
	$new_page['default'][$u]= 'y';

	$new_page['json'][$u]=  '{	"icon":"file",
"name":"page",
"htmlelement":[{ "name": "htmlelement", "friendly": "Your content", "type":"html"  }]
}';
	$u++;

	for ($x = 0; $x <= $u; $x++) {
		$new_pagefill = '';
		$new_page_id = '';
		if (empty($new_page[$x])) $new_page[$x] = array();
		if (empty( $new_page['post_type'][$x]))  $new_page['post_type'][$x] = "";
		if (empty( $new_page['title'][$x]))  $new_page['title'][$x] = "";
		if (empty( $new_page['content'][$x]))  $new_page['content'][$x] = "";
		if (empty( $new_page['json'][$x])) $new_page['json'][$x] = "";
		if (empty($new_page['default'][$x])) $new_page['default'][$x]= "";

		$new_pagefill = array(
			'post_type' =>  $new_page['post_type'][$x],
			'post_title' =>  $new_page['title'][$x],
			'post_content' =>  $new_page['content'][$x],
			'post_status' => 'publish'
		);

		$new_page_id = wp_insert_post($new_pagefill);

		update_post_meta($new_page_id, '_json',  sanitize_text_field($new_page['json'][$x]));
		update_post_meta($new_page_id, '_default', sanitize_text_field($new_page['default'][$x]));

	}


	/* main pages */

	$new_app = array(
		'post_type' => 'page',
		'post_title' => 'Build a new mobile app',
		'post_content' => '[mab_submission_form]',
		'post_status' => 'publish'
	);

	$new_app_id = wp_insert_post($new_app);

	$new_app = array(
		'post_type' => 'page',
		'post_title' => 'Edit a mobile app',
		'post_content' => '[mab_article_list]',
		'post_status' => 'publish'
	);

	$edit_app_id = wp_insert_post($new_app);

	$new_app = array(
		'post_type' => 'page',
		'post_title' => 'Payment for App',
		'post_content' => '[MAB_PAYPAL_payment]',
		'post_status' => 'publish'
	);

	$payment_page = wp_insert_post($new_app);

	update_option('mab_paypal_page_id', sanitize_text_field($payment_page));
	update_option('mab_edit_page_id', sanitize_text_field($edit_app_id));
	update_option('mab_new_page_id', sanitize_text_field($new_app_id));





	/* THEME SETUP */

	$content = '{htmlelement}
[facebook {facebookurl} social_plugin="false" posts={num}]';
	$json_themes = '{
	"url":[{ "name": "facebookurl", "friendly": "Facebook URL", "type":"text" }],
	"num":[{ "name": "num", "friendly": "Number of Post", "type":"text" }],
"htmlelement":[{ "name": "htmlelement", "friendly": "Your content", "type":"html"  }]
}';

	$new_page_theme = array(
		'post_type' => 'mab_apps_themes',
		'post_title' => 'Default',
		'post_content' => $content,
		'post_status' => 'publish'
	);

	$new_theme_id = wp_insert_post($new_page_theme);

	update_post_meta($new_theme_id, '_json', sanitize_text_field($json_themes));
	update_post_meta($new_theme_id, '_navbar', 'bottom');
	update_post_meta($new_theme_id, '_cover', 'yes');
	update_post_meta($new_theme_id, '_side', 'yes');
	update_post_meta($new_theme_id, '_top', 'yes');

	mab_featured_setup( 'http://app-developers.biz/images_install/default.png',   $new_theme_id );


	$new_page_theme = array(
		'post_type' => 'mab_apps_themes',
		'post_title' => 'Social #2',
		'post_content' => $content,
		'post_status' => 'publish'
	);

	$new_theme_id = wp_insert_post($new_page_theme);

	update_post_meta($new_theme_id, '_json', $json_themes);
	update_post_meta($new_theme_id, '_navbar', 'top');
	update_post_meta($new_theme_id, '_cover', 'yes');
	update_post_meta($new_theme_id, '_side', 'yes');
	update_post_meta($new_theme_id, '_top', 'yes');

	mab_featured_setup( 'http://app-developers.biz/images_install/social2.png',   $new_theme_id );



	$new_page_theme = array(
		'post_type' => 'mab_apps_themes',
		'post_title' => 'Social #3',
		'post_content' => $content,
		'post_status' => 'publish'
	);

	$new_theme_id = wp_insert_post($new_page_theme);

	update_post_meta($new_theme_id, '_json', $json_themes);
	update_post_meta($new_theme_id, '_navbar', 'bottom');
	update_post_meta($new_theme_id, '_cover', 'yes');
	update_post_meta($new_theme_id, '_side', 'no');
	update_post_meta($new_theme_id, '_top', 'no');
	mab_featured_setup( 'http://app-developers.biz/images_install/social3.png',   $new_theme_id );

	$new_page_theme = array(
		'post_type' => 'mab_apps_themes',
		'post_title' => 'Social #4',
		'post_content' => $content,
		'post_status' => 'publish'
	);

	$new_theme_id = wp_insert_post($new_page_theme);

	update_post_meta($new_theme_id, '_json', sanitize_text_field($json_themes));
	update_post_meta($new_theme_id, '_navbar', 'none');
	update_post_meta($new_theme_id, '_cover', 'yes');
	update_post_meta($new_theme_id, '_side', 'yes');
	update_post_meta($new_theme_id, '_top', 'yes');

	mab_featured_setup( 'http://app-developers.biz/images_install/social4.png',   $new_theme_id );
	
	
	update_option( 'my-mab-notice-installed', '1' );
	update_option( 'my-mab-notice-dismissed', '1' );
	
	
	//Call flush_rules() as a method of the $wp_rewrite object
	$wp_rewrite->flush_rules( true );

}

function mab_featured_setup( $image_url, $post_id  ){
	$upload_dir = wp_upload_dir();
	$image_data = file_get_contents($image_url);
	$filename = basename($image_url);
	if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
	else                                    $file = $upload_dir['basedir'] . '/' . $filename;
	file_put_contents($file, $image_data);

	$wp_filetype = wp_check_filetype($filename, null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => sanitize_file_name($filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	$res1= wp_update_attachment_metadata( $attach_id, $attach_data );
	$res2= set_post_thumbnail( $post_id, $attach_id );
}
?>