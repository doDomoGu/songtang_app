<?php
use api\components\ApiFunc;
$classList  = ['Oa'/*,'User','Yun'*/];
?>

<?php foreach($classList as $c):?>
<section>
    <?php
        $help = ApiFunc::getHelp($c);
        $title = $help['title'];

        $list = $help['list'];
    ?>

    <p>
        <b><?=$title?></b>
    </p>
    <?php if(!empty($list)):?>
    <?php foreach($list as $action => $l):?>
            <hr/>
    <p>
        接口：<?=strtolower($c).'/'.$action?><br/>
        接口名称：<?=$l['title']?><br/>
        接口说明：<?=$l['explain']?>
    </p>
    <table class="table table-bordered table-hover">
        <tbody>
            <tr>
                <td class="col-lg-2">名称</td>
                <td class="col-lg-2">类型</td>
                <td class="col-lg-2">必填</td>
                <td class="col-lg-6">说明</td>
            </tr>
            <?php foreach($l['param'] as $key=>$p):?>
            <tr>
                <td><?=$key?></td>
                <td><?=$p['type']?></td>
                <!--<td class="col-lg-2"><?/*=strtolower($c).'/'.$l['title']*/?></td>-->
                <td><?=isset($p['required']) && $p['required']?'是':'否'?></td>
                <td><?=$p['explain']?></td>
               <!-- <td><?/*=$l['desc']*/?></td>-->
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php endforeach;?>
    <?php endif;?>
</section>
<?php endforeach;?>