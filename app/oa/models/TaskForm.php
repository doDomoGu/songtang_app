<?php

namespace oa\models;

//oa 模板和表单的对应关系

use Yii;

class TaskForm extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'task_id' => '模板ID',
            'form_id' =>'表单ID'
        ];
    }

    public function rules()
    {
        return [
            [['task_id','form_id'], 'integer']
        ];
    }

    public function getForm(){
        return $this->hasOne(Form::className(), array('id' => 'form_id'));
    }
    /*public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }*/

}
