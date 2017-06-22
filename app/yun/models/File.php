<?php

namespace yun\models;

use ucenter\models\User;
use yii\helpers\ArrayHelper;
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

    public function getDistrictAttr(){
        $attr = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_DISTRICT])->one();
        return $attr->attr_id;
    }

    public function getIndustryAttr(){
        $attr = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_INDUSTRY])->one();
        return $attr->attr_id;
    }


    public function getDistrictAttrs(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_DISTRICT])->all();
        foreach($attrs as $a){
            $arr[$a->attr_id] = $a->district->name;
        }
        return $arr;
    }

    public function getAreaAttrs2(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_DISTRICT])->all();
        foreach($attrs as $a){
            if($a->attr_id==1)
                $arr[] = '全员';
            else
                $arr[] = $a->district->name;
        }
        return $arr;
    }

    public function getIndustryAttrs(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_INDUSTRY])->all();
        foreach($attrs as $a){
            $arr[$a->attr_id] = $a->industry->name;
        }
        return $arr;
    }

    public function getBusinessAttrs2(){
        $arr = [];
        $attrs = FileAttribute::find()->where(['file_id'=>$this->id,'attr_type'=>Attribute::TYPE_INDUSTRY])->all();
        foreach($attrs as $a){
            if($a->attr_id==1)
                $arr[] = '全员';
            else
                $arr[] = $a->industry->name;
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


    public static function getFileFullRouteByCache($id){
        $cache = yii::$app->cache;
        $key = 'file-full-route';
        if(isset($cache[$key]) && isset($cache[$key][$id])){
            $data = $cache[$key][$id];
        }else {
            $data = self::getFileFullRoute($id);
            if(!isset($cache[$key])){
                $arr = [$id => $data];
            }else{
                $arr = ArrayHelper::merge($cache[$key],[$id => $data]);
            }
            $cache[$key] = $arr;
        }
        return $data;
    }

    /*
     * 函数getFileFullRoute ,实现根据dir_id(Dir表 id字段  父目录) p_id(File表 id 父文件夹)获取完整的板块目录路径
     *
     * @param dir_id 目录id
     * @param p_id 父文件夹id
     * @param separator 分隔符 (默认 '>' )
     * return string/null
     */
    public static function getFileFullRoute($dir_id,$p_id = 0,$separator = ' > '){
        $route = null;
//        $route = self::getFullRoute($dir_id,$separator);


        if($dir_id > 0){
            $route .= Dir::getFullRoute($dir_id,$separator);
        }


        if($p_id > 0){
            $pDir = self::find()->where(['id'=>$p_id])->one();
            if($pDir){
                if($pDir->p_id>0){
                    $route .= self::getFileFullRoute(0,$pDir->p_id,$separator);
                }
                $route .= $separator.$pDir->filename;
            }
        }
        return $route;
    }
}