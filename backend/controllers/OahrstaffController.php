<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaHrStaff;
use backend\models\ZhSettingBase;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\OaQingjiaGii;
use Yii;

/**
 * 人事档案管理控制器
 */
class OahrstaffController extends AuthController
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
     * 人事档案管理列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $row = OaHrStaff::find()->where(['a.is_del' => 0])->alias('a')
            ->leftJoin('zh_depart as d','d.d_id = a.d_id')
            ->leftJoin('zh_user as u', 'u.u_id = a.staff_id')
            ->select('a.*, d.d_name, u.u_role_id')
            ->where(['a.company_id' => $this->_user['company_id']]);
        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere(['a.d_id' => trim($param['d_id'])]);
        }
        if (isset($param['zhiwu']) && $param['zhiwu']) {
            $row->andWhere(['u.u_role_id' => trim($param['zhiwu'])]);
        }
        if (isset($param['u_status']) && $param['u_status']) {
            $row->andWhere(['u.u_status' => trim($param['u_status'])]);
        }
        if (isset($param['kw']) && $param['kw']){
            $row->andWhere("(u.u_name like '%" . $param["kw"]."%'"
                . " or u.u_phone like '%" . $param["kw"]."%'"
                . " or a.staff_name like '%" . $param["kw"]."%'"
                . " or a.lianxifangshi like '%" . $param["kw"]."%'"
                . " or a.staff_no like '%" . $param["kw"]."%'"
                . " or u.u_employee_id like '%" . $param["kw"]."%')");
        }
        if (isset($param['rzDate']) && $param['rzDate'] && count($param['rzDate']) == 2){
            $daterange = $param['rzDate'];
            $row->andFilterWhere(['between','a.ruzhishijian',$daterange[0], $daterange[1]]);
        }
        if (isset($param['chushengDate']) && $param['chushengDate'] && count($param['chushengDate']) == 2){
            $daterange = $param['chushengDate'];
            $row->andFilterWhere(['between','a.birthday',$daterange[0], $daterange[1]]);
        }

        //echo $row->createCommand()->getRawSql();
        $list = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        foreach($list as $key => $val){
            $val['sex_name'] = $val['sex'] == 1 ? '男' : '女';
            $list[$key] = $val;
        }
        $data['list'] = $list;
        $data['totalnum'] = $row->count();

        $data['zhaopinqudao'] = ZhSettingBase::getBaseChildSettings('招聘渠道');
        $data['biandongleixing'] = ZhSettingBase::getBaseChildSettings('人事-员工变动类型');
        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 人事档案管理添加修改
     */
    public function actionUpdate(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new OaHrStaff();
            $result = $model->UpdateHr($post,$this->_user());
            $message = isset($post['hr_id']) ? '修改' : '添加';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 人事档案管理编辑（后台管理）
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
            $model->remark = $post['remark'];
            $model->utime = date('Y-m-d H:i:s');
            $model->u_id = $user['u_id'];
            $model->company_id = $user['company_id'];
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
