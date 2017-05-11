<?php
namespace api\controllers\user\login;

use common\components\CommonFunc;
use yii\base\Action;
use ucenter\models\User;
use ucenter\models\UserSession;

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
                        $userSession = UserSession::find()->where(['user_id'=>$user->id])->one();
                        if($userSession){
                            $session = $userSession->session;
                            $userSession->expire_time = date('Y-m-d H:i:s',strtotime('+1 day'));
                            $userSession->save();
                        }else{
                            $newUserSession = new UserSession();
                            $newUserSession->user_id = $user->id;
                            $session = $this->generateSession();
                            $newUserSession->session = $session;
                            $newUserSession->expire_time = date('Y-m-d H:i:s',strtotime('+1 day'));
                            $newUserSession->save();
                        }
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
                        'session' => $session
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

    private function generateSession(){
        $code = CommonFunc::generateCode(20);
        $exist = UserSession::find()->where(['session'=>$code])->one();
        if($exist){
            $code = $this->generateSession();
        }
        return $code;
    }
}

/*
 CREATE TABLE `user_session` (
 `user_id` int(11),
 `session` varchar(20) DEFAULT NULL,
 `expire_time` datetime DEFAULT NULL,
 PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */