<?php

namespace login\models;

use ucenter\models\User;
use ucenter\models\UserApiAuth;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    const GET_API_TOKEN = 'generate_api_token';

    public function init ()
    {
        parent::init();
        $this->on(self::GET_API_TOKEN, [$this, 'onGenerateApiToken']);
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required','message'=>'请填写{attribute}'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或者密码错误');
            }
        }
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->status==1) {
                $this->addError($attribute, '该用户被禁用');
            }
        }
    }


    public function login()
    {
        if ($this->validate()) {
            $this->trigger(self::GET_API_TOKEN);
            Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);

            return $this->_user;
        } else {
            return null;
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->username = trim($this->username);
            $this->_user = UserIdentity::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * 登录校验成功后，为用户生成新的token
     * 如果token失效，则重新生成token
     */
    public function onGenerateApiToken ()
    {
        $one = UserApiAuth::find()->where(['user_id'=>$this->_user->id])->one();
        if($one){
            $token = $one->auth_key;
        }else{
            $token = '';
        }
        if (!UserApiAuth::apiTokenIsValid($token)) {
            $one = UserApiAuth::find()->where(['user_id'=>$this->_user->id])->one();
            if ($one) {
                $one->generateApiToken();
                $one->expire_time = date('Y-m-d H:i:s',strtotime('+3600 second',time()));
                $one->save();
            }else{
                $userApiAuth = new UserApiAuth();
                $userApiAuth->user_id = $this->_user->id;
                $userApiAuth->generateApiToken();
                $userApiAuth->expire_time = date('Y-m-d H:i:s',strtotime('+3600 second',time()));
                $userApiAuth->save();
            }


        }
    }


}
