<?php
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use common\components\CommonFunc;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\Position;


//\yun\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/user-group-permission/index.js');
$this->title = '用户组 - 成员设置';
?>
    <div style="margin-bottom: 10px;">
        <?/*=Html::Button('添加',['value'=>'','class'=> 'btn btn-success','id'=>'modalButton','data-toggle'=>"modal",'data-target'=>"#addModal"])*/?>
    </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>姓名</th>
                    <th>职位</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($list)):?>
            <?php foreach($list as $l):?>
                <tr>
                    <th scope="row"><?=$l->id?></th>
                    <td><?=$l->name?></td>
                    <td>
                        <?=CommonFunc::getByCache(District::className(),'getName',[$l->district_id],'ucenter:district/name')?> >
                        <?=CommonFunc::getByCache(Industry::className(),'getName',[$l->industry_id],'ucenter:industry/name')?> >
                        <?=CommonFunc::getByCache(Company::className(), 'getName',[$l->company_id], 'ucenter:company/name')?> >
                        <?=CommonFunc::getByCache(Department::className(),'getFullRoute',[$l->department_id],'ucenter:department/full-route')?> >
                        <?=CommonFunc::getByCache(Position::className(),'getName',[$l->position_id],'ucenter:position/name')?>
                    </td>
                    <td>
                        <?/*=Html::a('设置成员',['/admin/permission/user-group-user','group_id'=>$l->id],['class'=>'btn btn-primary btn-xs'])*/?><!--
                        --><?/*=Html::a('设置权限',['/admin/permission/user-group-permission','group_id'=>$l->id],['class'=>'btn btn-success btn-xs'])*/?>
                    </td>
                </tr>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>