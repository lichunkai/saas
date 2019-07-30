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
use backend\models\Salary_config_mingcheng_yeji;
use backend\models\User;
use backend\models\ZhSettingBase;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 用户控制器
 */
class UsergongziController extends AuthController
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
        $depart = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $result['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        //提成方案
        $row=Salary_config_mingcheng::find()->select(['scm_id as value','fanganmingcheng as label'])->where(['is_del'=>0])->with(
            ['children'=>function($query){
                $query->select(['scm_id','scmy_id as value', 'yejimingcheng as label'])->where(['is_del'=>0]);
            }]
        );
        $result['tichengData']= $row->asArray()->all();
        $result['rolelist'] = Role::find()->select('role_id as value,role_name as label')->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->all();
        //$result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        $result['prefix'] = ZhSettingBase::getBaseSettings(108);
        $result['transfer'] = ZhSettingBase::getBaseSettings(85);
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

        $row = User::find()->alias('a')->select('a.*,b.d_name,c.dts_name')->where(['a.is_del' => 0,'a.company_id'=>$this->_user['company_id']])
            ->leftJoin('zh_depart b','a.u_dept_id=b.d_id')
            ->leftJoin('com_district c','b.d_district=c.dts_id and c.is_del=0')
            ->with(['role' => function ($query) {
            $query->select(['role_id', 'role_name']);
        }]);

        if (isset($param['did']) && $param['did']) {
            $row->andWhere(['a.u_dept_id' => $param['did']]);
        }
        if (isset($param['rid']) && $param['rid']) {
            $row->andWhere(['a.u_role_id' => $param['rid']]);
        }
        if (isset($param['sid']) && $param['sid']) {
            $row->andWhere(['a.u_status' => $param['sid']]);
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['or', ['like', 'a.u_name', $kw], ['like', 'a.u_employee_id', $kw],['like', 'a.u_phone', $kw]]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['userlist'] = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();
        //var_dump($listdata['userlist']);die;
        //权限循环
        $listdata['purview'] = Purview::getPurviewList();
        $listdata['dataauth'] = Yii::$app->params['dataAuth'];
        $listdata['showtab'] = array_keys($listdata['purview'])[0];

        $connection = Yii::$app->db;
        $purviewdata = $connection->createCommand('SELECT p_id,p_type,0 as type_value FROM zh_purview WHERE is_del=0 AND p_pid<>0')->queryAll();
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
            $arr = [];
            $listdata['userlist'][$key]['departpath'] = $this->_getDepartid($item['u_dept_id'], $arr);
            if(!empty($item['ticheng_id'])){
                $yejifangan = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'scmy_id' => $item['ticheng_id']])->asArray()->one();
                $listdata['userlist'][$key]['fanganmingcheng']= $yejifangan['yejimingcheng'];
            }
            if(!empty($item['fuzerenticheng_id'])){
                $yejifangan = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'scmy_id' => $item['fuzerenticheng_id']])->asArray()->one();
                $listdata['userlist'][$key]['fuzerenfanganmingcheng']= $yejifangan['yejimingcheng'];
            }
            if(!empty($item['fuzerenticheng_zyid'])){
                $yejifangan = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'scmy_id' => $item['fuzerenticheng_zyid']])->asArray()->one();
                $listdata['userlist'][$key]['fuzerenfanganmingcheng_zy']= $yejifangan['yejimingcheng'];
            }

            $roleAuth = $connection->createCommand('SELECT p_id,data_range FROM zh_user_auth WHERE u_id=' . $item['u_id'].' and company_id='.$this->_user['company_id'])->queryAll();
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
     *批量设置方案
     */
    public function actionPl_sz()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {

            $param = Yii::$app->request->post();
            foreach($param['selection'] as $v){
                $model = user::find()->where(['u_id'=>$v['u_id'],'company_id'=>$this->_user['company_id']])->one();
                $model->ticheng_id = isset($param['ticheng_id'])? $param['ticheng_id'] :'' ;
                $model->fuzerenticheng_id = !empty($param['fuzerenticheng_id'])? $param['fuzerenticheng_id'] :'' ;
                $model->fuzerenticheng = !empty($param['fuzerenticheng'])?json_encode($param['fuzerenticheng']):'' ;
                $model->fuzerenticheng_zyid = !empty($param['fuzerenticheng_zyid'])? $param['fuzerenticheng_zyid'] :'' ;
                $model->fuzerenticheng_zy = !empty($param['fuzerenticheng_zy'])?json_encode($param['fuzerenticheng_zy']):'' ;
                $model->ticheng = !empty($param['ticheng_id'])?json_encode($param['ticheng'])  :'' ;
                $model->uid = $this->_user['u_id'];
                $model->utime = date('Y-m-d H:i:s') ;
                $result = $model->save();
                if(!$result){
                    $transaction->rollBack();
                    return ApiReturn::wrongParams('批量设置提成方案失败');
                }
            }
            $transaction->commit();
            return ApiReturn::success('批量设置提成方案成功!');

        }catch (Exception $e){
            $transaction->rollBack();
            return ApiReturn::wrongParams('批量设置提成方案失败');
        }
    }
    /**
     * 用户授权
     * @return array|\common\models\json
     */
    public function actionAuth()
    {
        $param = Yii::$app->request->post();
        //var_dump($param);die;
        $result = UserAuth::UpdateAuth($param, $this->_user);

        if ($result) {
            return ApiReturn::success('授权成功');
        } else {
            return ApiReturn::wrongParams('授权失败');
        }

    }
    /*
     * 用户更新
     * @return array|\common\models\json
     */
    public function actionEdit()
    {
        $param = Yii::$app->request->post();
        $usermodel = User::findOne($param['u_id']);
        $result = $usermodel->GongziUser($param, $this->_user);
        if ($result) {
            return ApiReturn::success('工资设置成功');
        } else {
            return ApiReturn::wrongParams('工资设置失败');
        }
    }
}
