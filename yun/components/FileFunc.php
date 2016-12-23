<?php
namespace yun\components;

use yun\models\Dir;
use yun\models\File;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use yii;

class FileFunc extends Component {
    Const ORDER_TYPE_1 = 'ord Desc,id Desc';
    /*
     * 函数getDropDownList ,实现根据is_leaf(Position表 is_leaf字段) 判断是部门还是职位
     *
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean includeSelf 是否包含自己本身的标志位 (默认false)
     * @param integer level  显示层级数限制 (默认false,不限制)
     * return string/null
     */

    //public function getDropDownListOne($pid,$type,$showLeaf,$includeSelf=false){

    public static function getDropDownList($dir_id,$p_id=0,$includeSelf=false,$level=false){
        $arr = [];

        $list = self::getListArr($dir_id,$p_id,false,$includeSelf,$level);
        if(!empty($list)){
            foreach($list as $l){
                $prefix = '';
                if($l->p_id>0){
                    /*for($i=0;$i<$l->level;$i++){
                        $prefix .='&emsp;';
                    }
                    if($l->is_last>0){
                        $prefix .='└';
                    }else{
                        $prefix .='├';
                    }*/
                }
                $arr[$l->id] = $prefix.$l->filename;
            }
        }
        return $arr;
    }


    /*
     * 函数getDropDownList ,实现根据is_leaf(Dir表 is_leaf字段) 底层
     *
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean includeSelf 是否包含自己本身的标志位 (默认false)
     * @param integer level  显示层级数限制 (默认false,不限制)
     * return array
     */

    public static function getListArr($dir_id,$p_id=0,$showTree=false,$includeSelf=false,$level=false){
        $arr = [];
        $file_dir = NULL;
        $selfChildrenIds = [];
        $dir = Dir::find()->where(['id'=>$dir_id,'is_leaf'=>1,'status'=>1])->one();
        if($dir){
            if($p_id>0){
                //根据p_id(父id)查找对应父对象
                $file_dir = File::find()->where(['id'=>$p_id,'filetype'=>0])->one();
                if($file_dir==NULL || $file_dir->status==0){ //不存在或者状态禁用则返回空数组
                    return [];
                }else if($includeSelf===true){  //将自己本身添加至数组
                    $arr[$file_dir->id]= $file_dir;
                }
            }/*else{
                $file_dir = File::find()->where(['dir_id'=>$p_id,'filetype'=>0])->one();
            }*/

            $level = $level===false?false:intval($level);
            if($level>0 || $level===false){  //level正整数 或者 false不限制
                $list = self::getChildren($dir_id,$p_id);

                if(!empty($list)){
                    $nlevel = $level===false?false: intval($level - 1);
                    foreach($list as $l){
                        $arr[$l->id] = $l;
                        if($showTree){
                            $prefix = '';
                            /*if($l->level>1){
                                for($i=1;$i<$l->level;$i++){
                                    $prefix.='&emsp;';
                                }
                                if($l->is_last>0){
                                    $prefix.='└─ ';
                                } else{
                                    $prefix.='├─ ';
                                }
                            }*/
                            $arr[$l->id]->filename = $prefix.$l->filename;
                        }

                        if($nlevel === false || $nlevel > 0){
                            $children = self::getListArr($dir_id,$l->id,$showTree,false,$nlevel);
                            $childrenIds = array();
                            if(!empty($children)){
                                foreach($children as $child){
                                    $arr[$child->id] = $child;
                                    $childrenIds[]=$child->id;
                                }
                            }
                            $arr[$l->id]->childrenIds = $childrenIds;
                            if($includeSelf===true){
                                $selfChildrenIds = ArrayHelper::merge($selfChildrenIds,$childrenIds);
                            }
                        }
                        if($includeSelf===true){
                            $selfChildrenIds = ArrayHelper::merge($selfChildrenIds,[$l->id]);
                        }
                    }
                }
            }

            if($file_dir && $includeSelf===true){
                $arr[$file_dir->id]->childrenIds = $selfChildrenIds;
            }
        }


        return $arr;
    }



    /*
     * 函数getDropDownArr ,实现根据is_leaf(Dir表 is_leaf字段) 底层
     *
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean includeSelf 是否包含自己本身的标志位 (默认false)
     * @param integer level  显示层级数限制 (默认false,不限制)
     * return array  递归
     */

