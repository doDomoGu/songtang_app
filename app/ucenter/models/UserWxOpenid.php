<?php

namespace ucenter\models;

use Yii;

class UserWxOpenid extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }
    public function attributeLabels(){
        return [
            'appid'=>'appID',
            'user_id'=>'职员ID',
            'openid'=>'openId'
        ];
    }

    public function rules()
    {
        return [
            [['appid','user_id','openid'], 'required'],
            [['appid','user_id','openid'], 'integer'],
        ];
    }

}
