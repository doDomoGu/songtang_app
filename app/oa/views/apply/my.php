<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;

$this->title = '我的申请';
oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/my.css');

?>
<section class="panel panel-default">
    <div class="panel-heading">
        <?=Html::img('/images/main/apply/apply_heading_5.png')?>
        <?=$this->title?>
        <?/*=Html::img('/images/main/apply/create-icon-2.png')*/?>
    </div>
    <div class="panel-body">
        <table>
            <tr>
                <th><span>#</span></th>
                <th><span><?=Html::img('/images/main/apply/th_1.png',['style'=>''])?> &nbsp;&nbsp; 标题</span></th>
                <th><span><?=Html::img('/images/main/apply/th_2.png',['style'=>''])?> &nbsp;&nbsp; 任务表</span></th>
                <th><span><?=Html::img('/images/main/apply/th_3.png',['style'=>''])?> &nbsp;&nbsp; 申请时间</span></th>
                <th class="last"><span><?=Html::img('/images/main/apply/th_4.png',['style'=>''])?> &nbsp;&nbsp; 操作</span></th>
            </tr>
            <tbody>
            <?php foreach($list as $l):?>
                <tr>
                    <td><span><?=$l->id?></span></td>
                    <td><span><?=$l->title?></span></td>
                    <td><span><?=$l->task->title?></span></td>
                    <td><span><?=$l->add_time?></span></td>
                    <td class="last"><span>
                        <?=Html::a('查看详情','script:void(0)',['data-id'=>$l->id,'data-toggle'=>"modal",'data-target'=>"#infoModal",'class'=>'btn btn-success btn-xs'])?>
                            </span>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>

</section>
<?php
Modal::begin([
    'header' => '申请表详情',
    'id'=>'infoModal',
    'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="infoContent">
        <div class="content"></div>
        <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
    </div>
<?php
Modal::end();
?>