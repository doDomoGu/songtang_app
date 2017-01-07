<?php

namespace ucenter\models;

use Yii;
use yii\base\Model;

class UserForm extends Model
{
    public $id;
    public $username;
    public $password;
    public $password2;
    public $reg_code;
    public $forgetpw_code;
    public $name;
    public $is_admin;
    public $aid;
    public $bid;
    public $did;
    public $position_id;
    public $gender;
    public $birthday;
    public $join_date;
    public $contract_date;
    public $mobile;
    public $phone;
    public $describe;
    public $ord;
    public $status;

    public function attributeLabels(){
        return [
            'username' => '用户名(邮箱)',
            'password' => '密码',
            'password2' => '密码(再输入一次)',
            'reg_code' => '注册码',
            'forgetpw_code' => '忘记密码验证码',
            'name' => '姓名',
            //'is_admin' => '是否为管理员',
            'aid' => '地区',
            'bid' => '业态',
            'did' => '部门',
            'position_id' => '职位',
            'gender' => '性别',
            'birthday' => '生日',
            'join_date' => '入职日期',
            'contract_date' => '合同到期日期',
            'mobile' => '联系电话(手机)',
            'phone' => '联系电话(座机)',
            'describe' => '其他备注',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function scenarios(){
        return [
            'create'=>[
                'username',
                'password',
                'password2',
                'reg_code',
                'forgetpw_code',
                'name',
                'aid',
                'bid',
                'did',
                'position_id',
                'gender',
                'birthday',
                'ord',
                'status',
                'join_date',
                'contract_date',
                'mobile',
                'phone',
                'describe',
                'ord',
                'status',
            ],
            'update'=>[
                'id',
                'username',
                'password',
                'password2',
                'reg_code',
                'forgetpw_code',
                'name',
                'aid',
                'bid',
                'did',
                'position_id',
                'gender',
                'birthday',
                'ord',
                'status',
                'join_date',
                'contract_date',
                'mobile',
                'phone',
                'describe',
                'ord',
                'status',
            ]
        ];
    }


    public function rules()
    {
        return [
            /*[['password','password2'],'required','on'=>'create'],*/
            [['username', 'name', 'ord', 'status'], 'required'],
            [['id', 'ord', 'status','aid','bid','did', 'position_id', 'gender'], 'integer'],
            ['username','unique','on'=>'create', 'targetClass' =>User::className(), 'message' => '此用户名已经被使用。'],
            ['username','email'],
            ['password2', 'compare','compareAttribute'=>'password'],
            [['reg_code', 'forgetpw_code', 'join_date', 'contract_date', 'mobile', 'phone', 'describe'], 'safe']
        ];
    }

}
