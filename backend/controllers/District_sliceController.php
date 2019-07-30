<?php
namespace backend\controllers;

use backend\models\District_slice;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 片区控制器
 */
class District_sliceController extends AuthController
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
     *片区列表
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = District_slice::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'dt_id' => $param['dt_id']]);
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
            $count = District_slice::find()->where(['dts_name' => $post['dts_name'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->count();
            if ($count > 0) {
                return ApiReturn::wrongParams('片区名已经存在，请勿重复添加！');
            }
            $district_slice = new District_slice();
            $result = $district_slice->updateDistrict_slice($post, $this->_user());
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
            $district_slice = new District_slice();
            $result = $district_slice->updateDistrict_slice($post, $this->_user());
            if ($result) {
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改失败');
            }
        } else {
            return ApiReturn::wrongParams('修改失败');
        }
    }

    /*
     * 删除
     */
    public function actionDel()
    {
        $post = Yii::$app->request->post();
        if (empty($post['dts_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $district_slice = District_slice::find()->where(['dts_id'=>$post['dts_id'],'company_id'=>$this->_user['company_id']])->one();
        $district_slice->is_del = 1;
        $result = $district_slice->update();

        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}