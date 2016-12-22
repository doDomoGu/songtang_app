<?php

use yii\db\Migration;

class m161222_094157_ucenter_init extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_ucenter;
        parent::init();
    }

    public function up()
    {
        $this->createTable('area', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->createTable('business', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->createTable('department', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull(),
            'p_id' => $this->integer(11)->defaultValue(0),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->createIndex('unique','department',['alias','p_id'],true);

        $this->createTable('structure', [
            'aid'=> $this->integer(11)->notNull()->defaultValue(0),
            'bid'=> $this->integer(11)->notNull()->defaultValue(0),
            'did'=> $this->integer(11)->notNull()->defaultValue(0),
            'ord'=> $this->integer(11)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->addPrimaryKey('pk','structure',['aid','bid','did']);

        $this->createTable('position', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull(),
            'p_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->createIndex('unique','position',['alias','p_id'],true);

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username'=> $this->string(200)->notNull(),
            'password'=> $this->string(32)->notNull(),
            'password_true'=> $this->string(100)->notNull(),
            'name'=> $this->string(200)->notNull(),
            'aid' => $this->integer(11)->notNull(),
            'bid' => $this->integer(11)->notNull(),
            'did' => $this->integer(11)->notNull(),
            'position_id' => $this->integer(11)->notNull(),
            'gender' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'birthday' => $this->date(),
            'join_date' => $this->date(),
            'contract_date' => $this->date(),
            'mobile' => $this->string(20),
            'phone' => $this->string(20),
            'describe' => $this->text(),
            'ord' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(1)
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
        $this->dropTable('position');
        $this->dropTable('structure');
        $this->dropTable('department');
        $this->dropTable('business');
        $this->dropTable('area');
    }

}
