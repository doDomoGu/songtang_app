<?php
    use yii\bootstrap\BaseHtml;

?>
<?php $i=0;?>
<?php foreach($dirList as $list):?>
    <?php if(!empty($list)):?>
    <?php
        $option = [
            'encode'=>false,
            'class'=>'pos-select-group'];
        //if($i==0)
            $option['prompt'] = '===请选择===';
    ?>
    <?=BaseHtml::dropDownList(
        'pos-select',
        isset($selected[$i])?$selected[$i]:'',
        $list,
        $option
    )?>
        <br/>
    <?php endif;?>
<?php $i++;?>
<?php endforeach;?>

<?php $i=0;?>
<?php foreach($dir2List as $list):?>
    <?php if(!empty($list)):?>
        <?php
        $option = [
            'encode'=>false,
            'class'=>'pos2-select-group'];
        //if($i==0)
        $option['prompt'] = '===请选择===';
        ?>
        <?=BaseHtml::dropDownList(
            'pos2-select',
            isset($selected2[$i])?$selected2[$i]:'',
            $list,
            $option
        )?>
        <br/>
    <?php endif;?>
    <?php $i++;?>
<?php endforeach;?>
