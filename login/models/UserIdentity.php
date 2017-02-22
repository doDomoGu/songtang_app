<?php

namespace login\models;

use ucenter\models\UserAppAuth;
use ucenter\models\User;
use Yii;

class UserIdentity extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $name;
    public $district_id;
    public $district;
    public $industry_id;
    public $industry;
    public $company_id;
    public $company;
    public $department_id;
    public $department;
    public $position_id;
    public $position;
    public $join_date;
    public $contract_date;
    public $authKey;
    public $accessToken;
    public $status;
    public $isSuperAdmin;
    public $isUcenterAdmin;
    public $isYunAdmin;
    public $isYunFrontend;
    public $isOaAdmin;

    /*private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];*/

    public static function findIdentity($id)
    {
        $user = User::find()->where(['id'=>$id,'status'=>1])->one();
        if($user){
            $superAdminArr = [10000];
            $appAuthList = UserAppAuth::find()->where(['user_id'=>$user->id])->all();
            $appAuthArr = [];
            foreach($appAuthList as $a){
                $appAuthArr[] = $a->app;
            }
            $userStatic = [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name'=>$user->name,
                'district_id'=>$user->district_id,
                'district'=>$user->district->name,
                'industry_id'=>$user->industry_id,
                'industry'=>$user->industry->name,
                'company_id'=>$user->company_id,
                'company'=>$user->company->name,
                'department_id'=>$user->department_id,
                'department'=>$user->getDepartmentFullRoute(),
                'position_id'=>$user->position_id,
                'position'=>$user->position->name,
                'join_date'=>$user->join_date,
                'contract_date'=>$user->contract_date,
                //'position'=>$user->position->name,
                'authKey' => 'key-'.$user->id,
                'accessToken' => 'token-'.$user->id,
                'isSuperAdmin' => in_array($user->id,$superAdminArr)?true:false,
                'isUcenterAdmin' => in_array('ucenter-admin',$appAuthArr)?true:false,
                'isYunAdmin' => in_array('yun-admin',$appAuthArr)?true:false,
                'isYunFrontend' => in_array('yun-frontend',$appAuthArr)?true:false,
                'isOaAdmin' => in_array('oa-admin',$appAuthArr)?true:false,
            ];
            return new static($userStatic);
        }
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        ##没有用到

        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }*/

        return null;
    }

    public static function findByUsername($username)
    {
        if(substr($username,-13)!='@songtang.net'){
            $username = $username.'@songtang.net';
        }

        $user = User::find()->where(['username'=>$username])->one();
        if($user){
            $userStatic = [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'authKey' => 'key-'.$user->id,
                'accessToken' => 'token-'.$user->id,
                'status' => $user->status
            ];
            return new static($userStatic);
        }

        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }


    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

}
