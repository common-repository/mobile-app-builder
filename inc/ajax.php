<?php

/**
 * Matches submitted content against restrictions set in the options panel
 *
 * @param array $content The content to check
 * @return string: An html string of errors
 */



function mab_post_has_errors($content)
{
	$mab_plugin_options = get_option('mab_post_restrictions');
	$mab_messages = mab_messages();
	$min_words_title = $mab_plugin_options['min_words_title'];
	$max_words_title = $mab_plugin_options['max_words_title'];
	$min_words_content = $mab_plugin_options['min_words_content'];
	$max_words_content = $mab_plugin_options['max_words_content'];
	$min_words_bio = $mab_plugin_options['min_words_bio'];
	$max_words_bio = $mab_plugin_options['max_words_bio'];
	$max_links = $mab_plugin_options['max_links'];
	$max_links_bio = $mab_plugin_options['max_links_bio'];
	$min_tags = $mab_plugin_options['min_tags'];
	$max_tags = $mab_plugin_options['max_tags'];
	$thumb_required = $mab_plugin_options['thumbnail_required'];
	$error_string = '';
	$format = '%s<br/>';

	if (($min_words_title && empty($content['post_title'])) || ($min_words_content && empty($content['post_content'])) || ($min_words_bio && empty($content['about_the_author'])) || ($min_tags && empty($content['post_tags']))) {
		$error_string .= sprintf($format, $mab_messages['required_field_error']);
	}

	$tags_array = explode(',', $content['post_tags']);
	$stripped_bio = strip_tags($content['about_the_author']);
	$stripped_content = strip_tags($content['post_content']);

	if (!empty($content['post_title']) && str_word_count($content['post_title']) < $min_words_title)
		$error_string .= sprintf($format, $mab_messages['title_short_error']);
	if (!empty($content['post_title']) && str_word_count($content['post_title']) > $max_words_title)
		$error_string .= sprintf($format, $mab_messages['title_long_error']);
	if (!empty($content['post_content']) && str_word_count($stripped_content) < $min_words_content)
		$error_string .= sprintf($format, $mab_messages['article_short_error']);
	if (str_word_count($stripped_content) > $max_words_content)
		$error_string .= sprintf($format, $mab_messages['article_long_error']);
	if (!empty($content['about_the_author']) && $stripped_bio != -1 && str_word_count($stripped_bio) < $min_words_bio)
		$error_string .= sprintf($format, $mab_messages['bio_short_error']);
	if ($stripped_bio != -1 && str_word_count($stripped_bio) > $max_words_bio)
		$error_string .= sprintf($format, $mab_messages['bio_long_error']);
	if (substr_count($content['post_content'], '</a>') > $max_links)
		$error_string .= sprintf($format, $mab_messages['too_many_article_links_error']);
	if (substr_count($content['about_the_author'], '</a>') > $max_links_bio)
		$error_string .= sprintf($format, $mab_messages['too_many_bio_links_error']);
	if (!empty($content['post_tags']) && count($tags_array) < $min_tags)
		$error_string .= sprintf($format, $mab_messages['too_few_tags_error']);
	if (count($tags_array) > $max_tags)
		$error_string .= sprintf($format, $mab_messages['too_many_tags_error']);
	if ($thumb_required == 'true' && $content['featured_img'] == -1)
		$error_string .= sprintf($format, $mab_messages['featured_image_error']);

	if (str_word_count($error_string) < 2)
		return false;
	else
		return $error_string;
}

/**
 * Ajax function for fetching a featured image
 *
 * @uses array $_POST The id of the image
 * @return string: A JSON encoded string
 */
function mab_fetch_featured_image()
{
	$image_id = sanitize_text_field($_POST['img']);
	echo wp_get_attachment_image($image_id, array(200, 200),"", array('class' => 'appimages'));
	die();
}

add_action('wp_ajax_mab_fetch_featured_image', 'mab_fetch_featured_image');

function mab_fetch_featured_image_splash()
{
	$image_id = sanitize_text_field($_POST['img']);
	echo wp_get_attachment_image($image_id, array(640, 960),"", array('class' => 'appimages'));
	die();
}

add_action('wp_ajax_mab_fetch_featured_image_splash', 'mab_fetch_featured_image_splash');


