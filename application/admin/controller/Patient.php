<?php 
namespace app\admin\controller;
use think\Db;
class Patient extends Common{
	//查询就诊人信息
	public function select_patient(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from patient";
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
				$res[$key]['patient_sex'] = $this->turn_sex($res[$key]['patient_sex']);
			}
			//获取查询数据的总数
			$num = Db::query('SELECT FOUND_ROWS()');
			$this->return_msg(200,'查询就诊人成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询就诊人失败',$res);
		}
	}
	//更新就诊人信息
	public function update_patient(){
		$data = $this->params;
		$res = db('patient')->where('patient_id',$data['patient_id'])->update($data);
		// if($res){
		// 	$this->return_msg(200,'更新就诊人成功',$res);
		// }else{
		// 	$this->return_msg(400,'更新就诊人失败',$res);
		// }
		if($res === false){
			$this->return_msg(400,'更新就诊人失败',$res);
		}else{
			$this->return_msg(200,'更新就诊人成功',$res);
		}
	}
	//删除就诊人信息
	public function delete_patient(){
		$data = $this->params;
		$res = db('patient')->delete($data['patient_id']);
		if($res){
			$this->return_msg(200,'删除就诊人成功',$res);
		}else{
			$this->return_msg(400,'删除就诊人失败',$res);
		}
	}
	//添加就诊人
	public function add_patient(){
		$data = $this->params;
		$res = db('patient')->insertGetId($data);
		if($res){
			$this->return_msg(200,'添加就诊人成功',$res);
		}else{
			$this->return_msg(400,'新增就诊人失败');
		}
	}
}