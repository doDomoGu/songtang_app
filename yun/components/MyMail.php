<?php
namespace yun\components;

use yii\base\Component;
use yii;

class MyMail extends Component {
    public $from;
    public $to;
    public $subject;
    public $htmlBody;

    public function __construct(){
        $this->from = yii::$app->params['mailFrom'];
    }

    public function send(){
        $sendResult = false;
        $mail = Yii::$app->mailer->compose();
        $mail->setFrom($this->from);
        $mail->setTo($this->to);
        $mail->setSubject($this->subject);
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $mail->setHtmlBody($this->htmlBody);    //发布可以带html标签的文本
        if($this->from!='' && $this->to!=''){
            $sendResult = $mail->send();
            if($sendResult==false){
                //log错误日志 #TODO

            }
        }
        return $sendResult;
    }
}