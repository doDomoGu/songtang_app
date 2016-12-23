<?php
namespace yun\components;

use yun\models\GroupDirPermission;
use yun\models\GroupUser;
use ucenter\models\User;
use yun\models\Group;
use yii\base\Component;
use Yii;

class GroupFunc extends Component {
    //根据一个user_id 获取他所在的所有组（有效）id集合
    public static function getGroupsIn($uid){
        $groupUser = GroupUser::find()->where(['user_id'=>$uid])->all();
        $groupIds = [];
        $groupIdsTemp = [];
        foreach ($groupUser as $gu){
            $groupIdsTemp[] = $gu->group_id;
        }

        if(!empty($groupIdsTemp)){
            $groups = Group::find()->where(['in','id',$groupIdsTemp])->andWhere(['status'=>1])->all();
            foreach($groups as $g){
                $groupIds[] = $g->id;
            }
        }
        return $groupIds;

    }

    //根据user_id 获取他在群组中赋予的权限集合
    public static function getOneDirPermissionTypes($uid,$dir_id){
        $groupIds = self::getGroupsIn($uid);
        $pm = GroupDirPermission::find()->where(['in','group_id',$groupIds])->andWhere(['dir_id'=>$dir_id])->all();

        $typeArr = [];
        if(!empty($pm)){
            foreach($pm as $p)
                $typeArr[] = $p->type;
        }

        return $typeArr;
    }

}