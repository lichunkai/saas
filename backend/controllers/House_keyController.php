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
class House_keyController extends AuthController
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

	/***
	 * 获取钥匙列表
	 */
	public function actionGetindex(){
		$param = Yii::$app->request->post();
		$page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
		$pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
		$start = ($page - 1) * $pagesize;

		$row = HouseKeyGii::find()->alias('a')->select("a.*,b.*,e.hkl_user,c.u_name as deyaoshiren_name,d.u_name as jiechuyaoshiren_name")
			->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => '0','b.is_del'=>'0'])
			->leftJoin('zh_house as b','a.house_id=b.house_uuid')
			->leftJoin('zh_user as c','a.hk_deyaoshiren=c.u_id')
			->leftJoin('zh_user as d','a.hk_jiechuyaoshiren=d.u_id')
            ->leftJoin('zh_house_key_log as e','a.hk_id=e.hk_id');
		//判断房源状态
		if (isset($param["house_status"]) && $param["house_status"] !='') {  //房源状态 0=无效 1=有效 2=基他
			$row->andWhere(['b.house_status' => $param["house_status"]]);
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
		if (isset($param["hk_status"]) && $param["hk_status"]) {  //钥匙状态
			$row->andWhere(['a.hk_status' => $param["hk_status"]]);
		}

		if(isset($param['d_id'])&&$param['d_id']&&empty($param["u_id"])){
			$users = $this->_getUsersByDepartId($param['d_id']);
			$row->andWhere(['IN','a.hk_deyaoshiren',$users]);
		}

		if(isset($param["u_id"]) && $param["u_id"]){
            $row->andWhere(['a.hk_deyaoshiren' => $param["u_id"]]);
        }

		if (isset($param["daterange"]) && $param["daterange"]) {  //时间
			$row->andWhere(['between','a.ctime',$param["daterange"][0], $param["daterange"][1]]);
		}

		if (isset($param["villages"]) && $param["villages"]) {  //片区
			$row->andWhere(['b.village_id' => end($param["villages"])]);
		}
		if (isset($param["sale_type"]) && $param["sale_type"]) {  //房源类型
			$row->andWhere(['b.sale_type' => $param["sale_type"]]);
		}

		if (isset($param["danyuan"]) && $param["danyuan"]) {  //钥匙状态
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
		foreach ($list as $key=>$item){
			$list[$key]['hk_dian_text'] = $this->_getYaoshidian($item['hk_dian']);//处理钥匙店
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
			switch ($item['hk_status']) {
				case '1':
					$list[$key]['hk_status_text'] = '正常';
					break;
				case '2':
					$list[$key]['hk_status_text'] = '借出';
					break;
				case '3':
					$list[$key]['hk_status_text'] = '退还';
					break;
				case '4':
					$list[$key]['hk_status_text'] = '失效';
					$shixiaoren = User::find()->select('u_name,utime')->where(['u_id'=>$list[$key]['hkl_user']])->asArray()->one();
                    $list[$key]['hk_shixiaoren'] = $shixiaoren['u_name'];
                    $list[$key]['hk_shixiaoshijian'] = $shixiaoren['utime'];
					break;
				default:
					$list[$key]['hk_status_text'] = '未知';
					break;
			}
		}

		$data['list'] = $list;
		$data['count'] = $row->count();
		return ApiReturn::success('查询成功', $data);
	}

	/***
	 * 修改钥匙状态
	 * @return array|\common\models\json
	 */
	public function actionSetstatus(){
		$param = Yii::$app->request->post();
		if(isset($param['hk_id'])){
			$connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();//开启事物
//			$model = new HouseKeyGii();
//			$model->hk_id = $param['hk_id'];
			$data['hk_status'] = $param['hk_status'];
			if($param['hk_status']==2){
				$data['hk_jiechuyaoshiren'] = $param['hk_jiechuyaoshiren'];
			}
			if(HouseKeyGii::updateAll($data,['hk_id'=>$param['hk_id']])){
				//写日志
				$hkLog = new HouseKeyLogGii();
				$hkLog->hk_id = $param['hk_id'];
				$hkLog->company_id = $this->_user['company_id'];
				$hkLog->c_id = $hkLog->u_id = $this->_user['u_id'];
				$hkLog->ctime=$hkLog->utime = date('Y-m-d H:i:s',time());
				if($param['hk_status']==2){
					$hkLog->hkl_user = $param['hk_jiechuyaoshiren'];
					$hkLog->hkl_content = '借出钥匙';
				}elseif ($param['hk_status']==1){
                    $hkLog->hkl_user = $param['hk_jiechuyaoshiren'];
					$hkLog->hkl_content = '归还钥匙';
				}else{
					$hkLog->hkl_content = '未知操作';
				}
				if($hkLog->save()){
					$transaction->commit();
					return ApiReturn::success("操作成功");
				}else{
					$transaction->rollBack();
					return ApiReturn::wrongParams('保存失败1');
				}
			}else{
				$transaction->rollBack();
				return ApiReturn::wrongParams('保存失败2');
			}
		}else{
			return ApiReturn::wrongParams('参数错误');
		}
	}


	/***
	 * 钥匙日志
	 * @return array|\common\models\json
	 */
	public function actionHousekeylog(){
		$param = Yii::$app->request->post();
		if(isset($param['hk_id'])){
			$page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
			$pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
			$start = ($page - 1) * $pagesize;
			$row = HouseKeyLogGii::find()->alias('a')->select('a.*,b.u_name,c.d_name')
                ->leftJoin('zh_user as b','a.hkl_user=b.u_id')
                ->leftJoin('zh_depart as c','b.u_dept_id = c.d_id')
                ->where(['a.company_id'=>$this->_user['company_id'],'a.hk_id'=>$param['hk_id']]);
            $data['total'] = $row->count();
			$data['list'] = $row->orderBy('a.hkl_id DESC')->limit($pagesize)->offset($start)->asArray()->all();
			return ApiReturn::success('获取成功',$data);

		}else{
			return ApiReturn::wrongParams('参数错误');
		}
	}


	public function actionShixiao(){
		$post = Yii::$app->request->post();
		//判断是否需要审核
		$yaoshishixiaoshenhe = ZhSettingJuece::find()->where(['jsetting_shorthand'=>'yaoshishixiaoshenhe','company_id'=>$this->_user['company_id']])->asArray()->one();


		$status = "未知";
		$user = $this->_user();
		$param=[
			'u_id'=>$user['u_id'],
			'hk_id'=>$post['hk_id'],
			'company'=>$this->_user['company_id'],
			'hk_status'=>'4',
			'utime'=>date('Y-m-d H:i:s',time()),
		];

		if($yaoshishixiaoshenhe['val']==1){
			$shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand'=>'house_key_status_verify_user','company_id'=>$this->_user['company_id']])->asArray()->one();
			$zhuguan= $this->_getShengheren($this->_user,$shengheren['val']);

			if (!$zhuguan) {
				return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
			}
			$model = new Verify();
			$model->u_id=$user['u_id'];
			$model->company_id = $this->_user['company_id'];
			$model->v_post_user=$user['u_id'];
			$model->v_type='钥匙失效';
			$model->v_end_user=$zhuguan;
			$model->v_pass_func=serialize(['backend\models'.'\House','setKeyStatus']);
			$model->v_pass_param=serialize($param);
			$model->v_service_id=$post['house_id'];
			$model->v_service_sn=$post['house_sn'];
			$model->v_service_type=1;
			$model->v_content='修改钥匙状态为[失效],失效原因：'.isset($post['shixiaoyuanyin'])?$post['shixiaoyuanyin']:''.',备注：'.isset($post['shixiaoBeizhu'])?$post['shixiaoBeizhu']:"";
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
			if(House::setKeyStatus($param)){
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



/**
* 递归获取父节点
* @param $id
* @param $arr
* @return array
*/
	private function _getChildNode($id, &$arr)
	{
		$arr[] = $id;
		$ret = Depart::find()->where(['company_id'=>$this->_user['company_id'],'d_pid' => $id])->select('d_id')->asArray()->all();
		if (!empty($ret[0])) {
			foreach ($ret as $k => $node) {
				$arr[] = $node['d_id'];
				$this->_getChildNode($node['d_id'], $arr);
			}
		}
		return array_unique($arr);
	}

	private function _getUsersByDeparts($departs){
		$users =[];
		$result = User::find()->where(['company_id'=>$this->_user['company_id'],'is_del'=>'0'])->andWhere(['in','u_dept_id',$departs])->asArray()->all();
		foreach ($result as $item){
			$users[] = $item['u_id'];
		}
		return $users;
	}

	private function _getUsersByDepartId($d_id){
		$arr=[];
		$departs = $this->_getChildNode($d_id,$arr);
		return $this->_getUsersByDeparts($departs);
	}


}
