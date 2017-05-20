<?php

use yii\db\Migration;

class m009_oa_task_user_wildcard extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_oa;
        parent::init();
    }

    public function up()
    {
        //添加 模板 发起人根据属性匹配

        $this->createTable('task_user_wildcard', [
            'id'=> $this->primaryKey(),
            'task_id'=>$this->integer(11),
            'district_id'=>$this->integer(11),
            'industry_id'=>$this->integer(11),
            'company_id'=>$this->integer(11),
            'department_id'=>$this->integer(11),
            'position_id'=>$this->integer(11)
        ]);




        /*CREATE TABLE `task_user_wildcard` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
`task_id` int(11) NOT NULL,
`district_id` int(11) NOT NULL,
`industry_id` int(11) NOT NULL,
`company_id` int(11) NOT NULL,
`department_id` int(11) NOT NULL,
`position_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8*/



    }

    public function down()
    {
        $this->dropTable('task_user_wildcard');

    }

}
