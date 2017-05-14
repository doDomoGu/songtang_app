<?php

namespace oa\models;

use Yii;
use yii\base\Model;

class ApplyCreateForm extends Model
{
    public $id;
    public $title;
    public $task_id;
    public $task_category;
    public $message;

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '申请标题',
            'user_id' => '发起人ID',
            'task_id' => '选择申请任务',
            'task_category' => '分类',
            'flow_step' => '流程执行到第几步',
            'message' => '申请备注/内容',
            'add_time' => '开始时间',
            'edit_time' => '编辑时间',
            'status' => '状态',

        ];
    }

    public function rules()
    {
        return [
            [['title','task_id','task_category'], 'required'],
            [['task_id', 'task_category'], 'integer'],
            [['message'], 'safe']
        ];
    }


}
