<?php
    use yii\bootstrap\Modal;
    app\assets\AppAsset::addJsFile($this,'js/main/dir/modal/move.js');
?>
<?php
Modal::begin([
    'header' => '文件移动',
    'id'=>'moveModal',
    /*'size'=>'modal-lg',*/
    'options'=>['style'=>'margin-top:120px;']
]);
?>
    <div id="moveModalContent">
        <p>
            <label>所在路径（旧）：</label>
            <span class="parent_old"><?=substr($dirRoute,0,-1)?></span>
        </p>
        <p>
            <label style="width:120px;display:block;float:left;">所在路径（新）：</label>
            <div class="move_dir_route" style="display:block;float:left;">

            </div>
        </p>
        <div class="clearfix"></div>
        <p class="hidden">
            dir<input type="hidden" class="move_dir_id_new" name="move_dir_id_new" >
            <br/>
            p_id<input  type="hidden"class="move_p_id_new" name="move_p_id_new" >
            <br/>
            <span class="move-error"></span>
        </p>
        <p>
            <button class="btn btn-success">提交</button>
        </p>
        <!--<input type="hidden" class="p_id" />
        <input type="hidden" class="dir_id" />-->
    </div>
<?php
Modal::end();
?>