<?php 
namespace app\admin\controller;
use think\Db;
class Register extends Common{
	//查询挂号信息
	public function select_register(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from select_register";
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
			foreach($res as $key => $value){
				$res[$key]['register_time'] = $this->turn_time($res[$key]['register_time']);
				$res[$key]['register_date'] = date('Y-m-d',$res[$key]['register_date']);
				$res[$key]['order_time'] = date('Y-m-d',$res[$key]['order_time']);
				$res[$key]['register_status'] = $this->turn_status($res[$key]['register_status']);
			}
			//获取查询数据的总数
			$num = Db::query('SELECT FOUND_ROWS()');
			$this->return_msg(200,'查询挂号信息成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询挂号信息失败',$res);
		}
	}
	
	
	//修改挂号信息
	public function update_register(){
		$data = $this->params;
		$status = $data['register_status'];
		$res = db('register')->where('register_id',$data['register_id'])->update(['register_status' => $status]);
		if($res){
			$this->return_msg(200,'修改预约成功',$res);
		}else{
			$this->return_msg(400,'修改预约失败(或是因为提交的数据没有修改)',$res);
		}
	}

	//删除挂号信息
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