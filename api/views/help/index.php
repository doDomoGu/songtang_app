<?php
use api\components\ApiFunc;
$classList  = ['Oa','User','Yun'];
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
    <table class="table table-bordered table-hover">
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td class="col-lg-3"><?=strtolower($c).'/'.$l['title']?></td>
                <td class="col-lg-3"><?=$l['param']?></td>
                <td><?=$l['desc']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php endif;?>
</section>
<?php endforeach;?>