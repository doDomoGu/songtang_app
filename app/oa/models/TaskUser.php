<?php

namespace oa\models;

//oa 任务发起人 对应匹配表

use ucenter\models\User;
use Yii;

class TaskUser extends \yii\db\ActiveRecord
{
    const TYPE_ALL              = 1;   //全体职员
    const TYPE_USER             = 2;   //单个职员
    const TYPE_WILDCARD         = 3;   //前四个的任意组合
    const TYPE_GROUP            = 7;   //权限用户组

    const TYPE_ALL_CN              = '全体职员';   //全体职员
    const TYPE_USER_CN             = '单一职员';   //单独的USER_ID
    const TYPE_WILDCARD_CN         = '通配';       //地区/行业/公司等等属性 通配
    const TYPE_GROUP_CN            = '用户组';     //用户组

    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'task_id' => '任务ID',
            //'user_id' => '用户ID',
        ];
    }

    public function rules()
    {
        return [
            [['task_id', 'user_match_type','user_match_param_id'], 'integer'],
        ];
    }


    /*public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }*/

}
