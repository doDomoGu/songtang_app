<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_flow`.  OA任务流程图
 */
class m161216_232323_create_oa_flow_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('oa_flow', [
            'task_id'=> $this->integer(11)->notNull(),
            'user_id'=> $this->integer(11)->notNull()
        ]);
        $this->addPrimaryKey('pk','oa_flow',['task_id','user_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('oa_apply_user');
    }
}
