<?php

namespace yun\modules\admin\controllers;

use Yii;
use yun\components\YunFunc;

class CacheController extends BaseController
{


    public function actionIndex(){
        $this->view->title = '缓存管理';
        $params['list'] = $list =  YunFunc::$cacheList;
        $params['keyList'] = $keyList =  YunFunc::$cacheKeyList;

        $key = Yii::$app->request->get('key',false);
        if($key){
            $cache = Yii::$app->cache;
            $cleared = []; //被清除的列表
            if($key == 'all'){
                foreach($keyList as $k=>$kList){
                    foreach($kList as $k2){
                        $_k = 'yun:'.$k.'/'.$k2;
                        $cache->delete($_k);
                        $cleared[$k][] = $_k;
                    }
                }
            }elseif(isset($list[$key]) && isset($keyList[$key])){
                foreach($keyList[$key] as $k2) {
                    $_k = 'yun:' . $key . '/' . $k2;
                    $cache->delete($_k);
                    $cleared[$key][] = $_k;
                }

            }

            $params['cleared'] = $cleared;
        }

        return $this->render('index',$params);
    }

    public function actionTemp(){
        $cache = Yii::$app->cache;
        $cache->delete('dir-children-data');
    }
}
