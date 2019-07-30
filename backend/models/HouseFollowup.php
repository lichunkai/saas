<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\HouseFollowupGii;
use common\models\gii\HouseGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class HouseFollowup extends HouseFollowupGii
{
	/**
	 * 添加房源跟进
	 * @param $param array 添加参数
	 * @param $user array 用户信息
	 * @return bool 添加是否成功
	*/
	public function updateHouseFollowup($param,$user){
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();//开启事物
			$model = new HouseFollowup();
			$fields = $model->attributeLabels();
			foreach ($param as $key=>$item){
				foreach ($fields as $k=>$v){
					if($key==$k){
							$model->$k = $item;
					}
				}
			}
			$model->c_id = $user['u_id'];
			$model->u_id = $user['u_id'];
			$model->chedan = $model->hf_notify_is_chedan ? $param['chedan'] : '';
			$model->utime = date('Y-m-d H:i:s',time());
			$model->ctime = date('Y-m-d H:i:s',time());
			$model->company_id = $user['company_id'];
			$model->auth_rid = $user['auth_rid'];
			$model->auth_sid = $user['auth_sid'];
			$model->auth_aid = $user['auth_aid'];
			$model->auth_baid = $user['auth_baid'];

			if($model->save()){
				if(!empty($param['hf_notify_user'])){
					$notify = new Notify();
					$notify->n_title = '房源提醒通知';
					$notify->n_content = $param['hf_notify_content'];
					$notify->n_u_id = $param['hf_notify_user'];
					$notify->n_time = $param['hf_notify_time'];
					$notify->n_is_read = 0;
					$notify->n_is_notify = 0;
					$notify->n_url = '/#/roomDetails/'.$param['sale_type'].'/'.$param['house_id'];
                    $notify->company_id = $user['company_id'];
					$notify->c_id = $user['u_id'];
					$notify->u_id = $user['u_id'];
					$notify->utime = date('Y-m-d H:i:s',time());
					$notify->ctime = date('Y-m-d H:i:s',time());
					$notify->auth_rid = $user['auth_rid'];
					$notify->auth_sid = $user['auth_sid'];
					$notify->auth_aid = $user['auth_aid'];
					$notify->auth_baid = $user['auth_baid'];
					if($notify->save()){
						$transaction->commit();
						return true;
					}else{
						$transaction->rollBack();
						var_dump($notify->getErrors());
						return false;
					}
				}else{
					$transaction->commit();
					return true;
				}
			}else{
				$transaction->rollBack();
				var_dump($model->getErrors());
				return false;
			}

		}


	public function delHouseHouseFollowup($hf_id, $user){
		try{
			$model = static::findOne($hf_id);
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
