<?php
namespace yun\controllers;

use yun\components\DirFunc;
use yun\components\FileFrontFunc;
use yun\components\PermissionFunc;
use yun\components\QiniuUpload;
use yun\models\News;
use yun\models\Dir;
use yun\models\Recruitment;
use Yii;
use yun\models\SystemLog;


class DirController extends BaseController
{
    public $layout = 'main_dir';

    public $orderArr = [
        'filename.desc',
        'filename.asc',
        'filesize.desc',
        'filesize.asc',
        'add_time.desc',
        'add_time.asc',
        /*'clicks.desc',
        'clicks.asc'*/
    ];

    public $orderNameArr = [
        '文件名倒序',
        '文件名正序',
        '文件从大到小',
        '文件从小到大',
        '时间从新到旧',
        '时间从旧到新',
        /*'下载量从大到小',
        '下载量从小到大'*/
    ];

    public $previewTypeArr = [2,3,4,5,6];

    public $thumbTypeArr = [2,3,4,5,6];

    public $listTypeArr = ['list','grid'];

    public $listTypeNameArr = ['列表','图标'];

    public $listStylePageSize = 20;

    public $gridStylePageSize = 24;

    public $dir_id;


    public function actionIndex()
    {
        //url参数 p_id & dir_id 两者只存在一个 先取p_id
        $p_id = Yii::$app->request->get('p_id',false);

        if($p_id!=false && $p_id != (string)intval($p_id)){
            //验证dir_id的值是不是为纯数字  当有错误时报错
            ## 日志记录 ##
            SystemLog::user_log(
                SystemLog::LEVEL_WARN,
                'dir',
                '打开目录参数错误: p_id => '.$p_id
            );
            return yii::$app->runAction('/site/error');
        }else{
            if($p_id==false){
                $parDir = false;
            }else{
                $parDir = File::find()->where(['id'=>$p_id,'status'=>1])->one();
            }
        }

        //如果parDir存在 , 给dir_id赋值
        if($p_id!==false && $parDir && $parDir->dir_id>0){
            $dir_id = $parDir->dir_id;
        }else{//如果parDir部存在 , 取url中dir_id参数
            $dir_id = Yii::$app->request->get('dir_id',false);
            if($dir_id!=false && $dir_id != (string)intval($dir_id)){
                //验证dir_id的值是不是为纯数字  当有错误时报错
                ## 日志记录 ##
                SystemLog::user_log(
                    SystemLog::LEVEL_WARN,
                    'dir',
                    '打开目录参数错误: dir_id => '.$dir_id
                );
                return yii::$app->runAction('/site/error');
            }
            $p_id = 0;
        }

        $curDir = Dir::find()->where(['id'=>$dir_id,'status'=>1])->one();

        if($curDir){
            //$this->dir_id = $dir_id;
            $dirRoute = ''; //目录路径 用来在七牛上传文件时，拼接文件名
            //面包屑 & 文件路径
            $parents = DirFunc::getParents($dir_id);
            $parents2 = FileFrontFunc::getParents($p_id);
            if(!empty($parents2)){
                foreach($parents as $parent){
                    $this->view->params['breadcrumbs'][] = ['label'=>$parent->name,'url'=>['/dir','dir_id'=>$parent->id]];
                    $dirRoute .= $parent->name.'>';
                }
                $i=0;
                foreach($parents2 as $parent){
                    $i++;
                    if($i<count($parents2)){
                        $this->view->params['breadcrumbs'][] = ['label'=>$parent->filename,'url'=>['/dir','p_id'=>$parent->id]];
                    }else{
                        $this->view->params['breadcrumbs'][] = ['label'=>$parent->filename];
                    }
                    $dirRoute .= $parent->filename.'>';
                }
            }else{
                $i=0;
                foreach($parents as $parent){
                    $i++;
                    if($i<count($parents)){
                        $this->view->params['breadcrumbs'][] = ['label'=>$parent->name,'url'=>['/dir','dir_id'=>$parent->id]];
                    }else{
                        $this->view->params['breadcrumbs'][] = ['label'=>$parent->name];
                    }
                    $dirRoute .= $parent->name.'>';
                }
            }



            if($parDir)
                $this->view->title = $parDir->filename.$this->titleSuffix;
            else
                $this->view->title = $curDir->name.$this->titleSuffix;

            if($curDir->is_leaf){     //是底层目录 显示文件列表 可以进行上传/新建文件夹等操作

                $pageSize = $this->listStylePageSize;

                $page = yii::$app->request->get('page',1);

                $search = yii::$app->request->get('search',false);

                $order = yii::$app->request->get('order',false);

                $orderNum = 0;

                if(!in_array($order,$this->orderArr)){
                    $cache = Yii::$app->cache;
                    $cacheExist = false;
                    if(isset($cache['dirOrder_'.$this->user->id])){
                        if(in_array($cache['dirOrder_'.$this->user->id],$this->orderArr)){
                            $cacheExist = true;
                        }
                    }
                    if($cacheExist){
                        $order = $cache['dirOrder_'.$this->user->id];
                    }else{
                        $order = $this->orderArr[0];;
                    }
                }else{
                    $cache = Yii::$app->cache;
                    $cache['dirOrder_'.$this->user->id] = $order;
                }


                $orderSelect = [];
                foreach($this->orderArr as $n=>$ord){
                    if($order==$ord)
                        $orderNum = $n;

                    $orderSelect[$n] = $this->orderNameArr[$n];
                }



                $listType = yii::$app->request->get('list_type',false);

                $listTypeNum = 0;

                if(!in_array($listType,$this->listTypeArr)){
                    $cache = Yii::$app->cache;
                    $cacheExist = false;

                    if(isset($cache['dirListType_'.$this->user->id])){
                        if(in_array($cache['dirListType_'.$this->user->id],$this->listTypeArr)){
                            $cacheExist = true;
                        }
                    }
                    if($cacheExist){
                        $listType = $cache['dirListType_'.$this->user->id];
                    }else{
                        $listType = $this->listTypeArr[0];
                    }
                }else{
                    $cache = Yii::$app->cache;
                    $cache['dirListType_'.$this->user->id] = $listType;
                }


                $listTypeSelect = [];
                foreach($this->listTypeArr as $n=>$lT){
                    if($listType==$lT)
                        $listTypeNum = $n;

                    $listTypeSelect[$n] = $this->listTypeNameArr[$n];
                }

                if($listType == 'grid'){
                    $pageSize = $this->gridStylePageSize;
                }

                if($parDir){
                    if(!PermissionFunc::checkFileDownloadPermission($this->user->position_id,$parDir)){
                        ## 日志记录 ##
                        SystemLog::user_log(
                            SystemLog::LEVEL_WARN,
                            'dir',
                            '没有权限打开目录('.$parDir->id.':'.DirFunc::getFileFullRoute($parDir->id).')'
                        );
                        yii::$app->response->redirect('/')->send();
                    }
                }
                $list = [];
                $count = FileFrontFunc::getFilesNum($dir_id,$p_id,$search);

                /*$pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);

                $list = FileFrontFunc::getFiles($dir_id,$p_id,$pages,$orderTrue,$search);*/


                $links = [];
                $links2 = [];

                foreach($this->orderArr as $orderOne){
                    if($p_id>0)
                        $linkTmp = '/dir?p_id='.$p_id;
                    else
                        $linkTmp = '/dir?dir_id='.$dir_id;
                    $linkTmp .= $page>1?'&page='.$page:'';
                    $linkTmp .= '&order='.$orderOne;
                    $links[] = $linkTmp;
                }

                foreach($this->listTypeArr as $ltOne){
                    if($p_id>0)
                        $linkTmp = '/dir?p_id='.$p_id;
                    else
                        $linkTmp = '/dir?dir_id='.$dir_id;
                    $linkTmp .= $page>1?'&page='.$page:'';
                    $linkTmp .= '&list_type='.$ltOne;
                    $links2[] = $linkTmp;
                }


                $orderLink = [];
                $orderLink[0] = $links[0];
                $orderLink[1] = $links[2];
                $orderLink[2] = $links[4];

                $orderClass = [];
                $orderClass[0] = '';
                $orderClass[1] = '';
                $orderClass[2] = '';

                if($orderNum==0){
                    $orderLink[0] = $links[1];
                    $orderClass[0] = 'desend';
                }elseif($orderNum==1){
                    $orderClass[0] = 'ascend';
                }elseif($orderNum==2){
                    $orderLink[1] = $links[3];
                    $orderClass[1] = 'desend';
                }elseif($orderNum==3){
                    $orderClass[1] = 'ascend';
                }elseif($orderNum==4){
                    $orderLink[2] = $links[5];
                    $orderClass[2] = 'desend';
                }elseif($orderNum==5){
                    $orderClass[2] = 'ascend';
                }
//                $params['pages'] = $pages;
                $params['order'] = $order;
                $params['orderNum'] = $orderNum;
                $params['orderSelect'] = $orderSelect;
                $params['links'] = $links;
                $params['links2'] = $links2;
                $params['orderLink'] = $orderLink;
                $params['orderClass'] = $orderClass;
                $params['listType'] = $listType;
                $params['listTypeNum'] = $listTypeNum;
                $params['listTypeSelect'] = $listTypeSelect;
                $params['count'] = $count;
                $params['page'] = $page;
                $params['page_size'] = $pageSize;
                $params['page_num'] = ceil($count/$pageSize);
                $params['dirRoute'] = $dirRoute;
                $viewName = 'list';
            }else{
                $list = DirFunc::getChildren($dir_id);
                $viewName = 'index';
            }
            $params['list'] = $list;
            $params['dir_id'] = $dir_id;
            $params['p_id'] = $p_id;

            $this->view->params['dir_id'] = $dir_id;
            //$params['viewType'] = $viewName;

            return $this->render($viewName,$params);
        }else{
            $this->layout = 'main';
            yii::$app->response->statusCode = 404;
            return $this->render('error');
        }
    }

}
