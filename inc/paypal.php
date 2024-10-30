<?php


define('MAB_PAYPAL_PAYMENT_ACCEPT_PLUGIN_VERSION', '4.9.2');
define('MAB_PAYPAL_PAYMENT_ACCEPT_PLUGIN_URL', plugins_url('', __FILE__));

include_once('mab_app_admin_menu.php');
include_once('mab_app_paypal_utility.php');

function mab_pp_plugin_install() {
	// Some default options
	add_option('mab_pp_payment_email', get_bloginfo('admin_email'));
	add_option('mab_mab_paypal_payment_currency', 'USD');
	add_option('mab_pp_payment_subject', 'Plugin Service Payment');
	add_option('mab_pp_payment_item1', 'Basic Service - $10');
	add_option('mab_pp_payment_value1', '10');
	add_option('mab_pp_payment_item2', 'Gold Service - $20');
	add_option('mab_pp_payment_value2', '20');
	add_option('mab_pp_payment_item3', 'Platinum Service - $30');
	add_option('mab_pp_payment_value3', '30');
	add_option('mab_paypal_page_id', 'Paypal Payment');
	add_option('payment_button_type', 'https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif');
	add_option('mab_pp_show_other_amount', '-1');
	add_option('mab_pp_show_ref_box', '1');
	add_option('mab_pp_ref_title', 'Your Email Address');
	add_option('mab_pp_return_url', home_url());
}

register_activation_hook(__FILE__, 'mab_pp_plugin_install');

add_shortcode('MAB_PAYPAL_payment_box_for_any_amount', 'mab_app_buy_now_any_amt_handler');

function mab_app_buy_now_any_amt_handler($args) {
	$output = wppp_render_paypal_button_with_other_amt($args);
	return $output;
}

add_shortcode('MAB_PAYPAL_payment_box', 'mab_app_buy_now_button_shortcode');

function mab_app_buy_now_button_shortcode($args) {
	ob_start();
	wppp_render_paypal_button_form($args);
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function Paypal_payment_accept() {
	$paypal_email = get_option('mab_pp_payment_email');
	$payment_currency = get_option('mab_paypal_payment_currency');
	$paypal_subject = get_option('mab_pp_payment_subject');

	$itemName1 = get_option('mab_pp_payment_item1');
	$value1 = get_option('mab_pp_payment_value1');
	$itemName2 = get_option('mab_pp_payment_item2');
	$value2 = get_option('mab_pp_payment_value2');
	$itemName3 = get_option('mab_pp_payment_item3');
	$value3 = get_option('mab_pp_payment_value3');
	$itemName4 = get_option('mab_pp_payment_item4');
	$value4 = get_option('mab_pp_payment_value4');
	$itemName5 = get_option('mab_pp_payment_app_setup_item');
	$value5 = get_option('mab_pp_payment_app_setup_value');
	$itemName6 = get_option('mab_pp_payment_item6');
	$value6 = get_option('mab_pp_payment_value6');
	$payment_button = get_option('payment_button_type');
	$mab_pp_show_other_amount = get_option('mab_pp_show_other_amount');
	$mab_pp_show_ref_box = get_option('mab_pp_show_ref_box');
	$mab_pp_ref_title = get_option('mab_pp_ref_title');
	$mab_pp_return_url = get_option('mab_pp_return_url');

	/* === Paypal form === */
	$output = '';
	if(!isset($_GET['id'])){$_GET['id'] = 0;}
	$app_name = get_the_title( $_GET['id'] );
	$output .= '<div id="accept_paypal_payment_form">';
	$output .= '<h3 id="payment_title">'.__('Creation of app','mobile-app-builder').' - ' . $app_name .'</h3>';
	$output .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="wp_accept_pp_button_form_classic">';
	$output .= '<input type="hidden" name="cmd" value="_xclick-subscriptions">';
	$output .= '<input type="hidden" name="app_id" value="'.esc_attr($_GET['id']).'" />';
	$output .= '<input type="hidden" name="business" value="'.esc_attr($paypal_email).'" />';
	$output .= '<input type="hidden" name="item_name" value="'.esc_attr($app_name).' - '.esc_attr($_GET['id']).'" />';
	$output .= '<input type="hidden" name="currency_code" value="'.esc_attr($payment_currency).'" />';
	$output .= '<div class="mab_app_payment_subject"><span class="payment_subject"><strong>'.esc_attr($paypal_subject).'</strong></span></div>';


	/*
	    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="business" value="chicgeek.eu@gmail.Com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="a1" value="500.00">
<input type="hidden" name="p1" value="1">
<input type="hidden" name="t1" value="M">
<input type="hidden" name="src" value="1">
<input type="hidden" name="a3" value="2.00">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/fr_XC/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
<img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
</form>




	    $output .= '<input type="hidden" name="a3" value="<?php echo $cycle_amount; ?>">';
		$output .= '<input type="hidden" name="p3" value="1">';
		$output .= '<input type="hidden" name="t3" value="<?php echo $cycle; ?>">';
    */
	$output .= '<input type="hidden" name="t3" value="M"><input type="hidden" name="p3" value="1">';

	$output .= '<select id="amount" name="a3" class="">';
	$output .= '<option value="'.esc_attr($value1).'">'.esc_attr($itemName1).'</option>';
	if (!empty($value2)) {
		$output .= '<option value="'.esc_attr($value2).'">'.esc_attr($itemName2).'</option>';
	}
	if (!empty($value3)) {
		$output .= '<option value="'.esc_attr($value3).'">'.esc_attr($itemName3).'</option>';
	}
	if (!empty($value4)) {
		$output .= '<option value="'.esc_attr($value4).'">'.esc_attr($itemName4).'</option>';
	}


	$output .= '</select>';


	if (!empty($value5)) {
		$output .= '<h4>'.esc_attr($itemName5).' '.__('fee','mobile-app-builder').' '.$value5.' '.$payment_currency.'</h4><input value="'.esc_attr($value5).'"  type="hidden" name="a1">
        <input type="hidden" name="no_note" value="1">
<input type="hidden" name="p1" value="1">
<input type="hidden" name="t1" value="M">

        ';
	}
	// Show other amount text box
	if ($mab_pp_show_other_amount == '1') {
		$output .= '<div class="mab_app_other_amount_label"><strong>Other Amount:</strong></div>';
		$output .= '<div class="mab_app_other_amount_input"><input type="number" min="1" step="any" name="other_amount" title="Other Amount" value="" class="mab_app_other_amt_input" style="max-width:80px;" /></div>';
	}

	// Show the reference text box
	if ($mab_pp_show_ref_box == '1') {
		$output .= '<div class="mab_app_ref_title_label"><strong>'.esc_attr($mab_pp_ref_title).':</strong></div>';
		$output .= '<input type="hidden" name="on0" value="'.apply_filters('mab_pp_button_reference_name','Reference').'" />';
		$output .= '<div class="mab_app_ref_value"><input type="text" name="os0" maxlength="60" value="'.apply_filters('mab_pp_button_reference_value','').'" class="mab_pp_button_reference" /></div>';
	}

	$output .= '<input type="hidden" name="no_shipping" value="0" /><input type="hidden" name="no_note" value="1" /><input type="hidden" name="bn" value="TipsandTricks_SP" />';

	if (!empty($mab_pp_return_url)) {
		$output .= '<input type="hidden" name="return" value="' . esc_url($mab_pp_return_url) . '" />';
	} else {
		$output .='<input type="hidden" name="return" value="' . home_url() . '" />';
	}

	$output .= '<div class="mab_app_payment_button">';
	$output .= '<input type="image" src="'.esc_url($payment_button).'" name="submit" alt="Make payments with payPal - it\'s fast, free and secure!" />';
	$output .= '</div>';

	$output .= '</form>';
	$output .= '</div>';
	$output .= <<<EOT
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.wp_accept_pp_button_form_classic').submit(function(e){
        var form_obj = $(this);
        var other_amt = form_obj.find('input[name=other_amount]').val();
        if (!isNaN(other_amt) && other_amt.length > 0){
            options_val = other_amt;
            //insert the amount field in the form with the custom amount
            $('<input>').attr({
                type: 'hidden',
                id: 'amount',
                name: 'amount',
                value: options_val
            }).appendTo(form_obj);
        }
        return;
    });
});
</script>
EOT;
	/* = end of paypal form = */
	return $output;
}

