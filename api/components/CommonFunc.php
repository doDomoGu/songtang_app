<?php
namespace api\components;

use yii\base\Component;
use yii;

class CommonFunc extends Component {
    public static function getHelp($className){
        $className = '\api\controllers\\'.$className.'Controller';

        $funcList = get_class_methods($className::className());
        $class = new $className('user',false);

        $list = [];
        foreach($funcList as $l){
            if(substr($l,0,6)=='action' && strlen($l)>6 && $l!='actions'){
                $funcName = substr($l,6);
                $actionUrl = self::funcNameTrans($funcName);
                $helpMethod = 'help'.$funcName;

                if(!method_exists($class,$helpMethod)){
                    echo $className::className().' :: '.$helpMethod.' :not exist';exit;
                }

                $list[] = ['act-url'=>$actionUrl,'help-method'=>$helpMethod,'method'=>$l];

                //echo $l.'<br/>';
                //$class->$l(); echo '<br/>';

            }
        }

        //var_dump($actionList);exit;

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
}
