<?php
namespace yun\components;

use yun\models\Dir;
use app\models\Position;
use yii\base\Component;
use yii\helpers\BaseArrayHelper;
use Yii;

class DirFrontFunc extends Component {
    public static function getNavbar(){
        $arr = [];
        $isDirCtl = strpos(Yii::$app->controller->route,'dir')===0?true:false;
        $dirLvl_1 = null;
        if($isDirCtl){
            $dir_id = yii::$app->controller->dir_id;

            if($dir_id){
                $parents = DirFunc::getParents($dir_id);
                $dirLvl_1 = isset($parents[1])?$parents[1]:null;
            }
        }

        $dirs = Dir::find()->where(['p_id'=>0,'status'=>1])->orderBy('ord asc,id desc')->all();
        if(!empty($dirs)){
            foreach($dirs as $dir){
                $active = $dirLvl_1!=null && $dirLvl_1->id==$dir->id?true:false;

                $arr[] = [
                    'label'=>$dir->name.'<span class="active-red"></span>',
                    'url'=>['/dir','dir_id'=>$dir->id],
                    'active' => $active,
                    'encode'=>false
                ];
            }
        }


        return $arr;
    }

    public static function createTreeJson($arr,$dir_id,$p_ids){
        $data = null;
        $i=1;
        if(!empty($arr)){
            $data .= '[';
            foreach($arr as $l){
                $data.='{';
                $data.="name:'".$l->name."',url:'/dir?dir_id=".$l->id."',target:'_self'";
                if($l->id == $dir_id){
                    $data.=",font:{'background-color':'black', 'color':'white'}";
                }else if(in_array($l->id,$p_ids)){
                    $data.=',open:true';
                }
                if(!empty($l->childrenList)){
                    $data.=',children: '.self::createTreeJson($l->childrenList,$dir_id,$p_ids);
                }


                $data.='}';
                if($i<count($arr)){
                    $data.=',';
                }
                $i++;
            }
            $data .= ']';
        }
        return $data;
    }

    public static function getTreeData($dir_id){
        $treeData = null;
        $cache = Yii::$app->cache;
        $cacheExist = true;
        $treeDataId = [];
        if(isset($cache['treeDataId'])){
            $treeDataId = $cache['treeDataId'];
            if(isset($treeDataId[$dir_id])){
                $treeData = $cache['treeData_'.$dir_id];
            }else{
                $cacheExist = false;
            }
        }else{
            $cacheExist = false;
        }
        if($cacheExist == false){
            $cache['treeDataId'] = \yii\helpers\ArrayHelper::merge($treeDataId,[$dir_id=>1]);

            $parents = DirFunc::getParents($dir_id);
            if(isset($parents[1])){
                $p_ids = [];
                foreach($parents as $p){
                    $p_ids[] = $p->id;
                }


                //$dir_id_one = $parents[1];
                $arr = DirFunc::getChildrenArr($parents[1]->id,true,false,false);

                $treeData .=self::createTreeJson($arr,$dir_id,$p_ids);


                //$data = '[{ name:"父节点1 - 展开", open:true,isParent:true}]';
            }

            //$treeData = DirFrontFunc::getTreeData($dir_id);


            $cache['treeData_'.$dir_id]=$treeData;
        }
        return $treeData;



        //return $data;

       /* $data2 = '[
			{ name:"父节点1 - 展开", open:true,
                children: [
                { name:"父节点11 - 折叠",url:"manage",target:"_self",
                children: [
                { name:"叶子节点111"},
							{ name:"user",url:"user"},
							{ name:"叶子节点113"},
							{ name:"叶子节点114"}
]},
					{ name:"父节点12 - 折叠",
                        children: [
                        { name:"叶子节点121"},
							{ name:"叶子节点122"},
							{ name:"叶子节点123"},
							{ name:"叶子节点124"}
]},
					{ name:"父节点13 - 没有子节点"}
]},
			{ name:"父节点2 - 折叠",
                children: [
                { name:"父节点21 - 展开", open:true,
                children: [
                { name:"叶子节点211"},
							{ name:"叶子节点212"},
							{ name:"叶子节点213"},
							{ name:"叶子节点214"}
]},
					{ name:"父节点22 - 折叠",
                        children: [
                        { name:"叶子节点221"},
	/*						{ name:"叶子节点222"},
							{ name:"叶子节点223"},
							{ name:"叶子节点224"}
]},
					{ name:"父节点23 - 折叠",
                        children: [
                        { name:"叶子节点231"},
							{ name:"叶子节点232"},
							{ name:"叶子节点233"},
							{ name:"叶子节点234"}
]}
]},
			{ name:"父节点3 - 没有子节点", isParent:true}

]';
        return $data;*/
    }
}