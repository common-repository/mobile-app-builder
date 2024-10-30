<?php
function MAB_preview_app()
{
	if(!empty($_GET['mab_preview_app']) && isset($_GET['mab_preview_app'])):

		$iframeurl = str_replace('mab_preview_app=1','', $_SERVER['REQUEST_URI']);
?>
	<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php _e('Download Our App', 'mobile-app-builder'); ?></title>
        <meta name="description" content="">

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">

        <link rel="stylesheet" href="<?php echo MAB_DIR_URL; ?>static/download/css/normalize.min.css">
        <link rel="stylesheet" href="<?php echo MAB_DIR_URL; ?>static/download/css/main.css">
    </head>
    <body>
    <div id="wrap">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->




		<!-- START HEADER  -->
        <div class="header-container" id="masthead">
            <header class="wrapper clearfix">

                <!-- LOGO Replace // Replace Text with your App name for SEO, text is replaced with logo.png file  -->


                <!-- START HEADER TEXT  -->
                <h3><?php _e('App simulator', 'mobile-app-builder'); ?> </h3>



                <!-- END HEADER -->
            </header>
        </div>



		<!-- START FEATURES  -->
        <div class="main-container">
            <div class="main wrapper clearfix">

            	<!-- START IPHONE SLIDER -->
            	<div class="fader">
            		<div class="flexslider1">

            		 <iframe src="<?php echo $iframeurl?>" scrolling="yes" width="320" height="480" style="width: 320px;
hieght: 480px;" id="iphoneframe" name="iphoneframe">
        </iframe>
            		</div>
            	</div>



                <!-- INSERT FEATURE TEXT  -->
                <div class="fourth">
                	<article>

                    </article>


                <!-- INSERT FEATURE TEXT  -->

	            </div>

                <!-- INSERT FEATURE TEXT  -->
                <div class="fourth right last">
                    <article>
                    	<h2><?php _e('Download your app soon on', 'mobile-app-builder'); ?> </h2>

                    	<!-- INSERT STORES  -->
                    	<ul class="clearfix stores">
                    		<li><img src="<?php echo MAB_DIR_URL; ?>static/download/img/apple.png" alt="Download App" /></li>
                    		<li><img src="<?php echo MAB_DIR_URL; ?>static/download/img/android.png" alt="Download App" /></li>
                    	</ul>


                    </article>


                <!-- INSERT FEATURE TEXT  -->

                </div>


				<!-- END MAIN -->
            </div>
        </div>



        <!-- START QUOTE CONATINER -->
 		<div class="quote-container">
 			<div class="lipup"></div>
     		<div class="main wrapper clearfix">
        		<div class="flexslider2 quote">
        		  <br><br>
        		</div>

			<!-- END MAIN -->
    		</div>
		</div>




		<!-- START FOOTER  -->
        <div class="footer-container">
        	<div class="lipup"></div>
            <footer class="wrapper clearfix">

            <!-- END FOOTER -->
            </footer>
        </div>
        </div>




		<!-- LOAD SCRIPTS -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo MAB_DIR_URL; ?>static/download/js/vendor/jquery-1.8.0.min.js"><\/script>')</script>
        <script defer src="<?php echo MAB_DIR_URL; ?>static/download/js/jquery.flexslider-min.js"></script>
        <script defer src="<?php echo MAB_DIR_URL; ?>static/download/js/backstretch.js"></script>
		<script src="<?php echo MAB_DIR_URL; ?>static/download/js/main.js"></script>


		<script >
		$(window).load(function() {

    // Back Stretch
    $("#masthead").backstretch("<?php echo MAB_DIR_URL; ?>static/download/img/bg_1.jpg");


  });

		</script>
    </body>
</html>

	<?php


	exit;

	endif;
}
add_action('init', 'MAB_preview_app');

?>