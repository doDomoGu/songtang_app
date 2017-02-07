<?php
namespace api\controllers;

use ucenter\models\User;
use Yii;

class UserController extends BaseController{
    public $helpTitle = 'User API';

    public $allowArr = [
            'info-get'=>['id'=>'int'],
            'login'=>['username'=>'str','password'=>'str'],
            'wx-code-to-session'=>['code'=>'str'],
            'wx-encrypted-data'=>['session_key'=>'str','encryptedData'=>'str','iv'=>'str'],
            'wx-bind-user'=>['user_id'=>'str','openid'=>'str']
        ];
    public $requireArr = [
            'info-get'=>['id'],
            'login'=>['username','password'],
            'wx-code-to-session'=>['code'],
            'wx-encrypted-data'=>['session_key','encryptedData','iv'],
            'wx-bind-user'=>['user_id','openid']
            //'add'=>['name']
        ];

    public function actions()
    {
        return [
            'info-get'=>[
                'class'=>'api\controllers\user\info\get',
            ],
            'login'=>[
                'class'=>'api\controllers\user\login\index',
            ],
            'wx-code-to-session'=>[
                'class'=>'api\controllers\user\wx\codeToSession',
            ],
            'wx-encrypted-data'=>[
                'class'=>'api\controllers\user\wx\encryptedData',
            ],
            'wx-bind-user'=>[
                'class'=>'api\controllers\user\wx\bindUser',
            ],
        ];
    }


    public function getHelp($act){
        switch($act){
            case 'info-get':
                $msg = $act.' : help info';
                break;
            default:
                $msg = false;
        }

        return $msg;
    }
}
