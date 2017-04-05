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
use yun\models\Attribute;
use yun\models\Dir;
use yun\models\DirPermission;
use yun\models\File;
use yun\models\FileAttribute;
use yun\models\UserGroup;

class PermissionController extends BaseController
{

    public $enableCsrfValidation = false;


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
        $errorMsg = [];
        $successMsg = [];
        $user = false;
        if(Yii::$app->request->getIsPost()){
            $type = Yii::$app->request->post('type',0);
            $dir_id = Yii::$app->request->post('dir_id',0);
            $filedir_id = Yii::$app->request->post('filedir_id',0);
            $file_id = Yii::$app->request->post('file_id',0);
            $user_id = Yii::$app->request->post('user_id',0);
            $user = User::find()->where(['id'=>$user_id])->one();
            if($user){
                $successMsg['user'] = $user->name;
                if($type == 1){

                    $dir = Dir::find()->where(['id'=>$dir_id])->one();
                    if($dir){
                        $successMsg['dir']  = Dir::getFullRoute($dir->id);


                    }else{
                        $errorMsg['dir'] = '目录不存在';
                    }
                }elseif($type==2){

                    $fileDir = File::find()->where(['id' => $filedir_id,'filetype'=>0])->one();
                    if ($fileDir) {
                        $dir = Dir::find()->where(['id'=>$fileDir->dir_id])->one();

                        if($dir) {


                            $successMsg['filedir'] = Dir::getFullRoute($dir->id);
                        }else{
                            $errorMsg['filedir'] = '文件夹所在目录不存在';
                        }
                    } else {
                        $errorMsg['filedir'] = '文件夹不存在';
                    }
                }elseif($type==3){
                    $file = File::find()->where(['id' => $file_id])->andWhere(['>','filetype',0])->one();
                    if($file){
                        $dir = Dir::find()->where(['id'=>$file->dir_id])->one();
                        if($dir){
                            $districtAttr = FileAttribute::find()->where(['file_id'=>$file->id,'attr_type'=>Attribute::TYPE_DISTRICT])->one();
                            $districtItems = District::getItems(true);
                            $districtName = $districtItems[$districtAttr->attr_id];

                            $industryAttr = FileAttribute::find()->where(['file_id'=>$file->id,'attr_type'=>Attribute::TYPE_INDUSTRY])->one();
                            $industryItems = Industry::getItems(true);
                            $industryName = $industryItems[$industryAttr->attr_id];

                            $params['districtName'] = $districtName;
                            $params['industryName'] = $industryName;
                            $dir_id = $dir->id;
                            $successMsg['file'] = Dir::getFullRoute($dir->id).' > '.$file->filename;
                        }else{
                            $errorMsg['file'] = '所在目录不存在';
                        }
                    }
                }else{
                    $errorMsg['type'] = '请选择类型';
                }
            }else{
                $errorMsg['user'] = '职员ID错误不存在';
            }

        }else{
            $type = $dir_id = $filedir_id = $file_id = $user_id = '';
        }



        $params['errorMsg']   = $errorMsg;
        $params['successMsg'] = $successMsg;
        $params['type']       = $type;
        $params['dir_id']     = $dir_id;
        $params['filedir_id'] = $filedir_id;
        $params['file_id']    = $file_id;
        $params['user_id']    = $user_id;
        $params['user']       = $user;

        return $this->render('check',$params);
    }

}
