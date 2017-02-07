<?php
namespace api\controllers\user\wx;

use yii\base\Action;

class bindUser extends Action {
    public function run() {



        if(isset($result['openid'])){
            return ['wx_code_to_session_response'=>
                [
                    'result'=>[
                        'openid'=>$result['openid'],
                        'session_key' => $result['session_key'],
                    ]
                ]
            ];
        }else{
            return ['error_response'=>
                [
                    'code'=>$result['errcode'],
                    'msg'=>$result['errmsg']
                ]
            ];
        }
    }
}