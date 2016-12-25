<?php

namespace ucenter\models;

use Yii;

class User extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function attributeLabels(){
        return [
            'username' => '用户名(邮箱)',
            'password' => '密码',
            'password_true' => '密码22',
            'name' => '姓名',
            'aid' => '地区id',
            'bid' => '业态id',
            'did' => '部门id',
            'position_id' => '职位id',
            'gender' => '性别',
            'birthday' => '生日',
            'join_date' => '入职日期',
            'contract_date' => '合同到期日期',
            'mobile' => '联系手机',
            'phone' => '联系电话',
            'describe' => '其他备注',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password', 'name', 'ord', 'status'], 'required'],
            ['username','unique'],
            [['id', 'ord', 'status', 'position_id', 'gender'], 'integer'],
            //['username','email'],
            ['username','unique','on'=>'create', 'targetClass' => self::className(), 'message' => '此用户名已经被使用。'],
            [[ 'birthday', 'join_date', 'contract_date', 'mobile', 'phone', 'describe','password_true'], 'safe']

        ];
    }

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('User has installed');
            }else{


                $m = new User();
                $m->username = 'admin@songtang.net';
                $m->password = md5('123123');
                $m->password_true = '123123';
                $m->aid = 1;
                $m->bid = 1;
                $m->did = 1;
                $m->name = '管理员';
                $m->position_id = 1;
                $m->ord = 1;
                $m->status = 1;
                $m->save();
                $arr = [
                    ['zj','张总监'],
                    ['zjl','张总经理'],
                    ['zj2','王总监'],
                    ['zjl2','许总监'],
                    ['pt','张三'],
                    ['pt2','李四'],
                    ['pt3','王五'],
                    ['cw','李财务'],
                    ['cw2','张财务'],
                    ['zg','张主管'],
                    ['zg2','徐主管'],
                    ['xz','李行政'],
                    ['xz2','顾行政'],
                    ['rs','顾人事'],
                    ['rs2','张人事'],
                ];

                foreach($arr as $a){
                    $m = new User();
                    $m->username = $a[0];
                    $m->password = md5('123123');
                    $m->password_true = '123123';
                    $m->aid = 1;
                    $m->bid = 1;
                    $m->did = 1;
                    $m->name = $a[1];
                    $m->position_id = 1;
                    $m->ord = 1;
                    $m->status = 1;
                    $m->save();
                }
                echo 'User install finish'."<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            echo '<br/>';
            return false;
        }
    }


    public function getArea(){
        return $this->hasOne(Area::className(), array('id' => 'aid'));
    }

    public function getBusiness(){
        return $this->hasOne(Business::className(), array('id' => 'bid'));
    }

    public function getDepartmentFullRoute($separator=' > '){
        return Department::getFullRoute($this->did,$separator);
    }

    public function getPosition(){
        return $this->hasOne(Position::className(), array('id' => 'position_id'));
    }
}
