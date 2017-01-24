<?php
use api\components\CommonFunc;
$classList  = ['Oa'/*,'User'*/];
?>

<?php foreach($classList as $c):?>
<section>
    <?php
        $help = CommonFunc::getHelp($c);
        $title = $help['title'];
        $list = $help['list'];
    ?>

    <p>
        <b><?=$title?></b>
    </p>
    <?php if(!empty($list)):?>
    <table class="table table-bordered table-hover">
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l['title']?></td>
                <td><?=$l['desc']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php endif;?>
</section>
<?php endforeach;?>