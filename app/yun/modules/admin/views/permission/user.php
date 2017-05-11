<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\Position;

    $this->title = '目录权限 - 用户';
    //app\assets\AppAsset::addJsFile($this,'js/main/structure/index.js');
?>
<section>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>用户名</th>
            <th>姓名</th>
            <th>地区 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
            <th>行业 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
            <th>公司 <?/*=$aid>0?Html::dropDownList('business-select',$bid,$bArr2,['prompt'=>'----','id'=>'business-select']):''*/?></th>
            <th>部门</th>
            <th>职位</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->username?></td>
                <td><?=$l->name?></td>
                <td><?=CommonFunc::getByCache(District::className(),'getName',[$l->district_id],'ucenter:district/name')?></td>
                <td><?=CommonFunc::getByCache(Industry::className(),'getName',[$l->industry_id],'ucenter:industry/name')?></td>
                <td><?=CommonFunc::getByCache(Company::className(),'getName',[$l->company_id],'ucenter:company/name')?></td>
                <td><?=CommonFunc::getByCache(Department::className(),'getFullRoute',[$l->department_id],'ucenter:department/full-route')?></td>
                <td><?=CommonFunc::getByCache(Position::className(),'getName',[$l->position_id],'ucenter:position/name')?></td>
                <td>
                    <?=Html::a('查看权限','/admin/permission/user-permission?user_id='.$l->id,['class'=>'btn btn-success btn-xs'])?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '<span class="modal-title"></span>',
    'id'=>'addModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="addContent">
        <form class="form-horizontal" role="form">
            <input class="aid-value" type="hidden" />
            <input class="bid-value" type="hidden" />
            <input class="p_id-value" type="hidden" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">地区</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="aname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">业态</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="bname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">部门路径</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="pname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">部门选择</label>
                <div class="col-sm-6">
                    <select class="form-control new-did-select">
                    </select>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="add-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>