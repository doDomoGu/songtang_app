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
            <th>当前操作人</th>
            <!--<th width="200">所属地区 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
            <th>所属业态 <?/*=$aid>0?Html::dropDownList('business-select',$bid,$bArr,['prompt'=>'----','id'=>'business-select']):''*/?></th>
            <th>所属部门</th>-->
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <?php if($l->flow->user_id>0):?>
                <?php $username = $l->flow->user->name;?>
            <?php else:?>
                <?php
                $username = \oa\models\Apply::getOperationUser($l,$l->flow);

                ?>
            <?php endif;?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->title?></td>
                <td><?=$l->applyUser->name?></td>
                <td>(ID:<?=$l->task_id?>) <?=$l->task->title?></td>
                <td><?=$l->flow_step?></td>
                <td><?=$username?></td>
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