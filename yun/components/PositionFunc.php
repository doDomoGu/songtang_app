<?php
namespace yun\components;

//use app\models\Position;
use ucenter\models\Position;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use yii;

class PositionFunc extends Component {
    /*
     * 函数getFullRoute ,实现根据position_id(Position表 id字段)获取完整的部门/职位的中文路径
     *
     * @param position_id 位置id
     * @param separator 分隔符 (默认 '>' )
     * return string/null
     */
    public static function getFullRoute($position_id,$separator=' > '){
        $position = Position::find()->where(['id'=>$position_id])->one();
        if($position!==NULL){
            $str = '';
            $str.= self::getFullRoute($position->p_id,$separator);
            if($str!=null){
                $str.= $separator;
            }
            $str.= $position->name;
            return $str;
        }else{
            return null;
        }
    }


    /*
     * 函数getRouteArr , 根据职位id 获取 1地方 2业态 3部门（多层级） 4职位
     *
     * @param $position_id 职位id
     * @param $separator 分隔符
     * return array
     */
    public static function getRouteArr($position_id,$separator=' > '){
        $arr = [
            1 => '--',
            2 => '--',
            3 => '--',
            4 => '--'
        ];
        $position = Position::find()->where(['id'=>$position_id,'is_leaf'=>1])->one();
        if($position){
            $temp = [];
            $parents = self::getParents($position_id);
            for($i=1;$i<=count($parents);$i++){
                if(isset($parents[$i])){
                    $temp[$i-1] = $parents[$i]->name;
                }
            }




            $cityArr = [
                '上海','苏州','无锡','南京','合肥','呼和浩特'
            ];
            //如果第二个是城市 ，
            if(count($temp)>1 && in_array($temp[1],$cityArr)){
                array_shift($temp);
                asort($temp);
                $count = count($temp);
                if($count==1){
                    $arr[4] = $temp[0];
                }elseif($count==2){
                    $arr[3] = $temp[0];
                    $arr[4] = $temp[1];
                }elseif($count==3){
                    $arr[2] = $temp[0];
                    $arr[3] = $temp[1];
                    $arr[4] = $temp[2];
                }elseif($count==4){
                    $arr[1] = $temp[0];
                    $arr[2] = $temp[1];
                    $arr[3] = $temp[2];
                    $arr[4] = $temp[3];
                }elseif($count>4){
                    $arr[1] = $temp[0];
                    $arr[2] = $temp[1];
                    $arr[3] = $temp[2];
                    for($j=3;$j<$count-1;$j++)
                        $arr[3] .= $separator . $temp[$j];
                    $arr[4] = $temp[$count-1];
                }
            }elseif(count($temp)==1){
                $arr[4] = $temp[0];
            }else{
                //各个中心
                $arr[2] = $temp[0];
                $arr[3] = $temp[1];
                $arr[4] = $temp[2];
            }


            /*$count = count($temp);

            if($count==1){
                $arr[4] = $temp[1];
            }elseif($count==2){
                $arr[3] = $temp[1];
                $arr[4] = $temp[2];
            }elseif($count==3){
                $arr[2] = $temp[1];
                $arr[3] = $temp[2];
                $arr[4] = $temp[3];
            }elseif($count==4){
                $arr[1] = $temp[1];
                $arr[2] = $temp[2];
                $arr[3] = $temp[3];
                $arr[4] = $temp[4];
            }elseif($count>4){
                $arr[1] = $temp[1];
                $arr[2] = $temp[2];
                $arr[3] = $temp[3];
                for($j=4;$j<$count;$j++)
                    $arr[3] .= $separator . $temp[$j];
                $arr[4] = $temp[$count];
            }*/
        }
        return $arr;
    }


    /*
     * 函数getDropDownList ,实现根据is_leaf(Position表 is_leaf字段) 判断是部门还是职位
     *
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean includeSelf 是否包含自己本身的标志位 (默认false)
     * @param integer level  显示层级数限制 (默认false,不限制)
     * return string/null
     */
    public static function getDropDownList($p_id=0,$showLeaf=true,$includeSelf=false,$level=false){
        $arr = array();

        $list = self::getListArr($p_id,$showLeaf,false,$includeSelf,$level);
        if(!empty($list)){
            foreach($list as $l){
                $prefix = '';
                if($l->p_id>0){
                    for($i=0;$i<$l->level;$i++){
                        $prefix .='&emsp;';
                    }
                    if($l->is_last>0){
                        $prefix .='└';
                    }else{
                        $prefix .='├';
                    }
                }
                $arr[$l->id] = $prefix.$l->name;
            }
        }
        return $arr;
    }


