<?php

namespace oa\models;

//oa 任务 发起人对应表
class OaTaskApplyUser extends \yii\db\ActiveRecord
{
    public function attributeLabels(){
        return [
            'task_id' => '任务ID',
            'user_id' => '用户ID',
        ];
    }

    public function rules()
    {
        return [
            [['task_id', 'user_id'], 'integer'],
        ];
    }

}
