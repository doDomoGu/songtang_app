<?php
namespace ucenter\models;

use Yii;
//公司结构
use yii\helpers\ArrayHelper;

class Structure extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels(){
        return [
            'district_id' => '地区',
            'industry_id' => '行业',
            'company_id' => '公司',
            'department_id' => '部门',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [[ 'district_id', 'industry_id', 'company_id', 'department_id', 'ord', 'status'], 'integer'],
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
                    'hq'=>[
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

    public function getIndustryList(){
        $list = Structure::find()
            ->where(['district_id'=>$this->district_id,'status'=>1])
            ->andWhere(['>','industry_id',0])
            ->groupBy('district_id,industry_id')
            ->with('industry')
            ->all();
        return $list;
    }

    public function getCompanyList(){
        $list = Structure::find()
            ->where(['district_id'=>$this->district_id,'industry_id'=>$this->industry_id,'status'=>1])
            ->andWhere(['>','company_id',0])
            ->groupBy('district_id,industry_id,company_id')
            ->with('company')
            ->all();
        return $list;
    }



    public function getIndustryRelations(){
        $list = Structure::find()->where(['district_id'=>$this->district_id,'company_id'=>0,'department_id'=>0,'status'=>1])->with('industry')->all();
        return $list;
    }

    public function getCompanyRelations(){
        $list = Structure::find()->where(['district_id'=>$this->district_id,'industry_id'=>$this->industry_id,'department_id'=>0,'status'=>1])->andWhere(['>','company_id',0])->with('company')->all();
        return $list;
    }

    public function getDepartmentList(){
        $list = $this->getDepartmentRelationsRecursion($this->district_id,$this->industry_id,$this->company_id,0,1);
        return $list;
    }

    public function getDepartmentRelations(){
        $list = $this->getDepartmentRelationsRecursion($this->district_id,$this->industry_id,$this->company_id,0,1);
        //$list = Department::find()->where(['aid'=>$this->aid,'bid'=>$this->bid,'status'=>1,'p_id'=>0])->andWhere(['>','cid',0])->all();


        return $list;
    }

    public function getDepartmentRelationsRecursion($district_id,$industry_id,$company_id,$p_id,$level){
        $arr = [];
        $list = Structure::find()->where([
            'district_id'=>$district_id,
            'industry_id'=>$industry_id,
            'company_id'=>$company_id,
            'status'=>1,
            'department_id'=>Department::getChildrenIds($p_id)
        ])->with('department')->all();
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
                $l2->district_id = $l->district_id;
                $l2->industry_id = $l->industry_id;
                $l2->company_id = $l->company_id;
                $l2->department_id = $l->department_id;
                $l2->name = $prefix . $l->department->name;
                $l2->status = $l->status;
                $arr[] = $l2;

                $children = $this->getDepartmentRelationsRecursion($l2->district_id,$l2->industry_id,$l2->company_id,$l2->department_id,$level+1);
                if(!empty($children)){
                    $arr = ArrayHelper::merge($arr,$children);
                }

                $i++;
            }
        }

        return $arr;
    }

    public function getDistrict(){
        return $this->hasOne(District::className(), array('id' => 'district_id'));
    }

    public function getIndustry(){
        return $this->hasOne(Industry::className(), array('id' => 'industry_id'));
    }

    public function getCompany(){
        return $this->hasOne(Company::className(), array('id' => 'company_id'));
    }

    public function getDepartment(){
        return $this->hasOne(Department::className(), array('id' => 'department_id'));
    }


}
