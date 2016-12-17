<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
    $this->title = '任务管理';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/index.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?=Html::a('新增任务','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>标题</th>
            <th width="200">所属地区 <?=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])?></th>
            <th>所属业态 <?=$aid>0?Html::dropDownList('business-select',$bid,$bArr,['prompt'=>'----','id'=>'business-select']):''?></th>
            <th>所属部门</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->title?></td>
                <td><?=$l->area_id>0?$aArr[$l->area_id]:'--'?></td>
                <td><?=$l->business_id>0?$bArr[$l->business_id]:'--'?></td>
                <td><?=\ucenter\models\Department::getFullRoute([$l->department_id])?></td>
                <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?/*=Html::a('添加业态','script:void(0)',['data-pid'=>$l->aid,'data-pname'=>$aArr[$l->aid],'data-type'=>'area','data-toggle'=>"modal",'data-target'=>"#addModal",'class'=>'btn btn-xs btn-success'])*/?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '新增任务',
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
                <label class="col-sm-4 control-label label1">标题</label>
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