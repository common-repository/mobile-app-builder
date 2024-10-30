<?php
/*
Plugin Name: Mobile App Builder
Plugin URI: http://app-developers.biz
Description: Mobile app builder
Version: 1.0.7
Author: Dave Anthony
Author URI: http://app-developers.biz
Text Domain: mobile-app-builder
Domain Path: /languages
License: GPL2
*/

/**
 * Loads the plugin's text domain for localization.
 **/

$mapwidth ='100';
define('MAB_DIR', dirname(__FILE__));
define('MAB_DIR_URL', plugins_url('/', __FILE__));
define('DEBUG_MAB', 0);
define('MAB_VIDEO', 'https://www.youtube.com/embed/ZHvdNHz6Kpk');

require_once MAB_DIR.'/inc/classes/create_json.php';
require_once MAB_DIR.'/inc/classes/class-tgm-plugin-activation.php';


/* TO BE REMOVED */
//require_once(MAB_DIR.'/inc/classes/multi-post-thumbnails.php');

$createJson = new MABClass_json();

/* Config */
$mab_post_types = array('mab_apps','mab_apps_themes','mab_apps_plugins','mab_apps_pages'); //post type

add_action('init', array($createJson, 'MAB_produce_my_json'));


function mab_load_plugin_textdomain()
{
	load_plugin_textdomain('mobile-app-builder', false, plugin_basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'mab_load_plugin_textdomain');

/**
 * Starts output buffer so that auth_redirect() can work in shortcodes

function mab_start_output_buffers()
{

	ob_start();
}

add_action('init', 'mab_start_output_buffers');
 */
 
 
/**
 * Initializes plugin options on first run
 */
function mab_initialize_options()
{
	
	
add_role( 'mab_app_manager', 'App Builder', 
array(
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => true,
) );


	$activation_flag = get_option('mab_misc');

	if ($activation_flag)
		return;

	$mab_restrictions = array(
		'min_words_title'    => 2,
		'max_words_title'    => 12,
		'min_words_content'  => 250,
		'max_words_content'  => 2000,
		'min_words_bio'      => 50,
		'max_words_bio'      => 100,
		'min_tags'           => 1,
		'max_tags'           => 5,
		'max_links'          => 2,
		'max_links_bio'      => 2,
		'thumbnail_required' => false
	);

	$mab_roles = array(
		'no_check'          => false,
		'instantly_publish' => false,
		'enable_media'      => false
	);

	$mab_misc = array(
		'before_author_bio'   => '',
		'disable_author_bio'  => false,
		'remove_bios'         => false,
		'nofollow_body_links' => false,
		'nofollow_bio_links'  => false,
		'posts_per_page'      => 10
	);

	update_option('mab_post_restrictions', $mab_restrictions);
	update_option('mab_role_settings', $mab_roles);
	update_option('mab_misc', $mab_misc);
	

}
register_activation_hook(__FILE__, 'mab_initialize_options');

function mab_messages()
{
	$mab_messages = array(
		'unsaved_changes_warning'      => __('You have unsaved changes. Proceed anyway?', 'mobile-app-builder'),
		'confirmation_message'         => __('Are you sure?', 'mobile-app-builder'),
		'media_lib_string'             => __('Choose Image', 'mobile-app-builder'),
		'required_field_error'         => __('You missed one or more required fields', 'mobile-app-builder'),
		'general_form_error'           => __('Your submission has errors. Please try again!', 'mobile-app-builder'),
		'title_short_error'            => __('The title is too short', 'mobile-app-builder'),
		'title_long_error'             => __('The title is too long', 'mobile-app-builder'),
		'article_short_error'          => __('The article is too short', 'mobile-app-builder'),
		'article_long_error'           => __('The article is too long', 'mobile-app-builder'),
		'bio_short_error'              => __('The bio is too short', 'mobile-app-builder'),
		'bio_long_error'               => __('The bio is too long', 'mobile-app-builder'),
		'too_many_article_links_error' => __('There are too many links in the article body', 'mobile-app-builder'),
		'too_many_bio_links_error'     => __('There are too many links in the bio', 'mobile-app-builder'),
		'too_few_tags_error'           => __("You haven't added the required number of tags", 'mobile-app-builder'),
		'too_many_tags_error'          => __('There are too many tags', 'mobile-app-builder'),
		'featured_image_error'         => __('You need to choose a featured image', 'mobile-app-builder')
	);

	return $mab_messages;
}

/**
 * Removes plugin data before uninstalling
 */
function mab_rollback()
{
	wp_deregister_style('mab-style');
	wp_deregister_script('mab-script');
	delete_option('mab_post_restrictions');
	delete_option('mab_role_settings');
	delete_option('mab_misc');
	delete_option('mab_messages');
}

register_uninstall_hook(__FILE__, 'mab_rollback');

/**
 * Enqueue scripts and stylesheets
 *
 * @param array $posts WordPress posts to check for the shortcode
 * @return array $posts Checked WordPress posts
 */
 
add_action( 'admin_enqueue_scripts', 'my_mab_admin_assets' );
function my_mab_admin_assets() {
	wp_enqueue_script( 'my-mab-admin-update', plugins_url( '/static/js/notice-update.js', __FILE__ ), array( 'jquery' ), '1.0', true  );


		wp_localize_script('my-mab-admin-update', 'fepajaxhandler', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'fonturl' => MAB_DIR_URL.'static/data/fa.php',
				'success' => 'Success',
				'successmessage' => 'Saved with success',
			));
}


