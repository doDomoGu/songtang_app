<?php

namespace yun\models;
use Yii;
use yun\components\DirFunc;

class DirPermission extends \yii\db\ActiveRecord
{

    public static function getDb(){
        return Yii::$app->db_yun;
    }


    const MODE_ALLOW            = 1;   //模式 允许
    const MODE_DENY             = 2;   //模式 禁止
    const MODE_ALLOW_CN         = '允许';
    const MODE_DENY_CN          = '禁止';



    const OPERATION_UPLOAD      = 1;   //上传操作
    const OPERATION_DOWNLOAD    = 2;   //下载操作(预览)
    const OPERATION_COOP        = 3;   //协同操作
    //const OPERATION_DELETE      = 4;   //删除操作
    const OPERATION_UPLOAD_CN   = '上传';
    const OPERATION_DOWNLOAD_CN = '下载';
    const OPERATION_COOP_CN     = '协同';


    const TYPE_ALL              = 1;   //全体职员
    const TYPE_AREA             = 2;   //地区 （通配）
    const TYPE_BUSINESS         = 3;   //业态 （通配）
    //const TYPE_DEPARTMENT       = 4;   //部门 （通配）
    //const TYPE_POSITION         = 5;   //职位 （通配）
    //const TYPE_COMBINE          = 6;   // 前四个的任意组合
    const TYPE_GROUP            = 7;   //权限组
    const TYPE_USER             = 8;   //单独的USER_ID

    const TYPE_ALL_CN              = '全体职员';   //全体职员
    const TYPE_AREA_CN             = '通配地区';   //地区 （通配）
    const TYPE_BUSINESS_CN         = '通配业态';   //业态 （通配）
    //const TYPE_DEPARTMENT_CN       = 4;   //部门 （通配）
    //const TYPE_POSITION_CN         = 5;   //职位 （通配）
    //const TYPE_COMBINE_CN          = 6;   // 前四个的任意组合
    const TYPE_GROUP_CN            = '权限组';   //权限组
    const TYPE_USER_CN             = '单一职员';   //单独的USER_ID


    public function rules()
    {
        return [
            [['dir_id', 'area_id', 'business_id','department_id','position_id','group_id','user_id','type','operation','mode'], 'integer'],
        ];
    }

    public static function getTypeItems(){
        return [
            self::TYPE_ALL => self::TYPE_ALL_CN,
            self::TYPE_AREA => self::TYPE_AREA_CN,
            self::TYPE_BUSINESS => self::TYPE_BUSINESS_CN,
            /*self::TYPE_GROUP => self::TYPE_GROUP_CN,
            self::TYPE_USER => self::TYPE_USER_CN,*/
        ];
    }

    public static function getModeItems(){
        return [
            self::MODE_ALLOW => self::MODE_ALLOW_CN,
            self::MODE_DENY => self::MODE_DENY_CN
        ];
    }

    public static function getOperationItems(){
        return [
            self::OPERATION_UPLOAD => self::OPERATION_UPLOAD_CN,
            self::OPERATION_DOWNLOAD => self::OPERATION_DOWNLOAD_CN,
            self::OPERATION_COOP => self::OPERATION_COOP_CN
        ];
    }

    public static function getModeName($mode){
        if($mode==self::MODE_ALLOW){
            $return = self::MODE_ALLOW_CN;
        }else if($mode==self::MODE_DENY){
            $return = self::MODE_DENY_CN;
        }else {
            $return = 'N/A';
        }
        return $return;
    }

    public static function getOperationName($oper){
        if($oper==self::OPERATION_UPLOAD){
            $return = self::OPERATION_UPLOAD_CN;
        }else if($oper==self::OPERATION_DOWNLOAD){
            $return = self::OPERATION_DOWNLOAD_CN;
        }else if($oper==self::OPERATION_COOP){
            $return = self::OPERATION_COOP_CN;
        }else {
            $return = 'N/A';
        }
        return $return;
    }

/*    public static function getModeName($mode){
        if($mode==self::MODE_ALLOW){
            $return = self::MODE_ALLOW_CN;
        }else if($mode==self::MODE_DENY){
            $return = self::MODE_DENY_CN;
        }else {
            $return = 'N/A';
        }
        return $return;
    }*/

    /*
     * 检测当前用户是否在这个范围里
     * 参数 dm : 一条dir_permission记录
     * 参数 user: 用户  默认为当前登录用户
     */
    private static function isInRange($dm,$user=false){
        $return = false;
        if($user===false)
            $user = Yii::$app->user->identity;
        switch($dm->type){
            case self::TYPE_ALL:
                $return = true;
                break;
            case self::TYPE_AREA:
                if($dm->area_id>0 && $dm->area_id == $user->aid)
                    $return = true;
                break;
            case self::TYPE_BUSINESS:
                if($dm->business_id>0 && $dm->business_id == $user->bid)
                    $return = true;
                break;
        }

        return $return;
    }

