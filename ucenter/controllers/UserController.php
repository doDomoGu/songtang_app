<?php
namespace ucenter\controllers;

use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\Structure;
use ucenter\models\User;
use ucenter\models\UserForm;
use Yii;
use yii\web\Response;

class UserController extends BaseController
{
    public function actionIndex(){
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $list = User::find()->where([])->groupBy('id')->all();


        $params['list'] = $list;
        $params['aArr'] = Area::getNameArr();
        $params['bArr'] = Business::getNameArr();
        $params['pArr'] = Position::getNameArr();
        $params['bArr2'] = Area::getRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;

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

    public function actionStructUpdate(){
        //地区和业态关系
        $list = User::find()->groupBy(['aid','bid'])->select(['aid','bid'])->all();
        foreach($list as $l){
            $this->structAdd($l->aid,$l->bid,0);
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

        $list = User::find()->groupBy(['bid','did'])->select(['bid','did'])->all();
        foreach($list as $l){
            $this->structAdd(0,$l->bid,isset($dArr[$l->did])?$dArr[$l->did]:0);
        }


        //整套设置

        $list = User::find()->groupBy(['aid','bid','did'])->select(['aid','bid','did'])->all();
        foreach($list as $l){
            $this->structAdd($l->aid,$l->bid,isset($dArr[$l->did])?$dArr[$l->did]:0);
        }

    }

    public function structAdd($aid,$bid,$did){
        $n = Structure::find()->where(['aid'=>$aid,'bid'=>$bid,'did'=>$did])->one();
        if(!$n){
            $n = new Structure();
            $n->aid = $aid;
            $n->bid = $bid;
            $n->did = $did;
            $n->ord = 1;
            $n->status = 1;
            $n->save();
        }
    }


    public function actionImportAll(){
        if(Yii::$app->request->get('remove')==1){
            User::deleteAll(['>','id',10000]);
            Department::deleteAll(['>','id',8]);
            Position::deleteAll(['>','id',7]);
            Structure::deleteAll();

exit;
        }


        $exist = User::find()->where(['>','id',10000])->one();
        if($exist){
            echo 'has imported';exit;
        }


        header("Content-Type: text/html; charset=UTF-8");

        $arr = ['sh','sz','szgg','wx','nj','hf','nmg'];
        //$arr = ['sh'];
        foreach($arr as $a){
            $handle = fopen('../users/import2/'.$a.'.csv','r');
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

                $area = $result[$i][1];
                $business = $result[$i][2];
                $depart = $result[$i][3];
                $depart2 = $result[$i][4];
                $pos = $result[$i][5];
                $name = $result[$i][6];
                $email = $result[$i][7];
                $sex = $result[$i][8];
                $mobile = $result[$i][9];
                $pos2 = $result[$i][11];




                var_dump($area);echo '<br/>';
                var_dump($business);echo '<br/>';
                var_dump($depart);echo '<br/>';
                var_dump($depart2);echo '<br/>';
                var_dump($pos);echo '<br/>';
                var_dump($name);echo '<br/>';
                var_dump($mobile);echo '<br/>';
                var_dump($email);echo '<br/>';
                var_dump($sex);echo '<br/>';
                var_dump($pos2);echo '<br/>';


                if($name!=''){
                    $aid = $this->handleArea($area);
                    $bid = $this->handleBusiness($business);
                    $did = $this->handleDepart($depart,$depart2);
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
                            $n->aid = $aid;
                            $n->bid = $bid;
                            $n->did = $did;
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

    public function handleArea($area){
        if($area=='')
            $area = '[缺省]';
        $a = Area::find()->where(['name'=>$area])->one();
        if(!$a){
            $a = new Area();
            $a->name = $area;
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

    public function handleBusiness($business){
        if($business=='')
            $business = '[缺省]';
        $a = Business::find()->where(['name'=>$business])->one();
        if(!$a){
            $a = new Business();
            $a->name = $business;
            $a->alias = time().rand(0,100).rand(0,100).rand(0,100);
            $a->ord = 1;
            $a->status = 1;
            $a->save();
        }
        return $a->id;
    }

    public function handleDepart($depart,$depart2){
        if($depart==''){
            $depart = '[缺省]';
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
}
