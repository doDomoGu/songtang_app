<?php

use yii\db\Migration;

class m161126_182420_create_structure_table extends Migration
{
    public function up()
    {
        $this->createTable('structure', [
            'aid'=> $this->integer(11)->notNull()->defaultValue(0),
            'bid'=> $this->integer(11)->notNull()->defaultValue(0),
            'did'=> $this->integer(11)->notNull()->defaultValue(0),
            //'p_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'ord'=> $this->integer(11)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->addPrimaryKey('pk','structure',['aid','bid','did']);
    }

    public function down()
    {
        $this->dropTable('structure');
    }
}
