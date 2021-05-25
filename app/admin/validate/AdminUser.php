<?php
/**
 * 蒋氏
 * Created by
 * User: singwa
 * motto: 现在的努力是为了小时候吹过的牛逼！
 * Time: 07:11
 */
namespace app\admin\validate;

use think\Validate;

class AdminUser extends  Validate {
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'captcha' => 'require|checkCapcha', //验证码
    ];

    protected $message = [
        'username' => '用户名必须,请重新输入',
        'password' => '密码必须',
        'captcha' => '验证码必须',

    ];
//            验证码  验证
    protected function checkCapcha($value, $rule, $data = []) {
        if(!captcha_check($value)) {
            return "您输入的验证码不正确！";
        }

        return true;
    }
}