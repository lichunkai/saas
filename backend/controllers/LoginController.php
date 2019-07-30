<?php
namespace backend\controllers;

use backend\models\Button;
use backend\models\OrgCompany;
use backend\models\User;
use backend\models\UserAuth;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\LicenseGii;
use Yii;

/**
 * 登录控制器
 */
class LoginController extends AuthController
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
     * 用户登录
     * @return string
     */
    public function actionLogin()
    {
        $param = Yii::$app->request->post();		
        $result = User::find()->where(['d.company_id'=>$param['cid'],'a.u_account'=>$param['account'],'a.is_del'=>'0','a.u_status'=>1])->alias('a')
	        ->select('a.*,r.role_type,b.d_name,c.area_id,c.area_name,c.dts_id,c.dts_name,e.d_principal_id as auth_ruid,f.d_principal_id as auth_suid,g.d_principal_id as auth_auid,h.d_principal_id as auth_bauid,d.province_id,d.province_title,d.city_id,d.city_title')
            ->leftJoin('zh_depart as b','a.u_dept_id = b.d_id')
            ->leftJoin('org_company as d','a.company_id = d.company_id')
            ->leftJoin('zh_depart as e','a.auth_rid = e.d_id')
            ->leftJoin('zh_depart as f','a.auth_sid = f.d_id')
            ->leftJoin('zh_depart as g','a.auth_aid = g.d_id')
            ->leftJoin('zh_depart as h','a.auth_baid = h.d_id')
            ->leftJoin('com_district as c','b.d_district = c.dts_id and c.is_del=0')
            ->leftJoin('zh_role as r', 'r.role_id = a.u_role_id')
            ->asArray()->one();
        if(!$result){			
            return ApiReturn::wrongParams('公司号、账户或密码错误');
        }
        if(md5($param['password'].$result['u_salt'])!=$result['u_password']){
            return  ApiReturn::wrongParams('公司号、账户或密码错误');
        }
        if($result['dts_name'] == null  || $result['dts_name'] == ''){
            $result['dts_name'] = '---';
        }
        list($result['auths'],$result['menu']) = UserAuth::getUserAuth($result['u_id'],1);//用户权限及菜单
        $result['buttons'] = Button::getButton();		
        $result['tokens'] = md5($result['u_id'].$result['u_name'].$result['u_salt']);
        Yii::$app->redis->set($result['tokens'],json_encode($result));
        unset($result['u_password']);
        unset($result['u_salt']);
        unset($result['u_identity']);
        return ApiReturn::success('登录成功',$result);
    }

    /**
     * 用户登出
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->redis->del($this->_tokens);
        return ApiReturn::needAuth('请先登录');
    }

    /**
     * 检索公司列表
     * @return string
     */
    public function actionCompanylist()
    {

        $result = OrgCompany::find()->select('company_id,company_account,company_title,company_short_title')->orderBy('ctime desc')->asArray()->all();
       // var_dump($result);die;
        return ApiReturn::success('检索成功',$result);
    }

    /**
     * 修改密码
     * @return string
     */
    public function actionEditpwd()
    {
        $param = Yii::$app->request->post();

        $userinfo = User::find()->where(['u_id'=>$this->_user['u_id'],'is_del'=>'0'])->one();

        if(!$userinfo){
            return ApiReturn::wrongParams('用户不存在！');
        }
        if(md5($param['pwd'].$userinfo->u_salt)!=$userinfo->u_password){
            return  ApiReturn::wrongParams('账户密码错误');
        }
        $userinfo->u_password = md5($param['new_pwd'].$userinfo['u_salt']);
        $result = $userinfo->save();
        if($result !== false){
            return ApiReturn::success('修改成功');
        }else{
            return ApiReturn::wrongParams('修改失败');
        }
    }

    /**
     * 解锁屏幕
     * @return \common\models\json
     */
    public function actionUnlocked(){
        $param = Yii::$app->request->post();

        $result = User::find()->where(['u_id'=>$param['uid'],'is_del'=>'0'])->asArray()->one();
        if(!$result){
            return ApiReturn::wrongParams('账户密码不存在');
        }
        if(md5($param['pwd'].$result['u_salt'])!=$result['u_password']){
            return  ApiReturn::wrongParams('账户密码不存在');
        }
        return ApiReturn::success('登录成功');
    }



    public function actionGetlicense(){

	    $param = Yii::$app->request->post();

	    $result = LicenseGii::find()->where(['code'=>$param['code'],'is_del'=>'0','is_pass'=>'1'])->asArray()->one();
	    if(!$result){
		    return  ApiReturn::wrongParams('授权失败');
	    }else{
		    return ApiReturn::success('授权成功');
	    }
    }

    public function actionSetlicense(){
	    $param = Yii::$app->request->post();
	    $result = LicenseGii::find()->where(['code'=>$param['code']])->asArray()->one();
	    if($result){
		    $license = LicenseGii::findOne($result['id']);
		    $license->ip = Tools::get_client_ip();
		    $license->code= $param['code'];
		    $license->utime = date('Y-m-d H:i:s',time());
		    $license->mendian=$param['mendian'];
		    $license->shenqingren=$param['shenqingren'];
		    $license->remake=$param['remake'];
		    $license->is_pass=0;
	    }else{
		    $license = new  LicenseGii();
		    $license->ip = Tools::get_client_ip();
		    $license->code= $param['code'];
		    $license->ctime=$license->utime = date('Y-m-d H:i:s',time());
		    $license->mendian=$param['mendian'];
		    $license->shenqingren=$param['shenqingren'];
		    $license->remake=$param['remake'];
		    $license->is_pass=0;
	    }
		if($license->save()){
			return ApiReturn::success('申请成功，等待审核,管理员通过后重启软件即可。');
		}else{
			return ApiReturn::wrongParams('操作失败');
		}

        $hrStaff->age = isset($param['u_birthday_time'])? Tools::birthday($param['u_birthday_time']) :'' ;


    }
}
