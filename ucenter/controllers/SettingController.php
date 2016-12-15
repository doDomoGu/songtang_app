<?php

namespace ucenter\controllers;

use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\Structure;
use yii\web\Response;
use Yii;

class SettingController extends BaseController
{
    public function actionArea(){
        $list = Area::find()->orderBy('status desc,ord asc')->all();

        $count = Area::find()->where(['status'=>1])->count();
        $params['list'] = $list;
        $params['count'] = $count;

        return $this->render('area',$params);
    }

    public function actionAreaCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $name = trim(Yii::$app->request->post('name',false));
            $alias = trim(Yii::$app->request->post('alias',false));
            if($name=='' || $alias==''){
                $errormsg = '名称或别名不能为空！';
            }else{
                $exist = Area::find()->where(['name'=>$name])->orWhere(['alias'=>$alias])->one();
                if($exist){
                    $errormsg = '名称或别名已存在!';
                }else{
                    $last = Area::find()->where(['status'=>1])->orderBy('ord desc')->one();
                    $area = new Area();
                    $area->name = $name;
                    $area->alias = $alias;
                    $area->ord = $last?$last->ord+1:1;
                    $area->status = 1;
                    if($area->save()){
                        Yii::$app->getSession()->setFlash('success','新建地区【'.$area->name.'】成功！');
                        $result = true;
                    }else{
                        $errormsg = '保存失败，刷新页面重试!';
                    }
                }
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }




