<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaHrBiandong;
use backend\models\OaHrStaff;
use backend\models\Role;
use backend\models\ZhSettingBase;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\OaQingjiaGii;
use Yii;

/**
 * 变动记录管理控制器
 */
class OahrbiandongController extends AuthController
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
     * 变动记录管理列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $res = Depart::find()->where(['is_del' => 0])->asArray()->all();
        $departArr = [];
        foreach($res as $key => $val){
            $departArr[$val['d_id']] = $val['d_name'];
        }

        $res = Role::find()->where(['is_del' => 0])->asArray()->all();
        $roleArr = [];
        foreach($res as $key => $val){
            $roleArr[$val['role_id']] = $val['role_name'];
        }

        $row = OaHrBiandong::find()->where(['a.is_del' => 0])->alias('a')
            ->leftJoin('zh_depart as d','d.d_id = a.bd_d_id')
            ->leftJoin('zh_user as u','u.u_id = a.staff_id')
            ->leftJoin('zh_role as r','r.role_id = a.bd_role_id')
            ->select('a.*, d.d_name, r.role_name, u.u_name, u.u_employee_id as staff_no');
        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere('(a.yx_d_id = "'.$param['d_id'].'"
                or a.bd_d_id = "'.$param['d_id'].'")');
        }
        if (isset($param['kw']) && $param['kw']){
            $row->andWhere("u.u_name like '%" . $param["kw"]."%'"
                . " or u.u_phone like '%" . $param["kw"]."%'"
                . " or u.u_employee_id like '%" . $param["kw"]."%'");
        }
        if (isset($param['biandongleixing']) && $param['biandongleixing']){
            $row->andWhere(['a.biandongleixing' => $param['biandongleixing']]);
        }
        if (isset($param['daterange']) && $param['daterange'] && count($param['daterange']) == 2){
            $daterange = $param['daterange'];
            $row->andFilterWhere(['between','a.bd_date',$daterange[0], $daterange[1]]);
        }

        $list = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();

        foreach($list as $key => $val){
            $val['yx_d_name'] = array_key_exists($val['yx_d_id'], $departArr) ? $departArr[$val['yx_d_id']] : '未知';
            $val['yx_role_name'] = array_key_exists($val['yx_role_id'], $roleArr) ? $roleArr[$val['yx_role_id']] : '未知';
            $list[$key] = $val;
        }

        $data['list'] = $list;
        $data['totalnum'] = $row->count();
        $data['biandongleixing'] = ZhSettingBase::getBaseChildSettings('人事-员工变动类型');

        $depart = Depart::find()->where(['is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 变动记录管理添加修改
     */
    public function actionUpdate(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new OaHrBiandong();
            $user = $this->_user();
            $bd_d_id = $post['bd_d_id'][count($post['bd_d_id']) - 1];

            $model->staff_id = $post['staff_id'];
            $model->yx_d_id = $post['yx_d_id'];
            $model->yx_role_id = $post['yx_role_id'];
            $model->bd_role_id = $post['bd_role_id'];
            $model->bd_date = $post['bd_date'];
            $model->bd_d_id = $bd_d_id;
            $model->biandongleixing = $post['biandongleixing'];
            $model->biandongyuanyin = $post['biandongyuanyin'];
            $model->c_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->is_del = 0;
            $message = '添加';
            if($model->save()){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

}
