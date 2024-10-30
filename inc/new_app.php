<?php

function MAB_new_app(){

	global $current_user;



	if (isset($_GET['new_app']) && is_user_logged_in()){


		$theme = get_page_by_title( 'Default', ARRAY_A, 'mab_apps_themes' );

		//echo $theme['ID'];
		//echo 'hello';

		$args = array(
			'post_type' => 'mab_apps_plugins',
			'meta_query' => array(
				array(
					'key' => '_default',
					'value' => 'y',
					'compare' => '=',
				)
			)
		);
		$query = new WP_Query($args,true);
		$posts = $query->get_posts();

		$post_content = $theme['post_content']; // '[facebook {facebookurl} social_plugin="false" posts={num}]';

		$new_post = array(
			'post_title'     => sanitize_text_field($_GET['fbname']),
			'post_content'   => wp_kses_post($post_content),
			'post_type'      => 'mab_apps',

		);
		$new_post['post_status'] = 'publish';


		$new_post_id = wp_insert_post($new_post, true);
		if (is_wp_error($new_post_id)){

			_e('Error the app could not be built','mobile-app-builder');
			exit;
		}


		$json = '{"theme":"'.$theme['ID'].'","jsonstyle":"Default","jsonswatch":"sand","plugins":[{"num":"6","facebookurl":"'.$_GET['fb'].'"}]}';

		update_post_meta($new_post_id, '_json', sanitize_text_field($json) );
		update_post_meta($new_post_id, 'mab_page_id', sanitize_text_field( $_GET['fb'] ) );
		update_post_meta($new_post_id, 'mab_page_name', sanitize_text_field( $_GET['fbname']) );

		foreach($posts as $post) {
			//echo $post->post_title.'<br />';
			$json = (array)$post->_json;

			$json_h = $json[0];
			$json_h = substr($json_h, 0, -1);


			$json_h = $json_h.',"plugin":"'.$post->ID.'","_ID":"'.$new_post_id.'","_order":"100"}';







			$new_post = array(
				'post_title'     => sanitize_text_field($post->post_title),
				'post_content'   => wp_kses_post($post->post_content),
				'post_type'      => 'mab_apps_pages'
			);
			$new_post['post_status'] = 'publish';


			$new_app_id = wp_insert_post($new_post, true);
			if (is_wp_error($new_app_id)){

				_e('Error the app could not be built','mobile-app-builder');
				exit;
			}

			update_post_meta($new_app_id, '_order', '100');
			update_post_meta($new_app_id, '_connect', sanitize_text_field($new_post_id));
			update_post_meta($new_app_id, '_json', sanitize_text_field($json_h));





			// Do your stuff, e.g.
			// echo $post->post_name;


			/*
	    THE PAGE
	    {"name":"facebook2","icon":"arrow-circle-o-down","plugin":"79","_order":"3","_ID":"97","plugins": [{"facebookurl":"https://www.facebook.com/AppDevelopersBiz/","num":"5"}]}

	    {"icon":"facebook","name":"facebook","url":[{"name":"facebookurl","friendly":"Facebook URL","type":"text"}],"num":[{"name":"num","friendly":"Number of Post","type":"text"}],"plugin":79}



	    {"icon":"envelope","name":"email","textbefore":[{"name":"textbefore","friendly":"Text before button","type":"text","hint":"e.g. tap the button below to email us!"}],"emailaddress":[{"name":"emailaddress","friendly":"Full Email Address","type":"text"}],"textafter":[{"name":"textafter","friendly":"Text after button","type":"text"}],"plugin":136}


	    THE THEME

	    {	"icon":"facebook",
"name":"facebook",
	"url":[{ "name": "facebookurl", "friendly": "Facebook URL", "type":"text" }],
	"num":[{ "name": "num", "friendly": "Number of Post", "type":"text" }]
}

    */
		}
		/*
		echo '<hr><pre>';
		print_r($query->posts);
	echo '</pre>';
	*/

		$url = get_permalink(get_option('mab_edit_page_id')).'?mab_action=edit&mab_id='.$new_post_id;

		wp_redirect( $url );
		exit;

	}
}
add_action('init', 'MAB_new_app');



?>