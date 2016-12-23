<?php
use yii\bootstrap\BaseHtml;
use yii\bootstrap\Html;
use yun\components\CommonFunc;
use yii\bootstrap\Modal;

$this->title = '招聘信息';
?>
<?=BaseHtml::a('添加招聘',['add-and-edit'],['class'=>'btn btn-primary'])?>
        <p></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>标题</th>
                    <th>状态</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($list)):?>
            <?php foreach($list as $l):?>
                <tr>
                    <th scope="row"><?=$l->id?></th>
                    <td><?=$l->title?></td>
                    <td><?=$l->status==1?'启用':'禁用'?></td>
                    <td><?=$l->ord?></td>
                    <td><?=BaseHtml::a('编辑',['add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])?></td>
                </tr>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>