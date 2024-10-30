<?php
	
	$apptype = get_post_type( );
	$mab_page_name = get_post_meta($post->ID, 'mab_page_name', true);
	$mab_page_id = get_post_meta($post->ID, 'mab_page_id', true);
	$app_title = get_the_title(  );
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package mobileapp
 */

get_header(); ?>

<?php 
	
	if($apptype == 'mab_apps'):
?>
<section id="wallDetailMessage" style="width 100%"> 
<div  class="topimagesAPP">
<img src="http://app-developers.eu/cover.php?user=<?php echo $mab_page_id;?>" width="100%" style="max-height: 205px">
<img src="http://graph.facebook.com/<?php echo $mab_page_id; ?>/picture?width=200&height=200" style="max-width: 23%;
    position: absolute;
    padding: 5px;
    background-color: #ffffff;
    border: 4px !important;
    border-color: #000000;
    top: 130px;
    left: 11px;">
<p style="float:right;padding-right: 5px;margin: 0px;
font-size: initial;"><span style="color: grey;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
font-size: smaller;
-o-text-overflow: ellipsis;"><?php echo $app_title; ?><br> </span><br>
</div>
</section>

<?php
	
	endif;

?>

 <section class="belowPicture">
<div style="clear: both"></div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
 </section>
<?php

get_footer();
