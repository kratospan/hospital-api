<?php 
namespace app\admin\controller;
use think\Db;

class Testing extends Common{
	
	public function random_add_doctor(){
		
		//随机获取部门ID
		// $department = db('department')->select();
		// if(count($department) > 0){
		// 	$array = [];
		// 	foreach($department as $key => $value){
		// 		$array[$key] = $department[$key]['department_id'];
		// 	}
		// 	$department = $array;
		// 	// $department_id = $department[rand(0,count($department) - 1)];
			
		// 	// $this->return_msg(200,'查询部门列表成功',$department,count($department));
		// }else{
		// 	$this->return_msg(400,'查询部门列表失败',$department);
		// }
		$department_id = 4;
		
		//根据随机获取的部门ID，随机获取科室ID
		$office = db('office')->where('department_id',$department_id)->select();
		if(count($office) > 0){
			$array = [];
			foreach($office as $key => $value){
				$array[$key] = $office[$key]['office_id'];
			}
			$office = $array;
			$office_id = $office[rand(0,count($office) - 1)];
			// $this->return_msg(200,'查询部门列表成功',$office,count($office));
		}else{
			return 0;
		}
		
		
		$title = ['主任医师','副主任医师','主治医师','住院医师'];
		
		$sex = rand(0,1);
		$title = rand(0,3);
		
		$first_name = ['李','王','张','刘','陈','杨','赵','黄','周','吴','徐','孙','胡','朱','高','林','何','郭','马','罗','梁','宋','郑','谢','韩','唐','冯','于','董','萧','程','曹','袁','邓','许','傅','沈','曾','彭','苏','卢','蒋','蔡','贾','丁','魏','薛','叶','阎','余','潘','杜','戴','夏','钟','汪','田','任','姜','范','方','石','姚','谭','廖','邹','熊','金','陆','郝','孔','白','崔','康','毛','邱','秦','江','顾','侯','邵','孟','龙','万','段','漕','钱','汤','尹','黎','易','常','武','乔','贺','赖','龚','文'];
		$first_name = $first_name[rand(0,count($first_name) - 1)];
		$family_name = ['永寿','子平','烨然','元白','乐成','勇毅','自珍','和璧','明远','靖琪','光熙','兴德','向阳','高达','巍然','雨星','明俊','睿诚','蕴和','浩气','以旋','晓绿','宛菡','元灵','芷若','笑萍','格格','怡畅','梦松','经文','晓绿','欣玉','泓茹','紫玉','平蝶','米雪','新之','晗晗','茹云','瑜英'];
		$family_name = $family_name[rand(0,count($family_name) - 1)];
		$data = array(
			'doctor_sex' => $sex,
			'doctor_title' => $title,
			'office_id' => $office_id,
			'department_id' => $department_id,
			'doctor_name' => $first_name.$family_name,
			'doctor_good'=> $first_name.'医生擅长很多门技术，掌握核心方式'
		);
		
		// var_dump($data);
		
		$res = db('doctor')->insertGetId($data);
		if($res){
			// $this->return_msg(200,'随机添加医生成功',$res);
			return 1;
		}else{
			// $this->return_msg(200,'随机添加医生成功',$res);
			return 0;
		}
	}
	
	public function add_doctor(){
		$num = 0;
		for($i = 0;$i < 10; $i++){
			$num = $num + $this->random_add_doctor();
		}
		$this->return_msg(200,'添加成功,一共添加了'.$num.'条数据');
	}
}