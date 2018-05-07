<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Company;
use ucenter\models\Department;
use ucenter\models\Position;


    $this->title = '职员管理';
    //app\assets\AppAsset::addJsFile($this,'js/main/structure/index.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?=Html::a('新增职员','/user/add-and-edit',['class'=>'btn btn-success'])?>
        <?=Html::a('全部导出','/user/export-all',['class'=>'btn btn-warning'])?>
        <?/*=Html::a('清除user缓存','/user/clear-cache',['class'=>'btn btn-danger'])*/?>
    </div>
    <div id="search-form" style="padding:10px;">
        <form action="" method="get">
            <div>
                <label>用户名：</label>
                <?=Html::textInput('search[username]',$search['username'])?>
                <label>姓名：</label>
                <?=Html::textInput('search[name]',$search['name'])?>

<!--                --><?//=Html::dropDownList('search[username]',$search['username'],\oa\models\TaskCategory::getDropdownList(),['class'=>'search_category','prompt'=>'==请选择==','encode'=>false])?>
<!--                <label style="padding-left:100px;">时间范围</label>-->
<!--                --><?//=Html::textInput('search[add_time_start]',$search['add_time_start'],['class'=>'search_time'])?><!--  ~-->
<!--                --><?//=Html::textInput('search[add_time_end]',$search['add_time_end'],['class'=>'search_time'])?>
<!--            </div>-->
<!--            <div>-->
<!--                <label>状态</label>-->
<!--                --><?//=Html::checkboxList('search[status]',$search['status'],Apply::getStatusItems(),['tag'=>'span','separator'=>' '])?>
                <button style="float:right;" type="submit" id="searchBtn" >检索</button>
            </div>
        </form>
    </div>
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
    <?php echo \yii\widgets\LinkPager::widget([
        'pagination' => $pages ,
    ])?>
</section>
