<?php
    use yii\bootstrap\Modal;
    use yii\bootstrap\Html;
    use yii\helpers\Url;

    $this->title = '模板 - 分类设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/index.js');
?>
<section>
    <!--<div style="margin-bottom: 10px;">
        <?/*=Html::a('新增分类','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])*/?>
    </div>-->
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>名称</th>
            <th>分类</th>
            <th>排序</th>
            <th>状态</th>
            <!--<th>操作</th>-->
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->name?></td>
                <td><?=$l->typeName?></td>
                <td><?=$l->ord?></td>
                <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                <!--<td>-->
                    <?/*=Html::a('流程设置',Url::to(['task/flow','tid'=>$l->id]),['class'=>'btn btn-xs btn-primary'])*/?><!--
                    --><?/*=Html::a('发起人设置',Url::to(['task/apply-user','tid'=>$l->id]),['class'=>'btn btn-xs btn-primary'])*/?>
                <!--</td>-->
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '新增分类',
    'id'=>'createModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="createContent">
        <form class="form-horizontal" role="form">
            <input class="aid-value" type="hidden" />
            <input class="bid-value" type="hidden" />
            <input class="p_id-value" type="hidden" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">名称</label>
                <div class="col-sm-6">
                    <input class="form-control create-title">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">所属地区</label>
                <div class="col-sm-6">
                    <select class="form-control create-area-select">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">所属业态</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <select class="form-control create-business-select">
                        <option value="">---</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">所属部门</label>
                <div class="col-sm-6">
                    <select class="form-control create-department-select">
                        <option value="">---</option>
                    </select>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
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