<?php
namespace yun\modules\admin\controllers;

use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Position;
use ucenter\models\User;
use Yii;
use yun\components\DirFrontFunc;
use yun\components\DirFunc;
use yun\models\Dir;
use yun\models\DirPermission;
use yun\models\File;
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

    public function actionUserPermission(){
        $user_id = Yii::$app->request->get('user_id',0);

        $user = User::find()->where(['id'=>$user_id])->one();

        $dirList = Dir::getListArr(0,true,true);

        $params['dirList'] = $dirList;

        $params['user'] = $user;

        return $this->render('user_permission',$params);
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

    public function actionCheck(){
        $type = Yii::$app->request->get('type',0);
        $dir_id = Yii::$app->request->get('dir_id',0);
        $p_id = Yii::$app->request->get('p_id',0);
        $user_id = Yii::$app->request->get('user_id',0);

        $errorMsg = '';
        if(in_array($type,[1,2])){
            if($type == 1){

                $dir = Dir::find()->where(['id'=>$dir_id])->one();
                if($dir){
                    //$params['dir_id'] = $dir_id;
                }else{
                    $errorMsg = '目录不存在';
                }
            }elseif($type==2){

                $fileDir = File::find()->where(['id' => $p_id])->one();
                if ($fileDir) {
                    $dir = Dir::find()->where(['id'=>$fileDir->dir_id])->one();

                    if($dir) {
                        //$params['dir_id'] = $dir->id;
                        //$params['dir'] = $dir;
                    }else{
                        $errorMsg = '文件夹所在目录不存在';
                    }
                } else {
                    $errorMsg = '文件夹不存在';
                }
            }


            $user = User::find()->where(['id'=>$user_id])->one();
            if($user){

            }else{
                $errorMsg = '职员ID错误不存在';
            }

        }else{

        }

        $params['errorMsg']     = $errorMsg;
        $params['type']       = $type;
        $params['dir_id']     = $dir_id;
        $params['p_id']       = $p_id;
        $params['user_id']    = $user_id;

        return $this->render('check',$params);
    }

}
