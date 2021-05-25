<?php
namespace app\common\business;
use app\common\model\mysql\Goods as GoodsModel;
use app\common\business\GoodsSku as GoodsSkuBis;
use app\common\business\SpecsValue as SpecsValueBis;
use think\facade\Cache;
class Goods extends BusBase
{
    public $model = NULL;

    public function __construct()
    {
        $this->model = new GoodsModel();
    }

    /**
     * 新增商品逻辑
     * @param $data
     * @return array|bool|int|mixed
     */
    public function insertData($data) {
        // 开启一个事务
        $this->model->startTrans();
        try {
            $goodsId = $this->add($data);//你这儿添加goods表也是用的$data
            if (!$goodsId) {
                return $goodsId;
            }
            if ($data['goods_specs_type'] == 1) {  // 1 是统一规格
                $goodsSkuData = [
                    "goods_id" => $goodsId,
                    "specs_value_ids" => '',
                    "price" => $data[0]['sell_price'],
                    "cost_price" => $data[0]['market_price'],
                    "stock" => $data[0]['stock'],
                ];
                $res = (new GoodsSkuBis()) ->add($goodsSkuData);
                if ( !$res )
                {
                    throw new \think\Exception("sku表新增失败");
                }

                return true;
            } elseif ($data['goods_specs_type'] == 2) {
                $goodsSkuBisobj = new GoodsSkuBis();
                $data['goods_id'] = $goodsId;
                $res = $goodsSkuBisobj->saveAll($data);

                // 如果不为空
                if (!empty($res)) {
                    // 总库存
                    $stock = array_sum(array_column($res, "stock"));
                    $goodsUpdateData = [
                        "price" => $res[0]['price'],
                        "cost_price" => $res[0]['cost_price'],
                        "stock" => $stock,
                        "sku_id" => $res[0]['id'],
                    ];
                    // 执行完毕之后 更新 主表中的数据
                    $goodsRes = $this->model->updateById($goodsId, $goodsUpdateData);
                    if (!$goodsRes) {
                        throw  new \think\Exception("insertData:goods主表更新失败");
                    }

                } else {
                    throw new \think\Exception("sku表新增失败");
                }
            }
            // 事务提交
            $this->model->commit();
            return true;
        }catch (\think\Exception $e) {
            // 记录日志 untodo
            // 事务回滚
            $this->model->rollback();
            return false;
        }
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
     * 商品:修改状态 逻辑层--
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
     * 商品:修改状态 逻辑层--
     * @param $id
     * @param $status
     * @return array|bool|\think\Model|void|null
     * @throws \think\Exception
     */
    public function statusrecommend($id, $isindexrecommend) {
        // 查询 id这条数据是否存在
        $res = $this->getById($id);
        if(!$res) {
            throw new \think\Exception("不存在该条记录");
        }
        if($res['is_index_recommend'] == $isindexrecommend) {
            throw new \think\Exception("状态修改前和修改后一样没有任何意义哦");
        }

        $data = [
            "is_index_recommend" => intval($isindexrecommend),
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
     * 获取分页列表的数据
     * @param $data
     * @param int $num
     * @return array
     */
    public function getLists($data, $num = 5) {

        $likeKeys = [];// 如果没有定义这个 的时候会有报错
        if(!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getLists($likeKeys, $data, $num);
            $result = $list->toArray();
        }catch (\Exception $e) {
            // 最好这个地方的结构可以写到基础类库中
            $result = \app\common\lib\Arr::getPaginateDefaultData($num);
        }
        return $result;
    }

    /**
     * 商品列表排序bis -
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




}