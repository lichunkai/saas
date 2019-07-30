<?php
namespace backend\controllers;

use backend\models\Commission;
use backend\models\Customer;
use backend\models\House;
use backend\models\SystemLog;
use common\helps\Upload;
use common\models\CommSetting;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;

/**
 * Common controller
 */
class CommsettingController extends Controller
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

	public function ActionGetcommsetting(){
		$param=Yii::$app->request->get();
		if(yii::$app->request->isGet){

		}

	}





}
