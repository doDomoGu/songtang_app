<?php

namespace oa\models;
use Yii;
//oa 申请 进行操作的记录
class ApplyRecord extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'apply_id' => '对应申请id',
            'flow_id' => '对应任务流程id',
            'step' => '步骤数',
            'title' => '标题',
            'type' => '操作类型',
            'user_id' => '实际操作人ID',
            'result' => '操作结果', //1:是 0:否   具体参考 Flow 中 Result开头的常量
            'message' => '备注/留言',
            'attachment' => '附件',
            'add_time' => '操作时间',
        ];
    }

    public function rules()
    {
        return [
            [['apply_id','flow_id','result','user_id','type','step','title'], 'required'],
            [['apply_id','flow_id','result','user_id','type','step'], 'integer'],
            [['message','add_time','attachment'],'safe']
        ];
    }

    public function getFlow(){
        return $this->hasOne(Flow::className(), array('id' => 'flow_id'));
    }
}
