<?php
namespace yun\components;

use yun\models\Dir;
use yun\models\File;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use yii;

class DirFunc extends Component {






    /*
     * 函数getIsLeaf ,实现根据is_leaf(dir表 is_leaf字段) 判断是不是底层文件夹
     *
     * @param is_leaf  (1, 0, null) 是否为叶子的标志为
     * return string/null
     */
    public static function getIsLeaf($is_leaf=NULL){
        if($is_leaf==1){
            return '<span class="label label-info">底层目录</span>';
        }elseif($is_leaf==0){
            return '<span class="label label-default">目录</span>';
        }else{
            return 'N/A';
        }
    }
















    /*
     * 函数getChildrens ,实现根据 当前p_id 递归获取全部子层级 id
     *
     * @param integer p_id
     * return array
     */
    public static function getChildrens($p_id=0){
        $arr = [];
        $children = Dir::find()->where(['p_id'=>$p_id,'status'=>1])->all();
        if(!empty($children)){
            foreach($children as $child){
                $arr[] = $child->id;
                $arr2 = self::getChildrens($child->id);
                $arr = BaseArrayHelper::merge($arr,$arr2);
            }
        }
        ksort($arr);
        return $arr;
    }

    /*
    * 函数 handleIsLastAndIsLeaf , 修改了职位信息后，批量更新is_last,is_leaf字段
    *
    * @param integer p_id  父id
    * no return
    */
    public static function handleIsLastAndIsLeaf($p_id=0){
        $where['p_id']=$p_id;
        $where['status']=1;
        $dirs = Dir::find()->where($where)->orderBy('ord Desc,id DESC')->all();
        if(!empty($dirs)){
            Dir::updateAll(['is_last'=>0],['p_id'=>$p_id]);
            $count = count($dirs);
            $i = 0;
            foreach($dirs as $d){
                $i++;
                self::handleIsLastAndIsLeaf($d->id);
                if($i==$count){
                    Dir::updateAll(['is_last'=>1],['id'=>$d->id]);
                }
            }
        }else{
            //Dir::updateAll(['is_leaf'=>1],['id'=>$p_id]);
        }
    }

    /*
    * 函数 getIdByRoute , 通过所给路径获取 dir_id
    *
    * @param integer route  目录路径
    * @param integer p_id  父id
    * @return dir_id
    */
    public static function getIdByRoute($route,$p_id=false){
        $returnId = false;
        $routeArr = explode('/',$route);
        if($p_id===false){
            $p_id = 0;
            foreach($routeArr as $r){
                $p_id = self::getIdByRoute($r,$p_id);
                if($p_id===false)
                    break;
            }
            $returnId = $p_id;
        }else{
            $dir = Dir::find()->where(['name'=>$route,'p_id'=>$p_id])->one();
            if($dir){
                $returnId = $dir->id;
            }
        }
        return $returnId;
    }

    /*
    * 函数 getIdByRoute , 通过所给路径获取 dir_id
    *
    * @param integer route  目录路径 别名 alias
    * @param integer p_id  父id
    * @return dir_id
    */
    public static function getIdByAliasRoute($route,$p_id=false){
        $returnId = false;
        $routeArr = explode('/',$route);
        if($p_id===false){
            $p_id = 0;
            foreach($routeArr as $r){
                $p_id = self::getIdByAliasRoute($r,$p_id);
                if($p_id===false)
                    break;
            }
            $returnId = $p_id;
        }else{
            $dir = Dir::find()->where(['alias'=>$route,'p_id'=>$p_id])->one();
            if($dir){
                $returnId = $dir->id;
            }
        }
        return $returnId;
    }
}