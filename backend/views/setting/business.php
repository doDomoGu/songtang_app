<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;

$this->title = '参数设置 - 业态';
backend\assets\AppAsset::addJsFile($this,'js/main/setting/business.js');
?>
<div style="margin-bottom: 10px;">
    <?=Html::a('新增业态','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
</div>
<section class="panel">
    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>名称</th>
                <th>别名</th>
                <th>相关部门</th>
                <th width="300">排序</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <tbody>
            <?php $i=1;?>
            <?php foreach($list as $l):?>
                <tr>
                    <td><?=$l->id?></td>
                    <td><?=$l->name?></td>
                    <td><?=$l->alias?></td>
                    <td>
                        <?php if(!empty($l->relations)):?>
                            <?php foreach($l->relations as $r):?>
                                <?=$r->department->name?><br/>
                            <?php endforeach;?>
                        <?php else:?>
                            --
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if($l->status==1):?>
                            <?php
                            if($i>1){
                                $upFlag = true;
                            }else{
                                $upFlag = false;
                            }
                            if($i<$count){
                                $downFlag = true;
                            }else{
                                $downFlag = false;
                            }
                            ?>
                            <?=Html::button('<span class="glyphicon glyphicon-arrow-up"></span> 移至顶部',['class'=>'btn btn-xs btn-warning order-change '.($upFlag?'':'disabled'),'data-id'=>$l->id,'data-act'=>'top'])?>
                            <?=Html::button('<span class="glyphicon glyphicon-chevron-up"></span> 移上',['class'=>'btn btn-xs btn-success order-change '.($upFlag?'':'disabled'),'data-id'=>$l->id,'data-act'=>'up'])?>

                            <?=Html::button('<span class="glyphicon glyphicon-chevron-down"></span> 移下',['class'=>'btn btn-xs btn-success order-change '.($downFlag?'':'disabled'),'data-id'=>$l->id,'data-act'=>'down'])?>
                            <?=Html::button('<span class="glyphicon glyphicon-arrow-down"></span> 移至底部',['class'=>'btn btn-xs btn-warning order-change '.($downFlag?'':'disabled'),'data-id'=>$l->id,'data-act'=>'bottom'])?>
                        <?php endif;?>
                    </td>
                    <td><?=CommonFunc::getStatusCn($l->status)?></td>
                    <td>
    <?php if($l->status==1):?>
                        <?=Html::a('相关部门设置','script:void(0)',['data-id'=>$l->id,'data-toggle'=>"modal",'data-target'=>"#editRelationModal",'class'=>'btn btn-xs btn-warning'])?>
                        <?=Html::a('编辑名称','script:void(0)',['data-id'=>$l->id,'data-old-name'=>$l->name,'data-toggle'=>"modal",'data-target'=>"#editModal",'class'=>'btn btn-xs btn-primary'])?>
        <?php endif;?>
                    </td>
                </tr>
            <?php $i++;?>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>

</section>


<?php
Modal::begin([
    'header' => '新增业态',
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
        </form>
    </div>
<?php
Modal::end();
?>

<?php
Modal::begin([
    'header' => '编辑业态名称',
    'id'=>'editModal',
    //'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="editContent">
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-4 control-label">原名称</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <input class="id-value" type="hidden" />
                    <span class="old-name-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">新名称</label>
                <div class="col-sm-6">
                    <input class="form-control new-name">
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="edit-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>

<?php
Modal::begin([
    'header' => '',
    'id'=>'editRelationModal',
    //'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="editRelationContent">
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-4 control-label">业态</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <input class="id-value" type="hidden" />
                    <span class="name-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">相关部门</label>
                <div class="col-sm-8" style="padding-top: 7px;">
                    <div class="relation-checkboxlist">

                    </div>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="edit-relation-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>