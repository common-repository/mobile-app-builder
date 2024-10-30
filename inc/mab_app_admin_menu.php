<?php

// Displays PayPal Payment Accept Options menu

function mab_paypal_payment_options_page() {

	if(!current_user_can('manage_options')){
		wp_die('You do not have permission to access this settings page.');
	}

	if (isset($_POST['info_update'])) {
		$nonce = $_REQUEST['_wpnonce'];
		if ( !wp_verify_nonce($nonce, 'wp_accept_pp_payment_settings_update')){
			wp_die('Error! Nonce Security Check Failed! Go back to settings menu and save the settings again.');
		}

		$value1 = filter_input(INPUT_POST, 'mab_pp_payment_value1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$value2 = filter_input(INPUT_POST, 'mab_pp_payment_value2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$value3 = filter_input(INPUT_POST, 'mab_pp_payment_value3', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$value4 = filter_input(INPUT_POST, 'mab_pp_payment_value4', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$value5 = filter_input(INPUT_POST, 'mab_pp_payment_app_setup_value', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$value6 = filter_input(INPUT_POST, 'mab_pp_payment_value6', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

		update_option('mab_paypal_page_id', sanitize_text_field($_POST["mab_paypal_page_id"]));
		update_option('mab_edit_page_id', sanitize_text_field($_POST["mab_edit_page_id"]));
		update_option('mab_pp_payment_email', sanitize_email($_POST["mab_pp_payment_email"]));
		update_option('mab_paypal_payment_currency', sanitize_text_field($_POST["mab_paypal_payment_currency"]));
		update_option('mab_pp_payment_subject', sanitize_text_field($_POST["mab_pp_payment_subject"]));
		update_option('mab_pp_payment_item1', sanitize_text_field($_POST["mab_pp_payment_item1"]));
		update_option('mab_pp_payment_value1', $value1);
		update_option('mab_pp_payment_item2', sanitize_text_field($_POST["mab_pp_payment_item2"]));
		update_option('mab_pp_payment_value2', $value2);
		update_option('mab_pp_payment_item3', sanitize_text_field($_POST["mab_pp_payment_item3"]));
		update_option('mab_pp_payment_value3', $value3);
		update_option('mab_pp_payment_item4', sanitize_text_field($_POST["mab_pp_payment_item4"]));
		update_option('mab_pp_payment_value4', $value4);
		update_option('mab_pp_payment_app_setup_item', sanitize_text_field($_POST["mab_pp_payment_app_setup_item"]));
		update_option('mab_pp_payment_app_setup_value', $value5);
		update_option('mab_pp_payment_item6', sanitize_text_field($_POST["mab_pp_payment_item6"]));
		update_option('mab_pp_payment_value6', $value6);
		update_option('payment_button_type', sanitize_text_field($_POST["payment_button_type"]));
		update_option('mab_pp_show_other_amount', ($_POST['mab_pp_show_other_amount'] == '1') ? '1' : '-1' );
		update_option('mab_pp_show_ref_box', ($_POST['mab_pp_show_ref_box'] == '1') ? '1' : '-1' );
		update_option('mab_pp_ref_title', sanitize_text_field($_POST["mab_pp_ref_title"]));
		update_option('mab_pp_return_url', esc_url_raw(sanitize_text_field($_POST["mab_pp_return_url"])));

		echo '<div id="message" class="updated fade"><p><strong>';
		echo 'Options Updated!';
		echo '</strong></p></div>';
	}

	$mab_paypal_payment_currency = stripslashes(get_option('mab_paypal_payment_currency'));
	$payment_button_type = stripslashes(get_option('payment_button_type'));
?>

    <div class=wrap>
    <div id="poststuff"><div id="post-body">

        <h2> <?php _e('Accept PayPal Payments Settings','mobile-app-builder'); ?></h2>



        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <?php wp_nonce_field('wp_accept_pp_payment_settings_update'); ?>

            <input type="hidden" name="info_update" id="info_update" value="true" />

            <div class="postbox">
            <h3 class="hndle"><label for="title"><?php _e('PayPal Settings','mobile-app-builder'); ?></label></h3>


            <div class="postbox">
            <div class="inside">

                <table class="form-table">

                    <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('Payment Page ID','mobile-app-builder'); ?>:</strong>
                        </td><td align="left">
                            <input name="mab_paypal_page_id" type="text" size="30" value="<?php echo esc_attr(get_option('mab_paypal_page_id')); ?>"/>
                            <br /><i><?php _e('You can find this by editing the page and looking in the url bar','mobile-app-builder'); ?></i><br />
                        </td></tr>

                         <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('App Builder Page','mobile-app-builder'); ?>:</strong>
                        </td><td align="left">
                            <input name="mab_edit_page_id" type="text" size="30" value="<?php echo esc_attr(get_option('mab_edit_page_id')); ?>"/>
                            <br /><i><?php _e('You can find this by editing the page and looking in the url bar','mobile-app-builder'); ?></i><br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong>Paypal Email address:</strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_email" type="text" size="35" value="<?php echo esc_attr(get_option('mab_pp_payment_email')); ?>"/>
                            <br /><i>This is the Paypal Email address where the payments will go</i><br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong>Choose Payment Currency: </strong>
                        </td><td align="left">
                            <select id="mab_paypal_payment_currency" name="mab_paypal_payment_currency">
    <?php _e('<option value="USD"') ?><?php if ($mab_paypal_payment_currency == "USD") echo " selected " ?><?php _e('>US Dollar</option>') ?>
    <?php _e('<option value="GBP"') ?><?php if ($mab_paypal_payment_currency == "GBP") echo " selected " ?><?php _e('>Pound Sterling</option>') ?>
    <?php _e('<option value="EUR"') ?><?php if ($mab_paypal_payment_currency == "EUR") echo " selected " ?><?php _e('>Euro</option>') ?>
    <?php _e('<option value="AUD"') ?><?php if ($mab_paypal_payment_currency == "AUD") echo " selected " ?><?php _e('>Australian Dollar</option>') ?>
    <?php _e('<option value="CAD"') ?><?php if ($mab_paypal_payment_currency == "CAD") echo " selected " ?><?php _e('>Canadian Dollar</option>') ?>
    <?php _e('<option value="NZD"') ?><?php if ($mab_paypal_payment_currency == "NZD") echo " selected " ?><?php _e('>New Zealand Dollar</option>') ?>
    <?php _e('<option value="HKD"') ?><?php if ($mab_paypal_payment_currency == "HKD") echo " selected " ?><?php _e('>Hong Kong Dollar</option>') ?>
                            </select>
                            <br /><i>This is the currency for your visitors to make Payments.</i><br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong>Payment Subject:</strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_subject" type="text" size="35" value="<?php echo esc_attr(get_option('mab_pp_payment_subject')); ?>"/>
                            <br /><i>Enter the Product or service name or the reason for the payment here. The visitors will see this text</i><br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('Package #1','mobile-app-builder'); ?>:</strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_item1" type="text" size="25" value="<?php echo esc_attr(get_option('mab_pp_payment_item1')); ?>"/>
                            <strong>Price :</strong>
                            <input name="mab_pp_payment_value1" type="text" size="10" value="<?php echo esc_attr(get_option('mab_pp_payment_value1')); ?>"/>
                            <br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('Package #2','mobile-app-builder'); ?>:</strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_item2" type="text" size="25" value="<?php echo esc_attr(get_option('mab_pp_payment_item2')); ?>"/>
                            <strong>Price :</strong>
                            <input name="mab_pp_payment_value2" type="text" size="10" value="<?php echo esc_attr(get_option('mab_pp_payment_value2')); ?>"/>
                            <br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('Package #3','mobile-app-builder'); ?>:</strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_item3" type="text" size="25" value="<?php echo esc_attr(get_option('mab_pp_payment_item3')); ?>"/>
                            <strong>Price :</strong>
                            <input name="mab_pp_payment_value3" type="text" size="10" value="<?php echo esc_attr(get_option('mab_pp_payment_value3')); ?>"/>
                            <br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('Package #4','mobile-app-builder'); ?>:</strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_item4" type="text" size="25" value="<?php echo esc_attr(get_option('mab_pp_payment_item4')); ?>"/>
                            <strong>Price :</strong>
                            <input name="mab_pp_payment_value4" type="text" size="10" value="<?php echo esc_attr(get_option('mab_pp_payment_value4')); ?>"/>
                            <br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">
                            <strong><?php _e('Setup fee','mobile-app-builder'); ?></strong>
                        </td><td align="left">
                            <input name="mab_pp_payment_app_setup_item" type="text" size="25" value="<?php echo esc_attr(get_option('mab_pp_payment_app_setup_item')); ?>"/>
                            <strong>Price :</strong>
                            <input name="mab_pp_payment_app_setup_value" type="text" size="10" value="<?php echo esc_attr(get_option('mab_pp_payment_app_setup_value')); ?>"/>
                            <br />
                        </td></tr>

                    <tr valign="top"><td width="25%" align="left">

                        </td><td align="left">


                            <br /><i><?php _e('For example Bronze $12/mo, Silver $29/mo, Gold $39/mo + $199 setup fee.','mobile-app-builder');?></i>
                        </td></tr>





                    <tr valign="top"><td width="25%" align="left">
                            <strong>Return URL from PayPal:</strong>
                        </td><td align="left">
                            <input name="mab_pp_return_url" type="text" size="60" value="<?php echo esc_url(get_option('mab_pp_return_url')); ?>"/>
                            <br /><i>Enter a return URL (could be a Thank You page). PayPal will redirect visitors to this page after Payment.</i><br />
                        </td></tr>

                </table>

                <br /><br />

                <table style="width:50%;display:none; border-spacing:0; padding:0; text-align:center;">
                    <tr>
                        <td>
  <input type="radio" name="payment_button_type" value="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" checked />
                        </td>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td><img border="0" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" alt="" /></td>
                        <td></td>
                    </tr>
                </table>

            </div></div><!-- end of postbox -->

            <div class="submit">
                <center><input type="submit" class="button-primary" name="info_update" value="<?php _e('Update options'); ?> &raquo;" /></center>
            </div>
            
            
        </form>



    </div>
    
      <a class="button-primary" style="    background: #9a0404;border-color: #9a0404 #9a0404 #9a0404;" href="?page=mab_settings&mabinstall=1">Reset MAB</a></div>
    </div> <!-- end of .poststuff and post-body -->
    </div><!-- end of .wrap -->
    <div id="post-body">
  
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
    <?php
}
