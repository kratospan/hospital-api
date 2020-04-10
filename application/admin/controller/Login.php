<?php 

namespace app\admin\controller;

class Login extends Common{
	
	// 网页管理后端登录的方法
	
	public function login(){
		$data = $this->params;
		$admin_name = md5($data['admin_name']);
		$admin_pwd = md5($data['admin_pwd']);
		$res = db('admin')->where('admin_name',$admin_name)->find();
		if($res){
			if($res['admin_pwd'] == $admin_pwd){
				$this->return_msg(200,'密码输入正确',$res['admin_type']);
			}else{
				$this->return_msg(400,'密码输入错误');
			}
		}else{
			$this->return_msg(400,'用户不存在');
		}
	}
}