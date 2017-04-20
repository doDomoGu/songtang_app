<?php
namespace api\controllers\user\wx;
include_once "../../common/extensions/wx-encrypted/wxBizDataCrypt.php";

use yii\base\Action;
use WXBizDataCrypt;

class encryptedData extends Action {
    public function run() {
        $appid = 'wxfeb4bdcd2e97f17b';
       /* $secret = '558f4d98ab4a03e9ccb5e20270806436';
        $code = $this->controller->request['code'];

        $url = 'https://api.weixin.qq.com/sns/jscode2session?';

        $url.='appid='.$appid;
        $url.='&secret='.$secret;
        $url.='&js_code='.$code;
        $url.='&grant_type=authorization_code';

        $result = file_get_contents($url);

        $result = json_decode($result,true);
var_dump($result);exit;*/

        $sessionKey = $this->controller->request['session_key'];

        $encryptedData = $this->controller->request['encryptedData'];

        $iv = $this->controller->request['iv'];

        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) {
            print($data . "\n");
        } else {
            print($errCode . "\n");
        }

exit;
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