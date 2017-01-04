<?php

namespace login\models;

use common\models\UserAppAuth;
use ucenter\models\User;
use Yii;

class UserIdentity extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $name;
    public $aid;
    public $area;
    public $bid;
    public $business;
    public $did;
    public $department;
    public $position_id;
    public $position;
    public $join_date;
    public $contract_date;
    public $authKey;
    public $accessToken;
    public $status;
    public $isYunAdmin;

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
            $yunAdminExist = UserAppAuth::find()->where(['app'=>'yun-admin','user_id'=>$user->id,'is_enable'=>1])->one();
            $userStatic = [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name'=>$user->name,
                'aid'=>$user->aid,
                'area'=>$user->area->name,
                'bid'=>$user->bid,
                'business'=>$user->business->name,
                'did'=>$user->did,
                'department'=>$user->getDepartmentFullRoute(),
                'position_id'=>$user->position_id,
                'position'=>$user->position->name,
                'join_date'=>$user->join_date,
                'contract_date'=>$user->contract_date,
                //'position'=>$user->position->name,
                'authKey' => 'key-'.$user->id,
                'accessToken' => 'token-'.$user->id,
                'isYunAdmin' => $yunAdminExist?true:false
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
