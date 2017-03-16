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

        //用户签到
        $this->addColumn('apply','flow_user','varchar(200)');


    }

    public function down()
    {

        $this->dropColumn('apply','flow_user');
    }

}
