<?php

namespace oa\models;
use ucenter\models\Area;
use ucenter\models\Business;
use Yii;
//oa 任务表 (即预先分配好的oa流程环节)
class Task extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '任务名称',
            'category_id' => '任务分类',
            'area_id' => '锁定地区',
            'business_id' => '锁定业态',
            'department_id' => '锁定部门',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['ord', 'category_id', 'status', 'area_id', 'business_id', 'department_id'], 'integer'],
        ];
    }

    public function getCategory(){
        return $this->hasOne(TaskCategory::className(), array('id' => 'category_id'));
    }



    public function getArea(){
        return $this->hasOne(Area::className(), array('id' => 'area_id'));
    }

    public function getBusiness(){
        return $this->hasOne(Business::className(), array('id' => 'business_id'));
    }

}
