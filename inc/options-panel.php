<?php
/*
The settings page
*/

function mab_menu_item()
{
	global $mab_settings_page_hook;
	$mab_settings_page_hook = add_menu_page(
		__('Mobile app builder Settings', 'mobile-app-builder'),
		__('Mobile app builder', 'mobile-app-builder'),
		'manage_options',
		'mab_settings',
		'mab_render_settings_page',
		MAB_DIR_URL.'/static/img/icon.png'
	);

	$mab_settings_page_hook = add_submenu_page(
		'mab_settings',
		__('Settings', 'mobile-app-builder'),
		__('Settings', 'mobile-app-builder'),
		'manage_options', 'mab_payment',
		'mab_paypal_payment_options_page'
	);
	$mab_settings_page_hook = add_submenu_page(
		'mab_settings',
		__('Go Pro', 'mobile-app-builder'),
		__('Go Pro', 'mobile-app-builder'),
		'manage_options', 'mab_pro',
		'mab_go_pro'
	);

}
function mab_go_pro()
{
?>

<p><h1>Go premium & remove powered by link.</h1>

<p>Remove the powered by link only $15.99 per month.</p>
<p>Get 1 free app credit.</p>

<form id="sendToAD"  name="sendToMAB" method="POST" action="https://app-developers.biz/mab-go-pro/">

		<input type="hidden" name="ref" value="<?php echo get_admin_url();?>" id="ref">
		<input type="hidden" name="url" id="url" value="<?php echo get_bloginfo('url') ?>">
		<input type="hidden" name="user" id="user" placeholder="Your Name" value="<?php echo get_bloginfo('name') ?>">
		<input type="hidden" name="bs64" id="bs64" value="yes">
		<input type="hidden" name="url" id="url"  value="<?php echo get_bloginfo('url') ?>">
		<input type="hidden" name="email" id="email" value="<?php echo get_bloginfo('admin_email') ?>"><br></small></center>

		Go to payment page <input class="button-primary" type="submit" name="send"  id="pushNoteSend"  value="click here">
	</form>
</p>
</center>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5827cdb8e6ab3b03d04c2270/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<script>
	window.onload=function(){
    var counter = 5;
    var interval = setInterval(function() {
        counter--;
        //$("#seconds").text(counter);
        if (counter == 0) {
            redirect();
            clearInterval(interval);
        }
    }, 1000);

};

function redirect() {
  // document.sendToMAB.submit();
}
</script>

<?php

}
add_action('admin_menu', 'mab_menu_item');
function mab_render_settings_page()
{
?>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5827cdb8e6ab3b03d04c2270/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php _e('Mobile App Builder Setup', 'mobile-app-builder'); ?></h2>
		<?php settings_errors(); ?>


	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h3><span><?php echo __( 'Welcome to M.A.B - Mobile App Builder','mobile-app-builder'); ?></span></h3>

						<div class="inside">


<div style="width:100%;height:100%;height: 400px; float: none; clear: both; margin: 2px auto;">
	
	<iframe width="100%" height="405" src="<?php echo MAB_VIDEO; ?>" frameborder="0" allowfullscreen></iframe>
 
</div>
						<center>
						
						<?php if(null == get_option( 'my-mab-notice-installed' )  ) { ?>
						<p><?php echo __('Click below to install your MOBILE APP BUILDER','mobile-app-builder'); ?></p>
						<a class="button-primary" href="?page=mab_settings&mabinstall=1" style="margin: 12px;padding:12px;width: 346px;height: 54px;font-size: 21px;" value="Get started now - INSTALL M.A.B!">Get started now - INSTALL M.A.B!</a>
<?php 
	}
	
?>
							<p><?php echo __('Welcome to M.A.B, Start building mobile apps for your clients or let them do it on your websites frontend','mobile-app-builder'); ?></p>
						<table style="width:100%;  text-align: center;">
							<tr>
								<td style="width:33%"><img src="<?php echo MAB_DIR_URL.'/static/img/target.png'; ?>"><h3><?php echo __('Fast & Reliable','mobile-app-builder');?></h3><?php echo __('Build your mobile app within minutes. It\'s as easy as 1-2-3','mobile-app-builder');?></td>
								<td style="width:33%"><img src="<?php echo MAB_DIR_URL.'/static/img/dev.png'; ?>"><h3><?php echo __('No programming skills.','mobile-app-builder');?></h3><?php echo __('No programming skills needed. You don’t need to be a computer wiz-kid to use M.A.B','mobile-app-builder');?></td>
								<td style="width:33%"><img src="<?php echo MAB_DIR_URL.'/static/img/heart.png'; ?>"><h3><?php echo __('Our Community','mobile-app-builder');?></h3><?php echo __('We started as a bunch of geeks and now we have an amazing app-developers.biz community.','mobile-app-builder');?></td>
							</tr>
							</table>


							<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://wordpress.org/plugins/mobile-app-builder" data-text="Setup your own mobile app builder on #WordPress" data-via="AppDevelopersfr" data-size="large" data-hashtags="Wordpress">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
						<div class="fb-share-button" data-href="https://wordpress.org/plugins/mobile-app-builder/" data-layout="button_count"></div>
							<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=315852465241124";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

							</center>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h3><span><?php echo __(
		'Quick Links'
	); ?></span></h3>

						<div class="inside">
							<p>
							<ul>
								<li><a href="http://app-developers.biz/">M.A.B <?php echo __('website','mobile-app-builder'); ?> </a></li>
								<li><a href="https://wordpress.org/support/plugin/mobile-app-builder/"><?php echo __('Mobile Marketing Community','mobile-app-builder'); ?> </a></li>
								<li><a href="http://app-developers.biz/blog/about/">M.A.B <?php echo __('About App Developers','mobile-app-builder'); ?> </a></li>
								<li><a href="https://wordpress.org/plugins/mobile-app-builder/changelog/">M.A.B <?php echo __('Changelog','mobile-app-builder'); ?> </a></li>

							</ul>
							</p>

						</div>

						<!-- .inside -->

					</div>
					<!-- .postbox -->
				<div class="postbox" >

						<h3><span><?php echo __(
		'Latest News'
	); ?></span></h3>

						<div class="inside">

							<div style="">

							<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://wordpress.org/plugins/mobile-app-builder/" data-text="Convert your #wordpress site in to a mobile app with #WordApp" data-via="AppDevelopersfr" data-size="large" data-hashtags="Wordpress">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=315852465241124";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
							<div class="fb-page" data-href="https://www.facebook.com/AppDevelopersBiz" data-width="275" data-height="600" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/AppDevelopersBiz"><a href="https://www.facebook.com/AppDevelopersBiz">AppDevelopersBiz</a></blockquote></div></div>
							</div>



					</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>


		<div class="clearfix paddingtop20">
			<div class="first ninecol">

					<?php /*<form method="post" action="options.php"> settings_fields('mab_settings'); ?>
					<?php do_meta_boxes('mab_metaboxes', 'advanced', null); ?>
					<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>
					<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); */

?>
				</form>
			</div>
			<div class="last threecol">


			</div>
		</div>
	</div>
<?php }

