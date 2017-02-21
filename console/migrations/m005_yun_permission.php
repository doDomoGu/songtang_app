<?php

use yii\db\Migration;

class m005_yun_permission extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_yun;
        parent::init();
    }

    public function up()
    {
        //目录权限
        $this->createTable('dir_permission',[
            'dir_id'=>$this->integer(11),
            'permission_type' => $this->smallInteger(1), //权限类型 1.常规  2.限制文件属性(地区,行业,公司)和用户属性保持一致
            'user_match_type' => $this->smallInteger(1), //用户匹配模式 1.全员 2.单一用户 3.通配  wildcard表  4.用户组 group
            'user_match_param_id'=>$this->integer(11), //根据匹配模式 代表不同含义
            'operation' => $this->smallInteger(1),
            'mode' => $this->smallInteger(1)
        ]);
        $this->addPrimaryKey('pk','dir_permission',['dir_id','permission_type', 'user_match_type','user_match_param_id','operation','mode']);

        //用户组
        $this->createTable('user_group', [
            'id'=> $this->primaryKey(5),
            'name' => $this->string(100)->notNull(),
            'describe'=> $this->string(200),
            'status' => $this->smallInteger(1)
        ]);

        //用户组关联的用户
        $this->createTable('user_group_user', [
            'group_id'=> $this->integer(11)->notNull(),
            'user_id'=> $this->integer(11)->notNull()
        ]);
        $this->addPrimaryKey('pk','group_user',['group_id','user_id']);

        //用户通配关联
        $this->createTable('user_wildcard', [
            'id'=> $this->primaryKey(5),
            'district_id'=> $this->integer(11)->notNull(),
            'industry_id'=> $this->integer(11)->notNull(),
            'company_id'=> $this->integer(11)->notNull(),
            'department_id'=> $this->integer(11)->notNull(),
            'position_id'=> $this->integer(11)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('user_wildcard');
        $this->dropTable('user_group_user');
        $this->dropTable('user_group');
        $this->dropTable('dir_permission');
    }

}
