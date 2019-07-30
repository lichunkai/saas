<?php
namespace backend\controllers;

use backend\models\Customer;
use backend\models\Customer_xiading;
use backend\models\Depart;
use backend\models\OrderSellCollection;
use backend\models\User;
use common\models\ApiReturn;
use common\models\CommSetting;
use backend\models\Customer_log;
use common\helps\Tools;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * 买卖客源
 */
class Customer_xiadingController extends AuthController
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

    /*
     *买卖客源下定列表
     */
    public function actionGetindex(){
        $param = Yii::$app->request->get()?Yii::$app->request->get():Yii::$app->request->post();
        $page = isset($param["page"])&&$param["page"] ? $param["page"] : 1;
        if($param['d_id']=='undefined'){
            $param['d_id']='';
        }
        $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page-1)*$pagesize;
        $user = $this->_user();
        //部门数据
        $depart = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');


        //获取本人本组大区本大区的方法
        $bendianbenzu = Customer::getBumen(152, $user);
        //如果有部门查询 就找出当前查询部门的所有数据
        if (!empty($param['d_id'])){
            $departlist = Customer::getTree($depart, $param['d_id']);
        } else {
            /*
  * 初始化部门
  */
            //获取子集的树
            $departlist =  Customer::getTree($depart,$bendianbenzu);
            //把 树转换为列
            $departlist=Customer::setlistname($departlist);
            //获取父
            $departzhuyaode = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'d_id'=>$bendianbenzu])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();

            //子集加上父集
            if(!empty($departlist)  && !empty($departzhuyaode)){
                $departzhuyaode[0]['d_pid']=0;
                $departlist =array_merge($departlist,$departzhuyaode);
            }else if(!empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid']=0;
                $departlist =$departzhuyaode;
            }
            //数据排序一下
            if(!empty($departlist)){
                $departlist=Tools::listToTree($departlist, 'value', 'd_pid', 'children');
            }
        }
        //判断是否为真和是否为本人
        if ($bendianbenzu && $bendianbenzu != $user['u_id']) {
            $u_dept_id = $bendianbenzu;
            if (empty($param['d_id'])) {
                if($bendianbenzu<>'sys'){
                    foreach ($departlist as $v) {
                        //判断与他自己的部门
                        if ($v['value'] == $u_dept_id) {
                            $benzutree[] = $v;
                        }
                    }
                }else{
                        $benzutree=$data['peizhi']['departlist'];
                }
            }else{
                $benzutree=$departlist;
            }

            if (!empty($benzutree)) {

                $data['peizhi']['benzu'] = $benzutree;
                //把多维 树形数据变成一维
                $benzu = Customer::setlist($benzutree);
                $benzuin = !empty($param['d_id'])?$param['d_id'].',':'';
                //用in 的方法查出包含部门的所用用户数据。
                foreach ($benzu as $v) {
                    $benzuin .= $v['value'] . ',';
                }
                $benzuin = substr($benzuin, 0, strlen($benzuin) - 1);
            }
        }
        if(!empty($param['d_id']) &&  empty($benzuin)){
            $benzuin=$param['d_id'];
        }
        $row=Customer_xiading::find()
            ->select('x.*,x.ctime as x_ctime,x.beizhu as x_beizhu,c.*,h.house_sn,h.dts_id,h.dts_name as h_dts_name,h.village_name as h_village_name,h.loudong_name,h.danyuan_name,h.fanghao_name,u.*')->alias('x')
            ->leftJoin('zh_customer as c', 'c.customer_uuid=x.customer_uuid')
            ->leftJoin('zh_house as h', 'h.house_uuid=x.house_uuid')
            ->leftJoin('zh_user as u', 'u.u_id=x.jingbanren_id')
            ->where(['x.is_del'=>0,'x.company_id'=>$this->_user['company_id']]);
        //判断是否有部门搜索
        if (!empty($param['d_id']) && !empty($benzuin)) {
            $benzuin = !empty($benzuin) ? $benzuin : 0;
            $row->andWhere("u.u_dept_id in ($benzuin) ");
        }elseif(!empty($benzuin)){
            $row->andWhere("u.u_dept_id in ($benzuin) ");
        }
        if (!empty($param['user'])) {
            $row->andWhere(['=', 'x.jingbanren_id', $param['user']]);
        }
        if (!empty($param['bianhao'])) {
            $ssk = $param['bianhao'];
            $row->andWhere("c.xuqiubianhao like '%$ssk%' or h.fanghao_name like '%$ssk%' or h.house_sn like '%$ssk%' or h.house_sn like '%$ssk%'or x.chengjiaobianhao like '%$ssk%' or x.piaojuhao like '%$ssk%'");
        }
        if (!empty($param['shijian'])) {
            $row->andWhere(['>=', 'unix_timestamp(x.ctime)', strtotime($param['shijian'][0])]);
            $row->andWhere(['<=', 'unix_timestamp(x.ctime)', strtotime($param['shijian'][1])]);
        }
        if(!empty($param['customer_type']) && $param['customer_type']<>'quanbu'){
            $row->andWhere(['c.customer_type'=>$param['customer_type']]);
        }
        if(!empty($param['x_zhuangtai']) || $param['x_zhuangtai']<>""){
            $row->andWhere(['x.x_zhuangtai'=>$param['x_zhuangtai']]);
        }
        $list=$row->orderBy('x.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
if(!empty($list)){
        foreach ($list as $key => $v) {
            if ($v['customer_type'] == '1') {
                $list[$key]['customer_type_name'] ='租房';
            }else if ($v['customer_type'] == '2') {
                $list[$key]['customer_type_name'] ='求购高端';
            }else{
                $list[$key]['customer_type_name'] ='求购';
            }
            if ($v['x_zhuangtai'] == '0') {
                $list[$key]['x_zhuangtai'] ='待确认';
            }
            if ($v['x_zhuangtai'] == '1') {
                $list[$key]['x_zhuangtai'] ='支出';
            }
            if ($v['x_zhuangtai'] == '2') {
                $list[$key]['x_zhuangtai'] ='确认';
            }
            if ($v['x_zhuangtai'] == '3') {
                $list[$key]['x_zhuangtai'] ='退定';
            }
            if ($v['x_zhuangtai'] == '4') {
                $list[$key]['x_zhuangtai'] ='已驳回';
            }
            if ($v['h_dts_name'] and $v['h_village_name']) {
                $list[$key]['quyu'] =$v['h_dts_name'].';'.$v['h_village_name'];
            }
            if ($v['h_dts_name'] and !$v['h_village_name']) {
                $list[$key]['quyu'] =$v['h_dts_name'];
            }
            if ($v['loudong_name'] and $v['danyuan_name']) {
                $list[$key]['zuodong'] =$v['loudong_name'].'栋'.$v['danyuan_name'].'单元';
            }
        }
}
        //员工数据
        $principal = User::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->all();
        foreach ($principal as $key => $item) {
            $data['peizhi']['users'][$item['u_dept_id']][] = $item;
        }
        $data['list'] = $list;
        $data['count'] = $row->count();
        return ApiReturn::success('查询成功',$data);
    }
    /*
   * 添加客源跟进
   */
    public function actionAdd(){
        $post=Yii::$app->request->post();
        $post['image'] = isset($post['image'])&&count($post['image'])>=1 ? json_encode($post['image']):'';
        if(!empty($post)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();//开启事物
            try {
                $model=new Customer_xiading;
                $model->xiadingjine= $post['xiadingjine'];
                $model->xiadingriqi= $post['xiadingriqi'];
                $model->bumen=!empty($post['bumen'][count($post['bumen'])-1]['value'])?$post['bumen'][count($post['bumen'])-1]['value']:$post['bumen'][count($post['bumen'])-1];
                $model->beizhu= $post['beizhu'];
                $model->jingbanren_id= $post['jingbanren_id'];
                $model->piaojuhao= $post['piaojuhao'];
                $model->pay_way= $post['pay_way'];
                $model->company_id=$this->_user['company_id'];
                $model->yujijine= $post['yujijine'];
                $model->yujichengjiao= $post['yujichengjiao'];
                $model->image = $post['image'];
                $model->customer_uuid= $post['customer_uuid'];
                $model->house_uuid= $post['house_uuid'];
                $chengjiaobianhao = Yii::$app->redis->get('chengjiaobianhao');
                if(empty($chengjiaobianhao)){
                    $chengjiaobianhao = 1;
                    Yii::$app->redis->set('chengjiaobianhao',$chengjiaobianhao);
                }else{
                    $chengjiaobianhao = $chengjiaobianhao+1;
                    Yii::$app->redis->set('chengjiaobianhao',$chengjiaobianhao);
                }
                $model->chengjiaobianhao= 'XD-'.date('Ymd').'-'.str_pad($chengjiaobianhao,6,"0",STR_PAD_LEFT);
                $user=$this->_user();
                $model->c_id = $user['u_id'];
                $model->u_id = $user['u_id'];
                $model->ctime = date('Y-m-d H:i:s');
                $model->utime = date('Y-m-d H:i:s');
                $model->auth_cid = $user['auth_cid'];
                $model->auth_rid = $user['auth_rid'];
                $model->auth_sid = $user['auth_sid'];
                $model->auth_aid = $user['auth_aid'];
                $model->auth_baid = $user['auth_baid'];
                $result = $model->save();
                $x_id = Yii::$app->db->getLastInsertID();
                if(!$result){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('保存失败');
                }
                $cus=Customer::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'customer_uuid'=>$post['customer_uuid']])->one();
                $cus->xiading="下定";
                $cusresult = $cus->save();
                if(!$cusresult){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('保存失败');
                }
                //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)
                $Customer_log=new Customer_log();
                $logresult = $Customer_log->log($post['customer_uuid'],6,'添加下定',$this->_user());
                if(!$logresult){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('保存失败');
                }
                //添加财务审核
                $ordersellcostmodel =  new OrderSellCollection();
                $ordersellcostmodel->house_uuid = trim($post['house_uuid']);
                $ordersellcostmodel->xiading_id = $x_id;
                $ordersellcostmodel->xiading_status = 2;
                $ordersellcostmodel->collection_no = trim($post['piaojuhao']);
                $ordersellcostmodel->collection_type = 3;
                $ordersellcostmodel->collection_purpose = '收取意向金';
                $ordersellcostmodel->collection_way = $post['pay_way'];
                $ordersellcostmodel->collection_money = $post['xiadingjine'];
                $ordersellcostmodel->collection_payer = 2;
                $ordersellcostmodel->company_id = $this->_user['company_id'];
                $ordersellcostmodel->collection_day = $post['xiadingriqi'];
                $ordersellcostmodel->collection_image = $post['image'];
                $ordersellcostmodel->collection_remark = $post['beizhu'];
                $ordersellcostmodel->collection_status = 0;
                $ordersellcostmodel->c_id = $this->_user['u_id'];
                $ordersellcostmodel->u_id = $this->_user['u_id'];
                $ordersellcostmodel->ctime = date('Y-m-d H:i:s');
                $ordersellcostmodel->utime = date('Y-m-d H:i:s');
                $ordersellcostmodel->is_del = 0;
                $orderresult = $ordersellcostmodel->save();
                if(!$orderresult){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('保存失败');
                }
                $transaction->commit();
                return ApiReturn::success('保存成功');
            }catch (Exception $e){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
        }
    }
    /*
     * 编辑客源带看
     */
    public function actionEdit(){
        $post=Yii::$app->request->post();
        if(!empty($post)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();//开启事物
            try {
                $model= Customer_xiading::find()->where(['x_id'=>$post['x_id'],'company_id'=>$this->_user['company_id']])->one();
                $model->x_zhuangtai= 0;
                $user=$this->_user();
                $model->u_id = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s');
                $result = $model->save();
                if($result === false){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('保存失败');
                }

                //添加财务审核
                if($post['x_zhuangtai'] == 3){
                    $purpose = '退还意向金';
                    $contractno = '';
                    $remark = '';
                }else if($post['x_zhuangtai'] == 1){
                    $purpose = '支出意向金';
                    $contractno= trim($post['x_xys']);
                    $remark = trim($post['x_zcbz']);
                }
                $ordersellcostmodel =  new OrderSellCollection();
                $ordersellcostmodel->house_uuid = $model->house_uuid;
                $ordersellcostmodel->xiading_id = $post['x_id'];
                $ordersellcostmodel->xiading_status = $post['x_zhuangtai'];
                $ordersellcostmodel->xiading_contractno = $contractno;
                $ordersellcostmodel->collection_no = $model->piaojuhao;
                $ordersellcostmodel->collection_type = 3;
                $ordersellcostmodel->collection_purpose = $purpose;
                $ordersellcostmodel->collection_way = $model->pay_way;
                $ordersellcostmodel->collection_money = $model->xiadingjine;
                $ordersellcostmodel->collection_payer = 2;
                $ordersellcostmodel->company_id = $this->_user['company_id'];
                $ordersellcostmodel->collection_day = $model->xiadingriqi;
                $ordersellcostmodel->collection_image = $model->image;
                $ordersellcostmodel->collection_remark = $remark;
                $ordersellcostmodel->collection_status = 0;
                $ordersellcostmodel->c_id = $this->_user['u_id'];
                $ordersellcostmodel->u_id = $this->_user['u_id'];
                $ordersellcostmodel->ctime = date('Y-m-d H:i:s');
                $ordersellcostmodel->utime = date('Y-m-d H:i:s');
                $ordersellcostmodel->is_del = 0;
                $orderresult = $ordersellcostmodel->save();
                if(!$orderresult){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('保存失败');
                }
                $transaction->commit();
                return ApiReturn::success('保存成功');
            }catch (Exception $e){
                $transaction->rollBack();
                return ApiReturn::wrongParams('更新失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
         }

    }



}