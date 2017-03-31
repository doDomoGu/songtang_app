<?php
namespace yun\models;

use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\UserAppAuth;
use yii\helpers\ArrayHelper;
use yun\models\DirPermission;
use Yii;

class Dir extends \yii\db\ActiveRecord
{
    Const ORDER_TYPE_1 = 'ord asc,id Desc';
/*
    const ATTR_LIMIT_ALL   = 1;  //全员
    const ATTR_LIMIT_DISTRICT  = 2;  //文件(file)必须要有和职员一样的地区属性或者为缺省值
    const ATTR_LIMIT_INDUSTRY = 3;//文件(file)必须要有和职员一样的业态属性或者为缺省值
    const ATTR_LIMIT_DISTRICT_INDUSTRY = 4; //文件(file)必须要有和职员一样的(地区和业态)属性或者为缺省值
*/


    public $childrenIds;
    public $childrenList;

    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'p_id'], 'required'],
            [['id', 'ord', 'level', 'is_leaf', 'is_last', 'p_id', 'attr_limit', 'status'], 'integer'],
            [['describe','link'], 'safe']
        ];
    }

    public function getDirPermission()
    {
        return $this->hasMany(DirPermission::className(), array('dir_id' => 'id'));
    }

    public function install(){
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('Dir has installed');
            }else{
                $arr = [
                    ['n'=>'企业运营中心','a'=>'zyfzzx','c'=>[
                        ['n'=>'企宣管控中心','a'=>'qx','c'=>[
                            ['n'=>'颂唐机构及旗下品牌LOGO标志','a'=>'logo','l'=>true],
                            ['n'=>'颂唐机构及旗下品牌名片','a'=>'mp','l'=>true],
                            ['n'=>'颂唐机构及旗下品牌PPT模板','a'=>'ppt','l'=>true],
                            ['n'=>'颂唐机构及旗下品牌通知模板','a'=>'tz','l'=>true],
                            ['n'=>'颂唐机构及旗下品牌公告模板','a'=>'gg','l'=>true],
                            ['n'=>'颂唐机构及旗下品牌制度模板','a'=>'zd','l'=>true],
                            ['n'=>'颂唐机构-信封信纸','a'=>'xfxz','l'=>true],
                            ['n'=>'颂唐机构-档案袋','a'=>'dad','l'=>true],
                            ['n'=>'颂唐机构-礼品袋','a'=>'lpd','l'=>true],
                            ['n'=>'颂唐机构-企业简介','a'=>'qyjj','l'=>true],
                            ['n'=>'颂唐机构-电话彩铃','a'=>'dhcl','l'=>true],
                        ]],
                        ['n'=>'行政管控中心','a'=>'xz','c'=>[
                            ['n'=>'公告/通知','a'=>'gg','l'=>true],
                            ['n'=>'规章制度','a'=>'gz','l'=>true],
                            ['n'=>'行政表单大全','a'=>'bd','l'=>true],
                            ['n'=>'行政培训模板','a'=>'px','l'=>true]
                        ]],
                        ['n'=>'财务管控中心','a'=>'cw','c'=>[
                            ['n'=>'财务管理制度','a'=>'zd','l'=>true],
                            ['n'=>'财务表单范本','a'=>'bd','l'=>true]
                        ]],
                        ['n'=>'法务管控中心','a'=>'fw','c'=>[
                            ['n'=>'合同范本','a'=>'ht','l'=>true],
                            ['n'=>'信函范本','a'=>'xh','l'=>true]
                        ]]
                    ]],
                    ['n'=>'发展资源中心','a'=>'fzzyzx','c'=>[
                        ['n'=>'颂唐机构-人才资源中心','a'=>'rc','c'=>[
                            ['n'=>'在职员工档案','a'=>'zz','c'=>[
                                ['n'=>'总部','a'=>'zb','l'=>true],
                                ['n'=>'上海','a'=>'sh','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                    ['n'=>'日鑫商业','a'=>'sy','l'=>true]
                                ]],
                                ['n'=>'苏州','a'=>'sz','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ]],
                                ['n'=>'无锡','a'=>'wx','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ]]
                            ]],
                            ['n'=>'离职员工档案','a'=>'lz','c'=>[
                                ['n'=>'总部','a'=>'zb','l'=>true],
                                ['n'=>'上海','a'=>'sh','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                    ['n'=>'日鑫商业','a'=>'sy','l'=>true]
                                ]],
                                ['n'=>'苏州','a'=>'sz','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ]],
                                ['n'=>'无锡','a'=>'wx','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ]]
                            ]],
                            ['n'=>'应聘员工档案','a'=>'yp','c'=>[
                                ['n'=>'总部','a'=>'zb','l'=>true],
                                ['n'=>'上海','a'=>'sh','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                    ['n'=>'日鑫商业','a'=>'sy','l'=>true]
                                ]],
                                ['n'=>'苏州','a'=>'sz','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ]],
                                ['n'=>'无锡','a'=>'wx','c'=>[
                                    ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                    ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ]]
                            ]]
                        ]],
                        ['n'=>'颂唐机构-客户资源中心','a'=>'kh','c'=>[
                            ['n'=>'甲方人员名单','a'=>'jf','l'=>true]
                        ]],
                        ['n'=>'颂唐机构-供应商资源中心','a'=>'gys','c'=>[
                            ['n'=>'供应商档案','a'=>'da','c'=>[
                                ['n'=>'颂唐地产','a'=>'dc','l'=>true],
                                ['n'=>'颂唐广告','a'=>'gg','l'=>true],
                                ['n'=>'日鑫商业','a'=>'sy','l'=>true]
                            ]]
                        ]],
                        ['n'=>'颂唐地产-客户资源中心','a'=>'dckh','link'=>'/','l'=>true],
                        ['n'=>'日鑫商业-商家资源中心','a'=>'sysj','link'=>'/','l'=>true]
                    ]],
                    ['n'=>'工具应用中心','a'=>'gjyyzx','c'=>[
                        ['n'=>'颂唐地产','a'=>'dc','c'=>[
                            ['n'=>'开发拓展部工具箱','a'=>'kf','l'=>true],
                            ['n'=>'市场策划部工具箱','a'=>'sc','l'=>true],
                            ['n'=>'销售业务部工具箱','a'=>'xs','l'=>true]
                        ]],
                        ['n'=>'颂唐广告','a'=>'gg','c'=>[
                            ['n'=>'AE策划部工具箱','a'=>'ae','l'=>true],
                            ['n'=>'创作部工具箱','a'=>'cz','l'=>true]
                        ]],
                        ['n'=>'日鑫商业','a'=>'sy','c'=>[
                            ['n'=>'商业策划部工具箱','a'=>'ch','l'=>true],
                            ['n'=>'商业招商部工具箱','a'=>'zs','l'=>true]
                        ]],
                    ]],
                    ['n'=>'项目资源中心','a'=>'xmzyzx','c'=>[
                        ['n'=>'执行项目资料中心','a'=>'zx','c'=>[
                            ['n'=>'颂唐地产','a'=>'dc','c'=>[
                                ['n'=>'项目A','a'=>'a','l'=>true],
                                ['n'=>'项目B','a'=>'b','l'=>true],
                                ['n'=>'项目C','a'=>'c','l'=>true]
                            ]],
                            ['n'=>'颂唐广告','a'=>'gg','c'=>[
                                ['n'=>'项目A','a'=>'a','l'=>true],
                                ['n'=>'项目B','a'=>'b','l'=>true],
                                ['n'=>'项目C','a'=>'c','l'=>true]
                            ]],
                            ['n'=>'日鑫商业','a'=>'sy','c'=>[
                                ['n'=>'项目A','a'=>'a','l'=>true],
                                ['n'=>'项目B','a'=>'b','l'=>true],
                                ['n'=>'项目C','a'=>'c','l'=>true]
                            ]]
                        ]],
                        ['n'=>'历史项目资料中心','a'=>'ls','c'=>[
                            ['n'=>'项目A','a'=>'a','l'=>true],
                            ['n'=>'项目B','a'=>'b','l'=>true],
                            ['n'=>'项目C','a'=>'c','l'=>true],
                            ['n'=>'项目D','a'=>'c','l'=>true],
                            ['n'=>'项目E','a'=>'c','l'=>true]
                        ]]
                    ]],
                    ['n'=>'学习共享中心','a'=>'xxgxzx','c'=>[
                        ['n'=>'公司推荐学习资料库','a'=>'gs','l'=>true],
                        ['n'=>'员工推荐学习资料库','a'=>'yg','l'=>true]
                    ]]
                ];
                $this->insertData($arr,0,1);

                echo 'Dir install finish'."<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            //echo  'Dept_Area  install failed<br />';
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            echo '<br/>';

            /*echo '<br/><br/>';
            var_dump($e);
            echo '<br/><br/>';
            var_dump($errorInfo);*/

            //throw new \Exception($message, $errorInfo, (int) $e->getCode(), $e);
            return false;
        }
    }

    private function insertData($arr,$p_id,$level){

        $ord = 1;
        $count = count($arr);
        foreach($arr as $v) {
            $m = new Dir();
            $m->name = $v['n'];
            $m->alias = $v['a'];
            $m->ord = $ord;
            $m->is_last = $ord==$count?1:0;
            $m->is_leaf = isset($v['l']) && $v['l']==1?1:0;
            $m->link = isset($v['link'])?$v['link']:'';
            $m->p_id = $p_id;
            $m->level = $level;
            $m->status = 1;
            $m->save();
            if(isset($v['c'])){
                $this->insertData($v['c'],$m->id,$level+1);
            }
            $ord++;
        }
    }

    public static function getAttrSearch($attrLimit){
        $districtCheck = [];
        $industryCheck = [];
        if(Yii::$app->user->identity->isYunFrontend){
            $districtCheck = District::getIds();
            $industryCheck = Industry::getIds();
        }else{
            switch ($attrLimit){
                case DirPermission::PERMISSION_TYPE_NORMAL:
                case [DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY]:
                    $districtCheck = District::getIds();
                    $industryCheck = Industry::getIds();
                    break;
                case DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT:
                    $districtCheck = [Attribute::DISTRICT_DEFAULT,Yii::$app->user->identity->district_id];
                    $industryCheck = Industry::getIds();
                    break;
                case DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY:
                    $districtCheck =  District::getIds();
                    $industryCheck = [Attribute::INDUSTRY_DEFAULT,Yii::$app->user->identity->industry_id];
                    break;
                case DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY:
                    $districtCheck = [Attribute::DISTRICT_DEFAULT,Yii::$app->user->identity->district_id];
                    $industryCheck = [Attribute::INDUSTRY_DEFAULT,Yii::$app->user->identity->industry_id];
                    break;
            }
        }

        $districtCheck = $districtCheck!=false?District::getCheckIdsTrue($districtCheck):[];
        $industryCheck = $industryCheck!=false?Industry::getCheckIdsTrue($industryCheck):[];
        $attrSearch = ['district'=>$districtCheck,'industry'=>$industryCheck];
        return $attrSearch;
    }


   /* public static function getIds(){

    }*/

    public static function getParents($id){
        $return = [];
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur && $cur->p_id>0){
            $parents = self::getParents($cur->p_id);
            $return = array_merge([$cur->p_id],$parents);
        }

        return $return;
    }


    public static function getParentsByCache($id){
        $cache = yii::$app->cache;
        $key = 'dir-parents-data';
        if(isset($cache[$key]) && isset($cache[$key][$id])){
            $data = $cache[$key][$id];
        }else {
            $data = self::getParents($id);
            if(!isset($cache[$key])){
                $arr = [$id => $data];
            }else{
                $arr = ArrayHelper::merge($cache[$key],[$id => $data]);
            }
            $cache[$key] = $arr;
        }
        return $data;
    }

    public static function getOne($id){
        $dirData = (object)(Dir::find()->where(['id'=>$id])->one()->toArray()); //只取 元素的值
        return $dirData;
    }

    /*
     * 函数getOneByCache ,实现根据 id 读取数据缓存，有则直接返回缓存内容，没有则获取模型数据，并添加至缓存
     *
     * @param integer id  dir:id
     * return DirModel
     */
    public static function getOneByCache($id){
        $cache = yii::$app->cache;
        $key = 'dir-data';
        if(isset($cache[$key]) && isset($cache[$key][$id])){
            $data = $cache[$key][$id];
        }else {
            $data = self::getOne($id);
            if(!isset($cache[$key])){
                $arr = [$id => $data];
            }else{
                $arr = ArrayHelper::merge($cache[$key],[$id => $data]);
            }
            $cache[$key] = $arr;
        }
        return $data;
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
    public static function getChildrenByCache($p_id,$showLeaf=true,$status=1,$orderBy=self::ORDER_TYPE_1,$limit=false){
        $cache = yii::$app->cache;
        $key = 'dir-children-data';
        $idKey = $p_id.'_'.($showLeaf==true?'1':'0').'_'.($status==1?'1':'0').'_'.$orderBy.'_'.($limit==false?'f':$limit);
        if(isset($cache[$key]) && isset($cache[$key][$idKey])){
            $data = $cache[$key][$idKey];
        }else {
            $data = self::getChildren($p_id,$showLeaf,$status,$orderBy,$limit);
            if(!isset($cache[$key])){
                $arr = [$idKey => $data];
            }else{
                $arr = ArrayHelper::merge($cache[$key],[$idKey => $data]);
            }
            $cache[$key] = $arr;
        }
        return $data;
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
    public static function getChildren($p_id,$showLeaf=true,$status=1,$orderBy=self::ORDER_TYPE_1,$limit=false){
        $where['p_id'] = $p_id;
        $where['status'] = $status;
        if($showLeaf==false)
            $where['is_leaf'] = 0;

        $result = [];
        $list = self::find()->where($where)->orderBy($orderBy)->limit($limit)->asArray()->all();
        if(!empty($list)){
            foreach($list as $l){
                $result[] = (object)$l;
            }
        }
        return $result;
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

    public static function getListArr($p_id=0,$showLeaf=true,$showTree=false,$includeSelf=false,$level=false){
        $arr = [];
        $dir = NULL;
        $selfChildrenIds = [];
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
            $list = self::getChildrenByCache($p_id,$showLeaf);

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

        if($dir && $includeSelf===true){
            $arr[$dir->id]->childrenIds = $selfChildrenIds;
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
            $dir = Dir::getOneByCache($p_id);
            if($dir==NULL || $dir->status==0){ //不存在或者状态禁用则返回空数组
                return [];
            }else if($includeSelf===true){ //将自己本身添加至数组
                $arr[$dir->id]= $dir;
            }
        }

        $level = $level===false?false:intval($level);
        if($level>0 || $level===false){  //level正整数 或者 false不限制
            $list = self::getChildrenByCache($p_id,$showLeaf);

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
    * 函数 handleIsLast , 修改了职位信息后，批量更新is_last字段
    *
    * @param integer p_id  父id
    * no return
    */
    /*
     *  获取指定type_id 和 p_id 的当前层所有目录id和name   (既只获取一层目录)
     */

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

    public static function getDropDownList($p_id=0,$showLeaf=true,$includeSelf=false,$level=false){
        $arr = [];

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


    public static function getFullRouteByCache($id){
        $cache = yii::$app->cache;
        $key = 'dir-full-route';
        $dataArr = $cache->get($key);
        if(!empty($dataArr) && isset($dataArr[$id])){
            $data = $dataArr[$id];
        }else {
            $data = self::getFullRoute($id);

            if(empty($dataArr)){
                $arr = [$id => $data];
            }else{
                $arr = ArrayHelper::merge($dataArr,[$id => $data]);
            }
            $cache->set($key,$arr,86400);
        }
        return $data;
    }

    /*
    * 函数getFullRoute ,实现根据dir_id(Dir表 id字段)获取完整的板块目录路径
    *
    * @param dir_id 位置id
    * @param separator 分隔符 (默认 '>' )
    * return string/null
    */
    public static function getFullRoute($dir_id,$separator = ' > '){
        $dir = Dir::find()->where(['id'=>$dir_id])->one();
        if($dir!==NULL){
            $str = '';
            $str.= self::getFullRoute($dir->p_id,$separator);
            if($str!=null){
                $str.= $separator;
            }
            $str.= $dir->name;
            return $str;
        }else{
            return null;
        }
    }



}