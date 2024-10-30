<?php
	

$current_user = wp_get_current_user();
$post = false;
$post_id = -1;
$featured_img_html = '';
if (isset($_GET['mab_id']) && isset($_GET['mab_action']) && $_GET['mab_action'] == 'edit') {
	$post_id = $_GET['mab_id'];
	$p = get_post($post_id, 'ARRAY_A');
	$json = '';
	$json = get_post_meta($post_id, '_json', true);
	$jsonData = json_decode($json,true);
	//print_r($jsonData);

	if(isset($jsonData['jsonstyle'])){
		$jsonstyle = $jsonData['jsonstyle'];
	}else{
		$jsonstyle = '';
	}
	if(isset($jsonData['jsonswatch'])){
		$jsonswatch = $jsonData['jsonswatch'];
	}else{
		$jsonswatch = '';
	}
	if ($p['post_author'] != $current_user->ID) return __("You don't have permission to edit this post", 'mobile-app-builder');
	$category = get_the_category($post_id);
	$tags = wp_get_post_tags($post_id, array('fields' => 'names'));


	/*
	$featured_img_splash = get_post_thumbnail_id($post_id);
	$featured_img_html_splash = (!empty($featured_img_splash)) ? wp_get_attachment_image($featured_img_splash, array(200, 200)) : '';


	$featured_img_backgound = get_post_thumbnail_id($post_id);
	$featured_img_html_background = (!empty($featured_img_backgound)) ? wp_get_attachment_image($featured_img_backgound, array(200, 200)) : '';


	$featured_img = get_post_thumbnail_id($post_id);
	$featured_img_html = (!empty($featured_img)) ? wp_get_attachment_image($featured_img, array(200, 200)) : '';
	*/


	$featured_img_splash = get_post_meta($post_id, 'splash', true);
	if(isset($featured_img_splash) && $featured_img_splash != '' && $featured_img_splash != '-1'){
		$featured_img_html_splash = '<img src="'.esc_url( $featured_img_splash).'" style="max-width:200px" class="appimages">';
	}
	else{
		$featured_img_html_splash = '';
	}

	$featured_img_backgound = get_post_meta($post_id, 'background', true);
	if(isset($featured_img_backgound) && $featured_img_backgound != '' && $featured_img_backgound != '-1'){
		$featured_img_html_background = '<img src="'.esc_url( $featured_img_backgound).'"  style="max-width:200px" class="appimages">';
	}
	else{
		$featured_img_html_background = '';
	}

	$featured_img = get_post_meta($post_id, 'icon', true);
	if(isset($featured_img) && $featured_img != '' && $featured_img != '-1'){
		$featured_img_html = '<img src="'.esc_url( $featured_img).'" style="max-width:200px" class="appimages">';
	}
	else{
		$featured_img_html = '';
	}




	$post = array(
		'title'            => $p['post_title'],
		'content'          => $p['post_content'],
		'about_the_author' => get_post_meta($post_id, 'about_the_author', true)
	);
	if (isset($category[0]) && is_array($category))
		$post['category'] = $category[0]->cat_ID;
	if (isset($tags) && is_array($tags))
		$post['tags'] = implode(', ', $tags);
}
$mabbackground_colors = array('#FF5E3A', '#C644FC', '#FFDB4C', '#1AD6FD', '#FF3B30', '#FFCC00', '#4CD964',  '#007AFF', '#5856D6', '#FF2D55');


?>
<noscript>
	<div id="no-js"
		 class="warning"><?php _e('This form needs JavaScript to function properly. Please turn on JavaScript and try again!', 'mobile-app-builder'); ?></div>
</noscript>


 <?php

if(!isset($_GET['fb']) && !isset($_GET['mab_id'])){



?>
 <div class="qForm">
<div id="custom-templates">
  <input class="typeahead" id="tags" type="text" placeholder="<?php _e('Your Facebook Page', 'mobile-app-builder'); ?>">
<div class="spinner" style="display:none"><img src="<?php echo MAB_DIR_URL; ?>static/img/loadingfb.gif"><?php _e('Loading...', 'mobile-app-builder'); ?></div>
</div>
 </div>
 <?php }
