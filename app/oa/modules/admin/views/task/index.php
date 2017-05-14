<?php
    use yii\bootstrap\Modal;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use common\components\CommonFunc;
    use ucenter\models\Department;
    use ucenter\models\District;
    use ucenter\models\Company;
    use oa\models\Task;

    $this->title = '任务表设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/index.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?=Html::a('新增任务表','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>标题</th>
            <th width="200">关联分类</th>
            <th width="200">关联表单</th>
            <!-- <th width="100">所属地区 </th>-->
            <!--<th>所属行业</th>-->
            <!--<th width="100">所属公司 </th>-->
            <!--<th>所属部门</th>-->
            <th>状态</th>
            <th>设置</th>
            <th>是否完成设置</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <?php
                /*$categoryList = Task::getCategory($l->id);
                $category = implode(' , ',$categoryList);*/

                $category = Task::getCategory2($l->id);
            ?>

            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->title?></td>
                <td><?=$category?></td>
                <!--<td><?/*=commonFunc::getByCache(District::className(),'getName',[$l->district_id],'ucenter:district/name')*/?></td>-->
                <!--<td><?/*=$l->industry_id>0?$industryArr[$l->industry_id]:'--'*/?></td>-->
                <!--<td><?/*=commonFunc::getByCache(Company::className(),'getName',[$l->company_id],'ucenter:company/name')*/?></td>-->
                <!--<td><?/*=commonFunc::getByCache(Department::className(),'getFullRoute',[$l->department_id],'ucenter:department/full-route')*/?></td>-->
                <td><?=CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?php if($l->set_complete==1):?>
                        <?=Html::a('查看流程',Url::to(['task/flow','tid'=>$l->id]),['class'=>'btn btn-xs btn-primary'])?>
                        <?=Html::a('查看发起人',Url::to(['task/apply-user','tid'=>$l->id]),['class'=>'btn btn-xs btn-primary'])?>
                    <?php else:?>
                        <?=Html::a('流程设置',Url::to(['task/flow','tid'=>$l->id]),['class'=>'btn btn-xs btn-success'])?>
                        <?=Html::a('发起人设置',Url::to(['task/apply-user','tid'=>$l->id]),['class'=>'btn btn-xs btn-success'])?>
                    <?php endif;?>
                </td>
                <td>
                    <?php if($l->set_complete==1):?>
                        已完成
                    <?php else:?>
                        <button data-id="<?=$l->id?>" class="complete-btn btn btn-danger btn-xs">确认设置完成</button>
                    <?php endif;?>
                </td>
                <td>
                    <?/*=Html::a('编辑','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#editModal",'class'=>'btn btn-primary btn-xs'])*/?>
                    <?php $isApplied = Task::isApplied($l->id);?>
                    <?php if($isApplied):?>
                        <?=Html::a('删除',Url::to(''),['class'=>'btn btn-xs btn-danger disabled'])?>
                    <?php else:?>
                        <?=Html::button('删除',['class'=>'btn btn-xs btn-danger del-btn','data-id'=>$l->id])?>
                    <?php endif;?>
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
                <label class="col-sm-4 control-label label1">
                    <input type="checkbox" id="checkall" />
                    分类
                </label>
                <div class="col-sm-6">
                    <?=Html::checkboxList('create-category-select','',$taskCategoryList,['class'=>'create-category-select','prompt'=>'==请选择==','encode'=>false,'separator'=>'<br/>'])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">所属地区</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('district-select','',\ucenter\models\District::getItems(),['class'=>"form-control create-district-select"])?>
                </div>
            </div>
            <!--<div class="form-group">
                <label class="col-sm-4 control-label label1">所属行业</label>
                <div class="col-sm-6">
                    <?/*=\yii\bootstrap\BaseHtml::dropDownList('industry-select','',\ucenter\models\Industry::getItems(),['class'=>"form-control create-industry-select"])*/?>
                </div>
            </div>-->
            <div class="form-group">
                <label class="col-sm-4 control-label label1">所属公司</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('company-select','',\ucenter\models\Company::getItems(),['class'=>"form-control create-company-select"])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">所属部门</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('department-select','',\ucenter\models\Department::getItems(),['class'=>"form-control create-department-select",'encode'=>false])?>

                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="create-btn">提交</button>
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
    'header' => '编辑任务',
    'id'=>'editModal',
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
            <label class="col-sm-4 control-label label1">
                <input type="checkbox" id="checkall" />
                分类
            </label>
            <div class="col-sm-6">
                <?=Html::checkboxList('create-category-select','',$taskCategoryList,['class'=>'create-category-select','prompt'=>'==请选择==','encode'=>false,'separator'=>'<br/>'])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">所属地区</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('district-select','',\ucenter\models\District::getItems(),['class'=>"form-control create-district-select"])?>
            </div>
        </div>
        <!--<div class="form-group">
                <label class="col-sm-4 control-label label1">所属行业</label>
                <div class="col-sm-6">
                    <?/*=\yii\bootstrap\BaseHtml::dropDownList('industry-select','',\ucenter\models\Industry::getItems(),['class'=>"form-control create-industry-select"])*/?>
                </div>
            </div>-->
        <div class="form-group">
            <label class="col-sm-4 control-label label1">所属公司</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('company-select','',\ucenter\models\Company::getItems(),['class'=>"form-control create-company-select"])?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label2">所属部门</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('department-select','',\ucenter\models\Department::getItems(),['class'=>"form-control create-department-select",'encode'=>false])?>

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <button type="button" class="btn btn-success" id="create-btn">提交</button>
                <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
            </div>
        </div>
    </form>
</div>
<?php
Modal::end();
?>
