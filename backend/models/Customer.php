<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\CommissionGii;
use common\models\gii\Customer_telGii;
use common\models\gii\CustomerGii;
use common\models\gii\Customer_logGii;
use backend\models\Customer_log;
use backend\models\Customer_tel;
use backend\models\District_slice;
use common\models\gii\HouseGii;
use common\models\gii\ComDistrictGii;
use common\models\gii\ComVillageGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use backend\models\District_region;

class Customer extends CustomerGii
{
    private static $user;
    private static $_tokens;
    public function init(){
        parent::init();
        $headeToken =  Yii::$app->request->headers->get('X-Access-Token');
        $getToken =  Yii::$app->request->get('token');
        $postToken =  Yii::$app->request->get('token');
        if($headeToken&&!empty($headeToken)){
            self::$_tokens=$headeToken;
        }elseif($getToken&&!empty($getToken)){
            self::$_tokens=$getToken;
        }elseif($postToken&&!empty($postToken)){
            self::$_tokens=$postToken;
        }
        self::$user = json_decode(Yii::$app->redis->get(self::$_tokens),'true');
    }
    //获取电话
    public function getTel()
    {
        return $this->hasMany(Customer_tel::className(), ['customer_uuid' => 'customer_uuid']);
    }

    //判断本人本组
    public static function getBumen($p_id, $user)
    {
        foreach ($user['auths'] as $item) {
            if ($item['p_id'] == $p_id) {
                switch ($item['data_range']) {
                    case 1:
                        return $user['auth_uid'];
                        break;
                    case 2:
                        return $user['auth_rid'];
                        break;
                    case 3:
                        return $user['auth_sid'];
                        break;
                    case 4:
                        return $user['auth_aid'];
                        break;
                    case 5:
                        return $user['auth_baid'];
                        break;
                    case 6:
                        return 'sys';
                        break;
                    default:
                        return false;
                }
            }
        }
        return false;
    }

    //判断本人本组
    public static function getBumen_dq($p_id, $user)
    {
        foreach ($user['auths'] as $item) {
            if ($item['p_id'] == $p_id) {
                switch ($item['data_range']) {
                    case 1:
                        return 1;
                        break;
                    case 2:
                        return 2;
                        break;
                    case 3:
                        return 3;
                        break;
                    case 4:
                        return 4;
                        break;
                    case 5:
                        return 5;
                        break;
                    case 6:
                        return 'sys';
                        break;
                    default:
                        return false;
                }
            }
        }
        return false;
    }