    /*
     * 检测目录是否允许执行所选操作
     * 参数 dir_id : 目录ID
     * 参数 operation_id : 操作类型
     * 参数 user: 用户  默认为当前登录用户
     */
    public static function isAllow($dir_id,$operation_id,$user=false){
        $isAllow = false;
        if(Yii::$app->controller->isAdminAuth){
            $isAllow = true;
        }else{
            //$dir_id = $file->dir_id;
            //$flag = $file->flag; //flag = 1 普通  flag = 2 私有
            $allowList = self::find()->where(['dir_id'=>$dir_id,'operation'=>$operation_id,'mode'=>self::MODE_ALLOW])->all();
            if(!empty($allowList)){
                foreach($allowList as $a){
                    if(self::isInRange($a,$user)){
                        $isAllow = true;
                        break;
                    }
                }
            }

            $denyList = self::find()->where(['dir_id'=>$dir_id,'operation'=>$operation_id,'mode'=>self::MODE_DENY])->all();
            if(!empty($denyList)){
                foreach($denyList as $d){
                    if(self::isInRange($d,$user)){
                        $isAllow = false;
                        break;
                    }
                }
            }
        }
        return $isAllow;
    }

    /*
     * 检测文件的属性是否与属性限制条件相一致
     * 参数 file_id : 文件ID
     * 参数 attr_limit : 文件属性限制类型
     * 参数 user: 用户  默认为当前登录用户
     */
    public static function isFileAttributeAccorded($file_id,$attr_limit,$user=false){
        $return = false;
        if($user===false)
            $user = Yii::$app->user->identity;
        switch($attr_limit){
            case Dir::ATTR_LIMIT_ALL:
                $return = true;
                break;
            case Dir::ATTR_LIMIT_AREA:
                $fileAttr = FileAttribute::find()->where(['file_id'=>$file_id,'attr_type'=>Attribute::TYPE_AREA])->one();
                if($fileAttr){
                    $attr = $fileAttr->attr_id;
                    if($attr==Attribute::AREA_DEFAULT || $attr==$user->aid){
                        $return = true;
                    }
                }
                break;
            case Dir::ATTR_LIMIT_BUSINESS:
                $fileAttr = FileAttribute::find()->where(['file_id'=>$file_id,'attr_type'=>Attribute::TYPE_BUSINESS])->one();
                if($fileAttr){
                    $attr = $fileAttr->attr_id;
                    if($attr==Attribute::BUSINESS_DEFAULT || $attr==$user->bid){
                        $return = true;
                    }
                }
                break;
            case Dir::ATTR_LIMIT_AREA_BUSINESS:
                $areaReturn = false;
                $businessReturn = false;
                $fileAttr = FileAttribute::find()->where(['file_id'=>$file_id,'attr_type'=>Attribute::TYPE_BUSINESS])->one();
                if($fileAttr){
                    $attr = $fileAttr->attr_id;
                    if($attr==Attribute::BUSINESS_DEFAULT || $attr==$user->bid){
                        $areaReturn = true;
                    }
                }
                if($areaReturn){
                    $fileAttr = FileAttribute::find()->where(['file_id'=>$file_id,'attr_type'=>Attribute::TYPE_AREA])->one();
                    if($fileAttr){
                        $attr = $fileAttr->attr_id;
                        if($attr==Attribute::AREA_DEFAULT || $attr==$user->aid){
                            $businessReturn = true;
                        }
                    }
                    if($businessReturn){
                        $return = true;
                    }
                }
                break;
        }

        return $return;
    }
    /*
     * 检测文件是否允许执行所选操作
     * 参数 dir_id : 目录ID
     * 参数 file_id : 文件ID  (dir_id 和 file_id 应该已经检验过 存在且 状态正常 此处不再做检测）
     * 参数 attr_limit : 目录对文件属性的限制
     * 参数 operation_id : 操作类型
     * 参数 user: 用户  默认为当前登录用户
     */
    public static function isFileAllow($dir_id,$file_id,$attr_limit,$operation_id,$user=false){
        $isAllow = false;
        if(Yii::$app->controller->isAdminAuth){
            $isAllow = true;
        }else{
            if(self::isAllow($dir_id,$operation_id,$user)){
                $isAllow = self::isFileAttributeAccorded($file_id,$attr_limit,$user);
            }
        }
        return $isAllow;
    }

    /*
     * 获取 权限列表
     */
    public static function getPmList($p_id=0){
        //$ids = DirFunc::getChildrens($p_id);
        $arr = [];
        $list = DirPermission::find()->all();
        foreach($list as $l){
            $arr[$l->dir_id][] = $l->attributes;
        }
        return $arr;
    }

}