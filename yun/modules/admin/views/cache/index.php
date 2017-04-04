<?php
    use yii\bootstrap\Html;
?>
<style>
    .row-btn {
        width:240px;
        text-align:right;
        padding-right:20px;
    }
    .row-result {
        width:500px;
    }
</style>
<table class="table table-bordered">
    <tr>
        <td class="row-btn">操作</td>
        <td class="row-result">结果</td>
    </tr>
    <?php foreach($list as $k => $l):?>
    <tr>
        <td class="row-btn">
            <?=Html::a('清除【'.$l.'】相关的缓存',['/admin/cache','key'=>$k],['class'=>'btn btn-primary'])?>
        </td>
        <td class="row-result">
            <?php if(isset($cleared[$k])):?>
                <?php foreach($cleared[$k] as $c):?>
                        <?=$c?>  已清除
                <?php endforeach;?>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td class="row-btn">
            <?=Html::a('清除全部缓存',['/admin/cache','key'=>'all'],['class'=>'btn btn-danger'])?>
        </td>
        <td class="row-result">
            <?php if(isset($cleared['all'])):?>
                <?php foreach($cleared['all'] as $c):?>
                    <?=$c?>  已清除
                <?php endforeach;?>
            <?php endif;?>
        </td>
    </tr>
</table>

