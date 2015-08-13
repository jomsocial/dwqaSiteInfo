<?php

/**

 * Plugin Name: DWQA Site info

 * Plugin URI: http://peepso.com

 * Description: Users who want to post a question on a DWQA forum will need to enter their website information such as domain, login info, and FTP. 

 				No more back and forth messages to get all the information you need!

 * Version: 1.0

 * Author: peepso.com

 * Author URI: peepso.com

 * Text Domain: dwqa_site_info

 * License: 

 */

 

defined('ABSPATH') or die("No script kiddies please!");
function dwqa_siteinfo_css(){

	wp_register_style( 'siteinfo', plugin_dir_url(__FILE__)."helpers/siteinfo.css" );

	wp_enqueue_style( 'siteinfo' );	

}
function dwqa_siteinfo_js() {

	?>

	<script type="text/javascript">

		var NotesEdited 	= "Note added";

		var NotesNotEdited 	= "Note not added";

	</script>

    <?php

	wp_enqueue_script( 'dwqa-site-info', plugins_url() . '/dwqa_site_info/helpers/sinfoEdit.js', 'jquery', '', true );

	wp_localize_script( 'dwqa-site-info', 'sinfoEdit', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		

}
function dwqa_get_site_info($post_id){

	global $wpdb;	

	if(!$post_id){

		return false;

	}

	

	$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "site_info WHERE post_id = %d LIMIT 1", $post_id ) );

	if ( is_object( $row ) ){

		$return = $row->info;

		return $return;

	}else{

		return false;

	}

}
function dwqa_get_user_notes($user_id){

	global $wpdb;	
	if(!$user_id){

		return false;

	}
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "user_notes WHERE user_id = %d LIMIT 1", $user_id ) );

	if ( is_object( $row ) ){

		return $row;

	}else{

		return false;

	}

}
function dwqa_show_site_info(){

	global $post;

	$post_id 	= $post->ID;

	$user 		= wp_get_current_user();
	if(!isset($user->caps['administrator']) || $user->caps['administrator'] != 1){

		return false;

	}

	

	$sinfo = dwqa_get_site_info($post_id);

	//if($sinfo === false || empty($sinfo)){

	//	return false;

	//}

	

	$decoded_sinfo 	= json_decode($sinfo);

	

	$user_notes			= dwqa_get_user_notes($post->post_author);

	$user_notes_final	= isset($user_notes->notes) ? $user_notes->notes : '';

	

	include plugin_dir_path(__FILE__)."/template/details.php";

}
function dwqa_save_site_info($post_id){

	global $wpdb;
    if( is_user_logged_in() ) {

        $user_id = get_current_user_id();

    } elseif( dwqa_current_user_can('post_question') ) {

        $user_id = 0;

    } else {

        return false;

    }

	

	$sinfo 					= array();

	$sinfo['domain'] 		= esc_html( $_POST['sinfo_domain'] );

	$sinfo['site_url'] 		= esc_html( $_POST['sinfo_site_url'] );

	$sinfo['site_adminu'] 	= esc_html( $_POST['sinfo_site_adminu'] );

	$sinfo['site_adminp'] 	= esc_html( $_POST['sinfo_site_adminp'] );

	$sinfo['dev_url'] 		= esc_html( $_POST['sinfo_dev_url'] );

	$sinfo['dev_adminu'] 	= esc_html( $_POST['sinfo_dev_adminu'] );

	$sinfo['dev_adminp'] 	= esc_html( $_POST['sinfo_dev_adminp'] );

	$sinfo['htaccess_u'] 	= esc_html( $_POST['sinfo_htaccess_u'] );

	$sinfo['htaccess_p'] 	= esc_html( $_POST['sinfo_htaccess_p'] );

	$sinfo['ftp_host'] 		= esc_html( $_POST['sinfo_ftp_host'] );

	$sinfo['ftp_u'] 		= esc_html( $_POST['sinfo_ftp_u'] );

	$sinfo['ftp_p'] 		= esc_html( $_POST['sinfo_ftp_p'] );

	$sinfo['misc'] 			= esc_html( $_POST['sinfo_misc'] );

	$sinfo['os'] 			= esc_html( $_POST['sinfo_os'] );

	$sinfo['browser'] 		= esc_html( $_POST['sinfo_browser'] );

	$sinfo['userexpertise']	= esc_html( $_POST['userexpertise'] );	
	$sinfo_decoded 			= json_encode($sinfo);
	$insert_array 			= array();

	$insert_array['id'] 	= "";

	$insert_array['user_id']= $user_id;	

	$insert_array['post_id']= $post_id;	

	$insert_array['info'] 	= $sinfo_decoded;

	

	$wpdb->insert($wpdb->prefix.'site_info',$insert_array);

}
function dwqa_site_info_box(){

	global $wpdb;

	

	if( !is_user_logged_in() ) {

		return false;

	}
	$user 		= wp_get_current_user();

	

	$user_id = get_current_user_id();
	$domains	= array();

	$purchases 	= edd_get_users_purchases( get_current_user_id());

	if(!empty($purchases)){

		foreach($purchases as $payment){

			$licenses = edd_software_licensing()->get_licenses_of_purchase( $payment->ID );

			if(!empty($licenses)){

				foreach($licenses as $license){

					$sites = edd_software_licensing()->get_sites( $license->ID );

					if(!empty($sites)){

						foreach($sites as $site_row => $site){

							if(!in_array($site,$domains) && !strstr($site_row, "_changes")){

								$domains[] = $site;

							}

						}

					}

				}

			}

		}

	}

	if (!empty($domains)) {

		include plugin_dir_path(__FILE__)."/template/edit.php";	

	

	}

}

