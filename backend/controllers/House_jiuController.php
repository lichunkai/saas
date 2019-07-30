<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\District_region;
use backend\models\District_slice;
use backend\models\House;
use backend\models\HouseDescribe;
use backend\models\HouseFollowup;
use backend\models\HouseImg;
use backend\models\HouseJiu;
use backend\models\Notify;
use backend\models\SystemLog;
use backend\models\User;
use backend\models\Verify;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingHouse;
use backend\models\ZhSettingJuece;
use common\helps\Tools;
use common\helps\Upload;
use common\models\CommSetting;
use common\models\gii\HouseKeyGii;
use common\models\gii\HouseKeyLogGii;
use common\models\gii\HouseLogGii;
use common\models\gii\HouseMakeGii;
use common\models\gii\HousePhoneGii;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;

/**
 * Common controller
 */
class House_jiuController extends AuthController
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

	public function actionSetjiu(){
		if (Yii::$app->request->isPost) {
			$post = Yii::$app->request->post();
			try {
				$model = new HouseJiu();
				if ($model->updateHouseJiu($post, $this->_user())) {
					return ApiReturn::success('保存成功');
				} else {
					return ApiReturn::wrongParams('保存失败2');
				}
			} catch (Exception $exception) {
				return ApiReturn::codeError('保存失败1');
			}

		} else {
			return ApiReturn::wrongParams('保存失败');
		}
	}


}
