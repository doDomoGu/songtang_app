<?php

namespace yun\models;

use Yii;

class FileAttribute extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['file_id', 'attr_type', 'attr_id'], 'required'],
            [['file_id', 'attr_type', 'attr_id'], 'integer'],
        ];
    }



}