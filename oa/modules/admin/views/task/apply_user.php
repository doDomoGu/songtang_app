<?php
use yii\bootstrap\Html;
    use common\components\CommonFunc;
    $this->title = '【'.$task->title.'】的发起人设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/apply_user.js');
?>
<section>
    <input type="hidden" class="task-id" value="<?=$task->id?>" />
    <div class="errmsg" style="color:red;display:none;"></div>
    <?php if($task->set_complete==0):?>
    <button class="btn btn-success submit-btn" > 保存 </button>
    <?php endif;?>
    <?=Html::a('返回','/admin/task',['class'=>'btn btn-default'])?>
    <table class="table table-bordered" style="margin: 10px 0;background: #fafafa;">
        <tr>
            <th></th>
            <th>职员ID</th>
            <th>职员姓名</th>
            <th>地区</th>
            <th>业态</th>
            <th>部门</th>
            <th>职位</th>
            <!--<th>操作</th>-->
        </tr>
        <tbody>
        <?php foreach($userList as $l):?>
            <?php $userInfo = CommonFunc::getByCache(\login\models\UserIdentity::className(),'findIdentityOne',[$l->id],'ucenter:user/identity'); ?>
            <?php if($userInfo!=null):?>
            <tr>
                <td class="text-center"><input type="checkbox" name="user_check[]" value="<?=$l->id?>"  <?=in_array($l->id,$applyUserList)?'checked="checked"':''?> class="user-checkbox" /></td>
                <td><?=$l->id?></td>
                <td><?=$userInfo->name?></td>
                <td><?=$userInfo->district?></td>
                <td><?=$userInfo->industry?></td>
                <td><?=$userInfo->department?></td>
                <td><?=$userInfo->position?></td>
            </tr>
            <?php else:?>
                <tr>
                    <td></td>
                    <td><?=$l->id?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif;?>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php if($task->set_complete==0):?>
        <button class="btn btn-success submit-btn" > 保存 </button>
    <?php endif;?>
</section>


