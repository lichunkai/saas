<?php
namespace backend\controllers;

use backend\models\District_region;
use backend\models\District;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 小区控制器
 */
class District_regionController extends AuthController
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

    /*
     *小区列表
     */
    public function actionIndex()
    {
        //行政区
        $row = District::find()->select(['dt_id as value', 'dt_area_name as label'])->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->with(
            ['children' => function ($query) {
                $query->select(['dt_id', 'dts_id as value', 'dts_name as label'])->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
            }]
        );
        $data['district'] = $row->asArray()->all();

        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = District_region::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
        if (!empty($param["dt_id"])) {
            $row->andWhere(['dt_id' => $param["dt_id"]]);
        }
        if (!empty($param["dts_id"])) {
            $row->andWhere(['dts_id' => $param["dts_id"]]);
        }
        if (!empty($param["kw"])) {
            $kw = $param["kw"];
            $row->andWhere("`rn_name` like '%$kw%'");
        }
        $row->with(['district' => function ($query) {
            $query->select(['dt_id', 'dt_province_name', 'dt_city_name', 'dt_area_name'])->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
        }])
            ->with(['district_slice' => function ($query) {
                $query->select(['dts_id', 'dts_name', 'dts_address'])->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
            }]);
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();
        $data['list'] = $list;
        $data['count'] = $row->count();
        return ApiReturn::success('查询成功', $data);
    }

    /*
     * 添加
     */
    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost && !empty($post)) {
            $count = District_region::find()->where(['rn_name' => $post['rn_name'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->count();
            if ($count > 0) {
                return ApiReturn::wrongParams('小区名已经存在，请勿重复添加！');
            }
            $district_slice = new District_region();
            $result = $district_slice->updateDistrict_region($post, $this->_user());
            if ($result) {
                return ApiReturn::success('添加成功');
            } else {
                return ApiReturn::wrongParams('添加失败');
            }
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /*
     * 修改
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost && !empty($post)) {
            $district_slice = new District_region();
            $result = $district_slice->updateDistrict_region($post, $this->_user());
            if ($result) {
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改成功');
            }
        } else {
            return ApiReturn::wrongParams('修改成功');
        }
    }

    /*
     * 删除
     */
    public function actionDel()
    {
        $post = Yii::$app->request->post();
        if (empty($post['rn_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $district_region =District_region::find()->where(['rn_id'=>$post['rn_id'],'company_id'=>$this->_user['company_id']])->one();
        $district_region->is_del = 1;
        $result = $district_region->update();

        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}