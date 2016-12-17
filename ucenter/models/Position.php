<?php

namespace ucenter\models;

class Position extends \yii\db\ActiveRecord
{
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

    public function getChildren(){
        $arr = [];
        $list = self::find()->where(['p_id'=>$this->id,'status'=>1])->all();
        if(!empty($list)){
            $count = count($list);
            $i = 1;
            foreach($list as $l){
                $prefix = '&emsp;';
                if ($count == $i) {
                    $prefix .= '└─ ';
                } else {
                    $prefix .= '├─ ';
                }
                $l->name = $prefix . $l->name;
                $arr[] = $l;
                $i++;
            }
        }
        return $arr;
    }

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('Position has installed');
            }else{
                $arr = [
                    'admin' => '系统管理员',
                    'zjl' => '总经理',
                    'zj' => '总监',
                    'zg' => '主管',
                    'ptzy' => '普通职员'
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
}
