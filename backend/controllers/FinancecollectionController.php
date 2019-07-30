<?php

namespace backend\controllers;

use backend\models\Customer_xiading;
use backend\models\OrderSellCollection;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingFinance;
use common\models\ApiReturn;
use Yii;
use yii\base\Exception;

/**
 * 财务平台-佣金管理
 */
class FinancecollectionController extends AuthController
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
     * 获取选择参数
     * @return array|\common\models\json
     */
    public function actionGetsetting()
    {
        $order_status = ZhSettingBase::find()->where(['company_id'=>$this->_user['company_id'],'base_shorthand' => 'collectionPayway'])->select('base_desp')->asArray()->one();
        $result['way'] = json_decode($order_status['base_desp'],true);
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /**
     * 佣金管理列表
     */
    public function actionGetlist()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;

        $row = OrderSellCollection::find()->alias('a')->select('a.*,e.house_sn,b.order_sn,b.dts_name,b.village_name,b.house_building,b.house_door,b.agreement_sn,b.order_deal_username,b.order_deal_date,b.order_owner_commission,b.order_customer_commission,c.u_name as cname,d.u_name as uname')
            ->leftJoin('zh_order_sell b','a.order_id=b.order_id')
            ->leftJoin('zh_house e','a.house_id=e.house_id')
            ->innerJoin('zh_user c','a.c_id=c.u_id')
            ->innerJoin('zh_user d','a.u_id=d.u_id')
            ->where(['a.company_id'=>$this->_user['company_id'],'a.is_del'=>0]);
        if(isset($param['type']) && $param['type']){
            $row->andWhere(['a.collection_type'=>$param['type']]);
        }
        if(isset($param['status']) && $param['status'] != ''){
            $row->andWhere(['a.collection_status'=>$param['status']]);
        }
        if(isset($param['datetype']) && $param['datetype']){
            if($param['datetype'] == 1){
                if (isset($param['daterange']) && $param['daterange']) {
                    $row ->andWhere(['>=','a.collection_day',$param['daterange'][0]])->andWhere(['<=','a.collection_day',$param['daterange'][1]]);
                }
            }elseif ($param['datetype'] == 2){
                if (isset($param['daterange']) && $param['daterange']) {
                    $row ->andWhere(['>=','a.utime',$param['daterange'][0]])->andWhere(['<=','a.utime',$param['daterange'][1]]);
                }
            }elseif ($param['datetype'] == 3){
                if (isset($param['daterange']) && $param['daterange']) {
                    $row ->andWhere(['>=','b.order_deal_date',$param['daterange'][0]])->andWhere(['<=','b.order_deal_date',$param['daterange'][1]]);
                }
            }
        }
        if(isset($param['way']) && $param['way']){
            $row->andWhere(['a.collection_way'=>$param['way']]);
        }
        if(isset($param['payer']) && $param['payer']){
            $row->andWhere(['a.collection_payer'=>$param['payer']]);
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['like', 'b.order_sn', $kw]);
        }
        //echo $row->createCommand()->getRawSql();die;
        $data['list'] = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $data['yingshou'] = 0;
        $data['shishou'] = 0;
        $yingshou = [];
        foreach ($data['list'] as $key =>$item){
            $yingshou[$item['order_id']] = (float)$item['order_owner_commission']+(float)$item['order_customer_commission'];
            $data['shishou'] += $item['collection_money'];
            $data['list'][$key]['collection_image'] = json_decode($item['collection_image'],true);
        }
        $data['yingshou'] = array_sum($yingshou);
        $data['totalnum'] = $row->count();
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 更新佣金状态
     */
    public function actionUpdatestatus()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            if(empty($post['id'])){
                return ApiReturn::wrongParams('参数失败');
            }
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();//开启事物
            try {
                $model =  OrderSellCollection::findOne($post['id']);
                $model->collection_reason = isset($post['reason'])? $post['reason'] : '';
                $model->collection_status = isset($post['status'])? $post['status'] : '';
                $model->u_id = $this->_user['u_id'];
                $model->utime = date('Y-m-d H:i:s');
                $result = $model->save();
                if ($result === false) {
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('操作失败');
                }
                $xiadingmodel = Customer_xiading::findOne($model->xiading_id);
                if(!$xiadingmodel){
                    return ApiReturn::wrongParams('数据不匹配');
                }
                if(isset($post['status']) && $post['status']==1){
                    $xiadingmodel->x_zhuangtai = $model->xiading_status;
                    if($model->xiading_status == 1){
                        $xiadingmodel->x_xys = $model->xiading_contractno;
                        $xiadingmodel->x_zcbz = $model->collection_remark;
                    }
                }else if(isset($post['status']) && $post['status']==2){
                    $xiadingmodel->x_zhuangtai = 4;
                    if($model->xiading_status == 1){
                        $xiadingmodel->x_xys = $model->xiading_contractno;
                        $xiadingmodel->x_zcbz = $model->collection_remark;
                    }
                }
                $xiadingmodel->reject_reason = isset($post['reason'])? $post['reason'] : '';
                $xiadingresult = $xiadingmodel->save();
                if ($xiadingresult === false) {
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('操作失败');
                }
                $transaction->commit();
                return ApiReturn::success('保存成功');
            }catch (Exception $e){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败');
            }



        }
    }
}
