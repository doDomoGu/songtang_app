<?php

namespace yun\models;
use yun\components\CommonFunc;
use yun\components\DirFunc;
//use yun\components\PositionFunc;
use Yii;
use yii\helpers\ArrayHelper;

class Dir extends \yii\db\ActiveRecord
{
    public $childrenIds;
    public $childrenList;

    public static function getDb(){
        return Yii::$app->db_yun;
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'p_id'], 'required'],
            [['id', 'ord', 'level', 'is_leaf', 'is_last', 'p_id', 'status'], 'integer'],
            [['describe','link'], 'safe']
        ];
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
}