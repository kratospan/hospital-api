<?php 
namespace app\api\controller;
use think\Db;

class Doctor extends Common{
	public function add_doctor(){
		$data = $this->params;
		$res = db('doctor')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增医生成功',$res);
		}else{
			$this->return_msg(400,'新增医生失败');
		}
	}
	
	public function select_doctor(){
		$data = $this->params['doctor_id'];
		$res = Db::table('doctor')
			   ->join('department','department.department_id = doctor.department_id')
			   ->where('doctor_id',$data)
			   ->find();
		$array = ['主任医师','副主任医师','主治医师','住院医师'];
		$res['doctor_title'] = $array[$res['doctor_title']];
		if($res){
			$this->return_msg(200,'查询医生成功',$res);
		}else{
			$this->return_msg(400,'查询医生失败',$res);
		}
	}

	public function select_doctor_list(){
		$data = $this->params['office_id'];
		$page = $this->params['page'];
		$res = db('doctor')->where('office_id',$data)->page($page,10)->select();
		if(count($res) >= 0){
			foreach ($res as $key => $value) {
				$res[$key]['doctor_title'] = $this->turn_title($res[$key]['doctor_title']);
			}
			$this->return_msg(200,'查询医生成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询医生失败',$res);
		}
	}
	
	public function update_doctor(){
		$data = $this->params;
		$res = db('doctor')->where('doctor_id',$data['doctor_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新医生成功',$res);
		}else{
			$this->return_msg(400,'更新医生失败',$res);
		}
	}
	
	public function delete_doctor(){
		$data = $this->params;
		$res = db('doctor')->delete($data['doctor_id']);
		if($res){
			$this->return_msg(200,'删除医生成功',$res);
		}else{
			$this->return_msg(400,'删除医生失败',$res);
		}
	}

	//网页管理后端接口

	//获取所有的医生信息
	public function select_doctor_list_admin(){
		$data = $this->params;
		$sql = "SELECT o.office_id,o.office_name,d1.doctor_name,d1.doctor_sex,d1.doctor_title,d1.doctor_introduce,d1.doctor_payment,d1.doctor_good,d1.doctor_id,d2.department_name,d1.department_id from doctor d1 INNER JOIN office o on o.office_id = d1.office_id INNER JOIN department d2 on d1.department_id = d2.department_id ";
		$sql = $sql.$this->turn_special_sql($data);
		// echo $sql;
		$res = Db::query($sql);
		if(count($res) >= 0){
			foreach ($res as $key => $value) {
				$res[$key]['doctor_title'] = $this->turn_title($res[$key]['doctor_title']);
				$res[$key]['doctor_sex'] = $this->turn_sex($res[$key]['doctor_sex']);
			}
			$this->return_msg(200,'查询医生成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询医生失败',$res);
		}
	}
	
	//根据科室ID查询对应的医生
	public function select_doctor_list_by(){
		$data = $this->params['office_id'];
		// $page = $this->params['page'];
		$res = db('doctor')->where('office_id',$data)->select();
		if(count($res) >= 0){
			foreach ($res as $key => $value) {
				$res[$key]['doctor_title'] = $this->turn_title($res[$key]['doctor_title']);
			}
			$this->return_msg(200,'查询医生成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询医生失败',$res);
		}
	}
	
	//以下是通过视图来查询的
	public function select_doctor_list_view(){
		$data = $this->params;
		$page = $data['page'];
		if($page < 1){
			$this->return_msg(400,'页码不能小于1');
		}
		$page = $data['page'] - 1;
		unset($data['page']);
		$sql = "select SQL_CALC_FOUND_ROWS* from select_doctor";
		$sql = $sql.$this->turn_special_sql($data).' limit '.($page*15).',15';
		// echo $sql;
		$res = Db::query($sql);
		if(count($res) >= 0){
			foreach ($res as $key => $value) {
				$res[$key]['doctor_title'] = $this->turn_title($res[$key]['doctor_title']);
				$res[$key]['doctor_sex'] = $this->turn_sex($res[$key]['doctor_sex']);
			}
			//获取查询数据的总数
			$num = Db::query('SELECT FOUND_ROWS()');
			$this->return_msg(200,'查询医生成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询医生失败',$res);
		}
	}
}