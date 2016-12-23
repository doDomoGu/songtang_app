<?php

namespace yun\modules\admin\controllers;

use yun\models\Recruitment;
use yun\models\RecruitmentForm;
use Yii;


class RecruitmentController extends BaseController
{
    public function actionIndex(){
        $this->view->title = '招聘信息 - 管理列表';
        $list = Recruitment::find()->orderBy('status desc, ord desc, edit_time desc')->all();
        $params['list'] = $list;
        return $this->render('list',$params);
    }

    public function actionAddAndEdit(){
        $model = new RecruitmentForm();
        $recruitment = null;
        $id = Yii::$app->request->get('id');
        if($id!=''){
            $recruitment = Recruitment::find()->where(['id'=>$id])->one();
            if($recruitment){
                $this->view->title = '招聘信息 - 编辑';
                $model->setAttributes($recruitment->attributes);
                $recruitment->setScenario('update');
            }else{
                Yii::$app->response->redirect('recruitment')->send();
            }
        }else{
            $this->view->title = '招聘信息 - 添加';
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($recruitment == null){
                $recruitment = new Recruitment();
                $recruitment->setScenario('create');
            }

            $recruitment->setAttributes($model->attributes);
            if($recruitment->save()){
                Yii::$app->response->redirect('/admin/recruitment')->send();
            }
        }

        $params['model'] = $model;
        return $this->render('add_and_edit',$params);
    }

}
