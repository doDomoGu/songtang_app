<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\Apply;

$this->title = '相关事项';
oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/my.css');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/related.css');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/table.css');

oa\assets\AppAsset::addJsFile($this,'script/jquery-ui/jquery-ui.min.js');
oa\assets\AppAsset::addCssFile($this,'script/jquery-ui/jquery-ui.min.css');

?>
    <section class="panel panel-default">
        <div class="panel-heading">
            <?=Html::img('/images/main/apply/apply_heading_4.png')?>
            <?=$this->title?>
            <?/*=Html::img('/images/main/apply/create-icon-2.png')*/?>
        </div>
        <div class="panel-body">
            <table>
                <tr>
                    <th><span>#</span></th>
                    <th><span><?=Html::img('/images/main/apply/th_1.png',['style'=>''])?> &nbsp; 标题</span></th>
                    <th><span><?=Html::img('/images/main/apply/th_2.png',['style'=>''])?> &nbsp; 任务表</span></th>
                    <th><span><?=Html::img('/images/main/apply/th_2.png',['style'=>''])?> &nbsp;申请人</span></th>
                    <th><span><?=Html::img('/images/main/apply/th_3.png',['style'=>''])?> &nbsp; 申请时间</span></th>
                    <th><span><?=Html::img('/images/main/apply/th_5.png',['style'=>''])?> &nbsp; 状态</span></th>
                    <th class="last"><span><?=Html::img('/images/main/apply/th_4.png',['style'=>''])?> &nbsp; 操作</span></th>
                </tr>
                <tbody>
                <?php foreach($list as $l):?>
                    <tr>
                        <td><span><?=$l->id?></span></td>
                        <td><span><?=$l->title?></span></td>
                        <td><span><?=$l->task->title?></span></td>
                        <td><span><?=$l->applyUser->name?></span></td>
                        <td><span><?=substr($l->add_time,0,-3)?></span></td>
                        <td><span><?=Apply::getStatusCn($l->status)?> <br/> <?=Apply::getStepUser($l)?></span></td>
                        <td class="last"><span>
                            <?=Html::a('查看详情','script:void(0)',['data-id'=>$l->id,'data-toggle'=>"modal",'data-target'=>"#infoModal",'class'=>'btn btn-success btn-xs'])?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php echo \yii\widgets\LinkPager::widget([
                'pagination' => $pages ,
            ])?>
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