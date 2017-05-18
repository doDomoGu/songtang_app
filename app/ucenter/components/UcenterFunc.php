<?php
namespace ucenter\components;

use yii\base\Component;
use Yii;

class UcenterFunc extends Component {
    static $cacheList = [
        'district'=>'地区',
        'industry'=>'行业',
        'company'=>'公司',
        'department'=>'部门',
        'position'=>'职位',
        'user' =>'职员账号'
    ];
    static $cacheKeyList = [
        'district'=>[
            'name'
        ],
        'industry'=>[
            'name'
        ],
        'company'=>[
            'name'
        ],
        'department'=>[
            'full-route'
        ],
        'position'=>[
            'name'
        ],
        'user'=>[
            'identity',
            'items'
        ]
    ];
}