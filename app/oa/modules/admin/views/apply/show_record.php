<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = '申请操作流程';
oa\modules\admin\assets\AdminAsset::addCssFile($this,'css/main/apply/show_record.css');
?>
<section id="infoContent">
    <div>
        <h3><?=$apply->title?></h3>
    </div>
    <div class="content">
        <?=$html?>
    </div>
</section>