<?php
    yun\assets\AppAsset::addCssFile($this,'css/main/site/index.css');
    yun\assets\AppAsset::addJsFile($this,'js/main/site/index.js');
?>

<div class="clearfix"></div>
<div id="site-index">
<!--    <aside>-->
<!--        <section id="slogan">-->
<!--            <img src="/images/manyi.png" style="width:210px;padding:20px 10px;"/>-->
<!--        </section>-->
<!--        <section id="email-login">-->
<!--            <article>-->
<!--                <form method="post" target="_blank" action="https://exmail.qq.com/cgi-bin/login" name="form1">-->
<!--                    <input type="hidden" value="false" name="firstlogin">-->
<!--                    <input type="hidden" value="dm_loginpage" name="errtemplate">-->
<!--                    <input type="hidden" value="other" name="aliastype">-->
<!--                    <input type="hidden" value="bizmail" name="dmtype">-->
<!--                    <input type="hidden" value="" name="p">-->
<!--                    <div class="email-login-header">-->
<!--                        企业邮箱登录-->
<!--                    </div>-->
<!--                    <br/>-->
<!--                    <div class="bizmail_column">-->
<!--                        <!--<label>帐号:</label>- ->-->
<!--                        <div class="bizmail_inputArea">-->
<!--                            <input type="text" value="" class="text" name="uin" /><span class="email-suffix">@songtang.net</span>-->
<!--                            <input type="hidden" value="songtang.net" name="domain">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <br/>-->
<!--                    <div class="bizmail_column">-->
<!--                        <!--<label>密码:</label>- ->-->
<!--                        <div class="bizmail_inputArea">-->
<!--                            <input type="password" value="" class="text1" name="pwd">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="bizmail_SubmitArea">-->
<!--                        <!--<input type="submit" value="" style="display: none;" name="" class="buttom">- ->-->
<!--                        <input class="submit-btn" type="submit" value="邮箱登录"/>-->
<!--                    </div>-->
<!---->
<!--                </form>-->
<!--            </article>-->
<!--        </section>-->

    </aside>
    <main>
        <section id="dir-list2">
            <?php for($i=1;$i<=count($list_dirOne);$i++):?>
                <article class="<?=$i==count($list_dirOne)?'last':''?>">
                    <div class="item-heading">
                        <?=yii\bootstrap\Html::a($list_dirOne[$i]->name,['/dir','dir_id'=>$list_dirOne[$i]->id])?>
                    </div>
                    <div class="item-list">
                        <ul class="list-unstyled">
                        <?php $j=0;foreach(${'list_'.$i} as $l):?>
                            <li>
                                <?=yii\bootstrap\Html::a($l->name,['/dir','dir_id'=>$l->id])?>
                            </li>
                        <?php $j++;endforeach;?>
                        <?php for($k=$j;$k<5;$k++):?>
                            <li>

                            </li>
                        <?php endfor;?>
                        </ul>
                    </div>
                </article>
            <?php endfor;?>
        </section>
        <div class="clearfix"></div>
        <div id="code">
            <image src="/images/site-index/code.jpg" width="660" />
        </div>
<!--        <section id="article-link">-->
<!--            <article id="article-link-1">-->
<!--                <span><a href="http://www.songtang.net/tangxun" target="_blank"></a></span>-->
<!--            </article>-->
<!--            <article id="article-link-2">-->
<!--                <span><a href="http://www.songtang.net/tangkan" target="_blank"></a></span>-->
<!--            </article>-->
<!--            <article id="article-link-3">-->
<!--                <span><a href="http://www.songtang.net/tangjian" target="_blank"></a></span>-->
<!--            </article>-->
<!--        </section>-->
        </main>
</div>
<div class="clearfix"></div>