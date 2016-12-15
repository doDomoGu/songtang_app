<?php
namespace ucenter\controllers;

use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Department;
use ucenter\models\Structure;
use ucenter\models\User;
use Yii;
use yii\web\Response;

class UserController extends BaseController
{
    public function actionIndex(){
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $list = User::find()->where([])->groupBy('id')->all();


        $params['list'] = $list;
        $params['aArr'] = Area::getNameArr();
        $params['bArr'] = Business::getNameArr();
        $params['bArr2'] = Area::getRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;

        return $this->render('index',$params);
    }
}
