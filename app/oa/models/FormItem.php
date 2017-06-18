<?php

namespace oa\models;

//oa 模板和表单的对应关系

use Yii;

class FormItem extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'form_id' =>'表单ID',
            'ord' =>'排序',
            'item_key' =>'key',
            'item_value' =>'value',
            'status' =>'状态',
        ];
    }

    public function rules()
    {
        return [
            [['form_id','ord','item_key','item_value'], 'required'],
            [['form_id','ord','status'], 'integer']
        ];
    }


    /*public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }*/

}
