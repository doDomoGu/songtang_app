<?php

namespace oa\models;

//oa 申请 进行操作的记录
class OaApplyRecord extends \yii\db\ActiveRecord
{
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'apply_id' => '对应申请id',
            'flow_id' => '对应任务流程id',
            'result' => '结果', //1:是 0:否
            'message' => '备注/留言',
            'add_time' => '操作时间',
        ];
    }

    public function rules()
    {
        return [
            [['apply_id','flow_id','result'], 'required'],
            [['apply_id','flow_id','result'], 'integer'],
            [['message','add_time'],'safe']
        ];
    }

}
