<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;

$this->title = '待办事项';
oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');

?>
<section class="panel">
    <div class="panel-head">
        <h3><?=$this->title?></h3>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>标题</th>
                <th>任务表</th>
                <th>申请时间</th>
                <th>操作</th>
            </tr>
            <tbody>
            <?php foreach($list as $l):?>
                <tr>
                    <td><?=$l->id?></td>
                    <td><?=$l->title?></td>
                    <td><?=$l->task->title?></td>
                    <td><?=$l->add_time?></td>
                    <td>
                        <?=Html::a('进行操作','/apply/do?id='.$l->id,['class'=>'btn btn-success btn-xs'])?>
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