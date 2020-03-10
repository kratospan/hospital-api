<?php 

namespace app\admin\controller;

class Login extends Common{
	
	public function login(){
		$data = $this->params;
		//获取小程序发送过来的code
		$code = $this->params['code'];
		$user_nickname = $this->params['user_nickname'];
		$user_avatar = $this->params['user_avatar'];
		//配置必要的参数
		$appid = 'wxe75c588a767641af';
		$secret = '408012f8ee91498cd98140e7858f1d07';

		$curlobj = curl_init();
		//组合请求链接
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
		curl_setopt($curlobj, CURLOPT_URL, $url);
		//设置是否输出header
        curl_setopt($curlobj, CURLOPT_HEADER, false);
        //设置是否输出结果
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        //设置是否检查服务器端的证书
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false);
        //使用curl_exec()将curl返回的结果转换成正常数据并保存到一个变量中
        $data = curl_exec($curlobj);
        //关闭会话
        curl_close($curlobj);
        $data = json_decode($data);
		// if(!empty($data['errcode'])){
		// 	$this->return_msg(200,'fail',$data);
		// }
		if(!empty($data->errcode)){
			$this->return_msg(400,'登录失败，请重新登录',$data);
		}

		$open_id = $data->openid;

		$res = db('user')->where('open_id',$open_id)->find();
		if($res){
			$token = md5(md5('api-'.$open_id.'-ipa').$open_id.time());
			$update = db('user')->where('open_id',$open_id)->setField('token',$token);
			if($update){
				$res['token'] = $token;
				$this->return_msg(200,'登录成功',$res);
			}else{
				$this->return_msg(400,'登录失败',$update);
			}
		}else{
			$token = md5(md5('api-'.$open_id.'-ipa').$open_id.time());
			$data = array(
				'open_id' => $open_id,
				'user_nickname' => $user_nickname,
				'user_avatar' => $user_avatar,
				'token' => $token
			);
			$user_id = db('user')->insertGetId($data);
			if($user_id){
				$data = array(
					'token' => $token,
					'user_id' => $user_id
				);
				$this->return_msg(200,'登录成功',$data);
			}else{
				$this->return_msg(400,'登录失败',$user_id);
			}
		}

        $this->return_msg(200,'success',$openid);

	}


}