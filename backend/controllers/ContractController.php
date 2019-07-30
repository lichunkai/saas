<?php
namespace backend\controllers;

use backend\models\Agency;
use backend\models\Commission;
use backend\models\ConTemplet;
use backend\models\Customer;
use backend\models\Role;
use backend\models\Notify;
use backend\models\CustomColumns;
use backend\models\Customer_daikan;
use backend\models\Customer_log;
use backend\models\Customer_tel;
use backend\models\District;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingQujian;
use backend\models\Depart;
use backend\models\Contractlist;
use backend\models\House;
use backend\models\SystemLog;
use backend\models\User;
use backend\models\Customer_follow;
use backend\models\ZhSettingRequired;
use backend\models\ZhSettingJuece;
use backend\models\Verify;
use common\helps\Tools;
use common\helps\Upload;
use common\models\CommSetting;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use backend\models\District_region;
use backend\models\District_slice;

/**
 * Common controller
 */
class ContractController extends AuthController
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


	/*
	  * 模板列表
	  * @return array|\common\models\json
	  */
	public function actionGetindex()
	{
		$param = Yii::$app->request->get();
		$page = isset($param['page']) ? $param['page'] : 1;
		$pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
		$start = ($page - 1) * $pagesize;
		/*
* 初始化部门
*/
		$u_dept_id=$this->_user['u_dept_id'];
		$depart = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();

		//获取子集的树
		$departlist = Customer::getTree($depart, $u_dept_id);
		//把 树转换为列
		$departlist = Customer::setlistname($departlist);
		//获取父
		$departzhuyaode = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'd_id' => $u_dept_id])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();

		//子集加上父集
		if (!empty($departlist) && !empty($departzhuyaode)) {
			$departzhuyaode[0]['d_pid'] = 0;
			$departlist = array_merge($departlist, $departzhuyaode);
		} else if (!empty($departzhuyaode)) {
			$departzhuyaode[0]['d_pid'] = 0;
			$departlist = $departzhuyaode;
		}
		$benzuin='';
		//所有部门用逗号隔开
		foreach($departlist as $b){
			$benzuin .= $b['value'] . ',';
		}
		$benzuin = substr($benzuin, 0, strlen($benzuin) - 1);
		if($this->_user['auth_sid']){
			$benzuin=$this->_user['auth_sid'].','.$benzuin;
		}

		$row = Contractlist::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
