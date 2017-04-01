<?php
namespace yun\components;

use yii\base\Component;
use yii;

class CommonFunc extends Component {


    public static function getGender2($n){
        if($n=='男'){
            return 1;
        }elseif($n=='女'){
            return 2;
        }else{
            return 0;
        }
    }



    public static function imgUrl($img_url){
        if($img_url!='' && strpos($img_url,'http')===false)
            $img_url = yii::$app->params['qiniu-domain-beaut'].$img_url;
        return $img_url;
    }

    public static function array2string($arr){
        $str = null;
        if(is_array($arr) && !empty($arr)){
            $str = implode(',',$arr);
        }
        return $str;
    }

    public static function arrayDivide($arr,$num=200){
        $arr_obj = [];
        $count = count($arr);
        $i=1;
        $times = ceil($count/$num);
        while($i<=$times){
            $start = ($i-1)*$num;
            $arrTmp = array_slice($arr,$start,$num);
            $arr_obj[] = $arrTmp;
            $i++;
        }
        return $arr_obj;
    }


    /*
     * isHoliday 检查这个日期是不是节假日
     * 参数: date
     */
    public static function isHoliday($date,$weekday=false){
        $holidayArr = self::getHolidayArr();
        $workdayArr = self::getWorkdayArr();
        //var_dump($date.'='.$weekday);
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

    public static function getHolidayArr(){
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
            '2016-10-07'
        ];
        return $arr;
    }

    public static function getWorkdayArr(){
        $arr = [
            '2016-06-12',
            '2016-09-18',
            '2016-10-08',
            '2016-10-09',
        ];
        return $arr;
    }

    public static function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }

    public static function mySubstr($str,$len){
        $strlen = mb_strlen( $str, 'utf-8' );
        if($strlen>$len-2){
            $str = mb_substr( $str, 0, $len-1, 'utf-8' ).'...';
        }

        return $str;
    }
}