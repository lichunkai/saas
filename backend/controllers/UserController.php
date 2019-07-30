<?php

namespace backend\controllers;

use backend\models\Customer;
use backend\models\Depart;
use backend\models\Salary_config_mingcheng;
use backend\models\House;
use backend\models\Purview;
use backend\models\Rank;
use backend\models\Role;
use backend\models\UserAuth;
use backend\models\User;
use backend\models\ZhSettingBase;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 用户控制器
 */
class UserController extends AuthController
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
     * 获取选择参数
     * @return array|\common\models\json
     */
    public function actionGetsetting()
    {
        $depart = Depart::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $result['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        //提成方案
        $row=Salary_config_mingcheng::find()->select(['scm_id as value','fanganmingcheng as label'])->where(['is_del'=>0])->with(
            ['children'=>function($query){
                $query->select(['scm_id','scmy_id as value', 'yejimingcheng as label'])->where(['is_del'=>0]);
            }]
        );
        $result['tichengData']= $row->asArray()->all();
        $result['rolelist'] = Role::find()->select('role_id as value,role_name as label')->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->asArray()->all();
        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        $result['prefix'] = ZhSettingBase::getBaseSettings('user_employee_prefix');
        $result['transfer'] = ZhSettingBase::getBaseSettings('transfer_type');
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /*
     * 用户列表
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;

        $row = User::find()->alias('a')->select('a.*,b.d_name,c.dts_name')->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => 0])->leftJoin('zh_depart b','a.u_dept_id=b.d_id')->leftJoin('com_district c','b.d_district=c.dts_id and c.is_del=0')->with(['role' => function ($query) {
            $query->select(['role_id', 'role_name']);
        }]);

        if (isset($param['did']) && $param['did']) {
            $row->andWhere(['a.u_dept_id' => $param['did']]);
        }
        if (isset($param['rid']) && $param['rid']) {
            $row->andWhere(['a.u_role_id' => $param['rid']]);
        }
        $column = 'a.u_entry_time';
        if (isset($param['sid']) && $param['sid']) {
            if($param['sid'] != 1){
                $column = 'a.utime';
            }
            $row->andWhere(['a.u_status' => $param['sid']]);
        }
        if (isset($param['daterange']) && $param['daterange'][0]) {
            $row ->andWhere(['>=',$column,$param['daterange'][0]])->andWhere(['<=',$column,$param['daterange'][1].' 23:59:59']);
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['or', ['like', 'a.u_name', $kw], ['like', 'a.u_employee_id', $kw],['like', 'a.u_phone', $kw]]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['userlist'] = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;
        $listdata['userlist'] = Yii::$app->LoadData->listButton($this->id, $listdata['userlist'], $this->_user);
        //var_dump($listdata['userlist']);die;
        //权限循环
        $listdata['purview'] = Purview::getPurviewList();
        $listdata['dataauth'] = Yii::$app->params['dataAuth'];
        $listdata['showtab'] = array_keys($listdata['purview'])[0];

        $connection = Yii::$app->db;
        $purviewdata = $connection->createCommand('SELECT p_id,p_type,0 as type_value FROM zh_purview WHERE is_auth=1 AND is_del=0 AND p_pid<>0')->queryAll();
        $purviewdata = ArrayHelper::index($purviewdata, 'p_id');
        foreach ($purviewdata as $k => $data) {
            if ($data['p_type'] == 1) {
                $listdata['purviewdata'][$data['p_id']] = false;
            } else {
                $listdata['purviewdata'][$data['p_id']] = (int)$data['type_value'];
            }
        }

        foreach ($listdata['userlist'] as $key => $item) {
            $listdata['userlist'][$key]['u_sex_text'] = $listdata['userlist'][$key]['u_sex'] == 1 ? '男' : ($listdata['userlist'][$key]['u_sex'] == 2 ? '女' : '-');
//            $listdata['userlist'][$key]['u_employee_id'] = $item['u_employee_prefix'].$item['u_employee_no'];
            $listdata['userlist'][$key]['role_name'] = $item['role']['role_name'];
            if($listdata['userlist'][$key]['u_status'] == 1){
                $listdata['userlist'][$key]['u_status_text'] = '在职';
            }elseif($listdata['userlist'][$key]['u_status'] == 2){
                $listdata['userlist'][$key]['u_status_text'] = '休假';
            }elseif($listdata['userlist'][$key]['u_status'] == 3){
                $listdata['userlist'][$key]['u_status_text'] = '锁定';
            }elseif($listdata['userlist'][$key]['u_status'] == 4){
                $listdata['userlist'][$key]['u_status_text'] = '离职';
            }elseif($listdata['userlist'][$key]['u_status'] == 5){
                $listdata['userlist'][$key]['u_status_text'] = '开除';
            }

            $arr = [];
            $listdata['userlist'][$key]['departpath'] = $this->_getDepartid($item['u_dept_id'], $arr);

            $roleAuth = $connection->createCommand('SELECT p_id,data_range FROM zh_user_auth WHERE u_id=' . $item['u_id'])->queryAll();
            $roleAuth = ArrayHelper::map($roleAuth, 'p_id', 'data_range');
            $listdata['userlist'][$key]['userpurview'] = $listdata['purviewdata'];
            foreach ($roleAuth as $k => $priview) {
                if (isset($purviewdata[$k])) {
                    if ($purviewdata[$k]['p_type'] == 1) {
                        $listdata['userlist'][$key]['userpurview'][$k] = $priview == 6 ? true : false;
                    } else {
                        $listdata['userlist'][$key]['userpurview'][$k] = (int)$priview;
                    }
                }
            }
        }
        //var_dump($listdata);die;
        return ApiReturn::success('获取成功', $listdata);
    }

    /**
     * 用户授权
     * @return array|\common\models\json
     */
    public function actionAuth()
    {
        $param = Yii::$app->request->post();
        //var_dump($param);die;
        $user = User::find()->select('u_id')->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$this->_user['company_id'],'is_del'=>0])->asArray()->one();
        if(empty($user)){
            return ApiReturn::wrongParams('参数错误');
        }
        $param['u_id'] = $user['u_id'];
        $result = UserAuth::UpdateAuth($param, $this->_user);

        if ($result) {
            return ApiReturn::success('授权成功');
        } else {
            return ApiReturn::wrongParams('授权失败');
        }

    }

    /**
     * 检查手机号是否存在
     * @return \common\models\json
     */
    public function actionCheckmobile()
    {
        $id = Yii::$app->request->post('u_uuid');
        $mobile = Yii::$app->request->post('mobile');
        $query = User::find()->where(['u_phone' => $mobile,'company_id'=>$this->_user['company_id']]);//->asArray()->one();
        if ($id) {
            $query->andWhere(['<>', 'u_uuid', $id]);
        }
        $result = $query->asArray()->one();
        if ($result) {
            return ApiReturn::success('查询成功');
        } else {
            return ApiReturn::noData('查询失败');
        }
    }

    /**
     * 检查员工编号是否存在
     * @return \common\models\json
     */
    public function actionGetemployeeno()
    {
        $prefix = Yii::$app->request->post('prefix');
        $data = User::find()->select('count(u_id) as total')->where(['company_id'=>$this->_user['company_id']])->asArray()->one();
        $no = $data['total']+1;
        $no=str_pad($no,4,"0",STR_PAD_LEFT);
        return ApiReturn::success('查询成功',$no);
    }

    /**
     * 检查员工房源和客源
     * @return \common\models\json
     */
    public function actionCheckuserinfo()
    {
        $u_uuid = Yii::$app->request->post('u_uuid');
        $user = User::find()->select('u_id')->where(['u_uuid'=>$u_uuid,'company_id'=>$this->_user['company_id'],'is_del'=>0])->asArray()->one();
        if(empty($user)){
            return ApiReturn::wrongParams('参数错误');
        }
        $housecount = House::find()->where(['company_id'=>$this->_user['company_id'],'private_user'=>$user['u_id'],'house_private'=>'1','is_del'=>'0'])->count();
        if ($housecount>0) {
            return ApiReturn::success('用户拥有私人房源'.$housecount.'套，请先转移数据！');
        }
        $customercount = Customer::find()->where(['company_id'=>$this->_user['company_id'],'c_id'=>$user['u_id'],'customer_private'=>'私客','is_del'=>'0'])->count();
        if ($customercount>0) {
            return ApiReturn::success('用户拥有私人客源'.$customercount.'套，请先转移数据！');
        }
    }


    /*
     * 用户添加
     * @return array|\common\models\json
     */
    public function actionAdd()
    {
        $param = Yii::$app->request->post();
        $usermodel = new User();

        $param['u_employee_id'] = $param['u_employee_prefix'].$param['u_employee_no'];

        $autharr = $this->userAuth(0,$param['u_dept_id']);
        $param['auth_rid'] = isset($autharr['auth_rid'])? $autharr['auth_rid'] : 0;
        $param['auth_sid'] = isset($autharr['auth_sid'])? $autharr['auth_sid'] : 0;
        $param['auth_aid'] = isset($autharr['auth_aid'])? $autharr['auth_aid'] : 0;
        $param['auth_baid'] = isset($autharr['auth_baid'])? $autharr['auth_baid'] : 0;
        $param['auth_cid'] = isset($autharr['auth_cid'])? $autharr['auth_cid'] : 0;

        $result = $usermodel->UpdateUser($param, $this->_user);

        if ($result) {
            return ApiReturn::success('添加成功');
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /*
     * 用户更新
     * @return array|\common\models\json
     */
    public function actionEdit()
    {
        $param = Yii::$app->request->post();
        $usermodel = User::find()->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$this->_user['company_id'],'u_status'=>1,'is_del'=>0])->one();
        $autharr = $this->userAuth($usermodel->u_id,$param['u_dept_id']);//var_dump($autharr);die;
        $param['u_employee_id'] = $param['u_employee_prefix'].$param['u_employee_no'];

        $param['auth_uid'] = isset($autharr['auth_uid'])? $autharr['auth_uid'] : 0;
        $param['auth_rid'] = isset($autharr['auth_rid'])? $autharr['auth_rid'] : 0;
        $param['auth_sid'] = isset($autharr['auth_sid'])? $autharr['auth_sid'] : 0;
        $param['auth_aid'] = isset($autharr['auth_aid'])? $autharr['auth_aid'] : 0;
        $param['auth_baid'] = isset($autharr['auth_baid'])? $autharr['auth_baid'] : 0;
        $param['auth_cid'] = isset($autharr['auth_cid'])? $autharr['auth_cid'] : 0;

        $result = $usermodel->UpdateUser($param, $this->_user);
        if ($result) {
            return ApiReturn::success('修改成功');
        } else {
            return ApiReturn::wrongParams('修改失败');
        }
    }

    /*
     * 用户删除(废弃)
     * @return array|\common\models\json
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('u_id');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = User::findOne($id);
        $model->is_del = 1;
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::success('删除失败');
        }
    }

    /*
     *  重置密码
     * @return array|\common\models\json
     */
    public function actionResetpwd()
    {
        $param = Yii::$app->request->post();
        if (isset($param['u_uuid']) && $param['u_uuid']) {
            $model = User::find()->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$this->_user['company_id'],'u_status'=>1,'is_del'=>0])->one();
            if(empty($model)){
                return ApiReturn::wrongParams('参数错误');
            }
            $u_salt = rand(1000, 9999);
            $model->u_salt = (string)$u_salt;
            $model->u_password = md5($param['u_password'] . $u_salt);
            $result = $model->save();
            if ($result) {
                return ApiReturn::success('重置成功');
            } else {
                return ApiReturn::wrongParams('重置失败');
            }
        } else {
            return ApiReturn::wrongParams('参数错误');
        }
    }

    /*
     * 用户解锁定
     * @return array|\common\models\json
     */
    public function actionLocked()
    {
        $param = Yii::$app->request->post();
        if (!$param['u_uuid']) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = User::find()->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$this->_user['company_id'],'u_status'=>1,'is_del'=>0])->one();
        if(empty($model)){
            return ApiReturn::wrongParams('参数错误');
        }
        $model->u_status = $param['u_status'];
        $result = $model->save();
        if ($result) {
            if ($param['u_status'] == 3) {
                $tokens = md5($model->u_id . $model->u_name . $model->u_salt);
                Yii::$app->redis->del($tokens);
            }
            return ApiReturn::success('锁定成功');
        } else {
            return ApiReturn::success('锁定失败');
        }
    }


    /*
     * 用户激活
     * @return array|\common\models\json
     */
    public function actionActivate()
    {
        $param = Yii::$app->request->post();

        if (!$param['u_uuid']) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = User::find()->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$this->_user['company_id'],'is_del'=>0])->andWhere(['or','u_status=2','u_status=3','u_status=4','u_status=5'])->one();
        if(empty($model)){
            return ApiReturn::wrongParams('参数错误');
        }
        $model->u_status = 1;
        $result = $model->save();
        //TODO
        if ($result) {
            return ApiReturn::success('激活成功');
        } else {
            return ApiReturn::success('激活失败');
        }
    }

    /*
    * 用户转移
    * @return array|\common\models\json
    */
    public function actionTransfer()
    {
        $param = Yii::$app->request->post();
        if (!$param['u_uuid']) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = User::find()->where(['u_uuid'=>$param['u_uuid'],'company_id'=>$this->_user['company_id'],'u_status'=>1,'is_del'=>0])->one();
        if(empty($model)){
            return ApiReturn::wrongParams('参数错误');
        }
        $param['u_id'] = $model->u_id;
        $usermodel = new User();
        $result = $usermodel->Transfer($param,$this->_user);
        if ($result) {
            return ApiReturn::success('转移成功');
        } else {
            return ApiReturn::success('转移失败');
        }
    }

    /**
     * 获取用户
     * @return \common\models\json
     */
    public function actionGetuser()
    {
        $d_id = Yii::$app->request->post('d_id');
        $data = User::find()->select('u_id,u_name')->where(['u_dept_id'=>$d_id,'company_id'=>$this->_user['company_id'],'is_del'=>0])
            ->andWhere(['or','u_status=1','u_status=2','u_status=3'])
            ->asArray()->all();
        //echo $data->createCommand()->getRawSql();die;
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 获取用户
     * @return \common\models\json
     */
    public function actionGetuserlist()
    {
        $d_id = Yii::$app->request->get('d_id');
        $data = User::find()->select('u_id,u_name')->where(['u_dept_id'=>$d_id,'company_id'=>$this->_user['company_id'],'is_del'=>0])
            ->andWhere(['or','u_status=1','u_status=2','u_status=3'])
            ->asArray()->all();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 获取用户转移
     * @return \common\models\json
     */
    public function actionGetuser_zy()
    {
        $d_id = Yii::$app->request->post('d_id');
        $data = User::find()->where(['u_dept_id'=>$d_id,'company_id'=>$this->_user['company_id'],'is_del'=>0]);
        $data->andWhere('u_status=1 or u_status=2');
        $data = $data ->asArray()->all();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 一手房接口
     * @return array|\common\models\json
     */
    public function actionAlluser()
    {
        $user = User::find()->alias('a')
            ->select('a.u_id,a.u_employee_id,a.u_name,a.u_phone,a.u_sex,a.u_dept_id,b.role_id,b.role_name,b.role_type')
            ->leftJoin('zh_role as b','a.u_role_id=b.role_id')
            ->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => 0])->orderBy('a.u_id ASC')->asArray()->all();;
        if($user){
            return ApiReturn::success('获取成功',$user);
        }else{
            return ApiReturn::wrongParams('获取失败');
        }
    }



	/**
	 * 用户列表按照首字母排序
	 * @return \common\models\json
	 */
	public function actionUserlisttowechat()
	{
		$data = [];
		$params = Yii::$app->request->post();
		$data = User::find()->select('u_id,u_employee_id,u_name,u_phone,u_sex,u_status')->where(['or','u_status=1','u_status=2','u_status=3'])->andWhere(['is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->all();
		if(empty($data)){
			return ApiReturn::noData('无数据');
		}
		foreach($data as $key => $item){
			$first[] = $data[$key]['firstchar'] = Tools::getFirstChar($item['u_name']);
		}
		array_multisort($first,SORT_ASC,$data);

		return ApiReturn::success('获取成功',$data);
	}
    

    /**
     * 递归获取子节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getSubNode($id, &$arr)
    {
        $ret = Depart::find()->where(['d_pid' => $id,'is_del'=>0])->select('d_id')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node['d_id'];
                $this->_getSubNode($node['d_id'], $arr);
            }
        }
        return $arr;
    }

}
