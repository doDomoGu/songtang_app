<?php

namespace oa\models;


use Yii;

class Attachment extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['filename', 'filename_real', 'user_id', 'filesize', 'filetype'], 'required'],
            [['id', 'filesize', 'filetype', 'status', 'dir_id', 'p_id', 'user_id', 'ord', 'flag', 'status', 'clicks','parent_status'], 'integer'],
            [['add_time', 'edit_time'],'default','value'=>date('Y-m-d H:i:s')],
            [['describe', 'add_time', 'edit_time'],'safe']
        ];
    }

}