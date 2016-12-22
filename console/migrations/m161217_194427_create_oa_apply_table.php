<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_apply`.  OA 申请表
 */
class m161217_194427_create_oa_apply_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_oa;
    }
    public function up()
    {
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
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('apply');
    }
}
