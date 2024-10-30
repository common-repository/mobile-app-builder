
    <div data-role="navbar" data-iconpos="bottom">
        <ul>
            <li><a href="<?php echo $urlHome; ?>" class="taba ui-btn-icon-notext ui-icon-fa-home"></a></li><?php


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

}            echo '<li><a href="'.$link.'" data-transition="slideup"  class="taba ui-btn-icon-notext ui-icon-fa-'.$relatedJson['icon'].'"></a></li>';

            $u++;
            if($u == 3) break;
            endwhile;
            ?>

            <li><a href="#mypanel" class="taba ui-btn-icon-notext ui-icon-fa-ellipsis-h"></a></li>
        </ul>
    </div>

