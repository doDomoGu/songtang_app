<?php

namespace common\models;

use Yii;

class UserWxSession extends \yii\db\ActiveRecord
{
    public $db = 'db';
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
