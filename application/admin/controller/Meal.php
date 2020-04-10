<?php 
namespace app\admin\controller;
use think\Db;
class Meal extends Common{
	//查询套餐信息
	public function select_meal(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from meal";
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
			//获取查询数据的总数
			$num = Db::query('SELECT FOUND_ROWS()');
			$this->return_msg(200,'查询套餐成功',$res,$num[0]['FOUND_ROWS()']);
		}else{
			$this->return_msg(400,'查询套餐失败',$res);
		}
	}
	//添加套餐信息
	public function add_meal(){
		$data = $this->params;
		$res = db('meal')->insertGetId($data);
		if($res){
			$this->return_msg(200,'添加套餐成功',$res);
		}else{
			$this->return_msg(400,'添加套餐失败');
		}
	}
	//修改套餐信息
	public function update_meal(){
		$data = $this->params;
		$res = db('meal')->where('meal_id',$data['meal_id'])->update($data);
		if($res === false){
			$this->return_msg(400,'修改套餐失败',$res);
		}else{
			$this->return_msg(200,'修改套餐成功',$res);
		}
	}
	//删除套餐信息
	public function delete_meal(){
		$data = $this->params;
		$res = db('meal')->delete($data['meal_id']);
		if($res){
			$this->return_msg(200,'删除套餐成功',$res);
		}else{
			$this->return_msg(400,'删除套餐失败',$res);
		}
	}
}