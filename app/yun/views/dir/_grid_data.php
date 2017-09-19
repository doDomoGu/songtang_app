<?php
    use yii\bootstrap\Html;
    use yun\components\FileFrontFunc;
    use common\components\CommonFunc;
    use yun\models\DirPermission;
?>
<?php foreach($list as $l):?>
    <?php
        $downloadCheck = DirPermission::isFileAllow($l->dir_id,$l->id,DirPermission::OPERATION_DOWNLOAD);
        $filethumb = ($downloadCheck && in_array($l->filetype,$this->context->thumbTypeArr))?true:false;
    ?>
    <div class="list-item grid-style <?=$l->filetype == 0?'dirtype':'filetype'?> <?=$downloadCheck?'download-enable':'download-disable'?> <?=(in_array(yii::$app->user->id,[10000,10003,10004,10005]) || $l->user_id==yii::$app->user->id)?'delete-enable':'delete-disable'?> <?=$l->filetype==0?'is-dir':'is-file'?> text-center" data-is-dir="<?=$l->filetype==0?'1':'0'?>" data-id="<?=$l->id?>" download-check="<?=$downloadCheck?'enable':'disable'?>">
        <div class="icon <?=$filethumb?'filethumb_icon':''?>">
            <?php if($filethumb):?>
                <span class="filethumb" data-id="<?=$l->id?>">
                    <img class="filethumb-<?=$l->id?>" src="" style="width:100%;">
                </span>
            <?php else:?>
                <span class="fileicon">
                    <?=Html::img('/images/fileicon/'.FileFrontFunc::getFileExt($l->filetype).'.png')?>
                </span>
            <?php endif;?>
            <div class="file_attrs <?=$l->filetype == 0?'hidden':''?>">
                <?php if(!empty($l->areaAttrs2)):?>
                    <div class="area_attrs">
                        <?php foreach($l->areaAttrs2 as $a):?>
                            <span class="label label-primary"><?=$a?></span>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>

                <?php if(!empty($l->businessAttrs2)):?>
                    <div class="business_attrs">
                        <?php foreach($l->businessAttrs2 as $b):?>
                            <span class="label label-warning"><?=$b?></span>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
            </div>
            <div class="file-check" >
                <input type="checkbox" name="cb[]" class="file-checkbox" value="<?=$l->id?>" />
            </div>

        </div>
        <div class="filename" title="<?=$l->filename?>" alt="<?=$l->filename?>">
            <?=CommonFunc::mySubstr($l->filename,24);?>
        </div>

    </div>
<?php endforeach;?>