<?php
namespace backend\controllers;

use backend\models\Customer_follow;
use backend\models\Customer;
use backend\models\Depart;
use backend\models\User;
use backend\models\Notify;
use backend\models\Customer_log;
use backend\models\ZhSettingBase;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 买卖客源
 */
class Customer_followController extends AuthController
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
     *买卖客源跟进列表
     */
    public function actionIndex(){
        $param = Yii::$app->request->get();
        $page = isset($param["page"])&&$param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page-1)*$pagesize;
        $row=Customer_follow::find()->alias('cf')
            ->select('c.`xuqiubianhao`,c.customer_type,c.genjincishu,cf.*')
            ->leftJoin('zh_customer as c','c.customer_uuid=cf.customer_uuid')
            ->where(['cf.is_del'=>0,'cf.company_id'=>$this->_user['company_id']]);
        if(isset($param['customer_uuid']) && $param['customer_uuid']){
            $row->andWhere(['cf.customer_uuid'=>$param['customer_uuid']]);
        }

        $post = Yii::$app->request->post();
        if(isset($post['customer_uuid']) && $post['customer_uuid']){
            $row->andWhere(['cf.customer_uuid'=>$post['customer_uuid']]);
        }
        if (isset($post["c_type"]) && $post['c_type'] != '') { //客源类型
            $row->andWhere(['c.customer_type' => trim( $post['c_type'])]);
        }
        if (isset($post["genjinfangshi"]) && $post["genjinfangshi"]) { //跟单方式
            $row->andWhere(['cf.genjinfangshi' => trim( $post['genjinfangshi'])]);
        }
        if (isset($post["keywd"]) && $post["keywd"]) { //编号/小区/内容
            $row->andWhere("c.xuqiubianhao like '%" . $post["keywd"]."%'".
                " or cf.genjinfangshi like '%" . $post["keywd"]."%'");
        }
        if (isset($post['dateRange']) && $post['dateRange']){
            $daterange = $post['dateRange'];
            if($daterange[1] != 'undefined'){
                $row->andFilterWhere(['between','cf.ctime',$daterange[0].' 00:00:01', $daterange[1].' 23:59:59']);
            }
        }
        if(isset($post['u_id']) && $post['u_id']){
            $row->andWhere([ 'cf.c_id' => $post['u_id']]);
        }else{
            if(isset($post['departpath'])&&is_array($post['departpath'])){ //判断部门
                $users = $this->_getUsersByDepartId(end($post['departpath']));
                $row->andWhere([ 'in', 'cf.c_id',$users]);
            }
        }

        $list=$row->orderBy('cf.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
//        echo $row->createCommand()->getRawSql();die;
        foreach($list as $key=>$v){

            if($v['bumen_id']){
                $depart= Depart::find()->where(['d_id'=>$v['bumen_id'],'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
                $list[$key]['bumen']=$depart['d_name'];
            }
            if($v['c_id']){
                $user= User::find()->where(['u_id'=>$v['c_id'],'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
                $list[$key]['genjinren']=$user['u_name'];
            }
        }

        $data['list'] = $list;
        $data['count'] = $row->count();
//        //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）10（查看跟进列表）
//        $Customer_log=new Customer_log();
//        $Customer_log->log($param['customer_uuid'],10,'查看跟进列表',$this->_user());
        return ApiReturn::success('查询成功',$data);
    }
    /*
   * 添加客源跟进
   */
    public function actionAdd(){
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        $post=Yii::$app->request->post();
        $param=$post;
        if(!empty($post)){
            $model=new Customer_follow;
            $model->genjinfangshi= $post['genjinfangshi'];
            $model->genjinneirong= $post['genjinneirong'];
            $model->tixing_uid= $post['tixing_uid'];		
            //$model->bumen_id=  !empty($post['bumen_id'][count($post['bumen_id'])-1]['value'])?$post['bumen_id'][count($post['bumen_id'])-1]['value']:$post['bumen_id'][count($post['bumen_id'])-1];
            $model->customer_uuid= $post['customer_uuid'];
            $model->tixing_time= $post['tixing_time'];
            $model->tixingneirong= $post['tixingneirong'];
            $user=$this->_user();
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->auth_cid = $user['auth_cid'];
            $model->auth_rid = $user['auth_rid'];
            $model->company_id =$this->_user['company_id'];
            $model->auth_sid = $user['auth_sid'];
            $model->auth_aid = $user['auth_aid'];
            $model->auth_baid = $user['auth_baid'];
            $result = $model->save();

            //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）
            $Customer_log=new Customer_log();
            $Customer_log->log($post['customer_uuid'],9,'添加跟进',$this->_user());
            if($result){
                $cus=Customer::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'customer_uuid'=>$post['customer_uuid']])->one();
                if($cus->customer_private=="私客"){
                    $cus->weihurengenjin=date('Y-m-d H:i:s');
                }
                if($cus->customer_private=="公客"){
                    $cus->quanyuangenjin=date('Y-m-d H:i:s');
                }
                $cus->genjincishu=$cus->genjincishu+1;
                $cus->u_id=$user['u_id'];
                $cus->save();
                if(!empty($param['tixing_uid'])){
                    $notify = new Notify();
                    $notify->n_title = '客源提醒通知';
                    $notify->n_content = $param['tixingneirong'];
                    $notify->n_u_id = $param['tixing_uid'];
                    $notify->n_time = $param['tixing_time'];
                    $notify->n_is_read = 0;
                    $notify->n_is_notify = 0;
                    $notify->n_url = '/#/customerEtails/'.$param['customer_uuid'];
                    $notify->c_id = $user['u_id'];
                    $notify->u_id = $user['u_id'];
                    $notify->utime = date('Y-m-d H:i:s',time());
                    $notify->ctime = date('Y-m-d H:i:s',time());
                    $notify->auth_rid = $user['auth_rid'];
                    $notify->company_id =$this->_user['company_id'];
                    $notify->auth_sid = $user['auth_sid'];
                    $notify->auth_aid = $user['auth_aid'];
                    $notify->auth_baid = $user['auth_baid'];
                    if($notify->save()){
                        $transaction->commit();
                        return ApiReturn::success('写跟进成功');
                    }else{
                        $transaction->rollBack();
                        var_dump($notify->getErrors());
                        return ApiReturn::wrongParams('写跟进失败');
                    }
                }else{
                    $transaction->commit();
                    return ApiReturn::success('写跟进成功');
                }

            }else{
                return ApiReturn::wrongParams('写跟进失败');
            }
        }else{
            return ApiReturn::wrongParams('写跟进失败');
        }
    }

    /**
     * 获取配置,跟进方式
     */
    public function actionGetsetting(){

        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_genjinfangshi','company_id'=>$this->_user['company_id']]);
        $data['genjinfangshi'] = $row->asArray()->all();
        return ApiReturn::success('查询成功', $data);
    }

    /*
     * 编辑客源跟进
     */
    public function actionEdit(){
        $post=Yii::$app->request->post();
        if(!empty($post)){
            $model= Customer_follow::find()->andWhere(['f_id'=>$post['f_id'],'company_id'=>$this->_user['company_id']])->one();
            $model->genjinfangshi= $post['genjinfangshi'];
            $model->genjinneirong= $post['genjinneirong'];
            $model->tixing_uid= $post['tixing_uid'];
            $model->bumen_id= $post['bumen_id'];
            $model->tixing_time= $post['tixing_time'];
            $model->company_id= $this->_user['company_id'];
            $model->tixingneirong= $post['tixingneirong'];
            $user=$this->_user();
            $model->c_id = $user['u_id'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return ApiReturn::success('保存成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
         }

    }
    /*
 * 删除
 */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(empty($post['f_id'])){
            return ApiReturn::wrongParams('数据异常');
        }
        $customer_follow =Customer_follow::find()->andWhere(['f_id'=>$post['f_id'],'company_id'=>$this->_user['company_id']])->one();
        $customer_follow->is_del = 1;
        $result = $customer_follow->update();

        if($result){
            return ApiReturn::success('删除成功');
        }else{
            return ApiReturn::wrongParams('删除失败');
        }
    }


    private function _getUsersByDeparts($departs){
        $users =[];
        $result = User::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->andWhere(['in','u_dept_id',$departs])->asArray()->all();
        foreach ($result as $item){
            $users[] = $item['u_id'];
        }
        return $users;
    }

    private function _getUsersByDepartId($d_id){
        $arr=[];
        $departs = $this->_getChildNode($d_id,$arr);
        return $this->_getUsersByDeparts($departs);
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
        $ret = Depart::find()->where(['d_pid' => $id,'company_id'=>$this->_user['company_id']])->select('d_id')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node['d_id'];
                $this->_getChildNode($node['d_id'], $arr);
            }
        }
        return array_unique($arr);
    }
}