//'bumen_id' => $this->_user['auth_sid']
		if(!empty($param['departpath'])){
			$depart_id=end($param['departpath']);
			$row->andWhere(['bumen_id'=>$depart_id]);
		}
		if($benzuin && empty($param['departpath'])){
			$row->andWhere('bumen_id  in ('.$benzuin.')');
		}
		if (isset($param["keyword"]) && $param["keyword"]) {     //关键词
			$row->andWhere("fy_bianhao like '%" . $param["keyword"]."%'"
					. " or `ky_bianhao` like '%" . $param["keyword"]."%'"
					. " or `fy_khdh` like '%" . $param["keyword"]."%'"
					. " or `ky_khdh` like '%" . $param["keyword"]."%'"
					. " or `fy_khxm` like '%" . $param["keyword"]."%'"
					. " or `ky_khxm` like '%" . $param["keyword"]."%'"
					. " or `bianhao` like '%" . $param["keyword"]."%'"
					. " or `mingcheng` like '%" . $param["keyword"]."%'"
			);
		}
		$listdata['totalnum'] = $row->count();
		 $list= $row->orderBy(['utime'=>SORT_DESC])->limit($pagesize)->offset($start)->asArray()->all();
		if(!empty($list)){
			foreach($list as $key=> $v){
				if ($v['bumen_id']) {
					$depart = Depart::find()->where(['d_id' => $v['bumen_id'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
					$list[$key]['bumen'] = $depart['d_name'];
				}
				if ($v['c_id']) {
					$user = User::find()->where(['u_id' => $v['c_id'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
					$list[$key]['jingjiren'] = $user['u_name'];
				}
			}
		}
		//$listdata['list'] = Yii::$app->LoadData->listButton($this->id, $listdata['list'], $this->_user);
		$listdata['list']=$list;
		return ApiReturn::success('获取成功', $listdata);
	}

	/*
	 * 模板添加
	 * @return array|\common\models\json
	 */
	public function actionAdd()
	{        //判断是否需要审核
		$post = Yii::$app->request->post();
		$post['u_id']=$this->_user['u_id'];
		$post['u_dept_id']=$this->_user['u_dept_id'];
		foreach($post['htmb'] as $v){
			if($this->_user['auth_sid']){
				$depart = Depart::find()->where(['d_id' => $this->_user['auth_sid'], 'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
				$c_data=Contractlist::find()->where(['htqz'=>$v['con_templet_prefix'],'bumen_id'=>$this->_user['auth_sid']])->one();
				if(!empty($c_data)){
					if($c_data->zhuangtai=='进行中' || $c_data->zhuangtai=='审核中'){
						return ApiReturn::wrongParams($c_data->mingcheng.'['.$c_data->bianhao.']正在'.$c_data->zhuangtai);
					}
				}
			}
			else{
				return ApiReturn::wrongParams('申请合同失败1,只有店或者店员才能申请合同!');
			}

		}
		$fengpanshenhe = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'ht_sh','company_id'=>$this->_user['company_id']])->asArray()->one();
		if ($fengpanshenhe['val'] == 1) {
			//判断是否有没有审核的项目
			if (!Verify::verifyService(99, $post['u_id'])) {
				return ApiReturn::wrongParams('当前资源有操作还在审核');
			}
			$shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'ht_sh_ren','company_id'=>$this->_user['company_id']])->asArray()->one();
			$zhuguan = $this->_getShengheren($this->_user, $shengheren['val']);
			if (!$zhuguan) {
				return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
			}
			$post['zhuangtai']='审核中';
			$htjh=Contractlist::pl_scht($post,$this->_user);
			if ($htjh) {
				$v_type = "合同申请";
				$model = new Verify();
				$model->u_id = $this->_user['u_id'];
				$model->v_post_user = $this->_user['u_id'];
				$model->v_type = $v_type;
				$model->v_end_user = $zhuguan;
				//审核通过执行
				$model->v_pass_func = serialize(['backend\models' . '\Contractlist', 'pl_scht_tg']);
				$model->v_pass_param = serialize($htjh['htid']);
				//审核不通过执行
				$model->v_reject_func = serialize(['backend\models' . '\Contractlist', 'pl_scht_bh']);
				$model->v_reject_param = serialize($htjh['htid']);
				$model->v_service_id = $this->_user['u_id'];
				$model->v_service_sn =$htjh['bianhao'];
				$model->company_id =$this->_user['company_id'];
				$model->v_service_type = 99;
				$model->v_content = $depart['d_name'].'申请合同编号:（'.$htjh['bianhao'].')';
				$model->v_status = 0;
				$model->c_id = $model->u_id = $this->_user['u_id'];
				$model->ctime = $model->utime = date('Y-m-d H:i:s', time());
				if ($model->save()) {
					return ApiReturn::success('申请已提交，等待审核');
				} else {
					var_dump($model->getErrors());
					return ApiReturn::wrongParams('状态修改失败2');
				}
			}else {
				return ApiReturn::wrongParams('申请合同失败1');
			}

		}else{
			$post['zhuangtai']='进行中';
			if (Contractlist::pl_scht($post,$this->_user)) {
				return ApiReturn::success('申请合同成功');
			} else {
				return ApiReturn::wrongParams('申请合同失败1');
			}
		}
	}

	/*
	 *模板更新
	 * @return array|\common\models\json
	 */
	public function actionEdit()
	{	
		$post = Yii::$app->request->post();		
		if ($post['con_id']) {
			try {
				$notify = new Notify();
				$notify->n_title = '合同['.$post['bianhao'].']->状态:'.$post['zhuangtai'].',反馈通知';
				$notify->n_content = $post['shiyou'];
				$notify->n_u_id = $post['user'];
				$notify->n_time =date('Y-m-d H:i:s',time());
				$notify->n_is_read = 0;
				$notify->n_is_notify = 0;
				$notify->n_url = '/#/contract/contractList';
				$notify->c_id = $this->_user['u_id'];
				$notify->u_id = $this->_user['u_id'];
				$notify->utime = date('Y-m-d H:i:s',time());
				$notify->ctime = date('Y-m-d H:i:s',time());
				$notify->auth_rid = $this->_user['auth_rid'];
				$notify->auth_sid = $this->_user['auth_sid'];
				$notify->company_id =$this->_user['company_id'];
				$notify->auth_aid = $this->_user['auth_aid'];
				$notify->auth_baid = $this->_user['auth_baid'];
				$notify->customer_sex = $this->_user['customer_sex'];
				
				if($notify->save()){
					$c_data=Contractlist::find()->where(['con_id'=>$post['con_id']])->one();
					$c_data->zhuangtai=$post['zhuangtai'];
					$c_data->utime = date('Y-m-d H:i:s');
					$c_data->save();
					return ApiReturn::success('反馈成功');
				} else {
					return ApiReturn::wrongParams('反馈失败');
				}
			} catch (Exception $exception) {
				return ApiReturn::codeError('反馈失败');
			}
		} else {
			return ApiReturn::wrongParams('参数错误');
		}
	}
	/**
	 * 打印
	 */
	public function actionPrint(){
		$val=Yii::$app->request->get();
		if(!empty($val['htid'])){
			$c_data=Contractlist::find()->where(['con_id'=>$val['htid'],'company_id'=>$this->_user['company_id']])->one();
			return $this->render('/print/index',['val'=>$val,'data'=>$c_data]);
		}else{
			echo '异常，无法打印';
		}

	}
}
