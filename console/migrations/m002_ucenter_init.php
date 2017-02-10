<?php

use yii\db\Migration;

class m002_ucenter_init extends Migration
{
    public function init(){
        $this->db = Yii::$app->db_ucenter;
        parent::init();
    }
    //District      地区 (地方,行政划分)
    //Industry      行业 (产业)
    //Company       公司
    //Department    部门 (会有多层级)
    //Structure     组织架构  根据上面四个层级 相互的关系，构成
    //Position      职位 (抬头,扩展职位)

    public function up()
    {
        $this->createTable('district', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->execute('alter table `district` auto_increment = 10001');

        $this->createTable('industry', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->execute('alter table `industry` auto_increment = 10001');

        $this->createTable('company', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull()->unique(),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->execute('alter table `company` auto_increment = 10001');

        $this->createTable('department', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull(),
            'p_id' => $this->integer(11)->defaultValue(0),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);

        $this->createIndex('unique','department',['alias','p_id'],true);

        $this->execute('alter table `department` auto_increment = 10001');

        $this->createTable('structure', [
            'district_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'industry_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'company_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'department_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'ord'=> $this->integer(11)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->addPrimaryKey('pk','structure',['district_id','industry_id','company_id','department_id']);

        $this->createTable('position', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(40)->notNull(),
            'alias'=> $this->string(40)->notNull(),
            'p_id'=> $this->integer(11)->notNull()->defaultValue(0),
            'ord'=> $this->smallInteger(4)->notNull()->defaultValue(0),
            'status'=> $this->smallInteger(1)->notNull()->defaultValue(1),
        ]);
        $this->createIndex('unique','position',['alias','p_id'],true);

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username'=> $this->string(200)->notNull(),
            'password'=> $this->string(32)->notNull(),
            'password_true'=> $this->string(100)->notNull(),
            'name'=> $this->string(200)->notNull(),
            'district_id' => $this->integer(11)->notNull(),
            'industry_id' => $this->integer(11)->notNull(),
            'company_id' => $this->integer(11)->notNull(),
            'department_id' => $this->integer(11)->notNull(),
            'position_id' => $this->integer(11)->notNull(),
            'gender' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'birthday' => $this->date(),
            'join_date' => $this->date(),
            'contract_date' => $this->date(),
            'mobile' => $this->string(20),
            'phone' => $this->string(20),
            'describe' => $this->text(),
            'ord' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(1)
        ]);


        $this->createTable('user_app_auth',[
            'app'=> $this->string(20)->notNull(),
            'user_id'=> $this->integer(11)->notNull(),
            'is_enable'=> $this->smallInteger(1)->defaultValue(0)
        ]);
        $this->addPrimaryKey('pk','user_app_auth',['app','user_id']);
        $this->createIndex('app_name','user_app_auth','app');


        $this->createTable('user_wx_session',[
            'app_id'=>$this->string(49)->notNull(),
            'openid'=>$this->string(40)->notNull(),
            'wx_session'=>$this->string(100)->notNull(),
            'user_id'=> $this->integer(11),
            'ucenter_session'=>$this->string(20),
            'expire_time'=>$this->dateTime()
        ]);
        $this->addPrimaryKey('pk','user_wx_session',['app_id','openid']);

    }

    public function down(){
        $this->dropTable('user_wx_session');
        $this->dropTable('user_app_auth');
        $this->dropTable('user');
        $this->dropTable('position');
        $this->dropTable('structure');
        $this->dropTable('department');
        $this->dropTable('company');
        $this->dropTable('industry');
        $this->dropTable('district');
    }

}
