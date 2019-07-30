<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaQingjia;
use backend\models\User;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\OaQingjiaGii;
use Yii;

/**
 * 请假管理控制器
 */
class OaqingjiaController extends AuthController
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
     * 请假管理列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $row = OaQingjia::find()->where(['a.is_del' => 0])->alias('a')
            ->leftJoin('zh_user as u','u.u_id = a.staff_id and u.is_del=0')
            ->leftJoin('zh_depart as d','d.d_id = a.d_id')
            ->select('a.*, d.d_name, u.u_name');
        $departTree = $this->_getListDepartTreeByAuthId(696);  // 请假管理权限
        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere(['a.d_id' => trim($param['d_id'])]);
        }else{
            $this->_getTreeDeptIds($departTree);
            $tDeptIds = Yii::$app->redis->get('treeDepartIds') ? json_decode(Yii::$app->redis->get('treeDepartIds'), true) : [];
            Yii::$app->redis->del('treeDepartIds');
            $row->andWhere(['in', 'a.d_id', $tDeptIds]);
        }
        if (isset($param['kw']) && $param['kw']){
            $row->andWhere("u.u_name like '%" . $param["kw"]."%'"
                . " or u.u_phone like '%" . $param["kw"]."%'"
                . " or u.u_employee_id like '%" . $param["kw"]."%'");
        }
        if (isset($param['shDaterange']) && $param['shDaterange'] && count($param['shDaterange']) == 2){
            $shDaterange = $param['shDaterange'];
            $row->andFilterWhere(['between','a.shenqing_date',$shDaterange[0], $shDaterange[1]]);
        }
        if (isset($param['daterange']) && $param['daterange'] && count($param['daterange']) == 2){
            $daterange = $param['daterange'];
            $row->andWhere('(a.st_time between "'.$daterange[0].'" and "'.$daterange[1].'"
            or a.ed_time between "'.$daterange[0].'" and "'.$daterange[1].'")');
        }
        if (isset($param['qingjialeixing']) && $param['qingjialeixing']){
            $row->andWhere(['a.type' => $param['qingjialeixing']]);
        }
        if (isset($param['status']) && $param['status'] != ''){
            $row->andWhere(['a.status' => $param['status']]);
        }

        $data['totalnum'] = $row->count();
        $list = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();
        foreach($list as $key => $val){
            $s = User::findOne($val['shenhe_id']);
            $t = User::findOne($val['tixing_id']);
            $val['shenheren'] = $s['u_name'];
            $val['tixingren'] = $t['u_name'];
            $list[$key] = $val;
        }
        $data['list'] = $list;

        //$depart = Depart::find()->where(['is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = $departTree; //Tools::listToTree($depart, 'value', 'd_pid', 'children');

        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 请假管理添加修改（前端）
     */
    public function actionUpdate(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $st_time = strtotime($post['st'].' '.$post['st_time']);
            $ed_time = strtotime($post['ed'].' '.$post['ed_time']);
            $day24_time = strtotime(date('Y-m-d').' 23:59:59');

            if($st_time <= $day24_time || $ed_time <= $day24_time){
                return ApiReturn::wrongParams('不能请今天的假');
            }

            if(strtotime($st_time) > strtotime($ed_time)){
                return ApiReturn::wrongParams('请假开始时间不能大于结束时间');
            }

            $model=new OaQingjia();
            $result = $model->UpdateQj($post,$this->_user());
            $message = isset($post['oa_qingjia_id']) ? '修改' : '添加';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 请假管理编辑（后台管理）
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost && isset($post['oa_qingjia_id'])){
            $user = $this->_user();
            $oa_qingjia_id = $post['oa_qingjia_id'];

            $model = OaQingjia::findOne($oa_qingjia_id);
            $model->type = $post['type'];
            $model->st_time = $post['st'].' '.$post['st_time'];
            $model->ed_time = $post['ed'].' '.$post['ed_time'];
            if(strtotime($model->st_time) > strtotime($model->ed_time)){
                return ApiReturn::wrongParams('请假开始时间不能大于结束时间');
            }

            $model->remark = $post['remark'];
            if($model->status == 2){
                $model->status = 0;
            }
            $model->utime = date('Y-m-d H:i:s');
            $model->u_id = $user['u_id'];
            $message = '更新';
            if($model->save()){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 审核通过/驳回假单
     */
    public function actionShenhe(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost && isset($post['oa_qingjia_id'])) {
            $user = $this->_user();
            $oa_qingjia_id = $post['oa_qingjia_id'];
            $model = OaQingjia::findOne($oa_qingjia_id);
            $model->sh_time = date('Y-m-d H:i:s');
            $model->shenhe_id = $user['u_id'];
            $model->status = isset($post['status']) ? $post['status'] : 0;
            $model->utime = date('Y-m-d H:i:s');
            $model->company_id = $user['company_id'];

            if($model->save()){
                return ApiReturn::success('操作成功');
            }else{
                return ApiReturn::wrongParams('操作失败');
            }
        }
    }

    /**
     * 删除假单
     */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost && isset($post['oa_qingjia_id'])) {
            $oa_qingjia_id = $post['oa_qingjia_id'];
            $model = OaQingjia::findOne($oa_qingjia_id);
            $model->is_del = 1;
            if($model->save()){
                return ApiReturn::success('删除成功');
            }else{
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }
}
