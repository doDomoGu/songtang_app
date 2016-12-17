<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_apply`.  OA 申请表
 */
class m161217_194427_create_oa_apply_table extends Migration
{
    public function up()
    {
        $this->createTable('oa_apply', [
            'id' => $this->primaryKey(11),
            'title' => $this->string(100)->notNull(),
            'user_id' => $this->integer(11)->notNull()->defaultValue(0),
            'task_id' => $this->integer(11)->notNull()->defaultValue(0),
            'flow_step' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'add_time' => $this->dateTime(),
            'edit_time' => $this->dateTime(),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(1)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('oa_apply');
    }
}
