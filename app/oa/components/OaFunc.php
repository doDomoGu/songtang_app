<?php
namespace oa\components;

use oa\models\Task;
use oa\models\TaskApplyUser;
use yii\base\Component;
use yii;

class OaFunc extends Component {

    public static function getResourcePath($path,$beaut=true){
        if($path!='' && strpos($path,'http')!==0){
            if($beaut)
                return Yii::$app->params['qiniu-oa-domain-beaut'].$path;
            else
                return Yii::$app->params['qiniu-oa-domain'].$path;
        }
        return $path;
    }






}
