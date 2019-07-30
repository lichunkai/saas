<?php
namespace backend\controllers;


use backend\models\Depart;
use backend\models\User;
use backend\models\Verify;
use common\helps\Tools;
use Yii;
use common\models\ApiReturn;
use yii\rest\Controller;

/**
 * Common controller
 */
class WorkverifyController extends AuthController
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

	public function actionGetindex()
    {
		$param = Yii::$app->request->post();
		$page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
		$pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
		$start = ($page - 1) * $pagesize;
		$row = Verify::find()->alias('a')->select('a.*,b.u_name as post_user,c.u_name as end_user,d.u_name as shiji_end_user')
			->leftJoin('zh_user as b','a.v_post_user=b.u_id')
			->leftJoin('zh_user as c','a.v_end_user=c.u_id')
			->leftJoin('zh_user as d','a.u_id=d.u_id')
			->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => '0']);
		if (isset($param["v_service_type"]) && $param["v_service_type"]) { //业务类型
			$row->andWhere(['a.v_service_type' => $param['v_service_type']]);
		}
		if (isset($param["v_status"]) && $param['v_status'] != '') { //审核状态
			$row->andWhere(['a.v_status' => $param['v_status']]);
		}
		if (isset($param["v_post_user"]) && $param["v_post_user"]) { //申请人
			$row->andWhere(['a.v_post_user' => $param['v_post_user']]);
		}
		if (isset($param["v_end_user"]) && $param["v_end_user"]) { //审核人
			$row->andWhere(['a.v_end_user' => $param['v_end_user']]);
		}else{

			//获取自己及自己的下属uid
			$userArr = $this->_getChildUser();
			array_push($userArr,$this->_user['u_id']);
//			$row->andWhere(['a.v_end_user' => $this->_user['u_id']]);
            $userArr = array_diff($userArr,[NULL]);
			$row->andWhere(['in','a.v_end_user',$userArr]);
		}

		if(isset($param['ctime_start'])&&!empty($param['ctime_end'])){ //申请时间

			$row->andWhere(['between','a.ctime',$param['ctime_start'],$param['ctime_end']]);
		}

		if(isset($param['utime_start'])&&!empty($param['utime_end'])){ //申请时间
			$row->andWhere(['between','a.utime',$param['utime_start'],$param['utime_end']]);
		}

		$list = $row->limit($pagesize)->offset($start)->orderBy('ctime DESC')->asArray()->all();
