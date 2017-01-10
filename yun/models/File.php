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
            [['filename', 'filename_real', 'user_id', 'filesize', 'filetype'], 'required'],
            [['id', 'filesize', 'filetype', 'status', 'dir_id', 'p_id', 'user_id', 'ord', 'flag', 'status', 'clicks','parent_status'], 'integer'],
            [['add_time', 'edit_time'],'default','value'=>date('Y-m-d H:i:s')],
            [['describe', 'add_time', 'edit_time'],'safe']
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getDir()
    {
        return $this->hasOne(Dir::className(), array('id' => 'dir_id'));
    }

    public function getAreaAttrs(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_AREA])->all();
        foreach($attrs as $a){
            $arr[] = $a->area->name;
        }
        return $arr;
    }

    public function getAreaAttrs2(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_AREA])->all();
        foreach($attrs as $a){
            if($a->attr_id==1)
                $arr[] = '全员';
            else
                $arr[] = $a->area->name;
        }
        return $arr;
    }

    public function getBusinessAttrs(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_BUSINESS])->all();
        foreach($attrs as $a){
            $arr[] = $a->business->name;
        }
        return $arr;
    }

    public function getBusinessAttrs2(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_BUSINESS])->all();
        foreach($attrs as $a){
            if($a->attr_id==1)
                $arr[] = '全员';
            else
                $arr[] = $a->business->name;
        }
        return $arr;
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