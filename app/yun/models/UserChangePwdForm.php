<?php

namespace yun\models;

use Yii;
use yii\base\Model;
use ucenter\models\User;

class UserChangePwdForm extends Model
{
    public $id;
    public $password;
    public $password_new;
    public $password_new2;

    private $_user = false;

    public function rules()
    {
        return [
            [['password', 'password_new', 'password_new2'], 'required'],
            ['password','validatePassword'],
            ['password_new2','compare','compareAttribute'=>'password_new']
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find()->where(['id'=>$this->id])->one();
        }

        return $this->_user;
    }

    public function attributeLabels(){
        return [
            'password' => '原密码',
            'password_new' => '新密码',
            'password_new2' => '新密码（确认）',
        ];
    }
}
