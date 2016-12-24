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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionClear(){
        $cache = yii::$app->cache;
        $cache['dirDataId'] = [];
        $cache['dirChildrenDataId'] = [];
        $cache['treeDataid'] = [];
    }
    public function actionIndex(){
        $dir_1 = Dir::find()->where(['p_id'=>0,'ord'=>1])->one();
        $dir_2 = Dir::find()->where(['p_id'=>0,'ord'=>2])->one();
        $dir_3 = Dir::find()->where(['p_id'=>0,'ord'=>3])->one();
        $dir_4 = Dir::find()->where(['p_id'=>0,'ord'=>4])->one();
        $dir_5 = Dir::find()->where(['p_id'=>0,'ord'=>5])->one();

        $params['list_dirOne'] = [
            1=>$dir_1,
            2=>$dir_2,
            3=>$dir_3,
            4=>$dir_4,
            5=>$dir_5
        ];

        $limit = 5;
        $params['list_1'] = DirFunc::getChildren($dir_1->id,true,1,1,$limit);
        $params['list_2'] = DirFunc::getChildren($dir_2->id,true,1,1,$limit);
        $params['list_3'] = DirFunc::getChildren($dir_3->id,true,1,1,$limit);
        $params['list_4'] = DirFunc::getChildren($dir_4->id,true,1,1,$limit);
        $params['list_5'] = DirFunc::getChildren($dir_5->id,true,1,1,$limit);
        $this->view->title = yii::$app->name;
        return $this->render('index',$params);
    }

    public function actionGetQiniuUptoken(){
        $up=new QiniuUpload(yii::$app->params['qiniu-bucket']);
        $saveKey = yii::$app->request->get('saveKey','');
        $upToken=$up->createtoken($saveKey);
        echo json_encode(['uptoken'=>$upToken]);exit;
    }

    public function actionInstall(){
        $m = new Dir();
        $m->install();

        exit;
    }
}