    /*
     * 函数getDropDownList ,实现根据is_leaf(Position表 is_leaf字段) 判断是部门还是职位
     *
     * @param integer p_id 父id (默认 0 )
     * @param boolean showLeaf 是否显示叶子层级的标志位 (默认true)
     * @param boolean includeSelf 是否包含自己本身的标志位 (默认false)
     * @param integer level  显示层级数限制 (默认false,不限制)
     * return array
     */
    public static function getListArr($p_id=0,$showLeaf=true,$showTree=false,$includeSelf=false,$level=false){
        $arr = [];
        $level = $level===false?false:intval($level);
        $position = NULL;
        if($p_id>0){
            //根据p_id(父id)查找对应父对象
            $position = Position::find()->where(['id'=>$p_id])->one();
            if($position==NULL || $position->status==0){ //不存在或者状态禁用则返回空数组
                return [];
            }else if($includeSelf===true){ //将自己本身添加至数组
                $arr[$position->id]= $position;
            }
        }

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
                        $children = self::getListArr($l->id,$showLeaf,$showTree,false,$nlevel);
                        $childrenIds = array();
                        if(!empty($children)){
                            foreach($children as $child){
                                $arr[$child->id] = $child;
                                $childrenIds[]=$child->id;
                            }
                        }
                        $arr[$l->id]->childrenIds = $childrenIds;
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
    public static function getChildren($p_id,$showLeaf=true,$status=1,$orderBy='ord DESC,id DESC'){
        if($p_id === false){
            return [];
        }else{
            $where['p_id'] = $p_id;
            $where['status'] = $status;
            if($showLeaf==false)
                $where['is_leaf'] = 0;
            return Position::find()->where($where)->orderBy($orderBy)->all();
        }

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
            $pos = Position::find()->where(['id'=>$p_id])->one();
            if($pos==NULL || $pos->status==0){ //不存在或者状态禁用则返回空数组
                return [];
            }else if($includeSelf===true){ //将自己本身添加至数组
                $arr[$pos->id]= $dir;
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

    public static function getAllChildrenIds($p_id){
        $arr = [];
        $children = self::getChildren($p_id);
        if($children && !empty($children)){
            foreach($children as $c){
                $arr[] = $c->id;
                $children2 = self::getAllChildrenIds($c->id);
                $arr = ArrayHelper::merge($arr,$children2);
            }
        }
        return $arr;
    }

    public static function getAllLeafChildrenIds($p_id){
        $arr = [];
        $curPos = Position::find()->where(['id'=>$p_id])->one();
        if($curPos){
            if($curPos->is_leaf){
                $arr[] = $curPos->id;
            }else{
                $children = self::getChildren($p_id);
                if($children && !empty($children)){
                    foreach($children as $c){
                        if($c->is_leaf==1){
                            $arr[] = $c->id;
                        }else{
                            $children2 = self::getAllLeafChildrenIds($c->id);
                            $arr = ArrayHelper::merge($arr,$children2);
                        }
                    }
                }
            }
        }

        return $arr;
    }

    /*
     * 函数getParents ,实现根据 当前position_id 递归获取全部父层级 id
     *
     * @param integer position_id
     * return array
     */
    public static function getParents($position_id){
        $arr = [];
        $curPos = Position::find()->where(['id'=>$position_id,'status'=>1])->one();
        if($curPos){
            $arr[$curPos->level] = $curPos;
            $arr2 = self::getParents($curPos->p_id);
            $arr = BaseArrayHelper::merge($arr,$arr2);
        }
        ksort($arr);
        return $arr;
    }

    /*
    * 函数 handleIsLastAndIsLeaf , 修改了职位信息后，批量更新is_last，is_leaf字段
    *
    * @param integer p_id  父id
    * no return
    */
    public static function handleIsLastAndIsLeaf($p_id=0){
        $where['p_id']=$p_id;
        $where['status']=1;
        $positions = Position::find()->where($where)->orderBy('ord Desc,id DESC')->all();
        if(!empty($positions)){
            Position::updateAll(['is_last'=>0],['p_id'=>$p_id]);
            $count = count($positions);
            $i = 0;
            foreach($positions as $p){
                $i++;
                self::handleIsLastAndIsLeaf($p->id);
                if($i==$count){
                    Position::updateAll(['is_last'=>1],['id'=>$p->id]);
                }
            }
        }else{
            //Position::updateAll(['is_leaf'=>1],['id'=>$p_id]);
        }
    }

    public static function getAdminName($adminId){
        $adminId = intval($adminId);
        switch ($adminId){
            case 0:
                $name ='否';break;
            case 1:
                $name ='超级管理员';break;
            case 2:
                $name ='普通管理员';break;
            default:
                $name = 'N/A';
        }
        return $name;
    }

    public static function getIdByAlias($alias,$p_id=false){
        $returnId = false;
        $aliasArr = explode('/',$alias);
        //if(count($aliasArr)>1){
        if($p_id===false){
            $p_id = 0;
            foreach($aliasArr as $a){
                $p_id = self::getIdByAlias($a,$p_id);
                if($p_id===false)
                    break;
            }
            $returnId = $p_id;
        }else{
            $pos = Position::find()->where(['alias'=>$alias,'p_id'=>$p_id])->one();
            if($pos){
                $returnId = $pos->id;
            }
        }
        return $returnId;
    }

    public static function getIdByName($name,$p_id=false){
        $returnId = false;
        $nameArr = explode('-',$name);
        //if(count($aliasArr)>1){
        if($p_id===false){
            $p_id = 0;
            foreach($nameArr as $n){
                $p_id = self::getIdByName($n,$p_id);
                if($p_id===false)
                    break;
            }
            $returnId = $p_id;
        }else{
            $pos = Position::find()->where(['name'=>$name,'p_id'=>$p_id])->one();
            if($pos){
                $returnId = $pos->id;
            }
        }
        return $returnId;
    }

    //更新职位的full_alias 字段 全部
    public static function updateAllFullAlias(){
        $db = yii::$app->db;
        $command  = $db->createCommand('SELECT id FROM `position`');
        $result = $command->queryAll();
        foreach($result as $r){
            self::updateFullAlias($r['id']);
        }
        echo 'updateAllFullAlias ok';
        exit;
    }


    //更新职位的full_alias 字段 （单个）
    public static function updateFullAlias($position_id){
        $parents = self::getParents($position_id);
        $fullAlias = '';
        $fullAliasTmp = [];
        if(!empty($parents)){
            foreach($parents as $p){
                $fullAliasTmp[] = $p->alias;
            }
            $fullAlias = implode('/',$fullAliasTmp);
        }
        Position::updateAll(['full_alias'=>$fullAlias],'id = :id',[':id'=>$position_id]);

       // echo '<br/><br/>-----------<br/><br/>';
    }

    public static function checkAlias(){
        $db = yii::$app->db;
        $command  = $db->createCommand('SELECT count(id) as ct,id,alias,p_id FROM `position` group by alias , p_id HAVING ct>1 ');
        $result = $command->queryAll();
        foreach($result as $r){
            var_dump($r);
            echo '<br/><Br/>';
        }
        echo 'end ';exit;
    }


    /*
     * 树状图  测试用
     */
    public static function getTreeData(){
        $treeData = null;
        $cache = Yii::$app->cache;
        $cacheExist = true;
        if(isset($cache['posTreeData'])){
            $treeData = $cache['posTreeData'];
        }else{
            $cacheExist = false;
        }
        if($cacheExist == false){

                $arr = self::getChildrenArr(0,true,false,false);

                $treeData .=self::createTreeJson($arr,0,1);


                //$data = '[{ name:"父节点1 - 展开", open:true,isParent:true}]';

            //$treeData = DirFrontFunc::getTreeData($dir_id);


            $cache['posTreeData']=$treeData;
        }
        return $treeData;
    }

    public static function createTreeJson($arr,$dir_id,$level){
        $data = null;
        $i=1;
        $level2 = $level;
        if(!empty($arr)){
            $data .= '[';
            foreach($arr as $l){
                $data.='{';
                //有url
                //$data.="name:'".$l->name."',url:'/dir?dir_id=".$l->id."',target:'_self'";
                //无url
                $data.="name:'".$l->name."'";
                if($l->id == $dir_id){
                    $data.=",font:{'background-color':'black', 'color':'white'}";
                }else if($level<2){
                    $data.=',open:true';
                }
                if(!empty($l->childrenList)){
                    $level2++;
                    $data.=',children: '.self::createTreeJson($l->childrenList,$dir_id,$level2);
                }


                $data.='}';
                if($i<count($arr)){
                    $data.=',';
                }
                $i++;
            }
            $data .= ']';
        }
        return $data;
    }
}