else if(isset($_GET['fb']) or isset($_GET['mab_id'])){

		if(!isset($location)) $location = '';
		if(isset($_GET['fbname'])) $fbname = urldecode($_GET['fbname']);
		else $fbname = '';
?>


<div class="mabgroup">
<div class="mableft">
<ul class='mabtabs'>
  <li><a href='#mabtab1'><?php _e('App Features', 'mobile-app-builder'); ?></a></li>
  <li><a href='#mabtab2'><?php _e('Style & Design', 'mobile-app-builder'); ?></a></li>
  <li><a href='#mabtab3'><?php _e('Structure', 'mobile-app-builder'); ?></a></li>
</ul>
<div id='mabtab1'>

<div class="mabzone">
<div id="mabparent" class="mabparent" style="width: 100%; height: 100px;">

  <div id="mablist" class="mablist ui-sortable">
<?php

		include(MAB_DIR.'/inc/tabs.php');
?>

	</div>

<div style="clear: both"></div>

</div>

<div class="modsupp"><a href="" class="modsuppbutton"><?php _e('Add a new module', 'mobile-app-builder'); ?></a></div>
</div>
<div class="mabzone">

	<div class="mabprop" style="display:none">

	<div class="mableftmini">
	<div class="mabitemImage ui-sortable-handle" data-id="96"  data-type="facebook" data-modname="facebook" id="item-96"><i class="fa fa-facebook  fa-3x" id="previewIcon" aria-hidden="true"></i></div></div>
	<div class="mabrighttrash">
		<i class="fa fa-trash fa-2x" id="trashAppBuilder"  aria-hidden="true"></i>

	</div>
	<div class="mabrightmini">
		<label ><?php _e('Tab title', 'mobile-app-builder'); ?></label>
		<input type="text" id="previewText" name="name" class="mabtextmain" value="Home">

	</div>

</div>

<div id="mabloading" style="display:none;"><center><img src="<?php echo MAB_DIR_URL; ?>static/img/hourglass.gif"></center></div>

<div id="mabhome">

<h2><?php _e('App home configuration.', 'mobile-app-builder'); ?></h2>
<p ><label for="mab-post-title"><?php _e('App Name', 'mobile-app-builder'); ?></label>
		<input type="text" name="post_title" id="mab-post-title" value="<?php echo ($post) ? $post['title'] : $fbname; ?>">
</p>
<?php
		if(isset($json)){
			$jsondecode = json_decode($json,true);

			if(isset($jsondecode['theme'])){

				$jsontheme = get_post_meta( $jsondecode['theme'],'_json',true );
				$jsontheme = json_decode($jsontheme,true);
				//print_r($jsontheme);



				foreach ($jsontheme as $key) {

					if(isset($var[0]['name'])){

						$jval = $key[0]['name'];
						$jval = $jsonplugins[$jval];
					}else{

						$jval = '';
					}


					if($key[0]['type'] == 'text' || $key[0]['type']== ''){
						if(!isset($jsonData['plugins'][0][$key[0]['name']])){ $jsonData['plugins'][0][$key[0]['name']] = '';}
						echo '<p><label>'.$key[0]['friendly'].' : </label> <input class="inputPlugins" value="'.esc_textarea( $jsonData['plugins'][0][$key[0]['name']]).'" name="'.$key[0]['name'].'" ></p>';


					}else if($key[0]['type'] == 'html'){

							if (empty($key[0]['name']))  $key[0]['name'] = "";
							if (empty($jsonData['plugins'][0][$key[0]['name']]))  $jsonData['plugins'][0][$key[0]['name']] = "";

							echo '<p><label>'.$key[0]['friendly'].'</label> </p><p>
		<textarea data-friendly="'.$key[0]['friendly'].'" id="blurbhome"  name="'.$key[0]['name'].'" class="" '.$key[0]['name'].'">'.esc_textarea(base64_decode($jsonData['plugins'][0][$key[0]['name']])).'</textarea>

		<input data-friendly="'.$key[0]['friendly'].'" id="blurbhomeHidden" type="hidden" value="'.esc_html($jsonData['plugins'][0][$key[0]['name']]).'" name="'.$key[0]['name'].'" class="inputPlugins '.$key[0]['name'].'" >
		<br>';

						}

				}
				/*
			if($var[0]['type'] == 'text' || $var[0]['type']== ''){

		echo '<p><label>'.$var[0]['friendly'].'</label> : <input data-friendly="'.$var[0]['friendly'].'" type="'.$var[0]['type'].'" value="'.$jval.'" name="'.$var[0]['name'].'" class="mabtextedit '.$var[0]['name'].'" ><br><small class="mabhint">'.$hint.'</small></p>';
		}
		else if($var[0]['type'] == 'html'){
		echo '<p><label>'.$var[0]['friendly'].'</label> </p><p>
		<textarea data-friendly="'.$var[0]['friendly'].'" id="blurb"  name="'.$var[0]['name'].'" class="" '.$var[0]['name'].'">'.base64_decode($jval).'</textarea>

		<input data-friendly="'.$var[0]['friendly'].'" id="blurbHidden" type="hidden" value="'.$jval.'" name="'.$var[0]['name'].'" class="mabtextedit '.$var[0]['name'].'" >
		<br><small class="mabhint">'.$hint.'</small></p>';

		}*/
			}

		}


		echo '<a href="#" class="btnHomeSave peter-river-flat-button" >'.__("Save homepage settings", 'mobile-app-builder').'</a>';
?>



</div>
<div id="mabpages" style="display: none;">
hide
</div>
</div>

</div>
<div id='mabtab2'>
  <div class="mabzone">
	  <h3><?php _e('App Style', 'mobile-app-builder'); ?></h3>
	  <div id="mablisttheme" class="mablisttheme ui-sortable">
		  <?php


		$args = array(
			'post_type' => 'mab_apps_themes',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => 1,
			'orderby' => 'title',
			'order' => 'ASC',

		);



		$myposts = new WP_Query($args);
		echo '<ul class="ullipages">';
		while( $myposts->have_posts() ) : ($myposts->the_post());

		if(get_the_title() == $jsonstyle){ $selected = 'mabselected'; }else{ $selected =''; }
		echo '<li class="mabstyle ullipagesli '.$selected.'" title="'.get_the_title().'" id="'.get_the_ID().'">';
		echo the_post_thumbnail();
		echo '</li>';
		endwhile;

		echo '</ul>';
?>
		</div>
  </div>

  <div class="mabzone">
	  <h3><?php _e('App colors', 'mobile-app-builder'); ?></h3>
  <div class="list ui-sortable" style="height: 71px;">

       <ul class="swatches">

	     <?php


		$swatchColors = array('slate','aloe','water','candy','melon','mint','royal','sand','greenWhite','blueWhiter','yellowWhite','blackWhite','BluePink','seaSand','lavender','lightMint','blackRed','blueWhite','limeGreen','woody','snowWhite','lightPink','orangeHeader','cola','blackBlue','greyClass','blackGold','fruity','wierd','primaryColors','army');

		foreach($swatchColors as $swatchcol){

			if($swatchcol == $jsonswatch){ $selectedswatch = 'mabselectedswatch'; }else{ $selectedswatch =''; }

?>
        <li><label id="<?php echo $swatchcol; ?>" class="mabswatch <?php echo $swatchcol; ?> <?php echo $selectedswatch; ?>"><input type="radio" name="swatch" class="swatch" value="<?php echo esc_html($swatchcol); ?>"></label></li>

    <?php

		}
?>

      </ul>
      </div>
      </div>
</div>
<div id='mabtab3'>


<div id="mab-new-post">
	<div id="mab-message" class="warning"></div>
	<form id="mab-submission-form">

		<div style="display: none;">
		<label for="mab-post-content"><?php _e('Content', 'mobile-app-builder'); ?></label>
		<?php
		$enable_media = (isset($mab_roles['enable_media']) && $mab_roles['enable_media']) ? current_user_can($mab_roles['enable_media']) : 1;
		wp_editor(esc_html($post['content']), 'mab-post-content', $settings = array('textarea_name' => 'post_content', 'textarea_rows' => 7, 'media_buttons' => $enable_media));


		wp_nonce_field('fepnonce_action', 'fepnonce');
?>

			<input type="hidden" name="about_the_author" id="mab-about" value="-1">
		</div>

		<?php /*
		<label for="mab-category"><?php _e('Category', 'mobile-app-builder'); ?></label>
		<?php wp_dropdown_categories(array('id' => 'mab-category', 'hide_empty' => 0, 'name' => 'post_category', 'orderby' => 'name', 'selected' => $post['category'], 'hierarchical' => true, 'show_option_none' => __('None', 'mobile-app-builder'))); ?>
		*/
?>
		<input type="hidden" name="post_tags" id="mab-tags" value="<?php echo ($post) ? $post['tags'] : ''; ?>">


		<div id="mab-featured-image">
			<div id="mab-featured-image-container"><?php echo $featured_img_html; ?></div>
			<a id="mab-featured-image-link" href="#"><?php _e('Choose App Icon Image', 'mobile-app-builder'); ?></a>
			<input type="hidden" id="mab-featured-image-id" value="<?php echo (!empty($featured_img)) ? $featured_img : '-1'; ?>"/>
			<input type="hidden" id="mab-iconurl" value="<?php echo (!empty($featured_img)) ? $featured_img : '-1'; ?>"/>
		</div>

		<div id="mab-featured-image-splash">
			<div id="mab-featured-image-container-splash"><?php echo $featured_img_html_splash; ?></div>
			<a id="mab-featured-image-link-splash" href="#"><?php _e('Choose Splash Screen Image', 'mobile-app-builder'); ?></a>
			<input type="hidden" id="mab-featured-image-id-splash" value="<?php echo (!empty($featured_img_splash)) ? $featured_img_splash : '-1'; ?>"/>
			<input type="hidden" id="mab-splashurl" value="<?php echo (!empty($featured_img_splash)) ? $featured_img_splash : '-1'; ?>"/>
		</div>

		<div id="mab-featured-image-background">
			<div id="mab-featured-image-container-background"><?php echo $featured_img_html_background; ?></div>
			<a id="mab-featured-image-link-background" href="#"><?php _e('Choose Background Image', 'mobile-app-builder'); ?></a>
			<input type="hidden" id="mab-featured-image-id-backgroud" value="<?php echo (!empty($featured_img_backgound)) ? $featured_img_backgound : '-1'; ?>"/>
			<input type="hidden" id="mab-backgroundurl" value="<?php echo (!empty($featured_img_backgound)) ? $featured_img_backgound : '-1'; ?>"/>
		</div>


		<input type="hidden" name="post_id" id="mab-post-id" value="<?php echo $post_id ?>">

		<input type="hidden" name="_json" id="mab-json" value="<?php echo ($json) ? $json : ''; ?>">
		<input type="hidden" name="_json_style" id="mab-json-style" value="<?php echo ($jsonstyle) ? $jsonstyle : ''; ?>">
		<input type="hidden" name="_json_swatch" id="mab-json-style-swatch" value="<?php echo ($jsonswatch) ? $jsonswatch : ''; ?>">
		<button type="button" style="display:none" id="mab-submit-post" class="active-btn"><?php _e('Submit', 'mobile-app-builder'); ?></button>
		<img class="mab-loading-img" src="<?php echo plugins_url('static/img/ajax-loading.gif', dirname(__FILE__)); ?>"/>
	</form>
</div>
</div>
<div class="mabzone">
<center >


<?php _e('When you are finshed click below.','mobile-app-builder')?>
<br>
<input type="button" id="mabFinished" style="    background: #c0392b;
    padding: 1px;
    margin: 1px;
    width: 65%;
    height: 41px;" data-target="<?php echo get_permalink( esc_attr(get_option('mab_paypal_page_id')) ); ?>" class="pomegranate-flat-button" value="<?php _e('Publish my app!','mobile-app-builder')?>">
<br><br></center>
</div>
</div>
<div class="mabright">


<div class="marvel-device iphone6 silver sticker">
    <div class="top-bar"></div>
    <div class="sleep"></div>
    <div class="volume"></div>
    <div class="camera"></div>
    <div class="sensor"></div>
    <div class="speaker"></div>
    <div class="screen"><iframe id="iphonedemo" frameBorder="0" hspace="0" vspace="0" seamless="seamless" style="    width: 280px;height: 480px;" src="<?php echo get_site_url(); ?>?WordApp_launch=&WordApp_mobile_site=&WordApp_demo=&WordApp_mobile_app=&post_type=mab_apps&p=<?php echo $post_id; ?>"></iframe>
        <!-- Content goes here -->
    </div>

    <div class="bottom-bar"></div>
</div>
</div>
</div>
Powered by <a href="http://app-developers.biz">Mobile app builder</a>
<?php

		include(MAB_DIR.'/inc/popup-plugins.php');

	}

?>