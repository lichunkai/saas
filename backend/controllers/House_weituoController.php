<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\District_region;
use backend\models\District_slice;
use backend\models\House;
use backend\models\HouseDescribe;
use backend\models\HouseFollowup;
use backend\models\HouseImg;
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
use common\models\gii\HouseWeituoGii;
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
class House_weituoController extends AuthController
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

	public function actionGetsetting()
    {
        $setting = ZhSettingBase::find()->where(['company_id'=>$this->_user['company_id'],'base_shorthand'=>'weituo_invalid_reason'])->asArray()->one();
        $data['weituoshixiao'] = json_decode($setting['base_desp'],true);
        return ApiReturn::success('查询成功', $data);
    }

    /***
	 * 获取委托列表
	 */
	public function actionGetindex(){
		$param = Yii::$app->request->post();
		$page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
		$pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
		$start = ($page - 1) * $pagesize;

		$row = HouseWeituoGii::find()->alias('a')->select("a.*,b.*,c.u_name,d.d_name")

			->leftJoin('zh_house as b','a.house_id=b.house_uuid')
			->leftJoin('zh_user as c','a.hw_u_id=c.u_id')
			->leftJoin('zh_depart as d','a.hw_d_id=d.d_id')
		->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => '0','b.is_del'=>'0']);
		//判断房源状态
		if (isset($param["house_status"]) && $param["house_status"] !='') {  //房源状态 0=无效 1=有效 2=基他
			$row->andWhere(['b.house_status' => $param["house_status"]]);
		} else {
			$row->andWhere(['b.house_status' => '1']);
		}
		if(!empty($param["daikan"])){
			switch($param["daikan"]){
				case 3:
					$sj = strtotime("-3 day");
					$row->andWhere(['>', 'unix_timestamp(b.daikanshijian)', $sj]);
					$row->andWhere(['>=', 'b.daikancishu',1]);
					break;
				case 7:
					$sj = strtotime("-7 day");
					$row->andWhere(['>', 'unix_timestamp(b.daikanshijian)', $sj]);
					$row->andWhere(['>=', 'b.daikancishu', 1]);
					break;
				case 15:
					$sj = strtotime("-15 day");
					$row->andWhere(['>', 'unix_timestamp(b.daikanshijian)', $sj]);
					$row->andWhere(['>=', 'b.daikancishu', 1]);
					break;
			}

		}
		if(!empty($param["genjin"])){
			switch($param["genjin"]){
				case 3:
					$sj = strtotime("-3 day");
					$row->andWhere("unix_timestamp(b.quanyuangenjin)>$sj or unix_timestamp(b.weihurengenjin)>$sj");
					$row->andWhere(['>=', 'b.genjincishu', 1]);
					break;
				case 7:
					$sj = strtotime("-7 day");
					$row->andWhere("unix_timestamp(b.quanyuangenjin)>$sj or unix_timestamp(b.weihurengenjin)>$sj");
					$row->andWhere(['>=', 'b.genjincishu', 1]);
					break;
				case 15:
					$sj = strtotime("-15 day");
					$row->andWhere("unix_timestamp(b.quanyuangenjin)>$sj or unix_timestamp(b.weihurengenjin)>$sj");
					$row->andWhere(['>=', 'b.genjincishu', 1]);
					break;
			}

		}
		if (isset($param["hw_status"]) && $param["hw_status"]) {  //委托状态
			$row->andWhere(['a.hw_status' => $param["hw_status"]]);
		}

		if(isset($param["u_id"]) && $param["u_id"]){
            $row->andWhere(['a.hw_u_id' => $param["u_id"]]);
        }

		if (isset($param["daterange"]) && $param["daterange"]) {  //时间
			$row->andWhere(['between','a.ctime',$param["daterange"][0], $param["daterange"][1]]);
		}

		if (isset($param["villages"]) && $param["villages"]) {  //片区
			$row->andWhere(['b.village_id' => end($param["villages"])]);
		}
		if (isset($param["sale_type"]) && $param["sale_type"]) {  //片区
			$row->andWhere(['b.sale_type' => $param["sale_type"]]);
		}

		if (isset($param["danyuan"]) && $param["danyuan"]) {  //委托状态
			$row->andWhere(['or','b.danyuan_name='.$param["danyuan"].' or b.fanghao_name='.$param["danyuan"]]);
		}
		if (!empty($param['paixu'])) {
			switch ($param['paixu']) {
				case 1:
					$row->orderBy('b.genjincishu ASC');
					break;
				case 2:
					$row->orderBy('b.genjincishu DESC');
					break;
				case 3:
					$row->orderBy('b.daikancishu ASC');
					break;
				case 4:
					$row->orderBy('b.daikancishu DESC');
					break;
				case 5:
					$row->orderBy('a.ctime ASC');
					break;
				case 6:
					$row->orderBy('a.ctime DESC');
					break;

			}
		} else {
			$row->orderBy('a.utime DESC');
		}

		$list = $row->limit($pagesize)->offset($start)->asArray()->all();
