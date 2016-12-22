<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_flow`.  OA任务流程图
 */
class m161216_232323_create_oa_flow_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_oa;
    }
    public function up()
    {
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
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('flow');
    }
}
