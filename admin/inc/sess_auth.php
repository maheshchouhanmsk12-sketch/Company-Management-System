<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$current_uri = $_SERVER['REQUEST_URI'] ?? '';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$is_login_page = (strpos($current_uri, 'login.php') !== false) || (basename($script_name) === 'login.php');

if(!isset($_SESSION['userdata']) && !$is_login_page){
	redirect('admin/login.php');
}
if(isset($_SESSION['userdata']) && $is_login_page){
	redirect('admin/index.php');
}

$module = array('','admin','establishment','users');
if(isset($_SESSION['userdata']) && (strpos($current_uri, 'index.php') !== false || strpos($current_uri, 'admin/') !== false) && ($_SESSION['userdata']['login_type'] ?? 1) != 1){
	echo "<script>alert('Access Denied!');location.replace('".base_url.$module[$_SESSION['userdata']['login_type']]."');</script>";
    exit;
}
?>