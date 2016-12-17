<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\OaFlow;
    $this->title = '【'.$task->title.'】的流程设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/flow.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?=Html::a('新增流程','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>标题</th>
            <th>操作类型</th>
            <th>指定操作人</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->title?></td>
                <td><?=OaFlow::getTypeCn($l->type)?></td>
                <td><?=$l->user->name?></td>
                <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?/*=Html::a('流程设置',AdminFunc::adminUrl('task/flow',['tid'=>$l->id]),['class'=>'btn btn-xs btn-primary'])*/?><!--
                    --><?/*=Html::a('发起人设置',AdminFunc::adminUrl('task/apply_user',['tid'=>$l->id]),['class'=>'btn btn-xs btn-primary'])*/?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '新增流程',
    'id'=>'createModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="createContent">
        <form class="form-horizontal" role="form">
            <input class="task-id" type="hidden" value="<?=$task->id?>" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">标题</label>
                <div class="col-sm-6">
                    <input class="form-control create-title">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">类型</label>
                <div class="col-sm-6">
                    <select class="form-control create-type-select">
                        <?=OaFlow::getOptions()?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">职员</label>
                <div class="col-sm-6">
                    <input class="form-control create-user-select">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="create-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>