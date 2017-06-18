<?php

namespace oa\models;

//oa 申请表 的 表单申请信息

use Yii;

class ApplyFormContent extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    /*public function attributeLabels(){
        return [
            'title' => '标题',
            'status' =>'状态'
        ];
    }*/

    public function rules()
    {
        return [
            [['apply_id','item_key','item_value'],'required'],
            [['apply_id'], 'integer']
        ];
    }

    

    /*public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }*/

}
