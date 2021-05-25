<?php
namespace app\admin\controller;


use think\facade\View;
class Specs extends AdminBase
{
    public function dialog() {
        return view("", [
            "specs" => json_encode(config("specs"))
        ]);
    }

}
