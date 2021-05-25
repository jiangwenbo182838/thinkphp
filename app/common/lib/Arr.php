<?php
namespace app\common\lib;
class Arr {
    /**分页默认返回的数据
     * 如果有异常就让他和之前的分页格式保持一致。
     * @param $num
     * @return array
     */
    public static function getPaginateDefaultData($num) {
        $result = [
            "total" =>0,
            "per_page" =>$num,
            "current_page" =>1,
            "last_page" =>0,
            "data" =>[],
        ];
        return $result;
    }
}