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

    public function getFlow(){
        return $this->hasOne(Flow::className(), array('id' => 'flow_id'));
    }
}
