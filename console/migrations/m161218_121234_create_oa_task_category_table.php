<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oa_task_category`.  OA任务分类
 */
class m161218_121234_create_oa_task_category_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_oa;
    }
    public function up()
    {
        $this->createTable('task_category', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(100)->notNull(),
            'type' => $this->smallInteger(1)->notNull(),
            'ord' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'show_flag'=>$this->smallInteger(1)->notNull()->defaultValue(1),
            'status'=>$this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('task_category');
    }
}
