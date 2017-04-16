<?php
namespace api\controllers\user\info;

use yii\base\Action;
use ucenter\models\User;

class get extends Action {
    public function run() {
        $id = $this->controller->request['id'];
        $user = User::find()->where(['id'=>$id])->one();
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