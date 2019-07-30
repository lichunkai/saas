<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\HouseGii;
use common\models\gii\HouseImgGii;
use common\models\gii\HouseJiuGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class HouseJiu extends HouseJiuGii
{
	/**
	 * 添加九要素
	 * @param $param array 添加参数
	 * @param $user array 用户信息
	 * @return bool 添加是否成功
	 */
	public function updateHouseJiu($param, $user)
	{

		if(isset($param['hjy_id'])&&!empty($param['hjy_id'])){
			$model = static::findOne($param['hjy_id']);
			if ($model) {
				$fields = $model->attributeLabels();
				foreach ($param as $key => $item) {
					foreach ($fields as $k => $v) {
						if ($key == $k) {
							$model->$k = $item;
						}
					}
				}

				if(isset($param['peitao'])){
					if(isset($param['xcx'])&&!empty($param['xcx'])){
						$param['peitao']=explode(",",$param['peitao']);
					}
					$model->peitao = json_encode($param['peitao'],JSON_UNESCAPED_UNICODE);
				}

				$model->u_id = $user['u_id'];
				$model->utime = date('Y-m-d H:i:s', time());
				if (!$model->save()) {
					var_dump($model->getErrors());
					return false;
				}

				return true;

			}else{
				return false;
			}
		}else{
			$model = new HouseJiuGii();
			$fields = $model->attributeLabels();
			foreach ($param as $key => $item) {
				foreach ($fields as $k => $v) {
					if ($key == $k) {
						$model->$k = $item;
					}
				}
			}
			if(isset($model->peitao)){
				if(isset($param['xcx'])&&!empty($param['xcx'])){
					$param['peitao']=explode(",",$param['peitao']);
				}
				$model->peitao = json_encode($param['peitao'],JSON_UNESCAPED_UNICODE);
			}
			$model->c_id = $user['u_id'];
			$model->u_id = $user['u_id'];
			$model->company_id = $user['company_id'];
			$model->utime = date('Y-m-d H:i:s', time());
			$model->ctime = date('Y-m-d H:i:s', time());
			if (!$model->save()) {
				var_dump($model->getErrors());
				return false;
			}

			return true;
		}



	}


}