function mab_enqueue_files($posts)
{
	if (!is_main_query() || empty($posts))
		return $posts;

	$found = false;
	foreach ($posts as $post) {
		if (mab_has_shortcode($post->post_content, 'mab_article_list') || mab_has_shortcode($post->post_content, 'mab_submission_form')) {
			$found = true;
			break;
		}
	}
	

	if ($found) {
		wp_enqueue_style( 'mab-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );

		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-autocomplete");
		wp_enqueue_style("jquery-ui-core");
		wp_enqueue_style("jquery-ui-autocomplete");
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style (  'wp-jquery-ui-dialog');
		wp_enqueue_style('mab-style', plugins_url('static/css/style.css', __FILE__), array(), '1.0', 'all');
		wp_enqueue_script("mab-script", plugins_url('static/js/scripts.js', __FILE__), array('jquery'), '1.0', true);

		wp_enqueue_script("mab-script_sticky", plugins_url('static/js/jquery.sticky.js', __FILE__), array('jquery'));
		wp_enqueue_style('mab-style_growl', plugins_url('static/css/jquery.growl.css', __FILE__), array(), '1.0', 'all');
		wp_enqueue_script("mab-script_growl", plugins_url('static/js/jquery.growl.js', __FILE__), array('jquery'));


		wp_localize_script('mab-script', 'fepajaxhandler', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'fonturl' => MAB_DIR_URL.'static/data/fa.php',
				'success' => 'Success',
				'successmessage' => 'Saved with success',
			));
		$mab_rules = get_option('mab_post_restrictions');
		$mab_roles = get_option('mab_role_settings');
		$mab_rules['check_required'] = (isset($mab_roles['no_check']) && $mab_roles['no_check'] && current_user_can($mab_roles['no_check'])) ? 0 : 1;
		wp_localize_script('mab-script', 'mab_rules', $mab_rules);
		wp_localize_script('mab-script', 'mab_messages', mab_messages());
		$enable_media = (isset($mab_roles['enable_media']) && $mab_roles['enable_media']) ? current_user_can($mab_roles['enable_media']) : 1;
		if ($enable_media)
			wp_enqueue_media();
	}
	return $posts;
}

add_action('the_posts', 'mab_enqueue_files',99);

/**
 * Append post meta (author bio) to post content
 *
 * @param string $content post content to append the bio to
 * @return array $posts modified post content
 */
function mab_add_author_bio($content)
{
	$mab_misc = get_option('mab_misc');
	global $post;
	$ID = $post->ID;
	$author_bio = get_post_meta($ID, 'about_the_author', true);
	if (!$author_bio || $mab_misc['remove_bios']) return $content;
	$before_bio = $mab_misc['before_author_bio'];
	ob_start();
?>
	<?php echo $content ?><?php echo $before_bio ?>
	<div class="mab-author-bio"><?php echo $author_bio ?></div>
	<?php
	return ob_get_clean();
}

add_filter('the_content', 'mab_add_author_bio', 100);

/**
 * Scans content for shortcode.
 *
 * @param string $content post content to scan
 * @param string $tag shortcode text
 * @return bool: whether or not the shortcode exists in $content
 */
if (!function_exists('mab_has_shortcode')) {
	function mab_has_shortcode($content, $tag)
	{
		if (stripos($content, '[' . $tag . ']') !== false)
			return true;
		return false;
	}
}




/*

	Change theme

*/
function MAB_mobile_detect($post){

	global $wp_query,$post;
	//$post = $wp_query->get_queried_object();
	// $post = $wp_query->post;
	//echo $post->ID;
	if ('mab_apps' == isset($_GET['post_type']) || 'mab_apps_pages' == isset($_GET['post_type'])){

		add_filter('theme_root', 'MAB_change_theme_root', 99);
		add_filter('stylesheet_directory_uri', 'MAB_change_theme_root_css_uri', 99);
		add_filter('template_directory_uri', 'MAB_change_theme_root_uri', 99);
		add_filter('template', 'MAB_fxn_change_theme', 99);
		add_filter('stylesheet', 'MAB_fxn_change_theme', 99);
		show_admin_bar(false);
	}


}
add_action('plugins_loaded', 'MAB_mobile_detect');
function MAB_mobile_redirect($post){

	if ('mab_apps' == get_post_type() && !isset($_GET['post_type'])){

		$params = array_merge($_GET, array("post_type" => "mab_apps"));
		$new_query_string = http_build_query($params);

		$fullurl = (empty($_SERVER['HTTPS'])?"http://":"https://") .
			(empty($_SERVER['HTTP_HOST'])?$defaultHost:$_SERVER['HTTP_HOST']) .
			$_SERVER['REQUEST_URI'] . "?" . $new_query_string;
		// wp_redirect( );
		header('Location: '.$fullurl);
		exit;

	}
}
add_action('template_redirect', 'MAB_mobile_redirect');

function MAB_MyiOSTheme(){
	return MAB_DIR.'/themes/wordappjqmobileMyiOS/mainPage.php';
	exit;
}
function MAB_change_theme_root(){
	return plugin_dir_path(__FILE__).'themes';
}
function MAB_change_theme_root_uri(){
	return plugins_url('themes/mobileapp', __FILE__);
}
function MAB_change_theme_root_css_uri(){
	return plugins_url('themes/mobileapp', __FILE__);
}
function MAB_fxn_change_theme($theme){
	$theme = 'mobileapp';
	return $theme;
}

/**********************************************
 *
 * Inlcuding modules
 *
 ***********************************************/

include('inc/ajax.php');
include('inc/preview.php');
include('inc/shortcodes.php');

include('inc/options-panel.php');
include('inc/pages.php');
include('inc/images.php');
include('inc/content.php');
include('inc/paypal.php');
include('inc/plugin_ext.php');

include('inc/setup.php');
include('inc/new_app.php');
include('inc/alert.php');
//Kses for content and bio | featured image | custom fields | excerpts | custom post types