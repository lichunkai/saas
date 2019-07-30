<?php
namespace backend\controllers;

use backend\models\District;
use backend\models\District_slice;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use common\models\gii\ComAreaGii;
use common\models\gii\ComDistrictGii;
use common\models\gii\ComVillageGii;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 行政区控制器
 */
class DistrictController extends AuthController
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
     *行政区列表
     * fang 2018年9月1日9:53:05修改
     * 修改后行政区无法修改添加及删除
     * 新政区直接读取区域表
     */
    public function actionAreaindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        //查看当前用户所在的城市
	    $city_id = $this->_user['city_id'];
	    if(!$city_id){
		    return ApiReturn::codeError('参数错误，请退出后重新登录');
	    }
        $row = ComAreaGii::find()->where(['is_del'=>0,'ar_p_id'=>$this->_user['city_id']]);
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();
//        var_dump($list);die;
        $data['list'] = $list;
        $data['count'] = $row->count();
        return ApiReturn::success('查询成功', $data);
    }

    /**
     * 片区列表
    */
    public function actionDtsindex(){
    	//查看对应的列表
	    $param = Yii::$app->request->get();
	    $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
	    $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
	    $start = ($page - 1) * $pagesize;
	    $ar_id = $param['ar_id'];
	    if(!$ar_id){
		    return ApiReturn::codeError('参数错误');
	    }
	    $row = ComDistrictGii::find()->where(['is_del'=>0,'area_id'=>$ar_id])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.$this->_user['company_id']]);
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


            $dts = new ComDistrictGii();
            $count = ComDistrictGii::find()->where(['is_del'=>0,'dts_name'=>$post['dts_name']])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.$this->_user['company_id']])->count();
            if ($count > 0) {
                return ApiReturn::wrongParams('片区已经存在！');
            }

            $dts->company_id = $this->_user['company_id'];
            $dts->dts_address = $post['dts_address'];
            $dts->dts_name = $post['dts_name'];
            $dts->dts_status = 1;
            $dts->province_name = $this->_user['province_title'];
            $dts->province_id = $this->_user['province_id'];
            $dts->city_id = $this->_user['city_id'];
            $dts->city_name = $this->_user['city_title'];
            $dts->area_name = $post['area_name'];
            $dts->area_id = $post['area_id'];
            $dts->ctime = $dts->utime =date('Y-m-d H:i:s',time());
            $dts->c_id = $dts->u_id=$this->_user['u_id'];
            $result = $dts->save();
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
            $dts = ComDistrictGii::findOne($post['dts_id']);
            if(!$dts||$dts->company_id!=$this->_user['company_id']||$dts->dts_status!=1){
	            return ApiReturn::wrongParams('参数错误');
            }
	        $dts->dts_address = $post['dts_address'];
	        $dts->dts_name = $post['dts_name'];
	        $dts->dts_status = 1;
	        $dts->utime = date('Y-m-d H:i:s',time());
	        $dts->u_id = $this->_user['u_id'];
            if ($dts->save()) {
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
        if (empty($post['dts_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
        $count = ComDistrictGii::find()->where(['is_del'=>0,'dts_id'=>$post['dts_id'],'dts_status'=>'1','company_id'=>$this->_user['company_id']])->count();
        if ($count <=0) {
            return ApiReturn::wrongParams('数据异常');
        }
        //查看片区下有没有小区
	    $communityCount = ComVillageGii::find()->where(['is_del'=>0,'dts_id'=>$post['dts_id']])->count();
        if($communityCount>0){
	        return ApiReturn::wrongParams('片区下存在小区，无法删除');
        }
        $dts =  ComDistrictGii::findOne($post['dts_id']);
	    $dts->utime = date('Y-m-d H:i:s',time());
	    $dts->u_id = $this->_user['u_id'];
	    $dts->is_del=1;
        $result = $dts->save();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }
}