<?php

namespace oa\models;
use Yii;
//oa 任务表 与 分类的关联关系   一个任务表对应多个分类
class FormCategory extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }
    
    public function attributeLabels(){
        return [
            'form_id' => '表单ID',
            'category_id' => '分类ID',
        ];
    }

    public function rules()
    {
        return [
            [['form_id','category_id'], 'required'],
            [['category_id','category_id'], 'integer'],
        ];
    }

}
