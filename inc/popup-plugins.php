<div id="dialog-message-fonticons" title="MODULES">

<div id="fonticons">



</div>
</div>
<div id="dialog-message" title="MODULES">
<p>

<?php
$args = array(
	'post_type' => 'mab_apps_plugins',
	'posts_per_page' => -1,
	'ignore_sticky_posts' => 1,
	'orderby' => 'title',
	'order' => 'ASC'


);



$mypostsc = new WP_Query($args);


while( $mypostsc->have_posts() ) : ($mypostsc->the_post());

// if(get_the_title() == $jsonstyle){ $selected = 'mabselected'; }else{ $selected =''; }
//echo '<li class="mabstyle ullipagesli '.$selected.'" title="'.get_the_title().'">';
//echo the_post_thumbnail();
//echo '</li>';
$related = get_post_meta(get_the_ID(), '_json', true);

$relatedJson = json_decode($related,true);
$relatedJson['plugin'] = get_the_ID();

$related = json_encode($relatedJson);
//print_r($relatedJson);
echo '
<div class="outerItem"><div class="item2" style="background-color: '.$mabbackground_colors[array_rand($mabbackground_colors)].'" data-id="'.get_the_ID().'" data-parent="'.$post_id.'" data-json="'.base64_encode($related).'" data-content="'.base64_encode(get_the_content()).'"  data-type="'.$relatedJson['name'].'" data-modname="'.$relatedJson['name'].'" id="item-'.get_the_ID().'"><i class="fa fa-'.$relatedJson['icon'].'  fa-3x" aria-hidden="true"></i><br>'.get_the_title().'</div></div>


    ';

endwhile;

?>

</p>
</div>

<div id="dialog-confirm" style="display:none;" title="<?php _e('Delete this item?','mobile-app-builder'); ?>">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><?php _e('Are you sure you want to delete this item?','mobile-app-builder');?></p>
</div>