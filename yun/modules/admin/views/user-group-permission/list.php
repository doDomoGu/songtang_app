<?php
use yii\bootstrap\BaseHtml;
use yii\bootstrap\Html;
use yun\components\CommonFunc;
use yii\bootstrap\Modal;


\yun\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/user-group-permission/index.js');
$this->title = '用户组权限';
?>
    <div style="margin-bottom: 10px;">
        <?=Html::Button('添加',['value'=>'','class'=> 'btn btn-success','id'=>'modalButton','data-toggle'=>"modal",'data-target'=>"#addModal"])?>
    </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>用户组名称</th>
                    <th>人数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($list)):?>
            <?php foreach($list as $l):?>
                <tr>
                    <th scope="row"><?=$l->id?></th>
                    <td><?=$l->name?></td>
                    <td><?=count($l->users)?></td>
                    <td>---</td>
                </tr>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>

<?php
Modal::begin([
    'header' => '<span class="modal-title"></span>',
    'id'=>'addModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="addContent">
        <form class="form-horizontal" role="form">
            <input class="aid-value" type="hidden" />
            <input class="bid-value" type="hidden" />
            <input class="p_id-value" type="hidden" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">用户组名称</label>
                <div class="col-sm-6">
                    <input class="form-control group-name" id="add-group-name">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="add-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>