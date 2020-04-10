<?php 
namespace app\admin\controller;

class Project extends Common{
	//添加项目信息
	public function add_project(){
		$data = $this->params;
		$res = db('project')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增项目成功',$res);
		}else{
			$this->return_msg(400,'新增项目失败',$res);
		}
	}
	//查询项目信息
	public function select_project(){
		$data = $this->params['meal_id'];
		$res = db('project')->where('meal_id',$data)->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询项目成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询项目失败',$res);
		}
	}
	//更新项目信息
	public function update_project(){
		$data = $this->params;
		$res = db('project')->where('project_id',$data['project_id'])->update($data);
		if($res){
			$this->return_msg(200,'修改套餐成功',$res);
		}else{
			$this->return_msg(400,'修改项目失败(有可能是数据没有修改)',$res);
		}
	}
	//删除项目信息
	public function delete_project(){
		$data = $this->params;
		$res = db('project')->delete($data['project_id']);
		if($res){
			$this->return_msg(200,'删除项目成功',$res);
		}else{
			$this->return_msg(400,'删除项目失败',$res);
		}
	}
}