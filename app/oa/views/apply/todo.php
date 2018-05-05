<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\Apply;

$this->title = '待办事项';
oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/my.css');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/todo.css');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/table.css');

oa\assets\AppAsset::addJsFile($this,'script/jquery-ui/jquery-ui.min.js');
oa\assets\AppAsset::addCssFile($this,'script/jquery-ui/jquery-ui.min.css');

?>
<section class="panel panel-default">
    <div class="panel-heading">
        <?=Html::img('/images/main/apply/apply_heading_2.png')?>
        <?=$this->title?>
        <?/*=Html::img('/images/main/apply/create-icon-2.png')*/?>
    </div>
    <div class="panel-body">
        <div id="search-form" style="padding:10px;">
            <form action="" method="get">
                <div>
                    <label>申请类型</label>
                    <?=Html::dropDownList('search[category]',$search['category'],\oa\models\TaskCategory::getDropdownList(),['class'=>'search_category','prompt'=>'==请选择==','encode'=>false])?>
                    <label style="padding-left:100px;">时间范围</label>
                    <?=Html::textInput('search[add_time_start]',$search['add_time_start'],['class'=>'search_time'])?>  ~
                    <?=Html::textInput('search[add_time_end]',$search['add_time_end'],['class'=>'search_time'])?>
                    <button style="float:right;" type="submit" id="searchBtn" >检索</button>
                </div>
            </form>
        </div>
        <table>
            <tr>
                <th><span>#</span></th>
                <th><span><?=Html::img('/images/main/apply/th_1.png',['style'=>''])?> &nbsp; 标题</span></th>
                <th><span><?=Html::img('/images/main/apply/th_2.png',['style'=>''])?> &nbsp; 任务表</span></th>
                <th><span><?=Html::img('/images/main/apply/th_2.png',['style'=>''])?> &nbsp;申请人</span></th>
                <th><span><?=Html::img('/images/main/apply/th_3.png',['style'=>''])?> &nbsp; 申请时间</span></th>
                <th><span><?=Html::img('/images/main/apply/th_5.png',['style'=>''])?> &nbsp; 状态</span></th>
                <th class="last"><span><?=Html::img('/images/main/apply/th_4.png',['style'=>''])?> &nbsp; 操作</span></th>
            </tr>
            <tbody>
            <?php foreach($list as $l):?>
                <tr>
                    <td><span><?=$l->id?></span></td>
                    <td><span><?=$l->title?></span></td>
                    <td><span><?=$l->task->title?></span></td>
                    <td><span><?=$l->applyUser->name?></span></td>
                    <td><span><?=substr($l->add_time,0,-3)?></span></td>
                    <td><span><?=Apply::getStatusCn($l->status)?></span></td>
                    <td class="last">
                        <span><?=Html::a('进行操作','/apply/operation?id='.$l->id,['class'=>'btn btn-success btn-xs'])?></span>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages ,
        ])?>
    </div>

</section>
<?php
/*Modal::begin([
    'header' => '申请表详情',
    'id'=>'infoModal',
    'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
*/?><!--
    <div id="infoContent">
        <div class="content"></div>
        <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
    </div>
--><?php
/*Modal::end();
*/?>