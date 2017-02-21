<?php
use yun\models\DirPermission;

    $this->title = '目录权限 - 用户';
    //app\assets\AppAsset::addJsFile($this,'js/main/structure/index.js');

function showStatus($bool){
    if($bool){
        echo '<span style="color:greenyellow;" class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
    }else{
        echo '<span style="color:orangered;" class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

    }
}

?>
<section>
    <?php if($user):?>
    <section>
        <h3>当前查看职员： <?=$user->getFullPositionRoute().' > '.$user->name?></h3>
    </section>

    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>目录ID</th>
            <th>目录名称</th>
            <th class="text-center">目录限制属性</th>
            <th class="text-center">上传</th>
            <th class="text-center">上传<br/>（限制属性）</th>
            <th class="text-center">下载</th>
            <th class="text-center">下载<br/>（限制属性）</th>
        </tr>
        <tbody>
        <?php foreach($dirList as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->name?></td>
                <td width="120" class="text-center"><?=showStatus($l->attr_limit==1)?></td>
                <?php
                    $up1 = DirPermission::isAllow($l->id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_UPLOAD,$user);
                    $up2 = DirPermission::isAllow($l->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT,DirPermission::OPERATION_UPLOAD,$user);
                    $down1 = DirPermission::isAllow($l->id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_DOWNLOAD,$user);
                    $down2 = DirPermission::isAllow($l->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT,DirPermission::OPERATION_DOWNLOAD,$user);
                ?>
                <td width="120" class="text-center"><?=showStatus($up1)?></span></td>
                <td width="120" class="text-center"><?=showStatus($up2)?></span></td>
                <td width="120" class="text-center"><?=showStatus($down1)?></span></td>
                <td width="120" class="text-center"><?=showStatus($down2)?></span></td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
        用户ID 错误！！
    <?php endif;?>
</section>
