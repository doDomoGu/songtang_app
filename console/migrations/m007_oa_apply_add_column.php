<?php

use yii\db\Migration;

class m007_oa_apply_add_column extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_oa;
        parent::init();
    }

    public function up()
    {

        //自由选择操作人
        $this->addColumn('apply','flow_user','varchar(200) after `flow_step`');

        //流程图标
        $this->addColumn('flow','icon','varchar(20) after `type`');

    }

    public function down()
    {

        $this->dropColumn('apply','flow_user');

        $this->dropColumn('flow','icon');
    }

}
