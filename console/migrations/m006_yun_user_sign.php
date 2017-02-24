<?php

use yii\db\Migration;

class m006_yun_user_sign extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_yun;
        parent::init();
    }

    public function up()
    {

        //用户签到
        $this->createTable('user_sign', [
            'id'=> $this->primaryKey(5),
            'user_id'=>$this->integer(11),
            'point'=>$this->string(20),
            'y'=>$this->string(10),
            'm'=>$this->string(2),
            'd'=>$this->string(2),
            'sign_time' => $this->dateTime()
        ]);


        /*CREATE TABLE `user_sign` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`point` varchar(10) NOT NULL,
`y` varchar(10) NOT NULL,
`m` varchar(2) NOT NULL,
`d` varchar(2) NOT NULL,
`sign_time` datetime DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8*/
    }

    public function down()
    {

        $this->dropTable('user_sign');
    }

}
