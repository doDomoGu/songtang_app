<?php
namespace yun\controllers;

use common\components\CommonFunc;
use ucenter\models\District;
use ucenter\models\Industry;
use yii\data\Pagination;
use yun\components\DirFunc;
use yun\components\FileFrontFunc;
use yun\components\FileFunc;
use yun\components\PermissionFunc;
use yun\models\Attribute;
use yun\models\File;
use yun\models\FileAttribute;
use yun\models\Dir;
use yun\models\DirPermission;
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
//        //url参数 p_id & dir_id 两者只存在一个 先取p_id
//        $p_id = Yii::$app->request->get('p_id',false);
//
//        var_dump($p_id);
//        var_dump($p_id == (string)intval($p_id));exit;
//
//
//        if($p_id!=false && $p_id != (string)intval($p_id)){
//            //验证dir_id的值是不是为纯数字  当有错误时报错
//            ## 日志记录 ##
//            SystemLog::dirError('打开目录参数错误: p_id => '.$p_id);
//            return Yii::$app->runAction('/site/error');
//        }else{
//            if($p_id==false){
//                $parDir = false;
//            }else{
//                $parDir = File::find()->where(['id'=>$p_id,'status'=>1])->one();
//            }
//        }
//
//        //如果parDir存在 , 给dir_id赋值
//        if($p_id!==false && $parDir && $parDir->dir_id>0){
//            $dir_id = $parDir->dir_id;
//        }else{//如果parDir不存在 , 取url中dir_id参数
//            $dir_id = Yii::$app->request->get('dir_id',false);
//            if($dir_id!=false && $dir_id != (string)intval($dir_id)){
//                //验证dir_id的值是不是为纯数字  当有错误时报错
//                ## 日志记录 ##
//                SystemLog::dirError('打开目录参数错误: dir_id => '.$dir_id);
//                return Yii::$app->runAction('/site/error');
//            }
//            $p_id = 0;
//        }

        //两个ID只能存在一个,    （ 可通过文件夹ID获取 目录ID ）
        $p_id = Yii::$app->request->get('p_id',false);  //文件夹ID
        $dir_id = Yii::$app->request->get('dir_id',false);  //目录ID

        if($p_id!=false || $dir_id!=false){  //是否存在参数
            if($p_id != false){ //是否存在p_id (文件夹）
                if($p_id == (string)intval($p_id)) { //验证参数值是不是为纯数字  记录错误日志
                    $folder = File::find()->where(['id' => $p_id, 'status' => 1])->one(); //文件夹 非目录(dir)
                    if (!$folder) { //文件夹不存在 记录错误日志
                        SystemLog::dirError('打开目录参数错误: p_id => ' . $p_id . ' 文件夹不存在  |' . Yii::$app->request->url);
                        return Yii::$app->runAction('/site/error');
                    }else{
                        $dir_id = $folder->dir_id;  //取得目录ID
                    }
                }else{
                    SystemLog::dirError('打开目录参数错误: p_id => ' . $p_id . ' | ' . Yii::$app->request->url);
                    return Yii::$app->runAction('/site/error');
                }
            }else{
                $folder = false;
                $p_id = 0;
                if($dir_id != (string)intval($dir_id)){
                    SystemLog::dirError('打开目录参数错误: dir_id => '.$dir_id.' | '.Yii::$app->request->url);
                    return Yii::$app->runAction('/site/error');
                }
            }


            $dir = Dir::find()->where(['id'=>$dir_id,'status'=>1])->one();

            if($dir){
                $this->dir_id = $dir_id;
                $dirRoute = ''; //目录路径 用来在七牛上传文件时，拼接文件名
                //面包屑 & 文件路径
                $parents = Dir::getParents($dir_id);
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



                if($folder)
                    $this->view->title = $folder->filename.$this->titleSuffix;
                else
                    $this->view->title = $dir->name.$this->titleSuffix;

                if($dir->is_leaf){     //是底层目录 显示文件列表 可以进行上传/新建文件夹等操作

                    $pageSize = $this->listStylePageSize;

                    $page = Yii::$app->request->get('page',1);

                    $search = Yii::$app->request->get('search',false);

                    $order = Yii::$app->request->get('order',false);

                    //$areaCheck = Yii::$app->request->get('area_check',false);

                    //$businessCheck = Yii::$app->request->get('business_check',false);

                    $orderNum = 0;

                    if(!in_array($order,$this->orderArr)){
                        $cache = Yii::$app->cache;
                        $cacheExist = false;
                        if(isset($cache['dirOrder_'.Yii::$app->user->id])){
                            if(in_array($cache['dirOrder_'.Yii::$app->user->id],$this->orderArr)){
                                $cacheExist = true;
                            }
                        }
                        if($cacheExist){
                            $order = $cache['dirOrder_'.Yii::$app->user->id];
                        }else{
                            $order = $this->orderArr[0];;
                        }
                    }else{
                        $cache = Yii::$app->cache;
                        $cache['dirOrder_'.Yii::$app->user->id] = $order;
                    }


                    $orderSelect = [];
                    foreach($this->orderArr as $n=>$ord){
                        if($order==$ord)
                            $orderNum = $n;

                        $orderSelect[$n] = $this->orderNameArr[$n];
                    }




                    //$attrSearch = Dir::getAttrSearch($dir->attr_limit==NULL?Dir::ATTR_LIMIT_ALL:$dir->attr_limit);



                    $attrSearch = Dir::getAttrSearch(DirPermission::getTopPermission($dir_id,DirPermission::OPERATION_DOWNLOAD));

                    $listType = Yii::$app->request->get('list_type',false);

                    $listTypeNum = 0;

                    if(!in_array($listType,$this->listTypeArr)){
                        $cache = Yii::$app->cache;
                        $cacheExist = false;

                        if(isset($cache['dirListType_'. Yii::$app->user->id])){
                            if(in_array($cache['dirListType_'. Yii::$app->user->id],$this->listTypeArr)){
                                $cacheExist = true;
                            }
                        }
                        if($cacheExist){
                            $listType = $cache['dirListType_'. Yii::$app->user->id];
                        }else{
                            $listType = $this->listTypeArr[0];
                        }
                    }else{
                        $cache = Yii::$app->cache;
                        $cache['dirListType_'. Yii::$app->user->id] = $listType;
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

                    if($folder){
                        if(!DirPermission::isFileAllow($folder->dir_id,$folder->id,DirPermission::OPERATION_DOWNLOAD)){
                            ## 日志记录 ##
                            SystemLog::dirError('没有权限打开文件夹('.$folder->id.':'.File::getFileFullRoute($folder->id).')');
                            Yii::$app->response->redirect('/')->send();
                        }
                    }
                    $list = [];
                    $count = FileFrontFunc::getFilesNum($dir_id,$p_id,$search,$attrSearch);

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
                    $params['attrSearch'] = $attrSearch;
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
                    $list = CommonFunc::getByCache(Dir::className(),'getChildren',[$dir_id],'yun:dir/children');
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
                Yii::$app->response->statusCode = 404;
                return $this->render('error');
            }


        }else{
            SystemLog::dirError('打开目录参数错误: 缺少参数');
            return Yii::$app->runAction('/site/error');
        }
    }


    public function actionGetFiles(){
        $dir_id = Yii::$app->request->get('dir_id',false);
        $p_id = Yii::$app->request->get('p_id',false);
        $search = Yii::$app->request->get('search',false);
        $order = Yii::$app->request->get('order',false);
        $listType = Yii::$app->request->get('list_type',false);
        $districtCheck = Yii::$app->request->get('district_check',false);
        $industryCheck = Yii::$app->request->get('industry_check',false);

        $orderTrue = str_replace('.',' ',$order);  //排序字符串 变换
        $districtCheck = $districtCheck!=false?District::getCheckIdsTrue(explode(',',$districtCheck)):[];
        $industryCheck = $industryCheck!=false?Industry::getCheckIdsTrue(explode(',',$industryCheck)):[];
        $attrSearch = ['district'=>$districtCheck,'industry'=>$industryCheck];
        $pageSize = $this->listStylePageSize;
        if($listType=='grid')
            $pageSize = $this->gridStylePageSize;
        $count = FileFrontFunc::getFilesNum($dir_id,$p_id,$search,$attrSearch);

        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);

        $list = FileFrontFunc::getFiles($dir_id,$p_id,$pages,$orderTrue,$search,$attrSearch);

        $params['list'] = $list;
        $params['listType'] = $listType;
        $params['dir_id'] = $dir_id;
        $params['p_id'] = $p_id;

        //$params['start'] = intval($pageSize*($pages->page))+1;
        $this->layout = false;
        if($listType=='grid'){
            $viewName = '_grid_data';
        }else{
            $viewName = '_list_data';
        }
        return $this->render($viewName,$params);
    }

    public function actionSave(){
        $post = Yii::$app->request->post();

        $dir_id = isset($post['dir_id'])?$post['dir_id']:'';
        $p_id = isset($post['p_id'])?$post['p_id']:'';
        $user = Yii::$app->user->identity;
        $uid = Yii::$app->user->id;
        $flag = isset($post['flag'])?$post['flag']:'';
        $filename = isset($post['filename'])?$post['filename']:'';

        /*if($post['filetype']==0){
            $districtCheck = 10000;
            $industryCheck = 10000;
        }else{*/
            $districtCheck = isset($post['district_check'])?$post['district_check']:10000;
            $industryCheck = isset($post['industry_check'])?$post['industry_check']:10000;
        /*}*/

        /*if($districtCheck!=10000 && $districtCheck == $user->district_id
            && $industryCheck == $user->industry_id){
            //TODO
            $allowPermissionType = [DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::PERMISSION_TYPE_ATTR_LIMIT];
        }else{
            $allowPermissionType = [DirPermission::PERMISSION_TYPE_NORMAL];
        }*/

        /*
         * 判断用户有无上传权限
         * 权限分4个 1:normal  2:district 3:industry 4:district&industry
         * 1最松 都可以上传  4最紧(严格) 必须地区和行业都符合才行
         *
         * 文件属性 与用户的地区 和 行业属性都不符合时 必须拥有最高权限才能上传
         * 地区相同 如果有地区的权限就行
         * 行业相同 如果有行业的权限就行
         * 都符合 只要有 4 就行  
         */
        $allowPermissionType = [];
        $districtEqual = false; //判断选择的地区和  用户的地区是否相同
        $industryEqual = false; //判断选择的行业和  用户的行业是否相同
        if($districtCheck!=10000 && $districtCheck == $user->district_id){
            $districtEqual = true;
            $allowPermissionType[] = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT;
        }

        if($industryCheck!=10000 && $industryCheck == $user->industry_id){
            $industryEqual = true;
            $allowPermissionType[] = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY;
        }
        if($districtEqual && $industryEqual){
            $allowPermissionType[] = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY;
        }


        $allowPermissionType[] = DirPermission::PERMISSION_TYPE_NORMAL;


        if($dir_id>0 && $filename!=''){
            $allowUpload = false;
            foreach($allowPermissionType as $t){
                if(DirPermission::isDirAllow($dir_id,$t,DirPermission::OPERATION_UPLOAD)){
                    $allowUpload = true;
                }
            }

            if($allowUpload){
                $fileexist = File::find()->where(['dir_id'=>$dir_id,'p_id'=>$p_id,'filename'=>$filename])->andWhere('status < 2')->one();
                if($fileexist==false){
                    $file = new File();
                    $insert['filename'] = $filename;
                    $insert['filesize'] = isset($post['filesize'])?$post['filesize']:'';
                    $insert['filetype'] = isset($post['filetype'])?$post['filetype']:FileFrontFunc::getFileType($insert['filename']);
                    $insert['dir_id'] = $dir_id;
                    $insert['p_id'] = $p_id;
                    $insert['filename_real'] = isset($post['filename_real'])?$post['filename_real']:'';
                    $insert['user_id'] = $uid;
                    $insert['clicks'] = 0;
                    $insert['ord'] = 1;
                    $insert['status'] = 1;
                    $insert['parent_status'] = 1;
                    $insert['flag'] = $flag;  //flag ：1 公共  flag :2 个人\私人



                    //$file->insert(true,$insert);
                    $file->setAttributes($insert);

                    /*$file->filename = $post['filename'];
                    $file->filesize = $post['filesize'];
                    $file->filetype = FileFrontFunc::getFileType($post['filename']);
                    $file->dir_id = $post['dir_id'];
                    $file->p_id = $post['p_id'];
                    $file->filename_real = $post['filename_real'];
                    $file->uid = Yii::$app->user->id;
                    $file->clicks = 0;
                    $file->ord = 1;
                    $file->status = 1;
                    $file->flag = 1;*/

                    if($file->save()){
                        /*if($areaCheck!=''){
                            $areaCheckArr = explode(',',$areaCheck);
                            $areaCheckArr = array_unique($areaCheckArr);
                            foreach($areaCheckArr as $a){
                                $fileAttr = new FileAttribute();
                                $fileAttr->file_id = $file->id;
                                $fileAttr->attr_type = Attribute::TYPE_AREA;
                                $fileAttr->attr_id = $a;
                                $fileAttr->save();
                            }
                        }

                        if($businessCheck!=''){
                            $businessCheckArr = explode(',',$businessCheck);
                            $businessCheckArr = array_unique($businessCheckArr);
                            foreach($businessCheckArr as $b){
                                $fileAttr = new FileAttribute();
                                $fileAttr->file_id = $file->id;
                                $fileAttr->attr_type = Attribute::TYPE_BUSINESS;
                                $fileAttr->attr_id = $b;
                                $fileAttr->save();
                            }
                        }*/
                        if($districtCheck!=''){
                            $fileAttr = new FileAttribute();
                            $fileAttr->file_id = $file->id;
                            $fileAttr->attr_type = Attribute::TYPE_DISTRICT;
                            $fileAttr->attr_id = $districtCheck;
                            $fileAttr->save();
                        }
                        if($industryCheck!=''){
                            $fileAttr = new FileAttribute();
                            $fileAttr->file_id = $file->id;
                            $fileAttr->attr_type = Attribute::TYPE_INDUSTRY;
                            $fileAttr->attr_id = $industryCheck;
                            $fileAttr->save();
                        }

                    }


                    echo json_encode(['result'=>true]);
                }else{
                    echo json_encode(['result'=>false,'msg'=>'同名文件已存在']);
                }
            }else{
                echo json_encode(['result'=>false,'msg'=>'没有上传权限']);
            }
            //Yii::$app->response->redirect(['/dir','dir_id'=>$post['dir_id']])->send();
        }else{
            echo json_encode(['result'=>false,'msg'=>'目录参数错误或者文件名为空']);
        }

        Yii::$app->end();

    }

    public function actionDownload(){
        $id = Yii::$app->request->get('id',0);
        $preview = Yii::$app->request->get('preview',false);
        $imgUrl = Yii::$app->request->get('imgUrl',false);
        $file = File::find()->where(['id'=>$id,'status'=>1,'parent_status'=>1])->one();
        if($file){
            $dir = Dir::find()->where(['id'=>$file->dir_id,'status'=>1])->one();
            if($dir){
                if(DirPermission::isFileAllow($file->dir_id,$file->id,DirPermission::OPERATION_DOWNLOAD)){
                    $file_path = FileFrontFunc::getFilePath($file->filename_real,true);
                    if($preview!=false){
                        if(in_array($file->filetype,$this->previewTypeArr)){
                            if(in_array($file->filetype,[2,3,4,5,6])){
                                if($imgUrl!=false){
                                    echo $file_path;
                                }else{
                                    echo '<img width=100% src="'.$file_path.'" />';
                                }
                            }

                        }else{
                            echo '该文件类型暂时不支持预览<br/>'.$file_path;
                        }
                    }else{

                        $file_path = urlencode($file_path);
                        $file_path = str_replace('%3A',':',$file_path);
                        $file_path = str_replace('%2F','/',$file_path);
                        $file_path = str_replace('+','%20',$file_path);
                        $file_path = str_replace('%7E','~',$file_path);
                        /*$sssssssss2 = 'https://yun-source.songtang.net/file:%E5%B7%A5%E5%85%B7%E5%BA%94%E7%94%A8%E4%B8%AD%E5%BF%83%3E%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%3E%E9%94%80%E5%94%AE%E4%B8%9A%E5%8A%A1%E9%83%A8%E5%B7%A5%E5%85%B7%E7%AE%B1%3E%20%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%7E%E9%94%80%E5%94%AE%E4%B8%BB%E7%AE%A1%E5%9F%B9%E8%AE%AD%E6%89%8B%E5%86%8C%EF%BC%882017%E7%89%88%EF%BC%89%3Eo_1bgupuhpi1q531p32t4a3h1e1u9';

                        $file_path2 = 'https://yun-source.songtang.net/file:%E5%B7%A5%E5%85%B7%E5%BA%94%E7%94%A8%E4%B8%AD%E5%BF%83%3E%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%3E%E9%94%80%E5%94%AE%E4%B8%9A%E5%8A%A1%E9%83%A8%E5%B7%A5%E5%85%B7%E7%AE%B1%3E%20%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7~%E9%94%80%E5%94%AE%E4%B8%BB%E7%AE%A1%E5%9F%B9%E8%AE%AD%E6%89%8B%E5%86%8C%EF%BC%882017%E7%89%88%EF%BC%89%3Eo_1bgupuhpi1q531p32t4a3h1e1u9';
                        var_dump($file_path);
                        var_dump($file_path2);
                        var_dump($file_path==$file_path2);exit;*/

                        /*'file:%E5%B7%A5%E5%85%B7%E5%BA%94%E7%94%A8%E4%B8%AD%E5%BF%83%3E%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%3E%E9%94%80%E5%94%AE%E4%B8%9A%E5%8A%A1%E9%83%A8%E5%B7%A5%E5%85%B7%E7%AE%B1%3E+%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%7E%E9%94%80%E5%94%AE%E4%B8%BB%E7%AE%A1%E5%9F%B9%E8%AE%AD%E6%89%8B%E5%86%8C%EF%BC%882017%E7%89%88%EF%BC%89%3Eo_1bgupuhpi1q531p32t4a3h1e1u9';
                        'file:%E5%B7%A5%E5%85%B7%E5%BA%94%E7%94%A8%E4%B8%AD%E5%BF%83%3E%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%3E%E9%94%80%E5%94%AE%E4%B8%9A%E5%8A%A1%E9%83%A8%E5%B7%A5%E5%85%B7%E7%AE%B1%3E%20%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7~%E9%94%80%E5%94%AE%E4%B8%BB%E7%AE%A1%E5%9F%B9%E8%AE%AD%E6%89%8B%E5%86%8C%EF%BC%882017%E7%89%88%EF%BC%89%3Eo_1bgupuhpi1q531p32t4a3h1e1u9';

$file_path2 = 'https://yun-source.songtang.net/file:%E5%B7%A5%E5%85%B7%E5%BA%94%E7%94%A8%E4%B8%AD%E5%BF%83%3E%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7%3E%E9%94%80%E5%94%AE%E4%B8%9A%E5%8A%A1%E9%83%A8%E5%B7%A5%E5%85%B7%E7%AE%B1%3E%20%E9%A2%82%E5%94%90%E5%9C%B0%E4%BA%A7~%E9%94%80%E5%94%AE%E4%B8%BB%E7%AE%A1%E5%9F%B9%E8%AE%AD%E6%89%8B%E5%86%8C%EF%BC%882017%E7%89%88%EF%BC%89%3Eo_1bgupuhpi1q531p32t4a3h1e1u9';
var_dump($file_path);
var_dump($file_path==$file_path2);exit;
                        $filesize = strlen(file_get_contents($file_path));
                        var_dump($filesize);exit;
                        ob_clean();
                        var_dump($file_path);exit;
                        $s = @readfile($file_path);
                        var_dump($s);*/

                        /*header("Content-type: text/html; charset=utf-8");
                        $file_path = utf8_encode($file_path);
                        $filesize = strlen(file_get_contents($file_path));
                        var_dump($filesize);exit;*/
//var_dump(utf8_encode($file_path));exit;
                        FileFrontFunc::insertDownloadRecord($file,Yii::$app->user->id);

                        //Header("HTTP/1.1 303 See Other");
                        /*Header("Location: $file_path");
            var_dump($file_path);exit;
                        Yii::$app->end();*/
                        header("Content-type: application/octet-stream;");
                        header("Accept-Ranges: bytes");
                        //Header("Accept-Length: ".filesize($file_path));
                        $filename = $file->filename;
                        $ua = $_SERVER["HTTP_USER_AGENT"];
                        $encoded_filename = urlencode($filename);

                        $encoded_filename = str_replace("+", "%20", $encoded_filename);
                        //header('Content-Type: application/octet-stream');
                        if(preg_match("/MSIE/", $ua)){
                            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                        }/*elseif(preg_match("/Firefox/", $ua)){
                    header('Content-Disposition: attachment; filename*="utf8"' . $filename . '"');
                }*/else{
                            header('Content-Disposition: attachment; filename="' . $filename . '"');
                        }
                        //header("X-Accel-Redirect: $file_path");
                        //Header("Content-Disposition: attachment; filename=" . $file->filename);
                        //echo $result;
                        //echo file_get_contents($file_path);
                        //$file_path = utf8_encode($file_path);

                        $filesize = strlen(file_get_contents($file_path));
                        header("Content-Length: ". $filesize);
                        @readfile($file_path);
                    }

                }else{
                    echo 'download/ no permission';
                }
            }else{
                echo 'download/ no dir';
            }
        }else{
            echo 'download/ no file';
        }
        Yii::$app->end();
    }

    public function actionDelete(){
        $id = Yii::$app->request->get('id');

        $file = File::find()->where(['id'=>$id,'status'=>1])
        if(!in_array(Yii::$app->user->id,[10000,10003,10004,10005])){
            $file = $file->addWhere(['user_id'=>Yii::$app->user->id]);
        }

        $file = $file->one();
        if($file){
            $file->status = 0;
            if($file->save()){
                FileFrontFunc::updateParentStatus2($file->id);
                Yii::$app->response->redirect(Yii::$app->request->referrer)->send();
            }
        }else{
            echo '文件不存在';
        }
    }

    public function actionMoveFile(){
        $result = false;
        $error_message='';
        $new_dir_id = Yii::$app->request->post('new_dir_id');
        $new_p_id = Yii::$app->request->post('new_p_id');
        $file_ids = Yii::$app->request->post('file_ids');

        $permissionType = $this->getPermissionNeeded($file_ids);

        $permission = false;
        if($new_p_id>0){ //移至文件夹
            $parent_dir = File::find()->where(['id'=>$new_p_id,'filetype'=>0])->one();
            if($parent_dir){

                $permission =  DirPermission::isDirAllow($parent_dir->dir_id,$permissionType,DirPermission::OPERATION_UPLOAD);
//              $permission =  PermissionFunc::checkFileUploadPermission($this->user->position_id,$parent_dir->dir_id,1);
            }
        }else{//移至目录
            $parent_dir = Dir::find()->where(['id'=>$new_dir_id,'is_leaf'=>1])->one();
            if($parent_dir) {
                $permission =  DirPermission::isDirAllow($parent_dir->id,$permissionType,DirPermission::OPERATION_UPLOAD);

                //$permission = PermissionFunc::checkFileUploadPermission($this->user->position_id, $parent_dir->id, 1);
            }
        }
        //检查目录是否存在 目录dir 是否有上传权限
        if($parent_dir){
            if($permission){
                if(is_array($file_ids)){
                    //检查文件名是否重复
                    $filenameExist = false;
                    $files = File::find()->where(['in','id',$file_ids])->all();
                    //files2 代表目标路径下的文件
                    if($new_p_id>0){
                        $files2 = File::find()->where(['p_id'=>$new_p_id])->andWhere('status < 2')->all();
                    }else{
                        $files2 = File::find()->where(['dir_id'=>$new_dir_id,'p_id'=>0])->andWhere('status < 2')->all();
                    }
                    $files2Name = []; //取出所有文件名
                    foreach($files2 as $f2){
                        $files2Name[] = $f2->filename;
                    }
                    //逐一比对是否存在
                    $filenameRepeat = [];
                    foreach($files as $f){
                        if(in_array($f->filename,$files2Name)){
                            $filenameExist = true;
                            $filenameRepeat[] = $f->filename;
                        }
                    }
                    if($filenameExist){
                        $error_message = '有文件重名 ('.implode($filenameRepeat,'|').')';
                    }else{
                        if($new_p_id>0) {
                            foreach ($files as $f) {
                                $f->dir_id = $parent_dir->dir_id;
                                $f->p_id = $parent_dir->id;
                                $f->save();
                            }
                        }else{
                            foreach ($files as $f) {
                                $f->dir_id = $parent_dir->id;
                                $f->p_id = 0;
                                $f->save();
                            }
                        }
                        $result = true;
                    }
                }else{
                    $error_message = '请选择要移动的文件！';
                }
            }else{
                $error_message = '目录没有正确的上传权限';
            }
        }else{
            $error_message = '不是底层目录或目标目录不存在';
        }
        $arr = [];
        $arr['error'] = $error_message;
        $arr['result'] = $result;
        echo json_encode($arr);
        Yii::$app->end();
    }


    //移动文件时，根据所选文件 获得所需要的最高权限类型
    public function getPermissionNeeded($file_ids){
        $files = $this->getFiles(File::find()->where(['id'=>$file_ids])->all());
        $districtArr = [];
        $industryArr = [];
        foreach($files as $f){
            if($f->getDistrictAttr()>10000){
                $districtArr[] = $f->getDistrictAttr();
            }
            if($f->getIndustryAttr()>10000){
                $industryArr[] = $f->getIndustryAttr();
            }
        }
        $districtArr = array_unique($districtArr);
        $industryArr = array_unique($industryArr);

        $districtMatch = count($districtArr)==0 || (count($districtArr)==1 && in_array($this->user->district_id,$districtArr))?true:false;
        $industryMatch = count($districtArr)==0 || (count($industryArr)==1 && in_array($this->user->industry_id,$industryArr))?true:false;

        if($districtMatch){
            if($industryMatch){
                $permissionType = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY;
            }else{
                $permissionType = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT;
            }
        }else{
            if($industryMatch){
                $permissionType = DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY;
            }else{
                $permissionType = DirPermission::PERMISSION_TYPE_NORMAL;
            }
        }

        return $permissionType;

    }

    public function getFiles($files){
        $return = $files;
        foreach($files as $f){
            if($f->filetype ==0){
                $return = array_merge($return , $this->getFiles(File::find()->where(['p_id'=>$f->id])->all()));
            }
        }
        return $return;
    }

    public function actionAjaxMoveSelectDir(){
        $p_id = Yii::$app->request->get('p_id',0);
        $p_id3 = Yii::$app->request->get('p_id3',0);

        $this->layout = false;

        $parents = Dir::getParents($p_id);


        $dirList = [];
        $selected = [];
        if(!empty($parents)){
            $p_id2 = 0;
            foreach($parents as $p){
                $dirList[] =Dir::getDropDownList($p_id2,true,false,1);
                $selected[] = $p->id;
                $p_id2 = $p->id;
            }
            $dirList[] = Dir::getDropDownList($p_id2,true,false,1);
        }else{
            $dirList[] = Dir::getDropDownList($p_id,true,false,1);
        }


        $dir2List = [];
        $selected2 = [];
        $dir = Dir::find()->where(['id'=>$p_id])->one();
        if($dir && $dir->is_leaf==1){
            $parents2 = FileFunc::getParents($dir->id,$p_id3);
            if(!empty($parents2)){
                $p_id3_2 = 0;
                foreach($parents2 as $p){
                    $dir2List[] =FileFunc::getDropDownList($dir->id,$p_id3_2,false,1);
                    $selected2[] = $p->id;
                    $p_id3_2 = $p->id;
                }
                $dir2List[] = FileFunc::getDropDownList($dir->id,$p_id3,false,1);
            }else{
                $dir2List[] = FileFunc::getDropDownList($dir->id,$p_id3,false,1);

            }
        }

        $params['dirList'] = $dirList;
        $params['selected'] = $selected;
        $params['dir2List'] = $dir2List;
        $params['selected2'] = $selected2;

        return $this->render('modal/move_dir',$params);
    }

    public function actionEditFilename(){
        $result = false;
        $error_message='';
        $file_id = Yii::$app->request->post('file_id');
        $filename_new = Yii::$app->request->post('filename_new');
        $filename_new = trim($filename_new);
        $file = File::find()->where(['id'=>$file_id,'status'=>1/*,'user_id'=>Yii::$app->user->id*/])->one();
        if($file && $filename_new!=''){
            if($file->p_id>0){
                $exist = File::find()->where(['filename'=>$filename_new,'p_id'=>$file->p_id])->andWhere(['<>','filename',$file->filename])->all();
            }else{
                $exist = File::find()->where(['filename'=>$filename_new,'dir_id'=>$file->dir_id])->andWhere(['<>','filename',$file->filename])->all();
            }
            if(!empty($exist)){
                $error_message = '文件名重名';
            }else{
                $file->filename = $filename_new;
                if($file->save()){
                    $disAttr = FileAttribute::find()->where(['file_id'=>$file_id,'attr_type'=>Attribute::TYPE_DISTRICT])->one();
                    if($disAttr){
                        $disAttr->attr_id = Yii::$app->request->post('district_id');
                        $disAttr->save();
                    }

                    $indAttr = FileAttribute::find()->where(['file_id'=>$file_id,'attr_type'=>Attribute::TYPE_INDUSTRY])->one();
                    if($indAttr){
                        $indAttr->attr_id = Yii::$app->request->post('industry_id');
                        $indAttr->save();
                    }

                    $result = true;
                }
            }


        }else{
            $error_message = '文件不存在/没有操作权限';
        }
        $arr = [];
        $arr['error'] = $error_message;
        $arr['result'] = $result;
        echo json_encode($arr);
        Yii::$app->end();
    }

   /* public function actionGetUptoken(){
        $up=new QiniuUpload(Yii::$app->params['qiniu-bucket']);
        $saveKey = Yii::$app->request->get('saveKey','');
        $upToken=$up->createtoken($saveKey);
        echo json_encode(['uptoken'=>$upToken]);exit;
    }*/

    public function actionGetFilenameList(){
        $post = Yii::$app->request->post();

        $dir_id = isset($post['dir_id'])?$post['dir_id']:0;
        $p_id = isset($post['p_id'])?$post['p_id']:0;

        /*$dir_id = 7;
        $p_id = 131;*/
        $files = File::find()->select('filename')->where(['dir_id'=>$dir_id,'p_id'=>$p_id])->andWhere('status < 2 ')->all();
        $arr = [];
        foreach($files as $f){
            $arr[] = $f->filename;
        }
        echo json_encode($arr);

    }

}
