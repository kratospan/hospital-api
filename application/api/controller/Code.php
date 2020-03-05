<?php 

namespace app\api\controller;

class Code extends Common{
	public function send_before_check(){
		$data = $this->params;
		$res = Db('code')->where('code_phone',$data['code_phone'])->find();
		if($res){
			//数据库中已经有手机号码
			$now = time();
			if($now - $res['code_time'] < 30){
				$this->return_msg(400,'30秒才能再次发送验证码');
			}
			return true;
		}
		//数据库中没有手机号码
		return false;
	}
	
	public function send_code(){
		//先判断验证码是否存在 且 是否是30秒内重新发送
		$data = $this->params;
		$data['code_time'] = time();
		$data['code_content'] = 3322;
		$success = '发送验证码成功';
		$fail = '发送验证码失败';
		if($this->send_before_check()){
			$res = Db('code')->where('code_phone',$data['code_phone'])->update($data);
			if($res){
				$this->return_msg(200,$success);
			}else{
				$this->return_msg(400,$fail);
			}
		}else{
			$res = Db('code')->insertGetId($data);
			if($res){
				$this->return_msg(200,$success);
			}else{
				$this->return_msg(400,$fail);
			}
		}
		// $this->post_request();
	}
	
	public function check_code(){
		$data  = $this->params;
		$res = Db('code')->where('code_phone',$data['code_phone'])->find();
		$time = time();
		if($time - $res['code_time'] > 300){
			$this->return_msg(400,'验证码已过期');
		}
		
		if($data['code_content'] != $res['code_content']){
			$this->return_msg(400,'验证码不正确');
		}
		
		$this->return_msg(200,'验证成功');
	}
	
}