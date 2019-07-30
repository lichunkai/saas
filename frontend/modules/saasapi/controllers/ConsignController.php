<?php

namespace frontend\modules\saasapi\controllers;

use common\helps\Tools;
use frontend\modules\saasapi\models\ApiReturn;
use frontend\modules\saasapi\controllers\ApiController;
use frontend\modules\saasapi\models\OrgCompanyConsign;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ConsignController extends ApiController
{
    public $tokenActions = [];


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
            ->Where(['a.company_id'=>$this->_orgCompany['company_id'],'a.is_del'=>0]);
        if(isset($param['c_type']) && $param['c_type']){ //类型
            $row->andWhere(['a.c_type' => $param['c_type']]);
        }
        if(isset($param['uid']) && $param['uid']){ //用户
            $row->andWhere(['a.u_id' => $param['uid']]);
        }
        $data['total'] = $row->count();
        $data['list'] = $row->orderBy('a.ctime desc')->groupBy('a.c_id')->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();
        if($data){
            return ApiReturn::success('获取成功',$data);
        }else{
            return ApiReturn::noData('没有更多了');
        }

    }

    /**
     * 保存委托信息
     */
    public function actionAddconsign()
    {
        $param = Yii::$app->request->post();

        if (Yii::$app->request->isPost){
            if(isset($param['c_id']) && $param['c_id']){
                //$house_id = $param['house_id'];
                /*$model = CompanyShopHouse::findOne($param['house_id']);
                $param['py_zf_js'] = isset($param['py_zf_js']) ? join(',', $param['py_zf_js']) : "";
                $param['other_tags'] = isset($param['other_tags']) ? join(',', $param['other_tags']) : "";
                $model->uid = 2;
                $model->utime = date('Y-m-d H:i:s');
                if ($model->load($param,'') && $model->save()) {
                    return ApiReturn::success('保存成功');
                }else{
                    return ApiReturn::wrongParams('保存失败');
                }*/
            }else{
                $model = new OrgCompanyConsign();
                $model->u_id = $param['u_id'];
                $model->user_name = trim($param['user_name']);
                $model->user_phone = trim($param['user_phone']);
                $model->community_name = isset($param['community_name']) ? trim($param['community_name']) : '';
                $model->house_area = isset($param['house_area']) ? trim($param['house_area']) : 0;
                $model->sell_price = isset($param['sell_price']) ? trim($param['sell_price']) : 0;

                $model->lease_area_from = isset($param['lease_area_from']) ? trim($param['lease_area_from']) : 0;
                $model->lease_area_to = isset($param['lease_area_to']) ? trim($param['lease_area_to']) : 0;
                $model->lease_price_from = isset($param['lease_price_from']) ? trim($param['lease_price_from']) : 0;
                $model->lease_price_to = isset($param['lease_area_to']) ? trim($param['lease_price_to']) : 0;

                $model->buy_district = isset($param['buy_district']) ? trim($param['buy_district']) : '';
                $model->buy_area_from = isset($param['buy_area_from']) ? intval(trim($param['buy_area_from'])) : 0;
                $model->buy_area_to = isset($param['buy_area_to']) ? intval(trim($param['buy_area_to'])) : 0;
                $model->buy_price_from = isset($param['buy_price_from']) ? intval(trim($param['buy_price_from'])) : 0;
                $model->buy_price_to = isset($param['buy_price_to']) ? intval(trim($param['buy_price_to'])) : 0;

                $model->hire_area = isset($param['hire_area']) ? intval(trim($param['hire_area'])) : 0;
                $model->hire_price = isset($param['hire_price']) ? intval(trim($param['hire_price'])) : 0;
                $model->c_type = $param['c_type'];

                $model->company_id = $this->_orgCompany['company_id'];
                $model->ctime = date('Y-m-d H:i:s');
                $model->utime = date('Y-m-d H:i:s');
                $model->is_del = 0;

                if(!$model->save()){         //保存不成功
                    $errors = $model->getErrors();
                    return ApiReturn::wrongParams('保存失败');
                }
            }
            return ApiReturn::success('保存成功');
        }
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