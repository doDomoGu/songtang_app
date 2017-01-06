<?php
namespace oa\components;

use oa\models\Task;
use oa\models\TaskApplyUser;
use yii\base\Component;
use yii;

class Func extends Component {

    /*
     * 根据职员ID获取相关的可发起的申请列表
     * return  ['id1'=>'title1',...***]
     */
    public static function getTasksByUid($user_id){
        $return = [];
        //有效的task列表
        $task = Task::find()->where(['status'=>1,'set_complete'=>1])->all();
        $taskIds = [];
        foreach($task as $t){
            $taskIds[] = $t->id;
        }
        $list = TaskApplyUser::find()->where(['user_id'=>$user_id,'task_id'=>$taskIds])->all();
        foreach($list as $l){
            $return[$l->task_id] = $l->task->area->name.'|'.$l->task->business->name.'|'.$l->task->category->name.'|'.$l->task->title;
        }
        return $return;
    }



}
