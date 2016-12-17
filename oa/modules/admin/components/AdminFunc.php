<?php
namespace oa\modules\admin\components;

use yii\base\Component;
use yii;
use yii\helpers\Url;

class AdminFunc extends Component {
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

    public static function adminUrl($route=''){
        if($route == '/'){
            return '/admin';
        }else{
            return '/admin/'.(Url::to($route));
        }

    }
}
