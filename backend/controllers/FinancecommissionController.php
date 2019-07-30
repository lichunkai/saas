<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\House;
use backend\models\OrderSell;
use backend\models\OrderSellCommission;
use backend\models\OrderSellCost;
use backend\models\OrderSellDivide;
use backend\models\User;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingFinance;
use common\models\ApiReturn;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * 财务平台-佣金管理
 */
class FinancecommissionController extends AuthController
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
     * 佣金管理列表
     */
    public function actionGetlist()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $type = isset($param['type']) ? $param['type']:1;

        $row = OrderSellCost::find()->alias('a')->select('a.*,b.order_sn,b.dts_name,b.village_name,b.house_building,b.house_door,b.agreement_sn,b.order_deal_username,b.order_deal_date,b.order_owner_commission,b.order_customer_commission,')
            ->innerJoin('zh_order_sell b','a.order_id=b.order_id')
            ->where(['a.company_id'=>$this->_user['company_id'],'a.is_del'=>0]);
//        if($type == 1){
//            $row->andWhere(['or',['a.cost_type'=>1],['a.cost_type'=>3]]);
//        }
//        if($type == 2){
//            $row->andWhere(['a.cost_type'=>2]);
//        }
        if(isset($param['shouqu']) && $param['shouqu'] != ''){
            $row->andWhere(['a.cost_status'=>$param['shouqu']]);
        }
        if(isset($param['jiaoyi']) && $param['jiaoyi']){
            $row->andWhere(['b.order_type'=>$param['jiaoyi']]);
        }
        if(isset($param['datetype']) && $param['datetype']){
            if($param['datetype'] == 1){
                if (isset($param['daterange']) && $param['daterange']) {
                    $row->andFilterWhere(['between','a.cost_day',$param['daterange'][0], $param['daterange'][1]]);
                    //$row ->andWhere(['>=','a.cost_day',$param['daterange'][0]])->andWhere(['<=','a.cost_day',$param['daterange'][1]]);
                }
            }elseif ($param['datetype'] == 2){
                if (isset($param['daterange']) && $param['daterange']) {
                    $row->andFilterWhere(['between','a.utime',$param['daterange'][0].' 00:00:01', $param['daterange'][1].' 23:59:59']);
                    //$row ->andWhere(['>=','a.utime',$param['daterange'][0]])->andWhere(['<=','a.utime',$param['daterange'][1]]);
                }
            }elseif ($param['datetype'] == 3){
                if (isset($param['daterange']) && $param['daterange']) {
                    $row->andFilterWhere(['between','b.order_deal_date',$param['daterange'][0], $param['daterange'][1]]);
                    //$row ->andWhere(['>=','b.order_deal_date',$param['daterange'][0]])->andWhere(['<=','b.order_deal_date',$param['daterange'][1]]);
                }
            }
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['like', 'b.order_sn', $kw]);
        }

        $data['list'] = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $data['yingshou'] = 0;
        $data['shishou'] = 0;
        $yingshou = [];
        foreach ($data['list'] as $key =>$item){
            $yingshou[$item['order_id']] = (float)$item['order_owner_commission']+(float)$item['order_customer_commission'];
            $data['shishou'] += $item['cost_money'];
            $data['list'][$key]['cost_image'] = json_decode($item['cost_image'],true);
        }
        $data['yingshou'] = array_sum($yingshou);
        $data['totalnum'] = $row->count();