//        echo $list->createCommand()->getRawSql();die;
		foreach ($list as $key=>$item){
			switch ($item['v_service_type']) {
				case '1':
					$list[$key]['v_service_type_text'] = '房源';
					break;
				case '2':
					$list[$key]['v_service_type_text'] = '客源';
					break;
				case '3':
					$list[$key]['v_service_type_text'] = '成交';
					break;
				case '99':
					$list[$key]['v_service_type_text'] = '合同';
					break;
				default:
					$list[$key]['v_service_type_text'] = '未知';
					break;
			}
			$list[$key]['shijishenheren']='';
			switch ($item['v_status']) {
				case '0':
					$list[$key]['v_status_text'] = '等待审核';
					break;
				case '1':
					$list[$key]['v_status_text'] = '通过';
					$list[$key]['shijishenheren'] = $list[$key]['shiji_end_user'];
					break;
				case '9':
					$list[$key]['v_status_text'] = '驳回';
					$list[$key]['shijishenheren'] = $list[$key]['shiji_end_user'];
					break;
				default:
					$list[$key]['v_status_text'] = '未知';
					break;
			}
		}

		$data['list'] = $list;
		$data['count'] = $row->count();
		return ApiReturn::success('查询成功', $data);


	}


	public function actionGetsetting()
	{

		//部门数据
		$depart = Depart::find()->where(['is_del' => 0,'company_id'=>$this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
		$data['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

		//员工数据
		$principal = User::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
		$principal->andWhere('u_status=1 or u_status=2');
		$principal=$principal->asArray()->all();
		foreach ($principal as $key => $item){
			$data['users'][$item['u_dept_id']][] = $item;
		}

		//搜索数据
		$data['v_service_type'] = [
			['label' => '房源', 'value' => 1],
			['label' => '客源', 'value' => 2],
			['label' => '成交', 'value' => 3],
			['label' => '合同', 'value' => 99],
		];
//		$data['v_type'] = [
//			['label' => '房源状态审核'],
//			['label' => '客源状态审核'],
//		];
		$data['v_status'] = [
			['label' => '等待审核', 'value' => 0],
			['label' => '通过', 'value' => 1],
			['label' => '驳回）', 'value' => 9],
		];
		$data['v_status'] = [
			['label' => '等待审核', 'value' => 0],
			['label' => '通过', 'value' => 1],
			['label' => '驳回）', 'value' => 9],
		];

		return ApiReturn::success('查询成功', $data);
	}

	public function actionPass()
	{
		$param = Yii::$app->request->post();
		if (isset($param['v_id'])) {
			$verify = Verify::find()->where(['v_id' => $param['v_id']])->asArray()->one();
			if (!$verify || $verify['v_status'] != 0) {
				return ApiReturn::wrongParams('参数错误');
			}
//			if ($verify['v_end_user'] !== $this->_user['u_id']) {
//				return ApiReturn::wrongParams('权限验证失败');
//			}
			if (!empty($verify['v_pass_func'])) {
				//判断回调方法是否能执行

				$func = unserialize($verify['v_pass_func']);
				$params = unserialize($verify['v_pass_param']);
				if(!$func||!method_exists($func[0],$func[1])){
					if (Verify::updateAll(['v_status' => 1,'u_id' => $this->_user['u_id'],'utime'=>date('Y-m-d H:i:s',time())], ['v_id' => $param['v_id']])) {
						return ApiReturn::success('操作成功');
					} else {
						return ApiReturn::wrongParams('操作失败');
					}
				}else{
						$class = $func[0];
						$fu = $func[1];
					if ($class::$fu($params)) {
						if (Verify::updateAll(['v_status' => 1,'u_id' => $this->_user['u_id'],'utime'=>date('Y-m-d H:i:s',time())], ['v_id' => $param['v_id']])) {
							return ApiReturn::success('操作成功');
						} else {
							return ApiReturn::wrongParams('操作失败');
						}
					}
				}

			} else {
				return ApiReturn::wrongParams('参数错误');
			}
		}
	}


	public function actionReject()
	{
		$param = Yii::$app->request->post();
		if (isset($param['v_id'])) {
			$verify = Verify::find()->where(['v_id' => $param['v_id']])->asArray()->one();
			if (!$verify || $verify['v_status'] != 0) {
				return ApiReturn::wrongParams('参数错误');
			}
			if (!empty($verify['v_pass_func'])) {
				//判断回调方法是否能执行

				$func = unserialize($verify['v_reject_func']);
				$params = unserialize($verify['v_reject_param']);
				if(!$func||!method_exists($func[0],$func[1])){
					if (Verify::updateAll(['v_status' => 9,'u_id' => $this->_user['u_id'],'utime'=>date('Y-m-d H:i:s',time()),'v_reject_reason'=>$param['v_reject_reason']], ['v_id' => $param['v_id']])) {
						return ApiReturn::success('操作成功');
					} else {
						return ApiReturn::wrongParams('操作失败');
					}
				}else{
					$class = $func[0];
					$fu = $func[1];
					if ($class::$fu($params)) {
						if (Verify::updateAll(['v_status' => 9,'utime'=>date('Y-m-d H:i:s',time()),'u_id' => $this->_user['u_id'],'v_reject_reason'=>$param['v_reject_reason']], ['v_id' => $param['v_id']])) {
							return ApiReturn::success('操作成功');
						} else {
							return ApiReturn::wrongParams('操作失败');
						}
					}
				}

			} else {
				return ApiReturn::wrongParams('参数错误');
			}
		}
	}


	public function actionHasverify(){
		$u_id = $this->_user['u_id'];
		$count = Verify::find()->where(['v_end_user'=>$u_id,'v_status'=>'0','is_del'=>'0'])->count();
		if($count>0){
			return ApiReturn::success('查询成功', ['hasverify'=>true]);
		}else{
			return ApiReturn::success('查询成功', ['hasverify'=>false]);
		}
	}



	private function _getChildUser(){
		$d_id = $this->_user['u_dept_id'];
		$tmpArr = [];
		$dArr = $this->_getSubNode($d_id,$tmpArr);

		if(empty($dArr)){
			return [];
		}else{
			return $dArr;
		}

	}

    /**
     * 递归获取子节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getSubNode($id, &$arr)
    {
	    $ret = Depart::find()->where(['d_pid' => $id,'is_del'=>0])->select('d_id,d_principal_id')->asArray()->all();
	    if (!empty($ret[0])) {
		    foreach ($ret as $k => $node) {
			    $arr[] = $node['d_principal_id'];
			    $this->_getSubNode($node['d_id'], $arr);
		    }
	    }
	    return $arr;
    }


}
