<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_task`.  OA任务表
 */
class m161214_194427_create_oa_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('oa_task', [
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
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('oa_task');
    }
}
