<?php
namespace app\api\controller;
namespace app\api\controller;

use think\Validate;
use think\Controller;
use think\Request;

class Common extends Controller {

    protected $request; //用来处理参数
    protected $validater; //用来验证数据、参数
    protected $params; //过滤后符合要求的参数
    //定义参数过滤的规则
    protected $rules = array(
    	'User' => array(
    		'login' =>array(
    			'user_name' => ['require','chsDash','max'=>20],
    			'user_pwd' => 'require|length:32'
    		)
    	),
        'Patient' => array(
            'add_patient' => array(
                // 'patient_id' => 'require|number',
                'patient_name' => 'require|chs',
                'patient_card' => 'require',
                'patient_phone' => 'require|number|length:11',
                'patient_sex' => 'require|number|length:1',
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
        )
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
    	$this->validater = new Validate($rule);
    	if(!$this->validater->check($arr)){
    		$this->return_msg(400,$this->validater->getError());
    	}
    	return $arr;
    }

    //返还处理信息
    public function return_msg($code, $msg = '', $data = []) {
        $return_data['code'] = $code;
        $return_data['msg']  = $msg;
        $return_data['data'] = $data;

        echo json_encode($return_data);
        die;
    }
}