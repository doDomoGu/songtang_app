<?php
namespace api\controllers;

use Yii;

class YunController extends BaseController{
    public $helpTitle = 'Yun API';

    public $allowArr = [
            'info-get'=>['id'=>'int'],
            'login'=>['username'=>'str','password'=>'str'],
            'wx-code-to-session'=>['code'=>'str'],
            'wx-encrypted-data'=>['session_key'=>'str','encryptedData'=>'str','iv'=>'str'],
            'wx-bind-user'=>['user_id'=>'str','session_3rd'=>'str'],
            'wx-unbind-user'=>['user_id'=>'str','session_3rd'=>'str'],
            'wx-get-3rd-session'=>['code'=>'str']
        ];
    public $requireArr = [
            'info-get'=>['id'],
            'login'=>['username','password'],
            'wx-code-to-session'=>['code'],
            'wx-encrypted-data'=>['session_key','encryptedData','iv'],
            'wx-bind-user'=>['user_id','session_3rd'],
            'wx-unbind-user'=>['user_id','session_3rd'],
            'wx-get-3rd-session'=>['code']
            //'add'=>['name']
        ];

    public function actions()
    {
        return [
            'news-get'=>[
                'class'=>'api\controllers\yun\news\get',
            ],
//            'login'=>[
//                'class'=>'api\controllers\user\login\index',
//            ],
//            'wx-code-to-session'=>[
//                'class'=>'api\controllers\user\wx\codeToSession',
//            ],
//            'wx-get-3rd-session'=>[
//                'class'=>'api\controllers\user\wx\get3rdSession',
//            ],
//            'wx-encrypted-data'=>[
//                'class'=>'api\controllers\user\wx\encryptedData',
//            ],
//            'wx-bind-user'=>[
//                'class'=>'api\controllers\user\wx\bindUser',
//            ],
//            'wx-unbind-user'=>[
//                'class'=>'api\controllers\user\wx\unbindUser',
//            ]
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
