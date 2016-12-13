<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
    if($p_id>0)
        $this->title = '参数设置 - 扩展职位';
    else
        $this->title = '参数设置 - 职位';

    backend\assets\AppAsset::addJsFile($this,'js/main/setting/position.js');
?>
<section>
    <p>
        <?php if($p_id>0):?>
            <h3>对应基础职位： <?=$parent->name?></h3>
            <?=Html::a('返回',['/setting/position'],['class'=>'btn btn-primary'])?>
            <?=Html::a('添加扩展职位','script:void(0)',['data-p_id'=>$p_id,'data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
        <?php endif;?>
    </p>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>名称</th>
            <th>别名</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->name?></td>
                <td><?=$l->alias?></td>
                <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?php if($p_id==0):?>
                        <?=Html::a('进入扩展职位信息',['/setting/position','p_id'=>$l->id],['class'=>'btn btn-xs btn-success'])?>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</section>

<?php if($p_id>0):?>
<?php
Modal::begin([
    'header' => '新增扩展职位',
    'id'=>'createModal',
    //'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
<div id="createContent">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-sm-4 control-label">名称</label>
            <div class="col-sm-6">
                <input class="form-control create-name">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">别名</label>
            <div class="col-sm-6">
                <input class="form-control create-alias">
                <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <button type="button" class="btn btn-success" id="create-btn">提交</button>
            </div>
        </div>
        <input type="hidden" class="position_p_id"  value="<?=$p_id?>" />
    </form>
</div>
<?php
Modal::end();
?>

<?php endif;?>