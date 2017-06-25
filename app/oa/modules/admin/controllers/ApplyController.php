<?php

namespace oa\modules\admin\controllers;

use common\components\CommonFunc;
use login\models\UserIdentity;
use oa\models\Apply;
use oa\models\ApplyRecord;
use oa\models\Flow;
use oa\modules\admin\components\AdminFunc;
use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Position;
use Yii;
use yii\data\Pagination;


class ApplyController extends BaseController
{
    public function actionIndex()
    {
        $query = Apply::find();

        $count = $query->count();
        $pageSize = 20;
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);
        $list = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        $params['list'] = $list;
        $params['pages'] = $pages;

        return $this->render('index',$params);
    }

    public function actionShowRecord(){
        $aid = Yii::$app->request->get('aid',false);
        $apply = Apply::find()->where(['id'=>$aid])->one();
        if($apply){
            //1.发起申请
            $html = '<li><div>发起申请</div><div>操作人：<b>'.$apply->applyUser->name.'</b> 时间：<b>'.$apply->add_time.' </b></div></li>';

            //2.操作记录
            $records = ApplyRecord::find()->where(['apply_id'=>$aid])->orderBy('step asc')->all();
            $recordsDone = [];
            $recordsTodo = [];
            foreach($records as $r){
                if($r->step==0){
                    continue;
                }else if($r->step < $apply->flow_step){
                    $recordsDone[] = $r;
                }else{
                    $recordsTodo[] = $r;
                }
            }


            //3.已完成
            if(!empty($recordsDone)){
                foreach($recordsDone as $r){

                    $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                    $username = $flow_user?$flow_user->name:'N/A';

                    $htmlOne = '<li>';
                    $htmlOne.= '<div>步骤'.$r->step.'</div>';
                    $htmlOne.= '<div>标题：<b>'.$r->flow->title.'</b>  操作类型：<b>'.Flow::typeName($r->type).'</b></div>';
                    $htmlOne.= '<div>操作人：<b>'.$username.'</b> 时间: <b>'.$r->add_time.'</b> 结果：<b>'.Flow::getResultCn($r->type,$r->result).'</b></div>';
                    $htmlOne.= '<div>备注信息：<b>'.$r->message.'</b></div>';
                    $htmlOne.= '</li>';
                    $html .= $htmlOne;
                }
            }

            //4.未完成操作  * 只有申请表(apply)状态为执行中(status=1)
            if(!empty($recordsTodo)){
                foreach($recordsTodo as $r){
                    $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                    $username = $flow_user?$flow_user->name:'N/A';

                    $htmlOne = '<li class="not-do">';
                    $htmlOne .= '<div>步骤' . $r->step . ' 还未操作</div>';
                    $htmlOne .= '<div>标题：<b>' . $r->title . '</b>  操作类型：<b>' . Flow::typeName($r->type) . '</b></div>';
                    $htmlOne .= '<div>操作人：<b>' . $username . '</b> </div>';
                    $htmlOne .= '</li>';
                    $html .= $htmlOne;
                }
            }
            $params['html'] = $html;
            $params['apply'] = $apply;
            return $this->render('show_record',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','申请操作记录对应的申请id不存在!');
            return $this->redirect(AdminFunc::adminUrl('apply'));
        }
    }


    public function actionFixRecord(){
        header("Content-type: text/html; charset=utf-8");
        $applys = Apply::find()->where(['status'=>Apply::STATUS_NORMAL])->andWhere(['>','user_id',10000])->andWhere(['>','task_id',20])->all();
        var_dump(count($applys));echo '<br/><br/>';
        foreach($applys as $apply){
            echo $apply->id. ' : '.$apply->title;
            echo '<br/>';
            echo $apply->task_id. ' : '.$apply->task->title;
            echo '<br/>';
            echo $apply->flow_step;



            $records = ApplyRecord::find()->where(['apply_id'=>$apply->id])->orderBy('step asc')->all();
            echo ' - '.count($records);

            $flows = Flow::find()->where(['task_id'=>$apply->task_id])->orderBy('step asc')->all();
            echo ' - '.count($flows);
            echo '<br/>';

            $flowUser = Apply::flowUserStr2Arr($apply->flow_user);
            for($i = $apply->flow_step;$i<=count($flows);$i++){
                $flow = $flows[$i - 1];

                $r = new ApplyRecord();
                $r->apply_id = $apply->id;
                $r->flow_id = 0;
                $r->step = $flow->step;
                $r->title = $flow->title;
                $r->type = $flow->type;
                if($flow->user_id>0){
                    $r->user_id = $flow->user_id;
                }else{
                    $r->user_id = $flowUser[$flow->step];
                }
                $r->result = 0;
                $r->message = '';
                $r->add_time = '0000-00-00 00:00:00';
                $r->attachment = json_encode([]);

                var_dump($r->attributes);
                echo '<br/><br/>';
                    $r->save();
            }
        }

        echo '<br/> <hr/><br/><br/>';
    }

}
