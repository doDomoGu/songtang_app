<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;
    $this->title = '组织结构';
    ucenter\assets\AppAsset::addJsFile($this,'js/main/structure/index.js');
?>
<section>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th width="200">地区 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
            <th>行业 <?/*=$aid>0?Html::dropDownList('business-select',$bid,$bArr2,['prompt'=>'----','id'=>'business-select']):''*/?></th>
            <th>公司 <?/*=$aid>0?Html::dropDownList('business-select',$bid,$bArr2,['prompt'=>'----','id'=>'business-select']):''*/?></th>
            <th>部门</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php header("Content-Type: text/html; charset=UTF-8");?>
        <?php foreach($list as $l):?>
            <?php
            //echo 'distr: '.$l->district->name;
                $industryArr = [];
                if($industry_id>0){ //只取特定的行业
                    foreach($l->industryList as $itemIndustry){
                        if($lb->industry_id==$industry_id)
                            $industryArr = [$itemIndustry];
                    }

                }else{
                    $industryArr = $l->industryList;
                }
//            echo ':'.count($industryArr);
//            echo '<br/>';
                $districtCount = 1;
                $industryCount = [];
                $companyCount = [];
                $i = 0;
                foreach ($industryArr as $indOne){
                    //echo '&nbsp;&nbsp; ind : '.$indOne->industry->name;
                    $companyArr = $indOne->companyList;
                    $tmp = count($companyArr);
//                    echo ':'.count($companyArr);
//                    echo '<br/>';

                    $industryCount[$i] = 1;
                    $companyCount[$i] = [];
                    $districtCount++;
                    foreach ($companyArr as $comOne){
                        //echo '&nbsp;&nbsp;&nbsp;&nbsp comp: '.$comOne->company->name;
                        $departmentArr = $comOne->departmentList;
                        $tmp2 = count($departmentArr);
                        $districtCount++;
                        $districtCount+=$tmp2;
                        $industryCount[$i] += $tmp2+1;
                        $companyCount[$i][] = $tmp2+1;
//echo ':'.count($departmentArr);
//                        echo '<br/>';
//                        foreach ($departmentArr as $depOne){
//
//                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; department: '.$depOne->name;
//                            echo '<br/>';
//                        }
                        /*$countTmp = count($departmentArr);
                        $companyCount[$i][] = $countTmp+1;
                        $industryCount[$i] += $countTmp+1;
                        $districtCount += $countTmp+1;*/
                    }


                    /*$countTmp = count($companyArr);
                    $industryCount[] = $dCount+1;
                    $districtCount +=$dCount+1;



                    $count = 1;
                    $industryCount = [];*/

                    $i++;
                }
                //$bCount = count($br);
                //$count+=$bCount;
            ?>
<!--            --><?php //var_dump($districtCount,$industryCount,$companyCount);echo '<br/>';?>
<!--    --><?php //endforeach;exit;?>
<!--    --><?php //foreach($list as $l):?>
            <tr>
                <td rowspan="<?=$districtCount?>" ><?=$districtArr[$l->district_id]?></td>
                <td class="td-empty"></td>
                <td class="td-empty"></td>
                <td class="td-empty"></td>
                <td><?=CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?/*=Html::a('添加业态','script:void(0)',['data-pid'=>$l->aid,'data-pname'=>$aArr[$l->aid],'data-type'=>'area','data-toggle'=>"modal",'data-target'=>"#addModal",'class'=>'btn btn-xs btn-success'])*/?>
                </td>
            </tr>
            <?php $i=0;foreach($l->industryList as $indOne):?>
            <tr>
                <td rowspan="<?=$industryCount[$i]?>">
                    <?=$indOne->industry->name?>
                </td>
                <td class="td-empty"></td>
                <td class="td-empty"></td>
                <td><?=CommonFunc::getStatusCn($indOne->industry->status)?></td>
                <td>
                   <!-- --><?/*=Html::a('添加部门','script:void(0)',['data-aid'=>$l->aid,'data-bid'=>$b->bid,'data-p_id'=>0,'data-toggle'=>"modal",'data-target'=>"#addModal",'class'=>'btn btn-xs btn-warning'])*/?>
                </td>
            </tr>
                <?php $comList = $indOne->companyList; if(!empty($comList)):?>
                <?php $j=0;foreach($comList as $comOne):?>
                <tr>
                    <td rowspan="<?=$companyCount[$i][$j]?>">
                    <?=$comOne->company->name?>
                    </td>
                    <td class="td-empty"></td>
                    <td><?=CommonFunc::getStatusCn($comOne->company->status)?></td>
                    <td>
                        <?/*=Html::a('添加子部门','script:void(0)',['data-aid'=>$l->aid,'data-bid'=>$b->bid,'data-p_id'=>$c->did,'data-toggle'=>"modal",'data-target'=>"#addModal",'class'=>'btn btn-xs btn-primary'])*/?>
                    </td>
                </tr>
                    <?php $depList = $comOne->departmentList; if(!empty($depList)):?>
                    <?php foreach($depList as $depOne):?>
                        <tr>
                            <td>
                                <?=$depOne->name?>
                            </td>
                            <td><?=CommonFunc::getStatusCn($depOne->status)?></td>
                            <td>
                                <?/*=Html::a('添加子部门','script:void(0)',['data-aid'=>$l->aid,'data-bid'=>$b->bid,'data-p_id'=>$c->did,'data-toggle'=>"modal",'data-target'=>"#addModal",'class'=>'btn btn-xs btn-primary'])*/?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    <?php endif;?>
                <?php $j++;endforeach;?>
                <?php endif;?>
            <?php $i++;endforeach;?>
        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '<span class="modal-title"></span>',
    'id'=>'addModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="addContent">
        <form class="form-horizontal" role="form">
            <input class="aid-value" type="hidden" />
            <input class="bid-value" type="hidden" />
            <input class="p_id-value" type="hidden" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">地区</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="aname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">业态</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="bname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">部门路径</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="pname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">部门选择</label>
                <div class="col-sm-6">
                    <select class="form-control new-did-select">
                    </select>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="add-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>