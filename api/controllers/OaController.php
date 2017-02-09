<?php
namespace api\controllers;

use Yii;

class OaController extends BaseController{
    public $helpTitle = 'OA API';

    public $allowArr = [
            'apply-get'=>[
                'id'=>'int'
            ],
            'apply-create'=>[
                'title'=>'str',
                'task_id'=>'int',
                'message'=>'str',
                'user_id'=>'int',
                'session_3rd'=>'str'
            ],
            'change'=>[
                'id'=>'int',
                'name'=>'str'
            ],
            'task-getall'=>[
                'apply_user'=>'int'
            ],
            'apply-getall'=>[
                'user_id'=>'int'
            ]
        ];
    public $requireArr = [
            'apply-get'=>[
                'id'
            ],
            'apply-create'=>[
                'title'
            ],
        ];

    public function actions()
    {
        return [
            'apply-get'=>[
                'class'=>'api\controllers\oa\apply\get',
            ],
            'apply-create'=>[
                'class'=>'api\controllers\oa\apply\create',
            ],
            'apply-getall'=>[
                'class'=>'api\controllers\oa\apply\getall',
            ],
            'task-getall'=>[
                'class'=>'api\controllers\oa\task\getall',
            ]
        ];
    }


    public function getHelp($act){
        switch($act){
            case 'apply-create':
                $msg = $act.' : help info';
                break;
            case 'apply-get':
                $msg = $act.' : help info';
                break;
            case 'apply-getall':
                $msg = $act.' : help info';
                break;
            case 'task-getall':
                $msg = $act.' : help info';
                break;
            default:
                $msg = false;
        }

        return $msg;
    }
}
