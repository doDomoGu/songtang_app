<?php

namespace yun\models;

use ucenter\models\Area;
use ucenter\models\Business;
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

    public function getArea()
    {
        return $this->hasOne(Area::className(), array('id' => 'attr_id'));
    }

    public function getBusiness()
    {
        return $this->hasOne(Business::className(), array('id' => 'attr_id'));
    }



}