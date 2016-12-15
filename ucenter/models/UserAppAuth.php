<?php

namespace ucenter\models;

use Yii;

class UserAppAuth extends \yii\db\ActiveRecord
{
    public function attributeLabels(){
        return [
            'app'=>'app名称',
            'uid'=>'职员ID',
            'is_enable'=>'是否可用'
        ];
    }

    public function rules()
    {
        return [
            [['app','uid'], 'required'],
            [['uid', 'is_enable'], 'integer'],
        ];
    }

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('UserAppAuth has installed');
            }else{
                $m = new UserAppAuth();
                $m->app = 'ucenter';
                $m->uid = 1;
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
