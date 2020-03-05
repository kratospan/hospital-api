<?php 
namespace app\api\controller;

class Register extends Common{

	//添加挂号预约
	public function add_register(){
		$data = $this->params;
		$register_date = $this->params['register_date'];
		$patient_id = $this->params['patient_id'];

		//查询挂号是否重复
		$res = db('register')
			   ->where('register_date',$register_date)
			   ->where('patient_id',$patient_id)
			   ->find();
		if($res){
			$this->return_msg(400,'挂号预约失败,同一个就诊人同一天只能预约挂号一个时段',$res);
		}
		$user_id = $data['user_id'];
		unset($data['user_id']);
		$data['register_status'] = 0;
		$data['order_time'] = time();
		$res = db('register')->insertGetId($data);
		$test_id = $res;
		if($res){
			$notice = [
				'notice_content' => '你已成功预约挂号，点击查看详情',
				'notice_title' => '预约成功',
				'notice_type' => 0,
				'notice_time' => time(),
				'user_id' => $user_id,
				'test_id' => $res
			];
			$res = db('notice')->insertGetId($notice);
			if($res){
				$this->return_msg(200,'新增预约成功',$test_id);
			}else{
				$this->return_msg(400,'新增预约失败');
			}
		}else{
			$this->return_msg(400,'新增预约失败');
		}
	}

	public function select_register(){
		$data = $this->params['patient_id'];
		$page = $this->params['page'];
		$res = db('register')
			   ->join('doctor','register.doctor_id = doctor.doctor_id')
			   ->join('office','doctor.office_id = office.office_id')
			   ->join('department','department.department_id = doctor.department_id')
			   ->join('patient','patient.patient_id = register.patient_id')
			   ->where('register.patient_id',$data)
			   ->order('register_date','desc')
			   ->page($page,10)
			   ->select();
		if(count($res) >= 0){
			foreach ($res as $key => $value) {
				$res[$key]['register_time'] = $this->turn_time($res[$key]['register_time']);
				// $res['register_day'] = date('w',$res['register_date']);
				$res[$key]['register_day'] = $this->turn_day($res[$key]['register_date']);
				$res[$key]['register_date'] = date('Y-m-d',$res[$key]['register_date']);
				$res[$key]['register_status'] = $this->turn_status($res[$key]['register_status']);
				$res[$key]['doctor_title'] = $this->turn_title($res[$key]['doctor_title']);
			}
			$this->return_msg(200,'查询预约详细成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询预约详细失败',$res);
		}
	}

	public function select_register_more(){
		$data = $this->params['register_id'];
		$res = db('register')
			   ->join('doctor','register.doctor_id = doctor.doctor_id')
			   ->join('office','doctor.office_id = office.office_id')
			   ->join('department','department.department_id = doctor.department_id')
			   ->join('patient','patient.patient_id = register.patient_id')
			   ->where('register_id',$data)
			   ->find();
		if($res){
			$res['register_time'] = $this->turn_time($res['register_time']);
			// $res['register_day'] = date('w',$res['register_date']);
			$res['register_day'] = $this->turn_day($res['register_date']);
			$res['register_date'] = date('Y-m-d',$res['register_date']);
			$res['register_status'] = $this->turn_status($res['register_status']);
			$res['doctor_title'] = $this->turn_title($res['doctor_title']);
			$this->return_msg(200,'查询预约详细成功',$res);
		}else{
			$this->return_msg(400,'查询预约详细失败',$res);
		}
	}

	public function update_register(){
		$data = $this->params;
		$res = db('register')->where('register_id',$data['register_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新预约成功',$res);
		}else{
			$this->return_msg(400,'更新预约失败',$res);
		}
	}

	//取消挂号预约
	public function cancel_register(){
		$register_id = $this->params['register_id'];
		$user_id = $this->params['user_id'];
		$res = db('register')->where('register_id',$register_id)->find();
		if($res){
			if($res['register_status'] == 2){
				$this->return_msg(400,'取消预约失败,预约已过期',$res);
			}
			if($res['register_status'] == 1){
				$this->return_msg(400,'取消预约失败,预约已被取消,不能重复取消',$res);
			}
			if($res['register_status'] == 0){
				$res['register_status'] = 1;
				$res = db('register')->where('register_id',$res['register_id'])->update($res);
				//判断是否取消成功
				if($res){
					//添加消息提示
					$notice = [
						'notice_content' => '你已成功取消挂号预约，点击查看详情',
						'notice_title' => '取消预约成功',
						'notice_type' => 0,
						'notice_time' => time(),
						'user_id' => $user_id,
						'register_id' => $register_id
					];
					$res = db('notice')->insertGetId($notice);
					if($res){
						$this->return_msg(200,'取消预约成功',$res);
					}else{
						$this->return_msg(400,'取消预约失败');
					}
				}else{
					$this->return_msg(400,'挂号预约状态错误',$res);
				}
			}
		}else{
			$this->return_msg(400,'取消预约失败,挂号预约不存在',$res);
		}
	}

	public function delete_register(){
		$data = $this->params;
		$res = db('register')->delete($data['register_id']);
		if($res){
			$this->return_msg(200,'删除预约成功',$res);
		}else{
			$this->return_msg(400,'删除预约失败',$res);
		}
	}
}