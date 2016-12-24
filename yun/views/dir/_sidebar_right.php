<?php
    yun\assets\AppAsset::addCssFile($this,'css/main/dir/_right.css');
    $user = Yii::$app->user->identity;
    $position = $this->context->position;
?>
<table id="right-info-table">
    <tr>
        <th colspan="2">个人信息中心</th>
    </tr>
    <tr>
        <td>姓名</td>
        <td><?=$user->name?></td>
    </tr>
    <tr>
        <td>入职</td>
        <td><?=$user->join_date?></td>
    </tr>
    <tr>
        <td>合同</td>
        <td><?=$user->contract_date?></td>
    </tr>
    <tr>
        <td>地方</td>
        <td><?=$user->area?></td>
    </tr>
    <tr>
        <td>业态</td>
        <td><?=$user->business?></td>
    </tr>
    <tr>
        <td>部门</td>
        <td><?=$user->department?></td>
    </tr>
    <tr>
        <td>职位</td>
        <td><?=$user->position?></td>
    </tr>
    <tr>
        <td>职责</td>
        <td>查看详情</td>
    </tr>
    <tr>
        <td>积分</td>
        <td>0</td>
    </tr>
</table>
