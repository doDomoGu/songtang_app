<?php

namespace yun\models;

use ucenter\models\User;
use yii;

class SystemLog extends \yii\db\ActiveRecord
{

    public static function getDb(){
        return Yii::$app->db_yun;
    }

    const TYPE_SYSTEM   = 1;
    const TYPE_USER   = 2;

    const LEVEL_TRACE   = 1;
    const LEVEL_DEBUG   = 2;
    const LEVEL_INFO    = 3;
    const LEVEL_NOTICE  = 4;
    const LEVEL_WARN    = 5;
    const LEVEL_ERROR   = 6;
    const LEVEL_FATAL   = 7;



    public function rules()
    {
        return [
            [['type', 'message', 'uid'], 'required'],
            [['type','id', 'uid', 'level'], 'integer'],
            [['category','message'],'safe'],
            [['log_time'],'default','value'=>date('Y-m-d H:i:s')],
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'uid'));
    }

    public function getUser2($name)
    {
        return $this->hasOne(User::className(), array('id' => 'uid'))->where('name like "%'.$name.'%"');
    }


    public static function dirError($message){
        self::user_log(self::LEVEL_ERROR,'dir',$message);
    }

    public static function user_log($level,$category,$message){
        $log = new SystemLog();
        $log->type = 2;
        $log->uid = yii::$app->user->id;
        $log->level = $level;
        $log->category = $category;
        $log->message = $message;
        $log->save();
    }


    public static function system_log($level,$category,$message){
        $log = new SystemLog();
        $log->type = 1;
        $log->uid = 0;
        $log->level = $level;
        $log->category = $category;
        $log->message = $message;
        $log->save();
    }


    public static function getLevel($level_id){
        switch($level_id){
            case self::LEVEL_TRACE:
                $level = 'TRACE';
                break;
            case self::LEVEL_DEBUG:
                $level = 'DEBUG';
                break;
            case self::LEVEL_INFO:
                $level = 'INFO';
                break;
            case self::LEVEL_NOTICE:
                $level = 'NOTICE';
                break;
            case self::LEVEL_WARN:
                $level = 'WARN';
                break;
            case self::LEVEL_ERROR:
                $level = 'ERROR';
                break;
            case self::LEVEL_FATAL:
                $level = 'FATAL';
                break;
            default:
                $level = 'N/A';
        }
        return $level;
    }

    public static function getType($type_id){
        switch($type_id){
            case self::TYPE_SYSTEM:
                $level = '系统信息';
                break;
            case self::TYPE_USER:
                $level = '用户记录';
                break;
            default:
                $level = 'N/A';
        }
        return $level;
    }
}


