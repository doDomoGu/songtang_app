<?php

use yii\db\Migration;

class m005_yun_add_group_user extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_yun;
        parent::init();
    }

    public function up()
    {
        $this->createTable('group', [
            'id'=> $this->primaryKey(5),
            'name' => $this->string(100)->notNull(),
            'describe'=> $this->string(200),
            'status' => $this->smallInteger(1)
        ]);

        $this->createTable('group_user', [
            'group_id'=> $this->integer(11)->notNull(),
            'user_id'=> $this->integer(11)->notNull()
        ]);
        $this->addPrimaryKey('pk','group_user',['group_id','user_id']);

        $this->createTable('group_user_permission', [
            'group_id'=> $this->integer(11)->notNull(),
            'dir_id'=> $this->integer(11)->notNull(),
            'type' => $this->smallInteger(4)->notNull()
        ]);

    }

    public function down()
    {
        $this->dropTable('group_user_permission');
        $this->dropTable('group_user');
        $this->dropTable('group');
    }

}
