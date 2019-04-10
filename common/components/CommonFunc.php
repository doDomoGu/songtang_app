<?php
namespace common\components;

use common\models\GlobalConfig;
use ucenter\models\UserHistory;
use yii\base\Component;
use yii;
use yii\helpers\ArrayHelper;

class CommonFunc extends Component {
    /*
     *  根据chars 和 length 生成随机字符串
     *  返回类型 string
     */
    public static function generateCode( $length = 8 ) {
        // 字符集，可任意添加你需要的字符
        $chars = 'klmnvXYojAB15w23LRSCD0GTUtuZMcdINOPxFHzabJpqrs4KyefghiVW67EQ89';
        $code = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $code .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $code .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $code;
    }
/*


    public static function fixZero($num){
        if($num<10){
            $return = '0000'.$num;
        }elseif($num<100){
            $return = '000'.$num;
        }elseif($num<1000){
            $return = '00'.$num;
        }elseif($num<10000){
            $return = '0'.$num;
        }else{
            $return = $num;
        }
        return $return;
    }*/

    public static function mySubstr($str,$len){
        $strlen = mb_strlen( $str, 'utf-8' );
        if($strlen>$len-2){
            $str = mb_substr( $str, 0, $len-1, 'utf-8' ).'...';
        }

        return $str;
    }

    public static function getGenderCn($gender){
        if($gender==1){
            return '男';
        }elseif($gender==2){
            return '女';
        }else{
            return 'N/A';
        }
    }

    public static function getStatusCn($status){
        if($status==1){
            return '正常';
        }elseif($status==0){
            return '<span style="color:red;">禁用</span>';
        }else{
            return 'N/A';
        }
    }


    public static function isMobile(){
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }


    public static function getByCache($classname,$func,$params,$key){
        $cache = yii::$app->cache;
        if(!empty($params)){ //数组，多个数据
            $dataArr = $cache->get($key);
            $id = implode('|',$params);

            if(!empty($dataArr) && isset($dataArr[$id])){
                $data = $dataArr[$id];
            }else {
                $paramTemp = [];
                foreach ($params as $k => $par) {
                    //将true / false 字符串化 ，用于拼接key
                    if ($par === true) {
                        $par = 'true';
                    } elseif ($par === false) {
                        $par = 'false';
                    }
                    $paramTemp[] = $par;
                }
                $id = implode('|', $paramTemp);
                $paramStr = implode(',', $paramTemp);

                eval("\$data = $classname::$func($paramStr);");

                if (empty($dataArr)) {
                    $arr = [$id => $data];
                } else {
                    $arr = ArrayHelper::merge($dataArr, [$id => $data]);
                }

                $cache->set($key, $arr);
            }
        }else{ //单一数据
            $data = $cache->get($key);
            if(empty($data)) {
                eval("\$data = $classname::$func();");
            }
            $cache->set($key,$data);
        }
        return $data;
    }


    public static function getJoinDay($join_date){
        $arr = ['day'=>null,'yearMonth'=>null];
        $joinTimestamp = strtotime($join_date);
        if($joinTimestamp > 0){
            $now = strtotime(date('y-m-d'));
            if($now-$joinTimestamp > -1){
                $day = intval(abs($now-$joinTimestamp)/86400);
                $arr['day'] = $day;
                $joinday = date('y-m-d',$joinTimestamp);
                $today = date('y-m-d');
                $ymdArr =self::diffDate($joinday,$today);
                $arr['yearMonth'] = $ymdArr[0].'年'.$ymdArr[1].'个月'.$ymdArr[2].'天';
            }
        }
        return $arr;
    }

    public static function getContractDay($contract_date){
        $arr = ['day'=>null,'yearMonth'=>null];
        $contractTimestamp = strtotime($contract_date);
        if($contractTimestamp > 0){
            $now = strtotime(date('y-m-d'));
            if($contractTimestamp - $now > -1){
                $day = intval(abs($contractTimestamp - $now)/86400);
                $arr['day'] = $day;
                $contractday = date('y-m-d',$contractTimestamp);
                $today = date('y-m-d');
                $ymdArr =self::diffDate($today,$contractday);
                $arr['yearMonth'] = $ymdArr[0].'年'.$ymdArr[1].'个月'.$ymdArr[2].'天';
            }
        }
        return $arr;
    }

    private static function diffDate($date1, $date2) {
        if (strtotime($date1) > strtotime($date2)) {
            $ymd = $date2;
            $date2 = $date1;
            $date1 = $ymd;
        }
        list($y1, $m1, $d1) = explode('-', $date1);
        list($y2, $m2, $d2) = explode('-', $date2);
        $y = $m = $d = $_m = 0;
        $math = ($y2 - $y1) * 12 + $m2 - $m1;
        $y = intval($math / 12);
        $m = intval($math % 12);
        $d = (mktime(0, 0, 0, $m2, $d2, $y2) - mktime(0, 0, 0, $m2, $d1, $y2)) / 86400;
        if ($d < 0) {
            $m -= 1;
            $d += date('j', mktime(0, 0, 0, $m2, 0, $y2));
        }
        $m < 0 && $y -= 1;
        return array($y, $m, $d);
    }


    public static function getGender($type){
        if($type==1){
            return '男';
        }elseif($type==2){
            return '女';
        }else{
            return 'N/A';
        }
    }

    public static function addHistory(){
        $new = new UserHistory();
        $new->user_id = Yii::$app->user->isGuest?0:Yii::$app->user->id;
        $new->url = Yii::$app->request->getAbsoluteUrl();
        $new->controller = Yii::$app->controller->id;
        $new->action = Yii::$app->controller->action->id;
        $new->request = Yii::$app->request->queryString;
        $new->request_method = Yii::$app->request->method;
        $new->response = Yii::$app->response->statusCode;
        $new->ip = Yii::$app->request->getUserIP();
        $new->user_agent = Yii::$app->request->getUserAgent();
        $new->referer = Yii::$app->request->getReferrer();
        $new->add_time = date('Y-m-d H:i:s');
        $new->save();
    }

    public static function checkIpWhiteList(){
        $result = GlobalConfig::getConfig('ip_white_list');
        if($result){
            // 白名单以|号分割
            $list = explode('|',$result);
            if(in_array(Yii::$app->request->getUserIP(),$list)){
                return true;
            }else{
                echo '网站暂停访问!';
                return false;
            }
        }else{
            // 没有设置白名单表示全不允许
            return true;
        }
    }
}
