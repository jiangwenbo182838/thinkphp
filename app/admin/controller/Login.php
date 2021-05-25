<?php

namespace app\admin\controller;
use think\facade\View;
use app\common\model\mysql\AdminUser;

class Login extends AdminBase
{
    public function initialize()
    {
        if($this->isLogin()){
            return $this->redirect(url("index/index"));
        }
    }

    public function index(){

        return View::fetch();
    }

    public function md5(){
        dump(session(config("admin.session_admin")));
//        echo md5("admin_jiang");
    }
    public function check()
    {
        if (!$this->request->isPost()) {
            return show(config("status.error"), "请求方式错误");
        }
        $username = $this->request->param("username", "", "trim");
        $password = $this->request->param("password", "", "trim");
        $captcha = $this->request->param("captcha", "", "trim");
//            validate验证
        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha,
        ];
        $validate = new \app\admin\validate\AdminUser();
        if (!$validate->check($data)) {
            return show(config("status.error"), $validate->getError());
        }
//        business层处理业务逻辑

        try {
            $adminUserObj = new \app\admin\business\AdminUser();
            $result = $adminUserObj->login($data);
        }catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage());
        }
        if ($result) {
            return show(config("status.success"), "登录成功");
        } else {
            return show(config("status.error"), $validate->getError());
        }
    }

}