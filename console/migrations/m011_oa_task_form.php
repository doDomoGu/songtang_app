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
        //表单
        $this->createTable('form', [
            'id'=> $this->primaryKey(),
            'title'=>$this->string(200),
            'set_complete'=>$this->smallInteger(1),
            'status'=>$this->smallInteger(1)
        ]);

        //模板表单的分类关系表
        $this->createTable('form_category', [
            'form_id'=> $this->integer(11),
            'category_id'=> $this->integer(11)
        ]);
        $this->addPrimaryKey('primayKey','form_category',['form_id','category_id']);

        //表单内容
        $this->createTable('form_item', [
            'id'=> $this->primaryKey(),
            'form_id'=>$this->integer(11),
            'ord'=>$this->integer(5),
            'item_key'=>$this->string(200),
            'item_value'=>$this->text(),
            'status'=>$this->smallInteger(1)
        ]);

        //模板表单关系表
        $this->createTable('task_form', [
            'task_id'=> $this->integer(11),
            'form_id'=> $this->integer(11)
        ]);
        $this->addPrimaryKey('primayKey','task_form',['task_id','form_id']);


        //申请表 的 表单信息
        $this->createTable('apply_form_content', [
            'id'=> $this->primaryKey(),
            'apply_id'=>$this->integer(11),
            'ord'=>$this->integer(5),
            'item_key'=>$this->string(200),
            'item_value'=>$this->text()
        ]);

        //申请表 添加对应表单id 字段
        $this->addColumn('apply','form_id','INT(11) NOT NULL after task_id');

    }

    public function down()
    {
        $this->dropColumn('apply','form_id');
        $this->dropTable('apply_form_content');
        $this->dropTable('task_form');
        $this->dropTable('form_item');
        $this->dropTable('form_category');
        $this->dropTable('form');

    }

}
