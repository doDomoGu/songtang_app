<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use ucenter\models\District;
    use ucenter\models\Industry;
    use ucenter\models\Company;
    use ucenter\models\Department;
    use ucenter\models\Position;
    //yun\assets\AppAsset::addJsFile($this,'js/main/manage/user/add_and_edit.js');
?>

        <?php $form = ActiveForm::begin([
            'id' => 'user-form',
            'options' => ['class' => 'form-horizontal','autocomplete'=>'off'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-5\">{input}{hint}</div>\n<div class=\"col-lg-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>
<input style="display:none">
        <?/*= $form->field($model, 'username') */?>
        <?php if($model->getScenario()=='create'):?>
            <?= $form->field($model, 'username',['inputOptions'=>['autocomplete'=>'off']]) ?>
        <?php else:?>
            <?= $form->field($model, 'username',['inputOptions'=>['autocomplete'=>'off','disabled'=>true]]) ?>
        <?php endif;?>
<input style="display:none">

        <?php if($model->getScenario()=='create'):?>
            <div class="form-group field-userform-position_id">
                <label for="userform-position_id" class="col-lg-2 control-label">密码</label>
                <div class="col-lg-10">
                    自动随机创建，向管理员询问<!--在添加职员成功后，在给职员邮箱发送的邮件中查看密码-->
                </div>
            </div>
        <?php else:?>
            <?/*= $form->field($model, 'password')->passwordInput() */?>
            <?= $form->field($model, 'password',['inputOptions'=>['autocomplete'=>'off']])->passwordInput() ?>
            <input style="display:none">
            <?/*= $form->field($model, 'password2')->passwordInput() */?>
            <?= $form->field($model, 'password2',['inputOptions'=>['autocomplete'=>'off']])->passwordInput() ?>
        <?php endif;?>
        <?= $form->field($model, 'name') ?>

        <?/*= $form->field($model, 'position_id') */?>


<?= $form->field($model, 'district_id')->dropDownList(District::getItems()) ?>
<?= $form->field($model, 'industry_id')->dropDownList(Industry::getItems()) ?>
<?= $form->field($model, 'company_id')->dropDownList(Company::getItems()) ?>
<?= $form->field($model, 'department_id')->dropDownList(Department::getItems(),['encode'=>false]) ?>
<?= $form->field($model, 'position_id')->dropDownList(Position::getItems(),['encode'=>false]) ?>
        <?= $form->field($model, 'gender')->dropDownList([0=>'N/A',1=>'男',2=>'女']) ?>

        <?= $form->field($model, 'mobile') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'birthday')->hint('格式:2010-10-10') ?>

        <?= $form->field($model, 'join_date')->hint('格式:2010-10-10') ?>
        <?= $form->field($model, 'contract_date')->hint('格式:2010-10-10') ?>

        <?= $form->field($model, 'ord',[
            'template'=>"{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
        ])->dropDownList([5=>5,4=>4,3=>3,2=>2,1=>1])  ?>
        <?= $form->field($model, 'status',[
            'template'=>"{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
        ])->dropDownList([0=>'禁用',1=>'启用']) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?=$model->getScenario()=='update'?'<br/>*编辑职员时，如无需更改密码，密码字段留空':''?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
