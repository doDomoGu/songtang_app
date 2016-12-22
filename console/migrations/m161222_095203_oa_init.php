<?php

use yii\db\Migration;

class m161222_095203_oa_init extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_oa;
        parent::init();
    }

    public function up()
    {
        $this->createTable('task', [
            'id'=> $this->primaryKey(5),
            'title' => $this->string(100)->notNull(),
            'category_id' => $this->integer(11)->notNull(),
            'area_id' => $this->integer(11)->notNull()->defaultValue(0),
            'business_id' => $this->integer(11)->notNull()->defaultValue(0),
            'department_id' => $this->integer(11)->notNull()->defaultValue(0),
            'ord' => $this->smallInteger(4),
            'set_complete' => $this->smallInteger(1)->defaultValue(0),
            'status' => $this->smallInteger(1)
        ]);

        $this->createTable('task_apply_user', [
            'task_id'=> $this->integer(11)->notNull(),
            'user_id'=> $this->integer(11)->notNull()
        ]);
        $this->addPrimaryKey('pk','task_apply_user',['task_id','user_id']);

        $this->createTable('task_category', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(100)->notNull(),
            'type' => $this->smallInteger(1)->notNull(),
            'ord' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'show_flag'=>$this->smallInteger(1)->notNull()->defaultValue(1),
            'status'=>$this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->createTable('flow', [
            'id' => $this->primaryKey(11),
            'task_id' => $this->integer(11)->notNull(),
            'step' => $this->smallInteger(2)->notNull(),
            'title' => $this->string(100),
            'type' => $this->smallInteger(1),
            'user_id' => $this->integer(11)->notNull(),
            'back_step' => $this->smallInteger(2),
            'status' => $this->smallInteger(1)
        ]);

        $this->createTable('apply', [
            'id' => $this->primaryKey(11),
            'title' => $this->string(100)->notNull(),
            'user_id' => $this->integer(11)->notNull()->defaultValue(0),
            'task_id' => $this->integer(11)->notNull()->defaultValue(0),
            'flow_step' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'message' => $this->text(),
            'add_time' => $this->dateTime(),
            'edit_time' => $this->dateTime(),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(1)
        ]);

        $this->createTable('apply_record', [
            'id' => $this->primaryKey(11),
            'apply_id' => $this->integer(11)->notNull(),
            'flow_id' => $this->integer(11)->notNull(),
            'result' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'message' => $this->text(),
            'add_time' => $this->dateTime(),
        ]);

    }

    public function down()
    {
        $this->dropTable('apply_record');
        $this->dropTable('apply');
        $this->dropTable('flow');
        $this->dropTable('task_category');
        $this->dropTable('task_apply_user');
        $this->dropTable('task');
    }

}
