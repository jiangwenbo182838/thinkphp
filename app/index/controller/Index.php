<?php
namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return '前台模块';
    }

    public function hello()
    {
        return 'hello';
    }
}
