<?php
namespace api\controllers\oa\apply;

use oa\models\Apply;
use yii\base\Action;
use Yii;

class create extends Action {
    public function run() {
        $errormsg = '';
        $result = false;
        $session_3rd = $this->controller->rParams['session_3rd'];
        $table = 'user_wx_3rd_session';
        $db = Yii::$app->db_ucenter;
        $sql = "select * from $table where 3rd_session = '$session_3rd'";
        $re = $db->createCommand($sql)->queryOne();
        if(!$re){
            $errormsg = 'session 出错';
        }else{
            if(!isset($re['user_id']) || $re['user_id']==0){
                $errormsg = '用户信息出错';
            }else{

                $n = new Apply();
                $n->title = $this->controller->rParams['title'];
                $n->task_id  = $this->controller->rParams['task_id'];
                $n->message  = $this->controller->rParams['message'];
                $n->user_id = $re['user_id'];
                $n->flow_step = 1;
                $n->add_time = date('Y-m-d H:i:s');
                $n->edit_time = date('Y-m-d H:i:s');
                $n->status = 1;
                if($n->save()){
                    //Yii::$app->getSession()->setFlash('success','发起申请成功！');
                    $result = true;
                }else{
                    $errormsg = '发起申请失败!';
                }
            }
        }



        if($result){
            return ['apply_create_response'=>
                [
                    'info'=>[
                        'apply_id'=>$n->id,
                        'add_time'=>$n->add_time
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