    /*
     * get-info  获取信息
     * 参数 id:根据type读取不同的model
     * 参数 type: 'area'=>DepartmentArea  'business'=>DepartmentBusiness
     * 返回 数组 ['id'=>***,'name'=>****,'alias'=>****]
     */
    public function actionGetInfo(){
        $errormsg = '';
        $result = false;
        $info = [];
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',false);
            $type = trim(Yii::$app->request->post('type',false));
            switch($type){
                case 'area':
                    $area = Area::find()->where(['id'=>$id])->one();
                    if($area){
                        $info = ['id'=>$area->id,'name'=>$area->name,'alias'=>$area->alias];
                        $result = true;
                    }else{
                        $errormsg = '地区id不存在';
                    }
                    break;
                case 'business':
                    $business = Business::find()->where(['id'=>$id])->one();
                    if($business){
                        $info = ['id'=>$business->id,'name'=>$business->name,'alias'=>$business->alias];
                        $result = true;
                    }else{
                        $errormsg = '业态id不存在';
                    }
                    break;
                case 'department':
                    $class = Department::find()->where(['id'=>$id])->one();
                    if($class){
                        $info = ['id'=>$class->id,'name'=>$class->name,'alias'=>$class->alias,'fullRoute'=>Department::getFullRoute($id)];
                        $result = true;
                    }else{
                        $errormsg = '部门id不存在';
                    }
                    break;
                default:
                    $errormsg = '参数错误!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'info'=>$info];
    }

    /*
     * get-relation-items  获取相关下属选项 用户界面上的多选框 checkbox
     * 参数 type: 'area'=>DepartmentBusiness 'business'=>DepartmentClass
     * 返回 数组 ['id'=>***,'name'=>****]
     */
    public function actionGetRelationItems(){
        $errormsg = '';
        $result = false;
        $items = [];
        if(Yii::$app->request->isAjax){
            $type = trim(Yii::$app->request->post('type',false));
            switch($type){
                case 'area':
                    $business = Business::find()->all();
                    if(!empty($business)){
                        foreach($business as $b){
                            $items[]=['id'=>$b->id,'name'=>$b->name];
                        }
                    }
                    $result = true;
                    break;
                case 'business':
                    $class = Department::find()->where(['p_id'=>0])->all();
                    if(!empty($class)){
                        foreach($class as $c){
                            $items[]=['id'=>$c->id,'name'=>$c->name];
                        }
                    }
                    $result = true;
                    break;
                default:
                    $errormsg = '获取相关设置列表时，参数错误!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'items'=>$items];
    }

    /*
     * get-relation-check  获取相关下属选中的选项 用户界面上的多选框 checkbox 选中状态
     * 参数 id: 根据type表示不同的表id
     * 参数 type: 'area'=>DepartmentBusiness 'business'=>DepartmentClass
     * 返回 数组 [id1,id2,id3]
     */
    public function actionGetRelationCheck(){
        $errormsg = '';
        $result = false;
        $check = [];
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',false);
            $type = trim(Yii::$app->request->post('type',false));
            switch($type){
                case 'area':
                    $exist = Area::find()->where(['id'=>$id])->one();
                    if($exist){
                        $relation = Structure::find()->where(['aid'=>$id,'did'=>0])->all();
                        if(!empty($relation)){
                            foreach($relation as $b){
                                $check[]=$b->bid;
                            }
                        }
                        $result = true;
                    }else{
                        $errormsg = '地区不存在!';
                    }
                    break;
                case 'business':
                    $exist = Business::find()->where(['id'=>$id])->one();
                    if($exist){
                        $relation = Structure::find()->where(['aid'=>0,'bid'=>$id])->all();
                        if(!empty($relation)){
                            foreach($relation as $r){
                                $check[]=$r->did;
                            }
                        }
                        $result = true;
                    }else{
                        $errormsg = '业态不存在!';
                    }
                    break;
                default:
                    $errormsg = '查询已存在的设置时，参数错误';
            }

        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'check'=>$check];
    }


    /*
     * get-relation-check 获取相关下属选项 用户界面上的多选框 checkbox
     * 参数 id :根据type表示不同表ID
     * 参数 数组 checks: 选中的选项
     * 参数 type: 'area'=>Business 'business'=>Department
     * 返回 结果
     */
    public function actionEditRelationCheck(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',false);
            $checkList = Yii::$app->request->post('checks',[]);
            $type = trim(Yii::$app->request->post('type',false));
            switch($type){
                case 'area':
                    $exist = Area::find()->where(['id'=>$id])->one();
                    if($exist){
                        // exist2 检查删除的业态($checkCurList 和 $checkList比对)下是否有创建过部门 有的话不能删除
                        $checkCurrent = Structure::find()->where(['aid'=>$id,'did'=>0])->all();
                        $checkCurList = [];
                        foreach($checkCurrent as $c){
                            $checkCurList[] = $c->bid;
                        }
                        $checkDel = array_diff($checkCurList,$checkList);
                        $exist2 = Structure::find()->where(['aid'=>$id,'bid'=>$checkDel])->andWhere(['>','did',0])->all();
                        if(!empty($exist2)){
                            $errormsg = '选择要删除的业态下有创建过部门不能删除';
                        }else{
                            Structure::deleteAll(['aid'=>$id,'did'=>0]);
                            $ord = 1;
                            foreach($checkList as $v){
                                $st = new Structure();
                                $st->aid = $id;
                                $st->bid = $v;
                                $st->did = 0;
                                $st->ord = $ord++;
                                $st->status = 1;
                                $st->save();
                               ;
                            }
                            Yii::$app->getSession()->setFlash('success','设置【'.$exist->name.'】相关业态成功！');
                            $result = true;
                        }
                    }else{
                        $errormsg = '提交参数错误';
                    }
                    break;
                case 'business':
                    $exist = Business::find()->where(['id'=>$id])->one();
                    if($exist){
                        // exist2 检查删除的业态($checkCurList 和 $checkList比对)下是否有创建过部门 有的话不能删除
                        /*$checkCurrent = Structure::find()->where(['aid'=>$id,'did'=>0])->all();
                        $checkCurList = [];
                        foreach($checkCurrent as $c){
                            $checkCurList[] = $c->bid;
                        }
                        $checkDel = array_diff($checkCurList,$checkList);
                        $exist2 = Structure::find()->where(['bid'=>$checkDel])->andWhere(['>','did',0])->all();
                        if(!empty($exist2)){
                            $errormsg = '选择要删除的业态下有创建过部门不能删除';
                        }else{*/

                        Structure::deleteAll(['aid'=>0,'bid'=>$id]);
                        $ord = 1;
                        foreach($checkList as $v){
                            $st = new Structure();
                            $st->aid = 0;
                            $st->bid = $id;
                            $st->did = $v;
                            $st->ord = $ord++;
                            $st->status = 1;
                            $st->save();
                        }
                        Yii::$app->getSession()->setFlash('success','设置【'.$exist->name.'】相关部门成功！');
                        $result = true;
                    }else{
                        $errormsg = '提交参数错误!';
                    }
            }


        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }


    /*
     * change-ord 更改排序
     * 参数 id :根据type表示不同表ID
     * 参数 type: 'area'=>DepartmentArea 'business'=>DepartmentBusiness
     * 参数 act : up down top bottom
     * 返回 结果
     */
    public function actionChangeOrd(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',false);
            $type = trim(Yii::$app->request->post('type',false));
            $act = trim(Yii::$app->request->post('act',false));
            switch($type){
                case 'area':
                    $exist = Area::find()->where(['id'=>$id,'status'=>1])->one();
                    if($exist){
                        switch($act){
                            case 'up':
                                if(Area::ordUp($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移上成功！');
                                    $result = true;
                                }
                                break;
                            case 'down':
                                if(Area::ordDown($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移下成功！');
                                    $result = true;
                                }
                                break;
                            case 'top':
                                if(Area::ordTop($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移至顶部成功！');
                                    $result = true;
                                }
                                break;
                            case 'bottom':
                                if(Area::ordBottom($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移至底部成功！');
                                    $result = true;
                                }
                                break;
                        }
                        if($errormsg=='' && !$result){
                            $errormsg = '移动失败';
                        }
                    }else{
                        $errormsg = '数据不存在';
                    }
                    break;
                case 'business':
                    $exist = Business::find()->where(['id'=>$id,'status'=>1])->one();
                    if($exist){
                        switch($act){
                            case 'up':
                                if(Business::ordUp($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移上成功！');
                                    $result = true;
                                }
                                break;
                            case 'down':
                                if(Business::ordDown($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移下成功！');
                                    $result = true;
                                }
                                break;
                            case 'top':
                                if(Business::ordTop($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移至顶部成功！');
                                    $result = true;
                                }
                                break;
                            case 'bottom':
                                if(Business::ordBottom($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移至底部成功！');
                                    $result = true;
                                }
                                break;
                        }
                        if($errormsg=='' && !$result){
                            $errormsg = '移动失败';
                        }
                    }else{
                        $errormsg = '数据不存在';
                    }
                    break;
                case 'department':
                    $exist = Department::find()->where(['id'=>$id,'status'=>1])->one();
                    if($exist){
                        switch($act){
                            case 'up':
                                if(Department::ordUp($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移上成功！');
                                    $result = true;
                                }
                                break;
                            case 'down':
                                if(Department::ordDown($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移下成功！');
                                    $result = true;
                                }
                                break;
                            case 'top':
                                if(Department::ordTop($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移至顶部成功！');
                                    $result = true;
                                }
                                break;
                            case 'bottom':
                                if(Department::ordBottom($id)){
                                    Yii::$app->getSession()->setFlash('success','【'.$exist->name.'】的排序移至底部成功！');
                                    $result = true;
                                }
                                break;
                        }
                        if($errormsg=='' && !$result){
                            $errormsg = '移动失败';
                        }
                    }else{
                        $errormsg = '数据不存在';
                    }
                    break;
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }



    public function actionBusiness(){

        $list = Business::find()->orderBy('status desc,ord asc')->all();

        $count = Business::find()->where(['status'=>1])->count();
        $params['list'] = $list;
        $params['count'] = $count;
        return $this->render('business',$params);
    }

    public function actionBusinessCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $name = trim(Yii::$app->request->post('name',false));
            $alias = trim(Yii::$app->request->post('alias',false));
            $exist = Business::find()->where(['name'=>$name])->orWhere(['alias'=>$alias])->one();
            if($exist){
                $errormsg = '名称或别名已存在!';
            }else{
                $last = Business::find()->orderBy('ord desc')->one();
                $new = new Business();
                $new->name = $name;
                $new->alias = $alias;
                $new->ord = $last?$last->ord+1:1;
                $new->status = 1;
                $new->save();
                Yii::$app->getSession()->setFlash('success','新建业态【'.$name.'】成功！');
                $result = true;
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionDepartment(){
        $p_id = Yii::$app->request->get('p_id',0);
        if($p_id>0){
            $parent = Department::find()->where(['id'=>$p_id])->one();
            if(!$parent){
                Yii::$app->getSession()->setFlash('error','所访问的层级ID不存在！');
                $this->redirect('/setting/department');
                Yii::$app->end();
            }else{
                $pp_id = $parent->p_id;
                $params['pp_id'] = $pp_id;
            }
        }

        $list = Department::find()->where(['p_id'=>$p_id])->orderBy('status desc,ord asc')->all();
        $count = Department::find()->where(['p_id'=>$p_id,'status'=>1])->count();
        //$list = DepartmentClass::getList(0,0,false);

        $params['list'] = $list;
        $params['count'] = $count;
        $params['p_id'] = $p_id;

        return $this->render('department',$params);
    }


    public function actionEdit(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',false);
            $name = trim(Yii::$app->request->post('name',false));
            $type = trim(Yii::$app->request->post('type',false));
            if($name != ''){
                switch($type){
                    case 'area':
                        $exist = Area::find()->where(['id'=>$id])->one();
                        if($exist){
                            $repeat = Area::find()->where(['<>','id',$id])->andWhere(['name'=>$name])->one();
                            if($repeat){
                                $errormsg = '名称已存在！';
                            }else{
                                $oldName = $exist->name;
                                $exist->name = $name;
                                $exist->save();
                                Yii::$app->getSession()->setFlash('success','地区【'.$oldName.'】修改名称为【'.$name.'】成功！');
                                $result = true;
                            }
                        }else{
                            $errormsg = '地区不存在';
                        }
                        break;
                    case 'business':
                        $exist = Business::find()->where(['id'=>$id])->one();
                        if($exist){
                            $repeat = Business::find()->where(['<>','id',$id])->andWhere(['name'=>$name])->one();
                            if($repeat){
                                $errormsg = '名称已存在！';
                            }else{
                                $oldName = $exist->name;
                                $exist->name = $name;
                                $exist->save();
                                Yii::$app->getSession()->setFlash('success','业态【'.$oldName.'】修改名称为【'.$name.'】成功！');
                                $result = true;
                            }
                        }else{
                            $errormsg = '业态不存在';
                        }
                        break;
                    case 'class':
                        $exist = Department::find()->where(['id'=>$id])->one();
                        if($exist){
                            $repeat = Department::find()->where(['<>','id',$id])->andWhere(['name'=>$name,'p_id'=>$exist->p_id])->one();
                            if($repeat){
                                $errormsg = '名称已存在！';
                            }else{
                                $oldName = $exist->name;
                                $exist->name = $name;
                                $exist->save();
                                Yii::$app->getSession()->setFlash('success','部门【'.$oldName.'】修改名称为【'.$name.'】成功！');
                                $result = true;
                            }
                        }else{
                            $errormsg = '部门信息不存在';
                        }
                        break;
                }
            }else{
                $errormsg = '请填写名称！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionPosition(){
        $p_id = Yii::$app->request->get('p_id',0);
        $list = [];
        if($p_id>0){
            $parent =  Position::find()->where(['id'=>$p_id])->one();
            if($parent){
                $params['parent'] = $parent;
                $list = Position::find()->where(['p_id'=>$p_id])->all();
            }else{
                $this->redirect('/setting/position');
                Yii::$app->end();
            }
        }else{
            $list = Position::find()->where(['p_id'=>0])->all();
        }


        $params['list'] = $list;
        $params['p_id'] = $p_id;
        return $this->render('position',$params);
    }

    public function actionPositionCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $name = trim(Yii::$app->request->post('name',false));
            $alias = trim(Yii::$app->request->post('alias',false));
            $p_id = trim(Yii::$app->request->post('p_id',0));
            if($name=='' || $alias==''){
                $errormsg = '名称或别名不能为空！';
            }else{
                if($p_id>0){
                    $parentExist = Position::find()->where(['id'=>$p_id])->one();
                    if($parentExist){
                        $exist = Position::find()->where(['name'=>$name])->orWhere(['alias'=>$alias])->andWhere(['p_id'=>$p_id])->one();
                        if($exist){
                            $errormsg = '名称或别名已存在!';
                        }else{
                            $last = Position::find()->where(['p_id'=>$p_id,'status'=>1])->orderBy('ord desc')->one();
                            $newPos = new Position();
                            $newPos->name = $name;
                            $newPos->alias = $alias;
                            $newPos->p_id = $p_id;
                            $newPos->ord = $last?$last->ord+1:1;
                            $newPos->status = 1;
                            if($newPos->save()){
                                Yii::$app->getSession()->setFlash('success','新建扩展职位【'.$newPos->name.'】成功！');
                                $result = true;
                            }else{
                                $errormsg = '保存失败，刷新页面重试!';
                            }
                        }
                    }else{
                        $errormsg = '对应基础职位不存在!';
                    }
                }else{
                    $errormsg = '对应基础职位不存在11111111!!';
                }
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }
}
