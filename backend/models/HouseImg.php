<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\HouseGii;
use common\models\gii\HouseImgGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class HouseImg extends HouseImgGii
{
	/**
	 * 添加房源图片
	 * @param $param array 添加参数
	 * @param $user array 用户信息
	 * @return bool 添加是否成功
	 */
	public function updateHouseImg($param, $user)
	{
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();//开启事物
		$TmpImgNum = 0;
		//删除对应的图片
		$model = new HouseImg();
		if ($model->deleteAll(['house_id' => $param['house_id']])===false) {
			$transaction->rollBack();
			return false;
		}

		foreach ($param['images'] as $key=>$image) {
			$model = new HouseImg();
			if (is_array($image)) {
				if($key>0&&$key<=11){
					$TmpImgNum++;
				}
				foreach ($image as $key=>$img){
					$modelImg = new HouseImg();
					$modelImg->house_id = $param['house_id'];
					$modelImg->hi_url = $img['hi_url'];
					$modelImg->hi_status = 0;
					$modelImg->hi_type = $img['hi_type'];
					$modelImg->hi_is_cover = $img['hi_is_cover'];
					$modelImg->c_id = $user['u_id'];
					$modelImg->u_id = $user['u_id'];
					$modelImg->utime = date('Y-m-d H:i:s', time());
					$modelImg->ctime = date('Y-m-d H:i:s', time());
						if (!$modelImg->save()) {
							$transaction->rollBack();
							var_dump($modelImg->getErrors());
							return false;
						}
				}
			}

		}
		if($TmpImgNum>=11){
			$house = House::findOne($param['house_id']);

			if(empty($house->is_images)){
				$house->is_images=1;
				$house->is_images_user=$user['u_id'];
				if(!$house->save()){
					$transaction->rollBack();
					var_dump($house->getErrors());
					return false;
				}
			}
		}



		$transaction->commit();
		return true;

	}

	public function delHouseImg($hi_id, $user)
	{
		try {
			$model = static::findOne($hi_id);
			$model->u_id = $user['u_id'];
			$model->is_del = 1;
			$model->utime = date('Y-m-d H:i:s', time());
			if ($model->save()) {
				return true;
			} else {
				var_dump($model->getErrors());
				return false;
			}
		} catch (Exception $e) {
			return false;
		}

	}

}
