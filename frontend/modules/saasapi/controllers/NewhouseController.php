<?php

namespace frontend\modules\saasapi\controllers;

use backend\models\YsHouse;
use common\helps\Tools;
use frontend\modules\saasapi\models\ApiReturn;
use frontend\modules\saasapi\controllers\ApiController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class NewhouseController extends ApiController
{
    public $tokenActions = [];


    /**
     * 新房列表
     */
    public function actionNewhouselist()
    {
        $param = Yii::$app->request->get();
        $data = $this->getList($param);
        if($data){
            return ApiReturn::success('获取成功',$data);
        }else{
            return ApiReturn::noData('没有更多了');
        }

    }

    /**
     * 新房详情
     */
    public function actionNewhousedetail($id)
    {

    }

    //列表操作
    private function getList($param)
    {
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $row = YsHouse::find()->where(['company_id'=>$this->_orgCompany['company_id'],'is_del'=>0]);

        //项目类型
        if(isset($param['quyu']) && $param['quyu']){ //区域
            if(isset($param['loupan']) && $param['loupan']){ //推荐房源查询
                $row->andWhere(['<>','house_id',$param['selfid']]);
                $row->andWhere(['or',"village_name='".$param['loupan']."'","dts_id='".$param['quyu']."'"]);
            }else{
                $row->andWhere(['dts_id' => $param['quyu']]);
            }
        }
        if(isset($param['huxing']) && $param['huxing']){ //户型
            if($param['huxing'] < 5){
                $row->andWhere(['huxing_shi' => $param['huxing']]);
            }else{
                $row->andWhere(['>','huxing_shi',5]);
            }
        }
        if(isset($param['price']) && $param['price']){ //价格
            $tmpData = explode("-", $param["price"]);
            if($param['house_type'] == 1){
                $row->andWhere(['between', 'rent_price', $tmpData[0], $tmpData[1]]);
            }else if($param['house_type'] == 2){
                $row->andWhere(['between', 'sell_price', $tmpData[0], $tmpData[1]]);
            }
        }
        if(isset($param['area']) && $param['area']){ //面积
            $tmpData = explode("-", $param["area"]);
            $row->andWhere(['between', 'jianzhumianji', $tmpData[0], $tmpData[1]]);
        }

        if(isset($param['keyword']) && $param['keyword']){ //关键词
            $row->andWhere(['like','village_name', $param['keyword']]);
        }

        $data = $row->orderBy([ 'id' => SORT_DESC])->limit($pagesize)->offset($start)->asArray()->all();

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