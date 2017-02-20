<?php

namespace yun\modules\admin\controllers;

use yun\models\Group;
use Yii;


class UserGroupPermissionController extends BaseController
{
    public function actionIndex(){
        $list = Group::find()->all();
        $params['list'] = $list;
        return $this->render('list',$params);
    }


    public function actionAddGroup(){
        $groupName = Yii::$app->request->post('group_name',false);
        if(!empty($groupName)){
            $exist = Group::find()->where(['name'=>$groupName])->one();
            if(!$exist){
                $g = new Group();
                $g->name = $groupName;
                $g->status = 1;
                $g->save();
                echo json_encode(['success'=>true]);
            }else{

            }
        }else{

        }




    }

}
