<?php
namespace api\controllers\user\wx;

use ucenter\models\User;
use yii\base\Action;
use Yii;

class bindUser extends Action {
    public function run() {
        $result = false;
        $msg = '';
        $user_id = $this->controller->request['user_id'];
        $session_3rd = $this->controller->request['session_3rd'];

        $table = 'user_wx_3rd_session';
        $db = Yii::$app->db_ucenter;
        $sql = "select * from $table where user_id = '$user_id'";
        $re = $db->createCommand($sql)->queryOne();
        if($re){
            $msg = '用户已经绑定';
        }else{
            $db->createCommand()->update($table,['user_id'=>$user_id],"3rd_session = '$session_3rd'")->execute();

            $sql = "select * from $table where user_id = '$user_id' and 3rd_session = '$session_3rd'";

            $re = $db->createCommand($sql)->queryOne();

            if($re){
                $result = true;
                $user = User::find()->where(['id'=>$user_id])->one();
                $_result = [
                    'user_id'=>$user_id,
                    'username'=>$user->username,
                    'name'=>$user->name,
                    'session_key' => $re['3rd_session']
                ];
            }else{
                $msg = '绑定失败';
            }
        }

        if($result){
            return ['wx_bind_user_response'=>
                [
                    'result'=>$_result
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