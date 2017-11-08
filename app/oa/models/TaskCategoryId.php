<?php

namespace oa\models;
use Yii;
//oa 任务表 与 分类的关联关系   一个任务表对应多个分类
class TaskCategoryId extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }
    
    public function attributeLabels(){
        return [
            'task_id' => '任务表ID',
            'category_id' => '分类ID',
        ];
    }

    public function rules()
    {
        return [
            [['task_id','category_id'], 'required'],
            [['task_id','category_id'], 'integer'],
        ];
    }


    public function getCategory(){
        return $this->hasOne(TaskCategory::className(), array('id' => 'category_id'));
    }
}
