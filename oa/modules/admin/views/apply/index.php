<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = '申请列表';
/*oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/apply/index.js');*/
?>
    <section>
        <table class="table table-bordered" style="background: #fafafa;">
            <tr>
                <th>#</th>
                <th>标题</th>
                <th>发起人</th>
                <th>对应任务</th>
                <th>当前步骤</th>
                <!--<th width="200">所属地区 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
                <th>所属业态 <?/*=$aid>0?Html::dropDownList('business-select',$bid,$bArr,['prompt'=>'----','id'=>'business-select']):''*/?></th>
                <th>所属部门</th>-->
                <th>状态</th>
                <th>操作</th>
            </tr>
            <tbody>
            <?php foreach($list as $l):?>
                <tr>
                    <td><?=$l->id?></td>
                    <td><?=$l->title?></td>
                    <td><?=$l->applyUser->name?></td>
                    <td>(ID:<?=$l->task_id?>) <?=$l->task->title?></td>
                    <td><?=$l->flow_step?></td>
                    <!--<td><?/*=$l->area_id>0?$aArr[$l->area_id]:'--'*/?></td>
                    <td><?/*=$l->business_id>0?$bArr[$l->business_id]:'--'*/?></td>
                    <td><?/*=\ucenter\models\Department::getFullRoute([$l->department_id])*/?></td>-->
                    <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                    <td>
                        <?=Html::a('查看流程记录',Url::to(['apply/show-record','aid'=>$l->id]),['class'=>'btn btn-xs btn-success'])?>
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