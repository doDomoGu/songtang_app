<?php
namespace oa\modules\admin\components;

use yii\base\Component;
use yii;
use yii\helpers\Url;

class AdminFunc extends Component {
    public static function adminUrl($route=''){
        if($route == '/'){
            return '/admin';
        }else{
            return '/admin/'.(Url::to($route));
        }

    }
}
