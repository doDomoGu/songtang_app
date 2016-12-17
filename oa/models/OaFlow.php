<?php

namespace oa\models;

//oa 流程
class OaFlow extends \yii\db\ActiveRecord
{
    const TYPE_APPROVAL = 1;    //审批
    const TYPE_EXAMINE  = 2;    //审核
    const TYPE_EXECUTE  = 3;    //执行
    const TYPE_WATCH    = 4;    //阅览
    const TYPE_FEEDBACK = 5;    //反馈

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'task_id' => '对应任务ID',
            'step' => '步骤数',
            'title' => '标题',
            'type' => '操作类型',
            'user_id' => '指定操作人',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['task_id','step','title','type','user_id'], 'required'],
            [['task_id','step','type','user_id'], 'integer'],
        ];
    }

}
