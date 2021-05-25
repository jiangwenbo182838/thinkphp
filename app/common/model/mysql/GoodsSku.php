<?php

namespace app\common\model\mysql;
use think\Model;

class GoodsSku extends BaseModel {

    public function goods() {
        return $this->hasOne(Goods::class, "id",  "goods_id");
        ///return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }

    public function getNormalByGoodsId($goodsId = 0) {
        $where = [
            "goods_id" => $goodsId,
            "status" => config("status.mysql.table_normal"),
        ];

        return $this->where($where)->select();
    }

    public function  incStock($id, $num) {
        return $this->where("id", "=", $id)
            ->inc("stock", $num)
            ->update();
    }
}