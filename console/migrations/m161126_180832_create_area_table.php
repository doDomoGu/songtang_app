<?php

use yii\db\Migration;

/**
 * Handles the creation for table `area`.
 */
class m161126_180832_create_area_table extends Migration
{
    public function init(){
        parent::init();
        $this->db = Yii::$app->db_ucenter;
    }
    public function up()
    {
        $this->createTable('area', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
    }

    public function down()
    {
        $this->dropTable('area');
    }
}
