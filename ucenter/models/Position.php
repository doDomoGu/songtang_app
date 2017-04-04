<?php

namespace ucenter\models;

use Yii;
class Position extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => '名称',
            'alias' => '别名',
            'p_id' => '父ID',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'ord', 'status'], 'required'],
            [['id', 'p_id', 'ord', 'status'], 'integer'],
            [['name', 'alias'],'safe'],
        ];
    }

    public static function getChildren($p_id)
    {
        $list = self::find()->where(['p_id' => $p_id])->orderBy('ord asc')->all();
        return $list;
    }

//    public function getChildren(){
//        $arr = [];
//        $list = self::find()->where(['p_id'=>$this->id,'status'=>1])->all();
//        if(!empty($list)){
//            $count = count($list);
//            $i = 1;
//            foreach($list as $l){
//                $prefix = '&emsp;';
//                if ($count == $i) {
//                    $prefix .= '└─ ';
//                } else {
//                    $prefix .= '├─ ';
//                }
//                $l->name = $prefix . $l->name;
//                $arr[] = $l;
//                $i++;
//            }
//        }
//        return $arr;
//    }

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('Position has installed');
            }else{
                $arr = [
                    'default' => '--',
                    'dsz' => '董事长',
                    'zjl' => '总经理',
                    'fzjl' => '副总经理',
                    'zj' => '总监',
                    'jl' => '经理',
                    'zg' => '主管',
                    'zy' => '专员'
                ];
                $ord = 1;
                foreach($arr as $k=>$v) {
                    $m = new Position();
                    $m->name = $v;
                    $m->alias = $k;
                    $m->ord = $ord;
                    $m->status = 1;
                    $m->p_id = 0;
                    $m->save();
                    $ord++;
                }
                echo 'Position install finish'."<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            //echo  'Dept_Area  install failed<br />';
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


    public static function getNameArr(){
        $list = self::find()->select(['id','name'])->all();
        $arr = [];
        foreach($list as $l){
            $arr[$l->id] = $l->name;
        }
        return $arr;
    }
    public static function getItems(){
        $items = [];
        $list = self::find()->where(['status'=>1])->orderBy('ord asc')->all();
        foreach($list as $l){
            $items[$l->id] = $l->name;
        }
        return $items;
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
}
