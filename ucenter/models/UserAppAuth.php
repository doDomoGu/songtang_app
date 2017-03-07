<?php

namespace ucenter\models;

use Yii;

class UserAppAuth extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels(){
        return [
            'app'=>'app名称',
            'user_id'=>'职员ID',
            'is_enable'=>'是否可用'
        ];
    }

    public function rules()
    {
        return [
            [['app','user_id'], 'required'],
            [['user_id', 'is_enable'], 'integer'],
        ];
    }

    public static function getAppArr(){
        return [
            'ucenter-admin',
            'yun-admin',
            'yun-frontend',
            'oa-admin',
            'oa-frontend'
        ];
    }

    public static function getAppCnArr(){
        return [
            'ucenter-admin'=>'职员信息管理',
            'yun-admin'=>'颂唐云后台',
            'yun-frontend'=>'颂唐云前台',
            'oa-admin'=>'OA后台',
            'oa-frontend'=>'OA前台'
        ];
    }

    public static function hasAuth($user_id,$app){
        $exist = self::find()->where(['user_id'=>$user_id,'app'=>$app])->one();
        if($exist)
            return true;
        else
            return false;
    }




    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('UserAppAuth has installed');
            }else{
                $adminUser = User::find()->where(['username'=>'admin@songtang.net'])->one();


                $m = new UserAppAuth();
                $m->app = 'ucenter-admin';
                $m->user_id = $adminUser->id;
                $m->is_enable = 1;
                $m->save();

                $m = new UserAppAuth();
                $m->app = 'oa-admin';
                $m->user_id = $adminUser->id;
                $m->is_enable = 1;
                $m->save();

                $m = new UserAppAuth();
                $m->app = 'yun-admin';
                $m->user_id = $adminUser->id;
                $m->is_enable = 1;
                $m->save();

                $m = new UserAppAuth();
                $m->app = 'yun-frontend';
                $m->user_id = $adminUser->id;
                $m->is_enable = 1;
                $m->save();

                echo 'UserAppAuth install finish'."<br/>";
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

    public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }
}
