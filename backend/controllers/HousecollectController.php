<?php

namespace backend\controllers;


use backend\models\House;
use backend\models\OrgCompanyContract;
use backend\models\ZhSettingQujian;
use common\models\ApiReturn;
use common\helps\Tools;
use common\models\gii\HouseCaijiLogGii;
use Yii;
use yii\helpers\ArrayHelper;

class HousecollectController extends AuthController
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
     * 获取查询参数
     */
    public function actionGetsetting()
    {
        $jgqj = ZhSettingQujian::find()->where(['company_id'=>$this->_user['company_id'],'qujian_shorthand' => 'jgqj'])->select('qujian_desp')->asArray()->one();
        $data['jgqj'] = json_decode($jgqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_user['company_id'],'qujian_shorthand' => 'mjqj'])->select('qujian_desp')->asArray()->one();
        $data['mjqj'] = json_decode($mjqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_user['company_id'],'qujian_shorthand' => 'zuling_jigeqj'])->select('qujian_desp')->asArray()->one();
        $data['zlqj'] = json_decode($mjqj['qujian_desp'], true);

        $data['district'] = '';
        $district_url = Yii::$app->params['collect_url'].'/api/house/districtlist';
        $district_data = json_decode(Tools::curl_get($district_url), true);
        if($district_data['code']==200){
            $data['district'] = $district_data['data'];
        }

        return ApiReturn::success('查询成功', $data);
    }

    /**
     * 买卖房源采集列表
     */
    public function actionSellcollectlist()
    {
        $param = Yii::$app->request->get();

        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;

        $collect_url = Yii::$app->params['collect_url'].'/api/house/houselist?page='.$page.'&pagesize='.$pagesize.'&type=2';
       // var_dump($collect_url);exit();
        if(isset($param['kw']) && $param['kw']){
            $collect_url.='&kw='.$param['kw'];
        }
        if(isset($param['quyu']) && $param['quyu']){ //地区
            if(isset($param['xcx']) && $param['xcx']){
                $collect_url.='&quyu='.$param['quyu'];
            }else{
                $collect_url.='&quyu='.$param['quyu'][0];
            }
        }
        if(isset($param['area']) && $param['area']){ //面积
            $collect_url.='&area='.$param['area'];
        }
        if(isset($param['huxing']) && $param['huxing']){ //户型
            $collect_url.='&huxing='.$param['huxing'];
        }
        if(isset($param['price']) && $param['price']){ //价格
            $collect_url.='&price='.$param['price'];
        }
        //var_dump($collect_url);
        $result_arr = json_decode(Tools::curl_get($collect_url), true);//var_dump($result_arr);die;
        if ($result_arr['code'] != 200) {
            return ApiReturn::wrongParams('获取失败');
        }
        $data['total'] = $result_arr['data']['tatal'];
        $data['houseList'] = $result_arr['data']['list'];
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 出租房源采集列表
     */
    public function actionRentcollectlist()
    {
        $param = Yii::$app->request->get();

        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;

        $collect_url = Yii::$app->params['collect_url'].'/api/house/houselist?page='.$page.'&pagesize='.$pagesize.'&type=1';

        if(isset($param['kw']) && $param['kw']){
            $collect_url.='&kw='.$param['kw'];
        }
        if(isset($param['quyu']) && $param['quyu']){ //地区
            if(isset($param['xcx']) && $param['xcx']){
                $collect_url.='&quyu='.$param['quyu'];
            }else{
                $collect_url.='&quyu='.$param['quyu'][0];
            }
        }
        if(isset($param['area']) && $param['area']){ //面积
            $collect_url.='&area='.$param['area'];
        }
        if(isset($param['huxing']) && $param['huxing']){ //户型
            $collect_url.='&huxing='.$param['huxing'];
        }
        if(isset($param['price']) && $param['price']){ //价格
            $collect_url.='&price='.$param['price'];
        }
        //var_dump($collect_url);die;
        $result_arr = json_decode(Tools::curl_get($collect_url), true);
        if ($result_arr['code'] != 200) {
            return ApiReturn::wrongParams('获取失败');
        }

        $data['total'] = $result_arr['data']['tatal'];
        $data['houseList'] = $result_arr['data']['list'];

        return ApiReturn::success('获取成功',$data);
    }

    public function actionGetimportcount(){
        $user = $this->_user;

        $res = OrgCompanyContract::find()->where(['company_id' => $user['company_id']])->asArray()->one();
        $contract_import = $res['contract_import'];

        //搜索次数
        $count = HouseCaijiLogGii::find()->where(['c_id' => $user['u_id'], 'ch_sign' => 2])->andFilterWhere(['between', 'ctime', date('Y-m-d') . ' 00:00:01', date('Y-m-d') . ' 23:59:59'])->count();

        if($count > $contract_import){
            return ApiReturn::wrongParams('您今日导入的次数已经用完，如果想导入更多请升级！');
        }
        return ApiReturn::success('获取成功');
    }

    /*
    * 查看电话
    */
    public function actionGettel(){
        $get = Yii::$app->request->get();

        $user = $this->_user;

        $res = OrgCompanyContract::find()->where(['company_id' => $user['company_id']])->asArray()->one();
        $contract_phone = $res['contract_phone'];

        //搜索次数
        $count = HouseCaijiLogGii::find()->where(['c_id' => $user['u_id'], 'ch_sign' => 1])->andFilterWhere(['between', 'ctime', date('Y-m-d') . ' 00:00:01', date('Y-m-d') . ' 23:59:59'])->count();

        if($count > $contract_phone){
            return ApiReturn::wrongParams('您今日查看电话的次数已经用完，如果想查看更多请升级！');
        }

        $houseLog = new HouseCaijiLogGii();
        $houseLog->c_id = !empty($user['u_id'])?$user['u_id']:'' ;
        $houseLog->d_id = !empty($user['u_dept_id'])?$user['u_dept_id']:'' ;
        $houseLog->house_id = $get['house_id'];
        $houseLog->ctime = date('Y-m-d H:i:s',time());
        $houseLog->ch_type = $get['sale_type'];
        $houseLog->ch_sign = 1;
        $houseLog->ch_content = '查看电话';
        $houseLog->company_id = $user['company_id'];
        $houseLog->save();
        return ApiReturn::success('查看电话成功');
    }

    /**
     * 导入采集房源
     */
    public function actionImport(){
        $param = Yii::$app->request->post();
        if (!empty($param['selection']) && $param['selection'] <> 'undefined') {
            if(is_string($param['selection'])){
                $selection = json_decode($param['selection'], true);
            }else{
                $selection = $param['selection'];
            }
            $user = $this->_user();
            foreach ($selection as $v) {
                $res = House::find()->where(['customer_phone' => $v['customer_phone']])->one();
                if($res){ // 有重复记录
                    continue;
                }
                /*$House = House::findOne($v['house_id']);
                $House->is_del = 1;
                if ($House->update() === false) {
                    $transaction->rollBack();
                    return false;
                };
                House::setLog($v['house_id'], '0', '删除了房源', $this->_user);*/

                $house_sn = Yii::$app->redis->get('house_sn');

                if (empty($house_sn)) {
                    $house_sn = 1;
                    Yii::$app->redis->set('house_sn', $house_sn);
                } else {
                    $house_sn = $house_sn + 1;
                    Yii::$app->redis->set('house_sn', $house_sn);
                }

                if ($v['sale_type'] == 1) {
                    $v['house_sn'] = 'CZFY-' . date('ymd') . '-' . str_pad($house_sn, 4, "0", STR_PAD_LEFT);
                    $v['house_uuid'] = Tools::create_uuid('CZFY-');
                } elseif ($v['sale_type'] == 2) {
                    $v['house_sn'] = 'CSFY-' . date('ymd') . '-' . str_pad($house_sn, 4, "0", STR_PAD_LEFT);
                    $v['house_uuid'] = Tools::create_uuid('CSFY-');
                } elseif ($v['sale_type'] == 3) {
                    $v['house_sn'] = 'GDFY-' . date('ymd') . '-' . str_pad($house_sn, 4, "0", STR_PAD_LEFT);
                    $v['house_uuid'] = Tools::create_uuid('GDFY-');
                }

                $model = new House();
                $attrs = $model->attributes;
                foreach($attrs as $key => $a){
                    if(in_array($key, ['house_id', 'ctime', 'utime'])){
                        continue;
                    }
                    if(array_key_exists($key, $v)){
                        $model->$key = $v[$key];
                    }
                }

                $model->house_status = 1;
                $model->house_private = 1; // 0公盘，1私盘
                $model->private_user = $user['u_id'];
                $model->c_id = $user['u_id'];
                $model->u_id = $user['u_id'];
                $model->company_id = $user['company_id'];
                $model->utime = date('Y-m-d H:i:s', time());
                $model->ctime = date('Y-m-d H:i:s', time());
                $model->quanyuangenjin = date('Y-m-d H:i:s', time());
                $model->weihurengenjin = date('Y-m-d H:i:s', time());
                $model->daikanshijian = date('Y-m-d H:i:s', time());
                $model->auth_cid = $user['auth_cid'];
                $model->auth_rid = $user['auth_rid'];
                $model->auth_sid = $user['auth_sid'];
                $model->auth_aid = $user['auth_aid'];
                $model->auth_baid = $user['auth_baid'];

                if ($model->save()) {
                    $house_id = Yii::$app->db->getLastInsertId();

                    //self::setLog($house_id,'0','导入了采集的房源',$user);
                }
                /*$house->dts_name = $v['dts_name'];
                $house->village_name = $v['village_name'];
                $house->loudong_name = $v['loudong_name'];
                $house->danyuan_name = $v['danyuan_name'];
                $house->fanghao_name = $v['fanghao_name'];
                $house->customer_name = $v['customer_name'];
                $house->customer_sex = $v['customer_sex'];
                $house->customer_phone = $v['customer_phone'];
                $house->customer_type = $v['customer_type'];
                $house->house_title = $v['house_title'];
                $house->house_tag = $v['house_tag'];
                $house->house_tuijian_tag = $v['house_tuijian_tag'];
                $house->sell_price = $v['sell_price'];
                $house->rent_price = $v['rent_price'];
                $house->rent_unit = $v['rent_unit'];
                $house->huxing_shi = $v['huxing_shi'];
                $house->huxing_ting = $v['huxing_ting'];
                $house->huxing_wei = $v['huxing_wei'];
                $house->huxing_chu = $v['huxing_chu'];
                $house->huxing_yangtai = $v['huxing_yangtai'];
                $house->jianzhumianji = $v['jianzhumianji'];
                $house->louceng_now = $v['louceng_now'];
                $house->louceng_total = $v['louceng_total'];
                $house->chaoxiang = $v['chaoxiang'];
                $house->tihu_ti = $v['tihu_ti'];
                $house->tihu_hu = $v['tihu_hu'];*/
            }

            return ApiReturn::success('导入成功');
        } else {
            return ApiReturn::wrongParams('请您选择您要导入的房源');
        }
    }

}