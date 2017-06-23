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


    public static function getFileIdsByDistrict($district_ids){
        $fidArr = [];
        $faList = FileAttribute::find()->where(['attr_type'=>Attribute::TYPE_DISTRICT,'attr_id'=>explode('_',$district_ids)])->groupBy('file_id')->all();
        foreach($faList as $l){
            $fidArr[] = $l->file_id;
        }
        return $fidArr;
    }

    public static function getFileIdsByIndustry($industry_ids){
        $fidArr = [];
        $faList = FileAttribute::find()->where(['attr_type'=>Attribute::TYPE_INDUSTRY,'attr_id'=>explode('-',$industry_ids)])->groupBy('file_id')->all();
        foreach($faList as $l){
            $fidArr[] = $l->file_id;
        }
        return $fidArr;
    }


}