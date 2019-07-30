<?php

namespace backend\controllers;


use backend\models\OrgCompanyConsign;
use common\models\ApiReturn;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

class ConsignController extends AuthController
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
     * 委托列表
     */
    public function actionConsignlist()
    {
        $param = Yii::$app->request->get();

        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $row = OrgCompanyConsign::find()->alias('a')->leftJoin('org_company_consign_log b','a.c_id=b.c_id')
            ->select('a.*,count(b.cu_id) as shownum')
            ->Where(['a.company_id'=>$this->_user['company_id'],'a.is_del'=>0]);
        if(isset($param['type']) && $param['type']){ //类型
            $row->andWhere(['a.c_type' => $param['type']]);
        }
        if(isset($param['name']) && $param['name']){ //用户
            $row->andWhere(['like','community_name', $param['name']]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['consignlist'] = $row->orderBy('a.ctime DESC')->groupBy('a.c_id')->limit($pagesize)->offset($start)->asArray()->all();
        foreach ($listdata['consignlist'] as $key => $item){
            if($item['c_type'] == 1 ){
                $listdata['consignlist'][$key]['type'] =  '卖房';
                $listdata['consignlist'][$key]['area'] =  $item['house_area'];
                $listdata['consignlist'][$key]['price'] =  $item['sell_price'];
            }else if($item['c_type'] == 2){
                $listdata['consignlist'][$key]['type'] =  '买房';
                $listdata['consignlist'][$key]['area'] =  $item['buy_area_from'].'-'.$item['buy_area_to'];
                $listdata['consignlist'][$key]['price'] =  $item['buy_price_from'].'-'.$item['buy_price_to'];
            }else if($item['c_type'] == 3){
                $listdata['consignlist'][$key]['type'] =  '出租';
                $listdata['consignlist'][$key]['area'] =  $item['hire_area'];
                $listdata['consignlist'][$key]['price'] =  $item['hire_price'];
            }else if($item['c_type'] == 4){
                $listdata['consignlist'][$key]['type'] =  '租房';
                $listdata['consignlist'][$key]['area'] =  $item['lease_area_from'].'-'.$item['lease_area_to'];
                $listdata['consignlist'][$key]['price'] =  $item['lease_price_from'].'-'.$item['lease_price_from'];
            }
            $listdata['consignlist'][$key]['huxing'] = $item['house_type_shi'].'室'.$item['house_type_ting'].'厅'.$item['house_type_wei'].'卫';
        }
        //echo $row->createCommand()->getRawSql();
        return ApiReturn::success('获取成功',$listdata);
    }

}