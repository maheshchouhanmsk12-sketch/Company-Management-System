<?php
require_once __DIR__ . '/../config.php';
Class Content extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function update(){
		extract($_POST);
		$content_file = base_app . $file . ".html";
		$update = file_put_contents($content_file, $content);
		if($update !== false){
			$this->settings->set_flashdata("success", ucfirst($file) . " content successfully updated");
			return json_encode(array("status"=>"success"));
		} else {
			return json_encode(array("status"=>"failed", "message"=>"Failed to write file"));
		}
	}
	public function service(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database connection is not available. Please configure Aiven DB in db_setup.php'));
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
		if(!empty($data)) $data .= ", ";
		$data .= "`description` = '".$this->conn->real_escape_string(addslashes(htmlentities($description)))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO services set $data";
		}else{
			$safe_id = (int)$id;
			$sql ="UPDATE services set $data where id = {$safe_id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Service Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
	}
	public function service_delete(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$safe_id = (int)$id;
		$row = $this->conn->query("SELECT file_path FROM services where id = {$safe_id}");
		$fpath = ($row && $row instanceof mysqli_result) ? ($row->fetch_array()['file_path'] ?? '') : '';
		$qry = $this->conn->query("DELETE FROM services where id = {$safe_id}");
		if($qry){
			if(!empty($fpath) && is_file(base_app.$fpath))
				unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Service Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}

	public function testimonial(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','message','old_file'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
		if(!empty($data)) $data .= ", ";
		$data .= "`message` = '".$this->conn->real_escape_string(addslashes(htmlentities($message)))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO testimonials set $data";
		}else{
			$safe_id = (int)$id;
			$sql ="UPDATE testimonials set $data where id = {$safe_id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Testimony successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
	}

	public function testimonial_delete(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$safe_id = (int)$id;
		$row = $this->conn->query("SELECT file_path FROM testimonials where id = {$safe_id}");
		$fpath = ($row && $row instanceof mysqli_result) ? ($row->fetch_array()['file_path'] ?? '') : '';
		$qry = $this->conn->query("DELETE FROM testimonials where id = {$safe_id}");
		if($qry){
			if(!empty($fpath) && is_file(base_app.$fpath))
				unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Testimony successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Testimony has failed to delete";
		}
		return json_encode($resp);
	}

	public function client(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
		if(!empty($data)) $data .= ", ";
		$data .= "`description` = '".$this->conn->real_escape_string(addslashes(htmlentities($description)))."'";

		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app. $fname);
			if($move){
				$data .=" , file_path = '{$fname}' ";
			}
		}	

		if(empty($id)){
			$sql ="INSERT INTO clients set $data";
		}else{
			$safe_id = (int)$id;
			$sql ="UPDATE clients set $data where id = {$safe_id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Client Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
	}

	public function client_delete(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$safe_id = (int)$id;
		$row = $this->conn->query("SELECT file_path FROM clients where id = {$safe_id}");
		$fpath = ($row && $row instanceof mysqli_result) ? ($row->fetch_array()['file_path'] ?? '') : '';
		$qry = $this->conn->query("DELETE FROM clients where id = {$safe_id}");
		if($qry){
			if(!empty($fpath) && is_file(base_app.$fpath))
				unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Client Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Client Details has failed to delete";
		}
		return json_encode($resp);
	}

	public function contact(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$data = "";
		foreach ($_POST as $key => $value) {
			$k = $this->conn->real_escape_string($key);
			$v = $this->conn->real_escape_string($value);
			if(!empty($data)) $data .= ", ";
			$data .= "('{$k}','{$v}')";
		}
		$this->conn->query("TRUNCATE `contacts`");
		$sql = "INSERT INTO `contacts` (meta_field, meta_value) Values $data";
		$qry = $this->conn->query($sql);
		if($qry){
			$resp['status']='success';
			$resp['message']= " Contact Details successfully updated";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='error';
			$resp['message']= $sql;
		}
		return json_encode($resp);
	}

	public function message_delete(){
		if(!$this->conn){
			return json_encode(array('status'=>'failed','message'=>'Database not connected'));
		}
		extract($_POST);
		$safe_id = (int)$id;
		$qry = $this->conn->query("DELETE FROM messages where id = {$safe_id}");
		if($qry){
			$resp['status']='success';
			$resp['message']= " Inquiry successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Inquiry has failed to delete";
		}
		return json_encode($resp);
	}
}

$Content = new Content();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'update':
		echo $Content->update();
	break;
	case 'service':
		echo $Content->service();
	break;
	case 'service_delete':
		echo $Content->service_delete();
	break;
	case 'testimonial':
		echo $Content->testimonial();
	break;
	case 'testimonial_delete':
		echo $Content->testimonial_delete();
	break;
	case 'client':
		echo $Content->client();
	break;
	case 'client_delete':
		echo $Content->client_delete();
	break;
	case 'message_delete':
		echo $Content->message_delete();
	break;
	case 'contact':
		echo $Content->contact();
	break;
	default:
		break;
}
?>
