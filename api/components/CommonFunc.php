<?php
namespace api\components;

use yii\base\Component;
use yii;

class CommonFunc extends Component {
    public static function getHelp($className){
        $return = [];

        $class = '\api\controllers\\'.$className.'Controller';

        $class = new $class($className,false);

        $actionList = array_keys($class->actions());

        $return['title'] = $class->helpTitle;

        $list = [];

        foreach($actionList as $act){
            $list[] = [
                'title'=>$act,
                'desc'=>$class->getHelp($act)
            ];
        }

        $return['list'] = $list;


        return $return ;

    }

    public static function funcNameTrans($str){
        $length = mb_strlen($str);

        $new = '';

        for($i = 0; $i < $length; $i++)
        {
            $num = ord($str[$i]);
            $pre = isset($str[$i-1])?ord($str[$i-1]):0;

            $new .= ($i != 0 && ($num >= 65 && $num <= 90) && (($pre >= 97 && $pre <= 122)||($pre>=48 && $pre<=57))) ? "-{$str[$i]}" : $str[$i];
        }

        return strtolower($new);
    }


    public static function actNameTrans($str){
        $tmp = explode('-',$str);


        return strtolower($new);
    }
}
