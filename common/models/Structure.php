<?php

namespace common\models;


//公司结构
use yii\helpers\ArrayHelper;

class Structure extends \yii\db\ActiveRecord
{
    public function attributeLabels(){
        return [
            'aid' => '地区',
            'bid' => '业态',
            'did' => '部门',
            /*'class_p_id' => '父ID',
            'class_level' => '级数',

            'is_last' => '是否排序最后',*/
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [[ 'aid', 'bid', 'did','ord', 'status'], 'integer'],
        ];
    }

    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('Structure has installed');
            }else{
                $arr0 = [
                    'default'=>[
                        'default'=>['default']
                    ],
                    'headquarters'=>[
                        'default'=>[
                            'fzgkzx',
                            'xzgkzx',
                            'cwgkzx',
                            'qxgkzx',
                            'scclzx'
                        ],
                    ],
                    'sh'=>[
                        'default'=>['zhglb','cwb'],
                        'stdc'=>['zjb','kftzb','yxchb','xsywb'=>['bssksjac','sxjyac']],
                        'stgg'=>['zjb','kftzb','aechb'],
                        'rxsy'=>['zjb','pptzb','syzsb'=>['wjac'=>['aaa','bbb']]]
                    ],
                    'sz'=>[
                        'default'=>['zhglb','cwb'],
                        'stdc'=>['zjb','kftzb','yxchb','xsywb'],
                        'stgg'=>['zjb','kftzb','aechb','czb'],
                    ],
                    'wx'=>[
                        'default'=>['zhglb','cwb'],
                        'stdc'=>['zjb','kftzb','yxchb','xsywb'],
                        'stgg'=>['zjb','kftzb','aechb'],
                    ],
                ];
                foreach($arr0 as $k=>$arr) {
                    if(is_array($arr)){
                        $aid = $this->getId($k,1);
                        foreach($arr as $k2=>$arr2 ){
                            if(is_array($arr2)){
                                $bid = $this->getId($k2,2);
                                $this->addX($aid,$bid,0,$arr2);
                            }
                        }
                    }
                }

                //补全relation
                $list1 = Structure::find()->where(['>','aid',0])->groupBy('aid,bid')->all();
                foreach($list1 as $l){
                    $this->addOne($l->aid,$l->bid,0);
                }

                $list2 = Structure::find()->where(['>','did',0])->groupBy('bid,did')->all();
                foreach($list2 as $l){
                    $this->addOne(0,$l->bid,$l->did);
                }


                echo 'Structure install finish'."<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            //echo  'Dept_Area  install failed<br />';
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            echo '<br/>';
            return false;
        }
    }

    protected function addX($aid,$bid,$p_id,$arr){
        foreach($arr as $k2=>$arr2){
            if(is_array($arr2)){
                $did = $this->getId($k2,3,$p_id);
                $this->addX($aid,$bid,$did,$arr2);
            }else{
                $did = $this->getId($arr2,3,$p_id);
                $this->addOne($aid,$bid,$did);
            }
        }
    }
    protected function addOne($aid,$bid,$did){
        $new = new Structure();
        $new->aid = $aid;
        $new->bid = $bid;
        $new->did = $did;
        $new->status = 1;
        $new->save();

    }

    protected function getId($alias,$level,$p_id=0){
        $return = 0;
        if($level==1){
            //area
            $a = Area::find()->where(['alias'=>$alias])->one();
            if($a)
                $return = $a->id;
        }elseif($level==2){
            //business
            $b = Business::find()->where(['alias'=>$alias])->one();
            if($b)
                $return = $b->id;
        }else{
            //department
            $d = Department::find()->where(['alias'=>$alias,'p_id'=>$p_id])->one();
            if($d)
                $return = $d->id;
        }

        return $return;
    }


    public function getBusinessRelations(){
        $list = Structure::find()->where(['aid'=>$this->aid,'did'=>0,'status'=>1])->all();
        return $list;
    }

    public function getDepartmentRelations(){
        $list = $this->getDepartmentRelationsRecursion($this->aid,$this->bid,0,1);

        //$list = Department::find()->where(['aid'=>$this->aid,'bid'=>$this->bid,'status'=>1,'p_id'=>0])->andWhere(['>','cid',0])->all();


        return $list;
    }

    public function getDepartmentRelationsRecursion($aid,$bid,$p_id,$level){
        $arr = [];
        $list = Structure::find()->where(['aid'=>$aid,'bid'=>$bid,'status'=>1,'did'=>Department::getChildrenIds($p_id)])->with('department')->all();
        if(!empty($list)){
            $count = count($list);
            $i = 1;
            foreach($list as $l){
                $l2 = new \stdClass();
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
                $l2->aid = $l->aid;
                $l2->bid = $l->bid;
                $l2->did = $l->did;
                $l2->name = $prefix . $l->department->name;
                $l2->status = $l->status;
                $arr[] = $l2;

                $children = $this->getDepartmentRelationsRecursion($l2->aid,$l2->bid,$l->did,$level+1);
                if(!empty($children)){
                    $arr = ArrayHelper::merge($arr,$children);
                }

                $i++;
            }
        }

        return $arr;
    }

    public function getBusiness(){
        return $this->hasOne('common\models\Business', array('id' => 'bid'));
    }

    public function getDepartment(){
        return $this->hasOne('common\models\Department', array('id' => 'did'));
    }


}
