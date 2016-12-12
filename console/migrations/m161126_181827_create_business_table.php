<?php

use yii\db\Migration;

/**
 * Handles the creation for table `business`.
 */
class m161126_181827_create_business_table extends Migration
{
    public function up()
    {
        $this->createTable('business', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
    }

    public function down()
    {
        $this->dropTable('business');
    }
}
