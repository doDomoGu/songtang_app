<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_apply_record`.  OA 申请 流程操作记录
 */
class m161217_232311_create_oa_apply_record_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_oa;
    }
    public function up()
    {
        $this->createTable('apply_record', [
            'id' => $this->primaryKey(11),
            'apply_id' => $this->integer(11)->notNull(),
            'flow_id' => $this->integer(11)->notNull(),
            'result' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'message' => $this->text(),
            'add_time' => $this->dateTime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('apply_record');
    }
}
