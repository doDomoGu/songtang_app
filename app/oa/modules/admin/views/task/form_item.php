<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\Flow;
use yii\helpers\Url;
use oa\models\FormItem;

    $this->title = '【'.$form->title.'】的选项设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/form_item.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?php /*if($task->set_complete==0):*/?>
        <?=Html::a('新增选项','javascript:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
        <?/*=Html::a('清空选项','javascript:void(0)',['class'=>'btn btn-danger delete-all'])*/?>
        <?php /*endif;*/?>
        <?=Html::a('返回','/admin/task/form',['class'=>'btn btn-default'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>Key</th>
            <th>标签名</th>
            <th>类型</th>
            <th>选项参数</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <?php
                $valueArr = FormItem::jsonDecodeValue($l->item_value);
            ?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->item_key?></td>
                <td><?=$valueArr['label']?></td>
                <td><?=$valueArr['type_cn']?></td>
                <td><?=$valueArr['options']?></td>
                <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?/*=Html::a('编辑','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#editModal",'class'=>'btn btn-primary btn-xs','data-title'=>$l->title,'data-type'=>$l->type,'data-user-id'=>$l->user_id,'data-id'=>$l->id])*/?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '新增选项',
    'id'=>'createModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="createContent">
        <form class="form-horizontal" role="form">
            <input class="form-id" type="hidden" value="<?=$form->id?>" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">key</label>
                <div class="col-sm-6">
                    <input class="form-control create-key">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">标签名</label>
                <div class="col-sm-6">
                    <input class="form-control create-label">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">类型</label>
                <div class="col-sm-6">
                    <?=\yii\helpers\Html::dropDownList('type-select',null,FormItem::itemType(),['class'=>'form-control create-type-select'])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">选项参数</label>
                <div class="col-sm-6">
                    <textarea class="form-control create-options" ></textarea>
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
    'header' => '编辑流程',
    'id'=>'editModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
<div id="editContent">
    <form class="form-horizontal" role="form">
        <input class="task-id" type="hidden" value="<?=$form->id?>" />
        <input class="edit-flow-id" type="hidden"  />
        <div class="form-group">
            <label class="col-sm-4 control-label label1">标题</label>
            <div class="col-sm-6">
                <input class="form-control create-title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">类型</label>
            <div class="col-sm-6">
                <select class="form-control create-type-select">
                    <?=Flow::getOptions()?>
                </select>
            </div>
        </div>
        <div class="form-group" style="display:none;">
            <label class="col-sm-4 control-label label1">是否允许转发</label>
            <div class="col-sm-6">
                <select class="form-control create-enable-transfer-select">
                    <option value="0">禁止</option>
                    <option value="1">允许</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">职员</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('user-select','',\common\components\CommonFunc::getByCache(\ucenter\models\User::className(),'getItems',[],'ucenter:user/items'),['class'=>"form-control create-user-select",'prompt'=>'---'])?>
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
