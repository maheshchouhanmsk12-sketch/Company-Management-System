<?php
ob_start();
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/initialize.php');
require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/SystemSettings.php');

$db = new DBConnection;
$conn = $db->conn;

function redirect($url=''){
	if(!empty($url))
	echo '<script>location.href="'.base_url . ltrim($url, '/').'"</script>';
}

function get_banner_url(){
    global $_settings;
    if(isset($_settings)){
        $banner = $_settings->info('banner');
        if(!empty($banner) && is_file(base_app . $banner)){
            return base_url . $banner;
        }
    }
    if(is_file(base_app . 'uploads/1628750220_hd-background.jpg')){
        return base_url . 'uploads/1628750220_hd-background.jpg';
    }
    if(is_file(base_app . 'profile_asset/images/header-background.jpg')){
        return base_url . 'profile_asset/images/header-background.jpg';
    }
    return '';
}

function get_logo_url(){
    global $_settings;
    if(isset($_settings)){
        $logo = $_settings->info('logo');
        if(!empty($logo) && is_file(base_app . $logo)){
            return base_url . $logo;
        }
    }
    if(is_file(base_app . 'uploads/1628731260_logo.jpg')){
        return base_url . 'uploads/1628731260_logo.jpg';
    }
    if(is_file(base_app . 'profile_asset/images/profilepic.jpg')){
        return base_url . 'profile_asset/images/profilepic.jpg';
    }
    return base_url . 'dist/img/AdminLTELogo.png';
}

function validate_image($file){
	if(!empty($file)){
		if(is_file(base_app . $file)){
			return base_url . $file;
		}
	}
	return base_url . 'dist/img/no-image-available.png';
}

function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    if(isset($_SERVER['HTTP_USER_AGENT'])){
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
        }
    }
    return false;
}
ob_end_flush();
?>
