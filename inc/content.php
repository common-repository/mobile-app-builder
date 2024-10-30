<?php

add_filter('the_content', 'mab_my_content');
function mab_my_content( $content )
{
	$post_id = get_the_ID();
	$json = get_post_meta($post_id, '_json', true);
	$jsonData = json_decode($json,true);

	if(!isset($mapwidth)){
		$mapwidth ='100';
	}

	/* post id for default theme */
	$json = get_post_meta($post_id, '_json', true);

	$jsondecode = json_decode($json,true);

	if(isset($jsondecode['theme'])){

		$jsontheme = get_post_meta( $jsondecode['theme'],'_json',true );
		$jsontheme = json_decode($jsontheme,true);
		//print_r($jsontheme);



		foreach ($jsontheme as $key) {

			if(!isset($jsonData['plugins'][0][$key[0]['name']])){ $jsonData['plugins'][0][$key[0]['name']] = '';}

			if($key[0]['name'] == 'htmlelement'){
				$content = str_replace('{'.$key[0]['name'].'}', base64_decode($jsonData['plugins'][0][$key[0]['name']]), $content);

			}else{
				$content = str_replace('{'.$key[0]['name'].'}', $jsonData['plugins'][0][$key[0]['name']], $content);

			}

		}
	}else if(isset($jsondecode['plugins'])){




			foreach ($jsondecode['plugins'][0] as $key => $value) {

				if(!isset($key)){ $key = '';}
				if(!isset($value)){ $value = '';}

				if($key == 'htmlelement'){
					$value= base64_decode($value);
				}
				$content = str_replace('{'.$key.'}', $value, $content);
			}
		}
	/*

		{"name":"youtube","icon":"youtube","plugin":"103","_order":"100","_ID":"110","plugins": [{"y":"","y":"","youtubeuser":"AppDevelopers","youtubemaxitems":"10","youtubetitle":"MobileApps"}]}

	*/

	return $content;
}

?>