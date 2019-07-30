<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\HouseGii;
use common\models\gii\HouseKeyGii;
use common\models\gii\HouseKeyLogGii;
use common\models\gii\HouseLogGii;
use common\models\gii\HouseUserGii;
use common\models\gii\HouseWeituoGii;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class House extends HouseGii
{
    /**
     * 获取图片
     * @return \yii\db\ActiveQuery
     */
    public function getHouseimage()
    {
        return $this->hasMany(HouseImg::className(), ['house_id' => 'house_id'])->where(['between', 'hi_type', 1, 11])->orWhere(['hi_type' => 17]);
    }

    /**
     * 添加房源
     * @param $param array 添加参数
     * @param $user array 用户信息
     * @return bool 添加是否成功
     */
    public function updateHouse($param, $user)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if (isset($param['house_id']) && !empty($param['house_id'])) { //更新
                $model = static::findOne($param['house_id']);
                if ($model) {
                    $house_id = $param['house_id'];
                    $fields = $model->attributeLabels();
                    foreach ($param as $key => $item) {
                        if ($item == 'NULL' || $item == 'undefined' || $item == '******') { //过虑一些不正确的参数
                            continue;
                        }
                        foreach ($fields as $k => $v) {
                            if ($key == $k) {
                                $model->$k = $item;
                            }
                        }
                    }
                    //判断标签
                    if (isset($model->house_tuijian_tag)) {
                        $model->house_tuijian_tag = json_encode($model->house_tuijian_tag, JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($model->house_tag)) {
                        $model->house_tag = json_encode($model->house_tag, JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($model->peitao)) {
                        $model->peitao = json_encode($model->peitao, JSON_UNESCAPED_UNICODE);
                    }
                    if (isset($model->c_id)) {
                        unset($model->c_id);
                    }
                    if (isset($model->ctime)) {
                        unset($model->ctime);
                    }
                    $model->company_id = $user['company_id'];
                    $model->u_id = $user['u_id'];
                    $model->utime = date('Y-m-d H:i:s', time());

                    $result = $model->save();
                    if ($result === false) {
                        $transaction->rollBack();
                        return false;
                    }

                    $transaction->commit();
                    return true;
                } else {
                    return false;
                }
            } else { //添加
                $model = new House();
                $fields = $model->attributeLabels();

                foreach ($param as $key => $item) {
                    if ($item == 'NULL' || $item == 'undefined' || $item == '******') { //过虑一些不正确的参数
                        continue;
                    }
                    foreach ($fields as $k => $v) {
                        if ($key == $k) {
                            $model->$k = $item;
                        }
                    }
                }
                $data[] = [$param['house_uuid'],1,$user['u_id'],$user['u_dept_id'],$user['company_id'],$user['u_id'],$user['u_id'],date('Y-m-d H:i:s', time()),date('Y-m-d H:i:s', time()),0];
                $data[] = [$param['house_uuid'],2,$user['u_id'],$user['u_dept_id'],$user['company_id'],$user['u_id'],$user['u_id'],date('Y-m-d H:i:s', time()),date('Y-m-d H:i:s', time()),0];
                //一般委托书
                if(isset($param['yiban_image']) && !empty($param['yiban_image'])){
                    $data[] = [$param['house_uuid'],5,$user['u_id'],$user['u_dept_id'],$user['company_id'],$user['u_id'],$user['u_id'],date('Y-m-d H:i:s', time()),date('Y-m-d H:i:s', time()),0];
                    $yibanWeituo = new HouseWeituoGii();
                    $yibanWeituo->house_id = $param['house_uuid'];
                    $yibanWeituo->house_sn = $param['house_sn'];
                    $yibanWeituo->weituo_type = 1;
                    $yibanWeituo->weituo_image = $param['yiban_image'];
                    $yibanWeituo->hw_status = 1;
                    $yibanWeituo->company_id = $user['company_id'];
                    $yibanWeituo->hw_d_id = $user['u_dept_id'];
                    $yibanWeituo->hw_u_id = $user['u_id'];
                    $yibanWeituo->hw_sn = empty($param['hw_sn']) ? '' : $param['hw_sn'];
                    $yibanWeituo->c_id = $yibanWeituo->u_id = $user['u_id'];
                    $yibanWeituo->utime = $yibanWeituo->ctime = date('Y-m-d H:i:s', time());
                    $yibanResult = $yibanWeituo->save();
                    if($yibanResult === false){
                        $transaction->rollBack();
                        return false;
                    }
                }
                //独家委托书
                if(isset($param['dujia_image']) && !empty($param['dujia_image'])){
                    $data[] = [$param['house_uuid'],4,$user['u_id'],$user['u_dept_id'],$user['company_id'],$user['u_id'],$user['u_id'],date('Y-m-d H:i:s', time()),date('Y-m-d H:i:s', time()),0];
                    $data[] = [$param['house_uuid'],6,$user['u_id'],$user['u_dept_id'],$user['company_id'],$user['u_id'],$user['u_id'],date('Y-m-d H:i:s', time()),date('Y-m-d H:i:s', time()),0];
                    $houseWeituo = new HouseWeituoGii();
                    $houseWeituo->house_id = $param['house_uuid'];
                    $houseWeituo->house_sn = $param['house_sn'];
                    $houseWeituo->weituo_type = 2;
                    $houseWeituo->weituo_image = $param['dujia_image'];
                    $houseWeituo->hw_status = 1;
                    $houseWeituo->company_id = $user['company_id'];
                    $houseWeituo->hw_d_id = $user['u_dept_id'];
                    $houseWeituo->hw_u_id = $user['u_id'];
                    $houseWeituo->hw_sn = empty($param['hw_sn']) ? '' : $param['hw_sn'];
                    $houseWeituo->hw_start_time = empty($param['weituoqujian']) ? '' : $param['weituoqujian'][0];
                    $houseWeituo->hw_end_time = empty($param['weituoqujian']) ? '' : $param['weituoqujian'][0];
                    $houseWeituo->c_id = $houseWeituo->u_id = $user['u_id'];
                    $houseWeituo->utime = $houseWeituo->ctime = date('Y-m-d H:i:s', time());
                    $weituoResult = $houseWeituo->save();
                    if($weituoResult === false){
                        $transaction->rollBack();
                        return false;
                    }
                    $model->is_dujia = 1;
                    $model->is_dujia_user = $user['u_id'];
                    $model->is_yaoshi = 1;
                    $model->is_yaoshi_user = $user['u_id'];
                }

                //判断标签
                if (isset($model->house_tuijian_tag)) {
                    $model->house_tuijian_tag = json_encode($model->house_tuijian_tag, JSON_UNESCAPED_UNICODE);
                }
                if (isset($model->house_tag)) {
                    $model->house_tag = json_encode($model->house_tag, JSON_UNESCAPED_UNICODE);
                }
                if (isset($model->peitao)) {
                    $model->peitao = json_encode($model->peitao, JSON_UNESCAPED_UNICODE);
                }
                $model->private_company = $user['company_id'];
                $model->private_store = $user['u_dept_id'];
                $model->private_user = $user['u_id'];
                $model->c_id = $user['u_id'];
                $model->u_id = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s', time());
                $model->ctime = date('Y-m-d H:i:s', time());
                $model->quanyuangenjin = date('Y-m-d H:i:s', time());
                $model->weihurengenjin = date('Y-m-d H:i:s', time());
                $model->daikanshijian = date('Y-m-d H:i:s', time());
                $result = $model->save();
                if ($result === false) {
                    $transaction->rollBack();
                    return false;
                }
                $house_id = Yii::$app->db->getLastInsertId();
                //添加录入维护人
                Yii::$app->db->createCommand()->batchInsert(HouseUserGii::tableName(),
                    ['house_id','type','user_id','depart_id','company_id','c_id','u_id','ctime','utime','is_del'],
                    $data
                )->execute();

                self::setLog($house_id, '0', $user['u_name'].'添加了房源', $user);
                $transaction->commit();
                return $house_id;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }


    }

    public static function setStatus($param)
    {

        $data = [
            'house_status' => $param['house_status'],
            'u_id' => $param['u_id'],
            'utime' => $param['utime']
        ];
        if (House::updateAll($data, ['house_uuid' => $param['house_uuid']]) === false) {
            return false;
        }

        $status = "未知";
        switch ($param['house_status']) {
            case '1':
                $status = '有效';
                break;
            case '2':
                $status = '成交';
                break;
            case '3':
                $status = '无效';
                break;
            default:
                $status = '未知';
                break;
        }

        //写日志
        $houseLog = new HouseLogGii();
        $houseLog->c_id = $param['u_id'];
        $houseLog->house_id = $param['house_uuid'];
        $houseLog->u_id = $param['u_id'];
        $houseLog->ctime = $param['utime'];
        $houseLog->utime = $param['utime'];
        $houseLog->hl_type = 2;
        $houseLog->hl_content = '修改房源状态为[' . $status . ']';
        $houseLog->save();
        return true;

    }

    /***
     * 写日志
     * @param $house_id
     * @param $type
     * @param $content
     * @param $user
     * @return bool
     */
    public static function setLog($house_id, $type, $content, $user = array())
    {
        $houseLog = new HouseLogGii();
        $houseLog->c_id = !empty($user['u_id']) ? $user['u_id'] : 0;
        $houseLog->house_id = $house_id;
        $houseLog->company_id = !empty($user['company_id']) ? $user['company_id'] : '';
        $houseLog->u_id = !empty($user['u_id']) ? $user['u_id'] : 0;
        $houseLog->utime = $houseLog->ctime = date('Y-m-d H:i:s', time());
        $houseLog->hl_type = $type;
        $houseLog->hl_content = $content;
        if ($houseLog->save()) {
            return true;
        } else {
            print_r($houseLog->errors);
            return false;
        }
    }


    /***
     * 设置私盘
     * @param $param
     * @return bool
     */
    public static function setPrivate($param)
    {

        if (House::updateAll($param, ['house_uuid' => $param['house_uuid']]) === false) {
            return false;
        }


        //写日志
        $houseLog = new HouseLogGii();
        $houseLog->c_id = $param['u_id'];
        $houseLog->house_id = $param['house_uuid'];
        $houseLog->u_id = $param['u_id'];
        $houseLog->ctime = $param['utime'];
        $houseLog->utime = $param['utime'];
        $houseLog->hl_type = 5;
        $houseLog->hl_content =  '修改房源公私状态变为[共享盘]' ;
        $houseLog->save();
        return true;

    }

    /***
     * 设置封盘
     * @param $param
     * @return bool
     */
    public static function setFengpan($param)
    {

        //修改状态
        $data = [
            'is_fengpan' => $param['is_fengpan'],
            'fengpandaoqi' => $param['fengpandaoqi'],
            'fengpan_image' => $param['fengpan_image'],
            'fengpan_user' => $param['fengpan_user'],
            'u_id' => $param['u_id'],
            'utime' => $param['utime']
        ];
        if (House::updateAll($data, ['house_uuid' => $param['house_uuid']]) === false) {
            return false;
        }


        //写日志
        $houseLog = new HouseLogGii();
        $houseLog->c_id = $param['u_id'];
        $houseLog->house_id = $param['house_uuid'];
        $houseLog->u_id = $param['u_id'];
        $houseLog->ctime = $param['utime'];
        $houseLog->utime = $param['utime'];
        $houseLog->hl_type = 9;
        $houseLog->hl_content = $param['is_fengpan'] == 1 ? '申请[封路径]' : '申请[封电话]';
        $houseLog->save();
        return true;

    }

    /***
     * 通知撤单
     * @param $param
     * @return bool
     */
    public static function setChedan($param)
    {


        $house = House::find()->where(['house_uuid' => $param['house_id']])->one();
        $house->house_status = 0;
        $house->house_level = 'D级';
        $house->save();


        //写日志
        $houseLog = new HouseLogGii();
        $houseLog->c_id = $param['u_id'];
        $houseLog->house_id = $param['house_id'];
        $houseLog->u_id = $param['u_id'];
        $houseLog->ctime = date('Y-m-d H:i:s', time());
        $houseLog->utime = date('Y-m-d H:i:s', time());
        $houseLog->hl_type = 9;
        $houseLog->hl_content = '通知撤单';
        $houseLog->save();
        return true;

    }

    /***
     * 驳回通知撤单
     * @param $param
     * @return bool
     */
    public static function bhChedan($param)
    {

        $house = House::find()->where(['house_uuid' => $param['house_id']])->one();
        $house_tag = count(json_decode($house->house_tag, true));
        $level = 'D级';
        if ($house_tag >= 3) {
            $level = 'A级';
        } else if ($house_tag >= 1 && $house_tag < 3) {
            $level = 'B级';
        } else {
            $level = 'C级';
        }
        if ($param['now_status'] == 3) {
            $param['now_status'] = 1;
        }
        $house->house_status = $param['now_status'];
        $house->house_level = $level;
        $house->save();
        //写日志
        $houseLog = new HouseLogGii();
        $houseLog->c_id = $param['u_id'];
        $houseLog->house_id = $param['house_id'];
        $houseLog->u_id = $param['u_id'];
        $houseLog->ctime = date('Y-m-d H:i:s', time());
        $houseLog->utime = date('Y-m-d H:i:s', time());
        $houseLog->hl_type = 9;
        $houseLog->hl_content = '通知撤单驳回';
        $houseLog->save();
        return true;

    }

    public static function setKeyStatus($param)
    {
        //修改状态
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $housekey = HouseKeyGii::findOne($param['hk_id']);
            $housekey->hk_status = $param['hk_status'];
            $housekey->u_id = $param['u_id'];
            $housekey->utime = $param['utime'];
            $housekey->company_id = $param['company'];
            if ($housekey->update() === false) {
                $transaction->rollBack();
                return false;
            }

            $house = House::find()->where(['house_uuid' => $housekey['house_id']])->one();
            $house->yaoshi_dian = '';
            $house->is_yaoshi = 0;
            $house->is_yaoshi_user = '';
            if ($house->update() === false) {
                $transaction->rollBack();
                return false;
            }

            $hkLog = new HouseKeyLogGii();
            $hkLog->hk_id = $param['hk_id'];
            $hkLog->c_id = $hkLog->u_id = $param['u_id'];
            $hkLog->ctime = $hkLog->utime = date('Y-m-d H:i:s', time());
            $hkLog->hkl_user = $param['u_id'];
            $hkLog->hkl_content = '钥匙失效';
            if (!$hkLog->save()) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function setWeituoStatus($param)
    {
        //修改状态
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $houseweituo = HouseWeituoGii::findOne($param['hw_id']);
            $houseweituo->hw_status = $param['hw_status'];
            $houseweituo->u_id = $param['u_id'];
            $houseweituo->hw_invalid_reason = $param['hw_invalid_reason'];
            $houseweituo->hw_invalid_remark = $param['hw_invalid_remark'];
            $houseweituo->hw_invalid_uname = $param['hw_invalid_uname'];
            $houseweituo->hw_invalid_uid = $param['u_id'];
            $houseweituo->utime = $param['utime'];
            $houseweituo->company_id = $param['company_id'];
            if ($houseweituo->save() === false) {
                $transaction->rollBack();
                return false;
            }

            $house = House::find()->where(['company_id' => $param['company_id'], 'house_uuid' => $houseweituo['house_id']])->one();
            $house->is_dujia = 0;
            $house->is_dujia_user = '';
            if ($house->save() === false) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 私房跳公
     */
    public static function tiaogong()
    {
        $tigongday = Class_house::find()->where(['is_del'=>0,'company_id'=>0,'ch_type'=>0])->asArray()->one();

        $gongxiangpan = House::find()->where([ 'house_private' => 1,'is_del' => 0,])->all();
        if (!empty($gongxiangpan)) {
            foreach ($gongxiangpan as $key => $item) {
                $user['company_id'] = $item->company_id;
                $genjin = strtotime($item->weihurengenjin.'+'.$tigongday['ch_private_genjin'].'days');
                $daikan = strtotime($item->daikanshijian.'+'.$tigongday['ch_private_visit'].'days');
                if($genjin < time() && $daikan < time()){
                    House::setLog((string)$item->house_id, '19', '系统自动跳公至门店公盘', $user);
                    $item->house_private = 2;
                    $item->private_user = 0;
                    $item->quanyuangenjin = date('Y-m-d H:i:s');
                    $item->save();
                }
            }
        }
        $storepan = House::find()->where([ 'house_private' => 2,'is_del' => 0,])->all();
        if (!empty($storepan)) {
            foreach ($storepan as $key => $item) {
                $user['company_id'] = $item->company_id;
                $genjin = strtotime($item->quanyuangenjin.'+'.$tigongday['ch_store_genjin'].'days');
                $daikan = strtotime($item->daikanshijian.'+'.$tigongday['ch_store_visit'].'days');
                if($genjin < time() && $daikan < time()){
                    House::setLog((string)$item->house_id, '19', '系统自动跳公至公司公盘', $user);
                    $item->house_private = 3;
                    $item->private_store = 0;
                    $item->quanyuangenjin = date('Y-m-d H:i:s');
                    $item->save();
                }
            }
        }
        $conpanypan = House::find()->where([ 'house_private' => 3,'is_del' => 0,])->all();
        if (!empty($conpanypan)) {
            foreach ($conpanypan as $key => $item) {
                $user['company_id'] = $item->company_id;
                $genjin = strtotime($item->quanyuangenjin.'+'.$tigongday['ch_company_genjin'].'days');
                $daikan = strtotime($item->daikanshijian.'+'.$tigongday['ch_company_visit'].'days');
                if($genjin < time() && $daikan < time()){
                    House::setLog((string)$item->house_id, '19', '系统自动跳公至公司公盘', $user);
                    $item->house_private = 4;
                    $item->private_company = 0;
                    $item->quanyuangenjin = date('Y-m-d H:i:s');
                    $item->save();
                }
            }
        }
    }

    /*
     * $lb房源类别
     * $dj客源等级
     * $gs公客私客
     * $dksj 带看时间
     * $gjsj 跟进时间
     */
    public static function ctj($lb, $dj, $gs, $company_id, $dksj, $gjsj)
    {
        if ($dj == 'A级') {
            $dj = 'A类房源';
        }
        if ($dj == 'B级') {
            $dj = 'B类房源';
        }
        if ($dj == 'C级') {
            $dj = 'C类房源';
        }
        if ($dj == 'D级') {
            $dj = 'D类房源';
        }
        $list = Class_house::find()->where(['is_del' => 0, 'ch_type' => $lb, 'company_id' => $company_id, 'ch_name' => $dj])->asArray()->one();
        if ($gs == 1) {
            $gjwh = $list['ch_private_creturn'];
            $dakan = $list['ch_private_clook'];
            $gjwh = strtotime("$dksj +$gjwh day");
            $dakan = strtotime("$gjsj +$dakan day");
            if ($gjwh < time() or $dakan < time()) {
                return false;
            } else {
                return true;
            }
        }

    }

}
