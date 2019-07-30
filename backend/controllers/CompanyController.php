<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\OrgCompany;
use backend\models\Companycootel;
use backend\models\OrgCompanyContract;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\ComDistrictGii;
use common\models\gii\SmsCodeGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * 公司控制器
 */
class CompanyController extends AuthController
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

    public function actionSendsms()
    {
        $phone = Yii::$app->request->post('phone');
        if (!preg_match("/^1[345789]{1}\d{9}$/", $phone)) {
            return ApiReturn::wrongParams('手机号码错误');
        }
        $code = rand(100000, 999999);
        //判断这个手机号今天发了几次了
        $start = date('Y-m-d') . ' 00:00:00';
        $end = date('Y-m-d') . " 23:59:59";
        $count = SmsCodeGii::find()->where(['msg_mobile'=>$phone,'is_del'=>0])->andWhere(['between','ctime',$start,$end])->count();
        if ($count > 5) {
            return ApiReturn::wrongParams('超过发送次数');
        }

        $content = "您的验证码是：{$code}。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
        $smscodeModel = new SmsCodeGii();
        $smscodeModel->msg_mobile = $phone;
        $smscodeModel->msg_code = (string)$code;
        $smscodeModel->msg_content = $content;
        $smscodeModel->status = 0;
        $smscodeModel->ctime = date('Y-m-d H:i:s');
        $smscodeModel->utime = date('Y-m-d H:i:s');
        $smscodeModel->is_del = 0;
        if($smscodeModel->save()===false){
            return ApiReturn::wrongParams('发送失败');
        }
        $result = Tools::cr6868($phone,$content);
        if($result == 0){
            return ApiReturn::success('发送成功');
        }else{
            return ApiReturn::wrongParams('发送失败');
        }
    }

    /*
     * 公司添加
     * @return array|\common\models\json
     */
    public function actionRegister()
    {
        $post = Yii::$app->request->post();
        if (isset($post['phone']) && !preg_match("/^1[345789]{1}\d{9}$/", $post['phone'])) {
            return ApiReturn::wrongParams('手机号码错误');
        }
        $phonecount = OrgCompany::find()->where(['phone'=>$post['phone'],'is_del'=>0])->count();
        if($phonecount > 0){
            return ApiReturn::wrongParams('手机号已经注册');
        }
        $companynamecount = OrgCompany::find()->where(['company_title'=>trim($post['name']),'is_del'=>0])->count();
        if($companynamecount > 0 ){
            return ApiReturn::wrongParams('公司名称已注册');
        }
		 $companynamecount = OrgCompany::find()->where(['company_short_title'=>trim($post['short_name']),'is_del'=>0])->count();
		if($companynamecount > 0 ){
		    return ApiReturn::wrongParams('公司简称已注册');
		}
        if(empty($post['code'])){
            return ApiReturn::wrongParams('请填写验证码');
        }
        $code = SmsCodeGii::find()->where(['msg_mobile'=>$post['phone'],'status'=>0])->orderBy('ctime desc')->one();		
		if($code==null){
			return ApiReturn::wrongParams('验证码错误');
		}
        if($code->msg_code != $post['code']){
            return ApiReturn::wrongParams('验证码错误');
        }
        $code->status = 1;
        $code->save();

        $result = OrgCompany::addCompany($post);
        if($result){
            $content = "您已成功注册宜居客房产系统，您的公司ID为{$result}。";
            Tools::cr6868($post['phone'],$content);
            return ApiReturn::success('添加成功',['companyid'=>$result,'content'=>$content]);
        }else{
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /*
     * 公司更新
     * @return array|\common\models\json
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();

        $result = OrgCompany::addCompany($post,$this->_user);
        if($result){
            return ApiReturn::success('更新成功');
        }else{
            return ApiReturn::wrongParams('更新失败');
        }
    }

    /*
     * 公司签约
     * @return array|\common\models\json
     */
    public function actionContract()
    {
        $post = Yii::$app->request->post();

        $result = OrgCompanyContract::addContract($post,$this->_user);
        if($result){
            return ApiReturn::success('更新成功');
        }else{
            return ApiReturn::wrongParams('更新失败');
        }
    }
    /*
     * 对外合作电话
     */
    public function actionIndex()
    {
        $data['company']= OrgCompany::find()->with('companyPhone')
            ->where(['company_id'=>$this->_user['company_id'],'is_del'=>0])
            ->asArray()->one();
        $data['company']['contract'] = OrgCompanyContract::find()->alias('a')
            ->select('a.*,b.d_name')
            ->leftJoin('zh_depart b','a.depart_store_id = b.d_id')
            ->where(['a.company_id'=>$this->_user['company_id'],'a.is_del'=>0])->asArray()->all();
        $data['company']['departtype'] = Yii::$app->params['roleType'];
        $data['company']['district'] = ComDistrictGii::find()->select('dts_id,dts_name')->where(['is_del'=>'0'])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.$this->_user['company_id']])->asArray()->all();
        $depart = Depart::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['company']['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
//        var_dump($data['company']);die;
        return ApiReturn::success('获取成功',$data);
    }
    /*
     * 添加对外合作
     */
    public function actionAddtel()
    {
        $param = Yii::$app->request->post();
        if(!empty($param['occ_id'])){
            $model= Companycootel::find()->where(['company_id'=>$this->_user['company_id'],'occ_id'=>$param['occ_id'],'is_del'=>0])->one();
            $model->occ_name=$param['occ_name'];
            $model->occ_tel=$param['occ_tel'];
            $model->company_id=$this->_user['company_id'];
            $model->utime=date('Y-m-d H:i:s');
        }else{
            $model=new Companycootel();
            $model->occ_name=$param['occ_name'];
            $model->occ_tel=$param['occ_tel'];
            $model->company_id=$this->_user['company_id'];
            $model->ctime=date('Y-m-d H:i:s');
            $model->utime=date('Y-m-d H:i:s');
        }
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('操作成功');
        } else {
            return ApiReturn::wrongParams('添加成功');
        }
    }

    /*
     * 删除电话
     */
    public function actionDeltel(){
        $param = Yii::$app->request->post();
        $model= Companycootel::find()->where(['company_id'=>$this->_user['company_id'],'occ_id'=>$param['occ_id'],'is_del'=>0])->one();
        $model->is_del=1;
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('操作成功');
        } else {
            return ApiReturn::wrongParams('添加成功');
        }
    }

    /**
     * 判断是否过期
     * @return array
     */
    public function actionCheckexpire()
    {
        $param = Yii::$app->request->post();
        $today = date('Y-m-d');
        $depart = Depart::find()->select('d_id,d_name,d_type')->where(['d_id'=>$param['sid'],'company_id'=>$param['cid'],'is_del'=>0])->one();
        if($depart && $depart->d_type <= 2){
            $contract = OrgCompanyContract::find()->select('contract_end')->where(['company_id'=>$param['cid'],'depart_store_id'=>$param['sid'],'is_del'=>0])->one();
            //var_dump($contract);die;
            if($contract['contract_end'] >= $today){
                return ApiReturn::success('成功');
            }else{
                return ApiReturn::wrongParams('失败');
            }
        }else if($depart && $depart->d_type > 2){

            $contract = OrgCompanyContract::find()->select('contract_end')->where(['company_id'=>$param['cid'],'is_del'=>0])->andWhere(['>=','contract_end',$today])->one();
            if($contract){
                return ApiReturn::success('成功');
            }else{
                return ApiReturn::wrongParams('失败');
            }
        }else{
            return ApiReturn::needAuth('请重新登录');
        }
        return ApiReturn::wrongParams('添加成功');
    }
}
