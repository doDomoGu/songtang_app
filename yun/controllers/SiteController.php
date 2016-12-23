<?php
namespace yun\controllers;

use yun\components\DirFunc;
use yun\components\QiniuUpload;
use yun\models\News;
use yun\models\Dir;
use yun\models\Recruitment;
use Yii;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public function actionIndex(){
        /*$dir_1 = Dir::find()->where(['id'=>1])->one();
        $dir_2 = Dir::find()->where(['id'=>2])->one();
        $dir_3 = Dir::find()->where(['id'=>3])->one();
        $dir_4 = Dir::find()->where(['id'=>4])->one();
        $dir_5 = Dir::find()->where(['id'=>5])->one();

        $params['list_dirOne'] = [
            1=>$dir_1,
            2=>$dir_2,
            3=>$dir_3,
            4=>$dir_4,
            5=>$dir_5
        ];

        $limit = 5;
        $params['list_1'] = DirFunc::getChildren(1,true,1,1,$limit);
        $params['list_2'] = DirFunc::getChildren(2,true,1,1,$limit);
        $params['list_3'] = DirFunc::getChildren(3,true,1,1,$limit);
        $params['list_4'] = DirFunc::getChildren(4,true,1,1,$limit);
        $params['list_5'] = DirFunc::getChildren(5,true,1,1,$limit);*/
        $this->view->title = yii::$app->id;
        $params = [];
        return $this->render('index',$params);
    }

    public function actionError()
    {
        yii::$app->response->statusCode = 404;
        return $this->render('error');
    }

    public function actionGetQiniuUptoken(){
        $up=new QiniuUpload(yii::$app->params['qiniu-bucket']);
        $saveKey = yii::$app->request->get('saveKey','');
        $upToken=$up->createtoken($saveKey);
        echo json_encode(['uptoken'=>$upToken]);exit;
    }
}
