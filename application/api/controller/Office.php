<?php 
namespace app\api\controller;

use think\Db;

class Office extends Common{
	public function add_office(){
		$data = $this->params;
		$res = db('office')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增科室成功',$res);
		}else{
			$this->return_msg(400,'新增科室失败');
		}
	}
	
	public function select_office(){
		$data = $this->params['department_id'];
		$res = db('office')->where('department_id',$data)->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询科室成功',$res);
		}else{
			$this->return_msg(400,'查询科室失败',$res);
		}
	}

	public function select_office_more(){
		$data = $this->params['office_id'];
		$res = db('office')->where('office_id',$data)->find();
		if(count($res) >= 0){
			$this->return_msg(200,'查询科室成功',$res);
		}else{
			$this->return_msg(400,'查询科室失败',$res);
		}
	}


	
	public function update_office(){
		$data = $this->params;
		$res = db('office')->where('office_id',$data['office_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新科室成功',$res);
		}else{
			$this->return_msg(400,'更新科室失败(可能是没有更新任何数据)',$res);
		}
	}
	
	public function delete_office(){
		$data = $this->params;
		$res = db('office')->delete($data['office_id']);
		if($res){
			$this->return_msg(200,'删除科室成功',$res);
		}else{
			$this->return_msg(400,'删除科室失败',$res);
		}
	}

	//网页管理后台 api
	public function select_office_list_admin(){
		// $data = $this->params['office_id'];
		$res = db('office')->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询科室成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询科室失败',$res);
		}
	}

	public function select_office_list_more_admin(){
		$data = $this->params;
		$sql = "SELECT
		d.department_id,
		d.department_name,
		o.office_id,
		o.office_name,
		o.office_introduce,
		o.office_phone 
	FROM
		office o
		INNER JOIN department d ON d.department_id = o.department_id";
		$sql = $sql.$this->turn_special_sql($data);
		$res = Db::query($sql);
		if(count($res) >= 0){
			$this->return_msg(200,'查询排班记录成功',$res,count($res));
		}
		else{
			$this->return_msg(400,'查询排班记录失败',$res);
		}
	}
}