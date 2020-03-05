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
}