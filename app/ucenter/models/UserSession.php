<?php

namespace ucenter\models;

use Yii;

class UserSession extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }



}
