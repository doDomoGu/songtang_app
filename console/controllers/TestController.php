<?php

namespace console\controllers;
use ucenter\models\User;
use ucenter\models\UserAppAuth;
use Yii;
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

        $exist = UserAppAuth::find()->where('app = "oa-frontend" or app = "yun-frontend"')->one();
        if($exist){
            echo 'has added'."\n";
        }else{
            $uList = User::find()->all();

            foreach($uList as $u){
                $n = new UserAppAuth();
                $n->app = 'oa-frontend';
                $n->user_id = $u->id;
                $n->is_enable = 1;
                $n->save();
            }

            foreach($uList as $u){
                $n = new UserAppAuth();
                $n->app = 'yun-frontend';
                $n->user_id = $u->id;
                $n->is_enable = 1;
                $n->save();
            }

            echo 'add finish'."\n";
        }
    }


    public function actionClearDebug(){
        $dirList = [
            Yii::getAlias('@api'),
            Yii::getAlias('@login'),
            Yii::getAlias('@oa'),
            Yii::getAlias('@ucenter'),
            Yii::getAlias('@yun')
        ];
        foreach($dirList as $dir){
            $dirDebug = $dir.'/runtime/debug';
            if(is_dir($dir.'/runtime') && is_dir($dir.'/runtime/debug')){
                $handle=opendir($dirDebug);
                echo $dirDebug."\n";
                $num = 0;
                while ($file = readdir($handle)) {
                    if (($file!=".") and ($file!="..")) {
                        unlink($dirDebug.'/'.$file);
                        $num++;
                    }
                }
                echo 'cleared '.$num.' file(s)'."\n";
                closedir($handle);
            }
        }
exit;
    }


}