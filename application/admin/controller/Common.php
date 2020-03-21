<?php
namespace app\admin\controller;

use think\Validate;
use think\Controller;
use think\Request;
use think\Curl;

class Common extends Controller {

    protected $request; //用来处理参数
    protected $validater; //用来验证数据、参数
    protected $params; //过滤后符合要求的参数
    //定义参数过滤的规则
    protected $rules = array(
    	'Testing' => array(
			'random_add_doctor' => array(
			
			),
			'add_doctor' => array(
			
			)
		)
    );
	//自定义过滤提示
	protected $err_msg = array (
		'User' => array(
			'login' =>array(
				'user_name' => ['require','chsDash','max'=>20],
				'user_pwd' => 'require|length:32'
			)
		),
		'Patient' => array(
		    'add_patient' => array(
		        // 'patient_id' => 'require|number',
		        'patient_name.require' => '就诊人姓名不能为空',
		        'patient_name.chsAlpha' => '就诊人姓名格式错误',
				
		        'patient_card.require' => '身份证号码不能为空',
				'patient_card.number' => '身份证号码格式错误',
				'patient_card.unique' => '身份证号码已被绑定',
				
		        'patient_phone。require' => '手机号码不能为空',
				'patient_phone.number' => '手机号码格式错误',
				'patient_phone.length' => '手机号码格式错误',
				
		        'patient_relationship' => 'require|chs',
		        'user_id' => 'require|number'
		    ),
		    'select_patient' => array(
		        'patient_id' => 'require|number'
		    ),
		    'update_patient' => array(
		        'patient_id' => 'require|number',
		        'patient_name' => 'require|chs',
		        'patient_card' => 'require',
		        'patient_phone' => 'require|number|length:11',
		        'patient_sex' => 'require|number|length:1',
		        'patient_relationship' => 'require|chs',
		        'user_id' => 'require|number'
		    ),
		    'delete_patient' => array(
		        'patient_id' => 'require|number'
		    )
		),
		'Doctor' => array(
			'add_doctor' =>array(
				'user_name' => ['require','chsDash','max'=>20],
				'user_pwd' => 'require|length:32'
			)
		),
	);
	

    //初始化方法
    protected function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
		// $this->check_time($this->request->only(['time']));
		
		$check = $this->request->param();
        // if(!isset($check['code'])){
		// 	$this->check_token($this->request->param());
		// }
       	$this->params = $this->check_params($this->request->except(['time','token']));   //验证数据的有效性
    }

    /**
     * @param  [array] $arr [包含时间戳的参数数组]
     * @return [json]       [检测结果]
     **/
    public function check_time($arr) {
        if (!isset($arr['time']) || intval($arr['time']) <= 1) {
            $this->return_msg(400, '时间戳不正确！');
        }
        if (time() - intval($arr['time']) > 60) {
            $this->return_msg(400, '请求超时!');
        }
    }



    /**
     * @param  [array] $arr [包含token的参数数组]
     * @return [json]       [检测结果]
     **/
    public function check_token($arr) {
        if (!isset($arr['token']) || empty($arr['token'])) {
            $this->return_msg(101, 'token值为空');
        }
        $app_token = $arr['token'];
		$res = db('user')->where('token',$app_token)->find();
		if(!$res){
			$this->return_msg(101,'token值错误');
		}
    }


    /**
     * @param  [array] $arr [包含除时间戳和token外的参数数组]
     * @return [json]       [检测结果]
     **/
    public function check_params($arr){
    	$rule = $this->rules[$this->request->controller()][$this->request->action()];
		// $msg = $this->err_msg[$this->request->controller()][$this->request->action()];
    	$this->validater = new Validate($rule);
    	if(!$this->validater->check($arr)){
    		$this->return_msg(400,$this->validater->getError());
    	}
    	return $arr;
    }

    //返还处理信息
    public function return_msg($code, $msg = '', $data = [],$num = 0) {
        $return_data['code'] = $code;
        $return_data['msg']  = $msg;
        $return_data['data'] = $data;
        $return_data['num'] = $num;
        echo json_encode($return_data);
        die;
    }


    //将时间段标记转化为具体的时间
    public function turn_time($timestamp) {
        $array = [
    		'08:00-09:00',
    		'09:00-10:00',
    		'10:00-11:00',
    		'14:00-15:00',
    		'15:00-16:00',
    		'16:00-17:00',
    	];
    	return $array[$timestamp];
    }

    //将星期段标记转化为具体的星期
    public function turn_day($time){
    	$array = ['周日','周一','周二','周三','周四','周五','周六'];
    	return $array[date('w',$time)];
    }

    //判断预约状态
    public function turn_status($status){
    	$array = ['预约成功','预约取消','预约过期'];
    	return $array[$status];
    }

    //判断医生的头衔
    public function turn_title($title){
    	// 0是主任医师，1是副主任医师，2是主治医师，3是住院医师
    	$array = ['主任医师','副主任医师','主治医师','住院医师'];
    	return $array[$title];
    }


}