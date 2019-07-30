<?php

namespace backend\models;

use common\models\gii\OrderSellGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrderSell extends OrderSellGii
{
    /**
     * 获取添加人
     * @return \yii\db\ActiveQuery
     */
    public function getCreated()
    {
        return $this->hasOne(User::className(),['u_id'=>'c_id'])->select('d_name');
    }

    /*
     * 获取更新人
     */
    public function getUpdated()
    {
        return $this->hasOne(User::className(),['u_id'=>'u_id'])->select('role_name');
    }

    /**
     * 获取房源信息
     * @return \yii\db\ActiveQuery
     */
    public function getHouseinfo()
    {
        return $this->hasOne(House::className(),['house_sn'=>'owner_sn']);
    }

    /*
     * 获取客源信息
     */
    public function getCustomerinfo()
    {
        return $this->hasOne(Customer::className(),['xuqiubianhao'=>'customer_sn']);
    }

    /*
     * 获取电话信息
     */
    public function getPhone()
    {
        return $this->hasMany(OrderSellTel::className(),['order_id'=>'order_id']);
    }

    /*
     * 获取佣金收取记录
     */
    public function getCost()
    {
        return $this->hasMany(OrderSellCost::className(),['order_id'=>'order_id']);
    }

    /*
     * 获取代收代付记录
     */
    public function getCollection()
    {
        return $this->hasMany(OrderSellCollection::className(),['order_id'=>'order_id']);
    }

    /*
     * 获取用户流程记录
     */
    public function getProcedure()
    {
        return $this->hasMany(OrderSellProcedure::className(),['order_id'=>'order_id']);
    }

}
