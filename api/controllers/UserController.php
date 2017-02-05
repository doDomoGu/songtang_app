<?php
namespace api\controllers;

use ucenter\models\User;
use Yii;

class UserController extends BaseController{
    public $helpTitle = 'User API';

    public $allowArr = [
            'info-get'=>['id'=>'int'],
            'wx-code-to-session'=>['code'=>'str'],
            'wx-encrypted-data'=>['session_key'=>'str','encryptedData'=>'str','iv'=>'str']
        ];
    public $requireArr = [
            'info-get'=>['id'],
            'wx-code-to-session'=>['code'],
            'wx-encrypted-data'=>['session_key','encryptedData','iv']
            //'add'=>['name']
        ];

    public function actions()
    {
        return [
            'info-get'=>[
                'class'=>'api\controllers\user\info\get',
            ],
            'wx-code-to-session'=>[
                'class'=>'api\controllers\user\wx\codeToSession',
            ],
            'wx-encrypted-data'=>[
                'class'=>'api\controllers\user\wx\encryptedData',
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