//        echo $row->createCommand()->getRawSql();die;
//        var_dump($data);die;
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 佣金业绩明细
     */
    public function actionGetmingxi(){
        $param = Yii::$app->request->post();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        //$type = isset($param['type']) ? $param['type']:1;
        $row = OrderSellDivide::find()->alias('a')->select('a.*,s.order_type,s.order_sn,s.contract_sn,s.order_deal_date,
        s.house_name,s.order_owner_commission,s.order_customer_commission,
        s.house_building,s.house_unit,s.house_door,b.depart_name,b.user_name,b.reason,b.divide_per,c.cost_type,c.cost_day,
        c.cost_purpose,c.cost_money')
            ->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => 0])
            ->leftJoin('zh_order_sell_commission as b','a.commission_id=b.oc_id')
            ->leftJoin('zh_order_sell_cost as c','a.cost_id=c.cost_id')
            ->leftJoin('zh_order_sell as s', 's.order_id=a.order_id');

        if(isset($param['order_type']) && $param['order_type']){
            $row->andWhere(['s.order_type' => $param['order_type']]);
        }

        if(isset($param['orderstatus']) && $param['orderstatus']){
            $row->andWhere(['s.order_status' => $param['orderstatus']]);
        }

        if (isset($param['dateRange']) && $param['dateRange']){
            $daterange = $param['dateRange'];
            if($daterange[1] != 'undefined'){
                $row->andFilterWhere(['between','order_deal_date',$daterange[0], $daterange[1]]);
            }
        }
        if(isset($param['u_id']) && $param['u_id']){
//            $row->andWhere(['s.order_deal_user' => $param['u_id']]);
            $row->andWhere(['b.user_id' => $param['u_id']]);
        }else{
            if(isset($param['departpath']) && is_array($param['departpath'])){ //判断部门
                $users = $this->_getUsersByDepartId(end($param['departpath']));
                $row->andWhere(['in', 'b.user_id',$users]);
            }

        }

        if (isset($param["keywd"]) && $param["keywd"]) { //编号
            $row->andWhere("s.order_sn like '%" . $param["keywd"]."%'");
        }

        $data['list'] = $row->limit($pagesize)->offset($start)->asArray()->all();
//        echo $row->createCommand()->getRawSql();die;
        $data['count'] = $row->count();

