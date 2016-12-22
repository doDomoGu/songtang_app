<?php

namespace oa\models;

//oa 任务 发起人对应表

use ucenter\models\User;
use Yii;

class TaskApplyUser extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'task_id' => '任务ID',
            'user_id' => '用户ID',
        ];
    }

    public function rules()
    {
        return [
            [['task_id', 'user_id'], 'integer'],
        ];
    }


    public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }

}
