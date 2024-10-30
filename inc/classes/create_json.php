<?php

class MABClass_json  {
	
	 /*--  JSON RETURN --*/
 public function __construct() {
		
	}
   
/*--  JSON RETURN --*/
function MAB_produce_my_json() {

  if (!empty($_GET['fb_json']) && !empty($_GET['term']) ) {
   
    	
  	$fbquery = $_GET['term'];   		 
    $response = wp_remote_get( 'http://app-developers.eu/fb/json.php?term=' . $fbquery,true );
    //$encoded=json_encode($jsonpost);
    header( 'Content-Type: application/json' );
    echo $response['body'];
    exit;
  }
}

/*--  /JSON RETURN-- */
}