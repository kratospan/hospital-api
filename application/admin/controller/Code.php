<?php 

namespace app\admin\controller;

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
		}else{
			//数据库中没有手机号码
			$insertData = array(
				'code_phone' => $data['code_phone'],
				'code_content' => 0000,
				'code_time' => 0000000
			);
			$res = db('code')->insert($insertData);
			if($res){
				return true;
			}else{
				$this->return_msg(400,'验证码发送失败，未知错误');
			}
			
		}
		//数据库中没有手机号码
		return false;
	}
	
	public function send_code(){
		//先判断验证码是否存在 且 是否是30秒内重新发送
		$data = $this->params;
		// $data['code_time'] = time();
		$success = '发送验证码成功';
		$fail = '发送验证码失败';
		if($this->send_before_check()){
			//向验证码接口发送POST请求
			header('Content-Type:text/html;charset=utf-8');
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_URL, 'http://20160519.sc2yun.com/api_utour/public/api/v1.testSDK/sendPhoneMessage');
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$code = rand(1000,9999);
			$send = array(
				'msg' => '【大象医疗】验证码:'.$code.'，您正在验证手机号码，请于5分钟内正确输入。',
				'phone' => $data['code_phone'],
			);
			//发送post请求
			curl_setopt($curl,CURLOPT_POSTFIELDS,$send);
			$res_data = json_decode(curl_exec($curl),true);
			if($res_data['code'] == 200) {
				$data['code_content'] = $code;
				$data['code_time'] = time();
				$res = Db('code')->where('code_phone',$data['code_phone'])->update($data);
				if($res){
					$this->return_msg(200,$success);
				}else{
					$this->return_msg(400,$fail,$res_data);
				}
			}else{
				$this->return_msg(400,$fail,$res_data);
			}
			
			
		}
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