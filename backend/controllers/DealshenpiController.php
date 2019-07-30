<?php


namespace backend\controllers;


use backend\models\Depart;
use backend\models\House;
use backend\models\OrderSell;
use common\models\ApiReturn;
use Yii;

class DealshenpiController extends AuthController
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
     * @inheritdoc
     * 成交审核列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $company_id = $this->_user['company_id'];

        $row = OrderSell::find()->where(['company_id'=>$company_id,'is_del' => 0,'order_type'=>2])->with(['created' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }])->with(['updated' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }]);
        $datecolumn = 'order_deal_date';
        if (isset($param['order_timechoose']) && $param['order_timechoose']) {
            if($param['order_timechoose'] = '成交时间'){
                $datecolumn = 'order_deal_date';
            }
//            else if($param['order_timechoose'] = '合同结案时间'){
//                $datecolumn = 'order_contract_date';
//            }else if($param['order_timechoose'] = '佣金结案时间'){
//                $datecolumn = 'order_commission_date';
//            }
        }

        if (isset($param['daterange']) && $param['daterange']) {
            $row ->andWhere(['>=',$datecolumn,$param['daterange'][0]])->andWhere(['<=',$datecolumn,$param['daterange'][1].' 23:59:59']);
        }
        if (isset($param['order_deal_type']) && $param['order_deal_type']) {
            $row ->andWhere(['order_deal_type'=>$param['order_deal_type']]);
        }
//        if (isset($param['house_area']) && !empty($param['house_area'][0])) {
//            $row ->andWhere(['>=','house_area',$param['house_area'][0]])->andWhere(['<=','house_area',$param['house_area'][1]]);
//        }
//        if (isset($param['order_price']) && empty($param['order_price'][0])) {
//            $row ->andWhere(['>=','order_price',0])->andWhere(['<=','order_price',$param['order_price'][1]]);
//        }else if(isset($param['order_price']) && empty($param['order_price'][1])){
//            $row ->andWhere(['>=','order_price',$param['order_price'][0]]);
//        }else if(isset($param['order_price'])){
//            $row ->andWhere(['>=','order_price',$param['order_price'][0]])->andWhere(['<=','order_price',$param['order_price'][1]]);
//        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['or', ['like', 'order_sn', $kw], ['like', 'owner_sn', $kw], ['like', 'customer_sn', $kw]]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['listdata'] = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
//        var_dump($listdata['listdata']);die;
        //echo $row->createCommand()->getRawSql();die;
        $authwhere = Yii::$app->LoadData->checkDataByUser($this->id.'/'.$this->action->id,$this->_user);

        foreach ($listdata['listdata'] as $key => $val){
            $depart = Depart::find()->where(['d_id'=>$val['order_deal_did']])->select('d_name')->asArray()->one();
            $listdata['listdata'][$key]['c_user'] = $val['created']['u_name'];
            $listdata['listdata'][$key]['u_user'] = $val['updated']['u_name'];
            $listdata['listdata'][$key]['is_jump'] = 0;
            $listdata['listdata'][$key]['order_deal_department'] =  $depart['d_name'];
//            $listdata['listdata'][$key]['contract_image_num'] = count(json_decode($val['contract_image'],true)) ? count(json_decode($val['contract_image'],true)):0;
            if($authwhere['key'] == 'all'){
                $listdata['listdata'][$key]['is_jump'] = 1;
            }else{
                if($authwhere['value'] == $val[$authwhere['key']]){
                    $listdata['listdata'][$key]['is_jump'] = 1;
                }
            }
        }
//        var_dump($listdata);die;

        return ApiReturn::success('获取成功', $listdata);
    }

    /**
     * @inheritdoc
     * 更改成交状态通过
     */
    public function actionSurestatus(){
        $param = Yii::$app->request->post();
        if(empty($param['id'])){
            return ApiReturn::wrongParams('参数错误');
        }
        $orderinfo = OrderSell::find()->where(['order_id' => $param['id']])->asArray()->one();
        if(empty($orderinfo)){
            return ApiReturn::wrongParams('参数错误');
        }
        if(!isset($param['status']) && $param['status']!=1){
            return ApiReturn::wrongParams('参数错误');
        }
        if($param['status'] == 1){
            $order_status = '进行中';
        }
//        var_dump(OrderSell::updateAll(['order_status' => $order_status], ['order_id' => $param['id']]));die;
        if(OrderSell::updateAll(['order_status' => $order_status], ['order_id' => $param['id']]) ){
           if(House::updateAll(['house_status' => 2], ['house_sn' => $orderinfo['owner_sn']])){
               return ApiReturn::success('操作成功');
           }
        }else{
            return ApiReturn::wrongParams('操作失败');
        }
    }

    /**
     * @inheritdoc
     * 更改成交状态驳回
     */
    public function actionRejectstatus(){
        $param = Yii::$app->request->post();
        if(empty($param['id'])){
            return ApiReturn::wrongParams('参数错误');
        }
        $orderinfo = OrderSell::find()->where(['order_id' => $param['id']])->asArray()->one();
        if(empty($orderinfo)){
            return ApiReturn::wrongParams('参数错误');
        }
        if(!isset($param['status']) && $param['status']!=2){
            return ApiReturn::wrongParams('参数错误');
        }
        if($param['status'] == 2){
            $order_status = '驳回';
        }
        if(OrderSell::updateAll(['order_status' => $order_status,'order_reject_reason'=>trim($param['reason'])], ['order_id' => $param['id']])){
            return ApiReturn::success('操作成功');
        }else{
            return ApiReturn::wrongParams('操作失败');
        }
    }


}