<?php 
namespace app\admin\controller;

use think\Db;

class Schedul extends Common{

	public function add_schedul(){
		$data = $this->params;
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
}