<?php
namespace ucenter\controllers;

use moonland\phpexcel\Excel;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\Structure;
use ucenter\models\User;
use ucenter\models\UserForm;
use ucenter\models\UserHistory;
use Yii;
use yii\data\Pagination;
use yii\web\Response;
use common\components\CommonFunc;

class UserController extends BaseController
{
    public function actionIndex(){
        /*$aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);*/
        $search = Yii::$app->request->get('search',[]);

        $defaultSearch = [
            'username' => '',
            'name' => ''
        ];
        $search = array_merge($defaultSearch,$search);



        $query = User::find();
        foreach($search as $k=>$v){
            if(($k=='username'||$k=='name') && $v!=''){
                $query = $query->andWhere(['like',$k,$v]);
            }
        }


        $count = $query->count();

        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => 20,'pageSizeParam'=>false]);

        $list = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $params['pages'] = $pages;
        $params['list'] = $list;
        $params['search'] = $search;
        /*$params['districtArr'] = District::getNameArr();
        $params['industryArr'] = Industry::getNameArr();
        $params['companyArr'] = Company::getNameArr();
        $params['departmentArr'] = Department::getNameArr();
        $params['positionArr'] = Position::getNameArr();*/
        //$params['bArr2'] = Area::getRelationsArr($aid);

        //$params['aid'] = $aid;
        //$params['bid'] = $bid;

        return $this->render('index',$params);
    }

    public function actionAddAndEdit(){
        $model = new UserForm();
        $user = null;
        $updatePassword = true;
        $passwordTmp = null;
        $id = Yii::$app->request->get('id',0);
        if($id>0){
            $user = User::find()->where(['id'=>$id])->one();
            if($user){
                $this->view->title = '职员 - 编辑';
                $model->setScenario('update');
                $model->setAttributes($user->attributes);
                $model->password = '';
                //$user->setScenario('update');
            }else{
                Yii::$app->response->redirect('user')->send();
            }
        }else{
            $this->view->title = '职员 - 添加';
            $model->setScenario('create');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($user == null){
                $user = new User();
                //$user->setScenario('create');
            }
            if($model->getScenario()=='update'){
                if($model->password=='' || $model->password2==''){
                    $model->password = $user->password;
                    $updatePassword = false;
                }
            }elseif($model->getScenario()=='create'){
                $model->password = '123123';
                //$model->password = CommonFunc::generateCode(); //新增职员 自动创建随机密码
            }

            $user->setAttributes($model->attributes);

            if($updatePassword===true){
                $passwordTmp = $user->password;
                $user->password_true = $user->password;
                $user->password = md5($user->password);

            }

            if($user->save()){
                /*if($this->sendMail){
                    //发送邮件
                    if($model->getScenario()=='create'){
                        $mail = new MyMail();
                        $mail->to = $user->username;
                        $mail->subject = '【颂唐云】新职员注册成功';
                        $mail->htmlBody = '职员['.$user->name.'],您好：<br/>颂唐云网址为：http://yun.songtang.net 您的登录用户名为 '.$user->username.' 密码为 '.$passwordTmp;
                        $mail->send();
                    }elseif($model->getScenario()=='update'){
                        $mail = new MyMail();
                        $mail->to = $user->username;
                        if($updatePassword==true){
                            $mail->subject = '【颂唐云】职员信息变更(包括密码)';
                        }else{
                            $mail->subject = '【颂唐云】职员信息变更';
                        }
                        $mail->htmlBody = '职员['.$user->name.'],您好：<br/> 您的职员信息发生了更改。';
                        if($updatePassword==true){
                            $mail->htmlBody.=' <br/>您的登录密码变为 '.$passwordTmp;
                        }
                        $mail->send();
                    }
                }*/
                Yii::$app->response->redirect('/user')->send();
            }
        }

        $params['model'] = $model;
        return $this->render('add_and_edit',$params);
    }


    public function actionClearCache(){
        $cache = yii::$app->cache;
        unset($cache['user-get-items']);
        unset($cache['userPositionFullRoute']);
        unset($cache['department-full-route']);
        unset($cache['dir-data']);
        unset($cache['dir-children-data']);
        echo 222;exit;
        Yii::$app->response->redirect('/user')->send();
    }

    public function actionStructUpdate(){
        if(Yii::$app->request->get('del')==1){
            Structure::deleteAll();//exit;
        }

        $districtDefault = District::find()->where(['alias'=>'default'])->one();

        //地区和行业关系
        $list = User::find()->where(['>','district_id',$districtDefault->id])->groupBy(['district_id','industry_id'])->select(['district_id','industry_id'])->orderBy('district_id,industry_id')->all();
        foreach($list as $l){
            $this->structAdd($l->district_id,$l->industry_id,0,0);
        }

        //行业和公司关系
        $list = User::find()->where(['>','district_id',$districtDefault->id])->groupBy(['industry_id','company_id'])->select(['industry_id','company_id'])->orderBy('industry_id,company_id')->all();
        foreach($list as $l){
            $this->structAdd(0,$l->industry_id,$l->company_id,0);
        }


        //业态和部门关系
        //获取一级部门 id列表
        $departs = Department::find()->all();
        $dArr = [];
        foreach($departs as $d){
            if($d->p_id>0){
                $dArr[$d->id] = $d->p_id;
            }else{
                $dArr[$d->id] = $d->id;
            }
        }

        $list = User::find()->where(['>','district_id',$districtDefault->id])->groupBy(['company_id','department_id'])->select(['company_id','department_id'])->orderBy('company_id,department_id')->all();
        foreach($list as $l){
            $this->structAdd(0,0,$l->company_id,isset($dArr[$l->department_id])?$dArr[$l->department_id]:0);
        }


        //整套设置

        $list = User::find()->where(['>','district_id',$districtDefault->id])->groupBy(['district_id','industry_id','company_id','department_id'])
            ->select(['district_id','industry_id','company_id','department_id'])->orderBy('district_id,industry_id,company_id,department_id')->all();
        foreach($list as $l){
            $this->structAdd($l->district_id,$l->industry_id,$l->company_id,isset($dArr[$l->department_id])?$dArr[$l->department_id]:0);
        }

    }

    public function structAdd($district_id,$industry_id,$company_id,$department_id){
        $n = Structure::find()->where([
            'district_id'=>$district_id,
            'industry_id'=>$industry_id,
            'company_id'=>$company_id,
            'department_id'=>$department_id
        ])->one();
        if(!$n){
            $n = new Structure();
            $n->district_id = $district_id;
            $n->industry_id = $industry_id;
            $n->company_id = $company_id;
            $n->department_id = $department_id;
            $n->ord = 1;
            $n->status = 1;
            $n->save();
        }
    }


    public function actionImportDateAll(){
        /*$date = '20161128';
        var_dump($this->handleDate($date));exit;*/

        header("Content-Type: text/html; charset=UTF-8");

        $arr = ['sh','sz','szgg','wx','nj','hf','hhht'];
        foreach($arr as $a){
            $handle = fopen('../users/import_date/'.$a.'.csv','r');
            $this->importDate($handle);
        }
    }

    public function importDate($handle){
        $data = [];
        $usernameList = [];
        $wrongNum = 0;

        $result = $this->input_csv($handle); //解析csv
        $len_result = count($result);
        var_dump($len_result);echo '<br/>';
        if($len_result>0){
            //$aidArr = array_flip(Area::getArr());
            //$bidArr = array_flip(Business::getArr());



            for ($i = 2; $i < $len_result; $i++) { //循环获取各字段值
                foreach($result[$i] as $j => $v){
                    //$result[$i][$j] = iconv(mb_detect_encoding($v, mb_detect_order(), true), 'utf-8', $v);
                    //$result[$i][$j] = iconv('gbk', 'utf-8', $v);
                    /*echo $result[$i][$j];
                    echo '<Br/>';
                    echo '<Br/>';*/
                    $result[$i][$j] = trim($result[$i][$j]);

                }


                $join = $result[$i][7];
                //$start = $result[$i][9];
                $end = $result[$i][9];
                $email = $result[$i][10];

                $join2 = $this->handleDate($join);
                //$start2 = $this->handleDate($start);
                $end2 = $this->handleDate($end);




                var_dump($email);echo '<br/>';
                var_dump($join.' | '.$join2);echo '<br/>';
                //var_dump($start,' | '.$start2);echo '<br/>';
                var_dump($end. ' | ' . $end2);echo '<br/>';
                echo '========<br/><br/>';

                if($email!=''){
                    $user = User::find()->where(['username'=>$email])->one();
                    if($user){
                        $user->join_date = $join2;
                        //$user->contract_start_date = $start2;
                        $user->contract_date = $end2;
                        $user->save();
                    }

                }

                echo '===============<Br/><Br/><Br/><Br/>';
            }
        }

        //exit;

    }


    private function handleDate($date){
        if($date==''){
            $return = '0000-00-00';
        }else{
            if(strpos($date,'-')>-1){
                $date = substr($date,strpos($date,'-')+1);
            }
            $date = str_replace('.','/',$date);
            $date = str_replace('年','/',$date);
            $date = str_replace('月','/',$date);
            $date = str_replace('日','',$date);
            if(strpos($date,'无固定')>-1){
                $return = '9999-00-00';
            }else{
                $time = strtotime($date);
                if($time)
                    $return = date('Y-m-d',$time);
                else
                    $return = '0000-00-00';
            }
        }
        return $return;

    }

    public function actionImportAll(){
        /*if(Yii::$app->request->get('remove')==1){
            User::deleteAll(['>','id',10000]);
            Department::deleteAll(['>','id',8]);
            Position::deleteAll(['>','id',7]);
            Structure::deleteAll();

exit;
        }*/


        $exist = User::find()->where(['>','id',10000])->one();
        if($exist){
            echo 'has imported';exit;
        }


        header("Content-Type: text/html; charset=UTF-8");

        $arr = ['sh','sz','szgg','wx','nj','hf','hhht'];
        foreach($arr as $a){
            $handle = fopen('../users/import3/'.$a.'.csv','r');
            $this->import($handle);
        }
        /*上海*/
        //$handle = fopen('../users/import/sh.csv','r');

        /*苏州*/
        //$handle = fopen('../users/import/sz.csv','r');
        //$handle = fopen('../users/import/szgg.csv','r');

        /*无锡*/
        //$handle = fopen('../users/import/wx.csv','r');

        /*南京*/
        //$handle = fopen('../users/import/nj.csv','r');

        /*合肥*/
        //$handle = fopen('../users/import/hf.csv','r');

        /*呼和浩特*/
        //$handle = fopen('../users/import/hhht.csv','r');
    }


    public function import($handle){

        /*上海*/
        //$handle = fopen('../users/import/sh.csv','r');

        /*苏州*/
        //$handle = fopen('../users/import/sz.csv','r');
        //$handle = fopen('../users/import/szgg.csv','r');

        /*无锡*/
        //$handle = fopen('../users/import/wx.csv','r');

        /*南京*/
        //$handle = fopen('../users/import/nj.csv','r');

        /*合肥*/
        //$handle = fopen('../users/import/hf.csv','r');

        /*呼和浩特*/
        //$handle = fopen('../users/import/hhht.csv','r');


        //$handle = fopen($file['tmp_name'], 'r');
        $data = [];
        $usernameList = [];
        $wrongNum = 0;

        $result = $this->input_csv($handle); //解析csv
        $len_result = count($result);
        //var_dump($len_result);
        if($len_result>0){
            //$aidArr = array_flip(Area::getArr());
            //$bidArr = array_flip(Business::getArr());



            for ($i = 2; $i < $len_result; $i++) { //循环获取各字段值
                foreach($result[$i] as $j => $v){
                    //$result[$i][$j] = iconv(mb_detect_encoding($v, mb_detect_order(), true), 'utf-8', $v);
                    //$result[$i][$j] = iconv('gbk', 'utf-8', $v);
                    /*echo $result[$i][$j];
                    echo '<Br/>';
                    echo '<Br/>';*/
                    $result[$i][$j] = trim($result[$i][$j]);

                }

                $district = $result[$i][1];
                $industry = $result[$i][2];
                $company = $result[$i][3];
                $depart = $result[$i][4];
                $depart2 = $result[$i][5];
                $pos = $result[$i][6];
                $name = $result[$i][7];
                $email = $result[$i][8];
                $sex = $result[$i][9];
                $mobile = $result[$i][10];
                $pos2 = $result[$i][12];




                var_dump($district);echo '<br/>';
                var_dump($industry);echo '<br/>';
                var_dump($company);echo '<br/>';
                var_dump($depart);echo '<br/>';
                var_dump($depart2);echo '<br/>';
                var_dump($pos);echo '<br/>';
                var_dump($name);echo '<br/>';
                var_dump($mobile);echo '<br/>';
                var_dump($email);echo '<br/>';
                var_dump($sex);echo '<br/>';
                var_dump($pos2);echo '<br/>';


                if($name!=''){
                    $district_id = $this->handleDistrict($district);
                    $industry_id = $this->handleIndustry($industry);
                    $company_id = $this->handleCompany($company);
                    $department_id = $this->handleDepart($depart,$depart2);
                    $position_id = $this->handlePos($pos,$pos2);
                    $sex = $this->handleSex($sex);

                    if($position_id>0){
                        $exist = User::find()->where(['username'=>$email])->one();
                        if(!$exist){
                            $n = new User();
                            $n->username = $email;
                            $n->password = md5('123123');
                            $n->password_true = '123123';
                            $n->name = $name;
                            $n->district_id = $district_id;
                            $n->industry_id = $industry_id;
                            $n->company_id = $company_id;
                            $n->department_id = $department_id;
                            $n->position_id = $position_id;
                            $n->gender = $sex;
                            $n->mobile = $mobile;
                            $n->ord = 99;
                            $n->status = 1;
                            $n->save();
                        }
                    }
                }

echo '===============<Br/><Br/><Br/><Br/>';
            }
        }

        //exit;

    }

    public function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }

    public function handleDistrict($dis){
        if($dis=='')
            $dis = '--';
        $a = District::find()->where(['name'=>$dis])->one();
        if(!$a){
            $a = new District();
            $a->name = $dis;
            $a->alias = time().rand(0,100).rand(0,100).rand(0,100);
            $a->ord = 1;
            $a->status = 1;
            $a->save();
        }
        return $a->id;
    }


    public function handleSex($sex){
        if($sex=='男'){
            $return = 1;
        }elseif($sex=='女'){
            $return = 2;
        }else{
            $return = 0;
        }
        return $return;
    }

    public function handleIndustry($ind){
        if($ind=='')
            $ind = '--';
        $a = Industry::find()->where(['name'=>$ind])->one();
        if(!$a){
            $a = new Industry();
            $a->name = $ind;
            $a->alias = time().rand(0,100).rand(0,100).rand(0,100);
            $a->ord = 1;
            $a->status = 1;
            $a->save();
        }
        return $a->id;
    }

    public function handleCompany($com){
        if($com=='')
            $com = '--';
        $a = Company::find()->where(['name'=>$com])->one();
        if(!$a){
            $a = new Company();
            $a->name = $com;
            $a->alias = time().rand(0,100).rand(0,100).rand(0,100);
            $a->ord = 1;
            $a->status = 1;
            $a->save();
        }
        return $a->id;
    }

    public function handleDepart($depart,$depart2){
        if($depart==''){
            $depart = '--';
            $depart2 = '';
        }
        $d1 = Department::find()->where(['name'=>$depart])->one();
        if(!$d1){
            $d1 = new Department();
            $d1->name = $depart;
            $d1->alias = time().rand(0,100).rand(0,100).rand(0,100);
            $d1->p_id = 0;
            $d1->ord = 1;
            $d1->status = 1;
            $d1->save();
        }
        $did = $d1->id;
        if($depart2!=''){
            $d2 = Department::find()->where(['p_id'=>$did,'name'=>$depart2])->one();
            if(!$d2){
                $d2 = new Department();
                $d2->name = $depart2;
                $d2->alias = time().rand(0,100).rand(0,100).rand(0,100);
                $d2->p_id = $did;
                $d2->ord = 1;
                $d2->status = 1;
                $d2->save();
            }
            $did = $d2->id;
        }
        return $did;
    }

    public function handlePos($pos,$pos2){
        if($pos==''||$pos2==''||$pos2=='取消'){
            return 0;
        }
        $posBase = Position::find()->where(['p_id'=>0,'name'=>$pos2])->one();
        if(!$posBase){
            return 0;
        }
        $p_id = $posBase->id;
        $posTrue = Position::find()->where(['p_id'=>$p_id,'name'=>$pos])->one();
        if(!$posTrue){
            $posTrue = new Position();
            $posTrue->name = $pos;
            $posTrue->alias = time().rand(0,100).rand(0,100).rand(0,100);
            $posTrue->p_id = $p_id;
            $posTrue->ord = 1;
            $posTrue->status = 1;
            $posTrue->save();
        }
        return $posTrue->id;
    }

    public function actionExportAll(){
        error_reporting(E_ALL);

        $data = User::find()->all();
        foreach($data as $k=>$d){
$d->district_id = CommonFunc::getByCache(District::className(),'getName',[$d->district_id],'ucenter:district/name');
$d->industry_id = CommonFunc::getByCache(Industry::className(),'getName',[$d->industry_id],'ucenter:industry/name');
$d->company_id = CommonFunc::getByCache(Company::className(),'getName',[$d->company_id],'ucenter:company/name');
$d->department_id = CommonFunc::getByCache(Department::className(),'getFullRoute',[$d->department_id],'ucenter:department/full-route');
$d->position_id = CommonFunc::getByCache(Position::className(),'getName',[$d->position_id],'ucenter:position/name');
            $data[$k] = $d;
        }

        Excel::export([
            'models'=>$data,
            'fileName'=>'职员列表_'.time(),
            'columns'=>['id','username','name','district_id','industry_id','company_id','department_id','position_id'],
            'headers'=>[
                'id'=>'ID',
                'username'=>'用户名',
                'name'=>'姓名',
                'district_id'=>'地区',
                'industry_id'=>'行业',
                'company_id'=>'公司',
                'department_id'=>'部门',
                'position_id'=>'职位',
            ],
        ]);
    }


    public function actionExportAll2(){
        error_reporting(E_ALL);

        $data = User::find()
            ->where(['district_id'=>10005,'status'=>1])
            ->orderBy('industry_id asc, department_id asc')
            ->all();
        foreach($data as $k=>$d){
            $d->district_id = CommonFunc::getByCache(District::className(),'getName',[$d->district_id],'ucenter:district/name');
            $d->industry_id = CommonFunc::getByCache(Industry::className(),'getName',[$d->industry_id],'ucenter:industry/name');
            $d->company_id = CommonFunc::getByCache(Company::className(),'getName',[$d->company_id],'ucenter:company/name');
            $d->department_id = CommonFunc::getByCache(Department::className(),'getFullRoute',[$d->department_id],'ucenter:department/full-route');
            $d->position_id = CommonFunc::getByCache(Position::className(),'getName',[$d->position_id],'ucenter:position/name');

            $data[$k] = $d->attributes;

            $data[$k]['status'] = $d->status==1?'正常':'禁用';


            $authList = \ucenter\models\UserAppAuth::getAuthList($d->id);
            $data[$k]['yun_status'] = $authList['isYunFrontend']?'启用':($authList['isYunFrontendAdmin']?'启用*':'禁用');
            $data[$k]['oa_status'] = $authList['isOaFrontend']?'启用':($authList['isOaFrontendAdmin']?'启用*':'禁用');

        }

        Excel::export([
            'models'=>$data,
            'fileName'=>'南京职员列表_'.time(),
            'columns'=>['id','username','name','district_id','industry_id','company_id','department_id','position_id','status','yun_status','oa_status'],
            'headers'=>[
                'id'=>'ID',
                'username'=>'用户名',
                'name'=>'姓名',
                'district_id'=>'地区',
                'industry_id'=>'行业',
                'company_id'=>'公司',
                'department_id'=>'部门',
                'position_id'=>'职位',
                'status'=>'账号状态',
                'yun_status'=>'颂唐云状态',
                'oa_status'=>'颂唐OA状态'
            ],
        ]);
    }
    //操作记录
    public function actionHistory(){
        $start_time = date('Y-m-d',strtotime('-1 month'));

        $end_time = date('Y-m-d');



        //获取有过访问记录的用户ID
        $userResult = UserHistory::find()->where(['>','user_id',0])->groupBy('user_id')->all();
        $userIds = [];
        foreach($userResult as $u){
            $userIds[] = $u->user_id;
        }



        $list = User::find()->where(['id'=>$userIds])->orderBy('id asc')->all();



        $patterns = [
            'login'=>[
                'n'=>'登录系统',
                'u'=>Yii::$app->params['loginUrl'],
                'a'=>[
                    'site/index'=>'登录',
                    'site/logout'=>'退出'
                ]
            ],
            'ucenter'=>[
                'n'=>'用户中心',
                'u'=>Yii::$app->params['ucenterAppUrl'],
                'a'=>[
                    'site/index'=>'首页',
                    'user/index'=>'用户列表'
                ]
            ],
            'yun'=>[
                'n'=>'颂唐云',
                'u'=>Yii::$app->params['yunAppUrl'],
                'a'=>[
                    'site/index'=>'首页',
                    'dir/index'=>'目录'
                ]
            ],
            'oa'=>[
                'n'=>'颂唐OA',
                'u'=>Yii::$app->params['oaAppUrl'],
                'a'=>[
                    'site/index'=>'首页',
                    'apply/my'=>'我的申请',
                    'apply/create'=>'发起申请'
                ]
            ]
        ];

        /*$actionArr = [
            'login'=>[
                'site/index'=>'登录',
                'site/logout'=>'退出'
            ],
            'ucenter'=>[
                'site/index'=>'首页',
                'user/index'=>'用户列表'
            ],
            'yun'=>[
                'site/index'=>'首页',
                'dir/index'=>'目录'
            ],
            'oa'=>[
                'site/index'=>'首页',
                'apply/my'=>'我的申请',
                'apply/create'=>'发起申请'
            ]
        ];*/

        $history_list = [];

        foreach($list as $l){
            $temp = [];
            $userHistory = UserHistory::find()->where(['user_id'=>$l->id])->all();
            foreach($userHistory as $uh){
                $temp2 = [];
                $flag = false; //是否匹配标志
                foreach($patterns as $k=>$p){
                    if(strpos($uh->url,$p['u'])===0){
                        $temp2[$k][] = $uh;
                        $flag = true;
                    }
                }
                if($flag == false){
                    $temp2['other'][] = $uh;
                }
            }
            $history_list[$l->id] = $temp;
        }





        $params['list'] = $list;
        $params['history_list'] = $history_list;

        return $this->render('history',$params);
    }
}
