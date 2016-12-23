<?php

use yii\db\Migration;

class m161222_142522_yun_init extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_yun;
        parent::init();
    }

    public function up()
    {
        $this->createTable('recruitment', [
            'id'=> $this->primaryKey(5),
            'title'=> $this->string(255)->notNull(),
            'content'=> $this->text(),
            'img_url'=> $this->string(255),
            'link_url'=> $this->string(255),
            'ord' => $this->smallInteger(4)->unsigned(),
            'status' => $this->smallInteger(1),
            'add_time' => $this->dateTime(),
            'edit_time' => $this->dateTime()
        ]);

        $this->createTable('news',[
            'id'=> $this->primaryKey(5),
            'title'=> $this->string(255)->notNull(),
            'content'=> $this->text(),
            'img_url'=> $this->string(255),
            'link_url'=> $this->string(255),
            'ord' => $this->smallInteger(4)->unsigned(),
            'status' => $this->smallInteger(1),
            'add_time' => $this->dateTime(),
            'edit_time' => $this->dateTime()
        ]);

        $this->createTable('dir',[
            'id'=> $this->primaryKey(11),
            'name'=> $this->string(100)->notNull(),
            'alias'=> $this->string(255),
            'describe'=> $this->text(),
            'type'=> $this->smallInteger(4)->unsigned()->notNull(),
            'more_cate' => $this->smallInteger(1),
            'p_id'=>$this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'level'=>$this->integer(4)->unsigned()->notNull()->defaultValue(0),
            'is_leaf'=>$this->smallInteger(1),
            'is_last'=>$this->smallInteger(1),
            'ord' => $this->smallInteger(4),
            'status'=>$this->smallInteger(1),
        ]);

        $this->createTable('file',[
            'id' => $this->primaryKey(11),
            'filename'=> $this->string(200),
            'filesize'=> $this->integer(11),
            'filetype'=> $this->smallInteger(4),
            'dir_id'=>$this->integer(11),
            'p_id'=> $this->integer(11),
            'filename_real'=>$this->string(50),
            'user_id'=> $this->integer(11),
            'clicks'=> $this->integer(11),
            'add_time'=>$this->dateTime(),
            'edit_time'=>$this->dateTime(),
            'describe'=>$this->text(),
            'ord'=>$this->smallInteger(4),
            'flag' => $this->smallInteger(1),
            'status'=>$this->smallInteger(1),
            'parent_status'=>$this->smallInteger(1)
        ]);
    }

    public function down()
    {
        $this->dropTable('file');
        $this->dropTable('dir');
        $this->dropTable('news');
        $this->dropTable('recruitment');
    }


}
