<?php 
namespace app\api\controller;

use think\Db;

class Patient extends Common{

	public function add_patient(){
		$data = $this->params;
		$user_id = $this->params['user_id'];
		$res = db('patient')->where('user_id',$user_id)->select();
		if(count($res) >= 5){
			$this->return_msg(400,'添加就诊人失败，一个用户最多只能添加5个就诊人',$res);
		}
		$res = db('patient')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增就诊人成功',$res);
		}else{
			$this->return_msg(400,'新增就诊人失败');
		}
	}

	public function select_patient(){
		$data = $this->params['patient_id'];
		$res = db('patient')->where('patient_id',$data)->find();
		if($res){
			$this->return_msg(200,'查询就诊人成功',$res);
		}elseif ($res == 0) {
			$this->return_msg(200,'查询就诊人成功',$res);
		}else{
			$this->return_msg(400,'查询就诊人失败',$res);
		}
	}
	
	public function select_patient_list(){
		$data = $this->params['user_id'];
		$res = db('patient')->where('user_id',$data)->select();
		if(count($res) > 0){
			foreach ($res as $key => $value) {
				$card = $res[$key]['patient_card'];
			}
			$this->return_msg(200,'查询就诊人列表成功',$res,count($res));
		}elseif (count($res,COUNT_RECURSIVE) == 0) {
			$this->return_msg(200,'查询就诊人列表成功',$res);
		}
		else{
			$this->return_msg(400,'查询就诊人列表失败',$res);
		}
	}

	public function update_patient(){
		$data = $this->params;
		$res = db('patient')->where('patient_id',$data['patient_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新就诊人成功',$res);
		}else{
			$this->return_msg(400,'更新就诊人失败',$res);
		}
	}

	public function delete_patient(){
		$data = $this->params;
		$res = db('patient')->delete($data['patient_id']);
		if($res){
			$this->return_msg(200,'删除就诊人成功',$res);
		}else{
			$this->return_msg(400,'删除就诊人失败',$res);
		}
	}


	//以下皆为网页端的api

	public function select_patient_list_admin(){
		$count = $this->select_all('patient');
		$res = db('patient')
			   ->select();
		if(count($res) > 0){
			foreach( $res as $key => $value){
				$res[$key]['patient_sex'] = $this->turn_sex($res[$key]['patient_sex']);
			}
			$this->return_msg(200,'查询就诊人列表成功',$res,$count);
		}elseif (count($res) == 0) {
			$this->return_msg(200,'查询就诊人列表成功',$res);
		}
		else{
			$this->return_msg(400,'查询就诊人列表失败',$res);
		}
	}

	public function select_patient_list_admin_by(){
		$data = $this->params;

		// $sql =  str_replace("and"," ",strpos($sql,'and'),strlen('and'));
		// echo $sql;
		// $sql = "select * from patient where ";
		// $needle = "and";
		
		// if($data['patient_name'] != "") $sql = $sql."and patient_name='".$data['patient_name']."'";
		// if($data['patient_card'] != "") $sql = $sql."and patient_card='".$data['patient_card']."'";
		// if($data['patient_phone'] != "") $sql = $sql."and patient_phone='".$data['patient_phone']."'";
		// if($data['patient_sex'] != "") $sql = $sql."and patient_sex='".$data['patient_sex']."'";
		// $sql = substr_replace($sql,"",strpos($sql,$needle),strlen($needle));
		// if($data['patient_name'] == ''&&$data['patient_card'] == ''&&$data['patient_phone'] == ''&&$data['patient_sex'] == ''){
		// 	$sql = "select * from patient";
		// }
		$sql = $this->turn_sql($data,'patient');
		$res = Db::query($sql);
		if(count($res) > 0){
			foreach( $res as $key => $value){
				$res[$key]['patient_sex'] = $this->turn_sex($res[$key]['patient_sex']);
			}
			$this->return_msg(200,'查询就诊人列表成功',$res,count($res));
		}elseif (count($res) == 0) {
			$this->return_msg(200,'查询就诊人列表成功',$res,count($res));
		}
		else{
			$this->return_msg(400,'查询就诊人列表失败',$res);
		}
	}



	
}