<?php
namespace yun\components;

use yii\base\Component;
use Yii;
use yun\models\Dir;

class YunFunc extends Component {
    public static function getResourcePath($path,$beaut=true){
        if($path!='' && strpos($path,'http')!==0){
            if($beaut)
                return Yii::$app->params['qiniu-domain-beaut'].$path;
            else
                return Yii::$app->params['qiniu-domain'].$path;
        }
        return $path;
    }

    /*
     * isHoliday 检查这个日期是不是节假日
     * 参数: date
     */
    public static function isHoliday($date,$weekday=false){
        $holidayArr = self::getHolidayArr();
        $workdayArr = self::getWorkdayArr();
        if(in_array($date,$holidayArr)){
            return true;
        }elseif(in_array($date,$workdayArr)){
            return false;
        }else{
            if($weekday===false || !in_array($weekday,[0,1,2,3,4,5,6])){
                $weekday = date('w',strtotime($date));
            }
            $weekday = intval($weekday);
            //$weekday: 0 - 6  0和6分别为 周日 周六
            if($weekday === 0 || $weekday === 6){
                return true;
            }
        }


        return false;
    }

    private static function getHolidayArr(){
        $arr = [
            '2016-04-30',
            '2016-05-01',
            '2016-05-02',
            '2016-06-09',
            '2016-06-10',
            '2016-06-11',
            '2016-09-15',
            '2016-09-16',
            '2016-09-17',
            '2016-10-01',
            '2016-10-02',
            '2016-10-03',
            '2016-10-04',
            '2016-10-05',
            '2016-10-06',
            '2016-10-07',
            '2017-01-02',
            '2017-01-27',
            '2017-01-30',
            '2017-01-31',
            '2017-02-01',
            '2017-02-02',
            '2017-04-03',
            '2017-04-04',
            '2017-05-01',
            '2017-05-29',
            '2017-05-30',
            '2017-10-02',
            '2017-10-03',
            '2017-10-04',
            '2017-10-05',
            '2017-10-06',
        ];
        return $arr;
    }

    private static function getWorkdayArr(){
        $arr = [
            '2016-06-12',
            '2016-09-18',
            '2016-10-08',
            '2016-10-09',
            '2017-01-22',
            '2017-02-04',
            '2017-04-01',
            '2017-05-27',
            '2017-09-30',
        ];
        return $arr;
    }


    public static function getNavbar(){
        $arr = [];
        //var_dump(Yii::$app->controller->id);exit;
        //$isDirCtl = strpos(Yii::$app->controller->route,'dir')===0?true:false;
        $isDirCtl = Yii::$app->controller->id == 'dir'?true:false;
        $dirLvl_1 = null;
        if($isDirCtl){
            $dir_id = yii::$app->controller->dir_id;

            if($dir_id){
                $parents = Dir::getParents($dir_id);
                $dirLvl_1 = isset($parents[1])?$parents[1]:null;
            }
        }

        $dirs = Dir::find()->where(['p_id'=>0,'status'=>1])->orderBy('ord asc,id desc')->all();
        if(!empty($dirs)){
            foreach($dirs as $dir){
                $active = $dirLvl_1!=null && $dirLvl_1->id==$dir->id?true:false;

                $arr[] = [
                    'label'=>$dir->name.'<span class="active-red"></span>',
                    'url'=>['/dir','dir_id'=>$dir->id],
                    'active' => $active,
                    'encode'=>false
                ];
            }
        }


        return $arr;
    }
}