<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\Flow;
use yii\helpers\Url;
    $this->title = '【'.$task->title.'】的发起人设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/apply_user.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?php if($task->set_complete==0):?>
        <?=Html::a('新增发起人','script:void(0)',['data-toggle'=>"modal",
            'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
        <?php endif;?>
        <?=Html::a('返回','/admin/task',['class'=>'btn btn-default'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>职员ID</th>
            <th>职员姓名</th>
            <th>地区</th>
            <th>业态</th>
            <th>部门</th>
            <th>职位</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->user_id?></td>
                <td><?=$l->user->name?></td>
                <td><?=$l->user->area->name?></td>
                <td><?=$l->user->business->name?></td>
                <td><?=$l->user->did?></td>
                <td><?=$l->user->position_id?></td>
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
    'header' => '新增发起人',
    'id'=>'createModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="createContent">
        <form class="form-horizontal" role="form">
            <input class="task-id" type="hidden" value="<?=$task->id?>" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">发起人</label>
                <div class="col-sm-6">
                    <input class="form-control create-user-id">
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