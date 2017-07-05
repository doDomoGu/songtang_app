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
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->name?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <!--<td>
                    <?/*=Html::a('编辑',['/user/add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])*/?>
                </td>-->
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>
