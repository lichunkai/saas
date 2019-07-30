<?php

namespace frontend\modules\saasapi\controllers;

use backend\models\Companycootel;
use backend\models\Customer_daikan_house;
use backend\models\House;
use backend\models\User;
use common\helps\Tools;
use common\models\gii\ComDistrictGii;
use frontend\modules\saasapi\models\ApiReturn;
use frontend\modules\saasapi\controllers\ApiController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class SecondhouseController extends ApiController
{
    public $tokenActions = [];

    /**
     * 二手房列表
     */
    public function actionHouselist()
    {
        $param = Yii::$app->request->get();
        $data = $this->getList($param);
        if($data){
            return ApiReturn::success('获取成功',$data);
        }else{
            return ApiReturn::noData('没有更多了');
        }
    }

    public function actionRecommendlist()
    {
        $param = Yii::$app->request->get();
        $param['tj'] = 1;
        $param['selfid'] = '';
        $data = $this->getRecommendlist($param);
        if($data){
            return ApiReturn::success('获取成功',$data);
        }else{
            return ApiReturn::noData('没有更多了');
        }
    }

    /**
     * 二手房详情
     */
    public function actionHousedetail($id)
    {
        //var_dump($id);die;
        $select_columns = 'house_id,house_uuid,sale_type,house_sn,house_private,dts_name,village_name,house_title,house_tag,house_tuijian_tag,sell_price,rent_price,rent_unit,huxing_shi,huxing_ting,huxing_wei,';
        $select_columns .= 'huxing_chu,huxing_yangtai,jianzhumianji,shiyongmianji,louceng_now,louceng_total,chaoxiang,tihu_ti,tihu_hu,zhuangxiu,peitao,xianzhuang,fangwuleixing,jianzhujiegou,';
        $select_columns .= 'jianzaoniandai,chanquanxingzhi,chanquannianxian,chanzhengriqi,fangyuanshuifei,kanfangfangshi,fukuanfangshi,mark,company_id,private_user,c_id,u_id,ctime,utime';

        $data = House::find()->select($select_columns)->Where(['house_uuid'=>$id,'is_del'=>0])->with('houseimage')->asArray()->one();

        if(!$data){
            return ApiReturn::noData('没有房源数据');
        }
        $data['member_phone'] = '';

        $param['tj'] = 1;
        $param['pagesize'] = 3;
        $param['selfid'] = '';
        if($data['company_id'] == $this->_orgCompany['company_id']){
            $param['selfid'] = $data['house_id'];
        }
        $data['list'] =  $this->getRecommendlist($param);

        if($data['company_id'] == $this->_orgCompany['company_id']){ //自有房源
            $data['sort'] = 1;
            $user_arr = [$data['c_id']];
            if($data['house_private'] == 1){
                array_push($user_arr,$data['private_user']);
            }
            $daikan_id = Customer_daikan_house::find()->select('c_id')->where(['company_id'=>$this->_orgCompany['company_id'],'house_uuid'=>$data['house_uuid'],'is_del'=>0])->asArray()->all();
            if(!empty($daikan_id)){
                array_merge($user_arr,$daikan_id);
            }
            foreach ($daikan_id as $key => $item){
                array_push($user_arr,$item['c_id']);
            }
            $user_arr = array_unique($user_arr);
            $user_phone_arr = User::find()->select('u_name,u_phone')->where(['in', 'u_id', $user_arr])->asArray()->all();
            $data['member_phone'] = $user_phone_arr;
        }else{  //合作房源
            $data['sort'] = 0;
            $data['member_phone'] = Companycootel::find()->select('occ_name as u_name,occ_tel as u_phone')->where(['company_id'=>$this->_orgCompany['company_id'],'is_del'=>0])->asArray()->all();
        }
        return ApiReturn::success('获取成功',$data);
    }

    //列表操作
    private function getList($param)
    {
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;
        $company_id = $this->_orgCompany['company_id'];
        //(CASE WHEN company_id=$company_id THEN 1 ELSE 0 END)
        $select_columns = "house_id,house_uuid,sale_type,house_sn,dts_name,village_name,house_title,house_tag,house_tuijian_tag,sell_price,rent_price,rent_unit,huxing_shi,huxing_ting,huxing_wei,";
        $select_columns .= "huxing_chu,huxing_yangtai,jianzhumianji,chanquanxingzhi,zhuangxiu,fukuanfangshi,chaoxiang,fangwuleixing,(CASE WHEN company_id=$company_id THEN 1 ELSE 0 END) as sort,company_id,ctime,utime";

        $row = House::find()->select($select_columns)->where(['or',['company_id'=>$this->_orgCompany['company_id'],'online_show' => 1],['is_cooperation'=>1]])->andWhere(['is_del' => 0])->with('houseimage');

        if(isset($param['house_type'])&& $param["house_type"]){ // 盘源类型(租/售)
            $row->andWhere(['sale_type' => $param['house_type']]);
        }
        if(isset($param['quyu']) && $param['quyu']){ //区域
            if(isset($param['dts']) && $param['dts']){
                $row->andWhere(['dts_id' => $param['dts']]);
            }else{
                $child_dts = ComDistrictGii::find()->select('dts_id')->where(['area_id'=>$param['quyu']])->asArray()->all();
                $row->andWhere(['in','dts_id', $child_dts]);
            }
        }
        if(isset($param['huxing']) && $param['huxing']){ //户型
            $tmpData = explode("-", $param["huxing"]);
            if($tmpData[1] == 0){
                $row->andWhere(['>', 'huxing_shi', $tmpData[0]]);
            }else{
                $row->andWhere(['between', 'huxing_shi', $tmpData[0], $tmpData[1]]);
            }
        }
        if(isset($param['price']) && $param['price']){ //价格
            $tmpData = explode("-", $param["price"]);
            if($param['house_type'] == 1){
                if($tmpData[1] == 0){
                    $row->andWhere(['>', 'rent_price', $tmpData[0]]);
                }else{
                    $row->andWhere(['between', 'rent_price', $tmpData[0], $tmpData[1]]);
                }
            }else if($param['house_type'] == 2){
                if($tmpData[1] == 0){
                    $row->andWhere(['>', 'sell_price', $tmpData[0]]);
                }else{
                    $row->andWhere(['between', 'sell_price', $tmpData[0], $tmpData[1]]);
                }
            }
        }
        if(isset($param['area']) && $param['area']){ //面积
            $tmpData = explode("-", $param["area"]);
            if($tmpData[1] == 0){
                $row->andWhere(['>', 'jianzhumianji', $tmpData[0]]);
            }else{
                $row->andWhere(['between', 'jianzhumianji', $tmpData[0], $tmpData[1]]);
            }
        }

        if(isset($param['keyword']) && $param['keyword']){ //关键词
            $row->andWhere(['like','village_name', $param['keyword']]);
        }

        $sort = 'sort desc,utime desc';
        if(isset($sort) && $sort == 1){
            $sort = 'sort desc,jianzhumianji asc';
        }elseif (isset($sort) && $sort == 2){
            $sort = 'sort desc,jianzhumianji desc';
        }elseif (isset($sort) && $sort == 3){
            if($param['house_type'] == 1){
                $sort = 'sort desc,rent_price asc';
            }else if($param['house_type'] == 2){
                $sort = 'sort desc,sell_price asc';
            }
        }elseif (isset($sort) && $sort == 4){
            if($param['house_type'] == 1){
                $sort = 'sort desc,rent_price desc';
            }else if($param['house_type'] == 2){
                $sort = 'sort desc,sell_price desc';
            }
        }
        $data['tatal'] = $row->count();
        $data['list'] = $row->orderBy($sort)->limit($pagesize)->offset($start)->asArray()->all();
//        echo $data['list']->createCommand()->getRawSql();
//        var_dump($data);die;
        return $data;
    }

    //推荐列表
    private function getRecommendlist($param)
    {
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $select_columns = "house_id,house_uuid,sale_type,house_sn,dts_name,village_name,house_title,house_tag,house_tuijian_tag,sell_price,rent_price,rent_unit,huxing_shi,huxing_ting,huxing_wei,";
        $select_columns .= "huxing_chu,huxing_yangtai,jianzhumianji,chanquanxingzhi,zhuangxiu,fukuanfangshi,chaoxiang,fangwuleixing,company_id,ctime,utime";

        $row = House::find()->select($select_columns)->where(['company_id'=>$this->_orgCompany['company_id'],'online_show' => 1,'is_del' => 0])->with('houseimage');
        if(isset($param['tj']) && $param['tj']){ //推荐
            $row->andWhere(['is_main' => 1]);
        }
        if(isset($param['selfid']) && $param['selfid']){
            $row->andWhere(['<>','house_id',$param['selfid']]);
        }
        $sort = 'utime desc';
        $data['list'] = $row->orderBy($sort)->limit($pagesize)->offset($start)->asArray()->all();
        return $data;
    }

    /**
     * 设置action允许的methods
     * @return array
     */
    public function verbs()
    {
        return [];
    }
}