<?php

namespace oa\models;

//oa 申请表
use ucenter\models\User;

class OaApply extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL     = 1; //正常流程中
    const STATUS_DELETE     = 0; //删除
    const STATUS_SUCCESS    = 2; //成功 完成
    const STATUS_FAILURE    = 3; //失败 完成
    const STATUS_BEATBACK   = 4; //打回 发生情况：1.审核不通过 2.执行失败

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '申请标题',
            'user_id' => '发起人ID',
            'task_id' => '对应任务表Id',
            'flow_step' => '流程执行到第几步',
            'add_time' => '开始时间',
            'edit_time' => '编辑时间',
            'status' => '状态',

        ];
    }

    public function rules()
    {
        return [
            [['title','user_id','task_id','flow_step'], 'required'],
            [['user_id','task_id','flow_step','status'], 'integer'],
            [['add_time','edit_time'],'safe']
        ];
    }

    public function getApplyUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(OaTask::className(), array('id' => 'task_id'));
    }
}
