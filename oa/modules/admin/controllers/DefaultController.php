<?php

namespace oa\modules\admin\controllers;

use oa\models\OaTaskCategory;
use Yii;
/**
 * Default controller for the `admin` module
 */
class DefaultController extends BaseController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNoAuth(){
        return $this->render('no_auth');
    }


    public function actionInstall(){
        $n = new OaTaskCategory();
        $n->install();
    }
}
