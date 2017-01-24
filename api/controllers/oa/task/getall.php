<?php
namespace api\controllers\oa\task;

use yii\base\Action;
use oa\models\Task;

class getall extends Action {
    public function run() {
        $errormsg = '';
        $result = false;
        $task = Task::find()->all();
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