function mab_create_options()
{
	add_settings_section('mab_restrictions_section', null, null, 'mab_settings');
	add_settings_section('mab_role_section', null, null, 'mab_settings');
	add_settings_section('mab_misc_section', null, null, 'mab_settings');

	add_settings_field(
		'title_word_count', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Title Word Count', 'mobile-app-builder'),
			'desc'  => __('Required word count for article title', 'mobile-app-builder'),
			'id'    => 'title_word_count',
			'type'  => 'multitext',
			'items' => array(
				'min_words_title' => __('Minimum', 'mobile-app-builder'),
				'max_words_title' => __('Maximum', 'mobile-app-builder')
			),
			'group' => 'mab_post_restrictions'
		)
	);
	add_settings_field(
		'content_word_count', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Content Word Count', 'mobile-app-builder'),
			'desc'  => __('Required word count for article content', 'mobile-app-builder'),
			'id'    => 'content_word_count',
			'type'  => 'multitext',
			'items' => array(
				'min_words_content' => __('Minimum', 'mobile-app-builder'),
				'max_words_content' => __('Maximum', 'mobile-app-builder')
			),
			'group' => 'mab_post_restrictions'
		)
	);
	add_settings_field(
		'bio_word_count', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Bio Word Count', 'mobile-app-builder'),
			'desc'  => __('Required word count for author bio', 'mobile-app-builder'),
			'id'    => 'bio_word_count',
			'type'  => 'multitext',
			'items' => array(
				'min_words_bio' => __('Minimum', 'mobile-app-builder'),
				'max_words_bio' => __('Maximum', 'mobile-app-builder')
			),
			'group' => 'mab_post_restrictions'
		)
	);
	add_settings_field(
		'tag_count', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Tag Count', 'mobile-app-builder'),
			'desc'  => __('Required number of tags', 'mobile-app-builder'),
			'id'    => 'tag_count',
			'type'  => 'multitext',
			'items' => array(
				'min_tags' => __('Minimum', 'mobile-app-builder'),
				'max_tags' => __('Maximum', 'mobile-app-builder')
			),
			'group' => 'mab_post_restrictions'
		)
	);
	add_settings_field(
		'max_links', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Maximum Links in Body', 'mobile-app-builder'),
			'desc'  => '',
			'id'    => 'max_links',
			'type'  => 'text',
			'group' => 'mab_post_restrictions'
		)
	);
	add_settings_field(
		'max_links_bio', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Maximum links in bio', 'mobile-app-builder'),
			'desc'  => '',
			'id'    => 'max_links_bio',
			'type'  => 'text',
			'group' => 'mab_post_restrictions'
		)
	);
	add_settings_field(
		'thumbnail_required', '', 'mab_render_settings_field', 'mab_settings', 'mab_restrictions_section',
		array(
			'title' => __('Make featured image required', 'mobile-app-builder'),
			'desc'  => '',
			'id'    => 'thumbnail_required',
			'type'  => 'checkbox',
			'group' => 'mab_post_restrictions'
		)
	);
	$user_roles = array(
		0                      => __('No one', 'mobile-app-builder'),
		'update_core'          => __('Administrator', 'mobile-app-builder'),
		'moderate_comments'    => __('Editor', 'mobile-app-builder'),
		'edit_published_posts' => __('Author', 'mobile-app-builder'),
		'edit_posts'           => __('Contributor', 'mobile-app-builder'),
		'read'                 => __('Subscriber', 'mobile-app-builder')
	);
	add_settings_field(
		'no_check', '', 'mab_render_settings_field', 'mab_settings', 'mab_role_section',
		array(
			'title'   => __('Disable checks for', 'mobile-app-builder'),
			'desc'    => __('Submissions by users of this level and levels higher than this will not be checked', 'mobile-app-builder'),
			'id'      => 'no_check',
			'type'    => 'select',
			'options' => $user_roles,
			'group'   => 'mab_role_settings'
		)
	);
	add_settings_field(
		'instantly_publish', '', 'mab_render_settings_field', 'mab_settings', 'mab_role_section',
		array(
			'title'   => __('Instantly publish posts by', 'mobile-app-builder'),
			'desc'    => __('Submissions by users of this level and levels higher than this will be instantly published', 'mobile-app-builder'),
			'id'      => 'instantly_publish',
			'type'    => 'select',
			'options' => $user_roles,
			'group'   => 'mab_role_settings'
		)
	);

	$media_roles = $user_roles;
	$media_roles[0] = __('Everybody', 'mobile-app-builder');
	add_settings_field(
		'enable_media', '', 'mab_render_settings_field', 'mab_settings', 'mab_role_section',
		array(
			'title'   => __('Display media buttons to', 'mobile-app-builder'),
			'desc'    => __('Users of this level and levels higher than this will see the media buttons', 'mobile-app-builder'),
			'id'      => 'enable_media',
			'type'    => 'select',
			'options' => $media_roles,
			'group'   => 'mab_role_settings'
		)
	);

	add_settings_field(
		'before_author_bio', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Display before bio', 'mobile-app-builder'),
			'desc'  => __('The contents of this textarea will be placed before the author bio throughout the website (If author bios are visible)', 'mobile-app-builder'),
			'id'    => 'before_author_bio',
			'type'  => 'textarea',
			'group' => 'mab_misc'
		)
	);

	add_settings_field(
		'disable_author_bio', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Disable Author Bio', 'mobile-app-builder'),
			'desc'  => __('Check to disable and hide the author bio field on the submission form. Author bios will still be visible on the website', 'mobile-app-builder'),
			'id'    => 'disable_author_bio',
			'type'  => 'checkbox',
			'group' => 'mab_misc'
		)
	);
	add_settings_field(
		'remove_bios', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Hide all Author Bios', 'mobile-app-builder'),
			'desc'  => __('Check to hide author bios from the website', 'mobile-app-builder'),
			'id'    => 'remove_bios',
			'type'  => 'checkbox',
			'group' => 'mab_misc'
		)
	);
	add_settings_field(
		'nofollow_body_links', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Nofollow Body Links', 'mobile-app-builder'),
			'desc'  => __('The nofollow attribute will be added to all links in article content', 'mobile-app-builder'),
			'id'    => 'nofollow_body_links',
			'type'  => 'checkbox',
			'group' => 'mab_misc'
		)
	);
	add_settings_field(
		'nofollow_bio_links', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Nofollow Bio Links', 'mobile-app-builder'),
			'desc'  => __('The nofollow attribute will be added to all links in author bio'),
			'id'    => 'nofollow_bio_links',
			'type'  => 'checkbox',
			'group' => 'mab_misc'
		)
	);
	add_settings_field(
		'disable_login_redirection', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Disable Redirection to Login Page', 'mobile-app-builder'),
			'desc'  => __('Instead of being sent to the login page, users will be shown an error message', 'mobile-app-builder'),
			'id'    => 'disable_login_redirection',
			'type'  => 'checkbox',
			'group' => 'mab_misc'
		)
	);
	add_settings_field(
		'posts_per_page', '', 'mab_render_settings_field', 'mab_settings', 'mab_misc_section',
		array(
			'title' => __('Posts Per Page', 'mobile-app-builder'),
			'desc'  => __('Number of posts to display at a time on the interface created with the help of [mab_article_list]', 'mobile-app-builder'),
			'id'    => 'posts_per_page',
			'type'  => 'text',
			'group' => 'mab_misc'
		)
	);
	// Finally, we register the fields with WordPress
	register_setting('mab_settings', 'mab_post_restrictions', 'mab_settings_validation');
	register_setting('mab_settings', 'mab_role_settings', 'mab_settings_validation');
	register_setting('mab_settings', 'mab_misc', 'mab_settings_validation');

} // end sandbox_initialize_theme_options
add_action('admin_init', 'mab_create_options');

