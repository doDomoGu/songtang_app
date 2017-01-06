<?php

use yii\db\Migration;

class m161128_194427_common_init extends Migration
{
    //db 默认 common
    public function up()
    {
        $this->createTable('user_app_auth',[
            'app'=> $this->string(20)->notNull(),
            'user_id'=> $this->integer(11)->notNull(),
            'is_enable'=> $this->smallInteger(1)->defaultValue(0)
        ]);
        $this->addPrimaryKey('pk','user_app_auth',['app','user_id']);
        $this->createIndex('app_name','user_app_auth','app');

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
        $this->dropTable('user_app_auth');
    }
}
