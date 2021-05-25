<?php
namespace app\admin\controller;
use app\common\business\Goods as GoodsBis;
use app\common\lib\Status as StatusLib;
class Goods extends AdminBase {
    public function index() {
        $data = [];
        $title = input("param.title", "", "trim");
        $time = input("param.time", "", "trim");
        if(!empty($title)) {
            $data['title'] = $title;
        }
        if(!empty($time)) {
            $data['create_time'] = explode(" - ", $time);
        }

        $goods = (new GoodsBis())->getLists($data, 3);
//        halt($goods);
        return view("", [
            "goods" => $goods,
        ]);
    }

    public function add() {
        return view();
    }

    /**
     * 新增逻辑
     * @return \think\response\Json
     */
    public function save() {
        // 判断是否为post请求， 也可以通过在路由中做配置支持post即可。
        if(!$this->request->isPost()) {
            return show(config('status.error'), "参数不合法");
        }
        // validate验证机制自行验证参数, 并且严格判断数据类型。
        $data = input("param.");
//        halt($data);
        $check = $this->request->checkToken('__token__');
        if(!$check){
            return show(config('status.error'), "非法请求");
        }

        // 数据处理 = > 基于 我们得验证成功之后
        $data['category_path_id'] = $data['category_id'];
        $result = explode(",", $data['category_path_id']);
        $data['category_id'] = end($result);

        $res = (new GoodsBis())->insertData($data);

        if(!$res) {
            return show(config('status.error'), "商品新增失败");
        }
        return show(config('status.success'), "商品新增成功");
    }

    /**商品列表排序
     * @return \think\response\Json|void
     */
    public function listorder() {
        $id = input("param.id", 0, "intval");
        $listorder = input("param.listorder", 0, "intval");
//        halt($id);
        // 加入validate验证机制处理
        if (!$id) {
            return show(config('status.error'), "参数错误");
        }

        try {
            $res = (new GoodsBis())->listorder($id, $listorder);
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
     * 更新商品列表状态
     * @return \think\response\Json
     */
    public function status() {
        $status = input("param.status", 0, "intval");
        $id = input("param.id", 0, "intval");
        // 加入validate验证机制处理 相关验证  判断合法性  0  1 99
        if (!$id || !in_array($status, StatusLib::getTableStatus())) {
            return show(config('status.error'), "参数错误");
        }

        try {
            $res = (new GoodsBis())->status($id, $status);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if($res) {
            return show(config('status.success'), "状态更新成功");
        } else {
            return show(config('status.error'), "状态更新失败");
        }
    }
    /**
     * 商品列表是否推荐
     * @return \think\response\Json
     */
    public function isindexrecommend() {
        $isindexrecommend = input("param.is_index_recommend", 0, "intval");
        $id = input("param.id", 0, "intval");
        // 加入validate验证机制处理 相关验证  判断合法性  0  1 99
        if (!$id || !in_array($isindexrecommend, StatusLib::getTableStatus())) {
            return show(config('status.error'), "参数错误");
        }

        try {
            $res = (new GoodsBis())->statusrecommend($id, $isindexrecommend);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if($res) {
            return show(config('status.success'), "状态更新成功");
        } else {
            return show(config('status.error'), "状态更新失败");
        }
    }


}