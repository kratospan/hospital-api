<?php 
namespace app\api\controller;

use think\Db;

class Department extends Common{
	public function add_department(){
		$data = $this->params;
		$res = db('department')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增部门成功',$res);
		}else{
			$this->return_msg(400,'新增部门失败');
		}
	}
	
	public function select_department(){
		// $data = $this->params['department_id'];
		$res = db('department')->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询部门成功',$res);
		}else{
			$this->return_msg(400,'查询部门失败',$res);
		}
	}
	
	public function update_department(){
		$data = $this->params;
		$res = db('department')->where('department_id',$data['department_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新部门成功',$res);
		}else{
			$this->return_msg(400,'更新部门失败(可能是没有更新任何数据)',$res);
		}
	}
	
	public function delete_department(){
		$data = $this->params;
		$res = db('department')->delete($data['department_id']);
		if($res){
			$this->return_msg(200,'删除部门成功',$res);
		}else{
			$this->return_msg(400,'删除部门失败',$res);
		}
	}

	//以下是管理后台的接口
	public function select_department_list_admin(){
		$data = $this->params;
		$sql = $this->turn_sql($data,'department');
		// echo $sql;
		$res = Db::query($sql);
		if(count($res) >= 0){
			$this->return_msg(200,'查询用户成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询用户失败',$res);
		}
	}
}