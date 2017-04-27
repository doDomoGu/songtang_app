<?php
namespace api\controllers\user\logout;

use yii\base\Action;
use ucenter\models\User;
use ucenter\models\UserSession;

class index extends Action
{
    public function run()
    {
        $success = false;
        $msg = '未知错误';
        $user_session = $this->controller->request['user_session'];
        $username = $this->controller->request['username'];

        if ($username == '') {
            $msg = '用户名不能为空';
        } else {
            if (substr($username, -13) != '@songtang.net') {
                $username .= '@songtang.net';
            }
            $user = User::find()->where(['username' => $username])->one();
            if (!$user) {
                $msg = '该用户名不存在';
            } else {
                $userSession = UserSession::find()->where(['user_id' => $user->id])->one();
                if (!$userSession || $userSession->session=='' || $userSession->session != $user_session) {
                    $msg = '用户名和session不匹配';
                } else {
                    $userSession->delete();
                    $success = true;
                }
            }
        }


        if ($success) {
            return ['user_logout_response' =>
                [
                    'result' => 'success'
                ]
            ];
        } else {
            return ['error_response' =>
                [
                    'code' => 400,
                    'msg' => $msg
                ]
            ];
        }
    }
}