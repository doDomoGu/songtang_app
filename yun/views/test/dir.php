<?php
use yun\models\DirPermission;
use yun\models\Dir;
?>
<?/*=BaseHtml::a('添加目录（暂时不可用）',['dir-add-and-edit'],['class'=>'btn btn-primary disabled'])*/?>
<?/*=BaseHtml::a('重新生成目录缓存*',['dir-deploy-cache'],['class'=>'btn btn-warning'])*/?><!-- *在修改或添加过目录项后可点击，运行时间较长非必要无需重新生成
<p></p>-->

<table class="table table-bordered">
    <thead>
    <tr>
        <th width="50">#</th>
        <!--<th>排序</th>-->
        <th width="300">目录</th>
        <th width="80">类型</th>
        <th>权限</th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($list)):?>
        <?php foreach($list as $l):?>
            <tr>
                <th scope="row"><?=$l->id?></th>
                <!--<td><?/*=$l->ord*/?></td>-->
                <td><?=$l->name?> <span class="glyphicon glyphicon-info-sign" title="<?=DirFunc::getFullRoute($l->id)?>"></span></td>
                <td>
                    <?php if($l->link!=''):?>
                        <span class="label label-warning">链接</span>
                    <?php else:?>
                    <?=Dir::getIsLeaf($l->is_leaf)?></td>
                <?php endif;?>
                <td>
                    <?php if(isset($pmList[$l->id]) && !empty($pmList[$l->id])):?>
                    <?php foreach($pmList[$l->id] as $pm):?>
                        <div>
                            匹配用户类型：<?=$pm['type']?>
                            操作分类：<?=DirPermission::getOperationName($pm['operation'])?>
                            模式：<?=DirPermission::getModeName($pm['mode'])?>
                        </div>
                    <?php endforeach;?>
                    <?php endif;?>

                    <?/*=BaseHtml::a('编辑',['add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])*/?><!--
                    <?php /*if($l->link==""):*/?>
                        <?/*=BaseHtml::a('权限设置',['permission','dir_id'=>$l->id],['class'=>'btn btn-success btn-xs'])*/?>

                        <?php /*if($l->is_leaf==0):*/?>
                            <?/*=BaseHtml::a('添加子目录',['add-and-edit','p_id'=>$l->id],['class'=>'btn btn-warning btn-xs disabled'])*/?>
                        <?php /*else:*/?>

                        <?php /*endif;*/?>
                    --><?php /*endif;*/?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    </tbody>
</table>