    public static function getChildrenArr($p_id=0,$showLeaf=true,$showTree=false,$includeSelf=false,$level=false){
        $arr = [];
        $dir = NULL;
        if($p_id>0){
            //根据p_id(父id)查找对应父对象
            $dir = self::getOneByCache($p_id);
            if($dir==NULL || $dir->status==0){ //不存在或者状态禁用则返回空数组
                return [];
            }else if($includeSelf===true){ //将自己本身添加至数组
                $arr[$dir->id]= $dir;
            }
        }

        $level = $level===false?false:intval($level);
        if($level>0 || $level===false){  //level正整数 或者 false不限制
            $list = self::getChildren($p_id,$showLeaf);

            if(!empty($list)){
                $nlevel = $level===false?false: intval($level - 1);
                foreach($list as $l){
                    $arr[$l->id] = $l;
                    if($showTree){
                        $prefix = '';
                        if($l->level>1){
                            for($i=1;$i<$l->level;$i++){
                                $prefix.='&emsp;';
                            }
                            if($l->is_last>0){
                                $prefix.='└─ ';
                            } else{
                                $prefix.='├─ ';
                            }
                        }
                        $arr[$l->id]->name = $prefix.$l->name;
                    }

                    if($nlevel === false || $nlevel > 0){
                        $children = self::getChildrenArr($l->id,$showLeaf,$showTree,false,$nlevel);
                        $childrenIds = [];
                        $childrenList = [];
                        if(!empty($children)){
                            foreach($children as $child){
                                //$arr[$child->id] = $child;
                                $childrenIds[]=$child->id;
                                $childrenList[]=$child;
                            }
                        }
                        $arr[$l->id]->childrenIds = $childrenIds;
                        $arr[$l->id]->childrenList = $childrenList;
                    }

                }
            }
        }
        return $arr;
    }

    /*
     * 函数getChildren ,实现根据 p_id 获取子层级 （单层）
     *
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean status 状态 (默认1)
     * @param string orderBy  排序方法
     * return array
     */
    public static function getChildren($dir_id,$p_id,$status=1,$orderBy=1,$limit=false){
        $where['dir_id'] = $dir_id;
        $where['p_id'] = $p_id;
        $where['filetype'] = 0;
        $where['status'] = $status;


        $orderByStr = self::ORDER_TYPE_1;
        //其他排序类型赋值；
        /*if($orderBy==1){
            $orderByStr = self::ORDER_TYPE_1;
        }*/
        $result = [];
        $list = File::find()->where($where)->orderBy($orderByStr)->limit($limit)->asArray()->all();
        if(!empty($list)){
            foreach($list as $l){
                $result[] = (object)$l;
            }
        }
        return $result;
    }


    /*
     * 函数getChildrenByCache ,实现根据 p_id 获取子层级 （单层）
     *                       读取数据缓存，有则直接返回缓存内容，没有则获取模型数据，并添加至缓存
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean status 状态 (默认1)
     * @param string orderBy  排序方法
     * return array
     */
    public static function getChildrenByCache($p_id,$showLeaf=true,$status=1,$orderBy=1,$limit=false){
        $cache = yii::$app->cache;
        $cacheExist = true;
        $dirChildrenDataId = [];
        $dirChildrenData = NULL;
        $key = $p_id.'_'.($showLeaf==true?'1':'0').'_'.($status==1?'1':'0').'_'.$orderBy.'_'.($limit==false?'f':$limit);
//var_dump($key);exit;
        if(isset($cache['dirChildrenDataId'])){
            $dirChildrenDataId = $cache['dirChildrenDataId'];
            if(isset($dirChildrenDataId[$key])){
                $dirChildrenData = $cache['dirChildrenData_'.$key];
            }else{
                $cacheExist = false;
            }
        }else{
            $cacheExist = false;
        }
        if($cacheExist == false){
            $dirChildrenData = self::getChildren($p_id,$showLeaf,$status,$orderBy,$limit);

            $cache['dirChildrenDataId'] = \yii\helpers\ArrayHelper::merge($dirChildrenDataId,[$key=>1]);

            $cache['dirChildrenData_'.$key]=$dirChildrenData;
        }
        return $dirChildrenData;
    }


    /*
     * 函数getOneByCache ,实现根据 id 读取数据缓存，有则直接返回缓存内容，没有则获取模型数据，并添加至缓存
     *
     * @param integer id  dir:id
     * return DirModel
     */
    public static function getOneByCache($id){
        $cache = yii::$app->cache;
        $cacheExist = true;
        $dirDataId = [];
        $dirData = NULL;
        if(isset($cache['dirDataId'])){
            $dirDataId = $cache['dirDataId'];
            if(isset($dirDataId[$id])){
                $dirData = $cache['dirData_'.$id];
            }else{
                $cacheExist = false;
            }
        }else{
            $cacheExist = false;
        }
        if($cacheExist == false){
            $dirData = (object)(Dir::find()->where(['id'=>$id])->one()->toArray());

            $cache['dirDataId'] = \yii\helpers\ArrayHelper::merge($dirDataId,[$id=>1]);

            $cache['dirData_'.$id]=$dirData;
        }
        return $dirData;
    }

    /*
     * 函数getParents ,实现根据 当前dir_id 递归获取全部父层级 id
     *
     * @param integer dir_id
     * return array
     */
    public static function getParents($dir_id,$p_id){
        $arr = [];
        $curDir = File::find()->where(['id'=>$p_id,'dir_id'=>$dir_id,'status'=>1])->one();
        if($curDir){
            $arr[] = $curDir;
            $arr2 = self::getParents($dir_id,$curDir->p_id);
            $arr = BaseArrayHelper::merge($arr,$arr2);
        }
        $arr = array_reverse($arr);
        ksort($arr);
        return $arr;
    }
}