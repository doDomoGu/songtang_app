<?php

namespace yun\models;
use Yii;
class DownloadRecord extends \yii\db\ActiveRecord
{

    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['file_id', 'user_id'], 'required'],
            [['id', 'file_id', 'user_id'], 'integer'],
            [['download_time'],'default','value'=>date('Y-m-d H:i:s')],
        ];
    }

    public function getFile(){
        return $this->hasOne(File::className(), array('id' => 'file_id'));
    }

}