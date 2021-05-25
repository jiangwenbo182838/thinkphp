<?php
namespace app\admin\controller;

use think\facade\View;
use app\common\business\Category as CategoryBus;
use app\common\lib\Status as StatusLib;
class Category extends AdminBase
{
    public function index()
    {
        $pid = input("param.pid", 0, "intval");
        $data = [
            "pid" => $pid,
        ];
        try {
            $categorys = (new CategoryBus())->getLists($data, 5);
        }catch (\Exception $e) {
            $categorys = \app\common\lib\Arr::getPaginateDefaultData(5);
        }
//        halt($categorys);
        return View::fetch("", [
            "categorys" => $categorys,
            "pid" => $pid,
        ]);
    }
    public function add()
    {
        try {
            $categorys = (new CategoryBus())->getNormalCategorys();
        }catch (\Exception $e ) {
            $categorys = [];
        }

        return View::fetch("", [
            "categorys" => json_encode($categorys),
        ]);
    }

    /**
     * 添加逻辑
     * @return \think\response\Json
     */
    public function save() {
        $pid = input("param.pid", 0, "intval");
        $name = input("param.name", "", "trim");
        if(!request()->isPost()){
            return show(config("status.error"), "参数错误");
        }
        // 参数校验
        $data = [
            'pid' => $pid,
            'name' => $name,
        ];
        $validate = new \app\admin\validate\Category();
        if(!$validate->check($data)) {
            return show(config('status.error'), $validate->getError());
        }

        try {
            $result = (new CategoryBus())->add($data);
        } catch (\Exception $e) { //最好记录日志
            return show(config('status.error'), $e->getMessage());
        }
        if($result) {
            return show(config("status.success"), "OK");
        }
        return show(config("status.error"), "新增分类失败");
    }

    /**编辑逻辑
     * @return string|\think\response\Json
     */
    public function edit(){
        $id = input("param.id", 0, "intval");
        if (intval($id) < 1){
            return show(config('status.error'), "参数错误");
        }

        $category = (new CategoryBus())->getById($id);

        try {
            $categorys = (new CategoryBus())->getNormalCategorys();
        }catch (\Exception $e ) {
            $categorys = [];
        }

        return View::fetch("", [
            "categorys" => json_encode($categorys),
            "category" =>$category,
        ]);
    }
    public function update(){
        $id = input("param.id", "", "intval");
        if (!$id){
            return show(config('status.error'), "参数有误");
        }
        try {
            $res = (new CategoryBus())->save($id,['id'=>intval($id['id'])]);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($res){
            return show(config('status.success'), "修改成功");
        }else{
            return show(config('status.error'), "修改失败");
        }
    }

    /**排序
     * @return \think\response\Json
     */
    public function listorder() {
        $id = input("param.id", 0, "intval");
        $listorder = input("param.listorder", 0, "intval");
        // 加入validate验证机制处理
        if (!$id) {
            return show(config('status.error'), "参数错误");
        }

        try {
            $res = (new CategoryBus())->listorder($id, $listorder);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if($res) {
            return show(config('status.success'), "排序成功");
        } else {
            return show(config('status.error'), "排序失败");
        }
    }

    /**
     * 更新状态
     * @return \think\response\Json
     */
    public function status() {
//        halt(StatusLib::getTableStatus());
        $status = input("param.status", 0, "intval");
        $id = input("param.id", 0, "intval");
        // 加入validate验证机制处理 相关验证  判断合法性  0  1 99
        if (!$id || !in_array($status, StatusLib::getTableStatus())) {
            return show(config('status.error'), "参数错误");
        }

        try {
            $res = (new CategoryBus())->status($id, $status);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if($res) {
            return show(config('status.success'), "状态更新成功");
        } else {
            return show(config('status.error'), "状态更新失败");
        }
    }



    /*
     * 获取一级栏目的数据
     * @return \think\response\View
     */
    public function dialog() {
        // 获取正常的一级分类数据。
        $categorys = (new CategoryBus())->getNormalByPid();
        return view("", [
            "categorys" => json_encode($categorys),
        ]);
    }
    /**
     * 获取二级栏目数据
     * @return \think\response\Json
     */
    public function getByPid() {
        $pid = input("param.pid", 0, "intval");
        $categorys = (new CategoryBus())->getNormalByPid($pid);
        return show(config("status.success"), "OK", $categorys);
    }
}



