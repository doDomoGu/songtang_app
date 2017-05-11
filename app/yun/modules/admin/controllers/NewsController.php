<?php

namespace yun\modules\admin\controllers;

use yun\models\News;
use yun\modules\admin\models\NewsForm;
use Yii;


class NewsController extends BaseController
{
    public function actionIndex(){
        $list = News::find()->orderBy('status desc, ord desc, edit_time desc')->all();
        $params['list'] = $list;
        return $this->render('list',$params);
    }

    public function actionAddAndEdit(){
        $model = new NewsForm();
        $news = null;
        $id = Yii::$app->request->get('id');
        if($id!=''){
            $news = News::find()->where(['id'=>$id])->one();
            if($news){
                $this->view->title = '首页新闻 - 编辑';
                $model->setAttributes($news->attributes);
                $news->setScenario('update');
            }else{
                Yii::$app->response->redirect('news')->send();
            }
        }else{
            $this->view->title = '首页新闻 - 添加';
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($news == null){
                $news = new News();
                $news->setScenario('create');
            }

            $news->setAttributes($model->attributes);
            if($news->save()){
                Yii::$app->response->redirect('/admin/news')->send();
            }
        }

        $params['model'] = $model;
        return $this->render('add_and_edit',$params);
    }

}
