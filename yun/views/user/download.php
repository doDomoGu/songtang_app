<?php
    use app\components\DirFunc;
    use yii\bootstrap\BaseHtml;
    app\assets\AppAsset::addCssFile($this,'css/main/user/file.css');
?>
<div class="panel panel-default">
    <div class="panel-heading"><h1><?=$this->title?></h1></div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>文件名</th>
            <th>所在目录</th>
            <th>下载时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($list)):?>
            <?php foreach($list as $l):?>
                <tr>
                    <th scope="row"><?=$l->id?></th>
                    <td><?=$l->file->filename?></td>
                    <td>
                        <?=DirFunc::getFileFullRoute($l->file->dir_id,$l->file->p_id)?>
                    </td>
                    <td>
                        <?=$l->download_time?>
                    </td>
                    <td>
                        <?php
                        if($l->file->p_id>0){
                            $link = ['/dir','p_id'=>$l->file->p_id];
                        }else{
                            $link = ['/dir','dir_id'=>$l->file->dir_id];
                        }
                        ?>
                        <?=BaseHtml::a('进入目录',$link,['target'=>'_blank','class'=>'btn btn-primary btn-xs'])?>


                    </td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
        </tbody>
    </table>
</div>
<div class="clearfix text-center">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $pages]); ?>
</div>
<div class="clearfix text-center">共 <?=$pages->totalCount?> 个</div>