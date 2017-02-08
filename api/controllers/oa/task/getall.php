<?php
namespace api\controllers\oa\task;

use oa\models\TaskApplyUser;
use yii\base\Action;
use oa\models\Task;

class getall extends Action {
    public function run() {
        $errormsg = '';
        $result = false;
        $apply_user = $this->controller->rParams['apply_user'];


        $query = Task::find();
        if($apply_user>0){
            $tauList = TaskApplyUser::find()->where(['user_id'=>$apply_user])->all();
            $taskids = [];
            foreach($tauList as $t){
                $taskids[] = $t->task_id;
            }
            $query = $query->andWhere(['id'=>$taskids]);
        }
        $task = $query->all();

        if(!empty($task)){
            $result = true;
            $list = [];
            foreach($task as $t){
                $list[] = [
                    'id'=>$t->id,
                    'title'=>$t->title,
                ];
            }
        }else{
            $errormsg = '找不到对应的任务表!';
        }
        if($result){
            return ['task_get_all_response'=>
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