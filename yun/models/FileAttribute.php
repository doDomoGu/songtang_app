<?php

namespace yun\models;

use ucenter\models\District;
use ucenter\models\Industry;
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

    public function getDistrict()
    {
        return $this->hasOne(District::className(), array('id' => 'attr_id'));
    }

    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), array('id' => 'attr_id'));
    }



}