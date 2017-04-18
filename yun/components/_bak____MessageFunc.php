<?php
namespace yun\components;

use app\models\MessageUser;
use app\models\Position;
use app\models\User;
use yii\helpers\Json;
use yii\base\Component;
use yii;
use app\models\Message;

class MessageFunc extends Component {
    Const SEND_TYPE_ONE = 1;
    Const SEND_TYPE_POSITION = 2;
    Const SEND_TYPE_ALL = 3;


    public static function getTypeNameById($send_type){
        switch($send_type){
            case self::SEND_TYPE_ONE:
                $name = '对单一职员发送';break;
            case self::SEND_TYPE_POSITION:
                $name = '对整个部门/职位发送';break;
            case self::SEND_TYPE_ALL:
                $name = '对全体职员发送';break;
            default:
                $name = null;
        }
        return $name;
    }

    public static function getObjectInfo($send_type,$send_param){
        $info = 'N/A';
        if($send_type==self::SEND_TYPE_ONE){
            $object = Json::decode($send_param);
            $user_id = $object['user_id'];
            $user = User::find()->where(['id'=>$user_id])->one();
            $info = '('.$user_id.') ';
            if($user)
                $info .= $user->name;
            else
                $info .= 'N/A';
        }elseif($send_type==self::SEND_TYPE_POSITION){
            $object = Json::decode($send_param);
            $position_id = $object['position_id'];
            $position = Position::find()->where(['id'=>$position_id])->one();
            $info = '('.$position_id.') ';
            if($position)
                $info .= '取职位路径';
            else
                $info .= 'N/A';
        }elseif($send_type==self::SEND_TYPE_ALL){
            $info = '--';
        }
        return $info;
    }

    public static function getSendUserNum($msg_id,$read_status=false){
        $where = ['msg_id'=>$msg_id];
        if($read_status===1){
            $where['read_status'] = 1;
        }elseif($read_status===0){
            $where['read_status'] = 0;
        }
        $count = MessageUser::find()->where($where)->count();
        return $count;
    }
}