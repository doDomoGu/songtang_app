<?php

namespace ucenter\models;

//业态
class Business extends \yii\db\ActiveRecord
{
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

    public function getRelations(){
        $list = Structure::find()->where(['aid'=>0,'bid'=>$this->id,'status'=>1])->with('department')->all();
        return $list;
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
                throw new \yii\base\Exception('Business has installed');
            }else{
                $arr = [
                    'default' => '【缺省】',
                    'stdc' => '颂唐地产',
                    'stdc_2' => '颂唐地产(二)',
                    'stgg' => '颂唐广告',
                    'rxsy' => '日鑫商业',
                    'hyfw' => '汉佑房屋',
                    'hhjj' => '鸿汉经纪',
                ];
                $ord = 1;
                foreach ($arr as $k => $v) {
                    $m = new Business();
                    $m->name = $v;
                    $m->alias = $k;
                    $m->ord = $ord;
                    $m->status = 1;
                    $m->save();
                    $ord++;
                }

                echo 'Business install finish' . "<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            //echo  'Dept_Business  install failed<br />';
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            echo '<br/>';
            /*echo '<br/><br/>';
            var_dump($e);
            echo '<br/><br/>';
            var_dump($errorInfo);*/

            //throw new \Exception($message, $errorInfo, (int) $e->getCode(), $e);
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
