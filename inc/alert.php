<?php
	
if(null == get_option( 'my-mab-notice-installed' )  ) {
  add_action( 'admin_notices', 'my_mab_admin_notice' );
}

function my_mab_admin_notice() {
		$mab_admin_url = admin_url('admin.php?page=mab_settings&mabinstall=1'); 
  
  if($_GET['mabinstall'] == 1){
	  ?>
	  
	  <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Done! The install has completed with success. Thank you!', 'sample-text-domain' ); ?></p>
    </div>
	  <?php
  }else{
    ?>
    <div class="notice error my-mab-notice is-dismissible" >
        <p><b><?php _e( '[MAB] Mobile App Builder', 'mobile-app-builder' ); ?></b> <?php _e( 'You will need to install some default information to get you started. Just click the install button and we will take care of the rest :). If you are upgrading and you already installed please dismiss this.', 'mobile-app-builder' ); ?><br><center><a href="<?php echo $mab_admin_url; ?>" class="button-primary" style=""><?php _e('One-Click Install', 'mobile-app-builder' )?></a></center></p>
    </div>


    <?php
}
}


add_action( 'admin_init', 'my_detect_mab_dismissed' );
function my_detect_mab_dismissed() {
	//delete_option( 'my-mab-notice-dismissed' );
	//delete_option( 'my-mab-notice-installed' );
}
?>