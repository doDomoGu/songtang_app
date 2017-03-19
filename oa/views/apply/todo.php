<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;

$this->title = '待办事项';
oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/my.css');

?>
<section class="panel panel-default">
    <div class="panel-heading">
        <h3><?=$this->title?></h3>
        <?=Html::img('/images/main/apply/create-icon-2.png')?>
    </div>
    <div class="panel-body">
        <table>
            <tr>
                <th><span>#</span></th>
                <th><span><?=Html::img('/images/main/apply/list-title-1.png',['style'=>'height:44px;padding:2px;'])?> &nbsp;&nbsp; 标题</span></th>
                <th><span><?=Html::img('/images/main/apply/list-title-2.png',['style'=>'height:44px;padding:2px;'])?> &nbsp;&nbsp; 任务表</span></th>
                <th><span><?=Html::img('/images/main/apply/list-title-3.png',['style'=>'height:44px;padding:2px;'])?> &nbsp;&nbsp; 申请时间</span></th>
                <th class="last"><span><?=Html::img('/images/main/apply/list-title-4.png',['style'=>'height:44px;padding:2px;'])?> &nbsp;&nbsp; 操作</span></th>
            </tr>
            <tbody>
            <?php foreach($list as $l):?>
                <tr>
                    <td><span><?=$l->id?></span></td>
                    <td><span><?=$l->title?></span></td>
                    <td><span><?=$l->task->title?></span></td>
                    <td><span><?=$l->add_time?></span></td>
                    <td>
                        <span><?=Html::a('进行操作','/apply/do?id='.$l->id,['class'=>'btn btn-success btn-xs'])?></span>
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
    //'size'=>'modal-lg',
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