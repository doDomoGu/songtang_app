<?php
use yii\bootstrap\BaseHtml;
use yii\bootstrap\Html;
use yun\components\YunFunc;

$this->title = '首页新闻';
?>
<?=BaseHtml::a('添加新闻',['add-and-edit'],['class'=>'btn btn-primary'])?>
        <p></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>标题</th>
                    <th>图片预览</th>
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
                    <?php $img_url =YunFunc::getResourcePath($l->img_url);?>
                    <td><?=Html::img($img_url,['width'=>200,'title'=>$img_url,'alt'=>$img_url,'style'=>'border:1px solid #333;'])?></td>
                    <td><?=$l->status==1?'启用':'禁用'?></td>
                    <td><?=$l->ord?></td>
                    <td><?=BaseHtml::a('编辑',['add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])?></td>
                </tr>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>