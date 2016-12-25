<?php

namespace yun\modules\admin\controllers;

use Yii;
use yun\models\Dir;
use yun\components\DirFunc;
use yun\modules\admin\models\DirForm;

class DirController extends BaseController
{

    public function actionIndex()
    {
        $this->view->title = '板块目录 - 列表';

        $dir_id = Yii::$app->request->get('dir_id',false);  //目录

        $dirList_1 = DirFunc::getDropDownList(0,true,false,1); //第一层目录

        $dirList_2 = [];

        $list = [];

        $curDir = Dir::find()->where(['id'=>$dir_id,'status'=>1])->one();

        if($curDir){
            $parents = DirFunc::getParents($dir_id);

            $dirLvl_1 = isset($parents[1])?$parents[1]:null;
            $dirLvl_2 = isset($parents[2]) && $dirLvl_1?$parents[2]:null;
            if($dirLvl_1){
                $dirList_2 = DirFunc::getDropDownList($dirLvl_1->id,true,false,1);
            }
        }else{
            $dirLvl_1 = null;
            $dirLvl_2 = null;
        }

        if($curDir){
            if($curDir->level==2){
                $list = DirFunc::getListArr($dir_id,true,true,true);
            }else{
                $list = DirFunc::getListArr($dir_id,true,true,true,0);
            }
        }

        $params['list'] = $list;
        $params['dirList_1'] = $dirList_1;
        $params['dirList_2'] = $dirList_2;
        $params['dirLvl_1'] = $dirLvl_1;
        $params['dirLvl_2'] = $dirLvl_2;


        return $this->render('index',$params);
    }

    public function actionAddAndEdit(){
        $model = new DirForm();
        $dir = null;
        $id = Yii::$app->request->get('id',false);
        $p_id = Yii::$app->request->get('p_id',false);
        $action = null ;
        if($id!=false){
            $dir = Dir::find()->where(['id'=>$id])->one();
            if($dir){
                $this->view->title = '板块目录 - 编辑';
                $model->setAttributes($dir->attributes);
                $action = 'edit';
            }else{
                Yii::$app->response->redirect('dir')->send();
            }
        }elseif($p_id!=false){
            $parDir = Dir::find()->where(['id'=>$p_id,'is_leaf'=>0])->one();
            if($parDir){
                $model->p_id = $p_id;
                $model->type = $parDir->type;
                $model->level = $parDir->level + 1;
                $model->status = 1;
                $this->view->title = '板块目录 - 添加';
                $action = 'add';
            }else{
                Yii::$app->response->redirect('dir')->send();
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($dir == null){
                $dir = new Dir();
                $dir->setAttributes($model->attributes);
                $dir->more_cate = 0;
                //查找出当前父目录下的其他子目录 ord 最小的
                $lastDir = Dir::find()->where(['p_id'=>$p_id])->orderBy('ord asc')->one();
                if($lastDir){
                    //将原本is_last子目录改为0
                    $lastDir->is_last = 0;
                    $lastDir->save();
                    //赋予新建的目录ord = lastDir->ord - 1  is_last = 1
                    $dir->ord = $lastDir->ord - 1;
                }else{
                    $dir->ord = 99;
                }
                $dir->is_last = 1;

            }else{
                $dir->setAttributes($model->attributes);
            }

            if($dir->save()){
                //清除缓存
                $cache = Yii::$app->getCache();
                unset($cache['treeDataId']);
                //$this->clearTreeDataCache();

                //重定向
                $parents = DirFunc::getParents($dir->id);
                $redirect = ['admin/dir'];
                if(isset($parents[2])){
                    $redirect['dir_id'] = $parents[2]->id;
                }elseif(isset($parents[1])){
                    $redirect['dir_id'] = $parents[1]->id;
                }
                Yii::$app->response->redirect($redirect)->send();
            }
        }

        $params['model'] = $model;
        $params['action'] = $action;
        return $this->render('add_and_edit',$params);
    }

}