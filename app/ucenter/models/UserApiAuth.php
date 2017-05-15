<?php

namespace ucenter\models;

use Yii;

class UserApiAuth extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }


    /**
     * 生成 api_token
     */
    public function generateApiToken()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 校验api_token是否有效
     */
    public static function apiTokenIsValid($token)
    {
        if (empty($token)) {
            return false;
        }else {

            /*$timestamp = (int) substr($token, strrpos($token, '_') + 1);
            $expire = Yii::$app->params['user.apiTokenExpire'];
            return $timestamp + $expire >= time();*/


            $one = self::find()->where(['auth_key'=>$token])->one();
            if ($one) {
                return strtotime($one->expire_time) >= time();
            } else {
                return false;
            }
        }
    }

    public static function getAuthKey($user_id){
        $one = self::find()->where(['user_id'=>$user_id])->one();
        if($one)
            return $one->auth_key;
        else
            return null;
    }
}
