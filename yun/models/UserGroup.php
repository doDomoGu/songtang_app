<?php

namespace yun\models;
use Yii;

class UserGroup extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }
    public function getUsers(){
        return $this->hasMany(UserGroupUser::className(), array('group_id' =>'id'));
    }
}