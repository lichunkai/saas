<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\HouseFollowupGii;
use common\models\gii\HouseGii;
use common\models\gii\NotifyGii;
use common\models\gii\VerifyGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Verify extends VerifyGii
{
	/***
	 * 判断是否可以提交资源的审核
	 * @param $serviceType
	 * @param $serviceId
	 * @return bool
	 */
	public static function verifyService($serviceType,$serviceId){
		$count = Verify::find()->where(['v_service_type'=>$serviceType,'v_service_id'=>$serviceId,'is_del'=>'0','v_status'=>'0'])->count();
		if($count>0){
			return false;
		}else{
			return true;
		}
	}

}
