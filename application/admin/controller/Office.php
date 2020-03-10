<?php 
namespace app\admin\controller;

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
			$this->return_msg(400,'更新科室失败',$res);
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
}