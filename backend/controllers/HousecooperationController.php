<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\ZhSettingQujian;
use backend\models\UserAuth;
use backend\models\District_slice;
use backend\models\District_region;
use backend\models\User;
use backend\models\HouseCooperation;
use backend\models\House_blacklist;
use backend\models\Companycootel;
use backend\models\ZhSettingBase;
use common\controllers\CommonController;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\HouseImgGii;
use common\models\gii\ComDistrictGii;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 合作房源控制器
 */
class HousecooperationController extends AuthController
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
     * 获取选择参数
     * @return array|\common\models\json
     */
    public function actionGetsetting()
    {
        $jgqj = ZhSettingQujian::find()->where(['company_id'=>$this->_user['company_id'],'qujian_shorthand' => 'jgqj'])->select('qujian_desp')->asArray()->one();
        $data['jgqj'] = json_decode($jgqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_user['company_id'],'qujian_shorthand' => 'mjqj'])->select('qujian_desp')->asArray()->one();
        $data['mjqj'] = json_decode($mjqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$this->_user['company_id'],'qujian_shorthand' => 'zuling_jigeqj'])->select('qujian_desp')->asArray()->one();
        $data['zlqj'] = json_decode($mjqj['qujian_desp'], true);

        //获取片区小区树信息
        $data['district'] =CommonController::getDtsList($this->_user['city_id'],$this->_user['company_id']);
        return ApiReturn::success('查询成功', $data);
    }

    /*
     * 合作房源列表
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
      $row =HouseCooperation::find()->alias('c')->select('c.cooperation_id,h.house_id,c.company_id,c.utime,h.sale_type,h.house_title,h.dts_name,h.village_name,h.jianzhumianji,h.huxing_shi,h.huxing_ting,h.huxing_wei,h.huxing_chu,h.huxing_yangtai,h.sell_price,h.rent_price,h.louceng_now,h.louceng_total,h.fangwuleixing,h.zhuangxiu,h.house_tag,h.shiyongmianji,h.peitao,h.xianzhuang,h.jianzhujiegou,h.jianzaoniandai,h.chanquanxingzhi,h.chanquannianxian,h.chanzhengriqi,h.fangyuanshuifei,h.kanfangfangshi,h.is_yaoshi,h.house_sn')
          ->where(['c.is_del'=>0,'h.house_status'=>1,'h.is_cooperation'=>1,'c.house_type'=>$param['house_type']])
          ->andWhere('h.company_id<>'.$this->_user['company_id'])
          ->leftJoin('zh_house as h','h.house_uuid=c.house_id')
          ->leftJoin('zh_house_blacklist as hb','hb.cooperation_id=c.cooperation_id and hb.is_del=0 and hb.company_id='.$this->_user['company_id']);
        if (isset($param["kw"]) && $param["kw"]) {     //关键词 关键词可以是 房源标题、客户姓名、客户手机号、备注、核心备注
            $row->andWhere("h.house_title like '%" . $param["kw"] . "%'"
                . " or h.house_sn like '%" . $param["kw"] . "%'"
                . " or h.dts_name like '%" . $param["kw"] . "%'"
                . " or h.village_name like '%" . $param["kw"] . "%'"
            );
        }
        if (isset($param["area"]) && $param["area"]) {  //面积区间
            $tmpData = explode("-", $param["area"]);
            $row->andWhere(['between', 'h.jianzhumianji', $tmpData[0], $tmpData[1]]);
        }
        if (isset($param["price"]) && $param["price"]) {  //价格区间
            $tmpData = explode("-", $param["price"]);
            if($param["house_type"]<>1){
                $row->andWhere(['between', 'h.sell_price', $tmpData[0], $tmpData[1]]);
            }else{
                $row->andWhere(['between', 'h.rent_price', $tmpData[0], $tmpData[1]]);
            }

        }
        if (isset($param["huxing"]) && !empty($param["huxing"])) { //室
            $row->andWhere(['h.huxing_shi' => $param["huxing"]]);
        }
        if (isset($param["quyu"]) && is_array($param["quyu"])) {  //片区
            if (count($param["quyu"]) > 1) {
                $row->andWhere(['h.dts_id' => end($param['quyu'])]);
            } else {
                $dts_ids = ComDistrictGii::find()->select('dts_id')->where(['is_del'=>'0','area_id'=>end($param['quyu'])])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.$this->_user['company_id']])->asArray()->all();
                if($dts_ids){
                   $p=[];
                    foreach($dts_ids as $v){
                        $p[]=$v['dts_id'];
                    }
                    $row->andWhere(['IN','h.dts_id',$p]);
                }

            }
        }
        if(isset($param['xiaoqu'])&&!empty($param['xiaoqu'])){
            $row->andWhere(['h.village_id' => $param['xiaoqu']]);
        }


        if(!empty($param['zhuangtai'])){
            if($param['zhuangtai']==1){
                $row->andWhere('hb.b_id  is  null');
            }
            if($param['zhuangtai']==2){
                $row->andWhere('hb.b_id  is not  null ');
            }
        }
        $list=$row->orderBy('c.utime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        if(!empty($list)){
            foreach($list as $key=>$v){
                $list[$key]['blacklist']=  House_blacklist::find()->where(['cooperation_id'=>$v['cooperation_id'],'company_id'=>$v['company_id'],'company_id'=>$this->_user['company_id'],'is_del'=>0])->asArray()->all();
                //判断是否加入了黑名单（1 加入了黑名单 0未加入）
                if(!empty($list[$key]['blacklist'])){
                    $list[$key]['is_blacklist']=1;
                }else{
                    $list[$key]['is_blacklist']=0;
                }
                $list[$key]['company_tel']=  Companycootel::find()->where(['company_id'=>$v['company_id'],'is_del'=>0])->asArray()->all();
                $list[$key]['house_img'] =HouseImgGii::find()->where(['house_id'=>$v['house_id'],'is_del'=>0])->andWhere('hi_type<>2 and hi_type<>3')->asArray()->all();
              $peitao='';
               foreach (json_decode($v['peitao'] )as $kk=>$vv){
                   $peitao.=$vv.';';
               }
                $list[$key]['peitao']=$peitao;
              $house_tag='';
               foreach (json_decode($v['house_tag'] )as $kk=>$vv){
                   $house_tag.=$vv.';';
               }
                $list[$key]['house_tag'] = $house_tag;
            }
        }
        $result['houseList']=$list;
        $result['total']=$row->count();
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    //屏蔽房源
    public function actionBlacklist(){
        $param = Yii::$app->request->post();
        if(empty($param['cooperation_id'])){
            return ApiReturn::wrongParams('异常，请稍后再试！');
        }
        if($param['zhuangtai']=='正常'){
            $is_del=1;
        }else{
            $is_del=0;
        }
        $blacklist=House_blacklist::find()->where(['cooperation_id'=>$param['cooperation_id'],'company_id'=>$this->_user['company_id']])->one();
        $house=HouseCooperation::find()->where(['cooperation_id'=>$param['cooperation_id']])->one();
        if(!empty($blacklist)){

            $blacklist->is_del=$is_del;
            $blacklist->reason=$param['yuanyin'];
            $blacklist->utime=date('Y-m-d H:i:s');
            if($blacklist->save()){
                $rs= true;
            }else{
                $rs=  false;
            }
        }else{
            $blacklist=new House_blacklist();
            $blacklist->cooperation_id=$param['cooperation_id'];
            $blacklist->company_id=$param['company_id'];
            $blacklist->house_id=$house->house_id;
            $blacklist->reason=$param['yuanyin'];
            $blacklist->c_id=$this->_user['u_id'];
            $blacklist->utime=date('Y-m-d H:i:s');
            $blacklist->ctime=date('Y-m-d H:i:s');
            $blacklist->is_del=$is_del;
            if($blacklist->save()){
                $rs= true;
            }else{
                $rs=  false;
            }
        }
        if($rs){
            return ApiReturn::success('操作成功');
        }else{
            return ApiReturn::wrongParams('操作失败');
        }

    }


}
