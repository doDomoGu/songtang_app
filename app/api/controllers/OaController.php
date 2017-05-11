<?php
namespace api\controllers;

class OaController extends BaseController{

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
            ],
            'task-getall'=>[
                'param'=>[
                    'user_id'=>[
                        'type' => 'int',
                        'required' => true,
                        'explain' => '申请人用户ID'
                    ]
                ],
                'title' => '获取对应用户可发起的申请表模板',
                'explain' => '获取可发起的申请表模板.....'
            ],
            'apply-getall'=>[
                'param'=>[
                    'user_id'=>[
                        'type' => 'int',
                        'required' => true,
                        'explain' => '申请人用户ID'
                    ]
                ],
                'title' => '获取对应用户发起的申请',
                'explain' => '获取对应用户发起的申请表.....'
            ]
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
}
