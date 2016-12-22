<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m161128_184427_create_user_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_ucenter;
    }
    public function up()
    {
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

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
