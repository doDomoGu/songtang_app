<?php

namespace yun\models;
use Yii;

class DirPermission extends \yii\db\ActiveRecord
{

    public static function getDb(){
        return Yii::$app->db_yun;
    }


    const MODE_ALLOW            = 1;   //模式 允许
    const MODE_DENY             = 2;   //模式 禁止

    const OPERATION_UPLOAD      = 1;   //上传操作
    const OPERATION_DOWNLOAD    = 2;   //下载操作(预览)
    const OPERATION_COOP        = 3;   //协同操作
    //const OPERATION_DELETE      = 4;   //删除操作

    const TYPE_ALL              = 1;   //全体职员
    const TYPE_AREA             = 2;   //地区 （通配）
    const TYPE_BUSINESS         = 3;   //业态 （通配）
    //const TYPE_DEPARTMENT       = 4;   //部门 （通配）
    //const TYPE_POSITION         = 5;   //职位 （通配）
    //const TYPE_COMBINE          = 6;   // 前四个的任意组合
    const TYPE_GROUP            = 7;   //权限组
    const TYPE_USER             = 8;   //单独的USER_ID


    public function rules()
    {
        return [
            [['dir_id', 'area_id', 'business_id','department_id','position_id','group_id','user_id','type','operation','mode'], 'integer'],
        ];
    }

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
     * 检测是否允许执行所选操作
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
                    if(self::isInRange($a)){
                        $isAllow = true;
                        break;
                    }
                }
            }

            $denyList = self::find()->where(['dir_id'=>$dir_id,'operation'=>$operation_id,'mode'=>self::MODE_DENY])->all();
            if(!empty($denyList)){
                foreach($denyList as $d){
                    if(self::isInRange($d)){
                        $isAllow = false;
                        break;
                    }
                }
            }
        }
        return $isAllow;
    }

}