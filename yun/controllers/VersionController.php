<?php

namespace yun\controllers;
use yii;

class VersionController extends BaseController
{
    public $layout = 'main';

    public function beforeAction($action){
        if(parent::beforeAction($action)){
            $this->view->title = '版本功能'.$this->titleSuffix;
            return true;
        }else
            return false;
    }
    public function actionIndex(){
        return $this->render('index');

        /*$index = yii::$app->request->get('index',1);
        if(in_array($index,[1,2,3,4,5,6,7])){
            $viewName = 'index';
            if($index>1){
                $viewName .= '_'.$index;
            }
            return $this->render($viewName);
        }else{
            $this->redirect('/');
            return false;
        }*/

    }

}
