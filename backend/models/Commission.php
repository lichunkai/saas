<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\CommissionGii;
use common\models\gii\HouseGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Commission extends CommissionGii
{
	/**
	 * 添加项目
	 */
	public function updateCommission($param,$user){
		if(isset($param['id']) && !empty($param['id'])){
			$model = static::findOne($param['id']);
			$model->house_id=trim($param['house_id']);
			$model->dailikaishi=trim($param['dailikaishi']);
			$model->dailijieshu=trim($param['dailijieshu']);
			$model->yejibili_baifenbi=trim($param['yejibili_baifenbi']);
			$model->yijiabiaozhun_xianjin=trim($param['yijiabiaozhun_xianjin']);
			$model->dailibiaozhun_baifenbi=trim($param['dailibiaozhun_baifenbi']);
			$model->dailibiaozhun_xianjin=trim($param['dailibiaozhun_xianjin']);
			$model->tuangoubiaozhun_baifenbi=trim($param['tuangoubiaozhun_baifenbi']);
			$model->taungoubiaozhun_xianjin=trim($param['taungoubiaozhun_xianjin']);
			$model->yejibili_baifenbi=trim($param['yejibili_baifenbi']);
			$model->yejibili_xianjin=trim($param['yejibili_xianjin']);
			$model->zhongjieyongjin_baifenbi=trim($param['zhongjieyongjin_baifenbi']);
			$model->zhongjieyongjin_xianjin=trim($param['zhongjiejiangjin_xianjin']);
			$model->zhongjiejiangjin_baifenbi=trim($param['zhongjiejiangjin_baifenbi']);
			$model->zhongjiejiangjin_xianjin=trim($param['zhongjiejiangjin_xianjin']);
			$model->remark=trim($param['remark']);
			$model->utime=date('Y-m-d H:i:s');
			$model->uid = $user['u_id'];
			$result = $model->save();
			if($result){
				return true;
			}else{
				return false;
			}
		}else{
			$model = new Commission();
			$model->house_id=trim($param['house_id']);
			$model->dailikaishi=trim($param['dailikaishi']);
			$model->dailijieshu=trim($param['dailijieshu']);
			$model->yejibili_baifenbi=trim($param['yejibili_baifenbi']);
			$model->yijiabiaozhun_xianjin=trim($param['yijiabiaozhun_xianjin']);
			$model->dailibiaozhun_baifenbi=trim($param['dailibiaozhun_baifenbi']);
			$model->dailibiaozhun_xianjin=trim($param['dailibiaozhun_xianjin']);
			$model->tuangoubiaozhun_baifenbi=trim($param['tuangoubiaozhun_baifenbi']);
			$model->taungoubiaozhun_xianjin=trim($param['taungoubiaozhun_xianjin']);
			$model->yejibili_baifenbi=trim($param['yejibili_baifenbi']);
			$model->yejibili_xianjin=trim($param['yejibili_xianjin']);
			$model->zhongjieyongjin_baifenbi=trim($param['zhongjieyongjin_baifenbi']);
			$model->zhongjieyongjin_xianjin=trim($param['zhongjiejiangjin_xianjin']);
			$model->zhongjiejiangjin_baifenbi=trim($param['zhongjiejiangjin_baifenbi']);
			$model->zhongjiejiangjin_xianjin=trim($param['zhongjiejiangjin_xianjin']);
			$model->remark=$param['remark'];
			$model->ctime=date('Y-m-d H:i:s');
			$model->utime=date('Y-m-d H:i:s');
			$model->cid = $user['u_id'];
			$model->uid = $user['u_id'];
			$model->is_del = 0;
			$result = $model->save();
			if($result){
				return true;
			}else{
				return false;
			}

		}
	}


	/**
	 *删除佣金方案
	*/
	public function delCommission($id,$user){
		$model = static::findOne($id);
		$model->is_del = 1;
		$model->uid = $user['u_id'];
		$model->utime = date('Y-m-d H:i:s');
		if(!$model->update()){
			return false;
		}else{
			return true;
		}
	}

	public static function getLastCommission($house_id){
		return  static::find()->where(['house_id'=>$house_id,'is_del'=>'0'])->select('id,house_id,dailijieshu')->orderBy(['dailijieshu'=>SORT_DESC])->limit('0,1')->asArray()->all();
	}

}
