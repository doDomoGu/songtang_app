<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;

class HelpController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
