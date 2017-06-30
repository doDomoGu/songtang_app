<?php

use yii\db\Migration;

class m012_ucenter_user_history extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_yun;
        parent::init();
    }

    public function up()
    {
        $this->createTable('user_history', [
            'id'=> $this->primaryKey(),
            'user_id'=>$this->integer(11)->unsigned()->defaultValue(0),
            'url'=>$this->text(),
            'controller'=>$this->string(50),
            'action'=>$this->string(50),
            'request'=>$this->string(255),
            'request_method' => $this->string(20),
            'response'=>$this->string(255),
            'ip'=>$this->string(50),
            'user_agent'=>$this->text(),
            'referer'=>$this->text(),
            'add_time'=>$this->timestamp()
        ]);
        $this->createIndex('ctrl','user_history','controller');
        $this->createIndex('act','user_history','action');

    }

    public function down()
    {
        $this->dropTable('user_history');

    }

}
