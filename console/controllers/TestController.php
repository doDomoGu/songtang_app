<?php

namespace console\controllers;
use ucenter\models\User;
use ucenter\models\UserAppAuth;
use Yii;
use yii\base\Exception;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionCopyDb(){
        $db = Yii::$app->db;
        $dbList = ['songtang_common','songtang_oa','songtang_ucenter','songtang_yun'];

        foreach($dbList as $dbname){
            $testDbname = 'test_'.$dbname;
            $db->createCommand('DROP DATABASE if exists '.$testDbname)->execute();
            $db->createCommand('CREATE DATABASE '.$testDbname.' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;')->execute();

            $db->createCommand('use '.$dbname)->execute();
            $tables = $db->createCommand('show tables;')->queryAll();

            $db->createCommand('use '.$testDbname)->execute();
            foreach($tables as $t){
                $tablename = array_pop($t);

                $db->createCommand('CREATE TABLE `'.$testDbname.'`.`'.$tablename.'` LIKE `'.$dbname.'`.`'.$tablename.'`')->execute();
                $db->createCommand('INSERT INTO `'.$testDbname.'`.`'.$tablename.'` SELECT * FROM `'.$dbname.'`.`'.$tablename.'`')->execute();
//;


            }
        }
        echo 'finish '."\n";

//
//        DROP DATABASE  if exists test_songtang_yun;
//CREATE DATABASE test_songtang_yun;
//CREATE TABLE test_songtang_yun.dir LIKE songtang_yun.dir;
//INSERT INTO test_songtang_yun.dir SELECT * FROM songtang_yun.dir;

    }

    public function actionAddAppUserAuth(){

        /*$exist = UserAppAuth::find()->where('app = "oa-frontend" or app = "yun-frontend"')->one();
        if($exist){
            echo 'has added'."\n";
        }else{*/
            $uList = User::find()->all();

            foreach($uList as $u){
                $exist = UserAppAuth::find()->where('app = "oa-frontend" and user_id = '.$u->id)->one();
                if(!$exist) {
                    $n = new UserAppAuth();
                    $n->app = 'oa-frontend';
                    $n->user_id = $u->id;
                    $n->is_enable = 1;
                    $n->save();
                }
            }

            foreach($uList as $u){
                $exist = UserAppAuth::find()->where('app = "yun-frontend" and user_id = '.$u->id)->one();
                if(!$exist) {

                    $n = new UserAppAuth();
                    $n->app = 'yun-frontend';
                    $n->user_id = $u->id;
                    $n->is_enable = 1;
                    $n->save();
                }
            }

            echo 'add finish'."\n";
        /*}*/
    }


    public function actionClearRuntime($type='debug'){
        $appDirList = [
            Yii::getAlias('@common'),
            Yii::getAlias('@api'),
            Yii::getAlias('@login'),
            Yii::getAlias('@oa'),
            Yii::getAlias('@ucenter'),
            Yii::getAlias('@yun')
        ];

        foreach($appDirList as $appDir){
            $dir = $appDir.'/runtime/'.$type;
            if(is_dir($appDir.'/runtime')){
                $this->rmDir($dir);
            }
        }
    }

    public function rmDir($dir){
        if(is_dir($dir)){
            $handle=opendir($dir);
            echo $dir."\n";
            $num = 0;
            while ($file = readdir($handle)) {

                if (($file!=".") and ($file!="..")) {
                    $path = $dir.'/'.$file;

                    if (is_dir($path)) {
                        $this->rmDir($path);
                    } else {
                        unlink($path);
                        $num++;
                    }
                }
            }
            echo 'cleared '.$num.' file(s)'."\n";
            closedir($handle);
            rmdir($dir);
        }
    }


    public function actionAlarm(){
        //$headers = "Content-type:text/html;charset=utf-8" . "\r\n";
        try {
            User::find()->count();

        } catch (Exception $e) {
            echo $e->getMessage();
            echo "\n";


            mail('71936410@qq.com','songtang error',$e->getMessage());
            exit();
        }



        mail('71936410@qq.com','songtang no error mail','no error');
        //error_log('songtang no error',1,'71936410@qq.com');
        echo "finish\n";
        exit;

        /*try {
            $mgr = new CommandManager();
            $cmd = $mgr->getCommandObject("realcommand");
            $cmd->execute();
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        $user_count = User::find()->count();*/


    }

}