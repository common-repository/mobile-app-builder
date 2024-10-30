var hasChanged = false;

function confirmExit() {
	var mce = typeof(tinyMCE) != 'undefined' ? tinyMCE.activeEditor : false;
	if (hasChanged || (mce && !mce.isHidden() && mce.isDirty())) return mab_messages.unsaved_changes_warning;
}
//window.onbeforeunload = confirmExit;

function substr_count(mainString, subString) {
	var re = new RegExp(subString, 'g');
	if (!mainString.match(re) || !mainString || !subString) return 0;
	var count = mainString.match(re);
	return count.length;
}

function str_word_count(s) {
	if (!s.length) return 0;
	s = s.replace(/(^\s*)|(\s*$mab)/gi, "");
	s = s.replace(/[ ]{2,}/gi, " ");
	s = s.replace(/\n /, "\n");
	return s.split(' ').length;
}

function countTags(s) {
	if (!s.length) return 0;
	return s.split(',').length;
}

function post_has_errors(title, content, bio, category, tags, fimg) {
	var error_string = '';
	if (mab_rules.check_required == false) return false;
	if ((mab_rules.min_words_title != 0 && title === '') || (mab_rules.min_words_content != 0 && content === '') || (mab_rules.min_words_bio != 0 && bio === '') || category == -1 || (mab_rules.min_tags != 0 && tags === '')) error_string = mab_messages.required_field_error + '<br/>';
	var stripped_content = content.replace(/(<([^>]+)>)/ig, "");
	var stripped_bio = bio.replace(/(<([^>]+)>)/ig, "");
	if (title != '' && str_word_count(title) < mab_rules.min_words_title) error_string += mab_messages.title_short_error + '<br/>';
	if (content != '' && str_word_count(title) > mab_rules.max_words_title) error_string += mab_messages.title_long_error + '<br/>';
	if (content != '' && str_word_count(stripped_content) < mab_rules.min_words_content) error_string += mab_messages.article_short_error + '<br/>';
	if (str_word_count(stripped_content) > mab_rules.max_words_content) error_string += mab_messages.article_long_error + '<br/>';
	if (bio != -1 && bio != '' && str_word_count(stripped_bio) < mab_rules.min_words_bio) error_string += mab_messages.bio_short_error + '<br/>';
	if (bio != -1 && str_word_count(stripped_bio) > mab_rules.max_words_bio) error_string += mab_messages.bio_long_error + '<br/>';
	if (substr_count(content, '</a>') > mab_rules.max_links) error_string += mab_messages.too_many_article_links_error + '<br/>';
	if (substr_count(bio, '</a>') > mab_rules.max_links_bio) error_string += mab_messages.too_many_bio_links_error + '<br/>';
	if (tags != '' && countTags(tags) < mab_rules.min_tags) error_string += mab_messages.too_few_tags_error + '<br/>';
	if (countTags(tags) > mab_rules.max_tags) error_string += mab_messages.too_many_tags_error + '<br/>';
	if (mab_rules.thumbnail_required && mab_rules.thumbnail_required == 'true' && fimg == -1) error_string += mab_messages.featured_image_error + '<br/>';
	if (error_string == '') return false;
	else return '<strong>' + mab_messages.general_form_error + '</strong><br/>' + error_string;
}
 jQuery.noConflict();

var $mab =  jQuery.noConflict();


