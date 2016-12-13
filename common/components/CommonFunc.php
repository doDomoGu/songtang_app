<?php
namespace common\components;

use yii\base\Component;
use yii;

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
    }

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
}
