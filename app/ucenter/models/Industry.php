<?php

namespace ucenter\models;

use Yii;
use yun\models\Attribute;
use yun\models\Dir;
use yun\models\DirAttribute;
use yun\models\DirPermission;

//Industry      行业 (产业)

class Industry extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => '名称',
            'alias' => '别名',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'ord', 'status'], 'required'],
            [['id', 'ord', 'status'], 'integer'],
            [['name','alias'],'safe'],
        ];
    }

    public static function getArr(){
        return [
            'default' => '--',
            'stjg'=>'颂唐机构',
            'dc' => '地产',
            'jj' => '经纪',
            'fw' => '房屋',
            'zy' => '置业',
            'gg' => '广告',
            'gg2' => '公关',
            'hd' => '互动',
            'sy' => '商业',
            'jz' => '建筑',
            'wy' => '物业',
        ];
    }

    public static function getCheckIdsTrue($checkArr){
        $return = [];
        $list = self::find()->where(['id'=>$checkArr,'status'=>1])->all();
        foreach($list as $l){
            $return[] = $l->id;
        }
        return $return;
    }

    public function getCompanyRelations(){
        return Structure::find()->where([
            'district_id'=>0,
            'industry_id'=>$this->id,
            'department_id'=>0,
            'status'=>1
        ])->with('company')->all();
    }

    public static function getItems($frontend=false,$idArr=false){
        $items = [];
        $list = self::find()->where(['status'=>1]);
        if($idArr!==false && is_array($idArr)){
            $list = $list->andWhere(['id'=>$idArr]);
        }
        $list = $list->orderBy('ord asc')->all();
        foreach($list as $l){
            $items[$l->id] = $l->name;
        }
        if($frontend){
            $default = self::find()->where(['alias'=>'default'])->one();
            if(isset($items[$default->id]))
                $items[$default->id] = '全员';
        }
        return $items;
    }

    public static function getItemsByPermission($dir_id,$frontend=false,$user=false){
        $idArr = [];
        if($user===false)
            $user = Yii::$app->user->identity;
        $up1 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_UPLOAD,$user);
        if($up1){
            $idArr = false;
        }else{
            $up3 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY,DirPermission::OPERATION_UPLOAD,$user);
            $up4 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY,DirPermission::OPERATION_UPLOAD,$user);
            if($up3 || $up4){
                $idArr[] = $user->industry_id;
//                $default = self::find()->where(['alias'=>'default'])->one();
//                $idArr[] = $default->id;
            }
        }

        $dir = Dir::find()->where(['id'=>$dir_id])->one();
        if($dir){
            $idAttr = false;
            $attributes = json_decode($dir->attr_limit,true);
            if($attributes){
                if(isset($attributes[Attribute::TYPE_INDUSTRY]) && is_array($attributes[Attribute::TYPE_INDUSTRY])){
                    $idAttr = [];
                    foreach($attributes[Attribute::TYPE_INDUSTRY] as $attr){
                        $idAttr[] = $attr;
                    }
                }
            }
            if(is_array($idAttr)){
                $idArr = $idArr!==false ? array_diff($idArr,$idAttr) : $idAttr;
            }
        }

        return self::getItems($frontend,$idArr);
    }

    public static function getIds(){
        $ids = [];
        $list = self::find()->where(['status'=>1])->orderBy('ord asc')->all();
        foreach($list as $l){
            $ids[] = $l->id;
        }
        return $ids;
    }

    public static function getNameArr(){
        $list = self::find()->select(['id','name'])->all();
        $arr = [];
        foreach($list as $l){
            $arr[$l->id] = $l->name;
        }
        return $arr;
    }

    public static function getName($id){
        $one = self::find()->where(['id'=>$id])->one();
        if($one){
            $name = $one->name;
        }else{
            $name = null;
        }
        return $name;
    }

    public function install() {
        try {
            $exist = self::find()->one();
            if ($exist) {
                throw new \yii\base\Exception('Industry has installed');
            }else{
                $arr = self::getArr();
                $ord = 1;
                foreach ($arr as $k => $v) {
                    $m = new self();
                    $m->name = $v;
                    $m->alias = $k;
                    $m->ord = $ord;
                    $m->status = 1;
                    $m->save();
                    $ord++;
                }

                echo 'Industry install finish' . "<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            $message = $e->getMessage() . "\n";
            echo $message;
            echo '<br/>';

            return false;
        }
    }


    public static function ordUp($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $to = self::find()->where(['<','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord desc')->one();
            if($to){
                $cur->ord = $to->ord;
                $cur->save();
                $to->ord = $curOrd;
                $to->save();
                $result  = true;
            }
        }
        return $result;
    }

    public static function ordDown($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $to = self::find()->where(['>','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord asc')->one();
            if($to){
                $cur->ord = $to->ord;
                $cur->save();
                $to->ord = $curOrd;
                $to->save();
                $result  = true;
            }
        }
        return $result;
    }

    public static function ordTop($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $toList = self::find()->where(['<','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord desc')->all();
            if(!empty($toList)){
                foreach($toList as $to){
                    $toOrd = $to->ord;
                    $to->ord = $curOrd;
                    $to->save();
                    $curOrd = $toOrd;
                }
                $cur->ord = $curOrd;
                $cur->save();
                $result = true;
            }
        }
        return $result;
    }

    public static function ordBottom($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $toList = self::find()->where(['>','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord asc')->all();
            if(!empty($toList)){
                foreach($toList as $to){
                    $toOrd = $to->ord;
                    $to->ord = $curOrd;
                    $to->save();
                    $curOrd = $toOrd;
                }
                $cur->ord = $curOrd;
                $cur->save();
                $result = true;
            }
        }
        return $result;
    }
}
