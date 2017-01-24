<?php
namespace api\controllers\oa\apply;

use yii\base\Action;
use oa\models\Apply;

class get extends Action {
    public function run() {
        $errormsg = '';
        $result = false;
        $apply = Apply::find()->where(['id'=>$this->controller->rParams['id']])->one();
        if($apply){
            $result = true;
        }else{
            $errormsg = '找不到对应的申请!';
        }
        if($result){
            return ['apply_get_response'=>
                [
                    'info'=>[
                        'id'=>$apply->id,
                        'title'=>$apply->title,
                        'user_id'=>$apply->user_id,
                        'message'=>$apply->message,
                        'add_time'=>$apply->add_time
                    ]
                ]
            ];
        }else{
            return ['error_response'=>
                [
                    'code'=>400,
                    'msg'=>$errormsg
                ]
            ];
        }
    }
}