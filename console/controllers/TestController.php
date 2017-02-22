<?php

namespace console\controllers;
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


}