<?php
/**
 * Created by
 * User: 蒋氏
 * Time: 01:10
 */
namespace app\common\business;
class BusBase
{
    /**
     * 新增逻辑
     */
    public function add($data) {
        $data['status'] = config("status.mysql.table_normal");
        // 根据name 查询 $name 是否存在 未完成。
        try {
            $this->model->save($data);
        }catch (\Exception $e) {
            // 记录日志，便于后续问题的排查工作
            return 0;
        }

        // // 返回主键ID
        return $this->model->id;
    }

//    public function updateById($id, $data) {
//        $data['update_time'] = time();
//        return $this->where(["id" => $id])->save($data);
//    }

}