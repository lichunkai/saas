<?php

namespace backend\controllers;

use backend\models\Contractlist;
use backend\models\CustomColumns;
use backend\models\Customer;
use backend\models\House;
use backend\models\Yscustomer;
use backend\models\Depart;
use backend\models\OrderSell;
use backend\models\OrderSellCollection;
use backend\models\OrderSellCommission;
use backend\models\OrderSellCost;
use backend\models\OrderSellDivide;
use backend\models\OrderSellFollowup;
use backend\models\OrderSellLmg;
use backend\models\OrderSellProcedure;
use backend\models\OrderSellTel;
use backend\models\User;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingFinance;
use backend\models\ZhSettingQujian;
use backend\models\ZhSettingTransfer;
use common\controllers\CommonController;
use common\models\ApiReturn;
use common\helps\Tools;
use common\models\gii\ComDistrictGii;
use common\models\gii\HouseUserGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * 房屋出售订单控制器
 */
class OrdersellController extends AuthController
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
        $company_id = $this->_user['company_id'];
        $order_status = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'dealStatus'])->select('base_desp')->asArray()->one();
        $result['order_status'] = json_decode($order_status['base_desp'],true);
        $house_use = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'house_use'])->select('base_desp')->asArray()->one();
        $result['house_use'] = json_decode($house_use['base_desp'],true);
        $order_timechoose = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'order_timechoose'])->select('base_desp')->asArray()->one();
        $result['order_timechoose'] = json_decode($order_timechoose['base_desp'],true);
        $order_usertype = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'order_usertype'])->select('base_desp')->asArray()->one();
        $result['order_usertype'] = json_decode($order_usertype['base_desp'],true);
        $jgqj = ZhSettingQujian::find()->where(['company_id'=>$company_id,'qujian_shorthand' => 'jgqj'])->select('qujian_desp')->asArray()->one();
        $result['jgqj'] = json_decode($jgqj['qujian_desp'],true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$company_id,'qujian_shorthand' => 'mjqj'])->select('qujian_desp')->asArray()->one();
        $result['mjqj'] = json_decode($mjqj['qujian_desp'],true);

