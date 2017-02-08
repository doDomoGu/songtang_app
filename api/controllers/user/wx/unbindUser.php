<?php
namespace api\controllers\user\wx;

use ucenter\models\User;
use yii\base\Action;
use Yii;

class unbindUser extends Action {
    public function run() {
        $result = false;
        $msg = '';
        $user_id = $this->controller->rParams['user_id'];
        $session_3rd = $this->controller->rParams['session_3rd'];

        $table = 'user_wx_3rd_session';
        $db = Yii::$app->db_ucenter;
        $sql = "select * from $table where user_id = '$user_id'";
        $re = $db->createCommand($sql)->queryOne();
        if(!$re){
            $msg = '账号已经解除绑定!';
        }else{
            if($re['3rd_session']!=$session_3rd){
                $msg = 'session不匹配，非法请求';
            }else{
                $db->createCommand()->update($table,['user_id'=>0],"user_id = '$user_id'")->execute();

                $sql = "select * from $table where user_id = '$user_id'";

                $re = $db->createCommand($sql)->queryOne();

                if(!$re){
                    $result = true;
                    /*$user = User::find()->where(['id'=>$user_id])->one();
                    $_result = [
                        'user_id'=>$user_id,
                        'username'=>$user->username,
                        'name'=>$user->name,
                        'session_key' => $re['3rd_session']
                    ];*/
                }else{
                    $msg = '解绑失败';
                }
            }
        }
        if($result){
            return ['wx_unbind_user_response'=>
                [
                    'result'=>'success'
                ]
            ];
        }else{
            return ['error_response'=>
                [
                    'code'=>400,
                    'msg'=>$msg
                ]
            ];
        }
    }
}