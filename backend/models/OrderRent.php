<?php

namespace backend\models;

use common\models\gii\OrderRentGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrderRent extends OrderRentGii
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
        return $this->hasOne(House::className(),['house_sn'=>'house_sn']);
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

}
