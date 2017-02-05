<?php
namespace api\controllers\user\wx;

use yii\base\Action;

class codeToSession extends Action {
    public function run() {
        $appid = 'wxfeb4bdcd2e97f17b';
        $secret = '558f4d98ab4a03e9ccb5e20270806436';
        $code = $this->controller->rParams['code'];

        $url = 'https://api.weixin.qq.com/sns/jscode2session?';

        $url.='appid='.$appid;
        $url.='&secret='.$secret;
        $url.='&js_code='.$code;
        $url.='&grant_type=authorization_code';
        
        $result = file_get_contents($url);

        $result = json_decode($result,true);
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