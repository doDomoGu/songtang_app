<?php
namespace common\components;
include_once '../../common/extensions/aliyun-sdk-sms/aliyun-php-sdk-core/Config.php';

use common\models\GlobalConfig;
use Sms\Request\V20160927 as SendSmsRequest;
use DefaultProfile;
use DefaultAcsClient;
use ClientException;
use ServerException;
use yii\base\Component;
use Yii;
use common\models\Sms;
//use app\components\MyLog;

class SendSms extends Component {
    public $client;
    public $sign;
    public $product;

    public $templateCode;
    public $mobile;
    public $param;

    public $sms_id;

    public function __construct(array $config=[])
    {
        parent::__construct($config);

        $config = Yii::$app->params['aliyun_sms_config'];
        $this->sign = isset($config['sign'])?$config['sign']:'';
        $this->product = isset($config['product'])?$config['product']:'';
        $iClientProfile = DefaultProfile::getProfile($config['regionId'],$config['accessKey'], $config['accessSecret']);
        $this->client = new DefaultAcsClient($iClientProfile);
    }

    public function sendByDatabase($sms_id){
        $sms = Sms::find()->where(['id'=>$sms_id,'flag'=>Sms::FLAG_NOT_SEND])->one();
        if($sms){
            $this->sms_id = $sms->id;

            $this->templateCode = $sms->template_code;
            $this->mobile = $sms->mobile;
            //$this->param = "{\"code\":\"$sms->code\",\"product\":\"$this->product\"}";
            $this->param = $sms->param;

            $sms->flag = Sms::FLAG_SENDING;
            $sms->save();

            $return = $this->send();
            if($return['result']){
                $sms->response = $return['response'];
                $sms->flag = Sms::FLAG_SEND_SUCCESS;
            }else{
                $sms->error = $return['error'];
                $sms->flag = Sms::FLAG_SEND_FAIL;
            }
            $sms->send_time = date('Y-m-d H:i:s');
            $sms->save();

        }else{
            MyLog::error('sms id not found',MyLog::CATE_SMS);
        }
    }

    public function send(){
        $return = [];
        $flag = GlobalConfig::getConfig('send_sms_flag');
        if($flag!==false && $flag==1){
            $request = new SendSmsRequest\SingleSendSmsRequest();

            $request->setSignName($this->sign);/*签名名称*/
            $request->setTemplateCode($this->templateCode);/*模板code*/
            $request->setRecNum($this->mobile);/*目标手机号*/
            $request->setParamString($this->param);/*模板变量，数字一定要转换为字符串*/
            try {
                $response = $this->client->getAcsResponse($request);
                $return['result'] = true;
                $return['response'] = json_encode($response,JSON_UNESCAPED_UNICODE);
                //print_r($response);
            }
            catch (ClientException  $e) {
                $error = [
                    'code'=>$e->getErrorCode(),
                    'message'=>$e->getErrorMessage()
                ];
                $return['result'] = false;
                $return['error'] = json_encode($error,JSON_UNESCAPED_UNICODE);

                MyLog::error('send fail: client error, mobile:'.$this->mobile.'templateCode:'.$this->templateCode.' sign:'.$this->sign,MyLog::CATE_SMS);
            }
            catch (ServerException  $e) {
                $error = [
                    'code'=>$e->getErrorCode(),
                    'message'=>$e->getErrorMessage()
                ];
                $return['result'] = false;
                $return['error'] = json_encode($error,JSON_UNESCAPED_UNICODE);

                MyLog::error('send fail: server error, mobile:'.$this->mobile.'templateCode:'.$this->templateCode.' sign:'.$this->sign,MyLog::CATE_SMS);
            }
        }else{

           /* stdClass Object ( [Model] => 104407581146^1105976032668 [RequestId] => A89D5D6D-7241-4A5D-AE01-EF51B8430FBB )*/


            /*InvalidTemplateCode.Malformed
The specified templateCode is wrongly formed.*/
            $return['result'] = true;
            $return['response'] = 'not real send';
        }

        return $return;
    }
}
