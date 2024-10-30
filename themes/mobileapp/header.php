<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mobileapp
 */
 
 $post_id = get_the_ID();
 $json = get_post_meta($post_id, '_json', true);
 $jsonData = json_decode($json,true);

if(isset($jsonData['jsonswatch'])){ 
	$jsonswatch = $jsonData['jsonswatch']; 
	}
	

	
	$postType = get_post_type();
	
	if($postType == "mab_apps"){
		
		$main_post_id = get_the_ID();
	}else{
		
		$main_post_id = get_post_meta($post_id, '_connect', true);
	}
	
	
	$back = get_post_meta($main_post_id, 'background', true);
	$icon = get_post_meta($main_post_id, 'icon', true);
	
	
	if (strpos(get_permalink($main_post_id),'?') !== false) {
  $urlHome = get_permalink($main_post_id).'&post_type=mobile_apps';

} else {
 $urlHome = get_permalink($main_post_id).'?post_type=mobile_apps';

}
	

$apptitle  = get_the_title();
$apptype = get_post_type();

/* GET DEFAULT TEMPLATE VALUES */
	$main_json = get_post_meta($main_post_id, '_json', true);
	$main_json = json_decode($main_json, true);
	$theme = $main_json['theme'];
	$navbar = get_post_meta($theme, '_navbar', true);;
	$top = get_post_meta($main_json['theme'], '_top', true);
	$side = get_post_meta($main_json['theme'], '_side', true);
	$cover = get_post_meta($main_json['theme'], '_cover', true);
	$css = get_post_meta($main_json['theme'], '_css', true);
	$theme_json = get_post_meta($main_json['theme'], '_json', true);
	$theme_json = json_decode($theme_json, true);
	
	

/* GET DEFAULT TEMPLATE VALUES \END */


?>	
	
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

  	<meta name="viewport" content="width=device-width, initial-scale=1">
	 
 	<link  rel="stylesheet" type="text/css" href="<?php echo MAB_DIR_URL; ?>themes/mobileapp/layouts/app/<?php echo $jsonswatch; ?>/<?php echo $jsonswatch; ?>.min.css" />
  	<link  rel="stylesheet" type="text/css" href="<?php echo MAB_DIR_URL; ?>themes/mobileapp/layouts/app/icons/jquery.mobile.icons.min.css" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" /> 
  	<script  src="http://code.jquery.com/jquery-1.11.1.min.js"></script> 
  	<script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script> 
	
	<link  rel="stylesheet" type="text/css" href="<?php echo MAB_DIR_URL; ?>themes/mobileapp/layouts/jqm-font-awesome-isvg-ipng.min.css" />
  	<link  rel="stylesheet" type="text/css" href="<?php echo MAB_DIR_URL; ?>themes/mobileapp/layouts/style.css" />
  	<link  rel="stylesheet" type="text/css" href="<?php echo MAB_DIR_URL; ?>themes/mobileapp/layouts/font-awesome.min.css" />
  	
  	
<?php wp_head(); ?>

<style>
	<?php
		
		echo $css;
	?>
</style>
</head>

<body <?php body_class(); ?>>
	
	
	
  <div data-role="page" id="home-page">

    <div data-role="panel" id="mypanel" style="" data-position="left" data-theme="a" data-display="reveal" >
   

    <div id="" style="overflow-y: scroll;
position: fixed;
height: 478px;
width: 100%;
min-height:100%; padding-top: 0px;left: 0px;">
 <div style="text-align: right; width:100%">
<i class="mabsidetabs fa fa-times" style="background-color: #cebba9;padding: 8px;    margin-right: 15px;" data-rel="close" aria-hidden="true"></i>
</div>

	
	<center><img src="<?php echo $icon; ?>" style="max-width: 100px"></center>
<br>
<ul data-role="listview"> 
  
  
  <li><a data-transition="flip"  href="<?php echo $urlHome; ?>" class=" "><i class="mabsidetabs fa fa-home" aria-hidden="true"></i>Home</a></li>

 <?php

$args = array(
	'post_type' => 'mab_apps_pages',
	'posts_per_page' => -1,
	'ignore_sticky_posts' => 1,
	'meta_key' => '_order',
	'orderby' => 'meta_value_num',
	'order' => 'ASC',
	'meta_query'     => array(

		'relation'  => 'AND',
		array (
			'key'     => '_connect',
			'value'   => $main_post_id,
			'compare' => '=',

		)
	)

);


// print_r($args);
$mypostsc = new WP_Query($args);

$u = 0;
while( $mypostsc->have_posts() ) : ($mypostsc->the_post());

// if(get_the_title() == $jsonstyle){ $selected = 'mabselected'; }else{ $selected =''; }
//echo '<li class="mabstyle ullipagesli '.$selected.'" title="'.get_the_title().'">';
//echo the_post_thumbnail();
//echo '</li>';
$related = get_post_meta(get_the_ID(), '_json', true);
$relatedJson = json_decode($related,true);
$ordered = get_post_meta(get_the_ID(), '_order', true);
//print_r($relatedJson);

if (strpos(get_permalink(),'?') !== false) {
    $link = get_permalink().'&post_type='.$postType;

} else {
  $link = get_permalink().'?post_type='.$postType;

}
echo '<li><a data-transition="flip"  href="'.$link.'" class=" "><i class="mabsidetabs fa fa-'.$relatedJson['icon'].'" aria-hidden="true"></i>'.get_the_title().'</a></li>';

$u++;

endwhile;
  ?>
 
                
        
        	</ul>
        	
       <br><br><br><br>     	

</div>
    
    </div>
	<?php
	 
	 if($top == 'yes'):
	?>
    <div data-role="header"  data-position="fixed" data-tap-toggle="false">
	    
	 <?php
	 
	 if($side == 'yes'):
 
     echo '<a data-iconpos="notext" href="#mypanel" data-role="button" data-icon="bars"></a>';
     
     endif;
     ?>
      <h1 ><?php echo $apptitle; ?></h1>
      <?php /*
      <a data-iconpos="notext" href="#aboutUs"  data-role="button" data-icon="info" title="Home">Home</a> 
		*/
		?>
<!-- TOP BAR BUTTONS --> 
   <!-- /TOP BAR BUTTONS -->  
   <?php
	 
	 if($navbar == 'top'):
	 
	 include( get_template_directory() . '/inc/navbar.php'); 
	 
	 endif;
	 
 	?>     
    </div>
    <?php
	    
	     endif;
    ?>



<!-- BOTTOM STYLE -->

 <div data-role="footer"  data-position="fixed"  class="nav-glyphish-example" data-tap-toggle="false">
 <?php
	 
	 if($navbar == 'bottom'):
	 
	 include( get_template_directory() . '/inc/navbar.php'); 
	 
	 endif;
	 
 ?>
 
 </div>

<!-- /BOTTOM BAR STYLE-->





    <div data-role="content" role="main" class="mainFull">
      <div class="background"><img src="<?php echo $back; ?>" class="backImg"  width="100%">
		</div> 
