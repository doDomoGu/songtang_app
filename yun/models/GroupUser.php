<?php

namespace yun\models;
use Yii;

class GroupUser extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }
}