<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yun\models\Dir;
?>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-5\">{input}{hint}</div>\n<div class=\"col-lg-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>

        <div class="form-group">
            <label class="col-lg-2 control-label" >父级目录</label>
            <div class="col-lg-6" style="padding:7px 15px; font-weight: bold;color:#ED1B23;">
                <?php if($model->p_id==0):?>
                    ---
                <?php else:?>
                    <?=Dir::getFullRoute($model->p_id)?>
                <?php endif;?>
            </div>
        </div>

        <?= $form->field($model, 'name') ?>

        <?= $form->field($model, 'alias',['inputOptions'=>['disabled'=>$action=='add'?false:true]]) ?>

        <?= $form->field($model, 'link')->hint('填写后，会使这个目录转变一个链接，谨慎填写') ?>

        <?/*= $form->field($model, 'describe')->textarea() */?>

        <?/*= $form->field($model, 'type',[
            'template'=>"{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
        ])->dropDownList([1=>'企业运营中心',2=>'发展资源中心',3=>'工具应用中心',4=>'项目资源中心',5=>'学习共享中心']) */?>



        <?= $form->field($model, 'ord',[
            'template'=>"{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
        ]) ?>

        <?= $form->field($model, 'is_leaf',['inputOptions'=>['disabled'=>$action=='add'?false:true]])->dropDownList([0=>'否',1=>'是']) ?>
<?= $form->field($model, 'status',[
    'template'=>"{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
])->dropDownList([0=>'禁用',1=>'启用']) ?>


        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?= Html::button('返回', ['class' => 'btn btn-default', 'name' => 'back-button','onclick'=>"history.go(-1);"]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
