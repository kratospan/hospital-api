<?php 
namespace app\admin\controller;
use think\Db;

class Test extends Common{
	//查询体检数据
	public function select_test(){
		$data = $this->params;
		$page = '';
		$sql = "select SQL_CALC_FOUND_ROWS* from select_test";
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
			foreach($res as $key => $value){
				$res[$key]['test_status'] = $this->turn_status($res[$key]['test_status']);
				$res[$key]['test_date'] = date('Y-m-d',$res[$key]['test_date']);
				$res[$key]['order_time'] = date('Y-m-d',$res[$key]['order_time']);
			}
			$this->return_msg(200,'查询体检成功',$res,$num[0]['FOUND_ROWS()']);
			
		}else{
			$this->return_msg(400,'查询体检失败',$res);
		}
	}
	//查询体检报告
	public function select_photo(){
		$data = $this->params;
		$res = db('photo')
				->where('test_id',$data['test_id'])
				->select();
		if(count($res) >= 0){
			$this->return_msg(200,'查询体检报告成功',$res,count($res));
		}else{
			$this->return_msg(400,'查询体检报告失败');
		}
	}
	//添加体检报告
	public function add_photo(){
		$data = $this->params;
		$test_id = $this->params['test_id'];
		$res = db('photo')->insertGetId($data);
		if($res){
			// $res = db('test')->where('test_id',$test_id)->update(['has_result' => '1']);
			$res = db('test')->where('test_id',$test_id)->find();
			if($res['has_result'] == 0){
				$res = db('test')->where('test_id',$test_id)->update(['has_result' => '1']);
				if($res){
					$this->return_msg(200,'添加体检报告成功',$res);
				}else{
					$this->return_msg(400,'添加体检报告失败');
				}
			}else{
				$this->return_msg(200,'添加体检报告成功',$res);
			}
		}else{
			$this->return_msg(400,'添加体检报告失败');
		}
	}
	//删除体检报告
	public function delete_photo(){
		$data = $this->params;
		$res = db('photo')->where('photo_id',$data['photo_id'])->delete();
		if($res){
			$this->return_msg(200,'删除体检报告成功',$res);
		}else{
			$this->return_msg(400,'删除体检报告失败');
		}
	}
}