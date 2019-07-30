<?php

namespace backend\controllers;

use backend\models\ZhSettingBase;
use backend\models\ZhSettingFinance;
use backend\models\ZhSettingTransfer;
use common\models\ApiReturn;
use Yii;

/**
 * 财务平台-过户流程控制器
 */
class SettingtransferController extends AuthController
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
     * 过户流程列表
     */
    public function actionGetlist()
    {
        $data['process'] = ZhSettingBase::getBaseSettings('transfer_process',$this->_user['company_id']);
        $data['list'] = ZhSettingTransfer::find()->where(['company_id'=>$this->_user['company_id']])->orderBy('transfer_id DESC')->asArray()->all();
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 获取详情
     */
    public function actionDetail()
    {
        $post = Yii::$app->request->post();
        $data = ZhSettingTransfer::find()->where(['company_id'=>$this->_user['company_id'],'transfer_id'=>$post['transfer_id']])->asArray()->one();

        $data['transfer_owner_materials'] = json_decode($data['transfer_owner_materials'],true);
        $data['transfer_customer_materials'] = json_decode($data['transfer_customer_materials'],true);
        $data['transfer_process'] = json_decode($data['transfer_process'],true);

        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 分成方案配置添加
     */
    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            $res = ZhSettingTransfer::find()->where(['company_id'=>$this->_user['company_id'],'transfer_name' => trim($post['transfer_name'])])->one();
            if($res){
                return ApiReturn::wrongParams('流程名称重复，请重新添加');
            }

            $model = new ZhSettingTransfer();
            $model->transfer_name = isset($post['transfer_name']) ? $post['transfer_name'] : '';
            $model->company_id = $this->_user['company_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if ($result) {
                return ApiReturn::success('添加成功');
            } else {
                return ApiReturn::wrongParams('添加失败');
            }
        }
    }

    /**
     * 分成配置编辑
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            if(empty($post['transfer_id'])){
                return ApiReturn::wrongParams('参数错误');
            }

            $model = ZhSettingTransfer::findOne($post['transfer_id']);
            if(isset($post['ownerdata']) && $post['ownerdata']){
                foreach ($post['ownerdata'] as $key => $val){
                    if(!empty($val)){
                        $ownerdata[] = ['materials_name'=>$val];
                    }
                }
                $model->transfer_owner_materials = isset($ownerdata) ? json_encode($ownerdata) : '';
            }
            if(isset($post['customerdata']) && $post['customerdata']){
                foreach ($post['customerdata'] as $key => $val){
                    if(!empty($val)) {
                        $customerdata[] = ['materials_name' => $val];
                    }
                }
                $model->transfer_customer_materials = isset($customerdata) ? json_encode($customerdata) : '';
            }
            if(isset($post['processdata']) && $post['processdata']){
                foreach ($post['processdata']['name'] as $key => $val){
                    if(!empty($val)){
                        $processdata[] = ['name'=>$val];
                    }
                }
                $model->transfer_process = isset($processdata) ? json_encode($processdata) : '';
            }
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if ($result) {
                return ApiReturn::success('更新成功');
            } else {
                return ApiReturn::success('更新失败');
            }
        }
    }

    /**
     * 分成配置删除
     */
    public function actionDel()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            $model = ZhSettingTransfer::findOne($post['transfer_id']);
            $result = $model->delete();
            if ($result !== false) {
                return ApiReturn::success('删除成功');
            } else {
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }

    /**
     * 普通配置子配置项更新
     */
    public function actionEditchild()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            $model = new ZhSettingBase();
            $result = $model->UpdateChildSetting($post, $this->_user());
            $message = '添加';
            if (isset($param['base_id']) && $param['base_id']) {
                $message = '更新';
            }
            if ($result) {
                return ApiReturn::success($message . '成功');
            } else {
                return ApiReturn::wrongParams($message . '失败');
            }
        }
    }

}
