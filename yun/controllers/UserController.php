<?php

namespace yun\controllers;

use yun\components\CommonFunc;
use yun\components\DirFunc;
use yun\components\FileFrontFunc;
use yun\models\Dir;
//use yun\models\DownloadRecord;
use yun\models\File;
use yun\models\PositionDirPermission;
//use yun\models\UserSign;
use Yii;
use ucenter\models\User;
/*use yun\models\UserChangePwdForm;
use yun\models\UserChangeHeadImgForm;*/
use yii\data\Pagination;


class UserController extends BaseController
{
    public function actionIndex()
    {
        $this->view->title = '职员资料'.$this->titleSuffix;

        $user = User::find()->where(['id'=>Yii::$app->user->id])->one();


        return $this->render('index',array('user'=>$user));
    }

    public function actionChangePassword(){
        $this->view->title = '修改密码';
        $model = new UserChangePwdForm();
        $user = User::find()->where(['id'=>Yii::$app->user->id])->one();
        $model->id = $user->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->password = md5($model->password_new);
            $user->password_true = $model->password_new;
            if($user->save()){
                Yii::$app->user->logout();
                Yii::$app->response->redirect('/site/login')->send();
            }
        }
        $params['model'] = $model;
        return $this->render('change_password',$params);
    }


    public function actionChangeHeadImg(){
        $this->view->title = '修改头像';
        $model = new UserChangeHeadImgForm();
        $user = User::find()->where(['id'=>Yii::$app->user->id])->one();
        $model->id = $user->id;
        if ($model->load(Yii::$app->request->post())) {
            $user->head_img = $model->head_img;
            if($user->save()){
                Yii::$app->response->redirect('/user')->send();
            }
        }else{
            $model->head_img = $user->head_img;
        }

        $params['model'] = $model;
        return $this->render('change_head_img',$params);
    }

    public function actionPermissionList(){
        $this->view->title = '职员权限列表';
        //$list = [];
        $list =  DirFunc::getListArr(0,true,true,true,false);
        $pmCheck = [];
        $pmDirIds = [];
        foreach($list as $l){
            if($l->is_leaf ==1){
                $pmDirIds[] = $l->id;
            }
        }
        $pmList = PositionDirPermission::find()->where(['position_id'=>$this->user->position_id]);
        //$pmList = $pmList->andWhere(['in','dir_id',$pmDirIds]);
        $pmList = $pmList->all();

        foreach($pmList as $pmOne){
            $pmCheck[$pmOne->dir_id][$pmOne->type] = 1;
        }

        $params['list'] = $list;
        $params['pmCheck'] = $pmCheck;

        return $this->render('permission_list',$params);
    }

    public function actionFile(){
        $this->view->title = '我的上传';
        $list = File::find()->where(['uid'=>$this->user->id])->andWhere(['>','filetype',0]);
        $count = $list->count();
        $pageSize = 10;
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);
        $list = $list
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        $params['list'] = $list;
        $params['pages'] = $pages;
        return $this->render('file',$params);
    }

    public function actionDownload(){
        $this->view->title = '我的下载';
        $list = DownloadRecord::find()->where(['uid'=>$this->user->id]);
        $count = $list->count();
        $pageSize = 10;
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);
        $list = $list
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        $params['list'] = $list;
        $params['pages'] = $pages;
        return $this->render('download',$params);
    }

    public function actionRecycle(){
        $this->view->title = '回收站';
        //$list = File::find()->where(['uid'=>$this->user->id])->andWhere(['>','filetype',0]);
        //$list = File::find()->where(['uid'=>$this->user->id])->andWhere('status <2 and (status = 0 or parent_status = 0)')/*->andWhere(['>','filetype',0])*/;
        $list = File::find()->where(['uid'=>$this->user->id])->andWhere('status = 0  and  parent_status = 1')/*->andWhere(['>','filetype',0])*/;
        $count = $list->count();
        $pageSize = 10;
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);
        $list = $list
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        $params['list'] = $list;
        $params['pages'] = $pages;
        return $this->render('recycle',$params);
    }

    public function actionDoRecycle(){
        $file_id = yii::$app->request->get('id',false);
        $file = File::find()->where(['uid'=>$this->user->id,'status'=>0,'id'=>$file_id])->one();
        if($file){
            $file->status = 1;
            if($file->save()){
                FileFrontFunc::updateParentStatus($file->id);
                Yii::$app->response->redirect('/user/recycle')->send();
            }
        }else{
            echo '文件不存在';
        }

    }
    public function actionDoRecycleDelete(){
        $file_id = yii::$app->request->get('id',false);
        $file = File::find()->where(['uid'=>$this->user->id,'status'=>0,'id'=>$file_id])->one();
        if($file){
            $file->status = 2;
            if($file->save()){
                FileFrontFunc::updateDeleteStatus($file->id);
                Yii::$app->response->redirect('/user/recycle')->send();
            }
        }else{
            echo '文件不存在';
        }

    }

    public function actionDoRecycleDeleteAll(){
        $files = File::find()->where(['uid'=>$this->user->id])->andWhere('status = 0  and  parent_status = 1')->all();
        foreach($files as $f){
            $f->status = 2;
            if($f->save()){
                FileFrontFunc::updateDeleteStatus($f->id);

            }
        }
        Yii::$app->response->redirect('/user/recycle')->send();
    }


    public function actionSign(){
        $this->view->title = '每日签到';
        $y = yii::$app->request->get('y',false);
        $m = yii::$app->request->get('m',false);
        $y = $y?$y:date('Y');
        $m = $m?$m:date('m');
        if(!(in_array($y,['2015','2016','2017','2018']) && in_array($m,['01','02','03','04','05','06','07','08','09','10','11','12']))){
            Yii::$app->response->redirect('/user/sign')->send();
        }


        $dateFirst = $y.$m.'01'; //月份第一天

        $weekdayFirst = date('w',strtotime($dateFirst));

        $dayNum = date('t',strtotime($dateFirst)); //月份总天数

        $dateLast = $y.$m.$dayNum; //月份最后一天

        $weekdayLast = date('w',strtotime($dateLast));

        $today = date('Y-m-d'); //当前日期

        $prevMonth = strtotime('-1 month',strtotime($dateFirst));

        $nextMonth = strtotime('+1 month',strtotime($dateFirst));

        $prevLink = ['/user/sign','y'=>date('Y',$prevMonth),'m'=>date('m',$prevMonth)];
        $nextLink = ['/user/sign','y'=>date('Y',$nextMonth),'m'=>date('m',$nextMonth)];

        $signTodayFlag = false;

        $signToday = UserSign::find()->where(['uid'=>$this->user->id,'y'=>date('Y'),'m'=>date('m'),'d'=>date('d')])->one();
        if($signToday!=NULL){
            $signTodayFlag = true;
        }

        $signs = UserSign::find()->where(['uid'=>$this->user->id,'y'=>$y,'m'=>$m])->all();
        $signList = [];
        foreach($signs as $s){
            $signList[] = $s->d;
        }

        $params['y'] = $y;
        $params['m'] = $m;

        $params['prevLink'] = $prevLink;
        $params['nextLink'] = $nextLink;


        $params['dateFirst'] = $dateFirst;
        $params['weekdayFirst'] = $weekdayFirst;
        $params['dateLast'] = $dateLast;
        $params['weekdayLast'] = $weekdayLast;
        $params['dayNum'] = $dayNum;
        $params['today'] = $today;

        $params['signList'] = $signList;

        $params['signTodayFlag'] = $signTodayFlag;


//        var_dump($y,$m);exit;
        //$calender =


        return $this->render('sign',$params);
    }

    public function actionSignIn(){
        $this->view->title = '每日签到';
        $date = date('Y-m-d');
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $isHoliday = CommonFunc::isHoliday($date);
        if($isHoliday){
            $result = 3;
        }else{
            $sign = UserSign::find()->where(['uid'=>$this->user->id,'y'=>$y,'m'=>$m,'d'=>$d])->one();
            if($sign!=NULL){
                $result = 2;
            }else{
                $newSign = new UserSign();
                $newSign->uid = $this->user->id;
                $newSign->y = $y;
                $newSign->m = $m;
                $newSign->d = $d;
                $newSign->point = 1;
                $newSign->sign_time = date('Y-m-d H:i:s');
                $newSign->save();
                $result = 1;
            }
        }

        $params['result'] = $result;
        return $this->render('sign_in',$params);
    }
}
