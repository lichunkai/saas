<?php

namespace frontend\modules\saasapi\controllers;

use backend\models\District;
use backend\models\District_slice;
use backend\models\ZhSettingQujian;
use common\controllers\CommonController;
use common\helps\Tools;
use frontend\modules\saasapi\models\ApiReturn;
use frontend\modules\saasapi\controllers\ApiController;
use frontend\modules\saasapi\models\OrgWechatUser;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UserController extends ApiController
{
    public $tokenActions = [];

    /**
     * 授权登录
     */
    public function actionAuthorization()
    {
        $code = Yii::$app->request->post('code');
        $appid = $this->_orgCompany['app_id'];
        $secret = $this->_orgCompany['secret'];
        $url_wx = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $result = Tools::curl_get($url_wx);
        $authdata = json_decode($result,true);
        $wechatuser = OrgWechatUser::find()->where(['openid'=>$authdata['openid']])->asArray()->one();
        if($wechatuser){
            $setting = $this->getsetting();
            return ApiReturn::success('授权成功',['openid'=>$authdata['openid'],'uid'=>$wechatuser['uuid'],'phone'=>$wechatuser['member_phone'],'setting'=>$setting]);
        }else{
            return ApiReturn::noData('用户未注册',['openid'=>$authdata['openid'],'uid'=>'','phone'=>'']);
        }
    }

    /**
     * 注册小程序用户
     */
    public function actionRegister()
    {
        $param = Yii::$app->request->post();

        $wechatuser = OrgWechatUser::find()->where(['openid'=>$param['openid']])->asArray()->one();
        if($wechatuser){
            $setting = $this->getsetting();
            return ApiReturn::success('授权成功',['openid'=>$param['openid'],'uid'=>$wechatuser['uuid'],'phone'=>$wechatuser['member_phone'],'setting'=>$setting]);
        }
        $wechatModel = new OrgWechatUser();
        $uuid = $wechatModel->uuid = Tools::create_uuid('WXYH-');
        $wechatModel->created_at = time();
        if($wechatModel->load($param, '') && $wechatModel->save()){
            //$u_id = Yii::$app->db->getLastInsertID();
            $setting = $this->getsetting();
            return ApiReturn::success('注册成功',['uid'=>$uuid,'phone'=>'','setting'=>$setting]);
        }else{
            $errors = $wechatModel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    public function actionGetsetting()
    {
        $setting = $this->getsetting();
        return ApiReturn::success('获取成功',['setting'=>$setting]);
    }

    //获取配置
    private function getsetting(){
        $data['district'] = CommonController::getDtsList($this->_orgCompany['city_id'],$this->_orgCompany['company_id']);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_orgCompany['company_id'],'qujian_shorthand' => 'mjqj'])->select('qujian_desp')->asArray()->one();
        $data['area'] = json_decode($mjqj['qujian_desp'], true);
        $jgqj = ZhSettingQujian::find()->where(['company_id'=>$this->_orgCompany['company_id'],'qujian_shorthand' => 'jgqj'])->select('qujian_desp')->asArray()->one();
        $data['sell_price'] = json_decode($jgqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_orgCompany['company_id'],'qujian_shorthand' => 'zuling_jigeqj'])->select('qujian_desp')->asArray()->one();
        $data['rent_price'] = json_decode($mjqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_orgCompany['company_id'],'qujian_shorthand' => 'hxqj'])->select('qujian_desp')->asArray()->one();
        $data['huxing'] = json_decode($mjqj['qujian_desp'], true);
        return $data;
    }

    /**
     * 员工认证
     */
    public function actionMemberauth()
    {
        $param = Yii::$app->request->post();
        if(empty(trim($param['name']))){
            return ApiReturn::wrongParams('姓名不能为空');
        }
        if(empty($param['phone']) || !Tools::isValidMobile($param['phone'])){
            return ApiReturn::wrongParams('手机号错误');
        }
        $user = OrgWechatUser::find()->where(['openid'=>$param['openid']])->one();
        $user->member_name = trim($param['name']);
        $user->member_phone = trim($param['phone']);
        $result = $user->save();
        if($result === false){
            return ApiReturn::wrongParams('添加失败');
        }
        return ApiReturn::success('添加成功');
    }

    /**
     * 设置action允许的methods
     * @return array
     */
    public function verbs()
    {
        return [];
    }
}