function mab_fetch_featured_image_background()
{
	$image_id = sanitize_text_field($_POST['img']);
	echo wp_get_attachment_image($image_id, array(640, 960),"", array('class' => 'appimages'));
	die();
}

add_action('wp_ajax_mab_fetch_featured_image_background', 'mab_fetch_featured_image_background');

/**
 * Ajax function for deleting a post
 *
 * @uses array $_POST The id of the post and a nonce value
 * @return string: A JSON encoded string
 */
function mab_delete_posts()
{
	try {
		if (!wp_verify_nonce(sanitize_text_field($_POST['delete_nonce']), 'fepnonce_delete_action'))
			throw new Exception(__('Sorry! You failed the security check', 'mobile-app-builder'), 1);

		if (!current_user_can('delete_post', sanitize_text_field($_POST['post_id'])))
			throw new Exception(__("You don't have permission to delete this post", 'mobile-app-builder'), 1);

		$result = wp_delete_post(sanitize_text_field($_POST['post_id']), true);
		if (!$result)
			throw new Exception(__("The article could not be deleted", 'mobile-app-builder'), 1);

		$data['success'] = true;
		$data['message'] = __('The article has been deleted successfully!', 'mobile-app-builder');
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = $ex->getMessage();
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_delete_posts', 'mab_delete_posts');
add_action('wp_ajax_nopriv_mab_delete_posts', 'mab_delete_posts');


//! Page forms
/* TODO: Hide the forms */
function mab_ajax_handle_request()
{
	/*
	try{
	if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

	}*/

	$postID = sanitize_text_field($_POST['id']);
	if (isset($_POST['id'])){
		$post_id = sanitize_text_field($_POST['id']);
	}else{
		$post_id = "";
	}

	global $post;
	$post = get_post($postID);
	$post_json = get_post_meta($postID,'_json');
	$response = array(
		'sucess' => true,
		'post' => $post,
		'id' => $postID ,
		'_json' => $post_json[0],
	);

	//echo $post_json[0];
	$jsoncode = json_decode($post_json[0],true);

	$jsonplugins = $jsoncode['plugins'][0];

	$theplugin = $jsoncode['plugin'];
	$theorder = get_post_meta($postID,'_order',true);
	$thepluginjson = get_post_meta($theplugin,'_json'); //html form properties from Apps Plugins

	$jsonform = json_decode($thepluginjson[0],true);
	//print_r($jsonform);
	echo '<input type="hidden" name="icon" id="pageicon" value="'.$jsonform['icon'].'" class="mabtextmain">';
	echo '<input type="hidden" name="plugin" value="'.$theplugin.'" class="mabtextmain">';
	echo '<input type="hidden" name="_order" value="'.$theorder.'" class="mabtextmain">';
	echo '<input type="hidden" name="_ID" id="_ID" value="'.$postID.'" class="mabtextmain">';

	echo '<div style="clear:both"></div>';
	foreach($jsonform as $key => $var){

		if(strlen($var[0]['friendly']) <= 1){ continue; }

		if(isset($var[0]['name'])){

			$jval = $var[0]['name'];
			$jval = $jsonplugins[$jval];
		}else{

			$jval = '';
		}

		if(isset($var[0]['hint'])){
			$hint =  $var[0]['hint'];
		}else{
			$hint = '';
		}
		if($var[0]['type'] == 'text' || $var[0]['type']== ''){

			echo '<p><label>'.$var[0]['friendly'].'</label> <input placeholder="'.$var[0]['friendly'].'" data-friendly="'.$var[0]['friendly'].'" type="'.$var[0]['type'].'" value="'.$jval.'" name="'.$var[0]['name'].'" class="mabtextedit '.$var[0]['name'].'" ><br><small class="mabhint">'.$hint.'</small></p>';
		}
		else if($var[0]['type'] == 'html'){
				echo '<p><label>'.$var[0]['friendly'].'</label> </p><p>
		<textarea data-friendly="'.$var[0]['friendly'].'" id="blurb"  name="'.$var[0]['name'].'" class="" '.$var[0]['name'].'">'.base64_decode($jval).'</textarea>

		<input data-friendly="'.$var[0]['friendly'].'" id="blurbHidden" type="hidden" value="'.$jval.'" name="'.$var[0]['name'].'" class="mabtextedit '.$var[0]['name'].'" >
		<br><small class="mabhint">'.$hint.'</small></p>';

			}

	}


	echo '<a href="#" class="btnSave peter-river-flat-button" >'.__("Save page settings", 'mobile-app-builder').'</a>';
	// generate the response
	// print json_encode($response);

	//NOTE: ADD VISUAL EDITOR HERE

	//$mytext_var="Some Text"; // this var may contain previous data that was stored in mysql.
	//wp_editor($mytext_var,"mytext", array('textarea_rows'=>12, 'editor_class'=>'mytext_class'));


	exit;
}

add_action('wp_ajax_mab_ajax_handle_request', 'mab_ajax_handle_request');
add_action('wp_ajax_nopriv_mab_ajax_handle_request', 'mab_ajax_handle_request');

//! Page forms
/* TODO: Hide the forms */
function mab_ajax_refresh_tabs()
{
	/*
	try{
	if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

	}*/

	$post_id = sanitize_text_field($_POST['id']);

	include(MAB_DIR.'/inc/tabs.php');

	exit;
}

add_action('wp_ajax_mab_ajax_refresh_tabs', 'mab_ajax_refresh_tabs');
add_action('wp_ajax_nopriv_mab_ajax_refresh_tabs', 'mab_ajax_refresh_tabs');


//! Save style chages color and temp
/**
 * Ajax function  Save style chages color and temp
 *
 * @uses array $_POST The user submitted post
 * @return string: A JSON encoded string
 */
function mab_ajax_save_style()
{
	$mab_messages = mab_messages();
	try {
		if (!wp_verify_nonce(sanitize_text_field($_POST['post_nonce']), 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post', sanitize_text_field($_POST['post_id'])))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);

		$json = get_post_meta(sanitize_text_field($_POST['post_id']), '_json', true);
		$jsonData = json_decode($json,true);
		//print_r($jsonData);

		if($_POST['field']=='jsonstyle'){
			$jsonData['jsonstyle'] = sanitize_text_field($_POST['value']);
			$jsonData['theme'] = sanitize_text_field($_POST['value_id']);
		}

		if($_POST['field']=='jsonswatch'){
			$jsonData['jsonswatch'] = sanitize_text_field($_POST['value']);
		}

		$jsonDataEncoded = json_encode($jsonData);
		print_r($jsonDataEncoded);
		if(isset($_POST['post_id'])):
			update_post_meta(sanitize_text_field($_POST['post_id']), '_json', $jsonDataEncoded);
		endif;

	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_ajax_save_style', 'mab_ajax_save_style');
add_action('wp_ajax_nopriv_mab_ajax_save_style', 'mab_ajax_save_style');



/*

	IMAGES UPLOAD

*/
//!image upload

function mab_ajax_update_images()
{
	$mab_messages = mab_messages();
	try {
		if (!wp_verify_nonce(sanitize_text_field($_POST['post_nonce']), 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);
		$post__id = sanitize_text_field($_POST['post_id']);
		if ($post__id != -1 && !current_user_can('edit_post', $post__id))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);

		if(isset($_POST['icon'])):
			update_post_meta($post__id, 'icon', sanitize_text_field($_POST['icon']));
		endif;
		if(isset($_POST['splash'])):
			update_post_meta($post__id, 'splash', sanitize_text_field($_POST['splash']));
		endif;
		if(isset($_POST['background'])):
			update_post_meta($post__id, 'background', sanitize_text_field($_POST['background']));
		endif;

	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_ajax_update_images', 'mab_ajax_update_images');
add_action('wp_ajax_nopriv_mab_ajax_update_images', 'mab_ajax_update_images');




/**
 * Ajax function for adding a new post.
 *
 * @uses array $_POST The user submitted post
 * @return string: A JSON encoded string
 */
function mab_process_form_input()
{
	$mab_messages = mab_messages();
	try {
		if (!wp_verify_nonce(sanitize_text_field($_POST['post_nonce']), 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post', sanitize_text_field($_POST['post_id'])))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);

		$mab_role_settings = get_option('mab_role_settings');
		$mab_misc = get_option('mab_misc');

		if ($mab_role_settings['no_check'] && current_user_can($mab_role_settings['no_check']))
			$errors = false;
		else
			$errors = mab_post_has_errors($_POST);

		if ($errors)
			throw new Exception($errors, 1);

		if ($mab_misc['nofollow_body_links'])
			$post_content = wp_rel_nofollow(sanitize_text_field($_POST['post_content']));
		else
			$post_content = sanitize_text_field($_POST['post_content']);


		$current_post = empty($_POST['post_id']) ? null : get_post(sanitize_text_field($_POST['post_id']));
		$current_post_date = is_a($current_post, 'WP_Post') ? $current_post->post_date : '';

		$new_post = array(
			'post_title'     => sanitize_text_field($_POST['post_title']),
			'post_category'  => array(sanitize_text_field($_POST['post_category'])),
			'tags_input'     => sanitize_text_field($_POST['post_tags']),
			'post_content'   => wp_kses_post($post_content),
			'post_date'      => $current_post_date,
			'post_type'      => 'mab_apps',

			'comment_status' => get_option('default_comment_status')
		);

		if ($mab_role_settings['instantly_publish'] && current_user_can($mab_role_settings['instantly_publish'])) {
			$post_action = __('published', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __('submitted', 'mobile-app-builder');
			$new_post['post_status'] = 'pending';
		}

		if ($_POST['post_id'] != -1) {
			$new_post['ID'] = sanitize_text_field($_POST['post_id']);
			$post_action = __('updated', 'mobile-app-builder');
		}

		$new_post_id = wp_insert_post($new_post, true);
		if (is_wp_error($new_post_id))
			throw new Exception($new_post_id->get_error_message(), 1);



		if ($_POST['featured_img'] != -1)
			set_post_thumbnail($new_post_id, sanitize_text_field($_POST['featured_img']));

		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="mab-continue-editing">%s</a>',
			sprintf(__('Your article has been %s successfully!', 'mobile-app-builder'), $post_action),
			__('Continue Editing', 'mobile-app-builder')
		);
		if(isset($_POST['post_id'])):
			update_post_meta($data['post_id'], '_json', sanitize_text_field($_POST['_json']));
		endif;
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_process_form_input', 'mab_process_form_input');
add_action('wp_ajax_nopriv_mab_process_form_input', 'mab_process_form_input');



//! edit app pages
/**
 * Ajax function for adding a new post.
 *
 * @uses array $_POST The user submitted post
 * @return string: A JSON encoded string
 */
function mab_ajax_save_pages()
{
	$mab_messages = mab_messages();
	try {

		//echo "hello world";


		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post',sanitize_text_field($_POST['post_id'])))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);

		$mab_role_settings = get_option('mab_role_settings');
		$mab_misc = get_option('mab_misc');

		if ($mab_role_settings['no_check'] && current_user_can($mab_role_settings['no_check']))
			$errors = false;
		else
			$errors = mab_post_has_errors($_POST);


		$current_post = empty($_POST['post_id']) ? null : get_post($_POST['post_id']);
		$current_post_date = is_a($current_post, 'WP_Post') ? $current_post->post_date : '';

		$new_post = array(
			'post_title'     => sanitize_text_field($_POST['post_title']),
			'comment_status' => get_option('default_comment_status')
		);

		if ($mab_role_settings['instantly_publish'] && current_user_can($mab_role_settings['instantly_publish'])) {
			$post_action = __('published', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __('submitted', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		}

		if ($_POST['post_id'] != -1) {
			$new_post['ID'] = $_POST['post_id'];
			$post_action = __('updated', 'mobile-app-builder');
		}

		$my_post = array(
			'ID'           => sanitize_text_field($_POST['post_id']),
			'post_title'   => sanitize_text_field($_POST['post_title'])
		);

		// Update the post into the database
		wp_update_post( $my_post );
		$post___id = sanitize_text_field($_POST['post_id']);
		if(isset($post___id)):
			update_post_meta($post___id, '_json', sanitize_text_field($_POST['arrayjs']));
		endif;
		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="mab-continue-editing">%s</a>',
			sprintf(__('Your article has been %s successfully!', 'mobile-app-builder'), $post_action),
			__('Continue Editing', 'mobile-app-builder')
		);

	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}

	die(json_encode($data));
}

add_action('wp_ajax_mab_ajax_save_pages', 'mab_ajax_save_pages');
add_action('wp_ajax_nopriv_mab_ajax_save_pages', 'mab_ajax_save_pages');



/*
	HOME PAGE SAVE

*/


function mab_ajax_save_homepages()
{
	$mab_messages = mab_messages();
	try {

		//echo "hello world";


		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post', sanitize_text_field($_POST['post_id'])))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);

		$mab_role_settings = get_option('mab_role_settings');
		$mab_misc = get_option('mab_misc');

		if ($mab_role_settings['no_check'] && current_user_can($mab_role_settings['no_check']))
			$errors = false;
		else
			$errors = mab_post_has_errors($_POST);


		$current_post = empty($_POST['post_id']) ? null : get_post(sanitize_text_field($_POST['post_id']));
		$current_post_date = is_a($current_post, 'WP_Post') ? $current_post->post_date : '';

		$new_post = array(
			'post_title'     => sanitize_text_field($_POST['post_title']),
			'comment_status' => get_option('default_comment_status')
		);

		if ($mab_role_settings['instantly_publish'] && current_user_can($mab_role_settings['instantly_publish'])) {
			$post_action = __('published', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __('submitted', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		}

		if ($_POST['post_id'] != -1) {
			$new_post['ID'] = sanitize_text_field($_POST['post_id']);
			$post_action = __('updated', 'mobile-app-builder');
		}

		$my_post = array(
			'ID'           => sanitize_text_field($_POST['post_id']),
			'post_title'   => sanitize_text_field($_POST['post_title'])
		);

		// Update the post into the database
		wp_update_post( $my_post );

		if(isset($_POST['post_id'])):
			$current = get_post_meta( sanitize_text_field($_POST['post_id']), '_json', true );

		$current = json_decode($current,true);

		$new = json_decode(stripslashes(sanitize_text_field($_POST['arrayjs'])),true);

		$final = array_replace($current,$new);
		$final = json_encode($final);
		//print_r($final);
		update_post_meta(sanitize_text_field($_POST['post_id']), '_json', sanitize_text_field($final) );
		endif;
		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="mab-continue-editing">%s</a>',
			sprintf(__('Your article has been %s successfully!', 'mobile-app-builder'), $post_action),
			__('Continue Editing', 'mobile-app-builder')
		);

	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}

	die(json_encode($data));
}

add_action('wp_ajax_mab_ajax_save_homepages', 'mab_ajax_save_homepages');
add_action('wp_ajax_nopriv_mab_ajax_save_homepages', 'mab_ajax_save_homepages');




/**
 * Ajax function for adding a new post.
 *
 * @uses array $_POST The user submitted post
 * @return string: A JSON encoded string
 */
function mab_process_app_pages_new()
{
	$mab_messages = mab_messages();
	try {
		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post', sanitize_text_field($_POST['post_id'])))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);

		$mab_role_settings = get_option('mab_role_settings');
		$mab_misc = get_option('mab_misc');

		if ($mab_role_settings['no_check'] && current_user_can($mab_role_settings['no_check']))
			$errors = false;
		else
			$errors = false;

		if ($errors)
			throw new Exception($errors, 1);

		if ($mab_misc['nofollow_body_links'])
			$post_content = wp_rel_nofollow(sanitize_text_field($_POST['post_content']));
		else
			$post_content = sanitize_text_field($_POST['post_content']);


		$jso = base64_decode($_POST['_json']);
		$current_post = empty($_POST['post_id']) ? null : get_post($_POST['post_id']);
		$current_post_date = is_a($current_post, 'WP_Post') ? $current_post->post_date : '';

		$post_content = base64_decode($post_content);

		$new_post = array(
			'post_title'     => sanitize_text_field($_POST['post_title']),
			'post_category'  => array($_POST['post_category']),
			'tags_input'     => sanitize_text_field($_POST['post_tags']),
			'post_content'   => wp_kses_post($post_content),
			'post_date'      => $current_post_date,
			'post_type'      => 'mab_apps_pages',

			'comment_status' => get_option('default_comment_status')
		);

		if ($mab_role_settings['instantly_publish'] && current_user_can($mab_role_settings['instantly_publish'])) {
			$post_action = __('published', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __('submitted', 'mobile-app-builder');
			$new_post['post_status'] = 'publish';
		}

		if ($_POST['post_id'] != -1) {
			$new_post['ID'] = $_POST['post_id'];
			$post_action = __('updated', 'mobile-app-builder');
		}

		$new_post_id = wp_insert_post($new_post, true);
		if (is_wp_error($new_post_id))
			throw new Exception($new_post_id->get_error_message(), 1);


		update_post_meta($new_post_id, '_connect', sanitize_text_field($_POST['_connect']));
		update_post_meta($new_post_id, '_json',  sanitize_text_field($jso) );
		update_post_meta($new_post_id, '_order', '100');
		if ($_POST['featured_img'] != -1)
			set_post_thumbnail($new_post_id, sanitize_text_field($_POST['featured_img']));

		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="mab-continue-editing">%s</a>',
			sprintf(__('Your article has been %s successfully!', 'mobile-app-builder'), $post_action),
			__('Continue Editing', 'mobile-app-builder')
		);
		if(isset($_POST['post_id'])):
			update_post_meta(sanitize_text_field($data['post_id']), '_json', sanitize_text_field($jso) );
		endif;
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_process_app_pages_new', 'mab_process_app_pages_new');
add_action('wp_ajax_nopriv_mab_process_app_pages_new', 'mab_process_app_pages_new');

function mab_process_app_pages_delete()
{
	$mab_messages = mab_messages();
	try {
		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if (sanitize_text_field($_POST['post_id']) != -1 && !current_user_can('edit_post', sanitize_text_field($_POST['post_id'])))
			throw new Exception(
				__("You don't have permission to edit this post.", 'mobile-app-builder'),
				1
			);
		$connected = get_post_meta(sanitize_text_field($_POST['post_id']), '_connect',true);

		if($connected == sanitize_text_field($_POST['_connect'])){
			wp_trash_post( sanitize_text_field($_POST['post_id']) );
			echo "OK";
		}else{
			echo "NOT".$connected.' - '.$_POST['_connect'];
		}

		$data['success'] = true;
		$data['post_id'] = sanitize_text_field($_POST['post_id']);
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="mab-continue-editing">%s</a>',
			sprintf(__('Your article has been %s successfully!', 'mobile-app-builder'), $post_action),
			__('Continue Editing', 'mobile-app-builder')
		);

	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_process_app_pages_delete', 'mab_process_app_pages_delete');
add_action('wp_ajax_nopriv_mab_process_app_pages_delete', 'mab_process_app_pages_delete');





/**
 * Ajax function sort tabs.
 *
 * @uses array $_POST The user submitted post
 * @return string: A JSON encoded string
 */
function mab_ajax_sort_tabs()
{
	$mab_messages = mab_messages();
	try {
		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);

		if ($errors)
			throw new Exception($errors, 1);


		$sorted = sanitize_text_field($_POST['sortedlist']);
		//echo $sorted;
		$sorted = str_replace('&item[]=','|',$sorted);
		$sorted = str_replace('item[]=','',$sorted);
		//item[]=Home1&item[]=82&item[]=97&item[]=96&item[]=98

		$u = 1;

		foreach (explode('|', $sorted) as $p){
			if(!current_user_can('edit_post', $p)){
				continue;
			}
			if($p == '' || !isset($p)){

				continue;
			}
			else if($p == 'Home1'){

					update_post_meta($p, '_order', '0');
				}
			//echo "$p<br>";
			//echo $p;
			update_post_meta($p, '_order', $u);
			$u++;
		}



		//


		$data['success'] = true;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="mab-continue-editing">%s</a>',
			sprintf(__('Your article has been %s successfully!', 'mobile-app-builder'), $post_action),
			__('Continue Editing', 'mobile-app-builder')
		);


	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$mab_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_mab_ajax_sort_tabs', 'mab_ajax_sort_tabs');
add_action('wp_ajax_nopriv_mab_ajax_sort_tabs', 'mab_ajax_sort_tabs');



function my_dismiss_mab_notice(){
	
     update_option( 'my-mab-notice-dismissed', '1' );
     
}
     
add_action( 'wp_ajax_my_dismiss_mab_notice',   'my_dismiss_mab_notice' );
add_action( 'wp_ajax_nopriv_my_dismiss_mab_notice',   'my_dismiss_mab_notice' );


function mab_custom_ajax_login(){
        try {
		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("Sorry! You failed the security check", 'mobile-app-builder'),
				1
			);
	    if($_POST) {
                $login_data = array();
                $login_data['user_login']    = trim(sanitize_text_field($_POST['username']));
                $login_data['user_password'] = sanitize_text_field($_POST['password']);
                
                if(sanitize_text_field($_POST['rememberme'])){
                    $login_data['remember'] = "true";
                }else{
                    $login_data['remember'] = "false";
                }

                $user_signon = wp_signon( $login_data, false );
                if ( is_wp_error($user_signon) ){
                    echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
                } else {
                    echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
                }
                die();
            }else{
                echo json_encode(array('loggedin'=>false, 'message'=>__('Invalid login details')));
                die();
            }
            
          } catch (Exception $ex) {
		                echo json_encode(array('loggedin'=>false, 'message'=>__('Invalid security login details')));

		}
        }
            // ajax call
add_action( 'wp_ajax_mab_custom_ajax_login',   'mab_custom_ajax_login' );
add_action( 'wp_ajax_nopriv_mab_custom_ajax_login',   'mab_custom_ajax_login' );

 // Register ajax function
        function mab_custom_ajax_registration()
        {   
            if ($_POST) {
                $username   = sanitize_text_field($_POST['reg_uname']);
                $email      = sanitize_text_field($_POST['reg_email']);
                $password   = sanitize_text_field($_POST['reg_password']);
                $website    = sanitize_text_field($_POST['reg_website']);
                $first_name = sanitize_text_field($_POST['reg_fname']);
                $last_name  = sanitize_text_field($_POST['reg_lname']);
                $nickname   = sanitize_text_field($_POST['reg_nickname']);
              
            }

            $userdata = array(
                            'user_login'  => esc_attr($username),
                            'user_email'  => esc_attr($email),
                            'user_pass'   => esc_attr($password),
                            'user_url'    => esc_attr($website),
                            'first_name'  => esc_attr($first_name),
                            'last_name'   => esc_attr($last_name),
                            'nickname'    => esc_attr($nickname),
                            'role' => 'mab_app_manager'
                        
                        );


			if (empty($username) || empty($password) || empty($email)) {
				echo json_encode(array('loggedin'=>false, 'message'=> 'Required form field is missing.' ));  
                
                die();
            }

            if (strlen($username) < 4) {
	            echo json_encode(array('loggedin'=>false, 'message'=> 'Username too short. At least 4 characters is required.' ));  
                
                die();
            }

            if (strlen($password) < 8) {
	            echo json_encode(array('loggedin'=>false, 'message'=> 'Password length must be greater than 8.' ));  
                
                die();
            }

            if (!is_email($email)) {
	            echo json_encode(array('loggedin'=>false, 'message'=> 'Email is not valid' ));  
                
                die();
            }

            if (email_exists($email)) {
	            echo json_encode(array('loggedin'=>false, 'message'=> 'Email Already in use' ));  
                
                die();
            }

            if (!empty($website)) {
                if (!filter_var($website, FILTER_VALIDATE_URL)) {
	                echo json_encode(array('loggedin'=>false, 'message'=> 'Website is not a valid URL' ));  
                
                    die();
                }
            }

            $details = array(
                            'Username'   => $username,
                            'First Name' => $first_name,
                            'Last Name'  => $last_name,
                        );

            foreach ($details as $field => $detail) {
                if (!validate_username($detail)) {
	                echo json_encode(array('loggedin'=>false, 'message'=> 'Sorry, the "' . $field . '" you entered is not valid' ));  
                
                    die();
                }
            }
            
            

                $register_user = wp_insert_user($userdata);
                if (!is_wp_error($register_user)) {
                    echo json_encode(array('loggedin'=>true, 'message'=> 'Registration completed.' ));
                } else {
                    echo json_encode(array('loggedin'=>false, 'message'=> $register_user->get_error_message() ));                    
                }
            
            die();
        }

			
            add_action( 'wp_ajax_mab_custom_ajax_registration',  'mab_custom_ajax_registration' );
            add_action( 'wp_ajax_nopriv_mab_custom_ajax_registration',  'mab_custom_ajax_registration' );

       

