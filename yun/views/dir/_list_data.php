<?php
    use yii\bootstrap\Html;
    use app\components\FileFrontFunc;
    use app\components\PermissionFunc;
use app\components\CommonFunc;

?>
<?php foreach($list as $l):?>
    <?php $downloadCheck = PermissionFunc::checkFileDownloadPermission($this->context->user->position_id,$l);?>
    <div class="list-item list-style <?=$l->filetype == 0?'dirtype':'filetype'?> <?=$downloadCheck?'download-enable':'download-disable'?> <?=$l->uid==yii::$app->user->id?'delete-enable':'delete-disable'?>" data-is-dir="<?=$l->filetype==0?'1':'0'?>" data-id="<?=$l->id?>" download-check="<?=$downloadCheck?'enable':'disable'?>">
        <div class="info">
            <div class="file-check">
                <input type="checkbox" name="cb[]" class="file-checkbox" value="<?=$l->id?>" />
            </div>
            <div class="filename">
                <!--<span class="file-checkbox">
                    <input type="checkbox" <?/*=$downloadCheck?'':'disabled="disabled"'*/?> >
                </span>-->
                <span class="icon">
                    <?=Html::img('/images/fileicon/'.FileFrontFunc::getFileExt($l->filetype).'.png')?>
                </span>
                <span class="filename_txt " title="<?=$l->filename?>" alt="<?=$l->filename?>">
                <?php if($l->filetype == 0 && $downloadCheck):?>
                    <?=Html::a(CommonFunc::mySubstr($l->filename,30),['/dir','p_id'=>$l->id])?>
                <?php else:?>
                    <?=CommonFunc::mySubstr($l->filename,30)?>
                <?php endif;?>
                </span>
                <div class="click_btns">
                    <?php if($l->filetype!=0):?>
                        <?php if($downloadCheck):?>
                            <?=Html::Button('<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>',['data-id'=>$l->id,'class'=> 'downloadBtn btn '])?>
                        <?php else:?>
                            <?=Html::Button('<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>',['class'=> 'btn disabled'])?>
                        <?php endif;?>

                        <?php if($downloadCheck && in_array($l->filetype,$this->context->previewTypeArr)):?>
                            <?=Html::Button('<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>',['data-id'=>$l->id,'class'=> 'previewBtn btn'])?>
                        <?php else:?>
                            <?=Html::Button('<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>',['class'=> 'btn disabled'])?>
                        <?php endif;?>
                        <?php if($l->uid==yii::$app->user->id):?>
                            <?=Html::Button('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',['link'=>'/dir/delete?id='.$l->id,'class'=> 'editBtn btn','data-filename'=>$l->filename,'data-file-id'=>$l->id,'data-toggle'=>"modal",'data-target'=>"#editModal"])?>
                            <?=Html::Button('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',['link'=>'/dir/delete?id='.$l->id,'class'=> 'deleteBtn btn'])?>
                        <?php else:?>
                            <?=Html::Button('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',['class'=> 'btn disabled'])?>
                            <?=Html::Button('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',['class'=> 'btn disabled'])?>
                        <?php endif;?>

                    <?php else:?>
                        <?php if($l->uid==yii::$app->user->id):?>
                            <?=Html::Button('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',['class'=> 'editBtn btn','data-filename'=>$l->filename,'data-file-id'=>$l->id,'data-toggle'=>"modal",'data-target'=>"#editModal"])?>
                            <?=Html::Button('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',['link'=>'/dir/delete?id='.$l->id,'class'=> 'deleteBtn btn'])?>
                        <?php else:?>
                            <?=Html::Button('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',['class'=> 'btn disabled'])?>
                            <?=Html::Button('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',['class'=> 'btn disabled'])?>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
            <div class="filesize"><?=$l->filetype>0?FileFrontFunc::sizeFormat($l->filesize):'--'?></div>
            <div class="upload_time"><?=date('Y-m-d H:i',strtotime($l->add_time))?></div>

            <!--<div class="upload_uid"><?/*=$l->user->name*/?></div>
            <div class="download_times"><?/*=$l->clicks*/?></div>-->
            <!--<div class="click_btns">
                <?php /*if($l->filetype>0):*/?>
                    <?php /*if($downloadCheck):*/?>
                        <?/*=Html::Button('下载',['data-id'=>$l->id,'class'=> 'downloadBtn btn btn-success'])*/?>
                    <?php /*else:*/?>
                        <?/*=Html::Button('下载',['class'=> 'btn btn-success disabled'])*/?>
                    <?php /*endif;*/?>
                <?php /*else:*/?>
                    <?php /*if($downloadCheck):*/?>
                        <?/*=Html::Button('打开',['data-id'=>$l->id,'class'=> 'openBtn btn btn-success'])*/?>
                    <?php /*else:*/?>
                        <?/*=Html::Button('打开',['class'=> 'btn btn-success disabled'])*/?>
                    <?php /*endif;*/?>
                <?php /*endif;*/?>
                <?php /*if($downloadCheck && in_array($l->filetype,$this->context->previewTypeArr)):*/?>
                    <?/*=Html::Button('预览',['data-id'=>$l->id,'class'=> 'previewBtn btn btn-primary'])*/?>
                <?php /*else:*/?>
                    <?/*=Html::Button('预览',['class'=> 'btn btn-primary disabled'])*/?>
                <?php /*endif;*/?>
                <?php /*if($l->uid==yii::$app->user->id):*/?>
                    <?/*=Html::Button('删除',['link'=>'/dir/delete?id='.$l->id,'class'=> 'deleteBtn btn btn-success'])*/?>
                <?php /*else:*/?>
                    <?/*=Html::Button('删除',['class'=> 'btn btn-success disabled'])*/?>
                <?php /*endif;*/?>
            </div>-->
            <!--<div class="upload_type">上传类型：<?/*=$l->flag==1?'公共':'个人'*/?></div>
            <div class="download_times">下载次数：<span><?/*=$l->clicks*/?></span></div>-->
        </div>
    </div>
<?php endforeach;?>