//        var_dump($data['list'] );die;
        $order_status = ZhSettingBase::find()->where(['company_id'=>$this->_user['company_id'],'base_shorthand' => 'dealStatus'])->select('base_desp')->asArray()->one();
        $data['orderstatus'] = json_decode($order_status['base_desp'],true);

        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 确认佣金状态
     */
    public function actionSurestatus()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            if(empty($post['id'])){
                return ApiReturn::wrongParams('参数失败');
            }
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();//开启事物
            try {
                $model =  OrderSellCost::findOne($post['id']);
                $model->cost_status = isset($post['status'])? $post['status'] : '';
                $model->u_id = $this->_user['u_id'];
                $model->utime = date('Y-m-d H:i:s');
                $result = $model->save();
                if ($result == false) {
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('更新失败');
                }
                if($model->cost_type == 1 ){
                    $fencheng_per = OrderSellCommission::find()->where(['company_id'=>$this->_user['company_id'],'order_id'=>$model->order_id])->asArray()->all();
                    $fencheng_per = ArrayHelper::index($fencheng_per,'reason');
                    if(isset($fencheng_per['联动划成人']) && $fencheng_per['联动划成人'] && $fencheng_per['联动划成人']['divide_per']>0){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['联动划成人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        $dividemodel->divide_money = round($model->cost_money * $fencheng_per['联动划成人']['divide_per']/100,2);
                        $dividemodel->company_id = $this->_user['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败31');
                        }
                        $fencheng_money = $model->cost_money - $dividemodel->divide_money;
                    }else{
                        $fencheng_money = $model->cost_money;
                    }
                    $chengjiao_money = 0;
                    if(isset($fencheng_per['房源录入人']) && $fencheng_per['房源录入人'] && $fencheng_per['房源录入人']['user_id']){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['房源录入人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['房源录入人']['divide_per']/100,2);
                        $dividemodel->company_id = empty($fencheng_per['房源录入人']['company_id'])?$this->_user['company_id']:$fencheng_per['房源录入人']['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败31');
                        }
                    }else if(isset($fencheng_per['房源录入人']) && $fencheng_per['房源录入人'] && !$fencheng_per['房源录入人']['user_id']){
                        $chengjiao_money +=round($fencheng_money * $fencheng_per['房源录入人']['divide_per']/100,2);
                    }

                    if(isset($fencheng_per['房源维护人']) && $fencheng_per['房源维护人'] && $fencheng_per['房源维护人']['user_id']){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['房源维护人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        if(isset($fencheng_per['独家委托人']) && $fencheng_per['独家委托人'] && $fencheng_per['独家委托人']['user_id']){
                            $fangyuanweihuren_money= round($fencheng_money * $fencheng_per['房源维护人']['divide_per']/100,2);
                            $dividemodel->divide_money = 0;
                        }else{
                            $dividemodel->divide_money =round($fencheng_money * $fencheng_per['房源维护人']['divide_per']/100,2);
                        }
//                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['房源维护人']['divide_per']/100,2);
                        $dividemodel->company_id = empty($fencheng_per['房源维护人']['company_id'])?$this->_user['company_id']:$fencheng_per['房源维护人']['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败31');
                        }
                    }else if(isset($fencheng_per['房源维护人']) && $fencheng_per['房源维护人'] && !$fencheng_per['房源维护人']['user_id']){
                        if(isset($fencheng_per['独家委托人']) && $fencheng_per['独家委托人'] && $fencheng_per['独家委托人']['user_id']) {
                            $chengjiao_money += 0;
                        }else{
                            $chengjiao_money +=round($fencheng_money * $fencheng_per['房源维护人']['divide_per']/100,2);
                        }
                    }
                    if(isset($fencheng_per['图片录入人']) && $fencheng_per['图片录入人'] && $fencheng_per['图片录入人']['user_id']){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['图片录入人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['图片录入人']['divide_per']/100,2);
                        $dividemodel->company_id = empty($fencheng_per['图片录入人']['company_id'])?$this->_user['company_id']:$fencheng_per['图片录入人']['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败41');
                        }
                    }else if(isset($fencheng_per['图片录入人']) && $fencheng_per['图片录入人'] && !$fencheng_per['图片录入人']['user_id']){
                        $chengjiao_money +=round($fencheng_money * $fencheng_per['图片录入人']['divide_per']/100,2);
                    }
                    if(isset($fencheng_per['拿钥匙人']) && $fencheng_per['拿钥匙人'] && $fencheng_per['拿钥匙人']['user_id']){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['拿钥匙人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['拿钥匙人']['divide_per']/100,2);
//                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['拿钥匙人']['divide_per']/100,2);
                        $dividemodel->company_id = empty($fencheng_per['拿钥匙人']['company_id'])?$this->_user['company_id']:$fencheng_per['拿钥匙人']['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败51');
                        }
                    }else if(isset($fencheng_per['拿钥匙人']) && $fencheng_per['拿钥匙人'] && !$fencheng_per['拿钥匙人']['user_id']){
                        $chengjiao_money +=round($fencheng_money * $fencheng_per['拿钥匙人']['divide_per']/100,2);
                    }

                    if(isset($fencheng_per['一般委托人']) && $fencheng_per['一般委托人'] && $fencheng_per['一般委托人']['user_id']){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['一般委托人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        if(isset($fencheng_per['独家委托人']) && $fencheng_per['独家委托人'] && $fencheng_per['独家委托人']['user_id']){
                            $yibanweituoren =round($fencheng_money * $fencheng_per['一般委托人']['divide_per']/100,2);
                            $dividemodel->divide_money = 0;
                        }else{
                            $dividemodel->divide_money =round($fencheng_money * $fencheng_per['一般委托人']['divide_per']/100,2);
                        }
//                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['一般委托人']['divide_per']/100,2);
                        $dividemodel->company_id = empty($fencheng_per['一般委托人']['company_id'])?$this->_user['company_id']:$fencheng_per['一般委托人']['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败61');
                        }
                    }else if(isset($fencheng_per['一般委托人']) && $fencheng_per['一般委托人'] && !$fencheng_per['一般委托人']['user_id']){
                        if(isset($fencheng_per['独家委托人']) && $fencheng_per['独家委托人'] && $fencheng_per['独家委托人']['user_id']) {
                            $chengjiao_money += 0;
                        }else{
                            $chengjiao_money += round($fencheng_money * $fencheng_per['一般委托人']['divide_per'] / 100, 2);
                        }
                    }


                    if(isset($fencheng_per['独家委托人']) && $fencheng_per['独家委托人'] && $fencheng_per['独家委托人']['user_id']){
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['独家委托人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        $dividemodel->divide_money =round($fencheng_money * $fencheng_per['独家委托人']['divide_per']/100,2)+$fangyuanweihuren_money+$yibanweituoren;
                        $dividemodel->company_id = empty($fencheng_per['独家委托人']['company_id'])?$this->_user['company_id']:$fencheng_per['独家委托人']['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败61');
                        }
                    }else if(isset($fencheng_per['独家委托人']) && $fencheng_per['独家委托人'] && !$fencheng_per['独家委托人']['user_id']){
                        $chengjiao_money +=round($fencheng_money * $fencheng_per['独家委托人']['divide_per']/100,2);
                    }



                    if(isset($fencheng_per['合同成交人']) && $fencheng_per['合同成交人'] && $fencheng_per['合同成交人']['user_id']){
//                        $chengjiao_person_hetong_per = (100-($fencheng_per['房源录入人']['divide_per']+$fencheng_per['房源维护人']['divide_per']+$fencheng_per['图片录入人']['divide_per']+$fencheng_per['拿钥匙人']['divide_per']+$fencheng_per['一般委托人']['divide_per']+$fencheng_per['独家委托人']['divide_per']));
                        $chengjiao_money += round($fencheng_money * $fencheng_per['合同成交人']['divide_per']/100,2);
//                        $chengjiao_money += round($fencheng_money * $chengjiao_person_hetong_per/100,2);
                        if(isset($fencheng_per['协商划成人']) && $fencheng_per['协商划成人'] && $fencheng_per['协商划成人']['user_id']){
                            $xieshang_money = round($chengjiao_money * $fencheng_per['协商划成人']['divide_per']/100,2);
                            $chengjiao_money = $chengjiao_money - $xieshang_money;
                            $dividemodel = new OrderSellDivide();
                            $dividemodel->order_id = $model->order_id;
                            $dividemodel->commission_id = $fencheng_per['协商划成人']['oc_id'];
                            $dividemodel->cost_id = $model->cost_id;
                            $dividemodel->divide_money = $xieshang_money;
                            $dividemodel->company_id = $this->_user['company_id'];
                            $dividemodel->c_id = $this->_user['u_id'];
                            $dividemodel->u_id = $this->_user['u_id'];
                            $dividemodel->ctime = date('Y-m-d H:i:s');
                            $dividemodel->utime = date('Y-m-d H:i:s');
                            $dividemodel->is_del = 0;
                            $result = $dividemodel->save();
                            if($result === false){
                                $transaction->rollBack();
                                return ApiReturn::wrongParams('更新失败81');
                            }
                        }
                        $dividemodel = new OrderSellDivide();
                        $dividemodel->order_id = $model->order_id;
                        $dividemodel->commission_id = $fencheng_per['合同成交人']['oc_id'];
                        $dividemodel->cost_id = $model->cost_id;
                        $dividemodel->divide_money = $chengjiao_money;
                        $dividemodel->company_id = $this->_user['company_id'];
                        $dividemodel->c_id = $this->_user['u_id'];
                        $dividemodel->u_id = $this->_user['u_id'];
                        $dividemodel->ctime = date('Y-m-d H:i:s');
                        $dividemodel->utime = date('Y-m-d H:i:s');
                        $dividemodel->is_del = 0;
                        $result = $dividemodel->save();
                        $divide_id = Yii::$app->db->getLastInsertID();
                        if($result === false){
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('更新失败71');
                        }
                    }
                }
                $transaction->commit();
                return ApiReturn::success('操作成功');
            }catch (Exception $e){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败');
            }
        }
    }

    /**
     * 反驳佣金状态
     */
    public function actionRejectstatus()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            if(empty($post['id'])){
                return ApiReturn::wrongParams('参数失败');
            }
            $model =  OrderSellCost::findOne($post['id']);
            $model->cost_reason = isset($post['reason'])? $post['reason'] : '';
            $model->cost_status = isset($post['status'])? $post['status'] : '';
            $model->u_id = $this->_user['u_id'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result !== false) {
                return ApiReturn::success('操作成功');
            } else {
                return ApiReturn::wrongParams('操作失败');
            }
        }
    }

    /**
     * 递归获取父节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getChildNode($id, &$arr)
    {
        $arr[] = $id;
        $ret = Depart::find()->where(['company_id'=>$this->_user['company_id'],'d_pid' => $id])->select('d_id')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node['d_id'];
                $this->_getChildNode($node['d_id'], $arr);
            }
        }
        return array_unique($arr);
    }

    private function _getUsersByDeparts($departs)
    {
        $users = [];
        $result = User::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => '0'])->andWhere(['in', 'u_dept_id', $departs])->asArray()->all();
        foreach ($result as $item) {
            $users[] = $item['u_id'];
        }
        return $users;
    }

    private function _getUsersByDepartId($d_id)
    {
        $arr = [];
        $departs = $this->_getChildNode($d_id, $arr);
        return $this->_getUsersByDeparts($departs);
    }

}
