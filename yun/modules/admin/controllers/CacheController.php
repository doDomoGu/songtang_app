<?php

namespace yun\modules\admin\controllers;

use Yii;

class CacheController extends BaseController
{

    public function actionIndex(){
        $this->view->title = '缓存管理';
        $params['list'] = $list = [
            'dir'=>'目录',
            'dir-permission'=>'权限'
        ];
        $params['keyList'] = $keyList = [
            'dir'=>[
                'drop-down-list'
            ],
            'dir-permission'=>[

            ]
        ];

        $key = Yii::$app->request->get('key',false);
        if($key){
            $cache = Yii::$app->cache;
            $cleared = []; //被清除的列表
            if($key == 'all'){
                foreach($keyList as $k=>$kList){
                    foreach($kList as $k2){
                        $_k = 'yun:'.$k.'/'.$k2;
                        $cache->delete($_k);
                        $cleared['all'][] = $_k;
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
}