function dwqa_siteinfo_submit_question_bottom(){

	global $wpdb;

	

	if( !is_user_logged_in() ) {

		return false;

	}

	$user 		= wp_get_current_user();

	

	$user_id = get_current_user_id();

	$domains	= array();

	$purchases 	= edd_get_users_purchases( get_current_user_id());	

	if(!empty($purchases)){

		foreach($purchases as $payment){

			$licenses = edd_software_licensing()->get_licenses_of_purchase( $payment->ID );

			if(!empty($licenses)){

				foreach($licenses as $license){

					$sites = edd_software_licensing()->get_sites( $license->ID );

					if(!empty($sites)){

						foreach($sites as $site_row => $site){

							if(!in_array($site,$domains) && !strstr($site_row, "_changes")){

								$domains[] = $site;

							}

						}

					}

				}

			}

		}

	}

	if (empty($domains)) {

		include plugin_dir_path(__FILE__)."/template/empty_domains.php";

	}

	

}

add_action( 'dwqa_siteinfo_submit_question_bottom', "dwqa_siteinfo_submit_question_bottom");

function dwqa_get_user_domains(){

	global $wpdb;
	$response 	= new stdClass();

	$cat_id 	= absint( $_REQUEST['cat_id'] );

		

	if( !is_user_logged_in() || $cat_id == 0) {

        $response->error = "Error";

		echo json_encode($response);

		exit;

	}
	$product_id  = $wpdb->get_var( $wpdb->prepare( "SELECT edd_product_id FROM " . $wpdb->prefix . "edd_dwqa_categories WHERE dwqa_category_id = %d ", $cat_id ) );	

	if($product_id == 0){	

        $response->error = "Error";

		echo json_encode($response);

		exit;	

	}
	//$user 	 = wp_get_current_user();

	$user_id 	= get_current_user_id();
	$domains	= array();

	$purchases 	= edd_get_users_purchases( get_current_user_id());

	//echo $cat_id;

	//print_r($purchases);

	if(!empty($purchases)){

		foreach($purchases as $payment){

			$licenses = edd_software_licensing()->get_licenses_of_purchase( $payment->ID );

			//print_r($licenses); 

			if(!empty($licenses)){

				foreach($licenses as $license){

					

					$license_download_id = edd_software_licensing()->get_download_id( $license->ID );

					

					if($product_id != $license_download_id){

						continue;

					}

										

					$sites = edd_software_licensing()->get_sites( $license->ID );
					if(!empty($sites)){

						foreach($sites as $site_row => $site){

							if(!in_array($site,$domains) && !strstr($site_row, "_changes")){

								$domains[] = $site;

							}

						}

					}

				}

			}

		}
		if(empty($domains)){

			$response->error = "Error";

		} else {

			

			$response->message = '<select name="sinfo_domain" id="sinfo_domain" class="required" style="background-color:#FFF;" aria-required="true" required>

				<option value="" selected="selected"> '. __('Select a domain','dwqa_site_info') . '</option>';

				foreach($domains as $domain) {

					//$selected = (isset($sinfo_decoded) && $domain == $sinfo_decoded->sinfo_domain) ? 'selected="selected"' : '';

					$response->message .= '<option value="' . esc_html($domain) . '">' . esc_html($domain) . '</option>';

				}

			$response->message .= '</select>';

		

		}

	

	} else {

		$response->error = "Error";

	}
	echo json_encode($response);

	exit;

}