//        $depart = Depart::find()->where(['company_id' => $this->_user['company_id'], 'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
//        $result['alldepartlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $result['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        $principal = User::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->all();
        foreach ($principal as $key => $item) {
            $result['peizhi']['users'][$item['u_dept_id']][] = $item;
        }

        $result['house_other_imgs'] = [
            ['name' => '合同确认书', 'type' => 30, 'is_cover' => 0],
            ['name' => '佣金确认书', 'type' => 31, 'is_cover' => 0],
        ];
        $result['dts'] = CommonController::getDtsList($this->_user['city_id'], $this->_user['company_id']);
        //加载自定义列表
        $customcolumns = CustomColumns::find()->select('columns')->where(['company_id'=>$company_id,'u_id'=>$this->_user['u_id'],'module'=>2,'is_del'=>0])->asArray()->one();
        $result['customcolumns'] = json_decode($customcolumns['columns'],true);

        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /*
     * 房屋出售订单列表
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $company_id = $this->_user['company_id'];
        $row = OrderSell::find()->where(['company_id'=>$company_id,'is_del' => 0,'order_type'=>2])
            ->with(['created' => function ($query) {
                    $query->select(['u_id', 'u_name']);
                  }])
            ->with(['updated' => function ($query) {
                    $query->select(['u_id', 'u_name']);
                  }]);
//        $row->andWhere(['<>','order_status','审核中']);
        if (isset($param['order_status']) && $param['order_status']) {
            if($param['order_status'] == '结案'){ // 结案有佣金结案和权证结案
                $row->andWhere('order_status like "%'.trim($param['order_status']).'%"');
            }else{
                $row->andWhere(['order_status' => trim($param['order_status'])]);
            }
        }
        if (isset($param['house_use']) && $param['house_use']) {
            $row->andWhere(['house_type' => trim($param['house_use'])]);
        }
        $datecolumn = 'order_deal_date';
        if (isset($param['order_timechoose']) && $param['order_timechoose']) {
            if($param['order_timechoose'] = '成交时间'){
                $datecolumn = 'order_deal_date';
            }else if($param['order_timechoose'] = '合同结案时间'){
                $datecolumn = 'order_contract_date';
            }else if($param['order_timechoose'] = '佣金结案时间'){
                $datecolumn = 'order_commission_date';
            }
        }

        if (isset($param['daterange']) && $param['daterange']) {
            $row ->andWhere(['>=',$datecolumn,$param['daterange'][0]])->andWhere(['<=',$datecolumn,$param['daterange'][1].' 23:59:59']);
        }
        if (isset($param['house_area']) && !empty($param['house_area'][0])) {
            $row ->andWhere(['>=','a.house_area',$param['house_area'][0]])->andWhere(['<=','house_area',$param['house_area'][1]]);
        }
        if (isset($param['order_price']) && empty($param['order_price'][0])) {
            $row ->andWhere(['>=','order_price',0])->andWhere(['<=','order_price',$param['order_price'][1]]);
        }else if(isset($param['order_price']) && empty($param['order_price'][1])){
            $row ->andWhere(['>=','order_price',$param['order_price'][0]]);
        }else if(isset($param['order_price'])){
            $row ->andWhere(['>=','order_price',$param['order_price'][0]])->andWhere(['<=','order_price',$param['order_price'][1]]);
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['or', ['like', 'order_sn', $kw], ['like', 'owner_sn', $kw], ['like', 'customer_sn', $kw]]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['listdata'] = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
//        echo $row->createCommand()->getRawSql();die;
//        var_dump($listdata['listdata']);die;

        $authwhere = Yii::$app->LoadData->checkDataByUser($this->id.'/'.$this->action->id,$this->_user);

        foreach ($listdata['listdata'] as $key => $val){
            $depart = Depart::find()->where(['d_id'=>$val['order_deal_did']])->select('d_name')->asArray()->one();
            $listdata['listdata'][$key]['c_user'] = $val['created']['u_name'];
            $listdata['listdata'][$key]['u_user'] = $val['updated']['u_name'];
            $listdata['listdata'][$key]['is_jump'] = 0;
            $listdata['listdata'][$key]['is_jump'] = 0;
             $listdata['listdata'][$key]['order_deal_department'] =  $depart['d_name'];
//            $listdata['listdata'][$key]['contract_image_num'] = count(json_decode($val['contract_image'],true)) ? count(json_decode($val['contract_image'],true)):0;
            if($listdata['listdata'][$key]['order_status'] == '驳回'){
                $listdata['listdata'][$key]['order_status'] = $listdata['listdata'][$key]['order_status']."\n\n\t驳回原因：".$listdata['listdata'][$key]['order_reject_reason'];
                $listdata['listdata'][$key]['order_status_control'] = '驳回';
            }

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
     * 自定义列表
     * @return \common\models\json
     */
    public function actionCustomcolumns()
    {
        $params = Yii::$app->request->post();		
        $u_id = $this->_user['u_id'];
        $company_id = $this->_user['company_id'];
        $customcolumns = CustomColumns::find()->where(['company_id'=>$company_id,'u_id'=>$u_id,'module'=>$params['module'],'is_del'=>0])->one();
        if($customcolumns){
            $customcolumns->columns = $params['columns'];
            $customcolumns->u_id = $this->_user['u_id'];
            $customcolumns->utime = date('Y-m-d H:i:s');
        }else{
            $customcolumns = new CustomColumns();
            $customcolumns->user_id = $u_id;
            $customcolumns->module = $params['module'];
            $customcolumns->columns = $params['columns'];
            $customcolumns->company_id = $company_id;
            $customcolumns->c_id = $this->_user['u_id'];
            $customcolumns->u_id = $this->_user['u_id'];
            $customcolumns->ctime = date('Y-m-d H:i:s');
            $customcolumns->utime = date('Y-m-d H:i:s');
            $customcolumns->is_del = 0;
        }
        $result = $customcolumns->save();		
        if($result){
            return ApiReturn::success('保存成功');
        }else{
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /**
     * 获取成交详情
     * @return \common\models\json
     */
    public function actionGetinfo()
    {
        $order_id = Yii::$app->request->get('order_id');
        $company_id = $this->_user['company_id'];
//        var_dump($order_id);die;
        if(!$this->_validateOrderOwner($order_id,$this->_user,false)){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }
        $data = OrderSell::find()->where(['company_id'=>$company_id,'order_id'=>$order_id,'is_del' => 0])
            ->with('houseinfo')->with('customerinfo')->with('phone')->with('collection')->with('procedure')
            ->asArray()->one();

        //var_dump($authwhere);die;
        $data['order_price'] = (float)$data['order_price'];
        $data['order_owner_commission'] = (float)$data['order_owner_commission'];
        $data['order_customer_commission'] = (float)$data['order_customer_commission'];

        $data['jiafang'] = [];
        $data['yifang'] = [];
        foreach($data['phone'] as $key => $value){
            $value['phone']=substr_replace($value['phone'],'****',3,4);
            if($value['type'] == 1){
                $data['jiafang'][] = $value;
            }else if ($value['type'] == 2){
                $data['yifang'][] = $value;
            }
        }
        //签约值图片
        if(empty($data['contract_image'])){
            $data['contract_image'] = [['name'=>'定金合同','url'=>''], ['name'=>'资金托管协议','url'=>''], ['name'=>'首付款凭证','url'=>''], ['name'=>'资金管理','url'=>'']];
            $data['contract_image_num'] = 0;
        }else{
            $data['contract_image'] = json_decode($data['contract_image'],true);
            $data['contract_image_num'] = count($data['contract_image']);
        }
        $order_status = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'dealStatus'])->select('base_desp')->asArray()->one();
        $data['orderstatus'] = json_decode($order_status['base_desp'],true);
        //佣金收取列表
        $cproject = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'commissionProject'])->select('base_desp')->asArray()->one();
        $data['cost']['yongjin'] = json_decode($cproject['base_desp'],true);
        $oproject = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'otherProject'])->select('base_desp')->asArray()->one();
        $data['cost']['zaxiang'] = json_decode($oproject['base_desp'],true);
        $zproject = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'zheyongProject'])->select('base_desp')->asArray()->one();
        $data['cost']['zheyong'] = json_decode($zproject['base_desp'],true);
        $data['cost']['list'] = OrderSellCost::find()->alias('a')->select('a.*,b.u_name')->where(['a.company_id'=>$company_id,'a.order_id'=>$order_id,'a.is_del' => 0])->leftJoin('zh_user as b','a.c_id=b.u_id')->asArray()->all();
        $data['cost']['yy_shouqu'] =  OrderSellCost::find()->select('sum(cost_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'cost_type'=>1,'cost_payer'=>1,'cost_purpose'=>1,'is_del' => 0])->asArray()->one();
        $data['cost']['yy_zhichu'] =  OrderSellCost::find()->select('sum(cost_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'cost_type'=>1,'cost_payer'=>1,'cost_purpose'=>2,'is_del' => 0])->asArray()->one();
        $data['cost']['yk_shouqu'] =  OrderSellCost::find()->select('sum(cost_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'cost_type'=>1,'cost_payer'=>2,'cost_purpose'=>1,'is_del' => 0])->asArray()->one();
        $data['cost']['yk_zhichu'] =  OrderSellCost::find()->select('sum(cost_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'cost_type'=>1,'cost_payer'=>2,'cost_purpose'=>2,'is_del' => 0])->asArray()->one();
        $data['cost']['z_shouqu'] =  OrderSellCost::find()->select('sum(cost_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'cost_type'=>2,'cost_purpose'=>1,'is_del' => 0])->asArray()->one();
        $data['cost']['z_zhichu'] =  OrderSellCost::find()->select('sum(cost_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'cost_type'=>2,'cost_purpose'=>2,'is_del' => 0])->asArray()->one();
        //佣金划成
        $connection=Yii::$app->db;
        $perlist = $connection->createCommand('SELECT oc_id,order_id,depart_id,depart_name,user_id,user_name,reason,divide_per,0 as total,0 as money FROM zh_order_sell_commission WHERE company_id='.$company_id.' and order_id='.$order_id.' AND is_del=0')->queryAll();
        $perlist = ArrayHelper::index($perlist,'reason');

        $data['divide']['loglist'] = OrderSellDivide::find()->alias('a')->select('a.*,b.depart_name,b.user_name,b.reason,b.divide_per,c.cost_type,c.cost_day,c.cost_purpose,c.cost_money,d.company_short_title')->where(['a.order_id'=>$order_id,'a.is_del' => 0])
            ->leftJoin('zh_order_sell_commission as b','a.commission_id=b.oc_id')
            ->leftJoin('zh_order_sell_cost as c','a.cost_id=c.cost_id')
            ->leftJoin('org_company as d','a.company_id=d.company_id')
            ->asArray()->all();
        //echo $data['divide']['loglist']->createCommand()->getRawSql();die;
        $per = 0;
        $pers = 0;
        foreach ($data['divide']['loglist'] as $k =>$v){
            if(isset($perlist[$v['reason']])){
                if($v['cost_purpose'] == 1){
                    $perlist[$v['reason']]['total'] += $v['cost_money'];
                    $perlist[$v['reason']]['money'] += $v['divide_money'];
                }else if($v['cost_purpose'] == 2){
                    $perlist[$v['reason']]['total'] -= $v['cost_money'];
                    $perlist[$v['reason']]['money'] -= $v['divide_money'];
                }
            }
        }
        $data['divide']['perlist'] = array_values($perlist);
        foreach ($data['divide']['perlist'] as $kk => $vv){
            if($vv['reason'] != '合同成交人'){
                $pers += $vv['divide_per'];
            }
            if($vv['reason'] == '合同成交人'){
                $data['divide']['perlist'][$kk]['divide_per'] = 100-$pers;
                $per = 100-$pers;
            }
        }
        foreach ($data['divide']['loglist'] as $k =>$v){
            if($v['reason'] == '合同成交人'){
                $data['divide']['loglist'][$k]['divide_per'] = $per;
            }
        }

//        var_dump($data['divide']);die;
        //代收付款列表
//        $order_status = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'collectionPurpose'])->select('base_desp')->asArray()->one();
//        $data['collection']['yongtu'] = json_decode($order_status['base_desp'],true);
//        $order_status = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'collectionPayway'])->select('base_desp')->asArray()->one();
//        $data['collection']['way'] = json_decode($order_status['base_desp'],true);
//        $data['collection']['list'] = OrderSellCollection::find()->alias('a')->select('a.*,b.u_name')->where(['a.company_id'=>$company_id,'a.order_id'=>$order_id,'a.is_del' => 0])->leftJoin('zh_user as b','a.c_id=b.u_id')->asArray()->all();
//        $data['collection']['daishou'] =  OrderSellCollection::find()->select('sum(collection_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'collection_type'=>1,'is_del' => 0])->asArray()->one();
//        $data['collection']['daifu'] =  OrderSellCollection::find()->select('sum(collection_money) as num')->where(['company_id'=>$company_id,'order_id'=>$order_id,'collection_type'=>2,'is_del' => 0])->asArray()->one();
        //过户流程
//        $data['procedure']['list'] = OrderSellProcedure::find()->alias('a')->select('a.*,b.u_name')->where(['a.company_id'=>$company_id,'a.order_id'=>$order_id,'a.is_del' => 0])->leftJoin('zh_user as b','a.c_id=b.u_id')->asArray()->all();
//        $data['procedure']['currentstep'] = 0;
//        $data['procedure']['steplist'] = [];
//        foreach ($data['procedure']['list'] as $key => $item){
//             if($item['procedure_status'] == 1){
//                 $data['procedure']['currentstep'] ++;
//                 $data['procedure']['steplist'][] = ['stepid'=>$item['procedure_id'],'steptitle'=>$item['procedure_name'],'date'=>$item['procedure_finish_day']];
//             }else{
//                 $procedure_expect_day = $item['procedure_expect_day']=='0000-00-00' ? '' :$item['procedure_expect_day'];
//                 $data['procedure']['steplist'][] = ['stepid'=>$item['procedure_id'],'steptitle'=>$item['procedure_name'],'date'=>$procedure_expect_day];
//             }
//        }

        //跟进列表
        $data['genjin'] = OrderSellFollowup::find()->alias('a')->select('a.*,b.d_name,c.u_name')->where(['a.company_id'=>$company_id,'a.order_id'=>$order_id,'a.is_del' => 0])
            ->leftJoin('zh_depart as b','a.d_id=b.d_id')
            ->leftJoin('zh_user as c','a.c_id=c.u_id')
            ->asArray()->all();

//        $depart = Depart::find()->where(['company_id'=>$company_id,'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
//        $data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
//
//        $deal_arr = [];
//        $data['deal_departpath'] = $this->_getDepartid($data['order_deal_did'], $deal_arr);
//        $data['cjuser'] = User::find()->where(['is_del'=>0,'u_dept_id'=>$data['order_deal_did']])->asArray()->all();
//        $property_arr = [];
//        $data['property_departpath'] = $this->_getDepartid($data['order_property_did'], $property_arr);
//        $data['qzuser'] = User::find()->where(['is_del'=>0,'u_dept_id'=>$data['order_property_did']])->asArray()->all();
//        $linkage_arr = [];
//        $data['linkage_departpath'] = $this->_getDepartid($data['order_linkage_did'], $linkage_arr);
//        $data['lduser'] = User::find()->where(['is_del'=>0,'u_dept_id'=>$data['order_linkage_did']])->asArray()->all();
//        $consult_arr = [];
//        $data['consult_departpath'] = $this->_getDepartid($data['order_consult_did'], $consult_arr);
//        $data['xsuser'] = User::find()->where(['is_del'=>0,'u_dept_id'=>$data['order_consult_did']])->asArray()->all();

//        $data['process'] = ZhSettingTransfer::find()->select('transfer_id,transfer_name')->where(['company_id'=>$company_id])->asArray()->all();
//        $data['contractlist'] = Contractlist::find()->select('con_id,bianhao,mingcheng')->where(['company_id'=>$company_id,'bumen_id'=>$this->_user['u_dept_id'],'zhuangtai'=>'进行中','is_del'=>0])->asArray()->all();


        //图片配置
        $data['deal_imgs'] = [
            ['name' => '合同扫描件', 'type' => 1, 'is_cover' => 0],
            ['name' => '业主材料扫描件', 'type' => 2, 'is_cover' => 0],
            ['name' => '客户材料扫描件', 'type' => 3, 'is_cover' => 0],
            ['name' => '网签合同', 'type' => 4, 'is_cover' => 0],
            ['name' => '首付款凭证', 'type' => 5, 'is_cover' => 0],
            ['name' => '资金托管协议', 'type' => 6, 'is_cover' => 0],
            ['name' => '成交图片', 'type' => 7, 'is_cover' => 0],
        ];
        //获取房源图片
        $images = OrderSellLmg::find()->where(['company_id'=>$company_id,'order_id' => $order_id, 'is_del' => '0'])->asArray()->all();
        foreach ($data['deal_imgs'] as $tmp) {
            if (!empty($images)) {
                foreach ($images as $key => $item) {
                    $data['images'][$item['oi_type']] = $item;
                }
                if (empty($data['images'][$tmp['type']])) {
                    $data['images'][$tmp['type']] = false;
                }
            } else {
                $data['images'][$tmp['type']] = false;

            }
        }

        return ApiReturn::success('获取成功', $data);
    }
    /**
     * 添加成交信息
     * @return \common\models\json
     */
    public function actionAdd()
    {
        // 0 jinxingzhong 1 2 3
        $post = Yii::$app->request->post();
        $chengjiaoren = User::find()->select('u_name')->where(['u_id'=>$post['order_deal_user']])->asArray()->one();
//        $house_person = House::find()->select('dts_id,dts_name,village_id,village_name,fangwuleixing,jianzhumianji,loudong_name,danyuan_name,fanghao_name,c_id,u_id')->where(['house_sn'=>$_POST['owner_sn']])->asArray()->one();
        $house_person = House::find()->select('*')->where(['house_sn'=>$_POST['owner_sn']])->asArray()->one();
        $rows = OrderSell::find()->select('order_sn')->where(['village_id'=>$house_person['village_id'],'village_name'=>$house_person['village_name'],'house_building'=>$house_person['loudong_name'],'house_unit'=>$house_person['danyuan_name'],'house_door'=>$house_person['fanghao_name']])->asArray()->one();
//        if(!empty($rows)){
//            return ApiReturn::wrongParams('添加房源与【'.$house_person['house_sn'].'】重复');
//        }

        $post['order_deal_did'] = $post['d_id'];
//        house_building house_unit house_door
        $ordersellmodel =  new OrderSell();
        $ordersellmodel->order_zaxiang_type = $post['order_zaxiang_type'];
        $ordersellmodel->order_zaxiang_commission = $post['order_zaxiang_commission'];
        $ordersellmodel->order_deal_username = $chengjiaoren['u_name'];
        $ordersellmodel->house_building = $house_person['loudong_name'];
        $ordersellmodel->house_unit = $house_person['danyuan_name'];
        $ordersellmodel->house_door = $house_person['fanghao_name'];
        $ordersellmodel->house_name = $house_person['village_name'];
        $ordersellmodel->house_type = $house_person['fangwuleixing'];
        $ordersellmodel->dts_id = $house_person['dts_id'];
        $ordersellmodel->dts_name = $house_person['dts_name'];
        $ordersellmodel->village_id = $house_person['village_id'];
        $ordersellmodel->village_name = $house_person['village_name'];
        $ordersellmodel->house_area = $house_person['jianzhumianji'];
        $ordersellmodel->order_type = 2;
        $ordersellmodel->order_sn = 'CSCJ-' . date('ymd') . '-' . substr(str_pad($_POST['owner_sn'], 4, "0", STR_PAD_LEFT),-4);
        $ordersellmodel->contract_sn = 'MMHT-' . date('ymd') . '-' . substr(str_pad($_POST['owner_sn'], 4, "0", STR_PAD_LEFT),-4);
        $ordersellmodel->contract_name="买卖合同";
        $ordersellmodel->order_status = '审核中';
        $ordersellmodel->company_id = $this->_user['company_id'];
        $ordersellmodel->is_del = 0;
        $ordersellmodel->c_id = empty($house_person['c_id'])?'':$house_person['c_id'];
        $ordersellmodel->u_id = empty($house_person['u_id'])?'':$house_person['u_id'];
        $ordersellmodel->ctime = date('Y-m-d H:i:s');
        $ordersellmodel->utime = date('Y-m-d H:i:s');

        $bilifencheng = ZhSettingFinance::find()->where(['company_id'=>$this->_user['company_id'],'finance_shorthand'=>'secondhouse'])->orderBy('finance_id DESC')->asArray()->all();
        $finance_type_person = json_decode($bilifencheng[0]['finance_desp'],true);

        if($ordersellmodel->load($post, '') && $ordersellmodel->save()){
            $fangyuanren = array();
            $yaoshiren = array();
            $yibanweituo = array();
            $order_id = $ordersellmodel -> attributes['order_id'];
            $order_uesr_id = $ordersellmodel -> attributes['order_deal_user'];
            $order_depart_id = $ordersellmodel -> attributes['order_deal_did'];
            $model = new OrderSellLmg();
            if(!empty($post['hetong_image'])){
                $param1 = array(
                    'images' => array(
                        array(
                            'oi_url' => $post['hetong_image'],
                            'oi_type' => 5
                        )
                    ),
                    'order_id' => $order_id,
                );
                $model->updateDealImg($param1, $this->_user);
            }
            if(!empty($post['yongjin_image'])){
                $param2 = array(
                    'images' => array(
                        array(
                            'oi_url' => $post['yongjin_image'],
                            'oi_type' => 6
                        )
                    ),
                    'order_id' => $order_id,
                );
                $model->updateDealImg($param2, $this->_user);
            }
            $houseuser = new HouseUserGii();
            $house_users =  $houseuser::find()->select('*')->where(['house_id'=>$house_person['house_uuid'],'is_del'=>0])->orderBy('ctime DESC')->asArray()->all();
            foreach($house_users as $k => $v){
                if($v['type'] == 1 && $v['company_id'] == $this->_user['company_id']){
                    $fangyuanren[$k] = $v;
                    $person[$k] = $v;
                    unset($house_users[$k]);
                }
                if($v['type'] == 4 && $v['company_id'] == $this->_user['company_id']){
                    $yaoshiren[$k] = $v;
                    $person[$k] = $v;
                    unset($house_users[$k]);
                }
                if($v['type'] == 5 && $v['company_id'] == $this->_user['company_id']){
                    $yibanweituo[$k] = $v;
                    $person[$k] = $v;
                    unset($house_users[$k]);
                }
            }
//            var_dump($fangyuanren);die;
            if(!empty($person)){
                foreach($house_users as $k => $v){
                    if($v['type'] == 1 && !empty($fangyuanren)){
                        unset($house_users[$k]);
                    }elseif($v['type'] == 4 && !empty($yaoshiren)){
                        unset($house_users[$k]);
                    }elseif($v['type'] == 5 && !empty($yibanweituo)){
                        unset($house_users[$k]);
                    }
                }
                $house_users = array_merge($house_users,$person);
            }

            foreach ($house_users as $k=>$v){
                switch ($v['type']){
                    case 1:
                        $house_users[$k]['type'] = '房源录入人';
                        break;
                    case 2:
                        $house_users[$k]['type'] = '房源维护人';
                        break;
                    case 3:
                        $house_users[$k]['type'] = '图片录入人';
                        break;
                    case 4:
                        $house_users[$k]['type'] = '拿钥匙人';
                        break;
                    case 5:
                        $house_users[$k]['type'] = '一般委托人';
                        break;
                    case 6:
                        $house_users[$k]['type'] = '独家委托人';
                        break;
                    case 7:
                        $house_users[$k]['type'] = '合同成交人';
                        break;
                    default:
                        break;
                }
            }

            foreach ($finance_type_person as $kf => $vf) {
                foreach($house_users as $k => $v) {
                    if ($vf['finance_name'] == $v['type']) {
                        $finance_type_person[$kf]['user_id'] = $v['user_id'];
                        $finance_type_person[$kf]['depart_id'] = $v['depart_id'];
                        $finance_type_person[$kf]['type'] = $v['type'];
                    }

                    if($vf['finance_name'] == '合同成交人'){
                        $finance_type_person[$kf]['user_id'] = $ordersellmodel -> attributes['order_deal_user'];
                        $finance_type_person[$kf]['depart_id'] = $ordersellmodel -> attributes['order_deal_did'];
                        $finance_type_person[$kf]['type'] = '合同成交人';
                    }
                }
            }

//            var_dump($finance_type_person);die;
            //添加佣金记录表
            $ordersellcommisionmodel =  new OrderSellCommission();
            foreach($finance_type_person as $ks => $vs) {
                if(!empty($vs['user_id'])){
                $commision_user = User::find()
                                        ->alias('a')
                                        ->select('a.u_name,b.company_id,b.company_title,b.company_short_title')
                                        ->leftJoin('org_company as b', 'a.company_id=b.company_id')
                                        ->where(['a.u_id'=>$vs['user_id']])
                                        ->asArray()->one();
                }
                if(!empty($vs['depart_id'])){
                    $commision_depart = Depart::find()->where(['d_id'=> $vs['depart_id'],'is_del'=>0])->select('d_name')->asArray()->one();

                }

                $ordersellcommisionmodel->order_id = $order_id;
                $ordersellcommisionmodel->depart_id = empty($vs['depart_id'])?0:$vs['depart_id'];
                $ordersellcommisionmodel->depart_name = $commision_depart['d_name'];
                $ordersellcommisionmodel->user_job = '';
                $ordersellcommisionmodel->user_id = empty($vs['user_id'])?0:$vs['user_id'];
                $ordersellcommisionmodel->user_name = $commision_user['u_name'];
                $ordersellcommisionmodel->reason = empty($vs['type'])?$vs['finance_name']:$vs['type'];
                $ordersellcommisionmodel->divide_per = empty($vs['finance_per'])?0:$vs['finance_per'];//分成比例
                $ordersellcommisionmodel->tc_did = ''; //提成人店id
                $ordersellcommisionmodel->tc_dfzr = ''; //店负责人uid
                $ordersellcommisionmodel->tc_qid = ''; //提成区id
                $ordersellcommisionmodel->tc_qfzr = ''; //区负责人uid
                $ordersellcommisionmodel->tc_dqid = ''; //提成大区id
                $ordersellcommisionmodel->tc_dqfzr = ''; //大区负责人
                $ordersellcommisionmodel->c_id = '';
                $ordersellcommisionmodel->u_id = '';
                $ordersellcommisionmodel->ctime = date('Y-m-d H:i:s', time());
                $ordersellcommisionmodel->utime = date('Y-m-d H:i:s', time());
                $ordersellcommisionmodel->is_del = 0;
                $ordersellcommisionmodel->company_id = empty($commision_user['company_id'])?$this->_user['company_id']:$commision_user['company_id'];
                $models = clone $ordersellcommisionmodel;
                $models->save();
//                var_dump($commision_user);
            }
            return ApiReturn::success('添加成功');
        }else{
            $errors = $ordersellmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }

    }
    /**
     * 修改成交信息
     * @return \common\models\json
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();

        $ordermodel = $this->_validateOrderOwner($post['order_id'],$this->_user,true);
        if($ordermodel === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {  //var_dump($post);die;
            $ownerphone = $ordermodel->owner_phone;
            $customerphone = $ordermodel->customer_phone;

            $ordermodel->u_id = $this->_user['u_id'];
            $ordermodel->utime = date('Y-m-d H:i:s');

            if($post['order_loan'] === true){
                $post['order_loan'] = 1;
            }else{
                $post['order_loan'] = 0;
            }
            if($ordermodel->load($post, '') === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败1');
            }
            if($ordermodel->save() === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败2');
            }
            $ordertelmodel = OrderSellTel::find()->where(['or',['phone'=>$ownerphone],['phone'=>$customerphone]])->all();
            foreach ($ordertelmodel as $key=>$ordertel){
                if($ordertel->type == 1){
                    $ordertel->phone = $post['owner_phone'];
                }else{
                    $ordertel->phone = $post['customer_phone'];
                }
                $result = $ordertel->save();
                if($result === false){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('更新失败');
                }
            }
            $transaction->commit();
            return ApiReturn::success('更新成功');

        }catch (Exception $e){
            $transaction->rollBack();
            return ApiReturn::wrongParams('更新失败10');
        }
    }

    /**
     * 变更成交状态
     * @return \common\models\json
     */
    public function actionChangestatus()
    {
        $post = Yii::$app->request->post();

        $ordermodel = $this->_validateOrderOwner($post['order_id'],$this->_user,true);
        if($ordermodel === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        //$ordermodel =  OrderSell::findOne($post['order_id']);
        $ordermodel->u_id = $this->_user['u_id'];
        $ordermodel->utime = date('Y-m-d H:i:s');

        if($ordermodel->load($post, '') && $ordermodel->save()){
            return ApiReturn::success('更新成功');
        }else{
            $errors = $ordermodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /**
     * 签约值图片
     * @return \common\models\json
     */
    public function actionContractimage(){
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if(empty($post['order_id'])){
                return ApiReturn::wrongParams('参数错误');
            }
            $ordersell = $this->_validateOrderOwner($post['order_id'],$this->_user,true);
            if($ordersell === false){  //验证数据
                return ApiReturn::forbidden('无数据或无查看权限！');
            }
            $ordersell->contract_image = json_encode($post['contract_image']);
            $ordersell->u_id = $this->_user['u_id'];
            $ordersell->utime = date('Y-m-d H:i:s');
            $result = $ordersell->save();
            if($result !==false){
                $i= 0;
                foreach ($post['contract_image'] as $item){
                    if(!empty($item['url'])){
                        $i++;
                    }
                }
                return ApiReturn::success('上传成功',$i);
            }else{
                return ApiReturn::success('上传失败');
            }

        }
    }

    /***
     * 保存成交图片
     * @param post 上传的图片数据
     * @return  上传是否成功
     */
    public function actionImgupload()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            try {
                $model = new OrderSellLmg();
                if ($model->updateDealImg($post, $this->_user)) {
                    return ApiReturn::success('上传成功');
                } else {
                    return ApiReturn::wrongParams('上传失败2');
                }
            } catch (Exception $exception) {
                return ApiReturn::codeError('上传失败1');
            }

        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /**
     * 添加佣金记录
     * @return \common\models\json
     */
    public function actionAddcommission()
    {
        $post = Yii::$app->request->post();
        $post['data']['cost_image'] = isset($post['data']['cost_image'])&&count($post['data']['cost_image'])>=1 ? json_encode($post['data']['cost_image']):'';

        $result = $this->_validateOrderOwner($post['data']['order_id'],$this->_user,false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        $ordersellcostmodel =  new OrderSellCost();

        $ordersellcostmodel->cost_status = 0;
        $ordersellcostmodel->company_id = $this->_user['company_id'];
        $ordersellcostmodel->c_id = $this->_user['u_id'];
        $ordersellcostmodel->u_id = $this->_user['u_id'];
        $ordersellcostmodel->ctime = date('Y-m-d H:i:s');
        $ordersellcostmodel->utime = date('Y-m-d H:i:s');
        $ordersellcostmodel->is_del = 0;

        if($ordersellcostmodel->load($post['data'], '') && $ordersellcostmodel->save()){
            return ApiReturn::success('添加成功');
        }else{
            $errors = $ordersellcostmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /**
     * 添加代付款记录
     * @return \common\models\json
     */
    public function actionAddcollection()
    {
        $post = Yii::$app->request->post();
        $post['data']['collection_image'] = isset($post['data']['collection_image'])&&count($post['data']['collection_image'])>=1 ? json_encode($post['data']['collection_image']):'';

        $result = $this->_validateOrderOwner($post['data']['order_id'],$this->_user,false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        $ordersellcostmodel =  new OrderSellCollection();

        $ordersellcostmodel->collection_status = 0;
        $ordersellcostmodel->company_id = $this->_user['company_id'];
        $ordersellcostmodel->c_id = $this->_user['u_id'];
        $ordersellcostmodel->u_id = $this->_user['u_id'];
        $ordersellcostmodel->ctime = date('Y-m-d H:i:s');
        $ordersellcostmodel->utime = date('Y-m-d H:i:s');
        $ordersellcostmodel->is_del = 0;

        if($ordersellcostmodel->load($post['data'], '') && $ordersellcostmodel->save()){
            return ApiReturn::success('添加成功');
        }else{
            $errors = $ordersellcostmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /**
     * 成交进度调整
     * @return \common\models\json
     */
    public function actionAddprocedure()
    {
        $post = Yii::$app->request->post('data');

        $result = $this->_validateOrderOwner($post['order_id'],$this->_user,false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $currentdata =  OrderSellProcedure::findOne($post['current_id']);
            $currentdata->procedure_finish_day = date('Y-m-d');
            $currentdata->procedure_status = 1;
            $currentdata->company_id = $this->_user['company_id'];
            $currentdata->u_id = $this->_user['u_id'];
            $currentdata->utime = date('Y-m-d H:i:s');
            $result = $currentdata->save();
            if($result === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败');
            }
            $nextdata =  OrderSellProcedure::findOne($post['next_id']);
            $nextdata->procedure_expect_day = $post['procedure_expect_day'];
            $nextdata->company_id = $this->_user['company_id'];
            $nextdata->u_id = $this->_user['u_id'];
            $nextdata->utime = date('Y-m-d H:i:s');
            $result = $nextdata->save();
            if($result === false){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败');
            }
            $transaction->commit();
            return ApiReturn::success('更新成功');
        }catch (Exception $e){
            $transaction->rollBack();
            return ApiReturn::wrongParams('更新失败');
        }
    }

    /**
     * 添加联系方式
     * @return \common\models\json
     */
    public function actionAddphone()
    {
        $post = Yii::$app->request->post();

        $result = $this->_validateOrderOwner($post['order_id'],$this->_user,false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        $orderselltelmodel =  new OrderSellTel();
        $orderselltelmodel->company_id = $this->_user['company_id'];
        $orderselltelmodel->c_id = $this->_user['u_id'];
        $orderselltelmodel->u_id = $this->_user['u_id'];
        $orderselltelmodel->ctime = date('Y-m-d H:i:s');
        $orderselltelmodel->utime = date('Y-m-d H:i:s');

        if($orderselltelmodel->load($post, '') && $orderselltelmodel->save()){
            return ApiReturn::success('添加成功');
        }else{
            $errors = $orderselltelmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /**
     * 查看手机号
     * @return \common\models\json
     */
    public function actionShowphone()
    {
        $post = Yii::$app->request->post();

        $result = $this->_validateOrderOwner($post['order_id'],$this->_user,false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        $orderselltelmodel =  new OrderSellFollowup();

        $post['of_type'] = '查看电话';
        $post['of_content'] = '查看电话';
        $post['d_id'] = $this->_user['u_dept_id'];
        $orderselltelmodel->company_id = $this->_user['company_id'];
        $orderselltelmodel->c_id = $this->_user['u_id'];
        $orderselltelmodel->u_id = $this->_user['u_id'];
        $orderselltelmodel->ctime = date('Y-m-d H:i:s');
        $orderselltelmodel->utime = date('Y-m-d H:i:s');

        if($orderselltelmodel->load($post, '') && $orderselltelmodel->save()){
            $phone = OrderSellTel::find()->where(['order_id'=>$post['order_id']])->asArray()->all();
            foreach($phone as $key => $value){
                if($value['type'] == 1){
                    $data['jiafang'][] = $value;
                }else if ($value['type'] == 2){
                    $data['yifang'][] = $value;
                }
            }
            return ApiReturn::success('查询成功',$data);
        }else{
            $errors = $orderselltelmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /*
     * 用户删除
     * @return array|\common\models\json
     */
    public function actionDelgenjin()
    {
        $id = Yii::$app->request->post('of_id');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = OrderSellFollowup::findOne($id);
        $model->is_del = 1;
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::success('删除失败');
        }
    }

    /**
     * 获取用户
     * @return \common\models\json
     */
    public function actionGetuser()
    {
        $d_id = Yii::$app->request->post('d_id');
        $data = User::find()->where(['company_id'=>$this->_user['company_id'],'is_del'=>0,'u_dept_id'=>$d_id]);
        $data->andWhere('u_status=1 or u_status=2');
        $data = $data ->asArray()->all();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 添加跟进
     * @return array|\common\models\json
     */
    public function actionAddgenjin()
    {
        $post = Yii::$app->request->post();
        $result = $this->_validateOrderOwner($post['order_id'],$this->_user,false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }

        $orderselltelmodel =  new OrderSellFollowup();
        $orderselltelmodel->company_id = $this->_user['company_id'];
        $orderselltelmodel->c_id = $this->_user['u_id'];
        $orderselltelmodel->u_id = $this->_user['u_id'];
        $orderselltelmodel->ctime = date('Y-m-d H:i:s');
        $orderselltelmodel->utime = date('Y-m-d H:i:s');

        if($orderselltelmodel->load($post, '') && $orderselltelmodel->save()){
            $data['genjin'] = OrderSellFollowup::find()->alias('a')->select('a.*,b.d_name,c.u_name')->where(['a.order_id'=>$post['order_id'],'a.is_del' => 0])
                ->leftJoin('zh_depart as b','a.d_id=b.d_id')
                ->leftJoin('zh_user as c','a.c_id=c.u_id')
                ->asArray()->all();
            return ApiReturn::success('添加成功',$data);
        }else{
            $errors = $orderselltelmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    public function actionCustomerlist()
    {
        $param = Yii::$app->request->post();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $u_id = $this->_user['u_id'];
        $sjtime = time();

        $row = Customer::find()->alias('a')->select('a.*,b.tel_phone')
        ->where(['a.zhuangtai'=>'有效','a.company_id'=>$this->_user['company_id'],'a.is_del'=>0])
        ->leftJoin('zh_customer_tel as b','a.customer_uuid = b.customer_uuid');
        $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");

        if(isset($param['type']) && $param['type']== 1){
            $row->andWhere(['a.customer_type'=>1]);
        }else if(isset($param['type']) && $param['type']== 2){
            $row->andWhere(['a.customer_type'=>0]);
        }else if(isset($param['type']) && $param['type']== 3){
            $row->andWhere(['a.customer_type'=>2]);
        }

        if(isset($param['kw']) && $param['kw']){
            $row->andWhere(['or', ['like', 'b.tel_phone', trim($param['kw'])], ['like', 'a.customer_name', trim($param['kw'])]]);
        }

        $listdata['totalnum'] = $row->groupBy('a.customer_id')->count();
        $listdata['listdata'] = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->groupBy('a.customer_id')->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;
        foreach ($listdata['listdata'] as $key => $item) {
            $listdata['listdata'][$key]['customer_type'] = $item['customer_type'] == 0 ? '求购' : '租房';
        }

        return ApiReturn::success('获取成功',$listdata);
    }

    public function actionHouselist(){
        $param = Yii::$app->request->post();
        $page = !empty($param['hpageCurrent']) ? $param['hpageCurrent'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $u_id = $this->_user['u_id'];
        $sjtime = time();
//        var_dump($param);die;
        $row = House::find()->select('*')->where(['house_status'=>'1','sale_type' => 2,'is_del'=>0]);
//
//        if (isset($param["dts_id"]) && is_array($param["dts_id"])) {  //片区
//            if (count($param["dts_id"]) > 1) {
//                $row->andWhere(['dts_id' => end($param['dts_id'])]);
//            } else {
//                $dts_ids = ComDistrictGii::find()->select('dts_id')->where(['is_del' => '0', 'area_id' => end($param['dts_id'])])->andWhere(['OR', 'dts_status=0', 'dts_status=1 AND company_id=' . $this->_user['company_id']])->asArray()->all();
//                if ($dts_ids) {
//                    $row->andWhere(['IN', 'dts_id', $dts_ids]);
//                }
//            }
//        }
//        if (isset($param['village_id']) && !empty($param['village_id'])) { //小区
//            $row->andWhere(['village_id' => $param['village_id']]);
//        }
        if (isset($param["loudong_name"]) && $param["loudong_name"]) { //楼栋
            $row->andWhere(['loudong_name' => $param['loudong_name']]);
        }
        if (isset($param["danyuan_name"]) && $param["danyuan_name"]) { //单元
            $row->andWhere(['danyuan_name' => $param['danyuan_name']]);
        }
        if (isset($param["fanghao_name"])) { //房号
            $row->andWhere("fanghao_name like '%" . $param["fanghao_name"] . "%'");
        }
        if (isset($param["housekeyword"]) && $param["housekeyword"]) { //单元
//            ['or', ['like','base_name',trim($param['kw'])], ['like', 'base_shorthand', trim($param['kw'])]]
            $row->andWhere(['or',['like', 'village_name', trim($param["housekeyword"])],['customer_phone'=> $param["housekeyword"]],['like', 'customer_name', trim($param["housekeyword"])]]);
        }

        $listdata['totalnum'] = $row->groupBy('house_sn')->count();
        $listdata['listdata'] = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->groupBy('house_sn')->asArray()->all();

        //echo $row->createCommand()->getRawSql();die;
//        var_dump($listdata);die;
        return ApiReturn::success('获取成功',$listdata);

    }
    //新房的客源获取
    public function actionYscustomerlist()
    {
        $param = Yii::$app->request->post();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $u_id = $this->_user['u_id'];
        $sjtime = time();

        $row = Yscustomer::find()->alias('a')->select('a.*,b.tel_phone')
            ->where(['a.zhuangtai'=>'有效','a.company_id'=>$this->_user['company_id'],'a.is_del'=>0])
            ->leftJoin('ys_customer_tel as b','a.customer_id = b.customer_id');
        $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");
        if(isset($param['kw']) && $param['kw']){
            $row->andWhere(['or', ['like', 'b.tel_phone', trim($param['kw'])], ['like', 'a.customer_name', trim($param['kw'])]]);
        }
        $listdata['totalnum'] = $row->groupBy('a.customer_id')->count();
        $listdata['listdata'] = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->groupBy('a.customer_id')->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;
        foreach ($listdata['listdata'] as $key => $item) {
            $listdata['listdata'][$key]['customer_type'] = $item['customer_type'] == 0 ? '求购' : '租房';
        }

        return ApiReturn::success('获取成功',$listdata);
    }

    public function actionGetdealavg()
    {
        $param = Yii::$app->request->post();

        $row = OrderSell::find()->select('SUM(order_price) as price,SUM(house_area) as area')->where(['company_id'=>$this->_user['company_id'],'is_del'=>0,'order_type'=>$param['type']]);
        if(isset($param['area']) && $param['area']){
            $row->andWhere(['dts_id'=>$param['area']]);
        }
        if(isset($param['dealtime']) && $param['dealtime']){
            if($param['dealtime'] == '近三个月'){
                $time = date('Y-m-01',strtotime('-2 month'));
            }elseif ($param['dealtime'] == '近半年'){
                $time = date('Y-m-01',strtotime('-5 month'));
            }elseif ($param['dealtime'] == '近一年'){
                $time = date('Y-m-01',strtotime('-11 month'));
            }elseif ($param['dealtime'] == '近两年'){
                $time = date('Y-m-01',strtotime('-23 month'));
            }
            $row->andWhere(['>=','order_deal_date',$time]);
        }
        //echo $row->createCommand()->getRawSql();die;
        $data = $row->asArray()->one();
        $avg_price = '-';
        if($data['area'] != 0){
            $avg_price = round($data['price']*10000/$data['area'],2);
        }


        return ApiReturn::success('获取成功',$avg_price);
    }

    /*
     * @经纪人导出
     */
    public function actionExport()
    {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);

        $row = OrderSell::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0,'order_type'=>2])->with(['created' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }])->with(['updated' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }]);
//        $authwhere = Yii::$app->LoadData->checkDataByUser('ordersell/getindex',$this->_user);
//        if($authwhere['key'] == 'none'){
//            return ApiReturn::success('无查看权限！');
//        }else if($authwhere['key'] != 'all'){
//            $columns = $authwhere['key'];
//            $row->andWhere([$columns=>$authwhere['value']]);
//        }
        $result['list'] = $row->orderBy('order_deal_date DESC')->asArray()->all();
        $data = $result['list'];
        $n=0;
        foreach ( $data as $v )
        {
            //报表头的输出
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:S1');
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','买卖成交');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','买卖成交');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','买卖成交');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('S2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','合同编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','订单状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','分成状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','成交日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','区域');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','小区');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','座栋');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','单元');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','门牌号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','业主');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3','客户');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3','面积');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3','成交价');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3','协议编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3','房源编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3','客源编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3','维护人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3','成交人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('S3','录入人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+4) ,$v['order_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+4) ,$v['order_status']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,$v['order_divide_status']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+4) ,$v['order_deal_date']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+4) ,$v['dts_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,$v['village_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+4) ,$v['house_building']);
            $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n+4) ,$v['house_unit']);
            $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n+4) ,$v['house_door']);
            $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n+4) ,$v['owner_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('K'.($n+4) ,$v['customer_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('L'.($n+4) ,$v['house_area']);
            $objectPHPExcel->getActiveSheet()->setCellValue('M'.($n+4) ,$v['order_price']);
            $objectPHPExcel->getActiveSheet()->setCellValue('N'.($n+4) ,$v['agreement_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('O'.($n+4) ,$v['owner_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('P'.($n+4) ,$v['customer_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('Q'.($n+4) ,$v['order_deal_username']);
            $objectPHPExcel->getActiveSheet()->setCellValue('R'.($n+4) ,$v['created']['u_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('S'.($n+4) ,$v['updated']['u_name']);
            $n = $n +1;
        }

        //设置分页显示
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header("Content-type: text/html; charset=utf-8");
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'chengjiao-'.date("Y/m/j").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');
    }
}
