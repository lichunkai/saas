<?php
namespace backend\controllers;

use backend\models\OaKaoqingTpl;
use common\models\ApiReturn;
use Yii;

/**
 * 考勤模板控制器
 */
class OakaoqingtplController extends AuthController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 考勤模板列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;
        $row = OaKaoqingTpl::find();
        /*if(isset($param['kw']) && $param['kw']){
            $row->andWhere(['like','base_name',trim($param['kw'])]);
        }*/
        $data['list']=$row->orderBy('utime DESC')->where(['company_id' => $this->_user['company_id']])->limit($pagesize)->offset($start)->asArray()->all();
        $data['totalnum'] = $row->count();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 考勤模板添加
     */
    public function actionAdd(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new OaKaoqingTpl();
            $result = $model->UpdateTpl($post,$this->_user());
            $message = '添加';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 考勤模板编辑
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new OaKaoqingTpl();
            $result = $model->UpdateTpl($post,$this->_user());
            $message = '更新';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 删除考勤模板
     */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model = OaKaoqingTpl::findOne($post['kq_tp_id']);
            if($model->delete()){
                return ApiReturn::success('删除成功');
            }else{
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }
}
