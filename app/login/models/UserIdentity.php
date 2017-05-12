<?php

namespace login\models;

use common\components\CommonFunc;
use ucenter\models\UserApiAuth;
use ucenter\models\UserAppAuth;
use ucenter\models\User;
use Yii;
use yii\helpers\ArrayHelper;

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

    public $isSuperAdmin; //超级管理员

    public $isUcenterAdmin; //ucenter管理员

    public $isYunBackendAdmin; //颂唐云后台管理员
    public $isYunFrontend;      //颂唐云前台使用权限
    public $isYunFrontendAdmin;  //颂唐云前台管理员


    public $isOaBackendAdmin;  //颂唐OA后台管理员
    public $isOaFrontend;       //颂唐OA前台使用权限
    public $isOaFrontendAdmin;  //颂唐OA前台管理员  (暂时没用到)


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
        $key = 'ucenter:user/identity';
        $data = CommonFunc::getByCache(self::className(),'findIdentityOne',[$id],$key);
        return $data;
    }

    public static function findIdentityOne($id){
        $user = User::find()->where(['id'=>$id,'status'=>1])->one();
        if($user){
            return self::combineUser($user);
        }
        return null;
    }



    public static function findByUsername($username)
    {
        if(substr($username,-13)!='@songtang.net'){
            $username = $username.'@songtang.net';
        }

        $user = User::find()->where(['username'=>$username])->one();
        if($user){
            return self::combineUser($user);
        }

        return null;
    }

    public static function combineUser($user){
        $authList = UserAppAuth::getAuthList($user->id);

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
            //'authKey' => 'key-'.$user->id,
            'authKey' => UserApiAuth::getAuthKey($user->id),
            'accessToken' => 'token-'.$user->id,

            'isSuperAdmin' => $authList['isSuperAdmin'],

            'isUcenterAdmin' => $authList['isUcenterAdmin'],

            'isYunBackendAdmin' => $authList['isYunBackendAdmin'],
            'isYunFrontend' => $authList['isYunFrontend'],
            'isYunFrontendAdmin' => $authList['isYunFrontendAdmin'],

            'isOaBackendAdmin' => $authList['isOaBackendAdmin'],
            'isOaFrontend' => $authList['isOaFrontend'],
            'isOaFrontendAdmin' => $authList['isOaFrontendAdmin'],
            'status' => $user->status
        ];
        return new static($userStatic);
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

    public static function findIdentityByAccessToken($token, $type = null)
    {

        // 如果token无效的话，
        if(!UserApiAuth::apiTokenIsValid($token)) {
            throw new \yii\web\UnauthorizedHttpException("token is invalid.");
        }

        $userApiAuth = UserApiAuth::find()->where(['auth_key'=>$token])->one();

        return static::findOne(['id' => $userApiAuth->user_id, 'status' => self::STATUS_ACTIVE]);
        // throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    public function loginByAccessToken($token, $type = null)
    {
        // @var $class IdentityInterface
        $class = $this->identityClass;
        $identity = $class::findIdentityByAccessToken($token, $type);
        if ($identity && $this->login($identity)) {
            return $identity;
        } else {
            return null;
        }
    }

}
