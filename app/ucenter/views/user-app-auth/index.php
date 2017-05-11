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
    <div style="margin-bottom: 10px;">
        <?=Html::Button('添加用户权限',['value'=>'','class'=> 'btn btn-success','id'=>'modalButton','data-toggle'=>"modal",'data-target'=>"#addModal"])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th width="60">职员ID</th>
            <th width="240">用户名</th>
            <th width="80">姓名</th>
            <th style="background:#eaeaea;">用户中心</th>
            <th style="background:#eaeaea;">颂唐云<br/>前台/前台管理/后台</th>
            <th style="background:#eaeaea;">OA<br/>前台/前台管理/后台</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):
            //$user = $l['user'];
        $authList = \ucenter\models\UserAppAuth::getAuthList($l->id);?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->username?></td>
                <td><?=$l->name?></td>
                <td style="background:#eaeaea;">

                    <?=$authList['isUcenterAdmin']?
                        '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="ucenter-admin" data-act="del">启用</a>':
                        '<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="ucenter-admin" data-act="add">禁用</a>'?>
                </td>
                <td style="background:#eaeaea;">
                    <?=$authList['isYunFrontend']?
                    '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="yun-frontend" data-act="del">启用</a>':
                        ($authList['isYunFrontendAdmin']?'<a class="is-enable" data-user ="'.$l->id.'" data-app="yun-frontend">启用*</a>':'<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="yun-frontend" data-act="add">禁用</a>')?>
                    /

                    <?=$authList['isYunFrontendAdmin']?
                        '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="yun-frontend-admin" data-act="del">启用</a>':
                        '<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="yun-frontend-admin" data-act="add">禁用</a>'?>

                    /

                    <?=$authList['isYunBackendAdmin']?
                        '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="yun-backend-admin" data-act="del">启用</a>':
                        '<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="yun-backend-admin" data-act="add">禁用</a>'?>
                </td>
                <td style="background:#eaeaea;">
                    <?=$authList['isOaFrontend']?
                        '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="oa-frontend" data-act="del">启用</a>':
                        ($authList['isOaFrontendAdmin']?'<a class="is-enable" data-user ="'.$l->id.'" data-app="oa-frontend" >启用*</a>':'<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="oa-frontend" data-act="add">禁用</a>')?>
                    /

                    <?=$authList['isOaFrontendAdmin']?
                        '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="oa-frontend-admin" data-act="del">启用</a>':
                        '<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="oa-frontend-admin" data-act="add">禁用</a>'?>

                    /
                    <?=$authList['isOaBackendAdmin']?
                    '<a class="set-auth is-enable" data-user ="'.$l->id.'" data-app="oa-backend-admin" data-act="del">启用</a>':
                    '<a class="set-auth is-disable" data-user ="'.$l->id.'" data-app="oa-backend-admin" data-act="add">禁用</a>'?>
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
                <label class="col-sm-4 control-label label1">用户ID</label>
                <div class="col-sm-6">
                    <input class="form-control user-id" id="add-user-id">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">应用名称</label>
                <div class="col-sm-6">
                    <select class="form-control app-select" id="add-app">
                        <?php foreach(\ucenter\models\UserAppAuth::getAppCnArr() as $v=>$a):?>
                            <option value="<?=$v?>"><?=$a?></option>
                        <?php endforeach;?>
                    </select>
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