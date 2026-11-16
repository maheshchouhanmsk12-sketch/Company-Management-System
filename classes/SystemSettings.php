<?php
if(!class_exists('DBConnection')){
	require_once(__DIR__ . '/../config.php');
	require_once(__DIR__ . '/DBConnection.php');
}
class SystemSettings extends DBConnection{
	public function __construct(){
		parent::__construct();
	}
	function check_connection(){
		return($this->conn);
	}
	function load_system_info(){
		// Clean default fallback info matching actual disk assets
		$defaults = [
			'name' => 'Company Management System',
			'short_name' => 'Company CMS',
			'welcome_message' => 'We provide high-quality business solutions, digital services, and expert consulting to take your company to the next level.',
			'logo' => 'uploads/1628731260_logo.jpg',
			'banner' => 'uploads/1628750220_hd-background.jpg',
			'address' => '123 Business Avenue, Tech Park',
			'contact' => '+1 (555) 234-5678',
			'email' => 'contact@company.com'
		];

		if(!isset($_SESSION['system_info']) || !is_array($_SESSION['system_info'])){
			$_SESSION['system_info'] = $defaults;
		}

		// Purge any stale Real Estate data from session
		if(isset($_SESSION['system_info']['name']) && strpos($_SESSION['system_info']['name'], 'Real Estate') !== false){
			$_SESSION['system_info'] = $defaults;
		}

		if(!$this->conn) return false;
		$sql = "SELECT * FROM system_info";
		$qry = @$this->conn->query($sql);
		if($qry && $qry instanceof mysqli_result && $qry->num_rows > 0){
			while($row = $qry->fetch_assoc()){
				$_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
			}
		}
	}
	function update_system_info(){
		if(!$this->conn) return false;
		$sql = "SELECT * FROM system_info";
		$qry = @$this->conn->query($sql);
		if($qry && $qry instanceof mysqli_result){
			while($row = $qry->fetch_assoc()){
				if(isset($_SESSION['system_info'][$row['meta_field']]))unset($_SESSION['system_info'][$row['meta_field']]);
				$_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
			}
		}
		return true;
	}
	function update_settings_info(){
		if(!$this->conn) return false;
		$data = "";
		foreach ($_POST as $key => $value) {
			if($key == 'welcome_message') $value = addslashes($value);
			if(!in_array($key, array('about','privacy'))){
				if(isset($_SESSION['system_info'][$key])){
					$qry = $this->conn->query("UPDATE system_info set meta_value = '{$value}' where meta_field = '{$key}' ");
				}else{
					$qry = $this->conn->query("INSERT into system_info set meta_value = '{$value}', meta_field = '{$key}' ");
				}
			}
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
			if(isset($_SESSION['system_info']['logo'])){
				$qry = $this->conn->query("UPDATE system_info set meta_value = '{$fname}' where meta_field = 'logo' ");
				if(is_file('../'.$_SESSION['system_info']['logo'])) unlink('../'.$_SESSION['system_info']['logo']);
			}else{
				$qry = $this->conn->query("INSERT into system_info set meta_value = '{$fname}',meta_field = 'logo' ");
			}
		}
		if(isset($_FILES['img_banner']) && $_FILES['img_banner']['tmp_name'] != ''){
			$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img_banner']['name'];
			$move = move_uploaded_file($_FILES['img_banner']['tmp_name'],'../'. $fname);
			if(isset($_SESSION['system_info']['banner'])){
				$qry = $this->conn->query("UPDATE system_info set meta_value = '{$fname}' where meta_field = 'banner' ");
				if(is_file('../'.$_SESSION['system_info']['banner'])) unlink('../'.$_SESSION['system_info']['banner']);
			}else{
				$qry = $this->conn->query("INSERT into system_info set meta_value = '{$fname}',meta_field = 'banner' ");
			}
		}
		if(isset($_POST['about'])){
			file_put_contents(base_app.'about.html', $_POST['about']);
		}
		if(isset($_POST['privacy'])){
			file_put_contents(base_app.'privacy.html', $_POST['privacy']);
		}
		$update = $this->update_system_info();
		$flash = $this->set_flashdata('success','System Info Successfully Updated.');
		if($update && $flash){
			return true;
		}
	}
	function set_userdata($field='',$value=''){
		if(!empty($field) && !empty($value)){
			$_SESSION['userdata'][$field]= $value;
		}
	}
	function userdata($field = ''){
		if(!empty($field)){
			if(isset($_SESSION['userdata'][$field]))
				return $_SESSION['userdata'][$field];
			else
				return null;
		}else{
			return false;
		}
	}
	function set_flashdata($flash='',$value=''){
		if(!empty($flash) && !empty($value)){
			$_SESSION['flashdata'][$flash]= $value;
		return true;
		}
	}
	function chk_flashdata($flash = ''){
		if(isset($_SESSION['flashdata'][$flash])){
			return true;
		}else{
			return false;
		}
	}
	function flashdata($flash = ''){
		if(!empty($flash)){
			$_tmp = $_SESSION['flashdata'][$flash] ?? '';
			unset($_SESSION['flashdata']);
			return $_tmp;
		}else{
			return false;
		}
	}
	function sess_des(){
		if(isset($_SESSION['userdata'])){
			unset($_SESSION['userdata']);
			return true;
		}
		return true;
	}
	function info($field=''){
		if(!empty($field)){
			if(isset($_SESSION['system_info'][$field]) && $_SESSION['system_info'][$field] !== ''){
				if($field === 'name' && strpos($_SESSION['system_info'][$field], 'Real Estate') !== false){
					return 'Company Management System';
				}
				return $_SESSION['system_info'][$field];
			}
		}
		switch($field){
			case 'name': return 'Company Management System';
			case 'short_name': return 'Company CMS';
			case 'logo': return 'uploads/1628731260_logo.jpg';
			case 'banner': return 'uploads/1628750220_hd-background.jpg';
			case 'welcome_message': return 'We provide high-quality business solutions, digital services, and expert consulting to take your company to the next level.';
			case 'address': return '123 Business Avenue, Tech Park';
			case 'contact': return '+1 (555) 234-5678';
			case 'email': return 'contact@company.com';
			default: return '';
		}
	}
	function set_info($field='',$value=''){
		if(!empty($field) && !empty($value)){
			$_SESSION['system_info'][$field] = $value;
		}
	}
}
$_settings = new SystemSettings();
$_settings->load_system_info();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'update_settings':
		echo $sysset->update_settings_info();
		break;
	default:
		break;
}
?>
