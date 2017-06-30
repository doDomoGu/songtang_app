<?php

namespace ucenter\models;
use Yii;

class  UserHistory extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels()
    {
        return [
            'id'=>'操作记录ID',
            'user_id'=>'用户ID',
            'url'=>'完整url',
            'controller'=>'控制器',
            'action'=>'动作',
            'request_method'=>'请求方式',
            'request'=>'请求参数',
            'response'=>'响应',
            'ip'=>'操作人IP地址',
            'user_agent'=>'操作人浏览器代理商',
            'referer'=>'上一个页面，引用页',
            'add_time'=>'记录时间',
        ];
    }


    public function rules()
    {
        return [
            [['url'], 'required'],
            [['id','user_id'], 'integer'],
            [['controller','action','request','request_method','response','ip','user_agent','referer','add_time'],'safe']
        ];
    }

/*CREATE TABLE `user_history` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(11) unsigned DEFAULT '0',
`url` varchar(255) not null,
`controller` varchar(50) null,
`action` varchar(50) null,
`request` varchar(255) null,
`request_type` varchar(10) null,
`response` varchar(255) null,
`ip` varchar(50) null,
`user_agent` varchar(200) null,
`referer` varchar(255) null,
`add_time` timestamp,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8*/

    public function getRoom(){
        return $this->hasOne('app\models\Room', array('game_id' => 'id'));
    }


    public static function getValues($name){
        $arr = [];
        $list = self::find()->select($name)->groupBy($name)->all();
        foreach($list as $l){
            if($l->$name!='')
                $arr[$l->$name] = $l->$name;
        }
        return $arr;
    }

}