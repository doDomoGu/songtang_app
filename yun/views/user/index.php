<?php
    use yun\components\CommonFunc;
    use yun\components\PositionFunc;
    yun\assets\AppAsset::addCssFile($this,'css/main/user/index.css');
?>
<section>
    <!--<article id="head-img-aside" class="text-center">
        <div id="head-img-pic">
            <img src="<?/*=$user->head_img!=''?CommonFunc::imgUrl($user->head_img):'/images/default-user.png'*/?>"
             alt="职员头像" class="img-thumbnail" style="width:250px;height:250px;">
        </div>
        <div id="head-img-btn">
            <?/*=\yii\bootstrap\Html::a('修改头像','/user/change-head-img',['class'=>'btn btn-primary btn-sm'])*/?>
        </div>

    </article>-->
    <article id="user-info-aside">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">职员资料</h3>
            </div>
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="text-right">姓名</td>
                    <td><?=$user->name?></td>
                </tr>
                <tr>
                    <td class="text-right">邮箱</td>
                    <td>
                        <?=$user->username?>
                        &nbsp;&nbsp;
                       <!-- <a class="btn btn-xs btn-primary " href="<?/*=Yii::$app->urlManager->createUrl(['user/change-password']);*/?>">修改密码</a>-->
                    </td>
                </tr>
                <tr>
                    <td class="text-right">职位</td>
                    <td><?/*=PositionFunc::getFullRoute($user->position_id)*/?></td>
                </tr>
                <tr>
                    <td class="text-right">性别</td>
                    <td><?=CommonFunc::getGender($user->gender)?></td>
                </tr>
                <tr>
                    <td class="text-right">生日</td>
                    <td><?=$user->birthday?></td>
                </tr>
                <tr>
                    <td class="text-right">入职日期</td>
                    <td>
                        <?php if($user->join_date>0):?>
                            <?=date('Y-m-d',strtotime($user->join_date))?>
                            <?php $joinDay = CommonFunc::getJoinDay($user->join_date);?>
                            <br/>距今已经有 <?=$joinDay['day']?>天 / <?=$joinDay['yearMonth']?>
                        <?php else:?>
                            --
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">合同到期日期</td>
                    <td>
                        <?php if($user->join_date>0):?>
                            <?=date('Y-m-d',strtotime($user->contract_date))?>
                            <?php $contractDay = CommonFunc::getContractDay($user->contract_date);?>
                            <br/>
                            <?php if($contractDay['day']!=''):?>
                            距今还有 <?=$contractDay['day']?>天 / <?=$contractDay['yearMonth']?>
                            <?php else:?>
                            已到期
                            <?php endif;?>
                        <?php else:?>
                            --
                        <?php endif;?>
                    </td>
                </tr>
                <!--<tr>
                    <td class="text-right">个人岗位说明</td>
                    <td><?/*=$user->position->shuoming*/?></td>
                </tr>
                <tr>
                    <td class="text-right">个人岗位职权</td>
                    <td><?/*=$user->position->zhiquan*/?></td>
                </tr>
                <tr>
                    <td class="text-right">个人岗位职责</td>
                    <td><?/*=$user->position->zhize*/?></td>
                </tr>-->
            </table>
        </div>
    </article>
</section>