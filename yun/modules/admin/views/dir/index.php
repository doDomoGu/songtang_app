<?php
use yii\bootstrap\BaseHtml;
use yun\components\DirFunc;
 yun\assets\AppAsset::addJsFile($this,'js/manage-dir.js');
?>
<?/*=BaseHtml::a('添加目录（暂时不可用）',['dir-add-and-edit'],['class'=>'btn btn-primary disabled'])*/?>
<?=BaseHtml::a('重新生成目录缓存*',['dir-deploy-cache'],['class'=>'btn btn-warning'])?> *在修改或添加过目录项后可点击，运行时间较长非必要无需重新生成
<p></p>

板块选择：<?=BaseHtml::dropDownList('dir-select-1',$dirLvl_1?$dirLvl_1->id:'',$dirList_1,['encode'=>false,'id'=>'dir-select-1','prompt'=>'===请选择==='])?>
<?php if(!empty($dirList_2)):?>
    <p></p>
    目录选择：<?=BaseHtml::dropDownList('dir-select-2',$dirLvl_2?$dirLvl_2->id:'',$dirList_2,['encode'=>false,'id'=>'dir-select-2','prompt'=>'===请选择==='])?>
<?php endif;?>
<p></p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <!--<th>排序</th>-->
        <th>目录</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($list)):?>
        <?php foreach($list as $l):?>
            <tr>
                <th scope="row"><?=$l->id?></th>
                <!--<td><?/*=$l->ord*/?></td>-->
                <td><?=$l->name?> <span class="badge" title="<?=DirFunc::getFullRoute($l->id)?>">i</span></td>
                <td><?=DirFunc::getIsLeaf($l->is_leaf)?></td>
                <td>
                    <?=BaseHtml::a('编辑',['dir-add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])?>
                    <?php if($l->is_leaf==0):?>
                        <?=BaseHtml::a('添加子目录',['dir-add-and-edit','p_id'=>$l->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?php else:?>
                        <?=BaseHtml::a('查看对应职位权限',['dir-position-permission','dir_id'=>$l->id],['class'=>'btn btn-success btn-xs'])?>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    </tbody>
</table>
