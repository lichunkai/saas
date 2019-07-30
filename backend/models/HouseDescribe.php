<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\HouseDescribeGii;
use common\models\gii\HouseGii;
use common\models\gii\HouseImgGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class HouseDescribe extends HouseDescribeGii
{
	/**
	 * 添加房源图片
	 * @param $param array 添加参数
	 * @param $user array 用户信息
	 * @return bool 添加是否成功
	 */
	public function updateHouseDescribe($param, $user)
	{
		if (isset($param['hd_id']) && !empty($param['hd_id'])) {
			$model = static::findOne($param['hd_id']);
			if($model){
				$fields = $model->attributeLabels();
				foreach ($param as $key=>$item){
					foreach ($fields as $k=>$v){
						if($key==$k){
							$model->$k = $item;
						}
					}
				}
				$model->u_id = $user['u_id'];
				$model->utime = date('Y-m-d H:i:s',time());
				if($model->save()){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}

		}else{
			$model = new HouseDescribe();
			$fields = $model->attributeLabels();
			foreach ($param as $key => $item) {
				foreach ($fields as $k => $v) {
					if ($key == $k) {
						$model->$k = $item;
					}
				}
			}

			$model->auth_uid = $user['auth_uid'];
			$model->auth_rid = $user['auth_rid'];
			$model->auth_sid = $user['auth_sid'];
			$model->auth_aid = $user['auth_aid'];
			$model->auth_baid = $user['auth_baid'];
			$model->c_id = $user['u_id'];
			$model->u_id = $user['u_id'];
			$model->utime = date('Y-m-d H:i:s', time());
			$model->ctime = date('Y-m-d H:i:s', time());
			if ($model->save()) {
				return true;
			} else {
				var_dump($model->getErrors());
				return false;
			}
		}
	}

	public function delHouseDescribe($hd_id, $user){
		try{
			$model = static::findOne($hd_id);
			$model->u_id = $user['u_id'];
			$model->is_del =1;
			$model->utime = date('Y-m-d H:i:s', time());
			if ($model->save()) {
				return true;
			} else {
				var_dump($model->getErrors());
				return false;
			}
		}catch (Exception $e){
			return false;
		}

	}

}
