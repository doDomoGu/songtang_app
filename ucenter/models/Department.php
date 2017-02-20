<?php

namespace ucenter\models;
use Yii;
//部门
class Department extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'alias' => '别名',
            'p_id' => '父ID',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'ord', 'status'], 'required'],
            [['id', 'p_id', 'ord', 'status'], 'integer'],
            [['name', 'alias'], 'safe'],
        ];
    }


    public static function getNameArr()
    {
        $list = self::find()->select(['id', 'name'])->all();
        $arr = [];
        foreach ($list as $l) {
            $arr[$l->id] = $l->name;
        }
        return $arr;
    }

    public static function getItems(){
        $items = [];

        $list = self::getList();
        foreach($list as $l){
            $items[$l->id] = $l->name;
        }
        return $items;
    }

    public static function getChildrenIds($p_id){
        $list = self::find()->where(['p_id'=>$p_id])->all();
        $arr = [];
        foreach ($list as $l) {
            $arr[] = $l->id;
        }
        return $arr;
    }


    public function install()
    {
        try {
            $exist = self::find()->one();
            if ($exist) {
                throw new \yii\base\Exception('Department has installed');
            } else {
                $arr = [
                    ['a' => 'default', 'n' => '--'],
                    ['a' => 'ds', 'n' => '董事'],
                    ['a' => 'fzgkzx', 'n' => '发展管控中心'],
                    ['a' => 'xzgkzx', 'n' => '行政管控中心'],
                    ['a' => 'cwgkzx', 'n' => '财务管控中心'],
                    ['a' => 'qxgkzx', 'n' => '企宣管控中心'],
                    ['a' => 'scclzx', 'n' => '市场策略中心'],
                    ['a' => 'zhglb', 'n' => '综合管理部'],
                    ['a' => 'cwb', 'n' => '财务部'],
                    ['a' => 'zjb', 'n' => '总经办'],
                    ['a' => 'kftzb', 'n' => '开发拓展部'],
                    ['a' => 'xschb', 'n' => '销售策划部'],
                    ['a' => 'yxchb', 'n' => '营销策划部'],
                    ['a' => 'xsywb', 'n' => '销售业务部', 'c' => [
                        ['a' => 'bssksjac', 'n' => '宝山上坤上街案场'],
                        ['a' => 'sxjyac', 'n' => '绍兴锦园案场'],
                    ]],
                    ['a' => 'aechb', 'n' => 'AE/策划部'],
                    ['a' => 'czb', 'n' => '创作部'],
                    ['a' => 'pptzb', 'n' => '品牌拓展部'],
                    ['a' => 'syzsb', 'n' => '商业招商部', 'c' => [
                        ['a' => 'wjac', 'n' => '吴江案场','c'=>[
                            ['a' => 'aaa', 'n' => 'a组'],
                            ['a' => 'bbb', 'n' => 'b组']
                            ]
                        ],
                        ['a' => 'fyac', 'n' => '阜阳案场'],
                    ]],
                ];
                $this->add($arr, 0, 1);


                echo 'Department install finish' . "<br/>";
            }
            return true;
        } catch (\Exception $e) {
            //echo  'Dept_Business  install failed<br />';
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

    public function add($arr, $p_id, $ord)
    {
        //$count = count($arr);
        foreach ($arr as $a) {
            $m = new Department();
            $m->name = trim($a['n']);
            $m->alias = trim($a['a']);
            $m->ord = $ord;
            $m->p_id = $p_id;
            $m->status = 1;
            $m->save();
            if (isset($a['c']) && !empty($a['c']))
                $this->add($a['c'], $m->id, 1);
            $ord++;
        }
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

    public static function getList($p_id = 0, $level = 0, $recursion = true)
    {
        $arr = [];
        $dir = NULL;
        //$selfChildrenIds = [];

        $list = self::getChildren($p_id);

        if (!empty($list)) {
            $level++;
            $count = count($list);
            $i = 1;
            foreach ($list as $l) {
                $arr[$l->id] = $l;
                $prefix = '';
                if ($level > 1) {
                    for ($j = 1; $j < $level; $j++) {
                        $prefix .= '&emsp;';
                    }
                    if ($count == $i) {
                        $prefix .= '└─ ';
                    } else {
                        $prefix .= '├─ ';
                    }
                }
                $arr[$l->id]->name = $prefix . $l->name;
                if ($recursion) {
                    $children = self::getList($l->id, $level);
                    //$childrenIds = array();
                    if (!empty($children)) {
                        foreach ($children as $child) {
                            $arr[$child->id] = $child;
                            //$childrenIds[]=$child->id;
                        }
                    }
                    //$arr[$l->id]->childrenIds = $childrenIds;
                }
                $i++;
            }
        }
        return $arr;
    }

    public static function getChildren($p_id)
    {
        $list = self::find()->where(['p_id' => $p_id])->orderBy('ord asc')->all();
        return $list;
    }


    public static function ordUp($id)
    {
        $result = false;
        $cur = self::find()->where(['id' => $id])->one();
        if ($cur) {
            $curOrd = $cur->ord;
            $to = self::find()->where(['<', 'ord', $curOrd])->andWhere(['status' => 1, 'p_id' => $cur->p_id])->orderBy('ord desc')->one();
            if ($to) {
                $cur->ord = $to->ord;
                $cur->save();
                $to->ord = $curOrd;
                $to->save();
                $result = true;
            }
        }
        return $result;
    }

    public static function ordDown($id)
    {
        $result = false;
        $cur = self::find()->where(['id' => $id])->one();
        if ($cur) {
            $curOrd = $cur->ord;
            $to = self::find()->where(['>', 'ord', $curOrd])->andWhere(['status' => 1, 'p_id' => $cur->p_id])->orderBy('ord asc')->one();
            if ($to) {
                $cur->ord = $to->ord;
                $cur->save();
                $to->ord = $curOrd;
                $to->save();
                $result = true;
            }
        }
        return $result;
    }

    public static function ordTop($id)
    {
        $result = false;
        $cur = self::find()->where(['id' => $id])->one();
        if ($cur) {
            $curOrd = $cur->ord;
            $toList = self::find()->where(['<', 'ord', $curOrd])->andWhere(['status' => 1, 'p_id' => $cur->p_id])->orderBy('ord desc')->all();
            if (!empty($toList)) {
                foreach ($toList as $to) {
                    $toOrd = $to->ord;
                    $to->ord = $curOrd;
                    $to->save();
                    $curOrd = $toOrd;
                }
                $cur->ord = $curOrd;
                $cur->save();
                $result = true;
            }
        }
        return $result;
    }

    public static function ordBottom($id)
    {
        $result = false;
        $cur = self::find()->where(['id' => $id])->one();
        if ($cur) {
            $curOrd = $cur->ord;
            $toList = self::find()->where(['>', 'ord', $curOrd])->andWhere(['status' => 1, 'p_id' => $cur->p_id])->orderBy('ord asc')->all();
            if (!empty($toList)) {
                foreach ($toList as $to) {
                    $toOrd = $to->ord;
                    $to->ord = $curOrd;
                    $to->save();
                    $curOrd = $toOrd;
                }
                $cur->ord = $curOrd;
                $cur->save();
                $result = true;
            }
        }
        return $result;
    }

    /*
 * 函数getFullRoute ,实现根据dir_id(Dir表 id字段)获取完整的板块目录路径
 *
 * @param dir_id 位置id
 * @param separator 分隔符 (默认 '>' )
 * return string/null
 */
    public static function getFullRoute($id,$separator = ' > '){
        $self = self::find()->where(['id'=>$id])->one();
        if($self!==NULL){
            $str = '';
            $str.= self::getFullRoute($self->p_id,$separator);
            if($str!=null){
                $str.= $separator;
            }
            $str.= $self->name;
            return $str;
        }else{
            return null;
        }
    }
}