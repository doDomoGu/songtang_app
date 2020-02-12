<?php
namespace yun\controllers;

use common\components\CommonFunc;
use ucenter\models\User;
use yun\components\DirFunc;
use yun\components\QiniuUpload;
use yun\models\Dir;
use Yii;
use yun\models\DirPermission;
use yun\models\UserGroup;
use yun\models\UserGroupUser;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest(){
        $s = CommonFunc::getByCache(Dir::className(),'getFullRoute',['82','">>"'],'dir-full-route-22');

        var_dump($s);exit;
    }
    public function actionRedis(){

        Yii::$app->cache->set('test', 'hehe..');
        echo Yii::$app->cache->get('test'), "\n";

        Yii::$app->cache->set('test1', 'haha..', 5);
        echo '1 ', Yii::$app->cache->get('test1'), "\n";
        sleep(6);
        echo '2 ', Yii::$app->cache->get('test1'), "\n";
    }

    public function actionNoAuth(){
        return $this->render('no_auth');
    }

    public function actionClear(){
        $cache = yii::$app->cache;
        $cache['dirDataId'] = [];
        $cache['dirChildrenDataId'] = [];
        $cache['treeDataid'] = [];
    }
    public function actionIndex(){
        $dir_1 = Dir::find()->where(['p_id'=>0,'ord'=>1])->one();
        $dir_2 = Dir::find()->where(['p_id'=>0,'ord'=>2])->one();
        $dir_3 = Dir::find()->where(['p_id'=>0,'ord'=>3])->one();
//        $dir_4 = Dir::find()->where(['p_id'=>0,'ord'=>4])->one();
//        $dir_5 = Dir::find()->where(['p_id'=>0,'ord'=>5])->one();

        $params['list_dirOne'] = [
            1=>$dir_1,
            2=>$dir_2,
            3=>$dir_3,
//            4=>$dir_4,
//            5=>$dir_5
        ];

        $limit = 10;
        $params['list_1'] = Dir::getChildrenByCache($dir_1->id,true,1,Dir::ORDER_TYPE_1,$limit);
        $params['list_2'] = Dir::getChildrenByCache($dir_2->id,true,1,Dir::ORDER_TYPE_1,$limit);
        $params['list_3'] = Dir::getChildrenByCache($dir_3->id,true,1,Dir::ORDER_TYPE_1,$limit);
//        $params['list_4'] = Dir::getChildrenByCache($dir_4->id,true,1,Dir::ORDER_TYPE_1,$limit);
//        $params['list_5'] = Dir::getChildrenByCache($dir_5->id,true,1,Dir::ORDER_TYPE_1,$limit);
        $this->view->title = yii::$app->name;
        return $this->render('index',$params);
    }

    public function actionGetQiniuUptoken(){
        $up=new QiniuUpload(yii::$app->params['qiniu-bucket']);
        $saveKey = yii::$app->request->get('saveKey','');
        $upToken=$up->createtoken($saveKey);
        echo json_encode(['uptoken'=>$upToken]);exit;
    }

    public function actionInstall(){
        $m = new Dir();
        $m->install();

        exit;
    }

    public function actionInstallAuth(){
        UserGroupUser::deleteAll();
        UserGroup::deleteALl();
        DirPermission::deleteAll();


        $usergroupExist = UserGroup::find()->all();
        if(!empty($usergroupExist)) {
            echo 'auth installed';
            exit;
        }

        $all = [2,72,83,103,16];
        foreach($all as $a){
            $dp = new DirPermission();
            $dp->dir_id = $a;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_ALL;
            $dp->user_match_param_id = 0;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
        }

        $uploadAll = [105];
        foreach($uploadAll as $a){
            $dp = new DirPermission();
            $dp->dir_id = $a;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_ALL;
            $dp->user_match_param_id = 0;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
        }

        $xz = [15,17,18];
        foreach($xz as $x){
            $dp = new DirPermission();
            $dp->dir_id = $x;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT;
            $dp->user_match_type = DirPermission::TYPE_ALL;
            $dp->user_match_param_id = 0;
            $dp->operation = DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
        }



        $fawu = [22];
        $fawuUser = [
            'zhaoning',
            'zhujunfeng',
            'zhouxiaofang',
            'liujinping',
            'zhujunling',
            'yaojingjie',
            'mujin',
            'zhangxuhui',
            'wuxiaojin',
            'zhoujian',
            'zhourong',
            'zhangyewen',
            'kongyajun',
            'zhangxiwen',
            'baijie',
            'chenying',
            'shulihai',
            'yanglihai',
            'zhaoyang',
            'cuixiaoye',
            'xiadongen',
            'lvhaifeng',
            'dengwei',
            'hanaiqing',
            'hanaiqun',
            'lixiang',
            'liuxi',
            'luqiufeng',
            'chengchen',
            'maoshoujun',
            'zhaxuesong',
            'yangjie'
        ];

        $caiwu = [19];
        $caiwuUser = [
            'zhaoning',
            'wanglei',
            'chenhuafang',
            'xiangpeiying',
            'zhujunling',
            'yaojingjie',
            'zhangxuhui',
            'yeqian',
            'shenjie',
            'chenying',
            'huiminjuan',
            //'renjing',
            'shulihai',
            'haiaiqing',
            'zhongjuan',
            'hanaiqun',
            'lixiang',
            'haijun',
            'liyingjiao'
        ];

        echo 'fawu =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[法务管控中心]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            foreach($fawu as $f){
                $dp = new DirPermission();
                $dp->dir_id = $f;
                $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
                $dp->user_match_type = DirPermission::TYPE_GROUP;
                $dp->user_match_param_id = $usergroup->id;
                $dp->operation =DirPermission::OPERATION_DOWNLOAD;
                $dp->mode = DirPermission::MODE_ALLOW;
                $dp->save();
                $dp = new DirPermission();
                $dp->dir_id = $f;
                $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
                $dp->user_match_type = DirPermission::TYPE_GROUP;
                $dp->user_match_param_id = $usergroup->id;
                $dp->operation =DirPermission::OPERATION_UPLOAD;
                $dp->mode = DirPermission::MODE_ALLOW;
                $dp->save();
            }


            foreach($fawuUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'fawu finish=====<br/><br/>';
        echo 'caiwuwu =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[财务管控中心]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            foreach($caiwu as $c){
                $dp = new DirPermission();
                $dp->dir_id = $c;
                $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
                $dp->user_match_type = DirPermission::TYPE_GROUP;
                $dp->user_match_param_id = $usergroup->id;
                $dp->operation =DirPermission::OPERATION_DOWNLOAD;
                $dp->mode = DirPermission::MODE_ALLOW;
                $dp->save();
                $dp = new DirPermission();
                $dp->dir_id = $c;
                $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
                $dp->user_match_type = DirPermission::TYPE_GROUP;
                $dp->user_match_param_id = $usergroup->id;
                $dp->operation =DirPermission::OPERATION_UPLOAD;
                $dp->mode = DirPermission::MODE_ALLOW;
                $dp->save();
            }


            foreach($caiwuUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'caiwu finish =====<br/><br/>';


        $rcAll = 26;

        $rcAllUser = [
            'zhaoning',
            'zhouxiaofang',
            'zhujunling',
            'yaojingjie',
            'zhangxuhui',
            'kongyajun',
            'yanglihai',
            'hanaiqun',
            'lixiang',
            'haijun'
        ];

        echo 'rcAll =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[人才资源中心全部档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcAll;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcAll;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcAllUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcAll finish =====<br/><br/>';

        $rc2 = [39,51];
        $rc2User = [
            'liujinping',
            'jiangaiqi',
            'zhangyewen',
            'yangyanhong',
            'chenying',
            'chenmenglong',
            'hanaiqing',
            'zhongjuan',
            'zhuyuyao',
            'chengchen',
            'yangjie'
        ];


        echo 'rc2 =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[离职和应聘人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            foreach($rc2 as $c){
                $dp = new DirPermission();
                $dp->dir_id = $c;
                $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
                $dp->user_match_type = DirPermission::TYPE_GROUP;
                $dp->user_match_param_id = $usergroup->id;
                $dp->operation =DirPermission::OPERATION_DOWNLOAD;
                $dp->mode = DirPermission::MODE_ALLOW;
                $dp->save();
                $dp = new DirPermission();
                $dp->dir_id = $c;
                $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
                $dp->user_match_type = DirPermission::TYPE_GROUP;
                $dp->user_match_param_id = $usergroup->id;
                $dp->operation =DirPermission::OPERATION_UPLOAD;
                $dp->mode = DirPermission::MODE_ALLOW;
                $dp->save();
            }


            foreach($rc2User as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rc2 finish =====<br/><br/>';


        $rcSH = 29;
        $rcSHUser = [
            'liujinping',
            'jiangaiqi'
        ];
        echo 'rcSH =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[上海在职人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcSH;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcSH;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcSHUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcSH finish =====<br/><br/>';

        $rcSZ = 33;
        $rcSZUser = [
            'zhangyewen',
            'yangyanhong'
        ];
        echo 'rcSZ =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[苏州在职人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcSZ;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcSZ;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcSZUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcSZ finish =====<br/><br/>';

        $rcWX = 36;
        $rcWXUser = [
            'chenying',
            'chenmenglong'
        ];
        echo 'rcWX =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[无锡在职人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcWX;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcWX;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcWXUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcWX finish =====<br/><br/>';


        $rcNJ = 106;
        $rcNJUser = [
            'hanaiqing',
            'zhongjuan',
            'zhuyuyao'
        ];
        echo 'rcNJ =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[南京在职人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcNJ;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcNJ;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcNJUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcNJ finish =====<br/><br/>';


        $rcHF = 109;
        $rcHFUser = [
            'chengchen'
        ];
        echo 'rcHF =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[合肥在职人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcHF;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcHF;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcHFUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcHF finish =====<br/><br/>';


        $rcHHHT = 110;
        $rcHHHTUser = [
            'yangjie'
        ];
        echo 'rcHHHT =====<br/>';
        $usergroup = new UserGroup();
        $usergroup->name = '[呼和浩特在职人员档案]权限';
        $usergroup->status = 1;
        if($usergroup->save()){
            $dp = new DirPermission();
            $dp->dir_id = $rcHHHT;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_DOWNLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();
            $dp = new DirPermission();
            $dp->dir_id = $rcHHHT;
            $dp->permission_type = DirPermission::PERMISSION_TYPE_NORMAL;
            $dp->user_match_type = DirPermission::TYPE_GROUP;
            $dp->user_match_param_id = $usergroup->id;
            $dp->operation =DirPermission::OPERATION_UPLOAD;
            $dp->mode = DirPermission::MODE_ALLOW;
            $dp->save();


            foreach($rcHHHTUser as $u){
                $user = User::find()->where(['username'=>$u.'@songtang.net'])->one();
                echo $u;
                if($user){
                    echo ' yes'.'<br/>';
                    $n = new UserGroupUser();
                    $n->group_id = $usergroup->id;
                    $n->user_id = $user->id;
                    $n->save();
                }
            }
        }
        echo 'rcHHHT finish =====<br/><br/>';
    }

    public function actionFooter(){
        $this->layout = false;
        return $this->render('footer');
    }
}
