<?php

namespace oa\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => '用户名(邮箱)',
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

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->username = trim($this->username);
            $this->_user = UserIdentity::findByUsername($this->username);
        }

        return $this->_user;
    }



}
