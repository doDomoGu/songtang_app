<?php
    use yii\helpers\BaseHtml;
    use app\components\CommonFunc;
    app\assets\AppAsset::addCssFile($this,'css/main/user/sign.css');
    app\assets\AppAsset::addJsFile($this,'js/main/user/sign.js');
?>
<section id="sidebar-right">
    <div class="sign-btn">
    <?php if($signTodayFlag):?>
        <?=BaseHtml::a('今日已签到','',['class'=>'btn btn-primary btn-lg btn-sign disabled'])?>
    <?php elseif(CommonFunc::isHoliday(date('Y-m-d'))):?>
        <?=BaseHtml::a('今天是节假日',['/user/sign-in'],['class'=>'btn btn-primary btn-lg btn-sign disabled'])?>
    <?php else:?>
        <?=BaseHtml::a('今日签到',['/user/sign-in'],['class'=>'btn btn-primary btn-lg btn-sign'])?>
    <?php endif;?>
    </div>
    <div class="sign-text">
        本月共计签到 <?=count($signList)?> 天
    </div>
</section>
<section id="calendar">
    <div id="cal-title" class="clearfix">
        <span class="prev-btn"><?=BaseHtml::a('< 上月',$prevLink,['class'=>'btn btn-primary btn-xs'])?></span>
        <span class="ym"><?=$y?> 年 <?=$m?> 月</span>
        <span class="next-btn"><?=BaseHtml::a('下月 >',$nextLink,['class'=>'btn btn-primary btn-xs'])?></span>
    </div>

    <table class="table-bordered">
        <tr>
            <th>日</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th>六</th>
        </tr>
        <tr>
            <?php for($i=0;$i<$weekdayFirst;$i++):?>
            <td></td>
            <?php endfor;?>
            <?php for($d=1;$d<=$dayNum;$d++):?>
                <?php
                    if($i>6){
                        $i=0;
                        echo '<tr>';
                    }
                $thisDay = $y.'-'.$m.'-'.($d>9?$d:'0'.$d);
                $isHoliday = CommonFunc::isHoliday($thisDay,$i);

                    if($isHoliday){//节假日
                        echo '<td class="holiday">';
                    }elseif(in_array($d,$signList)){//已签到
                        echo '<td class="signed">';
                    }elseif($today==$thisDay){//今天
                        echo '<td class="today">';
                    }else{
                        echo '<td class="day">';
                    }
                    echo $d.'</td>';
                    if($i==6){
                        echo '</tr>';
                    }
                    $i++;
                ?>
            <?php endfor;?>
            <?php for($j=$i;$j<7;$j++):?>
                <td></td>
                <?php
                    if($j==6){
                        echo '</tr>';
                    }
                ?>

            <?php endfor;?>
    </table>
    <div style="text-align: right;">* 白底红字的日期为节假日，无需签到</div>
</section>