//		echo $list->createCommand()->getRawSql();die;
		foreach ($list as $key=>$item){
			$list[$key]['genjincishu']=$item['genjincishu'];
			$list[$key]['daikancishu']=$item['daikancishu'];
			switch ($item['house_status']) {
				case '1':
					$list[$key]['house_status_text'] = '有效';
					break;
				case '0':
					$list[$key]['house_status_text'] = '无效';
					break;
				case '2':
					$list[$key]['house_status_text'] = '其他';
					break;
				case '3':
					$list[$key]['house_status_text'] = '撤单';
					break;
                case '4':
                    $list[$key]['house_status_text'] = '成交';
                    break;
				default:
					$list[$key]['house_status_text'] = '未知';
					break;
			}
            switch ($item['hw_status']) {
                case '1':
                    $list[$key]['hw_status']=$item['hw_status'];
                    break;
                case '9':
                    $list[$key]['hw_status']=$item['hw_status'];
                    $shixiaoshijian = Verify::find()->select('utime')->where(['v_service_sn'=>$item['house_sn'],'v_type'=>'委托失效','v_status'=>'1'])->asArray()->one();
                    $list[$key]['hw_shixiaoshijian']=$shixiaoshijian['utime'];
                    break;
                default:
                    $list[$key]['hw_status'] = '未知';
                    break;
            }
		}
		$data['list'] = $list;
		$data['count'] = $row->count();
		return ApiReturn::success('查询成功', $data);
	}

	public function actionShixiao(){
		$post = Yii::$app->request->post();
		//判断是否需要审核
		$weituoshixiaoshenhe = ZhSettingJuece::find()->where(['jsetting_shorthand'=>'weituoshixiaoshenhe','company_id'=>$this->_user['company_id']])->asArray()->one();
		$status = "未知";
		$user = $this->_user;
		$param=[
			'u_id'=>$user['u_id'],
			'hw_id'=>$post['hw_id'],
			'company_id'=>$user['company_id'],
			'hw_invalid_uname'=>$user['u_name'],
			'hw_status'=>'9',
			'hw_invalid_reason'=>$post['shixiaoyuanyin'],
			'hw_invalid_remark'=>$post['shixiaoBeizhu'],
			'utime'=>date('Y-m-d H:i:s',time()),
		];

		if($weituoshixiaoshenhe['val']==1){
			$shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand'=>'weituo_status_verify_user','company_id'=>$this->_user['company_id']])->asArray()->one();
			$zhuguan= $this->_getShengheren($this->_user,$shengheren['val']);

			if (!$zhuguan) {
				return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
			}
			$model = new Verify();
			$model->u_id=$user['u_id'];
			$model->company_id = $user['company_id'];
			$model->v_post_user=$user['u_id'];
			$model->v_type='委托失效';
			$model->v_end_user=$zhuguan;
			$model->v_pass_func=serialize(['backend\models'.'\House','setWeituoStatus']);
			$model->v_pass_param=serialize($param);
			$model->v_service_id=$post['house_id'];
			$model->v_service_sn=$post['house_sn'];
			$model->v_service_type=1;
			$model->v_content='修改委托状态为[失效],失效原因：'.trim($post['shixiaoyuanyin']).',备注：'.trim($post['shixiaoBeizhu']);
			$model->v_status=0;
			$model->c_id=$model->u_id=$user['u_id'];
			$model->ctime=$model->utime=date('Y-m-d H:i:s',time());
			if($model->save()){
				return ApiReturn::success('失效申请已提交，等待审核');
			}else{
				var_dump($model->getErrors());
				return ApiReturn::wrongParams('失效申请失败2');
			}

		}else{
			if(House::setWeituoStatus($param)){
				return ApiReturn::success('失效成功');
			}else{
				return ApiReturn::wrongParams('失效失败1');
			}
		}
	}




	/**
	 * 递归获取父节点
	 * @param $id
	 * @param $arr
	 * @return array
	 */
	private function _getSubNode($id, &$arr)
	{
		$arr[] = $id;
		$ret = Depart::find()->where(['company_id'=>$this->_user['company_id'],'d_id' => $id])->select('d_pid')->asArray()->one();
		if (!empty($ret)) {
			if ($ret['d_pid'] != 0) {
				array_unshift($arr, $ret['d_pid']);
				$this->_getSubNode($ret['d_pid'], $arr);
			}
		}
		return $arr;
	}

	private function _getYaoshidian($id){
		$ret = Depart::find()->where(['company_id'=>$this->_user['company_id'],'d_id' => $id])->select('d_name')->asArray()->one();
		if($ret){
			return $ret['d_name'];
		}else{
			return '';
		}
	}
}
