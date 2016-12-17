<?php

namespace oa\modules\admin\controllers;

use oa\models\OaTask;
use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Position;
use Yii;
/**
 *  任务流程管理
 *  task flow
 */
class TaskController extends BaseController
{
    public function actionIndex()
    {
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $list = OaTask::find()->all();


        $params['list'] = $list;
        $params['aArr'] = Area::getNameArr();
        $params['bArr'] = Business::getNameArr();
        $params['pArr'] = Position::getNameArr();
        $params['bArr2'] = Area::getRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;
        return $this->render('index',$params);
    }
}
