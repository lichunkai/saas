<?php
namespace backend\controllers;

use backend\models\Commission;
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
class CommissionController extends AuthController
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

	/**
	 * @return \common\models\json
	 * 获取佣金方案列表
	 */
   public function actionCommissionlist(){
	   $param = Yii::$app->request->get();
	   $page = isset($param["page"])&&$param["page"] ? $param["page"] : 1;
	   $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
	   $start = ($page-1)*$pagesize;
	   $row = Commission::find()->select('a.*,b.house_name')->alias('a')->leftJoin('ys_house as b','a.house_id=b.id')->where(['a.is_del'=>'0']);
	   if(isset($param["kw"]) && $param["kw"]){
		   $row->andWhere(['like','b.house_name',$param["kw"]]);
	   }
	   $list = $row->limit($pagesize)->offset($start)->asArray()->all();
	   foreach ($list as $key=>$value){
	   	    if(strtotime($value['dailijieshu'].' 23:59:59')<time()){
		        $list[$key]['status']='已过期';
	        }else{
		        $list[$key]['status']='有效';
	        }
	   }
	   $data['list'] = $list;
	   $data['count'] = $row->count();


	   return ApiReturn::success('查询成功',$data);

   }



   /**
    * 添加佣金方案及修改
    */
   public function actionCommissionedit(){
	   $post = Yii::$app->request->post();
	   if (Yii::$app->request->isPost){
	   	    //判断代理时间
			//得到最后一个代理结束时间
		   $lastCommission = Commission::getLastCommission($post['house_id']);

		   if(isset($lastCommission)&&!empty($lastCommission)){
			   if(strtotime($lastCommission[0]['dailijieshu'].' 23:59:59')>strtotime($post['dailikaishi'])){
				   return ApiReturn::wrongParams('方案时间冲突，最后一个方案的结束时间为：'.$lastCommission[0]['dailijieshu']);
			   }
		   }
		   $model = new Commission();
		   $result = $model->updateCommission($post,$this->_user());
		   $message = '添加';
		   if(isset($post['id']) && $post['id']){
			   $message = '更新';
		   }
		   if($result){
			   return ApiReturn::success($message.'成功');
		   }else{
			   return ApiReturn::wrongParams($message.'失败');
		   }
	   }
   }

   /**
    * 删除佣金方案
    */
   public function actionCommissiondel(){
	   $id = Yii::$app->request->post('id');
	   if(!$id){
		   return ApiReturn::wrongParams('参数有误');
	   }
	   $model = Commission::findOne($id);
	   $model->is_del=1;
	   $result = $model->save();
	   if($result){
		   return ApiReturn::success('删除成功');
	   }else{
		   return ApiReturn::success('删除失败');
	   }
   }





}
