<?php

use yii\db\Migration;

class m001_common_init extends Migration
{
    //db 默认 common
    public function up(){
return true;
        $this->createTable('global_config',[
            'id'=>$this->primaryKey(11),
            'name'=>$this->string(255)->notNull(),
            'value'=>$this->text(),
            'title'=>$this->string(255),
            'configable'=>$this->smallInteger(1)
        ]);



        $this->createTable('sms',[
            'id'=>$this->primaryKey(11),
            'user_id'=>$this->integer(11),
            'app'=>$this->string(255),
            'scenario'=>$this->string(255),
            'mobile'=>$this->string(255),
            'content'=>$this->string(255),
            'template_code'=>$this->string(255),
            'param'=>$this->string(255),
            'create_time'=>$this->dateTime(),
            'send_time'=>$this->dateTime(),
            'flag'=>$this->smallInteger(1),
            'response'=>$this->string(255),
            'error'=>$this->string(255)
        ]);

        $this->createTable('log_sms',[
            'id'=>$this->primaryKey(11),
            'level'=>$this->integer(11),
            'category'=>$this->string(255),
            'log_time'=>$this->double(),
            'prefix'=>$this->text(),
            'message'=>$this->text()
        ]);
        $this->createIndex('idx_log_level','log_sms','level');
        $this->createIndex('idx_log_category','log_sms','category');
    }

    public function down()
    {
        $this->dropTable('sms');
        $this->dropTable('global_config');

    }
}
