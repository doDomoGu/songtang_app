<?php

use yii\db\Migration;

class m161126_182319_create_department_table extends Migration
{
    public function up()
    {
        $this->createTable('department', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull(),
            'p_id' => $this->integer(11)->defaultValue(0),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->createIndex('a','department',['alias','p_id'],true);
    }

    public function down()
    {
        $this->dropTable('department');
    }
}
