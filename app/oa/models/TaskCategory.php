<?php

namespace oa\models;
use Yii;
//oa 任务表分类
class TaskCategory extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }
    
    const TYPE_HR           = 1;    //人事类   招聘 人员调动
    const TYPE_ACCOUNT      = 2;    //财务类
    const TYPE_STAMP        = 3;    //用印类   合同
    const TYPE_OFFICIAL     = 4;    //行政类   用章用证

    const TYPE_HR_NAME          = '人事类';
    const TYPE_ACCOUNT_NAME     = '财务类';
    const TYPE_STAMP_NAME       = '用印类';
    const TYPE_OFFICIAL_NAME    = '行政类';

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'type' => '类型',
            'ord' => '排序',
            'show_flag' => '显示标识',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['ord', 'status', 'type', 'show_flag'], 'integer'],
        ];
    }

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('TaskCategory has installed');
            }else{
                $arr = [
                    1=>['招聘','入职','转正','调职','调薪','调动','离职','解聘/请假','调休','出差','加班'],
                    2=>['备用金申请','报销申请','奖金申请','奖励申请','事项审批'],
                    3=>['公章','印章','证件'],
                    4=>['固定资产采购','办公用品采购','服装采购','名片名牌印刷','事项审批']
                ];
                foreach($arr as $k=>$nameArr) {
                    $ord = 1;
                    foreach($nameArr as $n){
                        $m = new TaskCategory();
                        $m->name = $n;
                        $m->type = $k;
                        $m->ord = $ord;
                        $m->show_flag = 1;
                        $m->status = 1;
                        $m->save();
                        $ord++;
                    }
                }
                echo 'TaskCategory install finish'."<br/>";
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

    public static function getTypeList(){
        return [
            self::TYPE_HR => self::TYPE_HR_NAME,
            self::TYPE_ACCOUNT => self::TYPE_ACCOUNT_NAME,
            self::TYPE_STAMP => self::TYPE_STAMP_NAME,
            self::TYPE_OFFICIAL => self::TYPE_OFFICIAL_NAME
        ];
    }

    public function getTypeName(){
        switch($this->type){
            case self::TYPE_HR:
                $return = self::TYPE_HR_NAME;
                break;
            case self::TYPE_ACCOUNT:
                $return = self::TYPE_ACCOUNT_NAME;
                break;
            case self::TYPE_STAMP:
                $return = self::TYPE_STAMP_NAME;
                break;
            case self::TYPE_OFFICIAL:
                $return = self::TYPE_OFFICIAL_NAME;
                break;
            default:
                $return = 'N/A';
        }

        return $return;
    }

    public static function getItems(){
        $items = [];
        $list = self::find()->where(['status'=>1])->orderBy('ord asc')->all();
        foreach($list as $l){
            $items[$l->id] = $l->name;
        }
        return $items;
    }

    public static function getDropdownList(){
        $list = [];
        $typeList = self::getTypeList();
        foreach($typeList as $k => $tl){
            $list['t'.$k] = $tl;
            $cate = TaskCategory::find()->where(['type'=>$k,'status'=>1])->orderBy(['ord'=>SORT_ASC])->all();
            $count = count($cate);
            $i=1;
            foreach($cate as $c){
                if($i==$count)
                    $list[$c->id] = '&emsp;└─ '.$c->name;
                else
                    $list[$c->id] = '&emsp;├─ '.$c->name;
                $i++;
            }
        }
       /* '└─ ';
        '├─ ';*/
        return $list;
    }

}