    //数组 树化
    public static function getTree($data, $pId)
    {
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v['d_pid'] == $pId) {         //父亲找到儿子

                $v['children'] = self::getTree($data, $v['value']);
                $tree[] = $v;
                //unset($data[$k]);
            }
        }
        return $tree;
    }

    //把树 变成一维数组
    public static function setlist($arr, $parentid = 0)
    {
        $array = array();
        if (!empty($arr)) {
            foreach ($arr as $val) {
                $indata = array("value" => $val["value"], "d_pid" => $parentid);
                $array[] = $indata;
                if (isset($val["children"])) {
                    $children =self::setlist($val["children"], $val["d_pid"]);
                    if ($children) {
                        $array = array_merge($array, $children);
                    }
                }
            }
        }


        return $array;
    }

    //把树 变成一维数组带名字
    public static function setlistname($arr, $parentid = 0)
    {
        $array = array();
        if (!empty($arr)) {
            foreach ($arr as $val) {
                $indata = array("value" => $val["value"], "label" => $val["label"], "d_pid" => $val["d_pid"]);
                $array[] = $indata;
                if (isset($val["children"])) {
                    $children = self::setlistname($val["children"], $val["d_pid"]);
                    if ($children) {
                        $array = array_merge($array, $children);
                    }
                }
            }
        }


        return $array;
    }
    /**
     * 添加项目
     */
    public function updateCustomer($param, $user)
    {
		
        if (isset($param['customer_uuid']) && !empty($param['customer_uuid'])) {
            $model = static::find()->where(['customer_uuid'=>$param['customer_uuid'],'company_id'=>$user['company_id'],'is_del'=>0])->one();
            if (!empty($param['xuqiuquyu'][0])) {
                $dts = ComDistrictGii::find()->where(['is_del'=>0,'area_id'=>$param['xuqiuquyu'][0]])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.self::$user['company_id']])
                    ->asArray()->one();
                $model->dts_name = $dts['area_name'];
                $model->dts_id = $param['xuqiuquyu'][0];

            }
            if (!empty($param['xuqiuquyu'][1])) {
                $rn = ComDistrictGii::find()->where(['is_del'=>0,'dts_id'=>$param['xuqiuquyu'][1]])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.self::$user['company_id']])->asArray()->one();;
                $model->rn_name= $rn['dts_name'];
                $model->rn_id =$param['xuqiuquyu'][1];
            } else {
                $model->rn_id = null;
                $model->rn_name = null;
            }
            if(!empty($param['village'])){
                $va=ComVillageGii::find()->where(['is_del'=>0,'village_id'=>$param['village']])->asArray()->one();
                $model->village = trim($param['village']);
                $model->village_name = $va['village_name'];
            }


            $model->yongtu = trim($param['yongtu']);
            if ($param['xuqiuhuxing_min'] <> 'undefined') {
                $model->xuqiuhuxing_min = trim($param['xuqiuhuxing_min']);
            }
            if ($param['xuqiuhuxing_max'] <> 'undefined') {
                $model->xuqiuhuxing_max = trim($param['xuqiuhuxing_max']);
            }
            if ($param['xuqiujiage_min'] <> 'undefined') {
                $model->xuqiujiage_min = trim($param['xuqiujiage_min']);
            }
            if ($param['xuqiujiage_max'] <> 'undefined') {
                $model->xuqiujiage_max = trim($param['xuqiujiage_max']);
            }
            if ($param['xuqiumianji_min'] <> 'undefined') {
                $model->xuqiumianji_min = trim($param['xuqiumianji_min']);
            }
            if ($param['xuqiumianji_max'] <> 'undefined') {
                $model->xuqiumianji_max = trim($param['xuqiumianji_max']);
            }
            if ($param['xuqiulouceng_min'] <> 'undefined') {
                $model->xuqiulouceng_min = trim($param['xuqiulouceng_min']);
            }
            if ($param['xuqiulouceng_max'] <> 'undefined') {
                $model->xuqiulouceng_max = trim($param['xuqiulouceng_max']);
            }
            if ($param['xuqiufangling_min'] <> 'undefined') {
                $model->xuqiufangling_min = trim($param['xuqiufangling_min']);
            }
            if ($param['xuqiufangling_max'] <> 'undefined') {
                $model->xuqiufangling_max = trim($param['xuqiufangling_max']);
            }
            $model->chaoxiang = trim($param['chaoxiang']);
            $model->customer_name = trim($param['customer_name']);
			$model->kehudengji = trim($param['kehudengji']);
            $model->zhuangxiu = trim($param['zhuangxiu']);
            $peitao = '';
            if (!empty($param['peitao'])) {
                foreach ($param['peitao'] as $v) {
                    if ($v) {
                        $peitao .= $v . ';';
                    }
                }
            }
            $model->peitao = $peitao;
            $model->xuqiuyuanying = trim($param['xuqiuyuanying']);
            $model->fangwuleixing = trim($param['fangwuleixing']);
            $model->goutongjieduan = trim($param['goutongjieduan']);
            $model->kehulaiyuan = trim($param['kehulaiyuan']);
            $model->company_id = self::$user['company_id'];
            $xiaofeilinian = "";
            if (!empty($param['xiaofeilinian'])) {
                foreach ($param['xiaofeilinian'] as $v) {
                    if ($v) {
                        $xiaofeilinian .= $v . ';';
                    }
                }
            }
            $tiaojianbiaoqian = "";
            if (!empty($param['tiaojianbiaoqian'])) {
                foreach ($param['tiaojianbiaoqian'] as $v) {
                    if ($v) {
                        $tiaojianbiaoqian .= $v . ';';
                    }
                }
            }
            $model->tiaojianbiaoqian = $tiaojianbiaoqian;
            $duoxuanbiaoqian = "";
            if (!empty($param['duoxuanbiaoqian'])) {
                foreach ($param['duoxuanbiaoqian'] as $v) {
                    if ($v) {
                        $duoxuanbiaoqian .= $v . ';';
                    }

                }
            }
            $model->duoxuanbiaoqian = $duoxuanbiaoqian;
            $model->xiaofeilinian = $xiaofeilinian;
            $model->guoji = trim($param['guoji']);
            $model->minzu = trim($param['minzu']);
            $model->zhengjianhaoma = trim($param['zhengjianhaoma']);
            $model->email = trim($param['email']);
            $model->qq = trim($param['qq']);
            $model->weixin = trim($param['weixin']);
            $model->jiaotonggongju = trim($param['jiaotonggongju']);
            $model->chexing = trim($param['chexing']);
            $model->mark = trim($param['mark']);
            $model->core_mark = trim($param['core_mark']);
			$model->customer_sex = trim($param['customer_sex']);
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->utime = date('Y-m-d H:i:s');
            $model->u_dept_id = $user['u_dept_id'];
            $model->is_del = 0;
            $result = $model->save();
            //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息)
            //条件等级
            Customer::dengjipanding($param['customer_uuid']);
            $Customer_log = new Customer_log();
            $Customer_log->log($param['customer_uuid'], 3, '修改客源信息', $user);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            $model = new Customer();
            $model->customer_private = trim($param['customer_private']);
            $model->customer_name = trim($param['customer_name']);
            $model->customer_type = !empty($param['customer_type']) ? $param['customer_type'] : 0;
            $model->customer_sex = trim($param['customer_sex']);
			$model->kehudengji = trim($param['kehudengji']);
            $customer_sn = Yii::$app->redis->get('customer_sn');
            if (empty($customer_sn)) {
                $customer_sn = 1;
                Yii::$app->redis->set('customer_sn', $customer_sn);
            } else {
                $customer_sn = $customer_sn + 1;
                Yii::$app->redis->set('customer_sn', $customer_sn);
            }

            if ($model->customer_type == 0) {
                $model->xuqiubianhao = 'CSKY-' . date('ymd') . '-' . str_pad($customer_sn, 4, "0", STR_PAD_LEFT);
                $model->customer_uuid = Tools::create_uuid('CSKY-');
            } elseif ($model->customer_type == 1) {
                $model->xuqiubianhao = 'CZKY-' . date('ymd') . '-' . str_pad($customer_sn, 4, "0", STR_PAD_LEFT);
                $model->customer_uuid = Tools::create_uuid('CZKY-');
            } elseif ($model->customer_type == 2) {
                $model->xuqiubianhao = 'GDKY-' . date('ymd') . '-' . str_pad($customer_sn, 4, "0", STR_PAD_LEFT);
                $model->customer_uuid = Tools::create_uuid('GDKY-');
            }


            if (!empty($param['xuqiuquyu'][0])) {
                $dts = ComDistrictGii::find()->where(['is_del'=>0,'area_id'=>$param['xuqiuquyu'][0]])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.self::$user['company_id']])
                    ->asArray()->one();
                $model->dts_name = $dts['area_name'];
                $model->dts_id = $param['xuqiuquyu'][0];

            }
            if (!empty($param['xuqiuquyu'][1])) {
                $rn = ComDistrictGii::find()->where(['is_del'=>0,'dts_id'=>$param['xuqiuquyu'][1]])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.self::$user['company_id']])->asArray()->one();;
                $model->rn_name = $rn['dts_name'];
                $model->rn_id =$param['xuqiuquyu'][1];
            }
            if(!empty($param['village'])){
                $va=ComVillageGii::find()->where(['is_del'=>0,'village_id'=>$param['village']])->asArray()->one();
                $model->village = trim($param['village']);
                $model->village_name = $va['village_name'];
            }

            $model->yongtu = trim($param['yongtu']);
            if ($param['xuqiuhuxing_min'] <> 'undefined') {
                $model->xuqiuhuxing_min = trim($param['xuqiuhuxing_min']);
            }
            if ($param['xuqiuhuxing_max'] <> 'undefined') {
                $model->xuqiuhuxing_max = trim($param['xuqiuhuxing_max']);
            }
            if ($param['xuqiujiage_min'] <> 'undefined') {
                $model->xuqiujiage_min = trim($param['xuqiujiage_min']);
            }
            if ($param['xuqiujiage_max'] <> 'undefined') {
                $model->xuqiujiage_max = trim($param['xuqiujiage_max']);
            }
            if ($param['xuqiumianji_min'] <> 'undefined') {
                $model->xuqiumianji_min = trim($param['xuqiumianji_min']);
            }
            if ($param['xuqiumianji_max'] <> 'undefined') {
                $model->xuqiumianji_max = trim($param['xuqiumianji_max']);
            }
            if ($param['xuqiulouceng_min'] <> 'undefined') {
                $model->xuqiulouceng_min = trim($param['xuqiulouceng_min']);
            }
            if ($param['xuqiulouceng_max'] <> 'undefined') {
                $model->xuqiulouceng_max = trim($param['xuqiulouceng_max']);
            }
            if ($param['xuqiufangling_min'] <> 'undefined') {
                $model->xuqiufangling_min = trim($param['xuqiufangling_min']);
            }
            if ($param['xuqiufangling_max'] <> 'undefined') {
                $model->xuqiufangling_max = trim($param['xuqiufangling_max']);
            }
            $model->chaoxiang = trim($param['chaoxiang']);
            $model->zhuangxiu = trim($param['zhuangxiu']);
            $model->zhuangtai = trim($param['zhuangtai']);
            $peitao = '';
            if (!empty($param['peitao'])) {
                foreach ($param['peitao'] as $v) {
                    if ($v) {
                        $peitao .= $v . ';';
                    }
                }
            }
            $model->peitao = $peitao;
            $model->xuqiuyuanying = trim($param['xuqiuyuanying']);
            $model->fangwuleixing = trim($param['fangwuleixing']);
            $model->goutongjieduan = trim($param['goutongjieduan']);
            $model->kehulaiyuan = trim($param['kehulaiyuan']);
            $model->company_id = self::$user['company_id'];
            $xiaofeilinian = "";
            if (!empty($param['xiaofeilinian'])) {
                foreach ($param['xiaofeilinian'] as $v) {
                    if ($v) {
                        $xiaofeilinian .= $v . ';';
                    }
                }
            }
            $tiaojianbiaoqian = "";
            if (!empty($param['tiaojianbiaoqian'])) {
                foreach ($param['tiaojianbiaoqian'] as $v) {
                    if ($v) {
                        $tiaojianbiaoqian .= $v . ';';
                    }
                }
            }
            $model->tiaojianbiaoqian = $tiaojianbiaoqian;
            $duoxuanbiaoqian = "";
            if (!empty($param['duoxuanbiaoqian'])) {
                foreach ($param['duoxuanbiaoqian'] as $v) {
                    if ($v) {
                        $duoxuanbiaoqian .= $v . ';';
                    }
                }
            }
            $model->duoxuanbiaoqian = $duoxuanbiaoqian;
            $model->xiaofeilinian = $xiaofeilinian;
            $model->guoji = trim($param['guoji']);
            $model->minzu = trim($param['minzu']);
            $model->zhengjianhaoma = trim($param['zhengjianhaoma']);
            $model->email = trim($param['email']);
            $model->qq = trim($param['qq']);
            $model->weixin = trim($param['weixin']);
            $model->jiaotonggongju = trim($param['jiaotonggongju']);
            $model->chexing = trim($param['chexing']);
            $model->mark = trim($param['mark']);
            $model->core_mark = trim($param['core_mark']);
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->u_dept_id = $user['u_dept_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->weihurengenjin = date('Y-m-d H:i:s');
            $model->daikanshijian = date('Y-m-d H:i:s');
            $model->auth_cid = $user['auth_cid'];
            $model->auth_rid = $user['auth_rid'];
            $model->auth_sid = $user['auth_sid'];
            $model->auth_aid = $user['auth_aid'];
            $model->auth_baid = $user['auth_baid'];
            $model->is_del = 0;
            $result = $model->save();
            $modeltel = new Customer_tel();
            $modeltel->tel_phone = trim($param['tel_phone']);
            $modeltel->tel_type = trim($param['tel_type']);
            $modeltel->customer_private = trim($param['customer_private']);
            $modeltel->customer_uuid = $model->attributes['customer_uuid'];
            $modeltel->c_id = $user['u_id'];
            $modeltel->u_id = $user['u_id'];
            $modeltel->company_id = self::$user['company_id'];
            $modeltel->ctime = date('Y-m-d H:i:s');
            $modeltel->utime = date('Y-m-d H:i:s');
            $resulttel = $modeltel->save();
            //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息)
            //条件等级
             Customer::dengjipanding($model->attributes['customer_uuid']);
            $Customer_log = new Customer_log();
            $Customer_log->log($model->attributes['customer_uuid'], 4, '添加客源信息', $user);
            if ($result && $resulttel) {
                return true;
            } else {
                return false;
            }
        }		
    }

    public static function setStatus($param)
    {

        //修改状态
        $data = [
            'zhuangtai' => $param['customer_status'],
            'u_id' => $param['u_id'],
            'utime' => $param['utime']
        ];
        if (customer::updateAll($data, ['customer_uuid' => $param['customer_uuid'],'company_id'=>$param['company_id']]) === false) {
            return false;
        }
        //条件等级
        Customer::dengjipanding($param['customer_uuid']);
        $status = $param['customer_status'];

        ////写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）10（查看跟进列表）11（修改状态）',
        $Customer_log = new Customer_log();
        $Customer_log->log($param['customer_uuid'], 11, '修改客源状态为' . $status, ['u_id'=>$param['u_id'],'company_id'=>$param['company_id']]);
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
            'fengpan_user' => $param['fengpan_user'],
            'u_id' => $param['u_id'],
            'c_id' => $param['u_id'],
            'utime' => $param['utime']
        ];
        if (Customer::updateAll($data, ['customer_uuid' => $param['customer_uuid'],'company_id'=>$param['company_id']]) === false) {
            return false;
        }


        //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）10（查看跟进列表）11（修改状态）12（申请封盘）',
        $Customer_log = new Customer_log();
        $Customer_log->log($param['customer_uuid'], 12, '申请封盘', $param);
        return true;

    }


    /*
     * 客源等级设置
     */
    public static function dengjipanding($customer_uuid)
    {
        $customer = Customer::find()->where(['customer_uuid' => $customer_uuid, 'is_del' => 0])->one();

        $tiaojian = array();
        $key = 0;
        $duoxuanbiaoqian = explode(";", $customer->duoxuanbiaoqian);
        if (!empty($duoxuanbiaoqian)) {
            foreach ($duoxuanbiaoqian as $v) {
                if($v){
                    $tiaojian[$key++] = $v;
                }

            }
        }
        $i = count($tiaojian);
        if ($i >= 3 and $customer->zhuangtai == '有效') {
            $customer->tiaojiandengji = 'A级';
            $customer->save();
            return true;
        } else if ($i < 3 and $i >= 1 and $customer->zhuangtai == '有效') {
            $customer->tiaojiandengji ='B级';
            $customer->save();
            return true;
        } else if ($customer->zhuangtai == '无效') {
            $customer->tiaojiandengji = 'D级';
            $customer->save();
            return true;
        } else {
            $customer->tiaojiandengji = 'C级';
            $customer->save();
            return true;
        }

    }

    // 跳公
    public static function tiaogong()
    {
        $row = CustomerGii::find()->where(['is_del' => 0,'customer_private'=>'私客']);
        $list = $row->all();

        if (!empty($list)) {
            foreach ($list as $v) {
                if ($v->customer_private == "公客") {
                    $gjsj = $v->quanyuangenjin;
                } else {
                    $gjsj = $v->weihurengenjin;
                }
                $dksj = $v->daikanshijian;
                $r = self::ctj($v->customer_type, $v->tiaojiandengji, $v->customer_private,$v->company_id ,$dksj, $gjsj);
                if (!$r) {
                    $telture = Customer_telGii::find()->where(['is_del' => '0', 'customer_uuid' => $v['customer_uuid']])->all();
                    $c = 0;
                    if (!empty($telture)) {
                        foreach ($telture as $val) {
                            $rw = Customer_telGii::find()->where(['is_del' => '0', 'tel_phone' => $val->tel_phone]);
                            $count = $rw->andWhere(['<>', 'customer_uuid', $val->customer_uuid])->count();
                            $val->customer_private = '公客';
                            if ($count > 0) {//如果重复 就把电话设为删除
                                $val->is_del = 1;
                                $c = 1;
                            }
                            $val->save();
                        }
                    }
                    if ($c == 1) {
                        $v->is_del = 1;
                        $v->customer_private = '公客';
                        $v->c_id = NULL;
                        $Customer_log = new Customer_logGii();
                        $Customer_log->c_id =!empty($user['u_id'])?$user['u_id']:'' ;
                        $Customer_log->customer_uuid = $v['customer_uuid'];
                        $Customer_log->cl_type = 25;
                        $Customer_log->u_id = !empty($user['u_id'])?$user['u_id']:'';
                        $Customer_log->ctime = date('Y-m-d H:i:s');
                        $Customer_log->utime =date('Y-m-d H:i:s');
                        $Customer_log->company_id =  $v->company_id;
                        $Customer_log->cl_content = '自动跳为公客,重复删除';
                        $Customer_log->save();
                        $v->save();
                    } else {
                        //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）10（查看跟进列表）11（修改状态）12（申请封盘）',
                        $Customer_log = new Customer_logGii();
                        $Customer_log->c_id =!empty($user['u_id'])?$user['u_id']:'' ;
                        $Customer_log->customer_uuid = $v['customer_uuid'];
                        $Customer_log->cl_type = 25;
                        $Customer_log->u_id = !empty($user['u_id'])?$user['u_id']:'';
                        $Customer_log->ctime = date('Y-m-d H:i:s');
                        $Customer_log->utime =date('Y-m-d H:i:s');
                        $Customer_log->company_id =  $v->company_id ;
                        $Customer_log->cl_content = '私客维护时间已到，自动跳为公客';
                        $Customer_log->save();
                        $v->customer_private = '公客';
                        $v->c_id = NULL;
                        $v->quanyuangenjin = date('Y-m-d H:i:s');
                        $v->save();
                    }

                }
            }
        }

    }

    /*
     * $lb客源类别
     * $dj客源等级
     * $gs公客私客
     * $company_id 公司id
     * $dksj 带看时间
     * $gjsj 跟进时间
     */
    public static function ctj($lb, $dj, $gs, $company_id,$dksj, $gjsj)
    {
        if ($dj == 'A级') {
            $dj = 'A类客源';
        }
        if ($dj == 'B级') {
            $dj = 'B类客源';
        }
        if ($dj == 'C级') {
            $dj = 'C类客源';
        }
        if ($dj == 'D级') {
            $dj = 'D类客源';
        }
        $row = Class_customer::find()->where(['is_del' => 0,'company_id'=>$company_id, 'cc_type' => $lb, 'cc_name' => $dj]);
        $list = $row->asArray()->one();
        if ($gs == '私客') {
            $gjwh = $list['cc_private_creturn'];
            $dakan = $list['cc_private_clook'];
            $gjwh = strtotime("$dksj +$gjwh day");
            $dakan = strtotime("$gjsj +$dakan day");
            if ($gjwh < time() or $dakan < time()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }

    }


}