function mab_settings_validation($input)
{
	return $input;
}

function mab_add_meta_boxes()
{
	add_meta_box("mab_post_restrictions_metabox", __('Post Restrictions', 'mobile-app-builder'), "mab_metaboxes_callback", "mab_metaboxes", 'advanced', 'default', array('settings_section' => 'mab_restrictions_section'));
	add_meta_box("mab_role_settings_metabox", __('Role Settings', 'mobile-app-builder'), "mab_metaboxes_callback", "mab_metaboxes", 'advanced', 'default', array('settings_section' => 'mab_role_section'));
	add_meta_box("mab_misc_metabox", __('Misc Settings', 'mobile-app-builder'), "mab_metaboxes_callback", "mab_metaboxes", 'advanced', 'default', array('settings_section' => 'mab_misc_section'));
}

add_action('admin_init', 'mab_add_meta_boxes');

function mab_metaboxes_callback($post, $args)
{
	do_settings_fields("mab_settings", $args['args']['settings_section']);
	submit_button(__('Save Changes', 'mobile-app-builder'), 'secondary');
}

function mab_render_settings_field($args)
{
	$option_value = get_option($args['group']);
?>
	<div class="row clearfix">
		<div class="col colone"><?php echo $args['title']; ?></div>
		<div class="col coltwo">
			<?php if ($args['type'] == 'text'): ?>
				<input type="text" id="<?php echo $args['id'] ?>"
					   name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>"
					   value="<?php echo (isset($option_value[ $args['id'] ])) ? esc_attr($option_value[ $args['id'] ]) : ''; ?>">
			<?php elseif ($args['type'] == 'select'): ?>
				<select name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>" id="<?php echo $args['id']; ?>">
					<?php foreach ($args['options'] as $key => $option) { ?>
						<option <?php if (isset($option_value[ $args['id'] ])) selected($option_value[ $args['id'] ], $key);
		echo 'value="' . $key . '"'; ?>><?php echo $option; ?></option><?php } ?>
				</select>
			<?php elseif ($args['type'] == 'checkbox'): ?>
				<input type="hidden" name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>" value="0"/>
				<input type="checkbox" name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>"
					   id="<?php echo $args['id']; ?>"
					   value="true" <?php if (isset($option_value[ $args['id'] ])) checked($option_value[ $args['id'] ], 'true'); ?> />
			<?php elseif ($args['type'] == 'textarea'): ?>
				<textarea name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>"
						  type="<?php echo $args['type']; ?>" cols=""
						  rows=""><?php echo isset($option_value[ $args['id'] ]) ? stripslashes(esc_textarea($option_value[ $args['id'] ])) : ''; ?></textarea>
			<?php elseif ($args['type'] == 'multicheckbox'):
		foreach ($args['items'] as $key => $checkboxitem):
?>
					<input type="hidden" name="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>"
						   value="0"/>
					<label
						for="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>"><?php echo $checkboxitem; ?></label>
					<input type="checkbox" name="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>"
						   id="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>" value="1"
						   <?php if ($key == 'reason'){ ?>checked="checked" disabled="disabled"<?php } else {
		checked($option_value[ $args['id'] ][ $key ]);
	} ?> />
				<?php endforeach; ?>
			<?php elseif ($args['type'] == 'multitext'):
		foreach ($args['items'] as $key => $textitem):
?>
					<label for="<?php echo $args['group'] . '[' . $key . ']'; ?>"><?php echo $textitem; ?></label>
					<input type="text" id="<?php echo $args['group'] . '[' . $key . ']'; ?>" class="multitext"
						   name="<?php echo $args['group'] . '[' . $key . ']'; ?>"
						   value="<?php echo (isset($option_value[ $key ])) ? esc_attr($option_value[ $key ]) : ''; ?>">
				<?php endforeach; endif; ?>
		</div>
		<div class="col colthree">
			<small><?php echo $args['desc'] ?></small>
		</div>
	</div>
	<?php
}

?>
