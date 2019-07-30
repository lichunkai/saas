<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaKaoqingWaichu;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;

/**
 * 外出管理控制器
 */
class OakaoqingwaichuController extends AuthController
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
     * 外出管理列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $row = OaKaoqingwaichu::find()->where(['a.is_del' => 0])->alias('a')
            ->leftJoin('zh_user as u','u.u_id = a.u_id and u.is_del=0')
            ->leftJoin('zh_depart as d','d.d_id = a.d_id')
            ->select('a.*, d.d_name, u.u_name')
            ->where(['a.company_id' => $this->_user['company_id']]);
        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere(['a.d_id' => trim($param['d_id'])]);
        }
        if (isset($param['daterange']) && $param['daterange']){
            $daterange = $param['daterange'];
            $row->andFilterWhere(['between','a.ctime',$daterange[0].' 00:00:01', $daterange[1].' 23:59:59']);
        }
        if (isset($param['kw']) && $param['kw']){
            $row->andWhere("u.u_name like '%" . $param["kw"]."%'"
                . " or u.u_phone like '%" . $param["kw"]."%'"
                . " or u.u_employee_id like '%" . $param["kw"]."%'");
        }

        //echo $row->createCommand()->getRawSql();
        $list = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $data['list'] = $list;

        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        $data['totalnum'] = $row->count();

        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 外出登记(手机端)
     */
    public function actionDaka(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $lat = $post['lat'];
            $lng = $post['lng'];
            $addr = $post['addr'];
            $url = $post['url'];
            $content = $post['content'];

            $user = $this->_user();
            //$depart = Depart::findOne($user['u_dept_id']);

            $model = new OaKaoqingWaichu();
            $model->d_id = $user['u_dept_id'];
            $model->u_id = $user['u_id'];
            $model->lat = $lat;
            $model->lng = $lng;
            $model->wc_addr = $addr;
            $model->wc_photo = $url;
            $model->content = $content;
            $model->wc_time = date('Y-m-d H:i:s');
            $model->ctime = date('Y-m-d H:i:s');
            $model->is_del = 0;
            $model->company_id = $user['company_id'];

            $row = OaKaoqingWaichu::find()->where(['is_del' =>0, 'u_id'=>$user['u_id']])
                ->andFilterWhere(['between', 'ctime', date('Y-m-d 00:00:01'), date('Y-m-d 23:59:59')]);

            if($model->save()){
                return ApiReturn::success('外出登记成功', ['count' => $row->count()]);
            }else{
                return ApiReturn::wrongParams('外出登记失败');
            }
        }else{
            return ApiReturn::wrongParams('外出登记失败');
        }
    }

    /**
     * 获取今日外出次数
     */
    public function actionDakacount(){
        if(yii::$app->request->isPost){
            $user = $this->_user();
            $row = OaKaoqingWaichu::find()->where(['is_del' =>0, 'u_id'=>$user['u_id']])
                ->andFilterWhere(['between', 'ctime', date('Y-m-d 00:00:01'), date('Y-m-d 23:59:59')]);
            return ApiReturn::success('外出次数', ['count' => $row->count()]);
        }else{
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /**
     * 外出查询（手机端)
     */
    public function actionGetmlist(){
        $param = Yii::$app->request->get();
        /*$page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;*/
        $t = date('t');

        $user = $this->_user();
        $row = OaKaoqingwaichu::find()->where(['is_del' => 0, 'u_id' => $user['u_id']]);
        $row->andFilterWhere(['between', 'ctime', date('Y-m').'-01 00:00:01', date('Y-m'.'-'.$t.' 23:59:59')]);
        //$list = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $list = $row->orderBy('ctime DESC')->asArray()->all();
        $data['list'] = $list;

        #$data['totalnum'] = $row->count();

        return ApiReturn::success('获取成功',$data);
    }

}
