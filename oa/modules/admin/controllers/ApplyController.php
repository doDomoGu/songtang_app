<?php

namespace oa\modules\admin\controllers;

use oa\models\OaApply;
use oa\models\OaApplyRecord;
use oa\modules\admin\components\AdminFunc;
use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Position;
use Yii;


class ApplyController extends BaseController
{
    public function actionIndex()
    {
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $list = OaApply::find()->all();


        $params['list'] = $list;
        $params['aArr'] = Area::getNameArr();
        $params['bArr'] = Business::getNameArr();
        $params['pArr'] = Position::getNameArr();
        $params['bArr2'] = Area::getRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;
        return $this->render('index',$params);
    }

    public function actionShowRecord(){
        $aid = Yii::$app->request->get('aid',false);
        $apply = OaApply::find()->where(['id'=>$aid])->one();
        if($apply){
            $records = OaApplyRecord::find()->where(['apply_id'=>$apply->id])->orderBy('add_time desc')->all();
            $params['list'] = $records;
            $params['apply'] = $apply;
            return $this->render('show_record',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','申请操作记录对应的申请id不存在!');
            return $this->redirect(AdminFunc::adminUrl('apply'));
        }
    }

}
