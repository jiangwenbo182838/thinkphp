<?php

namespace app\common\business;
use app\common\model\mysql\Category as CategoryModel;
class Category {
    public $model = null;
    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    /**添加分类
     * @param $data
     * @return mixed
     * @throws \think\Exception
     */
    public function add($data) {
        $data['status'] = config("status.mysql.table_normal");
//        $name = $data['name'];

        try {
            $this->model->save($data);
        }catch (\Exception $e) {
            throw  new \think\Exception("服务内部异常");
        }
        return $this->model->id;
    }

    /**查询逻辑分类
     * @return array|\think\Collection
     */
    public function getNormalCategorys() {
        $field = "id, name, pid";
        $categorys = $this->model->getNormalCategorys($field);
        if(!$categorys) {
            return $categorys;
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

//    public function getNormalAllCategorys() {
//        $field = "id as category_id, name, pid";
//        $categorys = $this->model->getNormalCategorys($field);
//        if(!$categorys) {
//            return $categorys;
//        }
//        $categorys = $categorys->toArray();
//        return $categorys;
//    }
//
    /**
     * 查询一级分类并显示子栏目条数
     * @param $data
     * @param $num
     * @return array
     */
    public function getLists($data, $num) {
        $list = $this->model->getLists($data, $num);
        if(!$list) {
            return [];
        }

        $result = $list->toArray();
        $result['render'] = $list->render();

        // 思路： 第一步拿到列表中id 第二步：in mysql 求count  第三步：把count填充到列表页中
        $pids = array_column($result['data'], "id");
        if($pids) {
            $idCountResult = $this->model->getChildCountInPids(['pid' => $pids]);
            $idCountResult = $idCountResult->toArray(); //  如果没有的话会返回空数组

            $idCounts = [];
            // 第一种方式
            foreach($idCountResult as $countResult) {
                $idCounts[$countResult['pid']] = $countResult['count'];
            }
        }
        if($result['data']) {
            foreach($result['data'] as $k => $value) {
                /// $a ?? 0 等同于 isset($a) ? $a : 0。
                $result['data'][$k]['childCount'] = $idCounts[$value['id']] ?? 0;
            }
        }

        return $result;
    }

    /**
     * 根据id获取某一条记录
     * @param $id
     * @return array
     */
    public function getById($id) {
        $result = $this->model->find($id);
        if(empty($result)) {
            return [];
        }
        $result = $result->toArray();
        return $result;
    }

    /**
     * 排序bis -
     * @param $id
     * @param $listorder
     * @return bool
     * @throws \think\Exception
     */
    public function listorder($id, $listorder) {
        // 查询 id这条数据是否存在
        $res = $this->getById($id);
        if(!$res) {
            throw new \think\Exception("不存在该条记录");
        }
        $data = [
            "listorder" => $listorder,
        ];

        try {
            //$this->model->where(["id" => $id])->save($data);
            $res = $this->model->updateById($id, $data);
        }catch (\Exception $e) {
            // 记得记录日志。
            return false;
        }
        return $res;
    }

    /**
     * 修改状态 逻辑层--
     * @param $id
     * @param $status
     * @return array|bool|\think\Model|void|null
     * @throws \think\Exception
     */
    public function status($id, $status) {
        // 查询 id这条数据是否存在
        $res = $this->getById($id);

        if(!$res) {
            throw new \think\Exception("不存在该条记录");
        }
        if($res['status'] == $status) {
            throw new \think\Exception("状态修改前和修改后一样没有任何意义哦");
        }

        $data = [
            "status" => intval($status),
        ];

        try {
            $res = $this->model->updateById($id, $data);
        }catch (\Exception $e) {
            // 记得记录日志。
            return false;
        }
        return $res;
    }


    /**
     * 获取一级分类的内容
     * @return array
     */
    public function getNormalByPid($pid = 0 , $field = "id, name, pid") {
        //$field = "id,name,pid";
        try {
            $res = $this->model->getNormalByPid($pid, $field);
        }catch (\Exception $e) {
            // 记得记录日志。
            return [];
        }
        $res = $res->toArray();

        return $res;
    }
}