$mab(document).ready(function($mab) {
	$mab.fn.refreshtabs = function() {
		var id = $mab('#mab-post-id').val();
		var nonce = $mab("#fepnonce").val();
		//alert(id);
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_refresh_tabs',
				id: id,
				nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				//console.log(data);
				$mab('.mablist').html(data);
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
	};
	$mab.fn.updateimages = function() {
		var id = $mab('#mab-post-id').val();
		var nonce = $mab("#fepnonce").val();
		var back = $mab("#mab-backgroundurl").val();
		var icon = $mab("#mab-iconurl").val();
		var splash = $mab("#mab-splashurl").val();
		//alert(id);
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_update_images',
				post_id: id,
				post_nonce: nonce,
				background: back,
				icon: icon,
				splash: splash
			},
			success: function(data, textStatus, XMLHttpRequest) {
				//console.log(data);
				//$mab('.mablist').html(data);
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
	};
	$mab("input, textarea, #mab-post-content").keydown(function() {
		hasChanged = true;
	});
	$mab("select").change(function() {
		hasChanged = true;
	});
	$mab("td.post-delete a").click(function(event) {
		var id = $mab(this).siblings('.post-id').first().val();
		var nonce = $mab('#fepnonce_delete').val();
		var loadimg = $mab(this).siblings('.mab-loading-img').first();
		var row = $mab(this).closest('.mab-row');
		var message_box = $mab('#mab-message');
		var post_count = $mab('#mab-posts .count');
		var confirmation = confirm(mab_messages.confirmation_message);
		if (!confirmation) return;
		$mab(this).hide();
		loadimg.show().css({
			'float': 'none',
			'box-shadow': 'none'
		});
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_delete_posts',
				post_id: id,
				delete_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				var arr = $mab.parseJSON(data);
				message_box.html('');
				if (arr.success) {
					row.hide();
					message_box.show().addClass('success').append(arr.message);
					post_count.html(Number(post_count.html()) - 1);
				} else {
					message_box.show().addClass('warning').append(arr.message);
				}
				if (message_box.offset().top < $mab(window).scrollTop()) {
					$mab('html, body').animate({
						scrollTop: message_box.offset().top - 10
					}, 'slow');
				}
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		event.preventDefault();
	});
	$mab("#mab-submit-post.active-btn").on('click', function() {
		tinyMCE.triggerSave();
		var title = $mab("#mab-post-title").val();
		var content = $mab("#mab-post-content").val();
		var bio = $mab("#mab-about").val();
		var category = $mab("#mab-category").val();
		var tags = $mab("#mab-tags").val();
		var location = $mab("#mab-json").val();
		var pid = $mab("#mab-post-id").val();
		var fimg = $mab("#mab-featured-image-id").val();
		var nonce = $mab("#fepnonce").val();
		var message_box = $mab('#mab-message');
		var form_container = $mab('#mab-new-post');
		var submit_btn = $mab('#mab-submit-post');
		var load_img = $mab("img.mab-loading-img");
		var submission_form = $mab('#mab-submission-form');
		var post_id_input = $mab("#mab-post-id");
		var errors = post_has_errors(title, content, bio, category, tags, fimg);
		if (errors) {
			if (form_container.offset().top < $mab(window).scrollTop()) {
				$mab('html, body').animate({
					scrollTop: form_container.offset().top - 10
				}, 'slow');
			}
			message_box.removeClass('success').addClass('warning').html('').show().append(errors);
			return;
		}
		load_img.show();
		//submit_btn.attr("disabled", true).removeClass('active-btn').addClass('passive-btn');
		$mab.ajaxSetup({
			cache: false
		});
		//alert(fepajaxhandler.ajaxurl);
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_process_form_input',
				post_title: title,
				post_content: content,
				about_the_author: bio,
				post_category: category,
				post_tags: tags,
				_json: location,
				post_id: pid,
				post_type: 'mab_apps',
				featured_img: fimg,
				post_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				var arr = $mab.parseJSON(data);
				if (arr.success) {
					submission_form.hide();
					post_id_input.val(arr.post_id);
					message_box.removeClass('warning').addClass('success');
				} else message_box.removeClass('success').addClass('warning');
				message_box.html('').append(arr.message).show();
				if (form_container.offset().top < $mab(window).scrollTop()) {
					$mab('html, body').animate({
						scrollTop: form_container.offset().top - 10
					}, 'slow');
				}
				load_img.hide();
				//submit_btn.attr("disabled", false).removeClass('passive-btn').addClass('active-btn');
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
	});
	$mab('body').on('click', '#mab-continue-editing', function(e) {
		$mab('#mab-message').hide();
		$mab('#mab-submission-form').show();
		e.preventDefault();
	}); /* Icon */
	$mab('#mab-featured-image a#mab-featured-image-link').click(function(e) {
		e.preventDefault();
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: mab_messages.media_lib_string,
			button: {
				text: mab_messages.media_lib_string
			},
			multiple: false
		});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$mab('#mab-featured-image input#mab-featured-image-id').val(attachment.id);
			$mab('#mab-iconurl').val(attachment.url);
			$mab('#mab-iconurl').updateimages();
			$mab.ajax({
				type: 'POST',
				url: fepajaxhandler.ajaxurl,
				data: {
					action: 'mab_fetch_featured_image',
					img: attachment.id
				},
				success: function(data, textStatus, XMLHttpRequest) {
					$mab('#mab-featured-image-container').html(data);
					hasChanged = true;
				},
				error: function(MLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		});
		custom_uploader.open();
	}); /* App Splash */
	$mab('#mab-featured-image-splash a#mab-featured-image-link-splash').click(function(e) {
		e.preventDefault();
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: mab_messages.media_lib_string,
			button: {
				text: mab_messages.media_lib_string
			},
			multiple: false
		});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$mab('#mab-featured-image-splash input#mab-featured-image-id-splash').val(attachment.id);
			$mab('#mab-splashurl').val(attachment.url);
			$mab('#mab-splashurl').updateimages();
			$mab.ajax({
				type: 'POST',
				url: fepajaxhandler.ajaxurl,
				data: {
					action: 'mab_fetch_featured_image_splash',
					img: attachment.id
				},
				success: function(data, textStatus, XMLHttpRequest) {
					$mab('#mab-featured-image-container-splash').html(data);
					hasChanged = true;
				},
				error: function(MLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		});
		custom_uploader.open();
	}); /* App Background */
	$mab('#mab-featured-image-background a#mab-featured-image-link-background').click(function(e) {
		e.preventDefault();
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: mab_messages.media_lib_string,
			button: {
				text: mab_messages.media_lib_string
			},
			multiple: false
		});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$mab('#mab-featured-image input#mab-featured-image-id-background').val(attachment.id);
			$mab('#mab-backgroundurl').val(attachment.url);
			$mab('#mab-backgroundurl').updateimages();
			$mab.ajax({
				type: 'POST',
				url: fepajaxhandler.ajaxurl,
				data: {
					action: 'mab_fetch_featured_image_background',
					img: attachment.id
				},
				success: function(data, textStatus, XMLHttpRequest) {
					$mab('#mab-featured-image-container-background').html(data);
					hasChanged = true;
					var iframe = document.getElementById('iphonedemo');
					iframe.src = iframe.src;
				},
				error: function(MLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		});
		custom_uploader.open();
	});
});
$mab(document).ready(function($mab) {
	$mab(".sticker").sticky({
		topSpacing: 0
	});
	$mab('#mabFinished').click(function(event) {
		event.preventDefault();
		var post__id = $mab("#mab-post-id").val();
		var url = $mab(this).data('target') + '?id=' + post__id;
		location.replace(url);
	});
	$mab('.mabstyle').click(function() {
		$mab('.mabstyle').removeClass('mabselected');
		$mab(this).toggleClass('mabselected');
		var clicked = $mab(this).attr('title');
		var clickedid = $mab(this).attr('id');
		$mab('#mab-json-style').val(clicked);
		var nonce = $mab("#fepnonce").val();
		var post__id = $mab("#mab-post-id").val();
/*NOTE: 1 AJAX.php (PHP) function for the two sytles using field and val
			
		*/
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_save_style',
				post_id: post__id,
				field: 'jsonstyle',
				//jsonstyle or jsonswatch
				value: clicked,
				value_id: clickedid,
				post_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				$mab.growl.notice({
					title: fepajaxhandler.success,
					message: fepajaxhandler.successmessage
				});
				$mab('#mablist').refreshtabs();
				var iframe = document.getElementById('iphonedemo');
				iframe.src = iframe.src;
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		return false;
	});
	$mab('.mabswatch').click(function() {
		$mab('.mabswatch').removeClass('mabselectedswatch');
		$mab(this).toggleClass('mabselectedswatch');
		var clicked = $mab('.swatch:checked').val();
		//alert(clicked);
		$mab('#mab-json-style-swatch').val(clicked);
		var nonce = $mab("#fepnonce").val();
		var post__id = $mab("#mab-post-id").val();
/*NOTE: 1 AJAX.php (PHP) function for the two sytles using field and val
			
		*/
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_save_style',
				post_id: post__id,
				field: 'jsonswatch',
				//jsonstyle or jsonswatch
				value: this.id,
				post_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				$mab.growl.notice({
					title: fepajaxhandler.success,
					message: fepajaxhandler.successmessage
				});
				$mab('#mablist').refreshtabs();
				var iframe = document.getElementById('iphonedemo');
				iframe.src = iframe.src;
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		return false;
		//$mab("#"+clicked).removeClass("hidden").siblings().addClass("hidden");
	});
	$mab("#tags").autocomplete({
		source: "?fb_json=1",
		minLength: 2,
		max: 10,
		highlight: false,
		scroll: true,
		scrollHeight: 300,
		search: function(event, ui) {
			$mab('.spinner').show();
		},
		response: function(event, ui) {
			$mab('.spinner').hide();
		},
		select: function(event, uidd) {
			var url = uidd.item.id;
			var fbname = uidd.item.value;
			//console.log(ui.item);
			if (url != '#') {
				location.href = '?fb=' + url + '&fbname=' + fbname + '&new_app=1';
			}
		},
		html: true,
		// optional ($mab.ui.autocomplete.html.js required)
		// optional (if other layers overlap autocomplete list)
		open: function(event, ui) {
			$mab(".ui-autocomplete").css("z-index", 1000);
		}
	}).autocomplete("instance")._renderItem = function(ul, item) {
		return $mab("<li>").append("<a href='?fb=" + item.id + "&fbname=" + item.value + "&new_app=1'><img src='http://graph.facebook.com/" + item.id + "/picture?redirect=1&height=200&type=normal&width=200' class='imgFacebook'/> <b>" + item.value + "</b><br />" + item.cat + "<br /><br /></a>").appendTo(ul);
	};
}); /* START */
$mab(document).ready(function($mab) {
	//! click home page
	// My_New_Global_Settings =  tinyMCEPreInit.mceInit['mceEditor']; 
	if (typeof(tinyMCE) == "object" && typeof(tinyMCE.execCommand) == "function") {
		tinymce.init({
			selector: 'textarea#blurbhome',
			menubar: false,
			height: "300"
		});
	}
	$mab('body').on('click', '.mabitemHome', function(event) {
		event.preventDefault();
		$mab('#mabloading').show();
		$mab('#mabhome').fadeIn("slow");
		$mab('#mabloading').hide();
		$mab('.mabprop').hide();
		$mab('#mabpages').fadeOut("fast");
		//tinymce.remove();
		//$mab("#blurb").addClass("mceEditor");
		My_New_Global_Settings = tinyMCEPreInit.mceInit['mceEditor'];
		if (typeof(tinyMCE) == "object" && typeof(tinyMCE.execCommand) == "function") {
			tinymce.init({
				selector: 'textarea#blurbhome',
				menubar: false,
				height: "480"
			});
		}
		return false;
	});
	//! adding a new page 
	$mab('.item2').click(function(event) {
		$mab(this).clone().insertAfter("div.mabitem:last");
		var title = $mab(this).data('modname');
		var parent_id = $mab(this).data('parent');
		var content = $mab(this).data('content');
		var post_json = $mab(this).data('json');
		//var content = $mab("#mab-post-content").val();
		//var location = $mab("#mab-json").val();
		var nonce = $mab("#fepnonce").val();
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_process_app_pages_new',
				post_title: title,
				_connect: parent_id,
				post_content: content,
				post_id: -1,
				_json: post_json,
				post_type: 'mab_apps_pages',
				post_nonce: nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				$mab('#dialog-message').dialog('close');
				$mab('#mablist').refreshtabs();
				var iframe = document.getElementById('iphonedemo');
				iframe.src = iframe.src;
				var arr = $mab.parseJSON(data);
				if (arr.success) {}
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
	});
	$mab('body').on('click', '#trashAppBuilder', function(event) {
		event.preventDefault();
		$mab("#dialog-confirm").dialog({
			resizable: false,
			height: "auto",
			width: 400,
			modal: true,
			buttons: {
				"Delete!": function() {
					var nonce = $mab("#fepnonce").val();
					var post__id = $mab("#_ID").val();
					var parent_id = $mab("#mab-post-id").val();
					$mab.ajax({
						type: 'POST',
						url: fepajaxhandler.ajaxurl,
						data: {
							action: 'mab_process_app_pages_delete',
							_connect: parent_id,
							post_id: post__id,
							post_nonce: nonce
						},
						success: function(data, textStatus, XMLHttpRequest) {
							hasChanged = false;
							$mab('#mablist').refreshtabs();
							$mab('#mabloading').show();
							$mab('#mabhome').fadeIn("slow");
							$mab('#mabloading').hide();
							$mab('.mabprop').hide();
							$mab('#mabpages').fadeOut("fast");
							if (typeof(tinyMCE) == "object" && typeof(tinyMCE.execCommand) == "function") {
								tinymce.init({
									selector: 'textarea#blurbhome',
									menubar: false,
									height: "480"
								});
							}
							var iframe = document.getElementById('iphonedemo');
							iframe.src = iframe.src;
							var arr = $mab.parseJSON(data);
							if (arr.success) {}
						},
						error: function(MLHttpRequest, textStatus, errorThrown) {
							alert(errorThrown);
						}
					});
					$mab(this).dialog("close");
				},
				Cancel: function() {
					$mab(this).dialog("close");
				}
			}
		});
		return false;
	});
/*
			
  } );
			
		*/
	//! click pages for editing
	$mab('body').on('click', '.mabitem', function(event) {
		event.preventDefault();
		var id = $mab(this).data('id');
		var icon = $mab(this).data("icon");
		var icontext = $mab(this).data("icontext");
		$mab('#previewText').val(icontext);
		$mab("#previewIcon").removeClass();
		$mab("#previewIcon").addClass('fa fa-' + icon + '  fa-3x');
		$mab('#mabhome').fadeOut("fast");
		$mab('#mabloading').show();
		$mab('.mabprop').show();
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_handle_request',
				id: id
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				$mab("#mabpages").html(data);
				$mab('#mabpages').fadeIn("slow");
				$mab('#mabloading').hide();
				//var arr = $mab.parseJSON(data);
				//console.log(arr);
				tinymce.remove();
				//$mab("#blurb").addClass("mceEditor");
				My_New_Global_Settings = tinyMCEPreInit.mceInit['mceEditor'];
				if (typeof(tinyMCE) == "object" && typeof(tinyMCE.execCommand) == "function") {
					tinymce.init({
						selector: 'textarea#blurb',
						menubar: false,
						height: "480"
					});
				}
				//load_img.hide();
				//submit_btn.attr("disabled", false).removeClass('passive-btn').addClass('active-btn');
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		return false;
	});
	//! SAVE PAGES (not homepage)
	$mab('body').on('click', '.btnSave', function(event) {
		event.preventDefault();
		var Base64 = {
			_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
			encode: function(e) {
				var t = "";
				var n, r, i, s, o, u, a;
				var f = 0;
				e = Base64._utf8_encode(e);
				while (f < e.length) {
					n = e.charCodeAt(f++);
					r = e.charCodeAt(f++);
					i = e.charCodeAt(f++);
					s = n >> 2;
					o = (n & 3) << 4 | r >> 4;
					u = (r & 15) << 2 | i >> 6;
					a = i & 63;
					if (isNaN(r)) {
						u = a = 64
					} else if (isNaN(i)) {
						a = 64
					}
					t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
				}
				return t
			},
			decode: function(e) {
				var t = "";
				var n, r, i;
				var s, o, u, a;
				var f = 0;
				e = e.replace(/[^A-Za-z0-9+/=]/g, "");
				while (f < e.length) {
					s = this._keyStr.indexOf(e.charAt(f++));
					o = this._keyStr.indexOf(e.charAt(f++));
					u = this._keyStr.indexOf(e.charAt(f++));
					a = this._keyStr.indexOf(e.charAt(f++));
					n = s << 2 | o >> 4;
					r = (o & 15) << 4 | u >> 2;
					i = (u & 3) << 6 | a;
					t = t + String.fromCharCode(n);
					if (u != 64) {
						t = t + String.fromCharCode(r)
					}
					if (a != 64) {
						t = t + String.fromCharCode(i)
					}
				}
				t = Base64._utf8_decode(t);
				return t
			},
			_utf8_encode: function(e) {
				e = e.replace(/rn/g, "n");
				var t = "";
				for (var n = 0; n < e.length; n++) {
					var r = e.charCodeAt(n);
					if (r < 128) {
						t += String.fromCharCode(r)
					} else if (r > 127 && r < 2048) {
						t += String.fromCharCode(r >> 6 | 192);
						t += String.fromCharCode(r & 63 | 128)
					} else {
						t += String.fromCharCode(r >> 12 | 224);
						t += String.fromCharCode(r >> 6 & 63 | 128);
						t += String.fromCharCode(r & 63 | 128)
					}
				}
				return t
			},
			_utf8_decode: function(e) {
				var t = "";
				var n = 0;
				var r = c1 = c2 = 0;
				while (n < e.length) {
					r = e.charCodeAt(n);
					if (r < 128) {
						t += String.fromCharCode(r);
						n++
					} else if (r > 191 && r < 224) {
						c2 = e.charCodeAt(n + 1);
						t += String.fromCharCode((r & 31) << 6 | c2 & 63);
						n += 2
					} else {
						c2 = e.charCodeAt(n + 1);
						c3 = e.charCodeAt(n + 2);
						t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
						n += 3
					}
				}
				return t
			}
		}
		var nonce = $mab("#fepnonce").val();
		var post__id = $mab("#_ID").val();
		var post__title = $mab("#previewText").val();
		if ($mab("#blurb")[0]) {
			// Do something if class exists
			var html = tinyMCE.get('#blurb').getContent();
			html = Base64.encode(html);
			$mab('#blurbHidden').val(html);
		} else {
			// Do something if class does not exist
		}
		//alert(html);
		var inputtosave = $mab('#mabhome :input');
		var fullstring = '{';
		//var arrayjson = new Array();
		$mab(".mabtextmain").each(function() {
			var id = this.id;
			var name = this.name;
			var value = this.value;
			var type = this.type;
			fullstring += '"' + this.name + '":"' + this.value + '",';
		});
		fullstring += '"plugins": [{';
		$mab(".mabtextedit").each(function() {
			var id = this.id;
			var name = this.name;
			var value = this.value;
			var type = this.type;
			var friendly = $mab(this).data('friendly');
			fullstring += '"' + this.name + '":"' + this.value + '",';
		});
		fullstring = fullstring.slice(0, -1);
		fullstring += '}]}';
		//$mab('#jsontext').val(fullstring);
		var id = $mab(this).data('id');
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_save_pages',
				id: id,
				edit: '1',
				post_title: post__title,
				post_nonce: nonce,
				post_id: post__id,
				arrayjs: fullstring
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				$mab.growl.notice({
					title: fepajaxhandler.success,
					message: fepajaxhandler.successmessage
				});
				$mab('#mablist').refreshtabs();
				var iframe = document.getElementById('iphonedemo');
				iframe.src = iframe.src;
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		return false;
	});
	$mab('body').on('click', '.btnHomeSave', function(event) {
		event.preventDefault();
		var Base64 = {
			_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
			encode: function(e) {
				var t = "";
				var n, r, i, s, o, u, a;
				var f = 0;
				e = Base64._utf8_encode(e);
				while (f < e.length) {
					n = e.charCodeAt(f++);
					r = e.charCodeAt(f++);
					i = e.charCodeAt(f++);
					s = n >> 2;
					o = (n & 3) << 4 | r >> 4;
					u = (r & 15) << 2 | i >> 6;
					a = i & 63;
					if (isNaN(r)) {
						u = a = 64
					} else if (isNaN(i)) {
						a = 64
					}
					t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
				}
				return t
			},
			decode: function(e) {
				var t = "";
				var n, r, i;
				var s, o, u, a;
				var f = 0;
				e = e.replace(/[^A-Za-z0-9+/=]/g, "");
				while (f < e.length) {
					s = this._keyStr.indexOf(e.charAt(f++));
					o = this._keyStr.indexOf(e.charAt(f++));
					u = this._keyStr.indexOf(e.charAt(f++));
					a = this._keyStr.indexOf(e.charAt(f++));
					n = s << 2 | o >> 4;
					r = (o & 15) << 4 | u >> 2;
					i = (u & 3) << 6 | a;
					t = t + String.fromCharCode(n);
					if (u != 64) {
						t = t + String.fromCharCode(r)
					}
					if (a != 64) {
						t = t + String.fromCharCode(i)
					}
				}
				t = Base64._utf8_decode(t);
				return t
			},
			_utf8_encode: function(e) {
				e = e.replace(/rn/g, "n");
				var t = "";
				for (var n = 0; n < e.length; n++) {
					var r = e.charCodeAt(n);
					if (r < 128) {
						t += String.fromCharCode(r)
					} else if (r > 127 && r < 2048) {
						t += String.fromCharCode(r >> 6 | 192);
						t += String.fromCharCode(r & 63 | 128)
					} else {
						t += String.fromCharCode(r >> 12 | 224);
						t += String.fromCharCode(r >> 6 & 63 | 128);
						t += String.fromCharCode(r & 63 | 128)
					}
				}
				return t
			},
			_utf8_decode: function(e) {
				var t = "";
				var n = 0;
				var r = c1 = c2 = 0;
				while (n < e.length) {
					r = e.charCodeAt(n);
					if (r < 128) {
						t += String.fromCharCode(r);
						n++
					} else if (r > 191 && r < 224) {
						c2 = e.charCodeAt(n + 1);
						t += String.fromCharCode((r & 31) << 6 | c2 & 63);
						n += 2
					} else {
						c2 = e.charCodeAt(n + 1);
						c3 = e.charCodeAt(n + 2);
						t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
						n += 3
					}
				}
				return t
			}
		}
		var nonce = $mab("#fepnonce").val();
		var post__id = $mab("#mab-post-id").val();
		var post__title = $mab("#mab-post-title").val();
		if ($mab("#blurbhome")[0]) {
			// Do something if class exists
			var html = tinyMCE.get('#blurbhome').getContent();
			html = Base64.encode(html);
			$mab('#blurbhomeHidden').val(html);
		} else {
			// Do something if class does not exist
		}
		//alert(html);
		var inputtosave = $mab('#mabhome :input');
		var fullstring = '{';
		//var arrayjson = new Array();
		$mab(".mabtextmain").each(function() {
			var id = this.id;
			var name = this.name;
			var value = this.value;
			var type = this.type;
			fullstring += '"' + this.name + '":"' + this.value + '",';
		});
		fullstring += '"plugins": [{';
		$mab(".inputPlugins").each(function() {
			var id = this.id;
			var name = this.name;
			var value = this.value;
			var type = this.type;
			var friendly = $mab(this).data('friendly');
			fullstring += '"' + this.name + '":"' + this.value + '",';
		});
		fullstring = fullstring.slice(0, -1);
		fullstring += '}]}';
		//$mab('#jsontext').val(fullstring);
		var id = $mab(this).data('id');
		$mab.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'mab_ajax_save_homepages',
				id: id,
				edit: '1',
				post_title: post__title,
				post_nonce: nonce,
				post_id: post__id,
				arrayjs: fullstring
			},
			success: function(data, textStatus, XMLHttpRequest) {
				hasChanged = false;
				$mab.growl.notice({
					title: fepajaxhandler.success,
					message: fepajaxhandler.successmessage
				});
				$mab('#mablist').refreshtabs();
				var iframe = document.getElementById('iphonedemo');
				iframe.src = iframe.src;
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
		return false;
	});
	//! Open modal to add NEW PAGE
	$mab('.modsupp').click(function(event) {
		event.preventDefault();
		$mab('#dialog-message').dialog('open');
	});
	$mab('#dialog-message').dialog({
		autoOpen: false,
		//FALSE if you open the dialog with, for example, a button click
		title: 'Add new page',
		modal: false,
		width: 600,
		height: 450,
		buttons: {
			Cancel: function() {
				$mab(this).dialog("close");
			}
		},
		close: function() {}
	});
	//! Change page ICON 
	$mab('body').on('click', '.appicons', function(event) {
		// do something
		event.preventDefault();
		var app = $mab(this).attr("id");
		$mab("#previewIcon").removeClass();
		$mab("#previewIcon").addClass('fa fa-' + app + '  fa-3x');
		$mab("#pageicon").val(app);
		$mab('#dialog-message-fonticons').dialog("close");
		//alert(app);
	});
	//! Open modal for change CHANGE ICON
	$mab('.mabitemImage').click(function(event) {
		event.preventDefault();
		$mab("#fonticons").load(fepajaxhandler.fonturl);
		$mab('#dialog-message-fonticons').dialog('open');
	});
	$mab('#dialog-message-fonticons').dialog({
		autoOpen: false,
		//FALSE if you open the dialog with, for example, a button click
		title: 'Change icon',
		modal: false,
		width: 600,
		height: 450,
		buttons: {
			Cancel: function() {
				$mab(this).dialog("close");
			}
		},
		close: function() {}
	});
	//! SORT icons  
	$mab('#mablist').disableSelection().sortable({
		scroll: true,
		placeholder: 'placeholder',
		// containment:'mablist',
		axis: 'x',
		helper: 'clone',
		revert: true,
		stop: function(event, ui) {
			var data = $mab(this).sortable('serialize');
			var nonce = $mab("#fepnonce").val();
			var sorted = data;
			$mab.growl.notice({
				title: fepajaxhandler.success,
				message: fepajaxhandler.successmessage
			});
			$mab.ajax({
				type: 'POST',
				url: fepajaxhandler.ajaxurl,
				data: {
					action: 'mab_ajax_sort_tabs',
					post_nonce: nonce,
					sortedlist: sorted
				},
				success: function(data, textStatus, XMLHttpRequest) {
					hasChanged = false;
					$mab.growl.notice({
						title: fepajaxhandler.success,
						message: fepajaxhandler.successmessage
					});
					//$mab('#mablist').refreshtabs();							
					var iframe = document.getElementById('iphonedemo');
					iframe.src = iframe.src;
				},
				error: function(MLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		}
	});
/*
TABS	
	
*/
	$mab('ul.mabtabs').each(function() {
		// For each set of tabs, we want to keep track of
		// which tab is active and its associated content
		var $mabactive, $mabcontent, $mablinks = $mab(this).find('a');
		// If the location.hash matches one of the links, use that as the active tab.
		// If no match is found, use the first link as the initial active tab.
		$mabactive = $mab($mablinks.filter('[href="' + location.hash + '"]')[0] || $mablinks[0]);
		$mabactive.addClass('active');
		$mabcontent = $mab($mabactive[0].hash);
		// Hide the remaining content
		$mablinks.not($mabactive).each(function() {
			$mab(this.hash).hide();
		});
		// Bind the click event handler
		$mab(this).on('click', 'a', function(e) {
			// Make the old tab inactive.
			$mabactive.removeClass('active');
			$mabcontent.hide();
			// Update the variables with the new link and content
			$mabactive = $mab(this);
			$mabcontent = $mab(this.hash);
			// Make the tab active.
			$mabactive.addClass('active');
			$mabcontent.show();
			// Prevent the anchor's default click action
			e.preventDefault();
		});
	});



$mab('.mab_login_btn').click(function(e){
    e.preventDefault();
    var uname = $mab('form input[name="mab_user_login"]').val();
    var pwd   = $mab('form input[name="mab_user_password"]').val();

    if (uname.length == 0 ){
        $mab('form input[name="mab_user_login"]').addClass('input-error');
    }else{
        $mab('form input[name="mab_user_login"]').removeClass('input-error');
    }
    if (pwd.length == 0 ){
        $mab('form input[name="mab_user_password"]').addClass('input-error');
    }else{
        $mab('form input[name="mab_user_password"]').removeClass('input-error');
    }

    if (uname.length == 0 || pwd.length == 0){
        return false;
    }else{
	   
        mab_user_loginFrm();       
    }
});

$mab('.mab_reg_btn').click(function(e){
    e.preventDefault();
    mab_user_registerFrm();
});


$mab(document).on( 'click', '.my-mab-notice', function() {

    jQuery.ajax({
        url: fepajaxhandler.ajaxurl,
        data: {
            action: 'my_dismiss_mab_notice'
        }
    })

});
});

function mab_user_loginFrm(){
	 
	$mab('#mab_loader_login').show();
    $mab.ajax({
        type: 'POST',
        dataType: 'json',
        url: fepajaxhandler.ajaxurl,
        data: { 
            'action': 'mab_custom_ajax_login', //calls wp_ajax_nopriv_ajaxlogin
            'post_nonce'  :  $mab("#fepnonce").val(),
            'username'  : $mab('form input[name="mab_user_login"]').val(),
            'password'  : $mab('form input[name="mab_user_password"]').val(),
            'rememberme': $mab('form input[name="mab_rememberme"]').val()},            
        success: function(data){
	       
        	$mab('#mab_loader').hide();
        	if (data.loggedin == true){
        		$mab('form#mab_login_form .mab_response_msg').show();
                $mab('form#mab_login_form .mab_response_msg .alert').addClass('alert-success');
                $mab('form#mab_login_form .mab_response_msg .alert').text(data.message);
        		$mab('form#mab_login_form .mab_response_msg').delay(1000).fadeOut();
                location.reload();
            }else{
	            
                $mab('.mab_response_msg_login').show();
                $mab('.mab_response_msg_login .alert').addClass('alert-error');
                $mab('.mab_response_msg_login .alert').text(data.message);
                $mab('.mab_response_msg_login').delay(1000).fadeOut();
			}
        }
    });
	return false;
}

function mab_user_registerFrm(){
    $mab('#mab_loader').show();
    $mab.ajax({
        type: 'POST',
        dataType: 'json',
        url: fepajaxhandler.ajaxurl,
        data: { 
            'action'       : 'mab_custom_ajax_registration',
            'post_nonce'  :  $mab("#fepnonce").val(),
            'reg_uname'    : $mab('form input[name="reg_uname"]').val(),
            'reg_email'    : $mab('form input[name="reg_email"]').val(), 
            'reg_password' : $mab('form input[name="reg_password"]').val(),
            'reg_website'  : $mab('form input[name="reg_website"]').val(),
            'reg_fname'    : $mab('form input[name="reg_fname"]').val(),
            'reg_lname'    : $mab('form input[name="reg_lname"]').val(),
            'reg_nickname' : $mab('form input[name="reg_nickname"]').val(),
            'reg_bio'      : $mab('form textarea[name="reg_bio"]').val()            
        },
        success: function(data){
            $mab('#mab_loader').hide();
            if (data.loggedin == true){
                $mab('form#mab_register_form .mab_response_msg').show();
                $mab('form#mab_register_form .mab_response_msg .alert').addClass('alert-success');
                $mab('form#mab_register_form .mab_response_msg .alert').text(data.message);
                $mab('form#mab_register_form .mab_response_msg').delay(5000).fadeOut();  
                 location.reload();              
            }else{
                $mab('form#mab_register_form .mab_response_msg').show();
                $mab('form#mab_register_form .mab_response_msg .alert').addClass('alert-error');
                $mab('form#mab_register_form .mab_response_msg .alert').text(data.message);
                $mab('form#mab_register_form .mab_response_msg').delay(5000).fadeOut();
            }
        }
    });
    return false;
}


