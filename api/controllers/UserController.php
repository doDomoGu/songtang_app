<?php
namespace api\controllers;

use ucenter\models\User;
use Yii;

class UserController extends BaseController{
    public $helpTitle = 'User API';

    public $allowArr = [
            'info-get'=>['id'=>'int'],
            'change'=>['id'=>'int','name'=>'str']
        ];
    public $requireArr = [
            'info-get'=>['id'],
            'change'=>['id','name'],
            'add'=>['name']
        ];

    public function actions()
    {
        return [
            'info-get'=>[
                'class'=>'api\controllers\user\info\get',
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
