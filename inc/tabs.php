<?php
/*
	TABS - SORTABLE TABS

*/

if(!isset($post_id) || $post_id == ''){
	$post_id = sanitize_text_field($_POST['id']);
}
$mabbackground_colors = array('#FF5E3A', '#C644FC', '#FFDB4C', '#1AD6FD', '#FF3B30', '#FFCC00', '#4CD964',  '#007AFF', '#5856D6', '#FF2D55');
?>

<div class="mabitemHome" data-id="Home1" data-text="Home" data-type="Home1" data-modname="Home1" id="item-Home1"><i class="fa fa-home fa-3x" aria-hidden="true"></i><br>Home</div>
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
			'value'   => $post_id,
			'compare' => '=',

		)
	)

);


// print_r($args);
$mypostsc = new WP_Query($args);


while( $mypostsc->have_posts() ) : ($mypostsc->the_post());

// if(get_the_title() == $jsonstyle){ $selected = 'mabselected'; }else{ $selected =''; }
//echo '<li class="mabstyle ullipagesli '.$selected.'" title="'.get_the_title().'">';
//echo the_post_thumbnail();
//echo '</li>';
$related = get_post_meta(get_the_ID(), '_json', true);
$relatedJson = json_decode($related,true);
$ordered = get_post_meta(get_the_ID(), '_order', true);
//print_r($relatedJson);
echo '
	<div class="mabitem" data-id="'.get_the_ID().'" data-icon="'.$relatedJson['icon'].'" data-icontext="'.get_the_title().'" style="background-color: '.$mabbackground_colors[array_rand($mabbackground_colors)].'" data-type="'.$relatedJson['name'].'" data-modname="'.$relatedJson['name'].'" id="item-'.get_the_ID().'"><i class="fa fa-'.$relatedJson['icon'].'  fa-3x" aria-hidden="true"></i><br>'.get_the_title().'</div>

    ';

endwhile;


?>