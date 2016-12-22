<?php

use yii\db\Migration;

/**
 * Handles the creation for table `position`.
 */
class m161126_184427_create_position_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_ucenter;
    }
    public function up()
    {
        $this->createTable('position', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull(),
            'p_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->createIndex('a','position',['alias','p_id'],true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('position');
    }
}
