<?php
namespace api\controllers\oa\apply;

use yii\base\Action;
use oa\models\Apply;

class getall extends Action {
    public function run() {
        $errormsg = '';
        $result = false;

        $user_id = $this->controller->rParams['user_id'];


        $query = Apply::find();
        if($user_id>0){

            $query = $query->andWhere(['user_id'=>$user_id]);
        }

        $apply = $query->all();
        if(!empty($apply)){
            $result = true;
            $list = [];
            foreach($apply as $a){
                $list[] = [
                    'id'=>$a->id,
                    'title'=>$a->title,
                    'user_id'=>$a->user_id,
                    'message'=>$a->message,
                    'add_time'=>$a->add_time
                ];
            }
        }else{
            $errormsg = '找不到对应的申请!';
        }
        if($result){
            return ['apply_get_all_response'=>
                [
                    'list'=>$list
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