add_action( 'wp_ajax_getUserDomains', "dwqa_get_user_domains");
function ThreadSave(){

	global $wpdb;

		

    if( !is_user_logged_in() ) {

        return false;

    }
	$user 		= wp_get_current_user();

	if($user->caps['administrator'] != 1){

		return false;

	}
	$post_id 	= esc_html( $_REQUEST['post_id'] );

	$value 		= esc_html( $_REQUEST['value'] );

	$field 		= esc_html( $_REQUEST['field'] );
	$sinfo			= dwqa_get_site_info($post_id);

	$decoded_sinfo 	= json_decode($sinfo);	
	if(empty($decoded_sinfo)){

		$decoded_sinfo = new stdClass();

	}

	$decoded_sinfo->notesthread = $value;

	$encoded_sinfo				= json_encode($decoded_sinfo);
	$where_array 			= array();

	//$where_array['user_id']	= $user_id;	

	$where_array['post_id']	= $post_id;	

	

	$insert_array 			= array();	

	$insert_array['info'] 	= $encoded_sinfo;

	

	$update 	= $wpdb->update($wpdb->prefix.'site_info',$insert_array,$where_array);

	$response 	= new stdClass();
	if($update === false){

		$response->error = "Error";		

	}else{

		$response->message = "Ok";		

	}
	echo json_encode($response);

	exit;

}
function UserSave(){

	global $wpdb;

		

    if( !is_user_logged_in() ) {

        return false;

    }
	$user 		= wp_get_current_user();

	if($user->caps['administrator'] != 1){

		return false;

	}
	$id 		= esc_html( $_REQUEST['id'] );

	$value 		= esc_html( $_REQUEST['value'] );

	$user_id 	= esc_html( $_REQUEST['user_id'] );
	$user_notes	= dwqa_get_user_notes($user_id);
	if($user_notes === false){

		$action = "insert";

		$user_notes	= new stdClass();	

	}
	$where_array 			= array();

	$where_array['user_id']	= $user_id;	

	

	$insert_array 				= array();	

	$insert_array['user_id'] 	= $user_id;

	$insert_array['notes'] 		= $value;	

	

	if($action == "insert"){

		$update 	= $wpdb->insert($wpdb->prefix.'user_notes',$insert_array);		

	}else{

		$update 	= $wpdb->update($wpdb->prefix.'user_notes',$insert_array,$where_array);

	}

	$response 	= new stdClass();
	if($update === false){

		$response->error = "Error";		

	}else{

		$response->message = "Ok";		

	}
	echo json_encode($response);

	exit;

}
add_action( 'dwqa_submit_question_ui', 'dwqa_site_info_box', 11 );

add_action( 'dwqa_add_question', 'dwqa_save_site_info', 11 );

add_action( 'dwqa-question-content-footer', 'dwqa_show_site_info', 12 );

add_action( 'wp_enqueue_scripts', 'dwqa_siteinfo_js' );

add_action('wp_enqueue_scripts', 'dwqa_siteinfo_css');

add_action( 'wp_ajax_ThreadSave', "ThreadSave");

add_action( 'wp_ajax_UserSave', "UserSave");

