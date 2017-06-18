<?php

use yii\db\Migration;

class m011_oa_form extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_oa;
        parent::init();
    }

    public function up()
    {
        //添加 表单
        $this->createTable('form', [
            'id'=> $this->primaryKey(),
            'title'=>$this->string(200),
            'status'=>$this->smallInteger(1)
        ]);

        //添加 模板表单的分类关系表
        $this->createTable('form_category', [
            'form_id'=> $this->integer(11),
            'category_id'=> $this->integer(11)
        ]);
        $this->addPrimaryKey('primayKey','form_category',['form_id','category_id']);

        //添加 表单内容
        $this->createTable('form_item', [
            'id'=> $this->primaryKey(),
            'form_id'=>$this->integer(11),
            'ord'=>$this->integer(5),
            'item_key'=>$this->string(11),
            'item_value'=>$this->text(),
            'status'=>$this->smallInteger(1)
        ]);

        //添加 模板表单关系表
        $this->createTable('task_form', [
            'task_id'=> $this->integer(11),
            'form_id'=> $this->integer(11)
        ]);
        $this->addPrimaryKey('primayKey','task_form',['task_id','form_id']);

    }

    public function down()
    {
        $this->dropTable('task_form');
        $this->dropTable('form_item');
        $this->dropTable('form_category');
        $this->dropTable('form');

    }

}
