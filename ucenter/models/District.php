<?php
namespace ucenter\models;

use Yii;

//District      地区 (地方,行政划分)

class District extends \yii\db\ActiveRecord{
    const DEFAULT_ID = '';


    public static function getDb(){
        return Yii::$app->db_ucenter;
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => '名称',
            'alias' => '别名',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'ord', 'status'], 'required'],
            [['id', 'ord', 'status'], 'integer'],
            [['name','alias'],'safe'],
        ];
    }

    public static function getArr(){
        return [
            'default' => '--',
            'hq' => '总部',
            'sh' => '上海',
            'sz' => '苏州',
            'wx' => '无锡',
            'nj' => '南京',
            'hf' => '合肥',
            'hhht' => '呼和浩特'
        ];
    }


    public function getIndustryRelations(){
        return Structure::find()->where([
            'district_id'=>$this->id,
            'company_id'=>0,
            'department_id'=>0,
            'status'=>1
        ])->with('industry')->all();
    }

    public static function getNameArr(){
        $list = self::find()->select(['id','name'])->all();
        $arr = [];
        foreach($list as $l){
            $arr[$l->id] = $l->name;
        }
        return $arr;
    }

    //获得与地区相关的行业
    public static function getIndustryRelationsArr($aid){
        $list = Structure::find()->where(['aid'=>$aid,'did'=>0,'status'=>1])->with('industry')->all();
        $arr = [];
        foreach($list as $l){
            $arr[$l->industry_id] = $l->industry->name;
        }
        return $arr;
    }

    public static function getItems($frontend=false){
        $items = [];
        $list = self::find()->where(['status'=>1])->orderBy('ord asc')->all();
        foreach($list as $l){
            $items[$l->id] = $l->name;
        }
        if($frontend){  //前台 默认值显示设置
            $default = self::find()->where(['alias'=>'default'])->one();
            $items[$default->id] = '全员';
        }
        return $items;
    }

    public static function getIds(){
        $ids = [];
        $list = self::find()->where(['status'=>1])->orderBy('ord asc')->all();
        foreach($list as $l){
            $ids[] = $l->id;
        }
        return $ids;
    }


    public function install() {
        try {
            $exist = self::find()->one();
            if($exist){
                throw new \yii\base\Exception('District has installed');
            }else{
                $arr = self::getArr();
                $ord = 1;
                foreach($arr as $k=>$v) {
                    $m = new self();
                    $m->name = $v;
                    $m->alias = $k;
                    $m->ord = $ord;
                    $m->status = 1;
                    $m->save();
                    $ord++;
                }
                echo 'District install finish'."<br/>";
            }
            return true;
        }catch (\Exception $e)
        {
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            echo '<br/>';
            return false;
        }
    }

    public static function getCheckIdsTrue($checkArr){
        $return = [];
        $list = self::find()->where(['id'=>$checkArr,'status'=>1])->all();
        foreach($list as $l){
            $return[] = $l->id;
        }
        return $return;
    }

    public static function ordUp($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $to = self::find()->where(['<','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord desc')->one();
            if($to){
                $cur->ord = $to->ord;
                $cur->save();
                $to->ord = $curOrd;
                $to->save();
                $result  = true;
            }
        }
        return $result;
    }

    public static function ordDown($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $to = self::find()->where(['>','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord asc')->one();
            if($to){
                $cur->ord = $to->ord;
                $cur->save();
                $to->ord = $curOrd;
                $to->save();
                $result  = true;
            }
        }
        return $result;
    }

    public static function ordTop($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $toList = self::find()->where(['<','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord desc')->all();
            if(!empty($toList)){
                foreach($toList as $to){
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

    public static function ordBottom($id){
        $result = false;
        $cur = self::find()->where(['id'=>$id])->one();
        if($cur){
            $curOrd = $cur->ord;
            $toList = self::find()->where(['>','ord',$curOrd])->andWhere(['status'=>1])->orderBy('ord asc')->all();
            if(!empty($toList)){
                foreach($toList as $to){
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

}
