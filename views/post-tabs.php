<?php
$current_user = wp_get_current_user();
$status = isset($_GET['mab_type']) ? $_GET['mab_type'] : 'publish';
$paged = isset($_GET['mab_page']) ? $_GET['mab_page'] : 1;
$per_page = (isset($mab_misc['posts_per_page']) && is_numeric($mab_misc['posts_per_page'])) ? $mab_misc['posts_per_page'] : 10;
$author_posts = new WP_Query(array('posts_per_page' => $per_page,'post_type' => 'mab_apps', 'paged' => $paged, 'orderby' => 'DESC', 'author' => $current_user->ID, 'post_status' => $status));
$old_exist = ($paged * $per_page) < $author_posts->found_posts;
$new_exist = $paged > 1;
?>
<div id="mab-posts">
	<div id="mab-message"></div>
	<ul>
		<li><a <?php if ($status == 'publish'): ?>class="active"<?php endif; ?>
			   href="?mab_type=publish"><?php _e('Live', 'mobile-app-builder'); ?></a></li>
		<li><a <?php if ($status == 'pending'): ?>class="active"<?php endif; ?>
			   href="?mab_type=pending"><?php _e('Pending', 'mobile-app-builder'); ?></a></li>
	</ul>
	<div id="mab-post-table-container">
		<?php if (!$author_posts->have_posts()): ?><?php _e('You have not created any apps yet. ','mobile-app-builder'); ?>
			<a href="<?php echo get_the_permalink( get_option('mab_new_page_id') ); ?>"><?php _e('Click here to add your first app.', 'mobile-app-builder'); ?></a>
		<?php else: ?>
			<p><?php printf(__('%s app(s).', 'mobile-app-builder'), $author_posts->found_posts); ?></p>
		<?php endif; ?>
		<table>
			<?php
while ($author_posts->have_posts()) : $author_posts->the_post();
$postid = get_the_ID();
$image = get_post_meta( $postid, 'icon', true );
if($image != '') $image = '<center><img src="'.$image.'" style="width:50px;height:50px"></center>';
else $image = '';
?>
				<tr id="mab-row-<?php echo $postid ?>" class="mab-row">
					<td><?php echo  $image; ?></td>
					<td><?php the_title(); ?></td>
					<?php if ($status == 'publish'): ?>
						<td class="mab-fixed-td"><a href="<?php the_permalink(); ?>?mab_preview_app=1" target="_blank"
													title="<?php _e('View App', 'mobile-app-builder'); ?>"><?php _e('Preview App', 'mobile-app-builder'); ?></a>
						</td><?php endif; ?>
					<td class="mab-fixed-td"><a
							href="?mab_action=edit&mab_id=<?php echo $postid; ?><?php echo (isset($_SERVER['QUERY_STRING']) ? '&' . $_SERVER['QUERY_STRING'] : '') ?>"><?php _e('Edit', 'mobile-app-builder'); ?></a>
					</td>
					<td class="post-delete mab-fixed-td"><img id="mab-loading-img-<?php echo $postid ?>"
															  class="mab-loading-img"
															  src="<?php echo plugins_url('static/img/ajax-loading.gif', dirname(__FILE__)); ?>"><a
							href="#"><?php _e('Delete', 'mobile-app-builder'); ?></a><input type="hidden"
																							 class="post-id"
																							 value="<?php echo $postid ?>">
					</td>
				</tr>
			<?php endwhile; ?>
		</table>
		<?php wp_nonce_field('fepnonce_delete_action', 'fepnonce_delete'); ?>
		<div class="mab-nav">
			<?php if ($new_exist): ?>
				<a class="mab-nav-link mab-nav-link-left" href="?mab_type=<?php echo $status ?>&mab_page=<?php echo ($paged - 1) ?>">
					&#10094; <?php _e('Newer Posts', 'mobile-app-builder'); ?></a>
			<?php endif; ?>
			<?php if ($old_exist): ?>
				<a class="mab-nav-link mab-nav-link-right"
				   href="?mab_type=<?php echo $status ?>&mab_page=<?php echo ($paged + 1) ?>"><?php _e('Older Posts', 'mobile-app-builder'); ?>
					&#10095;</a>
			<?php endif; ?>
			<div style="clear:both;"></div>
		</div>
		<?php wp_reset_query();
wp_reset_postdata(); ?>
	</div>
</div>