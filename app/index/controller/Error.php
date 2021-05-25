<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/2/9
 * Time: 20:33
 */
namespace app\index\controller;

class Error {
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $result = [
            'status' => config("status.controller_not_found"),
            'message' => "找不到该控制器",
            'result' => null,
        ];
        return json($result, 400);
    }
}