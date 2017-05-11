<?php
namespace yun\components;

use yun\models\Attribute;
use yun\models\DownloadRecord;
use yun\models\File;
use yii\base\Component;
use yii\helpers\BaseArrayHelper;
use Yii;
use yun\models\FileAttribute;

class FileFrontFunc extends Component {

    public static function file_type($reverse=false){
        $arr = array(
            'documents','txt','jpg','jpeg','png','gif','bmp','tif','tiff',
            'doc','ppt','xls','docx','pptx','xlsx','psd','ai','html','htm',
            'mp3','avi','mp4','rmvb','wma','pdf',
        );
        if($reverse){
            $arr = array_flip($arr);
        }
        return $arr;
    }

    public static function getFileType($filename){
        $fileTypes = self::file_type(true);
        $ext = substr(strrchr($filename,'.'),1);
        if($ext!==false && isset($fileTypes[$ext])){
            return $fileTypes[$ext];
        }else{
            return 99;
        }
    }

    public static function getFileExt($fileType){
        $fileTypes = self::file_type();
        $fileType = intval($fileType);
        if(isset($fileTypes[$fileType])){
            return $fileTypes[$fileType];
        }else{
            return 'unknown';
        }
    }

    public static function getFilePath($path,$beaut=false){
        if($beaut)
            return yii::$app->params['qiniu-domain-beaut'].$path;
        else
            return yii::$app->params['qiniu-domain'].$path;
    }

    public static function getFiles($dir_id,$p_id,$pages,$order='add_time desc',$search=false,$attrSearch=[]){
        $query = self::createQuery($dir_id,$p_id,$search,$attrSearch);
        $files = $query/*->innerJoinWith('user')*/
            ->orderBy($order)
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $files;
    }

    public static function getFilesNum($dir_id,$p_id,$search=false,$attrSearch=[]){
        $query = self::createQuery($dir_id,$p_id,$search,$attrSearch);
        return $query->/*innerJoinWith('user')->*/count();
    }

    private static function createQuery($dir_id,$p_id,$search=false,$attrSearch=[]){
        $query = File::find();
        $dir_id = intval($dir_id);
        $p_id = intval($p_id);
        if($p_id>0)
            $query = $query->where(['file.p_id'=>$p_id,'file.status'=>1]);
        else
            $query = $query->where(['file.dir_id'=>$dir_id,'file.p_id'=>0,'file.status'=>1]);

        /*        $files = $files->where(['dir_id'=>$dir_id,'status'=>1]);
                $files = $files->andWhere(['p_id'=>$p_id,'status'=>1]);*/
        if(!empty($attrSearch)){
            $fidArr = [];
            if(isset($attrSearch['district']) && !empty($attrSearch['district'])){
                $faList = FileAttribute::find()->where(['attr_type'=>Attribute::TYPE_DISTRICT,'attr_id'=>$attrSearch['district']])->groupBy('file_id')->all();
                foreach($faList as $l){
                    $fidArr[] = $l->file_id;
                }
            }
            $query = $query->andWhere(['id'=>$fidArr]);

            $fidArr2 = [];
            if(isset($attrSearch['industry']) && !empty($attrSearch['industry'])){
                $faList = FileAttribute::find()->where(['attr_type'=>Attribute::TYPE_INDUSTRY,'attr_id'=>$attrSearch['industry']])->groupBy('file_id')->all();
                foreach($faList as $l){
                    $fidArr2[] = $l->file_id;
                }
            }
            $query = $query->andWhere(['id'=>$fidArr2]);
        }

        if($search!==false)
            $query = $query->andWhere(['like','file.filename',$search]);

        return $query;
    }




    public static function insertDownloadRecord($file,$user_id){
        $downloadRecord = new DownloadRecord();
        $downloadRecord->file_id = $file->id;
        $downloadRecord->user_id = $user_id;
        $downloadRecord->save();

        $file->clicks+=1;
        $file->save();
    }

    public static function sizeFormat($size)
    {
        if($size<1024)
        {
            return $size."B";
        }
        else if($size<(1024*1000))
        {
            $size=round($size/1024,0);
            return $size."KB";
        }
        else if($size<(1024*1024*1000))
        {
            $size=number_format($size/(1024*1024),1);
            return $size."M";
        }
        else
        {
            $size=round($size/(1024*1024*1000),1);
            return $size."G";
        }
    }


