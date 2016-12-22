<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_app_auth`.  控制用户是否可以使用某个app的权限
 */
class m161128_194427_create_user_app_auth_table extends Migration
{
    //db 默认 common
    public function up()
    {
        $this->createTable('user_app_auth', [
            'app'=> $this->string(20)->notNull(),
            'user_id'=> $this->integer(11)->notNull(),
            'is_enable'=> $this->smallInteger(1)->defaultValue(0)
        ]);
        $this->addPrimaryKey('pk','user_app_auth',['app','user_id']);
        $this->createIndex('app_name','user_app_auth','app');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_app_auth');
    }
}
