<?php
namespace backend\controllers;

use backend\models\District_region;
use backend\models\District;
use common\controllers\CommonController;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use common\models\gii\ComDistrictGii;
use common\models\gii\ComVillageGii;
use common\models\gii\LianjiaXiaoquGii;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 小区控制器
 */
class VillageController extends AuthController
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
        $dts = CommonController::getDtsList($this->_user['city_id'],$this->_user['company_id']);
	    $data['district'] =$dts;

        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = ComVillageGii::find()->select('a.*,b.area_id,b.area_name')->alias('a')
	        ->leftJoin('com_district as b','a.dts_id=b.dts_id')
	        ->where(['a.is_del'=>0])
	        ->andWhere(['OR','a.village_status=0','a.village_status=1 AND a.company_id='.$this->_user['company_id']]);
        if (!empty($param["dts_id"])) {
            $row->andWhere(['a.dts_id' => $param["dts_id"]]);
        }
        if (!empty($param["kw"])) {
            $kw = $param["kw"];
            $row->andWhere("a.village_name like '%$kw%'");
        }
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

	        $count = ComVillageGii::find()->where(['is_del'=>0,'village_name'=>$post['village_name']])->andWhere(['OR','village_status=0','village_status=1 AND company_id='.$this->_user['company_id']])->count();
	        if ($count > 0) {
		        return ApiReturn::wrongParams('小区已经存在！');
	        }
	        //通过dtsid查片区
	        $dts = ComDistrictGii::findOne($post['dts_id']);
	        if(!$dts){
		        return ApiReturn::wrongParams('参数错误！');
	        }

	        $cmu = new ComVillageGii();
	        $cmu->company_id = $this->_user['company_id'];
	        $cmu->village_name = $post['village_name'];

	        $cmu->dts_id =$dts->dts_id;
	        $cmu->dts_name = $dts->dts_name;
	        $cmu->village_price = $post['village_price'];
	        $cmu->village_address = $post['village_address'];
	        $cmu->ctime = $cmu->utime =date('Y-m-d H:i:s',time());
	        $cmu->c_id = $cmu->u_id=$this->_user['u_id'];

	        $cmu->village_status = 1;
	        $cmu->is_del = 0;
            if ($cmu->save()) {
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
		    $cmu = ComVillageGii::findOne($post['village_id']);
		    if(!$cmu||$cmu->company_id!=$this->_user['company_id']||$cmu->village_status!=1){
			    return ApiReturn::wrongParams('参数错误');
		    }

		    //通过dtsid查片区
		    $dts = ComDistrictGii::findOne($post['dts_id']);
		    if(!$dts){
			    return ApiReturn::wrongParams('参数错误！');
		    }
		    $cmu->village_name = $post['village_name'];
		    $cmu->dts_id =$dts->dts_id;
		    $cmu->dts_name = $dts->dts_name;
		    $cmu->village_price = $post['village_price'];
		    $cmu->village_address = $post['village_address'];
		    $cmu->utime = date('Y-m-d H:i:s',time());
		    $cmu->u_id = $this->_user['u_id'];
		    if ($cmu->save()) {
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
        if (empty($post['village_id'])) {
            return ApiReturn::wrongParams('数据异常');
        }
	    $count = ComVillageGii::find()->where(['is_del'=>0,'village_id'=>$post['village_id'],'village_status'=>'1','company_id'=>$this->_user['company_id']])->count();
	    if ($count <=0) {
		    return ApiReturn::wrongParams('数据异常');
	    }
	    $cmu = ComVillageGii::findOne($post['village_id']);
	    $cmu->is_del=1;
        if ($cmu->save()) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }

    /**
     * 通过片区查看小区
    */
    public function actionGetvillage(){
	    $param = Yii::$app->request->get();
	    $row = ComVillageGii::find()
		    ->where(['is_del'=>0])
		    ->andWhere(['OR','village_status=0','village_status=1 AND company_id='.$this->_user['company_id']]);
	    if (!empty($param["dts_id"])) {
		    $row->andWhere(['dts_id' => $param["dts_id"]]);
	    }
	    $list = $row->asArray()->all();
	    $data['list'] = $list;
	    $data['count'] = $row->count();
	    return ApiReturn::success('查询成功', $data);
    }







}