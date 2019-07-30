<?php
namespace backend\controllers;


use backend\models\Depart;
use backend\models\OrgCompany;
use backend\models\OrgCompanyContract;
use backend\models\OrgCompanyOrder;
use common\models\ApiReturn;
use yii\base\Exception;
use yii\base\Module;
use Yii;
use dosamigos\qrcode\QrCode;
use EasyWeChat\Factory;
use yii\helpers\Url;

/**
 * 支付控制器
 */
class PaymentController extends AuthController
{
    public $enableCsrfValidation = false;
    protected $wechat;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->wechat = Factory::payment(Yii::$app->params['wechatPaymentConfig']);
    }

    /**
     * 获取二维码
     * @return \common\models\json
     */
    public function actionQrcode()
    {
        $params = Yii::$app->request->get();
        return Qrcode::png($params['url'],false,2,5);
    }

    /**
     * 统一下单
     * @return \common\models\json
     */
    public function actionUnified()
    {
        $post = Yii::$app->request->post();
        $app = Factory::payment(Yii::$app->params['wechatPaymentConfig']);

        if(isset($post['upgrade']) && $post['upgrade'] == 1){
            $store_arr = OrgCompanyContract::find()->select('depart_store_id,contract_start,contract_end')->where(['company_id'=>$this->_user['company_id'],'is_del'=>0])->asArray()->all();
            if($post['amount'] != count($store_arr)*(3800-800)){
                return ApiReturn::wrongParams('升级套餐金额错误');
            }
            foreach ($store_arr as $key => $item){
                $post['store_id'][] = $item['depart_store_id'];
            }
        }else{
            if($post['contract_version'] == '门店版'){
                if($post['amount'] != 800){
                    return ApiReturn::wrongParams('门店版金额错误');
                }
            }else if($post['contract_version'] == '专业版'){
                if($post['amount'] != 3800){
                    return ApiReturn::wrongParams('专业版金额错误');
                }
            }else{
                return ApiReturn::wrongParams('版本信息错误');
            }
        }
        if($this->_user['company_id'] == 32 || $this->_user['company_id'] == 33 || $this->_user['company_id'] == 34){
            $post['amount']=0.01;
        }
        //生成订单号
        $order_sn = Yii::$app->redis->get('org_company_order');
        if (empty($order_sn)) {
            $order_sn = 1;
            Yii::$app->redis->set('org_company_order', $order_sn);
        } else {
            $order_sn = $order_sn + 1;
            Yii::$app->redis->set('org_company_order', $order_sn);
        }
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if(isset($post['store_id']) && $post['store_id']){
                if(is_array($post['store_id'])){
                    $store_id = json_encode($post['store_id']);
                }else{
                    $store_id = $post['store_id'];
                }
            }else{
                $departmodel = new Depart();
                $departmodel->d_name = $post['d_name'];
                $departmodel->d_type = $post['d_type'];
                $departmodel->d_level = $post['d_level'];
                $departmodel->d_pid = $post['d_pid'];
                $departmodel->d_pid_name = $post['d_pid_name'];
                $departmodel->d_phone = $post['d_phone'];
                $departmodel->d_district = $post['d_district'];
                $departmodel->d_address = $post['d_address'];
                $departmodel->d_principal = $post['d_principal'];
                $departmodel->d_principal_id = $post['d_principal_id'];
                $departmodel->company_id = $this->_user['company_id'];
                $departmodel->cid = $this->_user['u_id'];
                $departmodel->uid = $this->_user['u_id'];
                $departmodel->ctime = date('Y-m-d H:i:s');
                $departmodel->utime = date('Y-m-d H:i:s');
                $departmodel->is_del = 1;
                if ($departmodel->save() === false) {
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('下单失败!');
                }
                $store_id = Yii::$app->db->getLastInsertID();
            }
            $orderModel = new OrgCompanyOrder();
            $orderModel->order_sn = $order_sn  = 'GLFC-' . date('ymd') . '-' . str_pad($order_sn, 4, "0", STR_PAD_LEFT);
            $orderModel->order_title = $post['order_title'];
            $orderModel->company_id = $this->_user['company_id'];
            $orderModel->u_id = $this->_user['u_id'];
            $orderModel->contract_version = $post['contract_version'];
            $orderModel->store_id = $store_id;
            $orderModel->contract_term = 1;
            $orderModel->order_detail = json_encode($post);
            $orderModel->amount = $post['amount'];
            $orderModel->status = 0;
            $orderModel->ctime = date('Y-m-d H:i:s');
            $orderModel->is_del = 0;
            if($orderModel->save() === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('下单失败!!');
            }
            $transaction->commit();
            $result = $app->order->unify([
                'trade_type' => 'NATIVE', // JSAPI，NATIVE，APP...
                'attach' => '宜居客房产系统',
                'body' => $post['order_title'],
                'detail' => $post['order_title'],
                'out_trade_no' => $order_sn,
                'total_fee' => intval($post['amount'] * 100),
                'notify_url' =>  Url::to('payment/notify'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            ]);
            //var_dump($result);die;
            if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                if ($result['code_url']) {
                    $data = ['qrurl' => $result['code_url'], 'order_sn' => $order_sn];
                    return ApiReturn::success('下单成功',$data);
                } else {
                    return  ApiReturn::wrongParams('下单失败');
                }
            } else {
                $response = [
                    'order_sn' => $order_sn,
                    'return_code' => $result['return_code'],
                    'return_msg' => $result['return_msg'],
                    'result_code' => $result['result_code'],
                    'err_code' => $result['err_code'],
                    'msg' => $result['err_code_des'],
                ];
                return  ApiReturn::success('下单失败',$response);
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 微信支付回调通知
     * @return \common\models\json
     */
    public function actionNotify()
    {
        $app = Factory::payment(Yii::$app->params['wechatPaymentConfig']);
        $response = $app->handlePaidNotify(function($message, $fail)use ($app){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = OrgCompanyOrder::find()->where(['order_sn'=>$message['out_trade_no']])->one();

            if (!$order) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();//开启事物
                    try {
                        if($order->status == 3){
                            return true;
                        }
                        $order->transaction_id = $message['transaction_id'];
                        $order->paid_time = date('Y-m-d H:i:s'); // 更新支付时间为当前时间
                        $order->real_amount = $message['total_fee'] / 100 ;
                        $order->status = 3;
                        $order->utime = date('Y-m-d H:i:s');
                        if($order->save() === false){
                            $transaction->rollBack();
                            return false;
                        }
                        $store_id = json_decode($order->store_id,true);
                        $contractModel = OrgCompanyContract::find()->where(['company_id'=>$order->company_id])->andWhere(['in','depart_store_id',$store_id])->all();
                        if($contractModel){
                            foreach ($contractModel as $key => $contract){
                                $contract->contract_version = $order->contract_version;
                                $contract->contract_money = $order->contract_version=='专业版'? 3800 : 800 ;
                                $contract->contract_start = date('Y-m-d H:i:s');
                                $contract->contract_end = date('Y-m-d H:i:s',strtotime('+1year',strtotime($contract->contract_end)));
                                if($contract->save() === false){
                                    $transaction->rollBack();
                                    return false;
                                }
                            }
                        }else{
                            $contractModel = new OrgCompanyContract();
                            $contractModel->company_id = $order->company_id;
                            $contractModel->depart_store_id = $order->store_id;
                            $contractModel->contract_version = $order->contract_version;
                            $contractModel->contract_money = $message['total_fee'] / 100;
                            $contractModel->contract_start = date('Y-m-d H:i:s');
                            $contractModel->contract_end = date('Y-m-d H:i:s',strtotime('+1year'));
                            $contractModel->contract_city = '苏州市';
                            $contractModel->contract_personal = 20;
                            $contractModel->contract_phone = 200;
                            $contractModel->contract_import = 150;
                            $contractModel->cid = $order->u_id;
                            $contractModel->uid = $order->u_id;
                            $contractModel->ctime = date('Y-m-d H:i:s');
                            $contractModel->utime = date('Y-m-d H:i:s');
                            $contractModel->is_del = 0;
                            if($contractModel->save() === false){
                                $transaction->rollBack();
                                return false;
                            }
                        }
                        $stores = Depart::find()->where(['in','d_id',$store_id])->all();
                        foreach ($stores as $key => $store){
                            $store->is_del=0;
                            if($store->save() === false){
                                $transaction->rollBack();
                                return false;
                            }
                        }
                        $transaction->commit();
                        return true;
                    }catch (Exception $e){
                        $transaction->rollBack();
                        return false;
                    }
                } elseif (array_get($message, 'result_code') === 'FAIL') { // 用户支付失败
                    $order->status = 4;
                    $order->utime = date('Y-m-d H:i:s');
                    $order->save();
                    return true;
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            return true; // 返回处理完成
        });
        $response->send();
    }

    /**
     * 轮询判断支付状态
     * @return \common\models\json
     */
    public function actionQueryorder()
    {
        $params = Yii::$app->request->post();
        $app = Factory::payment(Yii::$app->params['wechatPaymentConfig']);
        $order = OrgCompanyOrder::find()->where(['order_sn'=>$params['order_sn']])->one();
        if(!$order){
            return ApiReturn::wrongWithData('支付数据错误,请重新下订单1',-1);
        }

            if ($params['count'] >= 0 && $params['count'] < 60) {
                $count = $params['count'] + 1;
                //调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付
                $orderresult = $app->order->queryByOutTradeNumber($params['order_sn']);
                if ($orderresult['return_code'] == 'SUCCESS' && $orderresult['result_code'] == 'SUCCESS') {
                    if ($orderresult['trade_state'] == 'SUCCESS') {
                        $connection = \Yii::$app->db;
                        $transaction = $connection->beginTransaction();//开启事物
                        try {
                            $order_sn = $orderresult['out_trade_no'];
                            $trade_no = $orderresult['transaction_id'];
                            $amount = $orderresult['total_fee'];

                            // 检查订单是否已经更新过支付状态
                            if ($order->status==3) {
                                return ApiReturn::success('支付成功', 0);
                            }

                            $order->transaction_id = $trade_no;
                            $order->paid_time = date('Y-m-d H:i:s'); // 更新支付时间为当前时间
                            $order->real_amount = $amount / 100 ;
                            $order->status = 3;
                            $order->utime = date('Y-m-d H:i:s');
                            if($order->save() === false){
                                $transaction->rollBack();
                                return ApiReturn::success('支付数据异常1',60);
                            }
                            $store_id = json_decode($order->store_id,true);
                            $contractModel = OrgCompanyContract::find()->where(['company_id'=>$order->company_id])->andWhere(['in','depart_store_id',$store_id])->all();
                            if($contractModel){
                                foreach ($contractModel as $key => $contract){
                                    $contract->contract_version = $order->contract_version;
                                    $contract->contract_money = $order->contract_version=='专业版'? 3800 : 800 ;
                                    $contract->contract_start = date('Y-m-d H:i:s');
                                    $contract->contract_end = date('Y-m-d H:i:s',strtotime('+1year',strtotime($contract->contract_end)));
                                    if($contract->save() === false){
                                        $transaction->rollBack();
                                        return ApiReturn::success('支付数据异常2',60);
                                    }
                                }
                            }else{
                                $contractModel = new OrgCompanyContract();
                                $contractModel->company_id = $order->company_id;
                                $contractModel->depart_store_id = $order->store_id;
                                $contractModel->contract_version = $order->contract_version;
                                $contractModel->contract_money = $amount / 100;
                                $contractModel->contract_start = date('Y-m-d H:i:s');
                                $contractModel->contract_end = date('Y-m-d H:i:s',strtotime('+1year'));
                                $contractModel->contract_city = '苏州市';
                                $contractModel->contract_personal = 20;
                                $contractModel->contract_phone = 200;
                                $contractModel->contract_import = 150;
                                $contractModel->cid = $order->u_id;
                                $contractModel->uid = $order->u_id;
                                $contractModel->ctime = date('Y-m-d H:i:s');
                                $contractModel->utime = date('Y-m-d H:i:s');
                                $contractModel->is_del = 0;
                                if($contractModel->save() === false){
                                    $transaction->rollBack();
                                    return ApiReturn::success('支付数据异常3',60);
                                }
                            }
                            $stores = Depart::find()->where(['in','d_id',$store_id])->all();
                            foreach ($stores as $key => $store){
                                $store->is_del=0;
                                if($store->save() === false){
                                    $transaction->rollBack();
                                    return ApiReturn::success('支付数据异常',60);
                                }
                            }

                            $transaction->commit();
                            return ApiReturn::success('支付成功', 0);
                        }catch (Exception $e){
                            $transaction->rollBack();
                            return ApiReturn::success('支付数据异常4',60);
                        }
                    } elseif ($orderresult['trade_state'] == 'CLOSED') {
                        $order->status = 1;
                        $order->utime = date('Y-m-d H:i:s');
                        $order->save();
                        return ApiReturn::wrongWithData('支付已关闭',60);
                    } elseif ($orderresult['trade_state'] == 'REFUND') {
                        $order->status = 5;
                        $order->utime = date('Y-m-d H:i:s');
                        $order->save();
                        return ApiReturn::wrongWithData('支付已转退款',60);
                    } else {
                        return ApiReturn::success('支付中',$count);
                    }
                } elseif ($orderresult['return_code'] == 'SUCCESS' && $orderresult['result_code'] == 'FAIL') {
                    return ApiReturn::noData('查询失败',$count);
                }
            } else if($params['count'] == 60) {
                $order->status = 2;
                $order->utime = date('Y-m-d H:i:s');
                $order->save();
                return ApiReturn::wrongWithData('订单支付超时，请重新下订单',60);
            }else{
                $order->status = 2;
                $order->utime = date('Y-m-d H:i:s');
                $order->save();
                return ApiReturn::success('弹窗关闭，请重新下订单',-1);
            }
    }

    /**
     * 添加开票信息
     * @return \common\models\json
     */
    public function actionAddinvoice()
    {
        $post = Yii::$app->request->post();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $companyModel = OrgCompany::findOne($this->_user['company_id']);
            $companyModel->invoice_name =  $post['name'];
            $companyModel->invoice_number =  $post['number'];
            $companyModel->invoice_address =  $post['address'];
            $companyModel->invoice_phone =  $post['phone'];
            $result = $companyModel->save();
            if($result === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('添加失败');
            }
            $order = OrgCompanyOrder::find()->where(['order_sn'=>$post['order_sn']])->one();
            $order->is_invoice = 1;
            $orderresult = $order->save();
            if($orderresult === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('添加失败');
            }
            $transaction->commit();
            return ApiReturn::success('添加成功');
        }catch (Exception $e){
            $transaction->rollBack();
            return ApiReturn::wrongParams('添加失败');
        }
    }


}
