<?php 
namespace app\api\controller;

class Patient extends Common{

	public function add_patient(){
		$data = $this->params;
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
				substr_replace($card,"*******",8,);
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
}