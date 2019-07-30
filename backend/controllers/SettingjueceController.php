<?php
namespace backend\controllers;

use backend\models\ZhSettingJuece;
use backend\models\ZhSettingQujian;
use common\models\ApiReturn;
use Yii;

/**
 * 房源补充设置控制器
 */
class SettingjueceController extends AuthController
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
     * 房源补充配置列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;
        $row = ZhSettingJuece::find();
        $row->where(['company_id'=>$this->_user['company_id']]);
        if(isset($param['type']) && $param['type']){
            $row->andWhere(['jsetting_type' => trim($param['type'])]);
        }
        if(isset($param['kw']) && $param['kw']){
            //$row->andWhere(['like','jsetting_name',trim($param['kw'])]);
            //$row->orWhere(['like','jsetting_shorthand',trim($param['kw'])]);
            $row->andWhere(['or', ['like','jsetting_name',trim($param['kw'])], ['like', 'jsetting_shorthand', trim($param['kw'])]]);
        }
        $data['list']=$row->orderBy('jsetting_id DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $data['totalnum'] = $row->count();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 房源补充配置添加
     */
    public function actionAdd(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingJuece();
            $result = $model->UpdateSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['jsetting_id']) && $param['jsetting_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 房源补充配置编辑
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingJuece();
            $result = $model->UpdateSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['jsetting_id']) && $param['jsetting_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

	/**
     * 房源补充变量配置
     */
	public function actionEditval(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $jueceModel = ZhSettingJuece::findOne($post['jsetting_id']);
            $jueceModel->val = $post['val'];
            if($jueceModel->save()){
                return ApiReturn::success('更新成功');
            }else{
                return ApiReturn::wrongParams('更新失败');
            }
        }
    }

    /**
     * 房源补充配置删除
     */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model = new ZhSettingJuece();
            $result = $model->DelSetting($post);
            return ApiReturn::success('删除成功');
        }
    }
}
