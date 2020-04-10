<?php 
namespace app\admin\controller;

use think\Db;

class Schedul extends Common{
	//添加排班信息
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
	//查询排班信息
	public function select_schedul(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from select_schedul";
		//判断传递的数组是否有Page属性
		if(isset($data['page'])){
			$page = $data['page'];
			if($page < 1){
				$this->return_msg(400,'页码数不正确，请填入正确的页码数');
			}
			$page = $data['page'] - 1;
			unset($data['page']);
			$sql = $sql.$this->turn_special_sql($data)." ORDER BY doctor_id,schedul_date,schedul_time".' limit '.($page*15).',15';
		}else{
			$sql = $sql.$this->turn_special_sql($data);
		}
		$arr = ['正常排班','已被预约'];
		$res = Db::query($sql);
		if(count($res) >= 0){
			foreach ($res as $key => $value) {
				$res[$key]['schedul_time'] = $this->turn_time($res[$key]['schedul_time']);
				$res[$key]['schedul_date'] = date('Y-m-d',$res[$key]['schedul_date']);
				$res[$key]['is_book'] = $arr[$res[$key]['is_book']];
			}
			//获取查询数据的总数
			$num = Db::query('SELECT FOUND_ROWS()');
			$this->return_msg(200,'查询排班成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询排班失败',$res);
		}
	}
	//更新排班信息
	public function update_schedul(){
		$data = $this->params;
		$res = db('schedul')->where('schedul_id',$data['schedul_id'])->update($data);
		if($res){
			$this->return_msg(200,'修改排班成功',$res);
		}else{
			$this->return_msg(400,'修改排班失败',$res);
		}
	}
	//删除排班信息
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