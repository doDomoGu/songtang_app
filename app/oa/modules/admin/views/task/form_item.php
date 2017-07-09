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
        <?=Html::button('新增选项',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
        <?=Html::button('清空选项',['class'=>'btn btn-danger del-all-btn'])?>
        <?/*=Html::a('清空选项','javascript:void(0)',['class'=>'btn btn-danger delete-all'])*/?>
        <?php /*endif;*/?>
        <?=Html::a('返回','/admin/task/form',['class'=>'btn btn-default'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>排序</th>
            <th>Key</th>
            <th>标签名</th>
            <th>类型</th>
            <th>选项参数</th>
            <!--<th>状态</th>-->
            <th width="200">操作</th>
        </tr>
        <tbody>
        <?php $count = count($list); $i=0;foreach($list as $l):$i++;?>
            <?php
                $vArr = FormItem::jsonDecodeValue($l->item_value);
                $label = $vArr['label'];
                $input_type = $vArr['input_type'];
                $input_type_cn = $vArr['input_type_cn'];
                $input_options = $vArr['input_options'];
                $input_options_html = $vArr['input_options_html'];
                $label_width = $vArr['label_width'];
                $input_width = $vArr['input_width'];
            ?>
            <tr>
                <td><?=$l->ord?></td>
                <td><?=$l->item_key?></td>
                <td><?=$label?></td>
                <td><?=$input_type_cn?></td>
                <td><?=str_replace("\n","<br/>",$input_options_html)?></td>
                <!--<td><?/*=\common\components\CommonFunc::getStatusCn($l->status)*/?></td>-->
                <td>
                    <?=Html::button('编辑',['data-toggle'=>"modal",'data-target'=>"#editModal",'class'=>'btn btn-primary btn-xs',
                        'data-key'=>$l->item_key,
                        'data-label'=>$label,
                        'data-label_width'=>$label_width,
                        'data-input_width'=>$input_width,
                        'data-input_type'=>$input_type,
                        'data-id'=>$l->id,
                        'data-input_options'=>$input_options_html])?>
                    <?=Html::button('删除',['class'=>'btn btn-danger btn-xs del-btn','data-id'=>$l->id])?>
                    <?=Html::button('上移',['class'=>'btn btn-success btn-xs ord-up-btn'.($i==1?' disabled':''),'data-id'=>$l->id])?>
                    <?=Html::button('下移',['class'=>'btn btn-success btn-xs ord-down-btn'.($i==$count?' disabled':''),'data-id'=>$l->id])?>
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
                <label class="col-sm-4 control-label label1">插入位置</label>
                <div class="col-sm-6">
                    <?=Html::dropDownList('create-position','last',$positionList,['class'=>'form-control create-position','style'=>'width:100%;'])?>
                </div>
            </div>
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
                <label class="col-sm-4 control-label label1">标签宽度</label>
                <div class="col-sm-6">
                    <input class="form-control create-label_width">
                    *建议120 或者纵向排列为 444   总宽度888
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">输入框宽度</label>
                <div class="col-sm-6">
                    <input class="form-control create-input_width">
                    *建议324（两栏）或者768（一栏）   或者纵向排列为 444
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">类型</label>
                <div class="col-sm-6">
                    <?=\yii\helpers\Html::dropDownList('input_type-select',null,FormItem::itemType(),['class'=>'form-control create-input_type-select'])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">选项参数</label>
                <div class="col-sm-6">
                    <textarea class="form-control create-input_options" ></textarea>
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
    'header' => '编辑选项',
    'id'=>'editModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="editContent">
        <form class="form-horizontal" role="form">
            <input class="form-id" type="hidden" value="<?=$form->id?>" />
            <input class="edit-form-item-id" type="hidden" value="" />

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
                <label class="col-sm-4 control-label label1">标签宽度</label>
                <div class="col-sm-6">
                    <input class="form-control create-label_width">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">输入框宽度</label>
                <div class="col-sm-6">
                    <input class="form-control create-input_width">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">类型</label>
                <div class="col-sm-6">
                    <?=\yii\helpers\Html::dropDownList('input_type-select',null,FormItem::itemType(),['class'=>'form-control create-input_type-select'])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">选项参数</label>
                <div class="col-sm-6">
                    <textarea class="form-control create-input_options" ></textarea>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="edit-submit-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>