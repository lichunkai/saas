<?php

namespace backend\models;

use common\models\gii\OrderSellLmgGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrderSellLmg extends OrderSellLmgGii
{
    /**
     * 添加房源图片
     * @param $param array 添加参数
     * @param $user array 用户信息
     * @return bool 添加是否成功
     */
    public function updateDealImg($param, $user)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        foreach ($param['images'] as $img) {
            if (is_array($img)) {
                $model = new OrderSellLmg();
                //删除对应的图片
                if ($model->deleteAll(['order_id' => $param['order_id'], 'oi_type' => $img['oi_type']])===false) {
                    $transaction->rollBack();
                    return false;
                }

                $model->order_id = $param['order_id'];
                $model->oi_url = $img['oi_url'];
                $model->oi_status = 0;
                $model->oi_type = $img['oi_type'];
                $model->company_id = $user['company_id'];
                $model->c_id = $user['u_id'];
                $model->u_id = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s', time());
                $model->ctime = date('Y-m-d H:i:s', time());
                if (!$model->save()) {
                    $transaction->rollBack();
                    var_dump($model->getErrors());
                    return false;
                }
            }

        }

        $transaction->commit();

        return true;

    }

    public function delHouseImg($hi_id, $user)
    {
        try {
            $model = static::findOne($hi_id);
            $model->u_id = $user['u_id'];
            $model->is_del = 1;
            $model->utime = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                return true;
            } else {
                var_dump($model->getErrors());
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

    }
}
