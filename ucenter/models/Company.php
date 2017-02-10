<?php
namespace ucenter\models;

use Yii;
//Company       公司

class Company extends \yii\db\ActiveRecord
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
            'stdc' => '颂唐地产',
            'stwydc' => '颂唐唯亿地产',
            'stgg' => '颂唐广告',
            'rxsy' => '日鑫商业',
            'hyfw' => '汉佑房屋',
            'hhjj' => '鸿汉经纪',
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

    public function getRelations(){
        $list = Structure::find()->where(['aid'=>0,'bid'=>$this->id,'status'=>1])->with('department')->all();
        return $list;
    }

    public static function getItems($foreground=false){
        $items = [];
        $list = self::find()->where(['status'=>1])->orderBy('ord asc')->all();
        foreach($list as $l){
            $items[$l->id] = $l->name;
        }
        if($foreground){
            $default = self::find()->where(['alias'=>'default'])->one();
            $items[$default->id] = '全员';
        }
        return $items;
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

    public function install() {
        try {
            $exist = self::find()->one();
            if ($exist) {
                throw new \yii\base\Exception('Company has installed');
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

                echo 'Company install finish' . "<br/>";
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
