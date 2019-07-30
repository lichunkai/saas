<?php

namespace backend\models;

use common\models\gii\OrgCompanyContractGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrgCompanyContract extends OrgCompanyContractGii
{
    /**
     * 公司签约
     * @param $contract
     * @return bool
     */
    public function addContract($contract)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            //角色添加
            $contractmodel = new OrgCompanyContract();
            $contractmodel->company_id = $contract['company_id'];
            $contractmodel->contract_level = $contract['contract_level'];
            $contractmodel->contract_money = $contract['contract_money'];
            $contractmodel->contract_start = $contract['contract_start'];
            $contractmodel->contract_end = $contract['contract_end'];
            $contractmodel->contract_city = $contract['contract_city'];
            $contractmodel->contract_barea = $contract['contract_barea'];
            $contractmodel->contract_area = $contract['contract_area'];
            $contractmodel->contract_store = $contract['contract_store'];
            $contractmodel->contract_row = $contract['contract_row'];
            $contractmodel->ctime = date('Y-m-d H:i:s');
            $contractmodel->utime = date('Y-m-d H:i:s');
            $contractmodel->is_del = 0;
            if ($contractmodel->save() === false) {
                $transaction->rollBack();
                return false;
            }
            $contract_id = Yii::$app->db->getLastInsertID();
            //更新公司主表的签约信息
            $companymodel = new OrgCompany();
            $companymodel->contract_id = $contract_id;
            $companymodel->contract_start = $contract['contract_money'];
            $companymodel->contract_end = $contract['contract_money'];
            $companymodel->utime = date('Y-m-d H:i:s');
            if ($companymodel->save() === false) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
