<?php

use yii\db\Migration;

class m010_oa_task_flow_record extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_oa;
        parent::init();
    }

    public function up()
    {

        //记录申请操作 不读flow对应内容  记录实际操作内容
        $this->addColumn('apply_record','step','smallint(4) after `flow_id`');  //步骤数
        $this->addColumn('apply_record','title','varchar(200) after `step`');  //标题
        $this->addColumn('apply_record','type','smallint(1) after `title`');  //操作类型
        $this->addColumn('apply_record','user_id','int(11) after `type`');  //操作人
        $this->addColumn('apply_record','attachment','text after `user_id`');  //附件

    }

    public function down()
    {
        $this->dropColumn('apply_record','step');
        $this->dropColumn('apply_record','title');
        $this->dropColumn('apply_record','type');
        $this->dropColumn('apply_record','user_id');
        $this->dropColumn('apply_record','attachment');

    }

}
