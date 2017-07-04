<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\Position;


    $this->title = '访问记录';
    //app\assets\AppAsset::addJsFile($this,'js/main/structure/index.js');
?>
<section>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>姓名</th>
            <th>应用</th>
            <th>页数</th>
            <th>性别</th>
            <th>生日</th>
            <th>状态</th>
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
                <td><?=CommonFunc::getGenderCn($l->gender)?></td>
                <td><?=$l->birthday?></td>
                <td><?=CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?=Html::a('编辑',['/user/add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>
