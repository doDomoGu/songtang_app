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
            [['id', 'type', 'ord', 'level', 'is_leaf', 'is_last', 'p_id', 'status'], 'integer'],
            [['describe'], 'safe']
        ];
    }



    public $arr0;

    /*public $arr_yt1;

    public $arr_yt2;

    public $arr_diyu;

    public $arr_company;

    public $arr_xiangmu;

    public $arr_yt3;*/

    public $arr1;

    public $arr2;

    public $arr3;

    public $arr4;

    public $arr5;

    public $city;

    public $yt;
    /*public $localArr;

    public $ytArr;

    public $positionArr;

    public $localPositionArr;*/

    public function __construct(){
        /*$this->localArr = [
            'shzbpt','sh','sz','wx','hf','sb','ah','nj'
        ];

        $this->ytArr = [
            'stjg','scclzx','hmjz','stdc','hyfw','zqjj','mzzy','rxsy','stgg','sjgg','yshd','zdwy'
        ];

        $this->positionArr = [
            'zhglb','cwb','zjb','kftzb','scchb'
        ];

        $this->localPositionArr = [
            'zjb'=>['sh/zjb','sz/zjb','wx/zjb','hf/zjb','sb/zjb','ah/zjb','nj/zjb'],
            'kftzb'=>['sh/kftzb','sz/kftzb','wx/kftzb','hf/kftzb','sb/kftzb','ah/kftzb','nj/kftzb'],
            'scchb'=>['sh/scchb','sz/scchb','wx/scchb','hf/scchb','sb/scchb','ah/scchb','nj/scchb'],
        ];


        $this->arr_diyu = [
            'stjg'=>[
                ['n'=>'上海总部平台','a'=>'shzbpt','l'=>true]
            ],
            'scclzx'=>[
                ['n'=>'上海总部平台','a'=>'shzbpt','l'=>true]
            ],
            'hmjz'=>[
                ['n'=>'上海华麦建筑','a'=>'sh','l'=>true]
            ],
            'stdc'=>[
                ['n'=>'上海颂唐地产','a'=>'sh','l'=>true],
                ['n'=>'苏州颂唐地产','a'=>'sz','l'=>true],
                ['n'=>'无锡颂唐地产','a'=>'wx','l'=>true],
                ['n'=>'南京颂唐地产','a'=>'nj','l'=>true],
                ['n'=>'安徽颂唐地产','a'=>'ah','l'=>true],
                ['n'=>'苏北颂唐地产','a'=>'sb','l'=>true]
            ],
            'hyfw'=>[
                ['n'=>'苏州汉佑房屋','a'=>'sz','l'=>true],
                ['n'=>'无锡汉佑房屋','a'=>'wx','l'=>true]
            ],
            'zqjj'=>[
                ['n'=>'上海致秦经纪','a'=>'sh','l'=>true],
                ['n'=>'苏州致秦经纪','a'=>'sz','l'=>true],
                ['n'=>'无锡致秦经纪','a'=>'wx','l'=>true],
                ['n'=>'南京致秦经纪','a'=>'nj','l'=>true],
                ['n'=>'合肥致秦经纪','a'=>'hf','l'=>true]
            ],
            'mzzy'=>[
                ['n'=>'上海明致置业','a'=>'sh','l'=>true],
                ['n'=>'南京明致置业','a'=>'nj','l'=>true]
            ],
            'rxsy'=>[
                ['n'=>'上海日鑫商业','a'=>'sh','l'=>true],
                ['n'=>'苏州日鑫商业','a'=>'sz','l'=>true],
                ['n'=>'无锡日鑫商业','a'=>'wx','l'=>true],
                ['n'=>'南京日鑫商业','a'=>'nj','l'=>true],
                ['n'=>'安徽日鑫商业','a'=>'ah','l'=>true],
                ['n'=>'苏北日鑫商业','a'=>'sb','l'=>true]
            ],
            'stgg'=>[
                ['n'=>'上海颂唐广告','a'=>'sh','l'=>true],
                ['n'=>'苏州颂唐广告','a'=>'sz','l'=>true],
                ['n'=>'南京颂唐广告','a'=>'nj','l'=>true],
                ['n'=>'安徽颂唐广告','a'=>'ah','l'=>true]
            ],
            'sjgg'=>[
                ['n'=>'苏州尚晋公关','a'=>'sz','l'=>true]
            ],
            'yshd'=>[
                ['n'=>'上海元素互动','a'=>'sh','l'=>true]
            ],
            'zdwy'=>[
                ['n'=>'苏州周道物业','a'=>'sz','l'=>true]
            ]
        ];*/
        $xiangmu = [
            ['n'=>'项目A','a'=>'xm_a','l'=>true],
            ['n'=>'项目B','a'=>'xm_b','l'=>true],
            ['n'=>'项目C','a'=>'xm_c','l'=>true],
            ['n'=>'项目D','a'=>'xm_d','l'=>true],
            ['n'=>'项目E','a'=>'xm_e','l'=>true]
        ];
        $this->arr0 = [
            ['n'=>'企业运营中心','a'=>'zyfzzx','c'=>[]],
            ['n'=>'发展资源中心','a'=>'fzzyzx','c'=>[]],
            ['n'=>'工具应用中心','a'=>'gjyyzx','c'=>[]],
            ['n'=>'项目资源中心','a'=>'xmzyzx','c'=>[]],
            ['n'=>'学习共享中心','a'=>'xxgxzx','c'=>[]]
        ];

        /*$this->arr_yt1 = [
            ['n'=>'颂唐机构','a'=>'stjg','l'=>true],
            ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
            ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
            ['n'=>'日鑫商业','a'=>'rxsy','l'=>true],
            ['n'=>'汉佑房屋','a'=>'hyfw','l'=>true],
            ['n'=>'鸿汉经纪','a'=>'hhjj','l'=>true],
            ['n'=>'明致置业','a'=>'mzzy','l'=>true],
            ['n'=>'华麦建筑','a'=>'hmjz','l'=>true],
            ['n'=>'尚晋公关','a'=>'sjgg','l'=>true],
            ['n'=>'元素互动','a'=>'yshd','l'=>true],
            ['n'=>'周道物业','a'=>'zdwy','l'=>true]
        ];

        $this->arr_yt2 = [
            ['n'=>'颂唐机构','a'=>'stjg','c'=>$this->arr_diyu['stjg']],
            ['n'=>'市场策略中心','a'=>'scclzx','c'=>$this->arr_diyu['scclzx']],
            ['n'=>'华麦建筑','a'=>'hmjz','c'=>$this->arr_diyu['hmjz']],
            ['n'=>'颂唐地产','a'=>'stdc','c'=>$this->arr_diyu['stdc']],
            ['n'=>'汉佑房屋','a'=>'hyfw','c'=>$this->arr_diyu['hyfw']],
            ['n'=>'致秦经纪','a'=>'zqjj','c'=>$this->arr_diyu['zqjj']],
            ['n'=>'明致置业','a'=>'mzzy','c'=>$this->arr_diyu['mzzy']],
            ['n'=>'日鑫商业','a'=>'rxsy','c'=>$this->arr_diyu['rxsy']],
            ['n'=>'颂唐广告','a'=>'stgg','c'=>$this->arr_diyu['stgg']],
            ['n'=>'尚晋公关','a'=>'sjgg','c'=>$this->arr_diyu['sjgg']],
            ['n'=>'元素互动','a'=>'yshd','c'=>$this->arr_diyu['yshd']],
            ['n'=>'周道物业','a'=>'zdwy','c'=>$this->arr_diyu['zdwy']]
        ];


        $this->arr_company = [
            ['n'=>'公司A','a'=>'gs_a','l'=>true],
            ['n'=>'公司B','a'=>'gs_b','l'=>true],
            ['n'=>'公司C','a'=>'gs_c','l'=>true],
        ];

        $this->arr_xiangmu = [
            ['n'=>'项目A','a'=>'xm_a','l'=>true],
            ['n'=>'项目B','a'=>'xm_b','l'=>true],
            ['n'=>'项目C','a'=>'xm_c','l'=>true]
        ];

        $this->arr_yt3 = [
            ['n'=>'颂唐机构','a'=>'stjg','c'=>$this->arr_company],
            ['n'=>'市场策略中心','a'=>'scclzx','c'=>$this->arr_company],
            ['n'=>'华麦建筑','a'=>'hmjz','c'=>$this->arr_company],
            ['n'=>'颂唐地产','a'=>'stdc','c'=>$this->arr_company],
            ['n'=>'汉佑房屋','a'=>'hyfw','c'=>$this->arr_company],
            ['n'=>'致秦经纪','a'=>'zqjj','c'=>$this->arr_company],
            ['n'=>'明致置业','a'=>'mzzy','c'=>$this->arr_company],
            ['n'=>'日鑫商业','a'=>'rxsy','c'=>$this->arr_company],
            ['n'=>'颂唐广告','a'=>'stgg','c'=>$this->arr_company],
            ['n'=>'尚晋公关','a'=>'sjgg','c'=>$this->arr_company],
            ['n'=>'元素互动','a'=>'yshd','c'=>$this->arr_company],
            ['n'=>'周道物业','a'=>'ydwy','c'=>$this->arr_company]
        ];*/


        $city = [
            //['n'=>'颂唐机构','a'=>'stjg','l'=>true],
            ['n'=>'上海','a'=>'sh','l'=>true],
            ['n'=>'苏州','a'=>'sz','l'=>true],
            ['n'=>'无锡','a'=>'wx','l'=>true],
            ['n'=>'南京','a'=>'nj','l'=>true],
            ['n'=>'合肥','a'=>'hf','l'=>true],
            ['n'=>'呼和浩特','a'=>'hhht','l'=>true],
        ];

        $yt = [
            ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
            ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
            ['n'=>'尚晋公关','a'=>'sjgg','l'=>true],
            ['n'=>'汉佑房屋','a'=>'hyfw','l'=>true],
            ['n'=>'鸿汉经纪','a'=>'hhjj','l'=>true],
            ['n'=>'明致置业','a'=>'mzzy','l'=>true],
            ['n'=>'日鑫商业','a'=>'rxsy','l'=>true],
            ['n'=>'迈华建筑','a'=>'mhjz','l'=>true],
            ['n'=>'周道物业','a'=>'ydwy','l'=>true],
            ['n'=>'元素互动','a'=>'yshd','l'=>true]
        ];

        $city_yt = [
            ['n'=>'上海','a'=>'sh','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
                ['n'=>'颂唐地产（二）','a'=>'stdc_2','l'=>true],
                ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
                ['n'=>'日鑫商业','a'=>'rxsy','l'=>true],
            ]],
            ['n'=>'苏州','a'=>'sz','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
                ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
                ['n'=>'汉佑房屋','a'=>'hyfw','l'=>true],
                ['n'=>'鸿汉经纪','a'=>'hhjj','l'=>true],
            ]],
            ['n'=>'无锡','a'=>'wx','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
                ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
                ['n'=>'汉佑房屋','a'=>'hyfw','l'=>true],
                ['n'=>'鸿汉经纪','a'=>'hhjj','l'=>true],
            ]],
            ['n'=>'南京','a'=>'nj','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
                ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
            ]],
            ['n'=>'合肥','a'=>'hf','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
                ['n'=>'颂唐广告','a'=>'stgg','l'=>true],
            ]],
            ['n'=>'呼和浩特','a'=>'hhht','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','l'=>true],
            ]],
        ];
        foreach($city as $c){
            $this->city[] = $c['a'];
        }
        foreach($city_yt as $cy){
            foreach($cy['c'] as $cy2){
                $this->yt[$cy['a']][]=$cy2['a'];
            }
        }

        $this->arr1 = [
            ['n'=>'企宣管控中心','a'=>'qxgkzx','c'=>[
                ['n'=>'公司简介','a'=>'gsjj','pm'=>[12=>'all'],'l'=>true],
                ['n'=>'VI应用标准模板','a'=>'vi','pm'=>[12=>'all'],'l'=>true],
                //['n'=>'公司简介','a'=>'gsjj','pm'=>[12=>'all'],'c'=>$city_yt],
                //['n'=>'VI应用标准模板','a'=>'vi','pm'=>[12=>'all'],'c'=>$city_yt]
            ]],
            ['n'=>'行政管控中心','a'=>'xzgkzx','c'=>[
                ['n'=>'公告通知','a'=>'ggtz','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'all','city_zhglb'=>'all']],'c'=>$city_yt],
                ['n'=>'行政管理制度','a'=>'xzglzd','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'all','city_zhglb'=>'all']],'c'=>$city_yt],
                ['n'=>'人事管理制度','a'=>'rsglzd','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'all','city_zhglb'=>'all']],'c'=>$city_yt],
                ['n'=>'管理表单范本','a'=>'glbdfb','pm'=>[12=>'all'],'l'=>true],
                ['n'=>'制度培训模板','a'=>'zdpxmb','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'all','city_zhglb'=>'all']],'c'=>$city_yt],
            ]],
            ['n'=>'财务管控中心','a'=>'cwgkzx','c'=>[
                ['n'=>'财务管理制度','a'=>'cwglzd','pm'=>[12=>['city_cwb'=>'all','city_yt'=>'zjb']],'c'=>$city_yt],
                ['n'=>'财务表单范本','a'=>'cwbdfb','pm'=>[12=>['city_cwb'=>'all','city_yt'=>'zjb']],'c'=>$city_yt]
            ]],
            ['n'=>'法务管控中心','a'=>'fwgkzx','c'=>[
                ['n'=>'合同范本','a'=>'htfb','pm'=>[12=>['city_yt'=>'zjb']],'c'=>$city_yt],
                ['n'=>'信函范本','a'=>'xhfb','pm'=>[12=>['city_yt'=>'zjb']],'c'=>$city_yt],
            ]]
        ];

        $this->arr2 = [
            ['n'=>'颂唐-人才资源中心','a'=>'st-rczyzx','c'=>[
                    ['n'=>'在职员工档案','a'=>'zzygda','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'zjb','city_zhglb'=>'all']],'c'=>$city_yt],
                    ['n'=>'离职员工档案','a'=>'lzygda','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'zjb','city_zhglb'=>'all']],'c'=>$city_yt],
                    ['n'=>'应聘员工档案','a'=>'ypygda','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'zjb','city_zhglb'=>'all']],'c'=>$city_yt],
                    ['n'=>'行业人才档案','a'=>'hyrcda','pm'=>[11=>['city_zhglb'=>'all'],12=>['city_yt'=>'zjb','city_zhglb'=>'all']],'c'=>$city_yt],
                ]
            ],
            ['n'=>'颂唐-客户资源中心','a'=>'st-khzyzx','c'=>[
                ['n'=>'甲方人员档案','a'=>'jfryda','l'=>true]
            ]],
            ['n'=>'颂唐-供应商资源中心','a'=>'st-gyszyzx','c'=>[
                ['n'=>'供应商档案','a'=>'gysda','pm'=>[11=>['yt'=>'zjb'],12=>['yt'=>'zjb']],'c'=>$yt]
            ]],
            ['n'=>'颂唐地产-客户资源中心','a'=>'stdc-khzyzx','c'=>[
                ['n'=>'购房客户档案','a'=>'gfkhda','l'=>true]
            ]],
            ['n'=>'日鑫商业-商家资源中心','a'=>'rxsy-sjzyzx','c'=>[
                ['n'=>'商家档案','a'=>'sjda','l'=>true]
            ]]
        ];

        $this->arr3 = [
            ['n'=>'颂唐地产','a'=>'stdc','c'=>[
                ['n'=>'开发拓展部工具箱','a'=>'kftzbgjx','c'=>[
                    ['n'=>'工作流程规范','a'=>'gzlcgf','pm'=>[
                        12=>[
                            'city'=>
                                [
                                    'stdc/zjb',
                                    'stdc/kftzb/zj',
                                    'stdc/kftzb/jl',
                                    'stdc_2/zjb',
                                    'stdc_2/kftzb/zj',
                                    'stdc_2/kftzb/jl'
                                ]
                            ]
                        ]
                        ,'l'=>true],
                    ['n'=>'工作文件范本','a'=>'gzwjfb','pm'=>[
                        12=>[
                            'city'=>
                                [
                                    'stdc/zjb',
                                    'stdc/kftzb/zj',
                                    'stdc/kftzb/jl',
                                    'stdc_2/zjb',
                                    'stdc_2/kftzb/zj',
                                    'stdc_2/kftzb/jl'
                                ]
                            ]
                        ]
                        ,'l'=>true],
                    ['n'=>'工作文件档案','a'=>'gzwjda','pm'=>[
                        //11=>['city'=>['stdc/zjb','stdc/kftzb','stdc_2/zjb','stdc_2/kftzb']],
                        //12=>['city_child'=>['stdc/zjb','stdc/kftzb','stdc_2/zjb','stdc_2/kftzb']],
                    ],'c'=>$city],
                ]],
                ['n'=>'市场策划部工具箱','a'=>'scchbgjx','c'=>[
                    ['n'=>'工作流程规范','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['stdc/zjb','stdc/scchb','stdc_2/zjb','stdc_2/scchb']]
                    ],'a'=>'gzlcgf','l'=>true],
                    ['n'=>'工作报告模板','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['stdc/zjb','stdc/scchb','stdc_2/zjb','stdc_2/scchb']]
                    ],'a'=>'gzbgmb','l'=>true],
                    ['n'=>'工作表单范本','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['stdc/zjb','stdc/scchb','stdc_2/zjb','stdc_2/scchb']]
                    ],'a'=>'gzbdfb','l'=>true],
                    ['n'=>'产品定位资源库','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['stdc/zjb','stdc/scchb','stdc_2/zjb','stdc_2/scchb']]
                    ],'a'=>'cpdwzyk','l'=>true],
                    ['n'=>'工作报告档案库','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['stdc/zjb','stdc/scchb','stdc/kftzb','stdc_2/zjb','stdc_2/scchb','stdc_2/kftzb']]
                    ],'a'=>'gzbgdak','l'=>true]
                ]],
                ['n'=>'销售业务部工具箱','a'=>'xsywbgjx','c'=>[
                    ['n'=>'工作流程规范','pm'=>[
                        12=>['city'=>['stdc/zjb','stdc_2/zjb'],'stdc_xsywb'=>['xmzj','xmjl','xmzg']]
                    ],'a'=>'gzlcgf','l'=>true],
                    ['n'=>'工作报告范本','pm'=>[
                        12=>['city'=>['stdc/zjb','stdc_2/zjb'],'stdc_xsywb'=>['xmzj','xmjl','xmzg']]
                    ],'a'=>'gzbgfb','l'=>true],
                    ['n'=>'工作表单范本','pm'=>[
                        12=>['city'=>['stdc/zjb','stdc_2/zjb'],'stdc_xsywb'=>['xmzj','xmjl','xmzg']]
                    ],'a'=>'gzbdfb','l'=>true],
                    ['n'=>'活动方案资源库','pm'=>[
                        12=>['city'=>['stdc/zjb','stdc_2/zjb','stdc/scchb','stdc_2/scchb'],'stdc_xsywb'=>['xmzj','xmjl','xmzg']]
                    ],'a'=>'hdfazyk','l'=>true]
                ]]
            ]],
            ['n'=>'颂唐广告','a'=>'stgg','c'=>[
                    ['n'=>'AE策划部工具箱','a'=>'aechbgjx','c'=>[
                        ['n'=>'工作流程规范','a'=>'gzlcgf','pm'=>[12=>['city'=>['stgg']]],'l'=>true],
                        ['n'=>'工作报告模板','a'=>'gzbgmb','pm'=>[12=>['city'=>['stgg']]],'l'=>true],
                        ['n'=>'工作表单范本','a'=>'gzbdfb','pm'=>[12=>['city'=>['stgg']]],'l'=>true]
                    ]],
                    ['n'=>'创作部工具箱','a'=>'czbgjx','c'=>[
                        ['n'=>'工作流程规范','a'=>'gzlcgf','pm'=>[12=>['city'=>['stgg']]],'l'=>true],
                        ['n'=>'工作报告模板','a'=>'gzbgmb','pm'=>[12=>['city'=>['stgg']]],'l'=>true],
                        ['n'=>'工作表单范本','a'=>'gzbdfb','pm'=>[12=>['city'=>['stgg']]],'l'=>true]
                    ]]
                ]
            ],
            ['n'=>'日鑫商业','a'=>'rxsy','c'=>[
                ['n'=>'商业策划部工具箱','a'=>'sychbgjx','c'=>[
                    ['n'=>'工作流程规范','a'=>'gzlcgf','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['rxsy/zjb','rxsy/sychb']]
                    ],'l'=>true],
                    ['n'=>'工作报告模板','a'=>'gzbgmb','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['rxsy/zjb','rxsy/sychb']]
                    ],'l'=>true],
                    ['n'=>'工作表单范本','a'=>'gzbdfb','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['rxsy/zjb','rxsy/sychb']]
                    ],'l'=>true],
                    ['n'=>'商业定位资源库','a'=>'sydwzyk','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['rxsy/zjb','rxsy/sychb']]
                    ],'l'=>true],
                    ['n'=>'工作报告档案库','a'=>'gzbgdak','pm'=>[
                        11=>['single'=>'stjg/scclzx/zj'],
                        12=>['single'=>'stjg/scclzx/zj','city'=>['rxsy/zjb','rxsy/sychb','rxsy/pptzb']]
                    ],'l'=>true]
                ]],
                ['n'=>'商业招商部工具箱','a'=>'syzsbgjx','pm'=>[
                    12=>['city'=>['rxsy/zjb'],'rxsy_syzsb'=>['xmzj','xmjl','xmzg']]
                    ],'c'=>[
                        ['n'=>'工作流程规范','a'=>'gzlcgf','l'=>true],
                        ['n'=>'工作文件模板','a'=>'gzwjmb','l'=>true],
                        ['n'=>'工作表单范本','a'=>'gzbdgf','l'=>true]
                    ]
                ]
            ]],
            /*['n'=>'迈华建筑','a'=>'mhjz','c'=>[]],
            ['n'=>'汉佑房屋','a'=>'hyfw','c'=>[]],
            ['n'=>'鸿汉经纪','a'=>'hhjj','c'=>[]],
            ['n'=>'明致置业','a'=>'mzzy','c'=>[]],
            ['n'=>'尚晋公关','a'=>'sjgg','c'=>[]],
            ['n'=>'元素互动','a'=>'yshd','c'=>[]],
            ['n'=>'周道物业','a'=>'zdwy','c'=>[]]*/
        ];


        $this->arr4 = [
            ['n'=>'执行项目资料中心','a'=>'zxxmzlzx','c'=>[
                ['n'=>'颂唐地产','a'=>'stdc','pm'=>[
                    12=>'all'
                ],'c'=>$xiangmu],
                ['n'=>'颂唐广告','a'=>'stgg','pm'=>[
                    12=>'all'
                ],'c'=>$xiangmu],
                ['n'=>'日鑫商业','a'=>'rxsy','pm'=>[
                    12=>'all'
                ],'c'=>$xiangmu],
                /*['n'=>'明致置业','a'=>'mzzy','pm'=>[
                    12=>'all'
                ],'c'=>$xiangmu],*/
            ]],
            ['n'=>'历史项目资料中心','a'=>'lsxmzlzx','pm'=>[12=>'all'],'c'=>$xiangmu]
        ];


        $this->arr5 = [
            ['n'=>'公司推荐学习资料库','a'=>'gstjxxzlk','pm'=>[12=>'all'],'l'=>true],
            ['n'=>'员工推荐学习资料库','a'=>'ygtjxxzlk','pm'=>[11=>'all',12=>'all'],'l'=>true],
        ];
    }
    public function install() {
        try {
            $exist = Dir::find()->one();
            if($exist){
                throw new yii\base\Exception('Dir has installed');
            }

            self::initDirTop($this->arr0);

            $this->initDir($this->arr1,1,2,1);

            $this->initDir($this->arr2,2,2,2);

            $this->initDir($this->arr3,3,2,3);

            $this->initDir($this->arr4,4,2,4);

            $this->initDir($this->arr5,5,2,5);
            return true;
        }catch (\Exception $e)
        {
            echo  'Dir install failed<br />';
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            /*echo '<br/><br/>';
            var_dump($e);
            echo '<br/><br/>';
            var_dump($errorInfo);*/

            //throw new \Exception($message, $errorInfo, (int) $e->getCode(), $e);
            return false;
        }
    }

    public function initDir($arr,$pid,$level,$type,$pm=[],$posRoute=''){
        $sqlbase = "INSERT IGNORE INTO `dir`(`name`,`alias`,`p_id`,`type`,`is_leaf`,`level`,`is_last`,`ord`,`status`)
                VALUES";
        $ord = 99;
        $i = 1;
        foreach($arr as $a){
            $isLast = $i == count($arr)?1:0;
            $leaf = isset($a['l']) && $a['l']==1?1:0;
            $name = isset($a['n']) && $a['n']!=''?$a['n']:'默认名称';
            $alias = isset($a['a']) && $a['a']!=''?$a['a']:'默认别名';
            /*if(isset($a['pmx']))//继承父级目录
                $pm = $a['pmx'];*/
           /* if(isset($a['pmx2']))//当前已经为叶级目录
                $pm = $a['pmx2'];*/
            if(isset($a['pm']) && !empty($a['pm']))//传递给子级目录
                $pm = $a['pm'];

            /*if(in_array($alias,$this->ytArr))
                $yt = $alias;

            if(in_array($alias,$this->localArr))
                $local = $alias;

            if(in_array($alias,$this->positionArr))
                $position = $alias;*/

            $sql = $sqlbase."('".$name."','".$alias."',$pid,$type,$leaf,$level,$isLast,$ord,1)";
            $cmd = Yii::$app->db->createCommand($sql);
            $cmd->execute();
            $lastId = Yii::$app->db->lastInsertID;
            $curPosRoute = '';
            if($leaf==0){
                if(isset($a['c']) && !empty($a['c'])){
                    if(isset($pm) && !empty($pm)){
                        if($posRoute!=''){
                            $curPosRoute = $posRoute.'/'.$alias;
                        }
                        if( $curPosRoute==''){
                            $curPosRoute = 'stjg';
                        }
                    }
                    /*echo 'leaf0<br/><br/>';
                    var_dump($curPosRoute);
                    echo '<br/><br/>';*/
                    $this->initDir($a['c'],$lastId,$level+1,$type,$pm,$curPosRoute);
                    //$this->initDir($a['c'],$lastId,$level+1,$type,$pm,$yt,$local,$position);
                }
            }else{
                if(isset($pm) && !empty($pm) && $posRoute==''){
                    $curPosRoute = 'stjg';
                }else{
                    $curPosRoute = $posRoute.'/'.$alias;
                }
                /*echo 'leaf1<br/><br/>';
                var_dump($curPosRoute);
                echo '<br/><br/>';*/
                $this->initPm($lastId,$pm,$curPosRoute);
                //$this->initPm($lastId,$pm,$yt,$local,$position);
            }


            $ord--;
            $i++;
        }
    }

    public function insert1(){
        try {
            $parDirRoute1 = '企业运营中心/行政管控中心/公告通知';
            $parDirRoute2 = '企业运营中心/行政管控中心/行政管理制度';
            $parDirRoute3 = '企业运营中心/行政管控中心/制度培训模板';

            $pid1 = DirFunc::getIdByRoute($parDirRoute1);
            $pid2 = DirFunc::getIdByRoute($parDirRoute2);
            $pid3 = DirFunc::getIdByRoute($parDirRoute3);
/*            var_dump($pid1);
            echo '<br/>';
            var_dump($pid2);
            echo '<br/>';
            var_dump($pid3);
            echo '<br/>';*/

            $dirExist1 = Dir::find()->where(['p_id'=>$pid1,'alias'=>'stjg'])->one();
            $dirExist2 = Dir::find()->where(['p_id'=>$pid2,'alias'=>'stjg'])->one();
            $dirExist3 = Dir::find()->where(['p_id'=>$pid3,'alias'=>'stjg'])->one();


/*            var_dump($dirExist1);
            echo '<br/>';
            var_dump($dirExist2);
            echo '<br/>';
            var_dump($dirExist3);
            echo '<br/>';*/

            if($dirExist1!=NULL ||$dirExist2!=NULL ||$dirExist3!=NULL ){
                throw new yii\base\Exception('Dir has insert  (1)');
            }

            $this->initDir([['n'=>'颂唐机构','a'=>'stjg','pm'=>[11=>['city_zhglb'=>'all'],12=>'all'],'l'=>true]],$pid1,4,1);
            $this->initDir([['n'=>'颂唐机构','a'=>'stjg','pm'=>[11=>['city_zhglb'=>'all'],12=>'all'],'l'=>true]],$pid2,4,1);
            $this->initDir([['n'=>'颂唐机构','a'=>'stjg','pm'=>[11=>['city_zhglb'=>'all'],12=>'all'],'l'=>true]],$pid3,4,1);

            echo 'insert success';exit;
        }catch (\Exception $e)
        {
            echo  'Dir insert (1) failed<br />';
            $message = $e->getMessage() . "\n";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            echo $message;
            /*echo '<br/><br/>';
            var_dump($e);
            echo '<br/><br/>';
            var_dump($errorInfo);*/

            //throw new \Exception($message, $errorInfo, (int) $e->getCode(), $e);
            return false;
        }
    }

    public function initPm($dir_id,$pmArr,$posRoute){
        if(!empty($pmArr)){
            $pAll = [];
            $positionAll = Position::find()->where(['is_leaf'=>1])->all();
            foreach($positionAll as $pOne){
                $pAll[] = $pOne->id;
            }
            $sqlBase = "INSERT IGNORE INTO `position_dir_permission`(`position_id`,`dir_id`,`type`) VALUES";
            foreach($pmArr as $k=>$pmItem){
                if($pmItem=='all'){
                    $sql = $sqlBase;
                    $sqlValueArr = [];
                    foreach($pAll as $p){
                        $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                    }
                    $sql .= implode(',',$sqlValueArr);
                    $cmd = Yii::$app->db->createCommand($sql);
                    $cmd->execute();
                }elseif(is_array($pmItem) && !empty($pmItem)){
                    foreach($pmItem as $type => $pmItem2){
                        if($type == 'single'){
                            $posId = PositionFunc::getIdByAlias($pmItem2);
                            /*var_dump($posRoute2);echo '<Br/><br/>';
                            exit;*/
                            $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                            if(!empty($pArr)){
                                $sqlValueArr = [];
                                foreach($pArr as $p){
                                    $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                }
                                $sql = $sqlBase . implode(',',$sqlValueArr);
                                $cmd = Yii::$app->db->createCommand($sql);
                                $cmd->execute();
                            }
                        }elseif($type == 'city_zhglb'){
                            if($pmItem2=='all'){

                                $posRoute2 = '';
                                $posTmp = explode('/',$posRoute);
                                if(count($posTmp)>=2){
                                    $posRoute2 = $posTmp[0].'/'.$posTmp[1].'/zhglb';
                                }

                                $posId = PositionFunc::getIdByAlias($posRoute2);

                                $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                if(!empty($pArr)){
                                    $sqlValueArr = [];
                                    foreach($pArr as $p){
                                        $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                    }
                                    $sql = $sqlBase . implode(',',$sqlValueArr);
                                    $cmd = Yii::$app->db->createCommand($sql);
                                    $cmd->execute();
                                }
                            }
                        }elseif($type == 'city_cwb'){
                            if($pmItem2=='all'){
                                $posTmp = explode('/',$posRoute);
                                $posRoute2 = $posTmp[0].'/'.$posTmp[1].'/cwb';
                                $posId = PositionFunc::getIdByAlias($posRoute2);
                                /*var_dump($posRoute2);echo '<Br/><br/>';
                                exit;*/
                                $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                if(!empty($pArr)){
                                    $sqlValueArr = [];
                                    foreach($pArr as $p){
                                        $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                    }
                                    $sql = $sqlBase . implode(',',$sqlValueArr);
                                    $cmd = Yii::$app->db->createCommand($sql);
                                    $cmd->execute();
                                }
                            }
                        }elseif($type == 'stdc_xsywb'){
                            $pArr =[];

                            foreach($this->city as $cityAlias){
                                $posRoute2 = 'stjg/'.$cityAlias.'/stdc/xsywb';
                                $posId = PositionFunc::getIdByAlias($posRoute2);
                                if($posId!=false){
                                    $anchangList = Position::find()->where(['p_id'=>$posId,'is_leaf'=>0])->all();
                                    foreach($anchangList as $ac){
                                        foreach($pmItem2 as $pi2){
                                            $posRoute3 = $posRoute2.'/'.$ac->alias.'/'.$pi2;
                                            $posId3 = PositionFunc::getIdByAlias($posRoute3);
                                            if($posId3)
                                                $pArr[] = $posId3;
                                        }
                                    }
                                }
                                $posRoute2 = 'stjg/'.$cityAlias.'/stdc_2/xsywb';
                                $posId = PositionFunc::getIdByAlias($posRoute2);
                                if($posId!=false){
                                    $anchangList = Position::find()->where(['p_id'=>$posId,'is_leaf'=>0])->all();
                                    foreach($anchangList as $ac){
                                        foreach($pmItem2 as $pi2){
                                            $posRoute3 = $posRoute2.'/'.$ac->alias.'/'.$pi2;
                                            $posId3 = PositionFunc::getIdByAlias($posRoute3);
                                            if($posId3)
                                                $pArr[] = $posId3;
                                        }
                                    }
                                }
                            }
                            if(!empty($pArr)){
                                $sqlValueArr = [];
                                foreach($pArr as $p){
                                    $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                }
                                $sql = $sqlBase . implode(',',$sqlValueArr);
                                $cmd = Yii::$app->db->createCommand($sql);
                                $cmd->execute();
                            }
                        }elseif($type == 'rxsy_syzsb'){
                            $pArr =[];
                            foreach($this->city as $cityAlias){
                                $posRoute2 = 'stjg/'.$cityAlias.'/rxsy/syzsb';
                                $posId = PositionFunc::getIdByAlias($posRoute2);
                                if($posId!=false){
                                    $anchangList = Position::find()->where(['p_id'=>$posId,'is_leaf'=>0])->all();
                                    foreach($anchangList as $ac){
                                        foreach($pmItem2 as $pi2){
                                            $posRoute3 = $posRoute2.'/'.$ac->alias.'/'.$pi2;
                                            $posId3 = PositionFunc::getIdByAlias($posRoute3);
                                            if($posId3)
                                                $pArr[] = $posId3;
                                        }
                                    }
                                }
                            }
                            if(!empty($pArr)){
                                $sqlValueArr = [];
                                foreach($pArr as $p){
                                    $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                }
                                $sql = $sqlBase . implode(',',$sqlValueArr);
                                $cmd = Yii::$app->db->createCommand($sql);
                                $cmd->execute();
                            }
                        }elseif($type == 'city_yt'){


                            if($pmItem2=='all'){
                                /*var_dump($dir_id);echo '<Br/><br/>';
                                var_dump($posRoute);echo '<Br/><br/>';
                                var_dump($posRoute);echo '<Br/><br/>';*/

                                $posId = PositionFunc::getIdByAlias($posRoute);
                                //var_dump($posId);echo '<Br/><br/>';
                                //exit;
                                $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                /*if($posRoute=='stjg/sh/stdc'){
                                    $posRoute='stjg/sh/stdc_2';
                                    $posId2 = PositionFunc::getIdByAlias($posRoute);
                                    $pArr2 = PositionFunc::getAllLeafChildrenIds($posId2);

                                    $pArr = array_merge($pArr,$pArr2);
                                }*/

                                if(!empty($pArr)){
                                    $sqlValueArr = [];
                                    foreach($pArr as $p){
                                        $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                    }
                                    $sql = $sqlBase . implode(',',$sqlValueArr);
                                    $cmd = Yii::$app->db->createCommand($sql);
                                    $cmd->execute();
                                }
                            }elseif($pmItem2 == 'zjb'){
                                $posRoute .='/zjb';
                                $posId = PositionFunc::getIdByAlias($posRoute);
                                //var_dump($posId);echo '<Br/><br/>';
                                //exit;
                                $pArr = PositionFunc::getAllLeafChildrenIds($posId);

                                /*if($posRoute=='stjg/sh/stdc/zjb'){
                                    $posRoute='stjg/sh/stdc_2/zjb';
                                    $posId2 = PositionFunc::getIdByAlias($posRoute);
                                    $pArr2 = PositionFunc::getAllLeafChildrenIds($posId2);

                                    $pArr = array_merge($pArr,$pArr2);
                                }*/

                                if(!empty($pArr)){
                                    $sqlValueArr = [];
                                    foreach($pArr as $p){
                                        $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                    }
                                    $sql = $sqlBase . implode(',',$sqlValueArr);
                                    $cmd = Yii::$app->db->createCommand($sql);
                                    $cmd->execute();
                                }
                            }
                        }elseif($type == 'yt'){
                            /*if($pmItem2=='all'){

                                $posId = PositionFunc::getIdByAlias($posRoute);
                                //var_dump($posId);echo '<Br/><br/>';
                                //exit;
                                $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                if(!empty($pArr)){
                                    $sqlValueArr = [];
                                    foreach($pArr as $p){
                                        $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                    }
                                    $sql = $sqlBase . implode(',',$sqlValueArr);
                                    $cmd = Yii::$app->db->createCommand($sql);
                                    $cmd->execute();
                                }
                            }else*/if($pmItem2 == 'zjb'){
                                foreach($this->city as $cityAlias){
                                    $posRoute2 = str_replace('stjg/','stjg/'.$cityAlias.'/',$posRoute);
                                    $posRoute2 = $posRoute2 .'/zjb';
                                    //var_dump($posRoute2);echo '<Br/><br/>';exit;


                                    $posId = PositionFunc::getIdByAlias($posRoute2);
                                    //var_dump($posId);echo '<Br/><br/>';
                                    //exit;
                                    $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                    if(!empty($pArr)){
                                        $sqlValueArr = [];
                                        foreach($pArr as $p){
                                            $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                        }
                                        $sql = $sqlBase . implode(',',$sqlValueArr);
                                        $cmd = Yii::$app->db->createCommand($sql);
                                        $cmd->execute();
                                    }
                                }
                            }
                        }elseif($type == 'city'){
                            if(is_array($pmItem2) && !empty($pmItem2)){
                                foreach($pmItem2 as $pi2){
                                    /*if(in_array($pi2 ,
                                        ['stdc/zjb','stgg/zjb','rxsy/zjb','stdc/scchb','stdc/kftzb/zj','stdc/kftzb/fzj','stdc/kftzb/jl','stdc/kftzb','stgg','rxsy/sychb','rxsy/pptzb','rxsy/syzsb/zjl','rxsy/syzsb/fzjl','rxsy/syzsb/zj']
                                    )){*/
                                        foreach($this->city as $cityAlias){
                                            //$posRoute2 = $posRoute.'/'.$cityAlias.'/'.$pi2;
                                            $posRoute2 = 'stjg/'.$cityAlias.'/'.$pi2;
                                            //var_dump($posRoute2);echo '<Br/><br/>';exit;
                                            $posId = PositionFunc::getIdByAlias($posRoute2);
                                            //var_dump($posId);echo '<Br/><br/>';
                                            //exit;
                                            $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                            if(!empty($pArr)){
                                                $sqlValueArr = [];
                                                foreach($pArr as $p){
                                                    $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                                }
                                                $sql = $sqlBase . implode(',',$sqlValueArr);
                                                $cmd = Yii::$app->db->createCommand($sql);
                                                $cmd->execute();
                                            }
                                        }
                                    /*}*/
                                }
                            }elseif(in_array($pmItem2 , ['stdc/zjb','stgg/zjb','rxsy/zjb'])){
                                foreach($this->city as $cityAlias){
                                    $posRoute2 = $posRoute.'/'.$cityAlias.'/'.$pmItem2;
                                    //var_dump($posRoute2);echo '<Br/><br/>';exit;
                                    $posId = PositionFunc::getIdByAlias($posRoute2);
                                    //var_dump($posId);echo '<Br/><br/>';
                                    //exit;
                                    $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                    if(!empty($pArr)){
                                        $sqlValueArr = [];
                                        foreach($pArr as $p){
                                            $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                        }
                                        $sql = $sqlBase . implode(',',$sqlValueArr);
                                        $cmd = Yii::$app->db->createCommand($sql);
                                        $cmd->execute();
                                    }
                                }
                            }
                        }elseif($type == 'city_child'){
                            /*var_dump($posRoute);echo '<br/>===<br/>';
                            var_dump($pmItem2);exit;*/
                            if(is_array($pmItem2) && !empty($pmItem2)){
                                foreach($pmItem2 as $pi2){
                                    /*if(in_array($pi2 ,
                                        ['stdc/zjb','stgg/zjb','rxsy/zjb','stdc/scchb','stdc/kftzb/zj','stdc/kftzb/fzj','stdc/kftzb/jl','stdc/kftzb','stgg','rxsy/sychb','rxsy/pptzb','rxsy/syzsb/zjl','rxsy/syzsb/fzjl','rxsy/syzsb/zj']
                                    )){*/
                                        $posRoute2 = $posRoute.'/'.$pi2;
                                        //var_dump($posRoute2);echo '<Br/><br/>';exit;
                                        $posId = PositionFunc::getIdByAlias($posRoute2);
                                        //var_dump($posId);echo '<Br/><br/>';
                                        //exit;
                                        $pArr = PositionFunc::getAllLeafChildrenIds($posId);
                                        if(!empty($pArr)){
                                            $sqlValueArr = [];
                                            foreach($pArr as $p){
                                                $sqlValueArr[] = '("'.$p.'","'.$dir_id.'","'.$k.'")';
                                            }
                                            $sql = $sqlBase . implode(',',$sqlValueArr);
                                            $cmd = Yii::$app->db->createCommand($sql);
                                            $cmd->execute();
                                    }
                                    /*}*/
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    public static function initDirTop($arr){
        $sqlbase = "INSERT IGNORE INTO `dir`(`name`,`alias`,`p_id`,`type`,`is_leaf`,`level`,`is_last`,`ord`,`status`)
                VALUES";
        $ord = 99;
        $i = 1;
        foreach($arr as $a){
            $isLast = $i == count($arr)?1:0;
            $leaf = isset($a['l']) && $a['l']==1?1:0;
            $name = isset($a['n']) && $a['n']!=''?$a['n']:'默认名称';
            $alias = isset($a['a']) && $a['a']!=''?$a['a']:'默认别名';
            $sql = $sqlbase."('".$name."','".$alias."',0,$i,$leaf,1,$isLast,$ord,1)";
            $cmd = Yii::$app->db->createCommand($sql);
            $cmd->execute();
            
            $ord--;
            $i++;
        }
    }
}