<?php
namespace yun\modules\admin\controllers;

use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Position;
use ucenter\models\User;
use Yii;
use yun\models\UserGroup;

class PermissionController extends BaseController
{
    public function actionUser(){
        $list = User::find()->where([])->groupBy('id')->all();


        $params['list'] = $list;
        $params['districtArr'] = District::getNameArr();
        $params['industryArr'] = Industry::getNameArr();
        $params['companyArr'] = Company::getNameArr();
        $params['departmentArr'] = Department::getNameArr();
        $params['positionArr'] = Position::getNameArr();

        return $this->render('user',$params);
    }


    public function actionUserGroup(){
        $list = UserGroup::find()->all();
        $params['list'] = $list;
        return $this->render('user_group',$params);
    }


    public function actionUserGroupAdd(){
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
