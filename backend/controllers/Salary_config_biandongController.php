<?php
namespace backend\controllers;

use backend\models\Salary_config_biandong;
use backend\models\Depart;
use backend\models\User;
use backend\models\ZhSettingBase;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 工资变动控制器
 */
class Salary_config_biandongController extends AuthController
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
     *工资变动列表
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = Salary_config_biandong::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
        if (!empty($param["d_id"])) {
            $row->andWhere(['bumen_id' => $param["d_id"]]);
        }
        if (!empty($param["user"])) {
            $row->andWhere(['renyuan' => $param["user"]]);
        }
        if (!empty($param["biandongModel"])) {
            $row->andWhere(['biandongleixing' => $param["biandongModel"]]);
        }
        if (!empty($param['shijian'])) {
            $row->andWhere(['>=', 'unix_timestamp(ctime)', strtotime($param['shijian'][0])]);
            $row->andWhere(['<=', 'unix_timestamp(ctime)', strtotime($param['shijian'][1])]);
        }
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();
        //部门数据
        $depart = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        //员工数据
        $principal = User::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->all();
        foreach ($principal as $key => $item) {
            $data['peizhi']['users'][$item['u_dept_id']][] = $item;
        }
        $row = ZhSettingBase::find()->where(['base_id' => 1088]);
        $data['peizhi']['biandongleixing'] = $row->asArray()->all();
        foreach ($list as $key => $v) {
            if ($v['bumen_id']) {
                $depart = Depart::find()->where(['d_id' => $v['bumen_id'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
                $list[$key]['bumen'] = $depart['d_name'];
            }
            if ($v['renyuan']) {
                $user = User::find()->where(['u_id' => $v['renyuan'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
                $list[$key]['renyuan'] = $user['u_name'];
            }
            $date = date_create($v['biandongriqi']);
            $list[$key]['biandongriqi'] = date_format($date, "Y-m-d");
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
            $Salary_config_biandong = new Salary_config_biandong();
            $result = $Salary_config_biandong->updateSalary_config_biandong($post, $this->_user);
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
            $Salary_config_biandong = new Salary_config_biandong();
            $result = $Salary_config_biandong->updateSalary_config_biandong($post, $this->_user);
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
        if (empty($post['scb_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $Salary_config_biandong = Salary_config_biandong::find()->where(['scb_id'=>$post['scb_id'],'company_id'=>$this->_user['company_id']])->one();
        $Salary_config_biandong->is_del = 1;
        $result = $Salary_config_biandong->update();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}