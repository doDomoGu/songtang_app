<?php

namespace ucenter\controllers;

use Yii;
use ucenter\components\UcenterFunc;

class CacheController extends BaseController
{


    public function actionIndex(){
        $this->view->title = '缓存管理';
        $params['list'] = $list =  UcenterFunc::$cacheList;
        $params['keyList'] = $keyList =  UcenterFunc::$cacheKeyList;

        $key = Yii::$app->request->get('key',false);
        if($key){
            $cache = Yii::$app->cache;
            $cleared = []; //被清除的列表
            if($key == 'all'){
                foreach($keyList as $k=>$kList){
                    foreach($kList as $k2){
                        $_k = 'ucenter:'.$k.'/'.$k2;
                        $cache->delete($_k);
                        $cleared[$k][] = $_k;
                    }
                }
            }elseif(isset($list[$key]) && isset($keyList[$key])){
                foreach($keyList[$key] as $k2) {
                    $_k = 'ucenter:' . $key . '/' . $k2;
                    $cache->delete($_k);
                    $cleared[$key][] = $_k;
                }

            }

            $params['cleared'] = $cleared;
        }

        return $this->render('index',$params);
    }
}
