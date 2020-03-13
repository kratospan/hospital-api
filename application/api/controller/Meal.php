<?php 
namespace app\api\controller;
use think\Db;

class Meal extends Common{

	public function add_meal(){
		$data = $this->params;
		$res = db('meal')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增套餐成功',$res);
		}else{
			$this->return_msg(400,'新增套餐失败');
		}
	}

	public function select_meal(){
		// $data = $this->params['patient_id'];
		$page = $this->params['page'];
		$res = db('meal')->page($page,10)->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询套餐成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询套餐失败',$res);
		}
	}

	public function select_meal_more(){
		$data = $this->params['meal_id'];
		$res = db('meal')->where('meal_id',$data)->find();
		if(count($res) >= 0){
			$this->return_msg(200,'查询套餐成功',$res);
		}else{
			$this->return_msg(400,'查询套餐失败',$res);
		}
	}

	public function update_meal(){
		$data = $this->params;
		$res = db('meal')->where('meal_id',$data['meal_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新套餐成功',$res);
		}else{
			$this->return_msg(400,'更新套餐失败(有可能是数据没有修改)',$res);
		}
	}

	public function delete_meal(){
		$data = $this->params;
		$res = db('meal')->delete($data['meal_id']);
		if($res){
			$this->return_msg(200,'删除套餐成功',$res);
		}else{
			$this->return_msg(400,'删除套餐失败',$res);
		}
	}

	//以下全部是网页后台的接口
	public function select_meal_list_admin(){
		$data = $this->params;
		$sql = $this->turn_sql($data,'meal');
		// echo $sql;
		$res = Db::query($sql);
		if(count($res) >= 0){
			$this->return_msg(200,'查询体检记录成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询体检记录失败',$res);
		}
	}
}