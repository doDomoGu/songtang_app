<?php
namespace api\controllers;

use Yii;

class OaController extends BaseController
{
    public function actionApplyCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $n = new Apply();
            $n->title = $post['title'];
            $n->task_id  = $post['task_id'];
            $n->message  = $post['message'];
            $n->user_id = Yii::$app->user->id;
            $n->flow_step = 1;
            $n->add_time = date('Y-m-d H:i:s');
            $n->edit_time = date('Y-m-d H:i:s');
            $n->status = 1;
            if($n->save()){
                Yii::$app->getSession()->setFlash('success','发起申请成功！');
                $result = true;
            }else{
                $errormsg = '发起申请失败!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }



    public function helpApplyCreate(){
        echo 'helpApplyCreate';
       // Yii::$app->end();
    }

}