function getOsInfo(){

	

	$uagent = $_SERVER["HTTP_USER_AGENT"];

	// the order of this array is important

	$oses   = array(

		'Win311' => 'Win16',

		'Win95' => '(Windows 95)|(Win95)|(Windows_95)',

		'WinME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',

		'Win98' => '(Windows 98)|(Win98)',

		'Win2000' => '(Windows NT 5.0)|(Windows 2000)',

		'WinXP' => '(Windows NT 5.1)|(Windows XP)',

		'WinServer2003' => '(Windows NT 5.2)',

		'WinVista' => '(Windows NT 6.0)',

		'Windows 7' => '(Windows NT 6.1)',

		'Windows 8' => '(Windows NT 6.2)',

		'WinNT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',

		'OpenBSD' => 'OpenBSD',

		'SunOS' => 'SunOS',

		'Ubuntu' => 'Ubuntu',

		'Android' => 'Android',

		'Linux' => '(Linux)|(X11)',

		'iPhone' => 'iPhone',

		'iPad' => 'iPad',

		'MacOS' => '(Mac_PowerPC)|(Macintosh)',

		'QNX' => 'QNX',

		'BeOS' => 'BeOS',

		'OS2' => 'OS/2',

		'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'

	);

	//$uagent = strtolower($uagent ? $uagent : $_SERVER['HTTP_USER_AGENT']);

	foreach ($oses as $os => $pattern)

		if (preg_match('/' . $pattern . '/i', $uagent))

			return $os;

	return $uagent;

}
function getBrowser() {

	if (isset($_SERVER["HTTP_USER_AGENT"]) OR ($_SERVER["HTTP_USER_AGENT"] != "")) {

		$visitor_user_agent = $_SERVER["HTTP_USER_AGENT"];

	} else {

		$visitor_user_agent = "Unknown";

	}

	$bname = 'Unknown';

	$version = "0.0.0";

 

	// Next get the name of the useragent yes seperately and for good reason

	if (strstr('MSIE', $visitor_user_agent) && !strstr('Opera', $visitor_user_agent)) {

		$bname = 'Internet Explorer';

		$ub = "MSIE";

	} elseif (strstr($visitor_user_agent, 'Firefox')) {

		$bname = 'Mozilla Firefox';

		$ub = "Firefox";

	} elseif (strstr($visitor_user_agent, 'Chrome')) {

		$bname = 'Google Chrome';

		$ub = "Chrome";

	} elseif (strstr($visitor_user_agent, 'Safari')) {

		$bname = 'Apple Safari';

		$ub = "Safari";

	} elseif (strstr($visitor_user_agent, 'Opera')) {

		$bname = 'Opera';

		$ub = "Opera";

	} elseif (strstr($visitor_user_agent, 'Netscape')) {

		$bname = 'Netscape';

		$ub = "Netscape";

	} elseif (strstr($visitor_user_agent, 'Seamonkey')) {

		$bname = 'Seamonkey';

		$ub = "Seamonkey";

	} elseif (strstr($visitor_user_agent, 'Konqueror')) {

		$bname = 'Konqueror';

		$ub = "Konqueror";

	} elseif (strstr($visitor_user_agent, 'Navigator')) {

		$bname = 'Navigator';

		$ub = "Navigator";

	} elseif (strstr($visitor_user_agent, 'Mosaic')) {

		$bname = 'Mosaic';

		$ub = "Mosaic";

	} elseif (strstr($visitor_user_agent, 'Lynx')) {

		$bname = 'Lynx';

		$ub = "Lynx";

	} elseif (strstr($visitor_user_agent, 'Amaya')) {

		$bname = 'Amaya';

		$ub = "Amaya";

	} elseif (strstr($visitor_user_agent, 'Omniweb')) {

		$bname = 'Omniweb';

		$ub = "Omniweb";

	} elseif (strstr($visitor_user_agent, 'Avant')) {

		$bname = 'Avant';

		$ub = "Avant";

	} elseif (strstr($visitor_user_agent, 'Camino')) {

		$bname = 'Camino';

		$ub = "Camino";

	} elseif (strstr($visitor_user_agent, 'Flock')) {

		$bname = 'Flock';

		$ub = "Flock";

	} elseif (strstr($visitor_user_agent, 'AOL')) {

		$bname = 'AOL';

		$ub = "AOL";

	} elseif (strstr($visitor_user_agent, 'AIR')) {

		$bname = 'AIR';

		$ub = "AIR";

	} elseif (strstr($visitor_user_agent, 'Fluid')) {

		$bname = 'Fluid';

		$ub = "Fluid";

	} else {

		$bname = 'Unknown';

		$ub = "Unknown";

	}

 

	// finally get the correct version number

	$known = array('Version', $ub, 'other');

	$pattern = '#(?<browser>' . join('|', $known) .

			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

	if (!preg_match_all($pattern, $visitor_user_agent, $matches)) {

		// we have no matching number just continue

	}

 

	// see how many we have

	$i = count($matches['browser']);

	if ($i != 1) {

		//we will have two since we are not using 'other' argument yet

		//see if version is before or after the name

		if (strripos($visitor_user_agent, "Version") < strripos($visitor_user_agent, $ub)) {

			$version = $matches['version'][0];

		} else {

			$version = $matches['version'][1];

		}

	} else {

		$version = $matches['version'][0];

	}

 

	// check if we have a number

	if ($version == null || $version == "") {

		$version = "?";

	}
 	return $bname;

/*	return array(

		'userAgent' => $visitor_user_agent,

		'name' => $bname,

		'version' => $version,

		'pattern' => $pattern

	);*/

}
function dwqa_siteinfo_get_product_by_question_category($cat_id){	

	global $wpdb;	

	$product_id  = $wpdb->get_var( $wpdb->prepare( "SELECT edd_product_id FROM " . $wpdb->prefix . "edd_dwqa_categories WHERE dwqa_category_id = %d ", $cat_id ) );	

	if($product_id > 0){	

		return $product_id;	

	} else {	

		return false;	

	}	

}	

	

	

