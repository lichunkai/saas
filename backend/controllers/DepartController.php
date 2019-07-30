<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\District_slice;
use backend\models\OrgCompany;
use backend\models\User;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\models\gii\ComDistrictGii;
use common\models\gii\DepartGii;
use common\helps\Tools;
use common\helps\Tree;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 用户部门控制器
 */
class DepartController extends AuthController
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
     * 部门列表
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();

        //顶部按钮及下拉选项
        $data['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user);
        $data['departtype'] = Yii::$app->params['roleType'];
        $data['district'] = ComDistrictGii::find()->select('dts_id,dts_name')->where(['is_del'=>'0'])->andWhere(['OR','dts_status=0','dts_status=1 AND company_id='.$this->_user['company_id']])->asArray()->all();

        $depart = Depart::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        $row = Depart::find()->alias('a')->select('a.*,b.dts_name,c.contract_money,c.contract_version,c.contract_start,c.contract_end')
            ->leftJoin('com_district as b','a.d_district = b.dts_id  and b.is_del=0')
            ->leftJoin('org_company_contract as c','a.d_id = c.depart_store_id')
            ->where(['a.company_id'=>$this->_user['company_id'],'a.is_del' => 0]);
        if (isset($param['d_id'][0]) && $param['d_id'][0]) {
            $row->andWhere(['a.d_id' => trim($param['d_id'][0])]);
        }

        $departlist = $row->orderBy('a.d_sort DESC,a.d_id ASC')->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;
        $departlist = Yii::$app->LoadData->listButton($this->id, $departlist, $this->_user);

        $data['contract_version'] = '门店版';
        $data['contract_storenum'] = 0;
        foreach ($departlist as $key => $item) {
            $arr = [];
            if($item['dts_name'] == null || $item['dts_name'] == ''){
                $departlist[$key]['dts_name'] = '---';
            }
            if($item['d_type'] == 2){
                if($item['contract_money'] > 0){
                    $data['contract_version'] = $item['contract_version'];
                    $data['contract_storenum']++;
                }
            }
            $departlist[$key]['departpath'] = $this->_getDepartid($item['d_pid'], $arr);
            if ($item['d_pid'] == 0 || (isset($param['d_id'][0]) && $item['d_id'] == $param['d_id'][0])) {
                $departlist[$key]['d_show_name'] = '　+ ' . $departlist[$key]['d_name'];
                $topdepart[$item['d_id']] = $departlist[$key];
            }
        }

        $data['departlist'] = [];
        foreach ($topdepart as $kk => $depart) {
            $childdepart = [];
            $childdepart[] = $depart;


            $childdepart = $this->_getSubNodeinfo($depart['d_id'], $departlist, $childdepart, $depart['d_level']);
            $data['departlist'] = array_merge($data['departlist'], $childdepart);
        }
//        var_dump($data);die;
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 添加部门
     * @return \common\models\json
     */
    public function actionAdd()
    {
         $param = Yii::$app->request->post();
         $typearr = ['4'=>'contract_barea','3'=>'contract_area','2'=>'contract_store','1'=>'contract_row'];
         $company_id = $this->_user['company_id'];
         $param['company_id'] = $company_id;
         //验证是否可以添加部门
//         if($param['d_type'] != 5){
//             $departlimit = OrgCompany::find()->alias('a')->select('b.contract_barea,b.contract_area,b.contract_store,b.contract_row')->where(['a.company_id'=>$company_id])->leftJoin('org_company_contract as b','a.contract_id=b.id')->asArray()->one();
//             $departnum = Depart::find()->where(['company_id'=>$company_id,'d_type'=>$param['d_type'],'is_del'=>0])->count();
//             if($departnum >= $departlimit[$typearr[$param['d_type']]]){
//                 return ApiReturn::wrongParams('部门数已超出，请联系官方');
//             }
//         }

         $result = $this->checkDepartRange(0,$param['d_pid'],$param['d_type']);
         if($result){
             return ApiReturn::noData('部门类型超出其所属部门类型！');
         }

        $departmodel = new Depart();

        $bdmap_url = Tools::buildGeoCodingUrl('json', $param['d_address']);
        $result_arr = json_decode(Tools::curl_get($bdmap_url), true);
        if ($result_arr['status'] == 0) {
            $departmodel->d_location = $result_arr["result"]["location"]["lat"] . "," . $result_arr["result"]["location"]["lng"];
        }
        $departmodel->cid = $this->_user['u_id'];
        $departmodel->uid = $this->_user['u_id'];
        $departmodel->ctime = date('Y-m-d H:i:s');
        $departmodel->utime = date('Y-m-d H:i:s');

        if($departmodel->load($param, '') && $departmodel->save()){
            return ApiReturn::success('添加成功');
        }else{
            $errors = $departmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /**
     * 更新部门
     * @return \common\models\json
     */
    public function actionEdit()
    {
        $param = Yii::$app->request->post();

        $result = $this->checkDepartRange($param['d_id'],$param['d_pid'],$param['d_type']);
        if($result){
            return ApiReturn::noData('部门类型超出其所属部门类型！');
        }

        $departmodel =  Depart::findOne($param['d_id']);
        $bdmap_url = Tools::buildGeoCodingUrl('json', $param['d_address']);
        $result_arr = json_decode(Tools::curl_get($bdmap_url), true);
        if ($result_arr['status'] == 0) {
            $departmodel->d_location = $result_arr["result"]["location"]["lat"] . "," . $result_arr["result"]["location"]["lng"];
        }
        $departmodel->uid = $this->_user['u_id'];
        $departmodel->utime = date('Y-m-d H:i:s');
//        $departmodel->load($param, '');
//        var_dump($departmodel);var_dump($param);die;
        if($departmodel->load($param, '') && $departmodel->save()){
            return ApiReturn::success('更新成功');
        }else{
            $errors = $departmodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /**
     * 删除部门
     * @return array|\common\models\json
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('d_id');

        $subnode = $this->_getSubNode($id, $arr);
        if (!empty($subnode)) {
            array_unshift($subnode, $id);
        } else {
            $subnode[] = $id;
        }
        if (!$subnode) {
            return ApiReturn::wrongParams('参数有误');
        }
        $depart = new Depart();
        $result = $depart->DeleteDepart($subnode, $this->_user);

        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }

    /**
     * 选择部门获取主管下拉人选接口
     * @return \common\models\json
     */
    public function actionGetuser()
    {
        $id = Yii::$app->request->post('d_id');

        $arr[] = $id;
        $subnode = $this->_getSubNode($id, $arr);

        $user = User::find()->select('u_id,u_name')->where(['in','u_dept_id',$subnode])->andWhere(['company_id'=>$this->_user['company_id'],'is_del'=>0])->andWhere(['or','u_status=1','u_status=2','u_status=3'])->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;
        return ApiReturn::success('获取成功',$user);
    }

    /**
     * 全部部门树
     * @return array|\common\models\json
     */
    public function actionDeparttree()
    {
        $depart = Depart::find()->select('d_id as value,d_name as label,d_pid')->where(['company_id'=>$this->_user['company_id'],'is_del'=>0])->orderBy('d_sort DESC')->asArray()->all();
        $departtree = Tools::listToTree($depart,'value','d_pid','children');

        if($departtree){
            return ApiReturn::success('获取成功',$departtree);
        }else{
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /**
     * 根据当前部门返回树
     * @return \common\models\json
     */
    public function actionDepartlisttowechat()
    {
        $data = [];
        $params = Yii::$app->request->post();

        $departinfo = Depart::find()->select('d_id,d_name,d_principal_id,d_principal,d_pid,d_level')->where(['company_id'=>$this->_user['company_id'],'d_id'=>$this->_user['u_dept_id'],'is_del'=>0])->asArray()->one();

        $arr[] = $departinfo;
        $subnode = $this->_getDepartlist($this->_user['u_dept_id'],$arr);
        if(count($subnode) >1){
            $departtree = Tools::listToTree($subnode,'d_id','d_pid','children');
        }else{
            $departtree = $subnode;
        }
        //var_dump($departtree);die;

        return ApiReturn::success('获取成功',$departtree);
    }

    /**
     * 递归部门（全部信息）
     * @param $depart
     * @param $arr
     * @return array
     */
    private function _getDepartlist($id, &$arr)
    {
        $ret = Depart::find()->where(['d_pid' => $id,'is_del'=>0])->select('d_id,d_name,d_principal_id,d_principal,d_pid,d_level')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node;
                $this->_getDepartlist($node['d_id'], $arr);
            }
        }
        return $arr;
    }

    /**
     * 递归获取子节点（获取id）
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

    /**
     * 递归获取子节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getSubNodeinfo($id, $departlist, &$arr, $level)
    {


        $ret = [];
        foreach ($departlist as $kk => $vv) {
            if ($vv['d_pid'] == $id) {
                $ret[] = $vv;
                unset($departlist[$kk]);
            }
        }

        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $str = '';
                for ($i = 1; $i <= $node['d_level']; $i++) {
                    $str .= '　　';
                }
                $str .= '└ ';
                $node['d_show_name'] = $str . $node['d_name'];
                $arr[] = $node;
                $this->_getSubNodeinfo($node['d_id'], $departlist, $arr, $node['d_level']);
            }
        }
        return $arr;
    }

    /**
     * 判断部门类型超出其所属部门类型
     * @param $id
     * @param $type
     * @return bool
     */
    private function checkDepartRange($id,$pid,$type)
    {
        if(empty($id)){
            if(empty($pid)){
                return false;
            }
            $depart = Depart::findOne($pid);
            if($type != 5){
                if($type >= $depart->d_type){
                    return true;
                }
            }
        }else{
            if(empty($pid)){
                return false;
            }
            $depart = Depart::findOne($pid);
            $departtype = Depart::find()->select('max(d_type) as type')->where(['d_pid'=>$id])->asArray()->one();
            if($type != 5){

                if($type >= $depart->d_type || $type <= $departtype['type']){
                    return true;
                }
            }
        }
        return false;

    }



	/**
	 * 返回一维排序的部门列表
	 * @return \common\models\json
	 */
//	public function actionDepartlisttowechats()
//	{
//		$data = [];
//		$params = Yii::$app->request->post();
//		$depart = Depart::find()->select('d_id,d_name,d_principal_id,d_principal,d_pid,d_level')->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->orderBy('d_id ASC')->asArray()->all();
//		$departtree = Tools::listToTree($depart,'d_id','d_pid','children');
//		$data = $this->_getDepartlist($departtree,$data);
//
//		return ApiReturn::success('获取成功',$data);
//	}

	/**
	 * 递归
	 * @param $depart
	 * @param $arr
	 * @return array
	 */
//	private function _getDepartlists($depart, &$arr)
//	{
//		foreach ($depart as $k => $node) {
//			if(!empty($node['children'])){
//				$departdata = $node;
//				unset($departdata['children']);
//				$arr[] = $departdata;
//				$this->_getDepartlist($node['children'], $arr);
//			}else{
//				$departdata = $node;
//				unset($departdata['children']);
//				$arr[] = $departdata;
//			}
//		}
//		return $arr;
//	}





}
