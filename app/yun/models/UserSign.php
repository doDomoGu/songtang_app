<?php

namespace yun\models;

use ucenter\models\User;
use Yii;

class UserSign extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function attributeLabels(){
        return [
            'user_id' => '职员ID',
            'point' => '积分',
            'y' => '年',
            'm' => '月',
            'd' => '日',
            'sign_time'=>'签到时间',
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'point', 'y', 'm', 'd'], 'required'],
            [['point','y','m','d'], 'integer'],
            [['sign_time'], 'safe']

        ];
    }

/*CREATE TABLE `user_sign` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`point` varchar(10) NOT NULL,
`y` varchar(10) NOT NULL,
`m` varchar(2) NOT NULL,
`d` varchar(2) NOT NULL,
`sign_time` datetime DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8*/


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
