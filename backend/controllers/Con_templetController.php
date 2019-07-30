<?php
namespace backend\controllers;

use backend\models\Agency;
use backend\models\Commission;
use backend\models\ConTemplet;
use backend\models\Customer;
use backend\models\Role;
use backend\models\CustomColumns;
use backend\models\Customer_daikan;
use backend\models\Customer_log;
use backend\models\Customer_tel;
use backend\models\District;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingQujian;
use backend\models\Depart;
use backend\models\House;
use backend\models\SystemLog;
use backend\models\User;
use backend\models\Customer_follow;
use backend\models\ZhSettingRequired;
use backend\models\ZhSettingJuece;
use backend\models\Verify;
use common\helps\Tools;
use common\helps\Upload;
use common\models\CommSetting;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use backend\models\District_region;
use backend\models\District_slice;

/**
 * Common controller
 */
class Con_templetController extends AuthController
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
	  * 模板列表
	  * @return array|\common\models\json
	  */
	public function actionGetindex()
	{
		$param = Yii::$app->request->get();
		$page = isset($param['page']) ? $param['page'] : 1;
		$pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
		$start = ($page - 1) * $pagesize;

		$row = ConTemplet::find()->where(['is_del' => 0,'company_id'=>$this->_user['company_id']]);
		if(!empty($param['zhuangtai'])){
			$row->andWhere(['con_templet_status'=>$param['zhuangtai']]);
		}
		$listdata['totalnum'] = $row->count();
		$listdata['list'] = $row->orderBy(['utime'=>SORT_DESC])->limit($pagesize)->offset($start)->asArray()->all();

		//$listdata['list'] = Yii::$app->LoadData->listButton($this->id, $listdata['list'], $this->_user);

		return ApiReturn::success('获取成功', $listdata);
	}

	/*
	 * 模板添加
	 * @return array|\common\models\json
	 */
	public function actionAdd()
	{
		$param = Yii::$app->request->post();
		$conTemplet = new ConTemplet();
		$conTemplet->c_id = $this->_user['u_id'];
		$conTemplet->u_id = $this->_user['u_id'];
		$conTemplet->company_id = $this->_user['company_id'];
		$conTemplet->ctime = date('Y-m-d H:i:s');
		$conTemplet->utime = date('Y-m-d H:i:s');
		if($conTemplet->load($param, '') && $conTemplet->save()){
			return ApiReturn::success('添加成功');
		}else{
			$errors = $conTemplet->getErrors();
			return ApiReturn::wrongParams(current($errors));
		}
	}

	/*
	 *模板更新
	 * @return array|\common\models\json
	 */
	public function actionEdit()
	{
		$param = Yii::$app->request->post();
		$conTemplet =  ConTemplet::findOne($param['con_templet_id']);
		$conTemplet->u_id = $this->_user['u_id'];
		$conTemplet->utime = date('Y-m-d H:i:s');
		//var_dump($param);die;
		if($conTemplet->load($param, '') && $conTemplet->save()){
			return ApiReturn::success('更新成功');
		}else{
			$errors = $conTemplet->getErrors();
			return ApiReturn::wrongParams(current($errors));
		}
	}

	/*
	 * 模板删除
	 * @return array|\common\models\json
	 */
	public function actionDel()
	{
		$id = Yii::$app->request->post('con_templet_id');
		if (!$id) {
			return ApiReturn::wrongParams('参数有误');
		}
		$model = ConTemplet::findOne($id);
		$model->is_del = 1;
		$model->utime = date('Y-m-d H:i:s');
		$result = $model->save();
		if ($result) {
			return ApiReturn::success('删除成功');
		} else {
			return ApiReturn::success('删除失败');
		}
	}

}
