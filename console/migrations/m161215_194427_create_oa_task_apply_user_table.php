<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_task_apply_user`.  OA任务申请人对应关系
 */
class m161215_194427_create_oa_task_apply_user_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_oa;
    }
    public function up()
    {
        $this->createTable('task_apply_user', [
            'task_id'=> $this->integer(11)->notNull(),
            'user_id'=> $this->integer(11)->notNull()
        ]);
        $this->addPrimaryKey('pk','task_apply_user',['task_id','user_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('task_apply_user');
    }
}
