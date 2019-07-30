<?php
namespace backend\controllers;

use backend\models\School;
use backend\models\Depart;
use backend\models\District_slice;
use backend\models\District_region;
use backend\models\School_district;
use common\controllers\CommonController;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 学区控制器
 */
class SchoolController extends AuthController
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
     *学区列表
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = School::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
        if (!empty($param["kw"])) {
            $kw = $param["kw"];
            $row->andWhere("`s_name` like '%$kw%' or `s_address` like '%$kw%' ");
        }
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();
        $data['list'] = $list;
        //获取片区小区树信息
	    $data['dts'] = CommonController::getDtsList($this->_user['city_id'],$this->_user['company_id']);
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
            $district = new School();
            $result = $district->updateDistrict($post, $this->_user());
            if ($result) {
                return ApiReturn::success('保存成功');
            } else {
                return ApiReturn::wrongParams('保存失败');
            }
        } else {
            return ApiReturn::wrongParams('保存失败');
        }
    }

    /*
     * 编辑
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost && !empty($post)) {
            $School = new School();
            $result = $School->updateDistrict($post, $this->_user());
            if ($result) {
                return ApiReturn::success('保存成功');
            } else {
                return ApiReturn::wrongParams('保存失败');
            }
        } else {
            return ApiReturn::wrongParams('保存失败');
        }
    }

    /*
     * 删除
     */
    public function actionDel()
    {
        $post = Yii::$app->request->post();
        if (empty($post['s_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $count = School_district::find()->where(['s_id' => $post['s_id'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->count();
        if ($count > 0) {
            return ApiReturn::wrongParams('该学区下，存在小区，删除失败！');
        }
        $district = School::findOne($post['s_id']);
        $district->is_del = 1;
        $result = $district->update();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}