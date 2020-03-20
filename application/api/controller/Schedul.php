<?php 
namespace app\api\controller;

use think\Db;

class Schedul extends Common{

	public function add_schedul(){
		$data = $this->params;
		$date = $data['schedul_date'];
		$doctor = $data['doctor_id'];
		$time = $data['schedul_time'];
		$res = db('schedul')
			   ->where('schedul_date',$date)
			   ->where('doctor_id',$doctor)
			   ->select();
		if(count($res) > 0){
			foreach($res as $key => $value){
				if($time == $res[$key]['schedul_time']){
					$this->return_msg(400,'该医生当天已有相同的时间段排班',$res);
				}
			};
		}	
		
		$res = db('schedul')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增排班成功',$res);
		}else{
			$this->return_msg(400,'新增排班失败');
		}
	}

	public function select_schedul_web(){
		$data = $this->params['office_id'];
		$res = db('schedul')->where('office_id',$data)->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询排班成功',$res);
		}else{
			$this->return_msg(400,'查询排班失败',$res);
		}
	}
	
	public function select_schedul_miniapp(){
		// $data = $this->params;
		$doctor_id = $this->params['doctor_id'];
		$schedul_date = $this->params['schedul_date'];
		$res = Db::table('schedul')
				->join('doctor','schedul.doctor_id = doctor.doctor_id')
				->where('schedul.doctor_id','=',$doctor_id)
				->where('schedul_date','=',$schedul_date)
				->select();
		if(count($res) >= 0){
			$schedul2 = [
				'1' => 3,
				'2' => 3,
				'3' => 3,
				'4' => 3,
				'5' => 3,
				'6' => 3,
			];
			foreach ($res as $key => $value) {
				$schedul2[$res[$key]['schedul_time']] = $res[$key]['is_book'];
			}
			
			// $schedul['data'] = $res;
			$schedul['am'] = [
				'1' => $schedul2[1],
				'2' => $schedul2[2],
				'3' => $schedul2[3],
			];
			$schedul['pm'] = [
				'4' => $schedul2[4],
				'5' => $schedul2[5],
				'6' => $schedul2[6],
			];
			if(count($res) > 0){
				$schedul['data'] = $res[0];
			}
			$this->return_msg(200,'查询排班成功',$schedul);
		}else{
			$this->return_msg(400,'查询排班失败',$res);
		}
	}

	public function update_schedul(){
		$data = $this->params;
		$res = db('schedul')->where('schedul_id',$data['schedul_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新排班成功',$res);
		}else{
			$this->return_msg(400,'更新排班失败',$res);
		}
	}

	public function delete_schedul(){
		$data = $this->params;
		$res = db('schedul')->delete($data['schedul_id']);
		if($res){
			$this->return_msg(200,'删除排班成功',$res);
		}else{
			$this->return_msg(400,'删除排班失败',$res);
		}
	}
	
	//获取未来7天的排班日期
	public function get_schedul_date(){
		$data = $this->params;
		$date = date("Y-m-d",time());
		$now = time();
		$timestamp = strtotime($date);
		$dateList = [];
		$array = ['日','一','二','三','四','五','六'];
		for($i = 0;$i < 7;$i++){
			$timestamp = $timestamp + 60*60*24;
			$dateList[$i] = [
				"day" => $array[date('w',$timestamp)],
				"date" =>date('d',$timestamp),
				"dateTime" => $timestamp
			];
		}
		$this->return_msg(200,'获取排班日期成功',$dateList);
	}

	//以下是网页后台的api
	public function select_schedul_list_admin(){
		$data = $this->params;
		$sql = "SELECT o.office_id,
		               o.office_name,
                       d.doctor_name,
                       d.doctor_id,
                       s.schedul_id,
                       s.schedul_date,
                       s.schedul_time,
                       s.is_book
				from schedul s 
				INNER JOIN office o 
				on o.office_id = s.office_id 
				INNER JOIN doctor d 
				on d.doctor_id = s.doctor_id ";
		$sql = $sql.$this->turn_special_sql($data)." ORDER BY doctor_id,schedul_date,schedul_time";
		$res = Db::query($sql);
		$arr = ['正常排班','已被预约'];
		if(count($res) >= 0){
			foreach($res as $key => $value){
				$res[$key]['schedul_time'] = $this->turn_time($res[$key]['schedul_time']);
				$res[$key]['schedul_date'] = date('Y-m-d',$res[$key]['schedul_date']);
				$res[$key]['is_book'] = $arr[$res[$key]['is_book']];
			}
			// echo $sql;
			$this->return_msg(200,'查询排班记录成功',$res,count($res));
		}
		else{
			$this->return_msg(400,'查询排班记录失败',$res);
		}
	}
	
}