function dwqa_siteinfo_get_question_category_by_product_id($product_id){	

	global $wpdb;	

	$cat_id  = $wpdb->get_var( $wpdb->prepare( "SELECT dwqa_category_id FROM " . $wpdb->prefix . "edd_dwqa_categories WHERE edd_product_id = %d ", $product_id ) );	

	if($cat_id > 0){	

		return $cat_id;	

	} else {	

		return false;	

	}	

}	

	

/**	

 * Has User Purchased	

 *	

 * Checks to see if a user has purchased a download.	

 *	

 * @access      public	

 * @since       1.0	

 * @param       int $user_id - the ID of the user to check	

 * @param       array $downloads - Array of IDs to check if purchased. If an int is passed, it will be converted to an array	

 * @param       int $variable_price_id - the variable price ID to check for	

 * @return      boolean - true if has purchased and license is active, false otherwise	

 */	

function dwqa_siteinfo_has_user_purchased( $user_id, $downloads, $variable_price_id = null, $verify_purchase = false ) {	

	

	$users_purchases = edd_get_users_purchases( $user_id );	

	

	$return = false;	

	

	if ( ! is_array( $downloads ) && $downloads !== NULL ) {	

		$downloads = array( $downloads );	

	}	

	

	$now	 		= strtotime(date('Y-m-d H:i:s'));	

	

	if ( $users_purchases ) {	

		foreach ( $users_purchases as $purchase ) {	

	

			$purchased_files = edd_get_payment_meta_downloads( $purchase->ID );	

				

			$licenses = edd_software_licensing()->get_licenses_of_purchase( $purchase->ID );	

			$licenses_products = array();	

	

			if( is_array( $licenses ) ){	

				foreach($licenses as $license){	

					$download_id 	= get_post_meta($license->ID, '_edd_sl_download_id', true);	

					$status 		= get_post_meta($license->ID, '_edd_sl_status', true);	

					$expire 		= get_post_meta($license->ID, '_edd_sl_expiration', true);	

					$licenses_products[$download_id] 			= array();	

					$licenses_products[$download_id]['status'] 	= $status;	

					$licenses_products[$download_id]['expire'] 	= $expire;					

				}	

			}else{	

				return false;	

			}	

	

			if ( is_array( $purchased_files ) ) {	

				foreach ( $purchased_files as $download ) {	

					if ( $downloads === NULL || in_array( $download['id'], $downloads )) {	


						//check to see if the license is active	

						//echo $licenses_products[$download['id']]['expire'] . ">" . $now . "==========";	

						if(isset($licenses_products[$download['id']]['expire']) && $now > $licenses_products[$download['id']]['expire']){// || $licenses_products[$download['id']]['status'] == 'inactive'	

							if($verify_purchase){

								return "purchased_expired";

							}else{

								return false;

							}

						}	

	

						$variable_prices = edd_has_variable_prices( $download['id'] );	

						if ( $variable_prices && ! is_null( $variable_price_id ) && $variable_price_id !== false ) {	

							if ( isset( $download['options']['price_id'] ) && $variable_price_id == $download['options']['price_id'] ) {	

								return true;	

							} else {	

								return false;	

							}	

						} else {	

							return true;	

						}	

					}	

				}	

			}	

		}	

	}	

	

	return false;	

}
function dwqa_siteinfo_activate() {

	global $wpdb;

	

	$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "edd_dwqa_categories` (

			`id` bigint(20) NOT NULL AUTO_INCREMENT,

			`dwqa_category_id` int(255) NOT NULL,

			`edd_product_id` int(255) NOT NULL,

			`edd_product_category_id` int(255) NOT NULL,

			PRIMARY KEY (`id`)

			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

	$wpdb->query( $sql );			

	$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "site_info` (

			`id` int(255) NOT NULL AUTO_INCREMENT,

			`user_id` int(255) NOT NULL,

			`post_id` int(255) NOT NULL,

			`info` text NOT NULL,

			PRIMARY KEY (`id`)

			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

	$wpdb->query( $sql );	

	$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "user_notes` (

			`id` int(255) NOT NULL AUTO_INCREMENT,

			`user_id` int(255) NOT NULL,

			`notes` text NOT NULL,

			PRIMARY KEY (`id`)

			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

	$wpdb->query( $sql );

		//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		//dbDelta($sql);
	//$wpdb->show_errors();

	//$wpdb->hide_errors();

	//$wpdb->query( $sql );

	//$wpdb->print_error();

}

