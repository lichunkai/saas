<?php
namespace backend\controllers;

use backend\models\Salary_config_mingcheng;
use backend\models\Salary_config_mingcheng_yeji;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 学区控制器
 */
class Salary_configController extends AuthController
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
     *提成方案
     */
    public function actionIndex(){
        $param = Yii::$app->request->get();
        $page = isset($param["page"])&&$param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page-1)*$pagesize;
        $row=Salary_config_mingcheng::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
        $list=$row->limit($pagesize)->offset($start)->asArray()->all();
        $data['list'] = $list;
        $data['count'] = $row->count();
        return ApiReturn::success('查询成功',$data);
    }
    /*
     * 添加
     */
    public function actionAdd(){
        $post=Yii::$app->request->post();
        if(yii::$app->request->isPost && !empty($post)){
            $Salary_config=new Salary_config_mingcheng();
            $result=$Salary_config->updateSalary_config($post,$this->_user);
            if($result){
                return ApiReturn::success('保存成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
        }
    }

    /*
     * 删除
     */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(empty($post['scm_id'])){
            return ApiReturn::wrongParams('数据异常');
        }
        $count=Salary_config_mingcheng_yeji::find()->where(['scm_id'=>$post['scm_id'],'is_del'=>0,'company_id'=>$this->_user['company_id']])->count();
        if($count>0){
            return ApiReturn::wrongParams('该方案下，存在业绩方案，删除失败！');
        }
        $Salary_config = Salary_config_mingcheng::findOne($post['scm_id']);
        $Salary_config->is_del = 1;
        $result = $Salary_config->update();
        if($result){
            return ApiReturn::success('删除成功');
        }else{
            return ApiReturn::wrongParams('删除失败');
        }
    }
}