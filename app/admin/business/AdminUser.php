<?php

namespace app\admin\business;
use app\common\model\mysql\AdminUser as AdminUserModel;

class AdminUser {

    public function __construct()
    {
        $this->adminUserObj = new AdminUserModel();
    }

    public function login($data){

                $adminUser = $this->adminUserObj->getAdminUserByUsername($data['username']);
                //        判断用户是否存在
                if(!$adminUser){
                    throw new \think\Exception(" 不存在该用户");
                }
                //        判断密码是否正确
                if($adminUser['password'] != md5($data['password']."_jiang")){
                    throw new \think\Exception(" 密码不正确");

                }

                //        更新最后登录的时间到表中去
                $updateData = [
                    "last_login_time" =>time(),
                    "last_login_ip" =>request()->ip(),
                    "update_time" =>time(),
                ];
                $rew = $this->adminUserObj->updateById($adminUser['id'],$updateData);
                if(empty($rew)){
                    throw new \think\Exception(" 登录失败");
//                    return show(config("status.error"),"登录失败");
                }

            //        记录到session
            session(config("admin.session_admin"),$adminUser);
            return true;
        }

            //    干净的用户数据
    public function getAdminUserByUsername($username) {
        $user = $this->adminUserObj ->getAdminUserByUsername($username);

        if(empty($user) || $user->status != config("status.mysql.table_normal")) {
            return [];
        }

        $user = $user->toArray();
        return $user;
    }
}

