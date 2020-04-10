<?php
namespace app\admin\controller;
use think\Db;
class User extends Common {
	public function add_user(){
		$data = $this->params;
		$res = db('user')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增用户成功',$res);
		}else{
			$this->return_msg(400,'新增用户失败');
		}
	}
	
	public function select_user(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from user";
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
			$this->return_msg(200,'查询用户成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询用户失败',$res);
		}
	}
	
	public function update_user(){
		$data = $this->params;
		$res = db('user')->where('user_id',$data['user_id'])->update($data);
		if($res){
			$this->return_msg(200,'修改用户成功',$res);
		}else{
			$this->return_msg(400,'修改用户失败',$res);
		}
	}
	
	public function delete_user(){
		$data = $this->params;
		$res = db('user')->delete($data['user_id']);
		if($res){
			$this->return_msg(200,'删除用户成功',$res);
		}else{
			$this->return_msg(400,'删除用户失败',$res);
		}
	}
}
