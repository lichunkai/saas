<?php

namespace backend\controllers;

use backend\models\ZhSettingBase;
use backend\models\ZhSettingFinance;
use common\models\ApiReturn;
use Yii;

/**
 * 财务平台-分成设置控制器
 */
class SettingfinanceController extends AuthController
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
     * 分成配置列表
     */
    public function actionGetlist()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $row = ZhSettingFinance::find()->where(['company_id'=>$this->_user['company_id']]);
        $data['list'] = $row->orderBy('finance_id DESC')->limit($pagesize)->offset($start)->asArray()->all();
        foreach($data['list'] as $k => $v ){
            if($v['finance_shorthand'] == 'secondhouse'){
                $list = $v;
            }
        }

        $data['totalnum'] = $row->count();
        $datalist['list'] = $list;
        return ApiReturn::success('获取成功', $datalist);
    }

    /**
     * 获取分成配置详情
     */
    public function actionGetchildlist()
    {
        $post = Yii::$app->request->post();
        $row = ZhSettingFinance::find()->where(['company_id'=>$this->_user['company_id'],'finance_shorthand'=>'secondhouse'])->asArray()->all();
//        if (!Yii::$app->request->isPost || empty($post['finance_id'])) {
//            return ApiReturn::wrongParams('参数有误');
//        }
        if(empty($row[0]['finance_id'])){
            return ApiReturn::wrongParams('参数有误');
        }
        $setting = ZhSettingFinance::find()->where(['company_id'=>$this->_user['company_id'],'finance_id' => $row[0]['finance_id']])->asArray()->one();
        if ($setting && !empty($setting)) {
            return ApiReturn::success('获取成功', json_decode($setting['finance_desp'], true));
        } else {
            return ApiReturn::wrongParams('没有找到相关子配置');
        }
    }

    /**
     * 分成方案配置添加
     */
    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            $res = ZhSettingFinance::find()->where(['company_id'=>$this->_user['company_id'],'finance_name' => trim($post['finance_name'])])
                ->orWhere(['finance_shorthand' => trim($post['finance_shorthand'])])->one();
            if($res){
                return ApiReturn::wrongParams('配置名称或英文简称重复，请重新添加');
            }
            $post['company_id'] = $this->_user['company_id'];
            $model = new ZhSettingFinance();
            $result = $model->addFinance($post);
            $message = '添加';
            if (isset($param['finance_id']) && $param['finance_id']) {
                $message = '更新';
            }
            if ($result) {
                return ApiReturn::success($message . '成功');
            } else {
                return ApiReturn::wrongParams($message . '失败');
            }
        }
    }

    /**
     * 分成配置编辑
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();
//        var_dump($post);die;
        if (yii::$app->request->isPost) {
            if(empty($post['finance_id'])){
                return ApiReturn::wrongParams('参数错误');
            }
            $totalper = $post['fangyuanlururen'] + $post['tupianlururen'] + $post['yaoshiren'] + $post['dujiaweituoren'] + $post['hetongchengjiaoren'] ;
            if($totalper > 100){
                return ApiReturn::wrongParams('分成比例超出总和');
            }
            $finance_data = [
                ['finance_name'=>'房源录入人','finance_short'=>'fangyuanlururen','finance_per'=>$post['fangyuanlururen']],
                ['finance_name'=>'房源维护人','finance_short'=>'fangyuanweihuren','finance_per'=>$post['fangyuanweihuren']],
                ['finance_name'=>'图片录入人','finance_short'=>'tupianlururen','finance_per'=>$post['tupianlururen']],
                ['finance_name'=>'拿钥匙人','finance_short'=>'yaoshiren','finance_per'=>$post['yaoshiren']],
                ['finance_name'=>'一般委托人','finance_short'=>'yibanweituoren','finance_per'=>$post['yibanweituoren']],
                ['finance_name'=>'独家委托人','finance_short'=>'dujiaweituoren','finance_per'=>$post['dujiaweituoren']],
                ['finance_name'=>'合同成交人','finance_short'=>'hetongchengjiaoren','finance_per'=>$post['hetongchengjiaoren']],
            ];
            $data = ['finance_id'=>$post['finance_id'],'finance_desp'=>json_encode($finance_data)];
            $model = new ZhSettingFinance();
            $result = $model->updateFinance($data);
            $message = '添加';
            if (isset($param['finance_id']) && $param['finance_id']) {
                $message = '更新';
            }
            if ($result) {
                return ApiReturn::success($message . '成功');
            } else {
                return ApiReturn::wrongParams($message . '失败');
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
            $model = new ZhSettingFinance();
            $result = $model->DelSetting($post);
            if ($result) {
                return ApiReturn::success('删除成功');
            } else {
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }
}
