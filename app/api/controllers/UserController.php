<?php
namespace api\controllers;

class UserController extends BaseController{
    public $format = [
        'info-get'=>[
            'param'=>[
                'id'=>[
                    'type' => 'int',
                    'required' => true,
                    'explain' => '用户ID'
                ],
                'user_session'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => '用户认证信息'
                ]
            ],
            'title' => '获取用户信息',
            'explain' => '获取用户信息.....'
        ],
        'login'=>[
            'param'=>[
                'username'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ],
                'password'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '用户登录',
            'explain'=>'login'
        ],
        'logout'=>[
            'param'=>[
                'user_session'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => '用户认证信息'
                ],
                'username'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '用户登出',
            'explain'=>'logout'
        ],
        'wx-code-to-session'=>[
            'param'=>[
                'code'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '微信码转换为session',
            'explain'=>'login'
        ],
        'wx-encrypted-data'=>[
            'param'=>[
                'session_key'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ],
                'encryptedData'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ],
                'iv'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '微信码解析',
            'explain'=>'login'
        ],
        'wx-bind-user'=>[
            'param'=>[
                'user_id'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ],
                'session_3rd'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '微信用户绑定',
            'explain'=>'login'
        ],
        'wx-unbind-user'=>[
            'param'=>[
                'user_id'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ],
                'session_3rd'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '微信用户解除绑定',
            'explain'=>'login'
        ],
        'wx-get-3rd-session' =>[
            'param'=>[
                'code'=>[
                    'type'=>'string',
                    'required' => true,
                    'explain' => ''
                ]
            ],
            'title'=> '获取第三方session',
            'explain'=>'login'
        ],
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
            'logout'=>[
                'class'=>'api\controllers\user\logout\index',
            ],
            'wx-code-to-session'=>[
                'class'=>'api\controllers\user\wx\codeToSession',
            ],
            'wx-get-3rd-session'=>[
                'class'=>'api\controllers\user\wx\get3rdSession',
            ],
            'wx-encrypted-data'=>[
                'class'=>'api\controllers\user\wx\encryptedData',
            ],
            'wx-bind-user'=>[
                'class'=>'api\controllers\user\wx\bindUser',
            ],
            'wx-unbind-user'=>[
                'class'=>'api\controllers\user\wx\unbindUser',
            ]
        ];
    }
}
