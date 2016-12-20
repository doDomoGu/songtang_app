<?php

namespace oa\modules\admin\controllers;

use oa\models\OaTaskCategory;
use Yii;
/**
 * Default controller for the `admin` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError(){
        echo 'oa - admin - error';
        Yii::$app->end();
    }

    public function actionNoAuth(){
        echo 'oa - admin - no - auth';
        Yii::$app->end();
    }


    public function actionInstall(){
        $n = new OaTaskCategory();
        $n->install();
    }
}
