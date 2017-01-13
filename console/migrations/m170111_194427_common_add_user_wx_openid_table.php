<?php

use yii\db\Migration;

class m170111_194427_common_add_user_wx_openid_table extends Migration
{
    //db 默认 common
    public function up()
    {
        $this->createTable('user_wx_openid',[
            'appid'=> $this->string(200)->notNull(),
            'user_id'=> $this->integer(11)->notNull(),
            'openid'=>$this->string(100)->notNull()
        ]);
        $this->addPrimaryKey('pk','user_wx_openid',['appid','user_id']);
        $this->createIndex('app','user_wx_openid','appid');


        $this->createTable('user_wx_session',[
            'key'=> $this->string(100),
            'value'=> $this->text()
        ]);
        $this->addPrimaryKey('pk','user_wx_session','key');



    }

    public function down()
    {
        $this->dropTable('user_wx_session');
        $this->dropTable('user_wx_openid');
    }
}
