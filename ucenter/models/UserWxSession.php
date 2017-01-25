<?php

namespace ucenter\models;

use Yii;

class UserWxSession extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels(){
        return [
            'key'=>'key',
            'value'=>'value'
        ];
    }

    public function rules()
    {
        return [
            [['key','value'], 'required'],
        ];
    }

}
