<?php
namespace backend\controllers;

use backend\models\Salary_config_mingcheng_yeji;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 学区控制器
 */
class Salary_config_yejiController extends AuthController
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
        if (empty($param["scm_id"])) {
            return ApiReturn::wrongParams('scm_id,不能为空！');
        }
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'scm_id' => $param["scm_id"]]);
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();
        foreach ($list as $key => $v) {
            if ($list[$key]['zhuangtai'] == 0) {
                $list[$key]['zhuangtai'] = "未设置";
            } else {
                $list[$key]['zhuangtai'] = "已设置";
            }
        }
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
            $Salary_config_mingcheng_yeji = new Salary_config_mingcheng_yeji();
            $result = $Salary_config_mingcheng_yeji->updateSalary_config_yeji($post, $this->_user);
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
     * 添加
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost && !empty($post)) {
            $Salary_config_mingcheng_yeji = new Salary_config_mingcheng_yeji();
            $result = $Salary_config_mingcheng_yeji->updateSalary_config_yeji($post, $this->_user);
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
        if (empty($post['scmy_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $Salary_config = Salary_config_mingcheng_yeji::findOne($post['scmy_id']);
        $Salary_config->is_del = 1;
        $result = $Salary_config->update();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}