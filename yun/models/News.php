<?php

namespace yun\models;
use Yii;

class News extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function beforeSave($insert){
        if($this->scenario=='update')
            $this->edit_time = date('Y-m-d H:i:s');
        return true;
    }

    public function scenarios(){
        return [
            'create'=>['title', 'content', 'ord', 'status', 'img_url', 'link_url', 'add_time', 'edit_time'],
            'update'=>['id', 'title', 'content', 'ord', 'status', 'img_url', 'link_url', 'edit_time']
        ];
    }

    public function rules()
    {
        return [
            [['title', 'content', 'ord', 'status'], 'required'],
            [['id', 'ord', 'status'], 'integer'],
            [['add_time', 'edit_time'],'default','value'=>date('Y-m-d H:i:s')],
            [['add_time', 'edit_time', 'img_url', 'link_url'], 'safe']

        ];
    }

}
