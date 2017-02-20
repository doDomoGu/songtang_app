<?php

namespace yun\models;
use Yii;

class Group extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }
    public function getUsers(){
        return $this->hasMany(GroupUser::className(), array('group_id' =>'id'));
    }
}