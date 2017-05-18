<?php

namespace ucenter\models;

use common\components\CommonFunc;
use Yii;
use yii\helpers\ArrayHelper;

class User extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;

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
            'district_id' => '地区id',
            'industry_id' => '行业id',
            'company_id' => '公司id',
            'department_id' => '部门id',
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
            [['id', 'ord', 'status', 'district_id','industry_id','company_id','department_id','position_id', 'gender'], 'integer'],
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
                $district = District::find()->where(['alias'=>'default'])->one();
                $industry = Industry::find()->where(['alias'=>'default'])->one();
                $company = Company::find()->where(['alias'=>'default'])->one();
                $department = Department::find()->where(['alias'=>'default'])->one();
                $position = Position::find()->where(['alias'=>'default','p_id'=>0])->one();

                $m = new User();
                $m->username = 'admin@songtang.net';
                $m->password = md5('123123');
                $m->password_true = '123123';
                $m->district_id = $district->id;
                $m->industry_id = $industry->id;
                $m->company_id = $company->id;
                $m->department_id = $department->id;
                $m->name = '管理员';
                $m->position_id = $position->id;
                $m->ord = 1;
                $m->status = 1;
                $m->save();
                /*$arr = [
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
                    $m->username = $a[0].'@songtang.net';
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
                }*/
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


    public function getDistrict(){
        return $this->hasOne(District::className(), array('id' => 'district_id'));
    }

    public function getIndustry(){
        return $this->hasOne(Industry::className(), array('id' => 'industry_id'));
    }

    public function getCompany(){
        return $this->hasOne(Company::className(), array('id' => 'company_id'));
    }

    public function getDepartmentFullRoute($separator=' > '){
        return Department::getFullRoute($this->department_id,$separator);
    }

    public function getPosition(){
        return $this->hasOne(Position::className(), array('id' => 'position_id'));
    }

    public function getFullPositionRoute($separator = ' > '){
        $str = '';
        $str .= $this->district->name.$separator;
        $str .= $this->industry->name.$separator;
        $str .= $this->company->name.$separator;
        $str .= $this->getDepartmentFullRoute().$separator;
        $str .= $this->position->name;
        return $str;
    }

    public function getFullPositionRouteByCache($separator = ' > '){
        $cache = yii::$app->cache;
        $key = 'userPositionFullRoute';
        if(isset($cache[$key]) && isset($cache[$key][$this->id])){
            $str = $cache[$key][$this->id];
        }else {
            $str = $this->getFullPositionRoute($separator);
            if(isset($cache[$key])){
                $arr = [$this->id => $str];
            }else{
                $arr = ArrayHelper::merge($cache[$key],[$this->id => $str]);
            }
            $cache[$key] = $arr;
        }
        return $str;
    }

    public function getFullRoute($separator = ' > '){
        $str = '';
        $str .= $this->district->name.$separator;
        $str .= $this->industry->name.$separator;
        $str .= $this->company->name.$separator;
        $str .= $this->getDepartmentFullRoute().$separator;
        $str .= $this->position->name.$separator;
        $str .= $this->name;

        return $str;
    }


    public static function getItems(){
        $items = [];
        $list = self::find()->where(['status'=>1])->all();
        foreach($list as $l){
            $items[$l->id] = $l->getFullRoute();
        }
        return $items;
    }







}