register_activation_hook( __FILE__, 'dwqa_siteinfo_activate' );

function edd_update_category_visibility($term_id, $tt_id){
	if ( isset( $_REQUEST['dwqa-question_category_visibility'] ) && is_array($_REQUEST['dwqa-question_category_visibility']) && !empty($_REQUEST['dwqa-question_category_visibility']) ) {
		$update_values = implode(",",$_REQUEST['dwqa-question_category_visibility']);
	} else {
		$update_values = 0;
	}
	global $wpdb;
	//$wpdb->show_errors();
	
	$meta_value  = $wpdb->get_var( $wpdb->prepare( "SELECT `id` FROM `" . $wpdb->prefix . "term_meta` WHERE `term_id` = %d AND `meta_key` = 'visibility' ORDER BY `id` ASC LIMIT 0,1", $term_id ) );
	if (!$meta_value) {
		$sql = "INSERT INTO `" . $wpdb->prefix . "term_meta` (id, term_id, meta_key, meta_value) VALUES ('', " . $term_id . ", 'visibility' , '" . $update_values . "')";
		$wpdb->query( $sql );
		//$wpdb->print_error();
	} else {
		$sql = "UPDATE `" . $wpdb->prefix . "term_meta` SET `meta_value` = '" . $update_values . "' WHERE `id` = " . $meta_value  . " AND `meta_key` = 'visibility' AND `term_id` = ".$term_id;
		$wpdb->query( $sql );		
	}
}
add_action( 'edited_dwqa-question_category', 'edd_update_category_visibility', 10 , 2 );

function get_existing_visibility($tag_ID){
	global $wpdb;
	$meta_value  = $wpdb->get_var( $wpdb->prepare( "SELECT `meta_value` FROM `" . $wpdb->prefix . "term_meta` WHERE `term_id` = %d AND `meta_key` = 'visibility'  ORDER BY `id` ASC LIMIT 0,1", $tag_ID ) );
	
	return $meta_value;
}

