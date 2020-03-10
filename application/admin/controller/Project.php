<?php 
namespace app\admin\controller;

class Project extends Common{

	public function add_project(){
		$data = $this->params;
		$res = db('project')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增项目成功',$res);
		}else{
			$this->return_msg(400,'新增项目失败',$res);
		}
	}

	public function select_project(){
		$data = $this->params['meal_id'];
		$res = db('project')->where('meal_id',$data)->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询项目成功',$res);
		}else{
			$this->return_msg(400,'查询项目失败',$res);
		}
	}

	public function update_meal(){
		$data = $this->params;
		$res = db('project')->where('meal_id',$data['meal_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新套餐成功',$res);
		}else{
			$this->return_msg(400,'更新项目失败',$res);
		}
	}

	public function delete_project(){
		$data = $this->params;
		$res = db('project')->delete($data['meal_id']);
		if($res){
			$this->return_msg(200,'删除项目成功',$res);
		}else{
			$this->return_msg(400,'删除项目失败',$res);
		}
	}
}