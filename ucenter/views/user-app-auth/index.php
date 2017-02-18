<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;

    $this->title = '系统管理员设置';
    ucenter\assets\AppAsset::addJsFile($this,'js/main/user-app-auth/index.js');
?>
<style>
    a.set-auth {
        cursor: pointer;
    }
    a.is-enable {
        color:#00B83F;

    }
    a.is-disable {
        color:#800000;
    }
</style>
<section>
    <!--<div style="margin-bottom: 10px;">
        <?/*=Html::a('新增职员','/user/add-and-edit',['class'=>'btn btn-success'])*/?>
    </div>-->
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th width="60">职员ID</th>
            <th width="240">用户名</th>
            <th width="80">姓名</th>
            <th style="background:#eaeaea;">用户中心</th>
            <th style="background:#eaeaea;">颂唐云后台</th>
            <th style="background:#eaeaea;">颂唐云前台</th>
            <th style="background:#eaeaea;">OA后台</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):$user = $l['user'];$auth = $l['auth'];?>
            <tr>
                <td><?=$user->id?></td>
                <td><?=$user->username?></td>
                <td><?=$user->name?></td>
                <td style="background:#eaeaea;">
                    <?php if($user->id == '10000'):?>
                        <span style="color:#00B83F;">启用</span>
                    <?php else:?>
                    <?=in_array('ucenter-admin',$auth)?
                        '<a class="set-auth is-enable" data-user ="'.$user->id.'" data-app="ucenter-admin" data-act="del">启用</a>':
                        '<a class="set-auth is-disable" data-user ="'.$user->id.'" data-app="ucenter-admin" data-act="add">禁用</a>'?>
                    <?php endif;?>
                </td>
                <td style="background:#eaeaea;">
                    <?=in_array('yun-frontend',$auth)?
                    '<a class="set-auth is-enable" data-user ="'.$user->id.'" data-app="yun-frontend" data-act="del">启用</a>':
                    '<a class="set-auth is-disable" data-user ="'.$user->id.'" data-app="yun-frontend" data-act="add">禁用</a>'?>
                </td>
                <td style="background:#eaeaea;">
                    <?=in_array('yun-admin',$auth)?
                    '<a class="set-auth is-enable" data-user ="'.$user->id.'" data-app="yun-admin" data-act="del">启用</a>':
                    '<a class="set-auth is-disable" data-user ="'.$user->id.'" data-app="yun-admin" data-act="add">禁用</a>'?>
                </td>
                <td style="background:#eaeaea;">
                    <?=in_array('oa-admin',$auth)?
                    '<a class="set-auth is-enable" data-user ="'.$user->id.'" data-app="oa-admin" data-act="del">启用</a>':
                    '<a class="set-auth is-disable" data-user ="'.$user->id.'" data-app="oa-admin" data-act="add">禁用</a>'?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>



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
                <label class="col-sm-4 control-label label1">地区</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="aname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">业态</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="bname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">部门路径</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="pname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">部门选择</label>
                <div class="col-sm-6">
                    <select class="form-control new-did-select">
                    </select>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
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