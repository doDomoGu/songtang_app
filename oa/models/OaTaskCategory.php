<?php

namespace oa\models;

//oa 任务表分类
class OaTaskCategory extends \yii\db\ActiveRecord
{
    const TYPE_HR       = 1;    //人事类   招聘 人员调动
    const TYPE_DAILY    = 2;    //日常类   费用核算 报销 采购
    const TYPE_BUSINESS = 3;    //商务类   合同
    const TYPE_OFFICIAL = 4;    //行政类   用章用证

    const TYPE_HR_NAME       = '人事类';
    const TYPE_DAILY_NAME    = '日常类';
    const TYPE_BUSINESS_NAME = '商务类';
    const TYPE_OFFICIAL_NAME = '行政类';

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
                throw new \yii\base\Exception('OaTaskCategory has installed');
            }else{
                $arr = [
                    1=>['招聘','入职','转正','升职','降职','调动','调薪'],
                    2=>['费用核算','差旅','报销','采购','行政事项'],
                    3=>['合同签署'],
                    4=>['公章','印章','证件']
                ];
                foreach($arr as $k=>$nameArr) {
                    $ord = 1;
                    foreach($nameArr as $n){
                        $m = new OaTaskCategory();
                        $m->name = $n;
                        $m->type = $k;
                        $m->ord = $ord;
                        $m->show_flag = 1;
                        $m->status = 1;
                        $m->save();
                        $ord++;
                    }
                }
                echo 'OaTaskCategory install finish'."<br/>";
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
            self::TYPE_DAILY => self::TYPE_DAILY_NAME,
            self::TYPE_BUSINESS => self::TYPE_BUSINESS_NAME,
            self::TYPE_OFFICIAL => self::TYPE_OFFICIAL_NAME
        ];
    }

    public function getTypeName(){
        switch($this->type){
            case self::TYPE_HR:
                $return = self::TYPE_HR_NAME;
                break;
            case self::TYPE_DAILY:
                $return = self::TYPE_DAILY_NAME;
                break;
            case self::TYPE_BUSINESS:
                $return = self::TYPE_BUSINESS_NAME;
                break;
            case self::TYPE_OFFICIAL:
                $return = self::TYPE_OFFICIAL_NAME;
                break;
            default:
                $return = 'N/A';
        }

        return $return;
    }

    public static function getDropdownList(){
        $list = [];
        $typeList = self::getTypeList();
        foreach($typeList as $k => $tl){
            $list['t'.$k] = $tl;
            $cate = OaTaskCategory::find()->where(['type'=>$k])->all();
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
