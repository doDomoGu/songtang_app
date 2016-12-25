<?php
    use yii\bootstrap\Html;
    use yun\components\FileFrontFunc;
    use yun\components\PermissionFunc;
    use yun\components\CommonFunc;
?>
<?php foreach($list as $l):?>
    <?php
        $downloadCheck = PermissionFunc::checkFileDownloadPermission(222,$l);
        $filethumb = ($downloadCheck && in_array($l->filetype,$this->context->thumbTypeArr))?true:false;
    ?>
    <div class="list-item grid-style <?=$l->filetype == 0?'dirtype':'filetype'?> <?=$downloadCheck?'download-enable':'download-disable'?> <?=$l->user_id==yii::$app->user->id?'delete-enable':'delete-disable'?> <?=$l->filetype==0?'is-dir':'is-file'?> text-center" data-is-dir="<?=$l->filetype==0?'1':'0'?>" data-id="<?=$l->id?>" download-check="<?=$downloadCheck?'enable':'disable'?>">
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
            <div class="file-check" >
                <input type="checkbox" name="cb[]" class="file-checkbox" value="<?=$l->id?>" />
            </div>

        </div>
        <div class="filename" title="<?=$l->filename?>" alt="<?=$l->filename?>">
            <?=CommonFunc::mySubstr($l->filename,24);?>
        </div>

    </div>
<?php endforeach;?>