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
            $records = ApplyRecord::find()->where(['apply_id'=>$aid])->all();
            if(!empty($records)){
                foreach($records as $r){

                    $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                    $username = $flow_user?$flow_user->name:'N/A';

                    $htmlOne = '<li>';
                    $htmlOne.= '<div>步骤'.$r->flow->step.'</div>';
                    $htmlOne.= '<div>标题：<b>'.$r->flow->title.'</b>  操作类型：<b>'.$r->flow->typeName.'</b></div>';
                    $htmlOne.= '<div>操作人：<b>'.$username.'</b> 时间: <b>'.$r->add_time.'</b> 结果：<b>'.Flow::getResultCn($r->flow->type,$r->result).'</b></div>';
                    $htmlOne.= '<div>备注信息：<b>'.$r->message.'</b></div>';
                    $htmlOne.= '</li>';
                    $html .= $htmlOne;
                }
            }

            //3.剩余未完成操作  * 只有申请表(apply)状态为执行中(status=1)
            if($apply->status==1) {
                $curStep = $apply->flow_step;
                $flow = Flow::find()->where(['task_id' => $apply->task_id])->andWhere(['>=', 'step', $curStep])->all();
                foreach ($flow as $f) {
                    $username = Apply::getOperationUser($apply, $f);

                    $htmlOne = '<li class="not-do">';
                    $htmlOne .= '<div>步骤' . $f->step . ' 还未操作</div>';
                    $htmlOne .= '<div>标题：<b>' . $f->title . '</b>  操作类型：<b>' . $f->typeName . '</b></div>';
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

}
