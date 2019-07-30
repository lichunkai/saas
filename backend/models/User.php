<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\UserGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class User extends UserGii
{
    /**
     * 获取部门
     * @return \yii\db\ActiveQuery
     */
    public function getDepart()
    {
        return $this->hasOne(Depart::className(),['d_id'=>'u_dept_id'])->select('d_name');
    }

    /*
     * 获取角色
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(),['role_id'=>'u_role_id'])->select('role_name');
    }

    /*
     * 添加用户
     */
    public function  UpdateUser($param,$user)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if(isset($param['u_uuid']) && $param['u_uuid']){
                $model = static::find()->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$user['company_id'],'is_del'=>0])->one();
                $user_id = $model->u_id;
                $old_role_id = $model->u_role_id;
                $model->u_employee_prefix = isset($param['u_employee_prefix'])? $param['u_employee_prefix'] :'' ;
                $model->u_employee_no = isset($param['u_employee_no'])? $param['u_employee_no'] :'' ;
                $model->u_employee_id = isset($param['u_employee_id'])? $param['u_employee_id'] :'' ;
                $model->u_name = isset($param['u_name'])? $param['u_name'] :'' ;
                $model->u_wx = isset($param['u_wx'])? $param['u_wx'] :'' ;
                $model->u_head_url = isset($param['u_head_url'])? $param['u_head_url'] :'' ;
                $model->u_address = isset($param['u_address'])? $param['u_address'] :'' ;
                $model->jibengongzi = isset($param['jibengongzi'])? $param['jibengongzi'] :'' ;
                $model->wuxiangeren = isset($param['wuxiangeren'])? $param['wuxiangeren'] :'' ;
                $model->wuxianyijingeren = isset($param['wuxianyijingeren'])? $param['wuxianyijingeren'] :'' ;
                $model->ticheng_id = isset($param['ticheng_id'])? $param['ticheng_id'] :'' ;
                $model->ticheng = !empty($param['ticheng_id'])?json_encode($param['ticheng'])  :'' ;
                $model->u_phone = isset($param['u_phone'])? $param['u_phone'] :'' ;
                $model->u_account = isset($param['u_phone'])? $param['u_phone'] :'' ;
                $model->u_sex = isset($param['u_sex'])? $param['u_sex'] :'' ;
                $model->u_dept_id = isset($param['u_dept_id'])? $param['u_dept_id'] :'' ;
                $model->u_role_id = isset($param['u_role_id'])? $param['u_role_id'] :'' ;
                $model->u_birthday_time = isset($param['u_birthday_time'])? $param['u_birthday_time'] :'' ;
                $model->u_entry_time = isset($param['u_entry_time'])? $param['u_entry_time'] :'' ;
                $model->u_identity = isset($param['u_identity'])? $param['u_identity'] :'' ;
                $model->u_email = isset($param['u_email'])? $param['u_email'] :'' ;
                $model->u_introducer_id = isset($param['u_introducer_id'])? $param['u_introducer_id'] :'' ;
                $model->u_sort = isset($param['u_sort'])?$param['u_sort']:'0' ;
                $model->u_status = isset($param['u_status'])? $param['u_status'] :1 ;
                $model->company_id = $user['company_id'];
                $model->uid = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s') ;
                $model->auth_uid = isset($param['auth_uid'])? $param['auth_uid'] : 0;
                $model->auth_rid = isset($param['auth_rid'])? $param['auth_rid'] : 0;
                $model->auth_sid = isset($param['auth_sid'])? $param['auth_sid'] : 0;
                $model->auth_aid = isset($param['auth_aid'])? $param['auth_aid'] : 0;
                $model->auth_baid = isset($param['auth_baid'])? $param['auth_baid'] : 0;
                $model->auth_cid = isset($param['auth_cid'])? $param['auth_cid'] : 0;
                $model->is_del = 0;
                $result = $model->save();
                if(!$result){
                    $transaction->rollBack();
                    return false;
                }

                // 添加/修改用户档案
                $dres = isset($param['u_dept_id'])? Depart::findOne($param['u_dept_id']) : false;
                $roleRes = isset($param['u_role_id'])? Role::findOne($param['u_role_id']) : false;
                $hrRes = OaHrStaff::find()->where(['staff_id' => $user_id])->one();
                if($hrRes){
                    $hrStaff=OaHrStaff::findOne($hrRes['hr_id']);
                }else{
                    $hrStaff=new OaHrStaff();
                }
                $hrStaff->staff_id = $model->u_id;
                $hrStaff->staff_name = isset($param['u_name'])? $param['u_name'] :'' ;
                $hrStaff->staff_no = isset($param['u_employee_id'])? $param['u_employee_id'] :'' ;
                $hrStaff->d_id = isset($param['u_dept_id'])? $param['u_dept_id'] :'' ;
                $hrStaff->sex = isset($param['u_sex'])? $param['u_sex'] :'' ;
                $hrStaff->lianxifangshi = isset($param['u_phone'])? $param['u_phone'] :'' ;
                $hrStaff->birthday = isset($param['u_birthday_time'])? $param['u_birthday_time'] :'' ;
                $hrStaff->ruzhishijian = isset($param['u_entry_time'])? $param['u_entry_time'] :'' ;
                $hrStaff->shenfenzheng = isset($param['u_identity'])? $param['u_identity'] :'' ;
                $hrStaff->suoshubumen = $dres ? $dres->d_name : '';
                $hrStaff->zhiwu = $roleRes ? $roleRes->role_name : '';
                $hrStaff->company_id = $user['company_id'];
                $hrStaff->ctime = date('Y-m-d H:i:s') ;
                $hrStaff->is_del = 0;
                if(!$hrStaff->save()){
                    $transaction->rollBack();
                    return false;
                }

                if($param['u_role_id'] != $old_role_id){
                    //更新用户权限
                    $roleauth = RoleAuth::find()->select('p_id,data_range')->where(['r_id'=>$param['u_role_id'],'company_id'=>$user['company_id']])->asArray()->all();
                    $result = UserAuth::deleteAll(['u_id' => $user_id]);
                    if($result === false){
                        $transaction->rollBack();
                        return false;
                    }
                    foreach ($roleauth as $key =>$auth){
                        if($auth !== '0' && $auth !== 'false') {
                            $authmodel = new UserAuth();
                            $authmodel->u_id = $user_id;
                            $authmodel->p_id = $auth['p_id'];
                            $authmodel->data_range = $auth['data_range'];
                            $authmodel->company_id = $user['company_id'];
                            $authmodel->cid = $user['u_id'];
                            $authmodel->ctime = date('Y-m-d H:i:s');
                            $auth_result = $authmodel->save();
                            if (!$auth_result) {
                                $transaction->rollBack();
                                return false;
                            }
                        }
                    }
                }
                $transaction->commit();
                return true;
            }else{
                $u_salt=rand(1000,9999);
                $model =new User();
                $model->u_employee_prefix = isset($param['u_employee_prefix'])? $param['u_employee_prefix'] :'' ;
                $model->u_employee_no = isset($param['u_employee_no'])? $param['u_employee_no'] :'' ;
                $model->u_employee_id = isset($param['u_employee_id'])? $param['u_employee_id'] :'' ;
                $model->u_uuid = Tools::create_uuid('GSYG-');
                $model->u_name = isset($param['u_name'])? $param['u_name'] :'' ;
                $model->jibengongzi = isset($param['jibengongzi'])? $param['jibengongzi'] :'' ;
                $model->wuxiangeren = isset($param['wuxiangeren'])? $param['wuxiangeren'] :'' ;
                $model->wuxianyijingeren = isset($param['wuxianyijingeren'])? $param['wuxianyijingeren'] :'' ;
                $model->ticheng_id = isset($param['ticheng_id'])? $param['ticheng_id'] :'' ;
                $model->ticheng = !empty($param['ticheng_id'])?json_encode($param['ticheng'])  :'' ;
                $model->u_phone = isset($param['u_phone'])? $param['u_phone'] :'' ;
                $model->u_account = isset($param['u_phone'])? $param['u_phone'] :'' ;
                $model->u_salt =(string)$u_salt;
                $model->u_wx = isset($param['u_wx'])? $param['u_wx'] :'' ;
                $model->u_head_url = isset($param['u_head_url'])? $param['u_head_url'] :'' ;
                $model->u_address = isset($param['u_address'])? $param['u_address'] :'' ;
                $model->u_password = md5($param['u_password'].$u_salt);
                $model->u_sex = isset($param['u_sex'])? $param['u_sex'] :'' ;
                $model->u_dept_id = isset($param['u_dept_id'])? $param['u_dept_id'] :'' ;
                $model->u_role_id = isset($param['u_role_id'])? $param['u_role_id'] :'' ;
                $model->u_birthday_time = isset($param['u_birthday_time'])? $param['u_birthday_time'] :'' ;
                $model->u_entry_time = isset($param['u_entry_time'])? $param['u_entry_time'] :'' ;
                $model->u_identity = isset($param['u_identity'])? $param['u_identity'] :'' ;
                $model->u_email = isset($param['u_email'])? $param['u_email'] :'' ;
                $model->u_introducer_id = isset($param['u_introducer_id'])? $param['u_introducer_id'] :'' ;
                $model->u_sort = isset($param['u_sort'])?$param['u_sort']:'0' ;
                $model->u_status = isset($param['u_status'])? $param['u_status'] :1 ;
                $model->company_id = $user['company_id'];
                $model->cid = $user['u_id'];
                $model->uid = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s');
                $model->ctime = date('Y-m-d H:i:s');
                $model->auth_uid = isset($param['auth_uid'])? $param['auth_uid'] : 0;
                $model->auth_rid = isset($param['auth_rid'])? $param['auth_rid'] : 0;
                $model->auth_sid = isset($param['auth_sid'])? $param['auth_sid'] : 0;
                $model->auth_aid = isset($param['auth_aid'])? $param['auth_aid'] : 0;
                $model->auth_baid = isset($param['auth_baid'])? $param['auth_baid'] : 0;
                $model->auth_cid = isset($param['auth_cid'])? $param['auth_cid'] : 0;
                $model->is_del = 0;
                $result = $model->save();
                $u_id = Yii::$app->db->getLastInsertID();

                if(!$result){
                    $transaction->rollBack();
                    return false;
                }

                // 添加用户档案
                $dres = isset($param['u_dept_id'])? Depart::findOne($param['u_dept_id']) : false;
                $roleRes = isset($param['u_role_id'])? Role::findOne($param['u_role_id']) : false;
                $hrStaff=new OaHrStaff();
                $hrStaff->staff_id = $u_id;
                $hrStaff->staff_name = isset($param['u_name'])? $param['u_name'] :'' ;
                $hrStaff->staff_no = isset($param['u_employee_id'])? $param['u_employee_id'] :'' ;
                $hrStaff->d_id = isset($param['u_dept_id'])? $param['u_dept_id'] :'' ;
                $hrStaff->sex = isset($param['u_sex'])? $param['u_sex'] :'' ;
                $hrStaff->lianxifangshi = isset($param['u_phone'])? $param['u_phone'] :'' ;
                $hrStaff->birthday = isset($param['u_birthday_time'])? $param['u_birthday_time'] :'' ;
                $hrStaff->ruzhishijian = isset($param['u_entry_time'])? $param['u_entry_time'] :'' ;
                $hrStaff->shenfenzheng = isset($param['u_identity'])? $param['u_identity'] :'' ;
                $hrStaff->suoshubumen = $dres ? $dres->d_name : '';
                $hrStaff->zhiwu = $roleRes ? $roleRes->role_name : '';
                $hrStaff->company_id = $user['company_id'];
                $hrStaff->ctime = date('Y-m-d H:i:s') ;
                $hrStaff->is_del = 0;
                if(!$hrStaff->save()){
                    $transaction->rollBack();
                    return false;
                }

                $model->auth_uid = $u_id;
                $result = $model->save();
                if(!$result){
                    $transaction->rollBack();
                    return false;
                }

                if($param['u_role_id']){
                    //更新用户权限
                    $roleauth = RoleAuth::find()->select('p_id,data_range')->where(['r_id'=>$param['u_role_id'],'company_id'=>$user['company_id']])->asArray()->all();
                    //var_dump($roleauth);die;
                    foreach ($roleauth as $key =>$auth){
                        if($auth !== '0' && $auth !== 'false') {
                            $authmodel = new UserAuth();
                            $authmodel->u_id = $u_id;
                            $authmodel->p_id = $auth['p_id'];
                            $authmodel->data_range = $auth['data_range'];
                            $authmodel->company_id = $user['company_id'];
                            $authmodel->cid = $user['u_id'];
                            $authmodel->ctime = date('Y-m-d H:i:s');
                            $auth_result = $authmodel->save();
                            if (!$auth_result) {
                                $transaction->rollBack();
                                return false;
                            }
                        }
                    }
                }
                $transaction->commit();
                return true;
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
    /*
         * 设置工资
         */
    public function GongziUser($param,$user)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if(isset($param['u_id']) && $param['u_id']){
                if(!empty($param['ticheng_id']) && $param['ticheng_id']=='undefined'){
                    $param['ticheng_id']='';
                }
                if(!empty($param['fuzerenticheng_id']) && $param['fuzerenticheng_id']=='undefined'){
                    $param['fuzerenticheng_id']='';
                }
                if(!empty($param['fuzerenticheng_zyid']) && $param['fuzerenticheng_zyid']=='undefined'){
                    $param['fuzerenticheng_zyid']='';
                }
                $model = static::findOne($param['u_id']);
                $model->jibengongzi = isset($param['jibengongzi'])? $param['jibengongzi'] :'' ;
                $model->wuxiangeren = isset($param['wuxiangeren'])? $param['wuxiangeren'] :'' ;
                $model->wuxianyijingeren = isset($param['wuxianyijingeren'])? $param['wuxianyijingeren'] :'' ;
                $model->ticheng_id = isset($param['ticheng_id'])? $param['ticheng_id'] :'' ;
                $model->fuzerenticheng_id = isset($param['fuzerenticheng_id'])? $param['fuzerenticheng_id'] :'' ;
                $model->fuzerenticheng = isset($param['fuzerenticheng'])?json_encode($param['fuzerenticheng']):'' ;
                $model->fuzerenticheng_zyid = isset($param['fuzerenticheng_zyid'])? $param['fuzerenticheng_zyid'] :'' ;
                $model->fuzerenticheng_zy = isset($param['fuzerenticheng_zy'])?json_encode($param['fuzerenticheng_zy']):'' ;
                $model->ticheng = !empty($param['ticheng_id'])?json_encode($param['ticheng'])  :'' ;
                $model->uid = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s') ;
                $result = $model->save();
                if(!$result){
                    $transaction->rollBack();
                    return false;
                }
                $transaction->commit();
                return true;
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
    /**
     * 用户信息转移
     * @param $params
     * @param $user
     * @return bool
     */
    public function Transfer($params,$user)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if(isset($params['csfy_uid']) && $params['csfy_uid']){
                $csfy_user = User::findOne($params['csfy_uid']);
                $csfy_houses = House::find()->andWhere(['private_user'=>$params['u_id'],'house_private'=>'1','sale_type'=>'2','is_del'=>'0'])->all();
                foreach ($csfy_houses as $key => $house){
                    $house->private_user = $params['csfy_uid'];
                    $house->u_id = $user['u_id'];
                    $house->utime = date('Y-m-d H:i:s');
                    $house->auth_uid = $csfy_user->auth_uid;
                    $house->auth_rid = $csfy_user->auth_rid;
                    $house->auth_sid = $csfy_user->auth_sid;
                    $house->auth_aid = $csfy_user->auth_aid;
                    $house->auth_baid = $csfy_user->auth_baid;
                    $house->auth_cid = $csfy_user->auth_cid;
                    if($house->update() === false){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            if(isset($params['czfy_uid']) && $params['czfy_uid']){
                $czfy_user = User::findOne($params['czfy_uid']);
                $czfy_houses = House::find()->andWhere(['private_user'=>$params['u_id'],'house_private'=>'1','sale_type'=>'1','is_del'=>'0'])->all();
                foreach ($czfy_houses as $key => $house){
                    $house->private_user = $params['czfy_uid'];
                    $house->u_id = $user['u_id'];
                    $house->utime = date('Y-m-d H:i:s');
                    $house->auth_uid = $czfy_user->auth_uid;
                    $house->auth_rid = $czfy_user->auth_rid;
                    $house->auth_sid = $czfy_user->auth_sid;
                    $house->auth_aid = $czfy_user->auth_aid;
                    $house->auth_baid = $czfy_user->auth_baid;
                    $house->auth_cid = $czfy_user->auth_cid;
                    if($house->update() === false){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            if(isset($params['gdfy_uid']) && $params['gdfy_uid']){
                $gdfy_user = User::findOne($params['gdfy_uid']);
                $gdfy_houses = House::find()->andWhere(['private_user'=>$params['u_id'],'house_private'=>'1','sale_type'=>'3','is_del'=>'0'])->all();
                foreach ($gdfy_houses as $key => $house){
                    $house->private_user = $params['gdfy_uid'];
                    $house->u_id = $user['u_id'];
                    $house->utime = date('Y-m-d H:i:s');
                    $house->auth_uid = $gdfy_user->auth_uid;
                    $house->auth_rid = $gdfy_user->auth_rid;
                    $house->auth_sid = $gdfy_user->auth_sid;
                    $house->auth_aid = $gdfy_user->auth_aid;
                    $house->auth_baid = $gdfy_user->auth_baid;
                    $house->auth_cid = $gdfy_user->auth_cid;
                    if($house->update() === false){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            if(isset($params['csky_uid']) && $params['csky_uid']) {
                $csky_user = User::findOne($params['csky_uid']);
                $csky_customers = Customer::find()->andWhere(['c_id' => $params['u_id'], 'customer_private' => '私客', 'customer_type' => '0', 'is_del' => '0'])->all();
                foreach ($csky_customers as $key => $customer) {
                    $customer->c_id = $params['csky_uid'];
                    $customer->u_id = $user['u_id'];
                    $customer->weihurengenjin = date('Y-m-d H:i:s');
                    $customer->utime = date('Y-m-d H:i:s');
                    $customer->auth_uid = $csky_user->auth_uid;
                    $customer->auth_rid = $csky_user->auth_rid;
                    $customer->auth_sid = $csky_user->auth_sid;
                    $customer->auth_aid = $csky_user->auth_aid;
                    $customer->auth_baid = $csky_user->auth_baid;
                    $customer->auth_cid = $csky_user->auth_cid;
                    if ($customer->update() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            if(isset($params['czky_uid']) && $params['czky_uid']) {
                $czky_user = User::findOne($params['czky_uid']);
                $czky_customers = Customer::find()->andWhere(['c_id' => $params['u_id'], 'customer_private' => '私客', 'customer_type' => '1', 'is_del' => '0'])->all();
                foreach ($czky_customers as $key => $customer) {
                    $customer->c_id = $params['czky_uid'];
                    $customer->u_id = $user['u_id'];
                    $customer->weihurengenjin = date('Y-m-d H:i:s');
                    $customer->utime = date('Y-m-d H:i:s');
                    $customer->auth_uid = $czky_user->auth_uid;
                    $customer->auth_rid = $czky_user->auth_rid;
                    $customer->auth_sid = $czky_user->auth_sid;
                    $customer->auth_aid = $czky_user->auth_aid;
                    $customer->auth_baid = $czky_user->auth_baid;
                    $customer->auth_cid = $czky_user->auth_cid;
                    if ($customer->update() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            if(isset($params['gdky_uid']) && $params['gdky_uid']) {
                $gdky_user = User::findOne($params['gdky_uid']);
                $gdky_customers = Customer::find()->andWhere(['c_id' => $params['u_id'], 'customer_private' => '私客', 'customer_type' => '2', 'is_del' => '0'])->all();
                foreach ($gdky_customers as $key => $customer) {
                    $customer->c_id = $params['gdky_uid'];
                    $customer->u_id = $user['u_id'];
                    $customer->weihurengenjin = date('Y-m-d H:i:s');
                    $customer->utime = date('Y-m-d H:i:s');
                    $customer->auth_uid = $gdky_user->auth_uid;
                    $customer->auth_rid = $gdky_user->auth_rid;
                    $customer->auth_sid = $gdky_user->auth_sid;
                    $customer->auth_aid = $gdky_user->auth_aid;
                    $customer->auth_baid = $gdky_user->auth_baid;
                    $customer->auth_cid = $gdky_user->auth_cid;
                    if ($customer->update() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }


}
