<?php
namespace app\admin\controller;

class Notice extends Common {
	public function add_notice(){
		$data = $this->params;
		$res = db('notice')->insertGetId($data);
		if($res){
			$this->return_msg(200,'新增通知成功',$res);
		}else{
			$this->return_msg(400,'新增通知失败');
		}
	}

	public function select_notice(){
		$data = $this->params['notice_id'];
		$res = db('notice')->where('notice_id',$data)->find();
		if(count($res) >= 0){
			$this->return_msg(200,'查询通知成功',$res);
		}else{
			$this->return_msg(400,'查询通知失败',$res);
		}
	}
	
	public function select_notice_list(){
		$data = $this->params['user_id'];
		// $page = $this->params['page'];
		$res = db('notice')->where('user_id',$data)->order('notice_id','desc')->select();
		foreach ($res as $key => $value) {
			$x = time() - $res[$key]['notice_time'];
			if($x < 60){
				$res[$key]['notice_time'] = $x.'秒前';
			}
			if($x > 60 && $x < 60*60){
				$res[$key]['notice_time'] = (int)($x/60+1).'分钟前';
			}
			if($x > 60*60){
				$res[$key]['notice_time'] = date('Y-m-d', $res[$key]['notice_time']);
			}
		}
		if(count($res) >= 0){
			$this->return_msg(200,'查询通知成功',$res);
		}else{
			$this->return_msg(400,'查询通知失败',$res);
		}
	}
	
	public function update_notice(){
		$data = $this->params;
		$res = db('notice')->where('notice_id',$data['notice_id'])->update($data);
		if($res){
			$this->return_msg(200,'更新通知成功',$res);
		}else{
			$this->return_msg(400,'更新通知失败',$res);
		}
	}
	
	public function delete_notice(){
		$data = $this->params;
		$res = db('notice')->delete($data['notice_id']);
		if($res){
			$this->return_msg(200,'删除通知成功',$res);
		}else{
			$this->return_msg(400,'删除通知失败',$res);
		}
	}
}