function dwqa_siteinfo_edit_category_fields($tag, $taxonomy){
	global $wpdb;
	
	if ("dwqa-question_category" == $taxonomy) {
		
		$tag_ID 				= absint($_REQUEST['tag_ID']);
		if ($tag_ID > 0) {
			$existing_visibility 	= get_existing_visibility($tag_ID);
			$existing_visibility	= explode(",",$existing_visibility);
		} else {
			$existing_visibility 	= array(0);
		}
		?>
		<tr class="form-field term-parent-wrap">
			<th scope="row"><label for="parent"><?php _ex( 'Visibility', 'dwqa_site_info' ); ?></label></th>
			<td>
				<?php
				$dropdown_args = array(0 => "None", 1 => "All", 2 => "Logged in users", 3 => "Guest users");
				
				$downloads_items = $exclude = $wpdb->get_results( " SELECT * FROM `" . $wpdb->posts . "` WHERE `post_type` = 'download'" );
				
				if (!empty($downloads_items)) {
					foreach ($downloads_items as $download_item) {
						$dropdown_args[$download_item->ID] = $download_item->post_title;
					}
				}
				
				//echo '<select name="dwqa-question_category_visibility[]" id="dwqa-question_category_visibility" multiple>';
					
					//$selected = in_array( 0, $existing_visibility ) ? 'selected="selected"' : '';
					
					//echo '<option value="0" ' . $selected . ' >' . __( 'None', 'dwqa_site_info' ) . '</option>';
					foreach($dropdown_args as $row => $arg){
						
						$checked = in_array( $row, $existing_visibility ) ? 'checked' : '';
						
						//echo '<option value="' . $row . '" ' . $selected . ' >' . $arg . '</option>';
						
						echo '<input type="checkbox" name="dwqa-question_category_visibility[]" value="' . $row . '" ' . $checked . '>' . $arg . '<br>';
					}
				//echo '</select>&nbsp;';?>
				<p class="description"><?php _e('Visibility on front-end', 'dwqa_site_info'); ?></p>
			</td>
		</tr>	
		<?php
	}
}
add_action( 'dwqa-question_category_edit_form_fields', "dwqa_siteinfo_edit_category_fields", 10, 2);

function dwqa_siteinfo_filter_dropdown_cats($terms, $taxonomies, $args){
	global $wpdb;
	
	if (is_array($taxonomies)) {
		$taxonomies = $taxonomies[0];
	}

	if( is_admin() || 'dwqa-question_category' != $taxonomies || !isset($args['term_meta'])) 
		return $terms;
		

	foreach ($terms as $term_row => $term) {
		$meta_value  	= $wpdb->get_var( $wpdb->prepare( "SELECT `meta_value` FROM `" . $wpdb->prefix . "term_meta` WHERE `term_id` = %d AND `meta_key` = 'visibility'  ORDER BY `id` ASC LIMIT 0,1", $term->term_id ) );
		$meta_array		= explode(",",$meta_value);
		
		if (!empty($meta_array)) {
			$remove = array();
			//echo $term->name; print_r($meta_array);	echo "\n\n";		
			foreach ($meta_array as $meta_row => $meta) {
				if ($meta == 1) {
					$remove[$meta_row] = 0;
				} elseif ($meta > 3) {
					if (!is_user_logged_in()) {
						//unset($terms[$term_row]);
						$remove[$meta_row] = 1;
					} else {
						$has_purchased = dwqa_siteinfo_has_user_purchased(get_current_user_id(),$meta);
						if ($has_purchased !== true) {
							//unset($terms[$term_row]);
							$remove[$meta_row] = 1;
						} else {
							$remove[$meta_row] = 0;
						}
					}
				} elseif ($meta == 2) {
					if (!is_user_logged_in()) {
						//unset($terms[$term_row]);
						$remove[$meta_row] = 1;						
					} else {
						$remove[$meta_row] = 0;
					}
				} elseif ($meta == 3) {
					if (is_user_logged_in()) {
						//unset($terms[$term_row]);
						$remove[$meta_row] = 1;						
					} else {
						$remove[$meta_row] = 0;
					}
				} else {
					//unset($terms[$term_row]);
					$remove[$meta_row] = 1;					
				}
			}
			if (!in_array(0,$remove)) {
				unset($terms[$term_row]);
			}			
		}
	}
	
/*	if (is_user_logged_in()) {
		$exclude = $wpdb->get_col( " SELECT term_id FROM `" . $wpdb->prefix . "term_meta` WHERE `meta_value` NOT IN (1, 2) AND `meta_key` = 'visibility'" );
	} else {
		$exclude = $wpdb->get_col( " SELECT term_id FROM `" . $wpdb->prefix . "term_meta` WHERE `meta_value` NOT IN (1, 3) AND `meta_key` = 'visibility'" );
	}
	if (!empty($exclude)) {
		foreach ($exclude as $exclude_id) {
			foreach ($terms as $term_row => $term) {
				if ($term->term_id == $exclude_id) {
					unset($terms[$term_row]);
					break;
				}
			}
		}
		sort($terms);
	}*/
	
	sort($terms);
	return $terms;
}
add_filter('get_terms', 'dwqa_siteinfo_filter_dropdown_cats', 10, 3);

