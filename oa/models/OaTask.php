<?php

namespace oa\models;

//oa 任务表 (即预先分配好的oa流程环节)
class OaTask extends \yii\db\ActiveRecord
{
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '任务名称',
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
            [['ord', 'status', 'area_id', 'bussiness_id', 'department_id'], 'integer'],
        ];
    }

}