    /*
     * 函数getParents ,实现根据 当前p_id 递归获取全部父层级 id
     *
     * @param integer p_id
     * return array
     */
    public static function getParents($p_id){
        $arr = [];
        $curDir = File::find()->where(['id'=>$p_id,'status'=>1])->one();
        if($curDir){
            $arr[] = $curDir;
            $arr2 = self::getParents($curDir->p_id);
            $arr = BaseArrayHelper::merge($arr2,$arr);
        }

        ksort($arr);
        return $arr;
    }

    /*
     * 函数getParentStatus ,实现根据 当前p_id 递归获取全部父层级 一旦有父层为删除状态（status = 0） 则返回false ,直到p_id = 0 为止
     *
     * @param integer p_id
     * return array
     */
    public static function getParentStatus($p_id){
        if($p_id==0){
            return true;
        }else{
            $curDir = File::find()->where(['id'=>$p_id])->one();
            if($curDir && $curDir->status==1){
                return self::getParentStatus($curDir->p_id);
            }else{
                return false;
            }
        }
    }

    /*
     * 函数getParentDeleteStatus ,实现根据 当前p_id 递归获取全部父层级 一旦有父层为删除状态（status = 2） 则返回false ,直到p_id = 0 为止
     *
     * @param integer p_id
     * return array
     */
    public static function getParentDeleteStatus($p_id){
        if($p_id==0){
            return true;
        }else{
            $curDir = File::find()->where(['id'=>$p_id])->one();
            if($curDir && $curDir->status<2){
                return self::getParentDeleteStatus($curDir->p_id);
            }else{
                return false;
            }
        }
    }

    /*
     * 函数updateParentStatus ,当在回收站做了还原操作，如果是一个文件夹而且下面有文件 会更新parent_status
     *             但是如果其下面有文件夹，文件夹的status是0 这个文件夹下面的不会更新，仍然是在回收站中的状态
     *
     * @param integer id
     * return array
     */
    public static function updateParentStatus($id){
        $children = File::find()->where(['p_id'=>$id])->all();
        foreach($children as $c){
            if($c->filetype==0){
                if($c->status==1){
                    $c->parent_status = 1;
                    $c->save();
                    self::updateParentStatus($c->id);
                }
            }else{
                $c->parent_status = 1;
                $c->save();
            }
        }
    }

    /*
     * 函数updateParentStatus2 ,当在目录页面做了删除操作（移入回收站），如果是一个文件夹而且下面有文件 会更新parent_status
     *
     * @param integer id
     * return array
     */
    public static function updateParentStatus2($id){
        $children = File::find()->where(['p_id'=>$id])->all();
        foreach($children as $c){
            if($c->filetype==0){
                if($c->status==1){
                    $c->parent_status = 0;
                    $c->save();
                    self::updateParentStatus2($c->id);
                }
            }else{
                $c->parent_status = 0;
                $c->save();
            }
        }
    }

    /*
         * 函数updateDelete,在回收站中做删除操作（即彻底删除操作） 如果是一个文件夹更新其下文件的状态
         *
         * @param integer id
         * return array
         */
    public static function updateDeleteStatus($id){
        $children = File::find()->where(['p_id'=>$id])->all();
        foreach($children as $c){
            if($c->filetype==0){
                $c->status = 2;
                $c->save();
                self::updateDeleteStatus($c->id);
            }else{
                $c->status = 2;
                $c->save();
            }
        }
    }

    public static function getDownloadList($dir_ids,$limit=10){
        $files = File::find()
            ->where(['in','dir_id',$dir_ids])
            ->andWhere(['>','filetype',0])
            ->andWhere(['status'=>1])
            ->orderBy('clicks desc')
            ->limit($limit)
            ->all();
        return $files;
    }

    public static function getRecentList($dir_ids,$limit=10){
        $files = File::find()
            ->where(['in','dir_id',$dir_ids])
            ->andWhere(['>','filetype',0])
            ->andWhere(['status'=>1])
            ->orderBy('add_time desc')
            ->limit($limit)
            ->all();
        return $files;
    }

    public static function getFileNum($p_id,$status=false){
        $num = 0;
        $children = File::find()->where(['p_id'=>$p_id]);
        if($status!==false && in_array($status,[0,1]))
            $children->andWhere(['status'=>$status]);
        $children = $children->all();
        foreach($children as $c){
            if($c->filetype==0){
                $num += self::getFileNum($c->id,$status);
            }else{
                $num++;
            }
        }
        return $num;
    }
}