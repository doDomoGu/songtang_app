<?php
namespace api\controllers\user\wx;

use common\components\CommonFunc;
use yii\base\Action;
use Yii;

class get3rdSession extends Action {
    public function run() {
        $appid = 'wxfeb4bdcd2e97f17b';
        $secret = '558f4d98ab4a03e9ccb5e20270806436';

        $expired = 3600; //过期时间 1小时

        $code = $this->controller->request['code'];

        $url = 'https://api.weixin.qq.com/sns/jscode2session?';

        $url.='appid='.$appid;
        $url.='&secret='.$secret;
        $url.='&js_code='.$code;
        $url.='&grant_type=authorization_code';
        
        $result = file_get_contents($url);

        $result = json_decode($result,true);

        if(isset($result['openid'])){
            //1.由code获取 openid 和 session_key(微信服务端)
            $openid = $result['openid'];
            $session_key = $result['session_key'];

            //2.根据openid查询
            $table = 'user_wx_3rd_session';
            $db = Yii::$app->db_ucenter;
            $sql = "select * from $table where openid = '$openid'";//' and session_key = "'.$session_key.'" and expire_time > "'.date('Y-m-d H:i:s').'"';
            $result = $db->createCommand($sql)->queryOne();
            //$result = $db->createCommand()->select('*')->from($table)->where('openid = "'.$openid.'"')->queryOne();
            if($result){
                //3.存在记录  需要对其进行更新
                $update = [];
                $where = 'openid = "'.$openid.'"';
                //3.1.判断session_key 是否与微信服务端一致
                if($result['session_key']!='' && $result['session_key']==$session_key){
                    if($result['3rd_session']=='' || $result['expire_time']<date('Y-m-d H:i:s')){
                        $update['3rd_session'] = $this->generate3rdSessionKey();
                        $update['expire_time'] = date('Y-m-d H:i:s',strtotime('+3600 second'));
                    }
                }else{
                    //不一致则更新 session_key  3rd_session 和 expire_time
                    $update['session_key'] = $session_key;
                    $update['3rd_session'] = $this->generate3rdSessionKey();
                    $update['expire_time'] = date('Y-m-d H:i:s',strtotime('+3600 second'));
                }
                if(!empty($update))
                    $db->createCommand()->update($table,$update,$where)->execute();
            }else{
                $insert = [
                    'user_id'=>0,
                    'openid'=>$openid,
                    'session_key'=>$session_key,
                    '3rd_session'=>$this->generate3rdSessionKey(),
                    'expire_time'=>date('Y-m-d H:i:s',strtotime('+3600 second'))
                ];
                $db->createCommand()->insert($table,$insert)->execute();
            }

            $sql = "select * from $table where openid = '$openid'";
            $result = $db->createCommand($sql)->queryOne();

            return ['wx_get_3rd_session_response'=>
                [
                    'result'=>[
                        'session_3rd'=>$result['3rd_session'],
//                        'openid'=>$openid,
//                        'session_key' => $session_key,
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

    public function generate3rdSessionKey(){
        $key = CommonFunc::generateCode();
        $table = 'user_wx_3rd_session';
        $db = Yii::$app->db_ucenter;
        $sql = "select * from $table where 3rd_session = 'key'";
        $result = $db->createCommand($sql)->queryOne();
        if($result){
            $key = $this->generate3rdSessionKey();
        }
        return $key;
    }
}


/*

CREATE TABLE `user_wx_session` (
`key` varchar(100) NOT NULL,
 `value` text,
 PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `user_wx_3rd_session` (
`openid` varchar(255) NOT NULL,
`session_key` varchar(255) NOT NULL,
`user_id` int(11),
`3rd_session` varchar(255) NOT NULL,
`expire_time` datetime,
 PRIMARY KEY (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

*/