<?php 
namespace app\api\controller;
use think\Db;

class Test extends Common{

	public function add_test(){
		$data = $this->params;
		$test_date = $this->params['test_date'];
		$patient_id = $this->params['patient_id'];

		$res = db('test')
			   ->where('test_date',$test_date)
			   ->where('patient_id',$patient_id)
			   ->find();
		if($res){
			$this->return_msg(400,'预约体检失败,同一个就诊人同一天只能预约一个体检',$res);
		}
		$user_id = $data['user_id'];
		unset($data['user_id']);
		$data['test_status'] = 0;
		$data['order_time'] = time();
		$test_id = db('test')->insertGetId($data);
		if($test_id){
			$notice = [
				'notice_content' => '你已成功预约体检，点击查看详情',
				'notice_title' => '预约成功',
				'notice_type' => 1,
				'notice_time' => time(),
				'user_id' => $user_id,
				'test_id' => $test_id
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

	public function select_test(){
		$data = $this->params['patient_id'];
		$page = $this->params['page'];
		// $page = $this->params[]
		//将预约时间由时间戳转成普通时间
		$res = Db::table('test')
			   ->join('patient','test.patient_id = patient.patient_id')
			   ->join('meal','test.meal_id = meal.meal_id')
			   ->where('test.patient_id','=',$data)
			   ->page($page,10)
			   ->select();
		$array = ["周日","周一","周二","周三","周四","周五","周六"];
		foreach ($res as $key => $value) {
			$res[$key]['test_date'] = date('Y-m-d', $res[$key]['test_date']);
			$res[$key]['test_status'] = $this->turn_status($res[$key]['test_status']);
		}
		if(count($res) >= 0){
			$this->return_msg(200,'查询预约成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询预约失败',$res);
		}
	}

	public function select_test_more(){
		$data = $this->params['test_id'];
		//将预约时间由时间戳转成普通时间
		$res = Db::table('test')
			   ->join('patient','test.patient_id = patient.patient_id')
			   ->join('meal','test.meal_id = meal.meal_id')
			   ->where('test_id','=',$data)
			   ->find();
		if($res){
			$res['test_day'] = $this->turn_day($res['test_date']);
			$res['test_date'] = date('Y-m-d', $res['test_date']);
			$res['test_status'] = $this->turn_status($res['test_status']);
			$this->return_msg(200,'查询预约成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询预约失败',$res);
		}
	}

	public function update_test(){
		$data = $this->params;
		$res = db('test')->where('test_id',$data['test_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新预约成功',$res);
		}else{
			$this->return_msg(400,'更新预约失败',$res);
		}
	}

	//取消体检预约
	public function cancel_test(){
		$test_id = $this->params['test_id'];
		$user_id = $this->params['user_id'];
		$res = db('test')->where('test_id',$test_id)->find();
		if($res){
			if($res['test_status'] == 2){
				$this->return_msg(400,'取消预约失败,预约已过期',$res);
			}
			if($res['test_status'] == 1){
				$this->return_msg(400,'取消预约失败,预约已被取消,不能重复取消',$res);
			}
			if($res['test_status'] == 0){
				$res['test_status'] = 1;
				$res = db('test')->where('test_id',$res['test_id'])->update($res);
				//判断是否取消成功
				if($res){
					//添加消息提示
					$notice = [
						'notice_content' => '你已成功取消体检预约，点击查看详情',
						'notice_title' => '取消预约成功',
						'notice_type' => 1,
						'notice_time' => time(),
						'user_id' => $user_id,
						'test_id' => $test_id
					];
					$res = db('notice')->insertGetId($notice);
					if($res){
						$this->return_msg(200,'取消预约成功',$res);
					}else{
						$this->return_msg(400,'取消预约失败');
					}
				}else{
					$this->return_msg(400,'体检预约状态错误',$res);
				}
			}
		}else{
			$this->return_msg(400,'取消预约失败,体检预约不存在',$res);
		}
	}



	public function delete_test(){
		$data = $this->params;
		$res = db('test')->delete($data['test_id']);
		if($res){
			$this->return_msg(200,'删除预约成功',$res);
		}else{
			$this->return_msg(400,'删除预约失败',$res);
		}
	}
	
	//通过视图获取已经出了体检报告的体检记录
	public function select_test_list_view(){
		$data = $this->params;
		$patient_id = $this->params['patient_id'];
		$page = $data['page'] - 1;
		$sql = 'select * from select_test_list where patient_id = '.$patient_id.' and has_result = 1 limit '.($page*10).',10';
		// echo $sql;
		$res = Db::query($sql);
		if(count($res) >= 0){
			foreach($res as $key => $value){
				$res[$key]['test_status'] = $this->turn_status($res[$key]['test_status']);
				$res[$key]['test_date'] = date('Y-m-d',$res[$key]['test_date']);
			}
			$this->return_msg(200,'查询体检报告成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询体检报告失败');
		}
	}

	//以下是网页后台的端口
	public function select_test_list_admin(){
		$data = $this->params;
		$sql = $this->turn_sql($data,'test');
		$res = Db::query($sql);
		if(count($res) >= 0){
			foreach($res as $key => $value){
				$res[$key]['test_status'] = $this->turn_status($res[$key]['test_status']);
				$res[$key]['test_date'] = date('Y-m-d',$res[$key]['test_date']);
				$res[$key]['order_time'] = date('Y-m-d',$res[$key]['order_time']);
			}
			$this->return_msg(200,'查询体检记录成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询体检记录失败',$res);
		}
	}

	public function select_photo(){
		$data = $this->params;
		$res = db('photo')
				->where('test_id',$data['test_id'])
				->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询体检报告成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询体检报告失败');
		}
	}

	public function add_photo(){
		$data = $this->params;
		$res = db('photo')->insertGetId($data);
		if($res){
			$this->return_msg(200,'添加体检报告成功',$res);
		}else{
			$this->return_msg(400,'添加体检报告失败');
		}
	}

	public function delete_photo(){
		$data = $this->params;
		$res = db('photo')->where('photo_id',$data['photo_id'])->delete();
		if($res){
			$this->return_msg(200,'删除体检报告成功',$res);
		}else{
			$this->return_msg(400,'删除体检报告失败');
		}
	}

	
}