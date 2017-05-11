<?php
namespace yun\components;
use yii;
use yii\base\Component;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\Operation;

class QiniuUpload extends Component {
    public $accessKey;
    public $secretKey;
    //空间名字
    public $bucket;
    //空间中文件的名字（可能有路径）
    public $key;
    //自定义上传路径
    public $url;
    //域名
    public $domain;

    public function __construct($bucket,$files='',$url='',$domain='',$key='') {
        require_once __DIR__.'/qiniuVendor/autoload.php';
        //$this->accessKey = 'U3Is3mssiNMUt6d3np4p55s7ltQ1ESh-NZBii7tI';//应该放入配置然后调用
        //$this->secretKey = '45SX6CQvMIH6P_WzOAHUwgiy1S4TDDk04E66bzGt';//应该放入配置然后调用
        $this->accessKey = yii::$app->params['qiniu-accessKey'];//应该放入配置然后调用
        $this->secretKey = Yii::$app->params['qiniu-secretKey'];//应该放入配置然后调用
        $this->url=$url;
        $this->key=$key;
        $this->domain=$domain;
        $this->bucket= $bucket;
    }
    //生成token
    //saveKey 上传策略 资源文件名格式
    public function createtoken($saveKey=''){
        $auth = new Auth($this->accessKey,$this->secretKey);
        if($saveKey){
            $token = $auth->uploadToken($this->bucket,'',3600,array('saveKey'=>$saveKey));
        }else{
            $token = $auth->uploadToken($this->bucket);
        }
        return $token;
    }


/*
    public function uploads(){
        $auth = new Auth($this->accessKey,$this->secretKey);
        $token=$this->createtoken();
        $filename=$_FILES['file']['name'];
        $array=explode('.',$filename);
        $key=date('YmdHis').'.'.$array[1];
        //是否本地存在这个文件夹，不存在自己新建
        if (!is_dir($this->url.'/')){
            mkdir($this->url.'/', 0777);
        }
        $realpath=$this->url.'/'.$key;
        move_uploaded_file($_FILES['file']['tmp_name'],$realpath);
        $uploadMgr = new UploadManager();
        $localurl=__DIR__.'/../'.$this->url.'/'.$key;
        $key=$this->key.$key;
        list($ret, $err) = $uploadMgr->putFile($token,$key,$localurl);
        if (file_exists ( $realpath )){		// 判断 文件是否存在
            unlink ( $realpath ); // 进行文件删除
        }
        $baseUrl = $this->domain.$key;
        $authUrl = $auth->privateDownloadUrl($baseUrl);
        return  $authUrl;
    }

    public function uploadsAvatar($image,$key){
        //$auth = new Auth($this->accessKey,$this->secretKey);
        $token=$this->createtoken();

        $uploadMgr = new UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token,$key,$image);
        if(!$err){
            return $ret['key'];
        }else{
            return false;
        }

    }*/
}