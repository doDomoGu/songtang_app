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

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('UserAppAuth has installed');
            }else{
                $adminUser = User::find()->where(['username'=>'admin@songtang.net'])->one();


                $m = new UserAppAuth();
                $m->app = 'ucenter';
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
}
