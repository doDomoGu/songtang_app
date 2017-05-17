<?php
    use yii\bootstrap\Modal;
    use yii\bootstrap\Html;
    use common\components\CommonFunc;
    use ucenter\models\District;
    use ucenter\models\Industry;
    use ucenter\models\Company;
    use ucenter\models\Department;
    use ucenter\models\Position;

    $this->title = '【'.$task->title.'】的发起人设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/apply_user.js');
?>
<section>
    <input type="hidden" class="task-id" value="<?=$task->id?>" />
    <div class="errmsg" style="color:red;display:none;"></div>
  <!--  <?php /*if($task->set_complete==0):*/?>
    <button class="btn btn-success submit-btn" > 保存 </button>
    --><?php /*endif;*/?>
    <div style="margin-bottom: 10px;">
    <?=Html::a('返回','/admin/task',['class'=>'btn btn-default'])?>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                发起人通过使用匹配职员的属性来选择  <?=Html::button('新增',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success btn-xs'])?>

                <?/*=Html::button('增加',['class'=>'btn btn-xs btn-success add-btn','data-id'=>$task->id])*/?>
            </h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered" style="margin: 10px 0;background: #fafafa;">
                <tr>
                    <th>地区</th>
                    <th>业态</th>
                    <th>公司</th>
                    <th>部门</th>
                    <th>职位</th>
                    <th>操作</th>
                </tr>
                <tbody>
                <?php foreach($list as $l):?>
                    <tr>
                        <td><?=District::getName($l->district_id)?></td>
                        <td><?=Industry::getName($l->industry_id)?></td>
                        <td><?=Company::getName($l->company_id)?></td>
                        <td><?=Department::getFullRoute($l->department_id)?></td>
                        <td><?=Position::getName($l->position_id)?></td>
                        <td>
                            <?=Html::button('编辑',['class'=>'btn btn-xs btn-primary edit-btn','data-id'=>$l->id,'data-task_id'=>$task->id,'district_id'=>$l->district_id,'industry_id'=>$l->industry_id,'company_id'=>$l->company_id,'department_id'=>$l->department_id,'position_id'=>$l->position_id])?>
                            <?=Html::button('删除',['class'=>'btn btn-xs btn-danger del-btn','data-id'=>$l->id,'data-task_id'=>$task->id])?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="well">共 <?=count($userList)?> 人</div>
    <table class="table table-bordered" style="margin: 10px 0;background: #fafafa;">
        <tr>
            <th>职员ID</th>
            <th>职员姓名</th>
            <th>地区</th>
            <th>业态</th>
            <th>公司</th>
            <th>部门</th>
            <th>职位</th>
            <!--<th>操作</th>-->
        </tr>
        <tbody>
        <?php foreach($userList as $l):?>
            <?php $userInfo = CommonFunc::getByCache(\login\models\UserIdentity::className(),'findIdentityOne',[$l->id],'ucenter:user/identity'); ?>
            <?php if($userInfo!=null):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$userInfo->name?></td>
                <td><?=$userInfo->district?></td>
                <td><?=$userInfo->industry?></td>
                <td><?=$userInfo->company?></td>
                <td><?=$userInfo->department?></td>
                <td><?=$userInfo->position?></td>
            </tr>
            <?php else:?>
                <tr>
                    <td><?=$l->id?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif;?>
        <?php endforeach;?>
        </tbody>
    </table>
<!--    <?php /*if($task->set_complete==0):*/?>
        <button class="btn btn-success submit-btn" > 保存 </button>
    --><?php /*endif;*/?>
</section>


<?php
Modal::begin([
    'header' => '新增设置',
    'id'=>'createModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
<div id="createContent">
    <form class="form-horizontal" role="form">
        <input class="task-id" value="<?=$task->id?>" type="hidden" />
        <input class="bid-value" type="hidden" />
        <input class="p_id-value" type="hidden" />

        <div class="form-group">
                <label class="col-sm-4 control-label label1">所属地区</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('district-select','',District::getItems(),['class'=>"form-control create-district-select"])?>
                </div>
            </div>
        <div class="form-group">
                <label class="col-sm-4 control-label label1">所属行业</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('industry-select','',Industry::getItems(),['class'=>"form-control create-industry-select"])?>
                </div>
            </div>
        <div class="form-group">
                <label class="col-sm-4 control-label label1">所属公司</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('company-select','',Company::getItems(),['class'=>"form-control create-company-select"])?>
                </div>
            </div>
        <div class="form-group">
                <label class="col-sm-4 control-label label2">所属部门</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('department-select','',Department::getItems(),['class'=>"form-control create-department-select",'encode'=>false])?>

                </div>
            </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label2">所属职位</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('position-select','',Position::getItems(),['class'=>"form-control create-position-select",'encode'=>false])?>

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <button type="button" class="btn btn-success" id="create-submit-btn">提交</button>
                <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
            </div>
        </div>
    </form>
</div>
<?php
Modal::end();
?>


<?php
Modal::begin([
    'header' => '编辑设置',
    'id'=>'editModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
<div id="editContent">
    <form class="form-horizontal" role="form">
        <input class="aid-value" type="hidden" />
        <input class="bid-value" type="hidden" />
        <input class="p_id-value" type="hidden" />
        <input class="edit-id" type="hidden" />
        <div class="form-group">
            <label class="col-sm-4 control-label label1">所属地区</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('district-select','',District::getItems(),['class'=>"form-control edit-district-select"])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">所属行业</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('industry-select','',Industry::getItems(),['class'=>"form-control edit-industry-select"])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">所属公司</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('company-select','',Company::getItems(),['class'=>"form-control edit-company-select"])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label2">所属部门</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('department-select','',Department::getItems(),['class'=>"form-control edit-department-select",'encode'=>false])?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label2">所属职位</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('position-select','',Position::getItems(),['class'=>"form-control edit-position-select",'encode'=>false])?>

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <button type="button" class="btn btn-success" id="edit-submit-btn">提交</button>
                <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
            </div>
        </div>
    </form>
</div>
<?php
Modal::end();
?>
