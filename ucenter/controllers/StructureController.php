<?php
namespace ucenter\controllers;

use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Structure;
use Yii;
use yii\web\Response;

class StructureController extends BaseController
{
    public function actionIndex(){
        $district_id = Yii::$app->request->get('district_id',false);
        $industry_id = Yii::$app->request->get('industry_id',false);
        $list = Structure::find();
        if($district_id>0)
            $list = $list->where(['district_id'=>$district_id]);
        else
            $list = $list->where(['>','district_id',0]);

        $list = $list->/*andWhere(['did'=>0])->*/groupBy('district_id')->with('district')->all();

        $params['list'] = $list;
        $params['districtArr'] = District::getNameArr();
        $params['industryArr'] = Industry::getNameArr();
        $params['companyArr'] = Company::getNameArr();
        //$params['deparmentArr'] = Department::getNameArr();
        $params['district_id']  = $district_id;
        $params['industry_id']  = $industry_id;
        $params['industryArr2'] = District::getIndustryRelationsArr($district_id);

        return $this->render('index',$params);
    }


    /*
     * get-items2  添加部门时 获取下拉框值
     * 参数
     * 返回 数组 ['id'=>***,'name'=>****]
     */
    public function actionGetItems2(){
        $errormsg = '';
        $result = false;
        $items = [];
        if(Yii::$app->request->isAjax){
            $aid = Yii::$app->request->post('aid',false);  //area id
            $bid = Yii::$app->request->post('bid',false);  //business id
            $p_id = Yii::$app->request->post('p_id',false); //department p_id
            $parentExist = Department::find()->where(['id'=>$p_id,'status'=>1])->one();
            $dids = [];//根据p_id获取下属部门ID
            $didsNot = [];  //需要排除的部门ID ，
            if($parentExist){
                // p_id>0 父层级下的次级部门
                $dList = Department::find()->where(['p_id'=>$p_id,'status'=>1])->all();
                if($dList){
                    foreach($dList as $dl){
                        $dids[] = $dl->id;
                    }
                }
            }else{
                //  p_id=0获取业态关联部门列表
                $dList = Structure::find()->where(['aid'=>0,'bid'=>$bid,'status'=>1])->all();
                if($dList){
                    foreach($dList as $dl){
                        $dids[] = $dl->did;
                    }
                }
            }
            // 已经添加过的部门需要排除
            $dList2 = Structure::find()->where(['aid'=>$aid,'bid'=>$bid])->all();
            if($dList2){
                foreach($dList2 as $dl){
                    $didsNot[] = $dl->did;
                }
            }


            $list = Department::find()->where(['id'=>$dids,'status'=>1])->andWhere(['not in' ,'id',$didsNot])->all();
            if(!empty($list)){
                foreach($list as $l){
                    $items[]=['id'=>$l->id,'name'=>$l->name];
                }
                $result = true;
            }else{
                $errormsg = '没有可选择的项！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'items'=>$items];
    }

    public function actionAdd(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $aid = Yii::$app->request->post('aid',false);
            $bid = Yii::$app->request->post('bid',false);
            //$p_id = Yii::$app->request->post('p_id',false);
            $new_did = Yii::$app->request->post('new_did',false);

            $existA = Area::find()->where(['id'=>$aid,'status'=>1])->one();
            if($existA){//检查地区是否存在
                $existB = Business::find()->where(['id'=>$bid])->one();
                if($existB){//检查部门是否存在
                    $departExist = Department::find()->where(['id'=>$new_did])->one();
                    if($departExist){//检查所选部门是否存在
                        if($departExist->p_id>0){//如果所选部门不是顶级部门 则对其父层级进行检查
                            $departParentExist = Department::find()->where(['id'=>$departExist->p_id])->one();
                            if($departParentExist){ //检查对应组织结构表 是否存在
                                $structureDepartParentExist = Structure::find()->where(['aid'=>$aid,'bid'=>$bid,'did'=>$departExist->p_id])->one();
                                if(!$structureDepartParentExist){
                                    $errormsg = '所选部门父层级不存在!';
                                }
                            }else{
                                $errormsg = '所选部门父层级不存在!';
                            }
                        }else{
                            //如果是顶级部门 检查业态关联部门是否存在
                            $businessRelationDepartExist = Structure::find()->where(['aid'=>0,'bid'=>$bid,'did'=>$new_did])->one();
                            if(!$businessRelationDepartExist){
                                $errormsg = '所选部门和所选业态没有相关联!';
                            }
                        }
                    }else{
                        $errormsg = '所选部门ID不存在!';
                    }
                    if($errormsg == ''){
                        $newExist = Structure::find()->where(['aid'=>$aid,'bid'=>$bid,'did'=>$new_did])->one();
                        if($newExist){
                            $errormsg = '所选部门已存在!';
                        }else{
                            $newStructure = new Structure();
                            $newStructure->aid = $aid;
                            $newStructure->bid = $bid;
                            $newStructure->did = $new_did;
                            $newStructure->status = 1;
                            if($newStructure->save()){
                                $name = $existA->name.' > '.$existB->name.' > '.Department::getFullRoute($new_did);
                                Yii::$app->getSession()->setFlash('success','添加部门【'.$name.'】成功！');
                                $result = true;
                            }else{
                                $errormsg = '保存失败，刷新页面重试!';
                            }
                        }
                    }
                }else{
                    $errormsg = '业态ID不存在!';
                }
            }else{
                $errormsg = '地区ID不存在!';
            }


        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

}
