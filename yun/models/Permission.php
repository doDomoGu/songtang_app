<?php

namespace yun\models;
use Yii;

class Permission extends \yii\db\ActiveRecord
{
    const MODE_ALLOW        = 1;
    const MODE_DENY         = 2;

    const OPERATION_UPLOAD       = 1;
    const OPERATION_DOWNLOAD     = 2;
    const OPERATION_EDIT         = 3;
    const OPERATION_DELETE       = 4;

    const TYPE_ALL          = 1;
    const TYPE_AREA         = 2;
    const TYPE_BUSINESS     = 3;


    public function rules()
    {
        return [
            [['position_id', 'dir_id', 'type'], 'required'],
            [['position_id', 'dir_id', 'type'], 'integer'],
        ];
    }
/*CREATE TABLE `position_dir_permission` (
 `position_id` int(11) unsigned NOT NULL,
 `dir_id` int(11) unsigned NOT NULL,
 `type` tinyint(1) unsigned NOT NULL,
 PRIMARY KEY (`position_id`,`dir_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8*/

}