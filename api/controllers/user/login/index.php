<?php
namespace api\controllers\user\login;

use yii\base\Action;
use ucenter\models\User;

class index extends Action {
    public function run() {
        $success = false;
        $msg = '未知错误';
        $username = $this->controller->request['username'];
        $password = $this->controller->request['password'];

        if($username==''){
            $msg = '用户名不能为空';
        }else{
            if(substr($username,-13)!='@songtang.net'){
                $username .='@songtang.net';
            }
            $user = User::find()->where(['username'=>$username])->one();
            if(!$user){
                $msg = '该用户名不存在';
            }else{
                if(!$user->validatePassword($password)){
                    $msg = '用户名或者密码错误';
                }else{
                    if($user->status!=1){
                        $msg = '该用户被禁用';
                    }else{
                        $success = true;
                    }
                }
            }
        }



        if($success){
            return ['user_login_response'=>
                [
                    'userinfo'=>[
                        'user_id'=>$user->id,
                        'username'=>$user->username,
                        'name' => $user->name,
                    ]
                ]
            ];
        }else{
            return ['error_response'=>
                [
                    'code'=>400,
                    'msg'=>$msg
                ]
            ];
        }
    }
}