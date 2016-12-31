<?php
namespace yun\controllers;

use Yii;
use yun\components\DirFunc;
use yun\models\DirPermission;

class TestController extends BaseController
{
    public function actionDir(){
        $list = DirFunc::getListArr(0,true,true,true);
        $pmList = DirPermission::getPmList();
        $params['list'] = $list;
        $params['pmList'] = $pmList;
        return $this->render('dir',$params);
    }
}
