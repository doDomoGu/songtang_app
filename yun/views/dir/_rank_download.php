<?php
    use app\components\FileFrontFunc;
    use app\components\DirFunc;
    app\assets\AppAsset::addCssFile($this,'css/main/dir/_rank_download.css');

    $id = 5;

    $dirs = \app\models\Dir::find()->where(['p_id'=>$id])->all();

    $dir_ids = [];

    foreach($dirs as $d){
        $dir_ids[] = $d->id;
    }

    $list = FileFrontFunc::getDownloadList($dir_ids);

    $list2 = FileFrontFunc::getRecentList($dir_ids);


?>
<div id="dir-sidebar-study-download">
    <h3>最热下载榜</h3>
    <ul class="list-unstyled">
        <?php $i=1;foreach($list as $l):?>
        <li>
            <?=$i?> . <?=\yii\bootstrap\Html::a($l->filename,['/dir/download','id'=>$l->id])?>
        </li>
        <?php $i++;endforeach;?>
    </ul>


    <h3>最新上传榜</h3>
    <ul class="list-unstyled">
        <?php $i=1;foreach($list2 as $l):?>
        <li>
            <?=$i?> . <?=\yii\bootstrap\Html::a($l->filename,['/dir/download','id'=>$l->id])?>
        </li>
        <?php $i++;endforeach;?>
    </ul>
</div>