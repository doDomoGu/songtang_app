<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CommonFunc;
use ucenter\models\Department;
use ucenter\models\District;
use ucenter\models\Company;
use oa\models\Task;
use oa\models\Form;

$this->title = '模板 - 表单设置';
oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/form.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?=Html::button('新增表单',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>表单标题</th>
            <th width="200">所属分类</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <?php
            /*$categoryList = Task::getCategory($l->id);
            $category = implode(' , ',$categoryList);*/

            $category = Form::getCategory($l->id);
            ?>

            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->title?></td>
                <td><?=$category?></td>
                <td>
                    <?=CommonFunc::getStatusCn($l->status)?>
                </td>
                <td>
                        <?=Html::a('编辑',Url::to(''),['class'=>'btn btn-xs btn-primary disabled'])?>
                        <?=Html::a('删除',Url::to(''),['class'=>'btn btn-xs btn-danger disabled'])?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
    <div class="clearfix text-center">
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pages]); ?>
    </div>
</section>




<?php
Modal::begin([
    'header' => '新增模板',
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
            <label class="col-sm-4 control-label label1">模板标题</label>
            <div class="col-sm-6">
                <input class="form-control create-title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">
                <input type="checkbox" id="checkall" />
                所属分类
            </label>
            <div class="col-sm-6">
                <?/*=Html::checkboxList('create-category-select','',$taskCategoryList,['class'=>'create-category-select','prompt'=>'==请选择==','encode'=>false,'separator'=>'<br/>'])*/?>
            </div>
        </div>
        <!--<div class="form-group">
                <label class="col-sm-4 control-label label1">所属地区</label>
                <div class="col-sm-6">
                    <?/*=\yii\bootstrap\BaseHtml::dropDownList('district-select','',\ucenter\models\District::getItems(),['class'=>"form-control create-district-select"])*/?>
                </div>
            </div>-->
        <!--<div class="form-group">
                <label class="col-sm-4 control-label label1">所属行业</label>
                <div class="col-sm-6">
                    <?/*=\yii\bootstrap\BaseHtml::dropDownList('industry-select','',\ucenter\models\Industry::getItems(),['class'=>"form-control create-industry-select"])*/?>
                </div>
            </div>-->
        <!--<div class="form-group">
                <label class="col-sm-4 control-label label1">所属公司</label>
                <div class="col-sm-6">
                    <?/*=\yii\bootstrap\BaseHtml::dropDownList('company-select','',\ucenter\models\Company::getItems(),['class'=>"form-control create-company-select"])*/?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">所属部门</label>
                <div class="col-sm-6">
                    <?/*=\yii\bootstrap\BaseHtml::dropDownList('department-select','',\ucenter\models\Department::getItems(),['class'=>"form-control create-department-select",'encode'=>false])*/?>

                </div>
            </div>-->
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
    'header' => '编辑模板',
    'id'=>'editModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
<div id="editContent">
    <form class="form-horizontal" role="form">
        <input class="aid-value" type="hidden" />
        <input class="bid-value" type="hidden" />
        <input class="p_id-value" type="hidden" />
        <input class="edit-task_id" type="hidden" />
        <div class="form-group">
            <label class="col-sm-4 control-label label1">模板标题</label>
            <div class="col-sm-6">
                <input class="form-control edit-title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">
                <input type="checkbox" id="edit-checkall" />
                所属分类
            </label>
            <div class="col-sm-6">
                <?/*=Html::checkboxList('edit-category-select','',$taskCategoryList,['class'=>'edit-category-select','prompt'=>'==请选择==','encode'=>false,'separator'=>'<br/>'])*/?>
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
