<?php
namespace app\api\controller;
namespace app\api\controller;

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
    	'Login' => array (
    		'login' => array (
    			// 'user_name' => ['require','chsDash','max'=>20],
    			'code' => 'require'
    		)
    	),
        'Patient' => array(
            'add_patient' => array(
                // 'patient_id' => 'require|number',
                'patient_name' => 'require|chsAlpha',
                'patient_card' => 'require|unique:patient|number',
                'patient_phone' => 'require|number|length:11',
                'patient_sex' => 'require|number|length:1',
                'patient_relationship' => 'require|chs',
                'user_id' => 'require|number'
            ),
            'select_patient' => array(
                'patient_id' => 'require|number'
            ),
			'select_patient_list' => array(
			    'user_id' => 'require|number'
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
        'Notice' => array(
			'add_notice' => array (
				'notice_type' => 'require|number',
				'test_id' => 'number',
				'register_id' => 'number',
				'user_id' => 'require|number'
			),
			'delete_notice' => array (
				'notice_id' => 'require|number',
			),
			'select_notice_list' => array (
				'user_id' => 'number|require',
				// 'page' => 'number|require'
			),
			'select_notice' => array (
				'notice_id' => 'number|require'
			),
			'update_notice' => array (
				'notice_id' => 'require|number',
				'test_id' => 'number',
				'register_id' => 'number',
				'user_id' => 'require|number'
			)
		),
		'Doctor' => array(
			'add_doctor' => array(
			    'doctor_name' => 'require|chsAlpha',
			    'doctor_sex' => 'require|number|length:1',
				'department_id' => 'require|number',
				'office_id' => 'require|number',
				'doctor_title' => 'require|number|length:1',

			),
			'update_doctor' => array(
				'doctor_id' => 'require|number',
			    'doctor_name' => 'require|chsAlpha',
			    'doctor_sex' => 'require|number|length:1',
				'department_id' => 'require|number',
				'office_id' => 'require|number',
				'doctor_title' => 'require|number|length:1',
			),
			'select_doctor' => array(
			    'doctor_id' => 'require|number',
			),
			'select_doctor_list' => array(
			    'office_id' => 'require|number',
			    'page' => 'require|number'
			),
			'delete_doctor' => array(
			    'doctor_id' => 'require|number',
			)
		),
		'Department' => array(
			'add_department' => array(
			    'department_name' => 'require|chsAlpha',
			),
			'update_department' => array(
				'department_id' => 'require|number',
			    'department_name' => 'require|chsAlpha',
			),
			'select_department' => array(
			    // 'department_id' => 'require|number',
			),
			'delete_department' => array(
			    'department_id' => 'require|number',
			)
		),
		'Office' => array(
			'add_office' => array(
			    'office_name' => 'require|chsAlpha',
				'department_id' => 'require|number',
			),
			'update_office' => array(
				'office_id' => 'require|number',
			    'office_name' => 'require|chsAlpha',
				'department_id' => 'require|number',
			),
			'select_office' => array(
			    'department_id' => 'require|number',
			),
			'select_office_more' => array(
			    'office_id' => 'require|number',
			),
			'delete_office' => array(
			    'office_id' => 'require|number',
			)
		),
		'User' => array(
			'add_user' => array(
			    'user_nickname' => 'require',
				'user_id' => 'require',
			),
			'update_user' => array(
				'user_id' => 'require',
			    'user_nickname' => 'require',
			),
			'select_user' => array(
			    'user_id' => 'require',
			),
			'delete_user' => array(
			    'user_id' => 'require',
			)
		),
		'Register' => array(
			'add_register' => array(
				// 'register_status' => 'require|number',
				'doctor_id' => 'require|number',
				'patient_id' => 'require|number',
				// 'order_time' => 'require|number',
				'register_date' => 'require|number',
				'register_time' => 'require|number',
				'user_id' => 'require|number',
			),
			'update_register' => array(
				'register_status' => 'require|number',
				'doctor_id' => 'require|number',
				'patient_id' => 'require|number',
				'order_time' => 'require|number',
				'register_date' => 'require|number',
				'register_time' => 'require|number',
				'payment_amount' => 'require|number',
				'register_id' => 'require|number',
			),
			'cancel_register' => array(
			    'register_id' => 'require|number',
			    'user_id' => 'require|number',
			),
			'select_register' => array(
			    'patient_id' => 'require|number',
			    'page' => 'number|require'
			),
			'select_register_more' => array(
			    'register_id' => 'require|number',
			),
			'delete_register' => array(
			    'register_id' => 'require|number',
			)
		),
		'Test' => array(
			'add_test' => array(
				// 'test_status' => 'require|number',
				'patient_id' => 'require|number',
				// 'order_time' => 'require|number',
				'test_date' => 'require|number',
				'meal_id' => 'require|number',
				'user_id' => 'require|number'
			),
			'update_test' => array(
				'test_status' => 'require|number',
				'patient_id' => 'require|number',
				'order_time' => 'require|number',
				'test_date' => 'require|number',
				'test_id' => 'require|number',
			),
			'cancel_test' => array(
			    'test_id' => 'require|number',
			    'user_id' => 'require|number',
			),
			'select_test' => array(
			    'patient_id' => 'require|number',
			    'page' => 'number|require'
			),
			'select_test_more' => array(
			    'test_id' => 'require|number',
			),
			'delete_test' => array(
			    'test_id' => 'require|number',
			)
		),
		'Meal' => array(
			'add_meal' => array(
				'meal_name' => 'require',
				'meal_cost' => 'require|number',
				'meal_introduce' => 'require',
				'meal_type' => 'require|number'
			),
			'update_meal' => array(
				'meal_name' => 'require',
				'meal_cost' => 'require|number',
				'meal_introduce' => 'require',
				'meal_id' => 'require|number'
			),
			'select_meal' => array(
			    // 'meal_id' => 'require|number',
			    'page' => 'number|require'
			),
			'select_meal_more' => array(
			    'meal_id' => 'require|number',
			),
			'delete_meal' => array(
			    'meal_id' => 'require|number',
			)
		),
		'Project' => array(
			'add_project' => array(
				'project_name' => 'require',
				// 'project_introduce' => 'require',
				'meal_id' => 'require|number'
			),
			'update_project' => array(
				'project_name' => 'require',
				// 'project_introduce' => '',
				'meal_id' => 'require|number',
				'project_id' => 'require|number'
			),
			'select_project' => array(
			    'meal_id' => 'require|number',
			),
			'delete_project' => array(
			    'project_id' => 'require|number',
			)
		),
		'Schedul' => array(
			'add_schedul' => array(
				'schedul_date' => 'require',
				// 'schedul_cost' => 'require|number',
				'office_id' => 'require|number',
				'doctor_id' => 'require|number',
				'schedul_time' => 'require|number',
				'is_book' => 'require|number|length:1'
			),
			'update_schedul' => array(
				'schedul_date' => 'require',
				// 'schedul_cost' => 'require|number',
				'department_id' => 'require|number',
				'doctor_id' => 'number',
				'schedul_time' => 'require|number',
				'is_book' => 'require|number|length:1',
				'schedul_id' => 'require|number',
				'department_id' => 'require|number'
			),
			'select_schedul_web' => array(
			    'office_id' => 'require|number',
			),
			'select_schedul_miniapp' => array(
			    'doctor_id' => 'require|number',
				'schedul_date' => 'require|number'
			),
			'delete_schedul' => array(
			    'schedul_id' => 'require|number',
			)
		),
		'Code' => array(
			'send_code' => array (
				'code_phone' => 'require|number',
			),
			'check_code' => array (
				'code_phone' => 'require|number',
				'code_content' => 'require|number'
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
        // $this->check_token($this->request->param());
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
            $this->return_msg(401, 'token值为空');
        }
        $app_token = $arr['token'];
        unset($arr['token']);
        $service_token = '';
        foreach ($arr as $key => $value) {
            $service_token .= md5($value);
        }
        $service_token = md5('api_' . $service_token . '_api'); //服务器实时生成token

        if ($app_token !== $service_token) {
            $this->return_msg(401, 'token值不正确');
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