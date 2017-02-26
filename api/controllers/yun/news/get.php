<?php
namespace api\controllers\yun\news;

use yii\base\Action;
use ucenter\models\User;
use yun\models\News;

class get extends Action {
    public function run() {
        //$id = $this->controller->rParams['id'];


        $list = News::find()->where(['status'=>1])->orderBy('ord desc, edit_time desc')->all();
        if($user){
            return ['user_get_response'=>
                [
                    'info'=>[
                        'username'=>$user->username,
                        'name' => $user->name,
                    ]
                ]
            ];
        }else{
            return ['error_response'=>
                [
                    'code'=>400,
                    'msg'=>'用户没找到'
                ]
            ];
        }
    }
}