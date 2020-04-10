<?php 
namespace app\admin\controller;
use think\Db;

class Department extends Common{
	
	//查询部门信息
	// public function select_department(){
	// 	// $data = $this->params['department_id'];
	// 	$res = db('department')->select();
	// 	if(count($res) >= 0){
	// 		$this->return_msg(200,'查询部门成功',$res,count($res));
	// 	}else{
	// 		$this->return_msg(400,'查询部门失败',$res);
	// 	}
	// }
	
	//查询部门信息
	public function select_department(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from department";
		//判断传递的数组是否有Page属性
		if(isset($data['page'])){
			$page = $data['page'];
			if($page < 1){
				$this->return_msg(400,'页码数不正确，请填入正确的页码数');
			}
			$page = $data['page'] - 1;
			unset($data['page']);
			$sql = $sql.$this->turn_special_sql($data).' limit '.($page*15).',15';
		}else{
			$sql = $sql.$this->turn_special_sql($data);
		}
		
		$res = Db::query($sql);
		if(count($res) >= 0){
			//获取查询数据的总数
			$num = Db::query('SELECT FOUND_ROWS()');
			$this->return_msg(200,'查询部门成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询部门失败',$res);
		}
	}
	
	
	//添加部门信息
	public function add_department(){
		$data = $this->params;
		$res = db('department')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增部门成功',$res);
		}else{
			$this->return_msg(400,'新增部门失败');
		}
	}
	
	
	
	//更新部门信息
	public function update_department(){
		$data = $this->params;
		$res = db('department')->where('department_id',$data['department_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新部门成功',$res);
		}else{
			$this->return_msg(400,'更新部门失败',$res);
		}
	}
	
	//删除部门信息
	public function delete_department(){
		$data = $this->params;
		$res = db('department')->delete($data['department_id']);
		if($res){
			$this->return_msg(200,'删除部门成功',$res);
		}else{
			$this->return_msg(400,'删除部门失败',$res);
		}
	}
}