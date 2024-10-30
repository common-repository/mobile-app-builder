 /* START */
jQuery(document).ready(function($) {
jQuery(document).on( 'click', '.my-mab-notice .notice-dismiss', function() {
alert('ok');
    jQuery.ajax({
        url: fepajaxhandler.ajaxurl,
        data: {
            action: 'my_dismiss_mab_notice'
        }
    });

});
});


