<?php

namespace yun\models;
use yun\components\CommonFunc;
use yun\components\DirFunc;
//use yun\components\PositionFunc;
use Yii;
use yii\helpers\ArrayHelper;

class Dir extends \yii\db\ActiveRecord
{
    public $childrenIds;
    public $childrenList;

    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'p_id'], 'required'],
            [['id', 'type', 'ord', 'level', 'is_leaf', 'is_last', 'p_id', 'status'], 'integer'],
            [['describe'], 'safe']
        ];
    }

    public function install(){
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('Dir has installed');
            }else{
                $arr0 = [
                    ['n'=>'企业运营中心','a'=>'zyfzzx','c'=>[]],
                    ['n'=>'发展资源中心','a'=>'fzzyzx','c'=>[]],
                    ['n'=>'工具应用中心','a'=>'gjyyzx','c'=>[]],
                    ['n'=>'项目资源中心','a'=>'xmzyzx','c'=>[]],
                    ['n'=>'学习共享中心','a'=>'xxgxzx','c'=>[]]
                ];
                $ord = 1;
                $count = count($arr0);
                foreach($arr0 as $v) {
                    $m = new Dir();
                    $m->name = $v['n'];
                    $m->alias = $v['a'];
                    $m->ord = $ord;
                    $m->type = $ord;
                    $m->is_last = $ord==$count?1:0;
                    $m->is_leaf = 0;
                    $m->p_id = 0;
                    $m->level = 1;
                    $m->status = 1;
                    $m->save();
                    $ord++;
                }
                echo 'Dir install finish'."<br/>";
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
}