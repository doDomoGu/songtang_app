<?php
namespace api\controllers;

use Yii;

class OaController extends BaseController{

    public $helpTitle = 'OA API';

    public $format = [
            'apply-get'=>[
                'param'=>[
                    'id'=>[
                        'type' => 'int',
                        'required' => true,
                        'explain' => '申请表ID'
                    ]
                ],
                'title' => '获取申请表信息',
                'explain' => '获取申请表信息.....'
            ],
            'apply-create'=>[
                'param' => [
                    'title'=>[
                        'type'=>'string',
                        'required' => true,
                        'explain' => '标题'
                    ],
                    'task_id'=>[
                        'type'=>'int',
                        'required' => false,
                        'explain' => '任务表ID'
                    ],
                    'message'=>[
                        'type'=>'string',
                        'required' => false,
                        'explain' => '申请内容'
                    ],
                    'user_id'=>[
                        'type'=>'int',
                        'required' => false,
                        'explain' => '发起人/申请人ID'
                    ],
                    'session_3rd'=>[
                        'type'=>'string',
                        'required' => false,
                        'explain' => '第三方session'
                    ]
                ],
                'title' => '创建申请表',
                'explain'=>'创建申请表...'
            ]
        ];

    public $allowArr = [
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
            /*'apply-getall'=>[
                'class'=>'api\controllers\oa\apply\getall',
            ],
            'task-getall'=>[
                'class'=>'api\controllers\oa\task\getall',
            ]*/
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
