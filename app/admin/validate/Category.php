<?php

namespace app\admin\validate;

use think\Validate;

class Category extends Validate {
    protected $rule = [
        'name'  =>  'require|unique:Category',
        'pid' =>  'require',
    ];

    protected $message = [
        'name.require'  =>  '分类名称必须',
        'name.unique'  =>  '该分类已存在，请从新输入',
        'pid' =>  '父类ID必须',
    ];
}