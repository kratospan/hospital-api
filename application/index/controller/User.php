<?php
namespace app\index\controller;

// use \think\Request;
// use \think\Validate;

class User {
        
    public function index() {
        // $data = [
        //     "num" => 123,
        //     "ppt" => '棒棒哒'
        // ];
        // return $data;
        // $request = Request::instance();
        // echo '请求方法:' . $request->method() . '<br/>';
        // echo '访问地址:' . $request->ip() . '<br/>';
        // echo '请求参数:';
        // dump($request->param());
        // echo '请求参数 : 仅包含name';
        // dump($request->only(['name']));
        // dump($request->except(['name']));
        // if($request->isGet()){
        //     echo '是get方法';
        // }
        // if($request->isPost()){
        //     echo '是Post方法';
        // }
        // if($request->isPut()){
        //     echo '是Put方法';
        // }
        // if($request->isDelete()){
        //     echo '是Delete方法';
        // }
        // $rule = [
        //     'name' => 'require|max:25',
        //     'age'  => 'number|between:1,120',
        //     'email' => 'email'
        // ];
        // $msg = [
        //     'name.require' => '请填写名称',
        //     'name.max' => '名称最多不能超过25个字符',
        //     'age.number' => '年龄必须是数字',
        //     'age.between' => '年龄只能在1-120之间',
        //     'email' => '邮件格式错误',
        // ];
        // $data = Input('post.');
        // $Validate = new Validate($rule,$msg);
        // $result = $Validate->check($data);
        // if(!$result){
        //     dump($Validate->getError());
        // }
        $res = Db::query('select version()');
        return $res;

    }
}