function wp_ppp_process($content) {
	if (strpos($content, "<!-- MAB_PAYPAL_payment -->") !== FALSE) {
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$content = str_replace('<!-- MAB_PAYPAL_payment -->', Paypal_payment_accept(), $content);
	}
	return $content;
}

function show_MAB_PAYPAL_payment_widget($args) {
	extract($args);

	$MAB_PAYPAL_payment_widget_title_name_value = get_option('MAB_PAYPAL_widget_title_name');
	echo $before_widget;
	echo $before_title . $MAB_PAYPAL_payment_widget_title_name_value . $after_title;
	echo Paypal_payment_accept();
	echo $after_widget;
}

function MAB_PAYPAL_payment_widget_control() {
?>
    <p>
    <?php _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
    <?php
}

function MAB_PAYPAL_payment_init() {
	wp_register_style('wpapp-styles', MAB_PAYPAL_PAYMENT_ACCEPT_PLUGIN_URL . '/wpapp-styles.css');
	wp_enqueue_style('wpapp-styles');

	//Widget code
	$widget_options = array('classname' => 'widget_MAB_PAYPAL_payment', 'description' => __("Display WP Paypal Payment."));
	wp_register_sidebar_widget('MAB_PAYPAL_payment_widgets', __('WP Paypal Payment'), 'show_MAB_PAYPAL_payment_widget', $widget_options);
	wp_register_widget_control('MAB_PAYPAL_payment_widgets', __('WP Paypal Payment'), 'MAB_PAYPAL_payment_widget_control');

	//Listen for IPN and validate it
	if (isset($_REQUEST['mab_app_paypal_ipn']) && $_REQUEST['mab_app_paypal_ipn'] == "process") {
		mab_app_validate_paypl_ipn();
		exit;
	}
}

function mab_app_shortcode_plugin_enqueue_jquery() {
	wp_enqueue_script('jquery');
}

add_filter('the_content', 'wp_ppp_process');
add_shortcode('MAB_PAYPAL_payment', 'Paypal_payment_accept');
if (!is_admin()) {
	add_filter('widget_text', 'do_shortcode');
}

add_action('init', 'mab_app_shortcode_plugin_enqueue_jquery');
add_action('init', 'MAB_PAYPAL_payment_init');