function dwqa_siteinfo_chage_avatar($avatar, $id_or_email, $size, $default, $alt, $args){
	$post = get_post();
	if (!empty($post) && "dwqa-question" == $post->post_type) {
		$avatar = str_replace("12","50",$avatar);
	}
	return $avatar;
}
add_filter('get_avatar', 'dwqa_siteinfo_chage_avatar', 10, 6);

function dwqa_siteinfo_guest_fields(){
	if ( ! is_user_logged_in() && dwqa_current_user_can( 'post_question' ) ) { ?>
	<div class="user-name">
		<label for="user-email" title="<?php _e( 'Enter your name.','dwqa_site_info' ) ?>"><?php _e( 'Your name *','dwqa' ) ?></label> 
		<input type="text" name="_dwqa_anonymous_name" id="_dwqa_anonymous_name" class="large-text" placeholder="<?php _e( 'Your name','dwqa_site_info' ) ?>" required> 
	</div>
	<?php  }
}
add_action('dwqa_submit_question_ui', 'dwqa_siteinfo_guest_fields');

function dwqa_siteinfo_guest_update_name($mid, $object_id, $meta_key, $_meta_value){
	if($meta_key == '_dwqa_anonymous_email'){
		if ( isset( $_POST['_dwqa_anonymous_name'] ) ) {	
			$question_author_name = sanitize_text_field($_POST['_dwqa_anonymous_name']);
			update_post_meta( $object_id, '_dwqa_anonymous_name', $question_author_name );
		}
	}
}
add_action( "added_post_meta", 'dwqa_siteinfo_guest_update_name', 10, 4 );
//add_action( "update_post_meta", 'dwqa_siteinfo_guest_update_name', 10, 4 );
/*function dwqa_siteinfo_add_file_paths($file_paths){
	array_unshift($file_paths, plugin_dir_path( __FILE__ )."template");
	return $file_paths;
}
add_filter( 'edd_template_paths', 'dwqa_siteinfo_add_file_paths' );*/
if (!function_exists('edd_dwqa_categories_override_question_content')) {
	function dwqa_siteinfo_categories_override_question_content($template, $name){
	
		if($name != 'single-question'){
			return $template;
		}
	
		return plugin_dir_path(__FILE__)."template/single-question.php";//$template;
	}
	add_filter( 'dwqa-load-template', 'dwqa_siteinfo_categories_override_question_content', 10000, 2  );
}

/*DWQA Submit question override*/

function dwqa_siteinfo_categories_submit_question_form_shortcode(){

	global $dwqa_sript_vars, $script_version, $dwqa_template_compat;

	ob_start();

	$dwqa_template_compat->remove_all_filters( 'the_content' );

	echo '<div class="dwqa-container" >';

		//dwqa_load_template( 'question', 'submit-form' );

		require plugin_dir_path(__FILE__)."template/question-submit-form.php";

	echo '</div>';

	$html = ob_get_contents();

	$dwqa_template_compat->restore_all_filters( 'the_content' );

	ob_end_clean();

	wp_enqueue_script( 'dwqa-submit-question', DWQA_URI . 'inc/templates/default/assets/js/dwqa-submit-question.js', array( 'jquery' ), $script_version, true );

	wp_localize_script( 'dwqa-submit-question', 'dwqa', $dwqa_sript_vars );

	//return $this->sanitize_output( $html );	

	return $html;	

}

add_shortcode( 'dwqa-submit-question-form', 'dwqa_siteinfo_categories_submit_question_form_shortcode' );


function dwqa_siteinfo_term_table() {

	global $wpdb;

	

	$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "term_meta` (

			  `id` int(255) NOT NULL AUTO_INCREMENT,

			  `term_id` int(255) NOT NULL,

			  `meta_key` varchar(255) NOT NULL,

			  `meta_value` varchar(255) NOT NULL,

			  PRIMARY KEY (`id`)

			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($sql);			

}

register_activation_hook( __FILE__, 'dwqa_siteinfo_term_table' );