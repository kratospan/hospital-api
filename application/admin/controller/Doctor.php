<?php 
namespace app\admin\controller;
use think\Db;

class Doctor extends Common{
	
	//添加医生
	public function add_doctor(){
		$data = $this->params;
		$res = db('doctor')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增医生成功',$res);
		}else{
			$this->return_msg(400,'新增医生失败');
		}
	}
	
	//根据视图查询医生
	public function select_doctor(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from select_doctor";
		//判断传递的数组是否有Page属性
		if(isset($data['page'])){
			$page = $data['page'];
			if($page < 1){
				$this->return_msg(400,'页码数不正确，请填入正确的页码数');
			}
			$page = $data['page'] - 1;
			unset($data['page']);
			$sql = $sql.$this->turn_special_sql($data).' limit '.($page*15).',15';
		}else{
			$sql = $sql.$this->turn_special_sql($data);
		}
		
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
	
	//根据科室ID查询对应的医生
	public function select_doctor_by(){
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
	
	//更新医生
	public function update_doctor(){
		$data = $this->params;
		$res = db('doctor')->where('doctor_id',$data['doctor_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新医生成功',$res);
		}else{
			$this->return_msg(400,'更新医生失败',$res);
		}
	}
	
	//删除医生
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