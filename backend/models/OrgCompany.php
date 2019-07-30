<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\OrgCompanyGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrgCompany extends OrgCompanyGii
{
    /**
     * 获取对外合作电话
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyPhone()
    {
        return $this->hasMany(Companycootel::className(),['company_id'=>'company_id'])->where(['is_del'=>0])->select('occ_id,occ_name,occ_tel,company_id');
    }

    /**
     * 添加公司
     * @param  $company 公司数据
     * @param  $user 用户
     */
    public static function addCompany($company,$user = array())
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $companymodel = new OrgCompany();
            $companymodel->company_type = isset($company['company_type']) ? $company['company_type'] : 1;
            $companymodel->company_level = 1;
            $companymodel->company_account = 'genleikeji';
            $companymodel->company_title = trim($company['name']);
			$companymodel->company_short_title = trim($company['short_name']);
            $companymodel->recommend_code = trim($company['recommend_code']);
            $companymodel->logo = isset($company['logo']) ? $company['logo'] : '';
            $companymodel->business_license = isset($company['business_license']) ? $company['business_license'] : '';
            $companymodel->tax_license = isset($company['tax_license']) ? $company['tax_license'] : '';
            $companymodel->certificate_license = isset($company['certificate_license']) ? $company['certificate_license'] : '';
            $companymodel->phone = $company['phone'];
            $companymodel->tel = isset($company['tel']) ? $company['tel'] : '';
            $companymodel->email = isset($company['email']) ? $company['email'] : '';
            $companymodel->province_id = isset($company['province_id']) ? $company['province_id'] : '320000';
            $companymodel->province_title = isset($company['province_title']) ? $company['province_title'] : '江苏省';
            $companymodel->city_id = isset($company['city_id']) ? $company['city_id'] : '320500';
            $companymodel->city_title = isset($company['city_title']) ? $company['city_title'] : '苏州市';
            $companymodel->district_id = isset($company['district_id']) ? $company['district_id'] : '';
            $companymodel->district_title = isset($company['district_title']) ? $company['district_title'] : '';
            $companymodel->address = isset($company['address']) ? $company['address'] : '';
            $companymodel->intro = isset($company['intro']) ? $company['intro'] : '';
            $companymodel->status = isset($company['status']) ? $company['status'] : 1;
            $companymodel->cid = '';
            $companymodel->uid = '';
            $companymodel->ctime = date('Y-m-d H:i:s');
            $companymodel->utime = date('Y-m-d H:i:s');
            $companymodel->is_del = 0;

            if ($companymodel->save() === false) {
                $transaction->rollBack();
                return false;
            }
            $company_id = Yii::$app->db->getLastInsertID();

            //var_dump($company);die;
            //添加部门
            $depart_arr = [['name'=>'总经办','type'=>'5','level'=>'2'],['name'=>'二手房部','type'=>'4','level'=>'3'],['name'=>'门店1','type'=>'2','level'=>'4']];
            $depart_pid = 0;
            $user_depart_pid = 0;
            $store_depart_pid = 0;
            foreach ($depart_arr as $key=>$depart){
                $departmodel = new Depart();
                $departmodel->d_name = $depart['name'];
                $departmodel->d_type = $depart['type'];
                $departmodel->company_id = $company_id;
                $departmodel->d_phone = $company['phone'];
                $departmodel->d_level = $depart['level'];
                $departmodel->d_pid = $depart_pid;
                $departmodel->cid = '';
                $departmodel->uid = '';
                $departmodel->ctime = date('Y-m-d H:i:s');
                $departmodel->utime = date('Y-m-d H:i:s');
                if ($departmodel->save() === false) {
                    $transaction->rollBack();
                    return false;
                }
                $depart_id = Yii::$app->db->getLastInsertID();
                $depart_pid = $depart_id;
                if($key == 0){
                    $user_depart_pid = $depart_id;
                }else if($key == 2){
                    $store_depart_pid = $depart_id;
                }

            }

            //添加公司附表签约信息
            $contractmodel = new OrgCompanyContract();
            $contractmodel->company_id = $company_id;
            $contractmodel->depart_store_id = $store_depart_pid;
            $contractmodel->contract_version = '门店版';
            $contractmodel->contract_money = 0;
            $contractmodel->contract_start = date('Y-m-d');
            $contractmodel->contract_end = date('Y-m-d',strtotime('+1month'));
            $contractmodel->contract_city = isset($company['city_title']) ? $company['city_title'] : '苏州市';
//            $contractmodel->contract_barea = 1;
//            $contractmodel->contract_area = 1;
//            $contractmodel->contract_store = 1;
//            $contractmodel->contract_row = 2;
            $contractmodel->contract_personal = 20;
            $contractmodel->contract_phone = 200;
            $contractmodel->contract_import = 150;
            $contractmodel->cid = '';
            $contractmodel->uid = '';
            $contractmodel->ctime = date('Y-m-d H:i:s');
            $contractmodel->utime = date('Y-m-d H:i:s');
            if ($contractmodel->save() === false) {
                $transaction->rollBack();
                return false;
            }
            $contrac_id = Yii::$app->db->getLastInsertID();

            $company_account = str_pad($company_id+1234, 6, 0, STR_PAD_LEFT);
            $companycontract = static::findOne($company_id);
            $companycontract->company_account = $company_account;
            if ($companycontract->save() === false) {
                $transaction->rollBack();
                return false;
            }


            //角色添加
            $role_arr = [['name'=>'总经理','level'=>'4'],['name'=>'总监','level'=>'3'],['name'=>'店长','level'=>'2'],['name'=>'业务员','level'=>'1']];
            foreach ($role_arr as $key => $role){
                $rolemodel = new Role();
                $rolemodel->role_name = $role['name'];
                $rolemodel->role_type = $role['level'];
                $rolemodel->role_desp = $role['name'];
                $rolemodel->company_id = $company_id;
                $rolemodel->cid = '';
                $rolemodel->uid = '';
                $rolemodel->ctime = date('Y-m-d H:i:s');
                $rolemodel->utime = date('Y-m-d H:i:s');
                $rolemodel->is_del = 0;
                if ($rolemodel->save() === false) {
                    $transaction->rollBack();
                    return false;
                }
                if($key == 0){
                    $role_id = Yii::$app->db->getLastInsertID();
                }
            }

            //用户添加
            $u_salt = rand(1000, 9999);
            $usermodel = new User();
            $usermodel->u_employee_prefix = $companymodel->company_type == 1 ? 'CSES' : 'CSXF';
            $usermodel->u_employee_no = '0001';
            $usermodel->u_employee_id = $usermodel->u_employee_prefix . $usermodel->u_employee_no;
            $usermodel->u_uuid = Tools::create_uuid('GSYG-');
            $usermodel->company_id = $company_id;
            $usermodel->u_name = $company['name'];
            $usermodel->u_phone = $company['phone'];
            $usermodel->u_account = $company['phone'];
            $usermodel->u_salt = (string)$u_salt;
            $usermodel->u_password = md5($company['password'] . $u_salt);
            $usermodel->u_sex = 1;
            $usermodel->u_dept_id = $user_depart_pid;
            $usermodel->u_role_id = $role_id;
            $usermodel->u_entry_time = date('Y-m-d');
            $usermodel->u_status = 1;
            $usermodel->cid = '';
            $usermodel->uid = '';
            $usermodel->utime = date('Y-m-d H:i:s');
            $usermodel->ctime = date('Y-m-d H:i:s');
            $usermodel->auth_baid = $depart_id;
            $usermodel->is_del = 0;
            if ($usermodel->save() === false) {
                $transaction->rollBack();
                return false;
            }
            $u_id = Yii::$app->db->getLastInsertID();

            //用户授权
            $purviews = Purview::find()->where(['is_del' => 0])->andWhere(['<>', 'p_pid', 0])->asArray()->all();
            foreach ($purviews as $kk => $purview) {
                $authmodel = new UserAuth();
                $authmodel->u_id = $u_id;
                $authmodel->p_id = $purview['p_id'];
                $authmodel->company_id = $company_id;
                $authmodel->data_range = 6;
                $authmodel->cid = $u_id;
                $authmodel->ctime = date('Y-m-d H:i:s');
                if ($authmodel->save() === false) {
                    $transaction->rollBack();
                    return false;
                }
            }

            //参数配置
            $settingbase = ZhSettingBase::find()->where(['company_id'=>0])->asArray()->all();			
            if(!empty($settingbase)){
                foreach ($settingbase as $key => $item) {
                    $authmodel = new ZhSettingBase();
                    $authmodel->base_shorthand = $item['base_shorthand'];
                    $authmodel->base_name = $item['base_name'];
                    $authmodel->base_desp = $item['base_desp'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $settingcust = ZhSettingCust::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settingcust)){
                foreach ($settingcust as $key => $item) {
                    $settingcust = new ZhSettingCust();
                    $settingcust->csetting_shorthand = $item['csetting_shorthand'];
                    $settingcust->csetting_type = $item['csetting_type'];
                    $settingcust->csetting_child = $item['csetting_child'];
                    $settingcust->company_id = $company_id;
                    $settingcust->ctime = date('Y-m-d H:i:s');
                    $settingcust->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $settingfinance = ZhSettingFinance::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settingfinance)){
                foreach ($settingfinance as $key => $item) {
                    $authmodel = new ZhSettingFinance();
                    $authmodel->finance_shorthand = $item['finance_shorthand'];
                    $authmodel->finance_name = $item['finance_name'];
                    $authmodel->finance_desp = $item['finance_desp'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            $settinghouse = ZhSettingHouse::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settinghouse)){
                foreach ($settinghouse as $key => $item) {
                    $authmodel = new ZhSettingHouse();
                    $authmodel->hsetting_shorthand = $item['hsetting_shorthand'];
                    $authmodel->hsetting_type = $item['hsetting_type'];
                    $authmodel->hsetting_child = $item['hsetting_child'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $settingjuece = ZhSettingJuece::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settingjuece)){
                foreach ($settingjuece as $key => $item) {
                    $authmodel = new ZhSettingJuece();
                    $authmodel->jsetting_shorthand = $item['jsetting_shorthand'];
                    $authmodel->jsetting_type = $item['jsetting_type'];
                    $authmodel->jsetting_name = $item['jsetting_name'];
                    $authmodel->jsetting_desp = $item['jsetting_desp'];
                    $authmodel->val_type = $item['val_type'];
                    $authmodel->val = $item['val'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $settingqujian = ZhSettingQujian::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settingqujian)){
                foreach ($settingqujian as $key => $item) {
                    $authmodel = new ZhSettingQujian();
                    $authmodel->qujian_shorthand = $item['qujian_shorthand'];
                    $authmodel->qujian_name = $item['qujian_name'];
                    $authmodel->qujian_desp = $item['qujian_desp'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $settingrequired = ZhSettingRequired::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settingrequired)){
                foreach ($settingrequired as $key => $item) {
                    $authmodel = new ZhSettingRequired();
                    $authmodel->rsetting_type = $item['rsetting_type'];
                    $authmodel->rsetting_options = $item['rsetting_options'];
                    $authmodel->rsetting_desp = $item['rsetting_desp'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $settingtransfer= ZhSettingTransfer::find()->where(['company_id'=>0])->asArray()->all();
            if(!empty($settingtransfer)){
                foreach ($settingtransfer as $key => $item) {
                    $authmodel = new ZhSettingTransfer();
                    $authmodel->transfer_name = $item['transfer_name'];
                    $authmodel->transfer_owner_materials = $item['transfer_owner_materials'];
                    $authmodel->transfer_customer_materials = $item['transfer_customer_materials'];
                    $authmodel->transfer_process = $item['transfer_process'];
                    $authmodel->company_id = $company_id;
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $authmodel->utime = date('Y-m-d H:i:s');
                    if ($authmodel->save() === false) { var_dump($authmodel->getErrors());die;
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $transaction->commit();
            return $company_account;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }


    public static function editCompany($company,$user = array())
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if (isset($company['company_id']) && !empty($company['company_id'])) {
                $companymodel = static::findOne($company['company_id']);
                $companymodel->company_type = isset($company['company_type']) ? $company['company_type'] : 1;
                //$companymodel->company_account = isset($company['company_account']) ? trim($company['company_account']) : '123456';
                $companymodel->company_title = trim($company['name']);
                $companymodel->logo = isset($company['logo']) ? $company['logo'] : '';
                $companymodel->business_license = isset($company['business_license']) ? $company['business_license'] : '';
                $companymodel->tax_license = isset($company['tax_license']) ? $company['tax_license'] : '';
                $companymodel->certificate_license = isset($company['certificate_license']) ? $company['certificate_license'] : '';
                $companymodel->phone = $company['phone'];
                $companymodel->tel = isset($company['tel']) ? $company['tel'] : '';
                $companymodel->email = isset($company['email']) ? $company['email'] : '';
                $companymodel->province_id = isset($company['province_id']) ? $company['province_id'] : '';
                $companymodel->province_title = isset($company['province_title']) ? $company['province_title'] : '';
                $companymodel->city_id = isset($company['city_id']) ? $company['city_id'] : '';
                $companymodel->city_title = isset($company['city_title']) ? $company['city_title'] : '';
                $companymodel->district_id = isset($company['district_id']) ? $company['district_id'] : '';
                $companymodel->district_title = isset($company['district_title']) ? $company['district_title'] : '';
                $companymodel->address = isset($company['address']) ? $company['address'] : '';
                $companymodel->intro = isset($company['intro']) ? $company['intro'] : '';
                $companymodel->status = isset($company['status']) ? $company['status'] : '';
                $companymodel->uid = $user['u_id'];
                $companymodel->utime = date('Y-m-d H:i:s');
                $companymodel->is_del = 0;
                if ($companymodel->save() === false) {
                    $transaction->rollBack();
                    return false;
                }
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
