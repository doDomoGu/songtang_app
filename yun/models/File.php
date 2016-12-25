<?php

namespace yun\models;

use ucenter\models\User;
use yun\components\FileFrontFunc;
use Yii;

class File extends \yii\db\ActiveRecord
{
    //public $childrenIds;
    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['filename', 'filename_real', 'uid', 'filesize', 'filetype'], 'required'],
            [['id', 'filesize', 'filetype', 'status', 'dir_id', 'p_id', 'uid', 'ord', 'flag', 'status', 'clicks'], 'integer'],
            [['add_time', 'edit_time'],'default','value'=>date('Y-m-d H:i:s')],
            [['describe', 'add_time', 'edit_time'],'safe']
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'uid'));
    }


    public static function handleDeleteStatus(){
        $files = File::find()->all();
        foreach($files as $f){
            $delete_status = FileFrontFunc::getParentDeleteStatus($f->p_id);
            if($delete_status==false){
                $f->status = 2;
                $f->save();
            }
        }
    }

    public static function handleParentStatus(){
        $files = File::find()->all();
        foreach($files as $f){
            $parent_status = FileFrontFunc::getParentStatus($f->p_id);
            if($parent_status==false){
                $f->parent_status = 0;
                $f->save();
            }
        }
    }
}