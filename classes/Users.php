<?php
require_once __DIR__ . '/../config.php';
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(isset($this->conn) && $this->conn instanceof mysqli){
					$v = $this->conn->real_escape_string($v);
				}
				if(!empty($data)) $data .=" , ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], base_app . $fname);
			if($move){
				$data .=" , `avatar` = '{$fname}' ";
				if(isset($_SESSION['userdata']['avatar']) && is_file(base_app . $_SESSION['userdata']['avatar']))
					unlink(base_app . $_SESSION['userdata']['avatar']);
			}
		}

		// Update in session anyway
		foreach($_POST as $k => $v){
			if($k != 'id' && $k != 'password'){
				$this->settings->set_userdata($k, $v);
			}
		}
		if(isset($fname) && isset($move)) {
			$this->settings->set_userdata('avatar', $fname);
		}

		if(!$this->conn){
			$this->settings->set_flashdata('success','Profile updated in active session (connect DB in db_setup.php to persist across restarts).');
			return 1;
		}

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully saved.');
				return 1;
			}else{
				return 2;
			}
		}else{
			$safe_id = (int)$id;
			$qry = $this->conn->query("UPDATE users set {$data} where id = {$safe_id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				return 1;
			}else{
				return 2;
			}
		}
	}
	public function delete_users(){
		if(!$this->conn) return false;
		extract($_POST);
		$safe_id = (int)$id;
		$qry = $this->conn->query("DELETE FROM users where id = {$safe_id}");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			return 1;
		}else{
			return false;
		}
	}
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	default:
		break;
}
?>
