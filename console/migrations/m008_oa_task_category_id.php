<?php

use yii\db\Migration;

class m008_oa_task_category_id extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_oa;
        parent::init();
    }

    public function up()
    {

        //删除任务表category_id字段   从一对一关系变化到一对多
        $this->dropColumn('task','category_id');   //`category_id` int(11) NOT NULL,

        //任务表和分类的对方关系表  一对多
        $this->createTable('task_category_id', [
            'task_id'=> $this->integer(11),
            'category_id'=>$this->integer(11)
        ]);


    }

    public function down()
    {
        $this->dropTable('task_category_id');

        $this->addColumn('task','category_id','int(11) NOT NULL after `title`');
    }

}
