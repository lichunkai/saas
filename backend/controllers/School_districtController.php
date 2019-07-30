<?php
namespace backend\controllers;

use backend\models\School;
use backend\models\School_district;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 学区控制器
 */
class School_districtController extends AuthController
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
     *绑定小区列表
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = School_district::find()->select('a.*,b.village_name,b.dts_id,b.dts_name,c.area_id,c.area_name')->alias('a')
	        ->leftJoin('com_village as b','a.rn_id=b.village_id')
	        ->leftJoin('com_district as c','b.dts_id=c.dts_id')
	        ->where(['a.is_del'=>0,'a.company_id'=>$this->_user['company_id'], 'a.s_id' => $param["s_id"]]);
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
            $count = School_district::find()->where(['rn_id' => $post['rn_id'], 's_id' => $post['s_id'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->count();
            if ($count > 0) {
                return ApiReturn::wrongParams('小区已经与学区绑定，请勿重复添加！');
            }
            $School_district = new School_district();
            $result = $School_district->updateDistrict($post, $this->_user());
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
            $School_district = new School_district();
            $result = $School_district->updateDistrict($post, $this->_user());
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
        if (empty($post['sd_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $district = School_district::find()->where(['sd_id'=>$post['sd_id'],'company_id'=>$this->_user['company_id']])->one();
        $district->is_del = 1;
        $result = $district->update();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}