<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\Notify;
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
 * 通知控制器
 */
class NotifyController extends AuthController
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
        $Notifytype = ZhSettingBase::find()->where(['company_id'=>$this->_user['company_id'],'base_shorthand' => 'Notify_type'])->select('base_desp')->asArray()->one();
        $result['Notifytype'] = json_decode($Notifytype['base_desp'],true);

        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /*
     * 通知列表
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 100;
        $start = ($page - 1) * $pagesize;

        $row = Notify::find();//->where(['is_del' => 0]);
	    $row->where(['company_id'=>$this->_user['company_id'],'n_u_id'=>$this->_user['u_id'],'is_del'=>'0']);
//	    $row->andWhere(['is_del' => $param['is_del']]);
//        if (isset($param['n_is_read']) && $param['n_is_read']) {
//            $row->andWhere(['n_is_read' => $param['n_is_read']]);
//        }

	    $row ->andWhere(['<=','n_time',date('Y-m-d',time())]);

        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['like', 'n_content', $kw]);
        }
        $row =  $row->orderBy(['n_time'=>SORT_DESC])->limit($pagesize)->offset($start)->asArray()->all();
	    $hasreadMesList=[];
	    $unreadMesList=[];

        if(is_array($row)){
        	foreach ($row as  $item){
        		if($item['n_is_read']=='0'){
			        $unreadMesList[]=$item;
		        }else{
			        $hasreadMesList[]=$item;
		        }
	        }
        }

        return ApiReturn::success('获取成功', ['hasreadMesList'=>$hasreadMesList,'unreadMesList'=>$unreadMesList]);
    }

    /*
     * 通知添加
     * @return array|\common\models\json
     */
    public function actionAdd()
    {
        $param = Yii::$app->request->post();
        $Notifymodel = new Notify();

        $param['is_pop'] = $param['is_pop']== true ? 1 : 0;

        $Notifymodel->cid = $this->_user['u_id'];
        $Notifymodel->uid = $this->_user['u_id'];
        $Notifymodel->company_id = $this->_user['company_id'];
        $Notifymodel->ctime = date('Y-m-d H:i:s');
        $Notifymodel->utime = date('Y-m-d H:i:s');


        if($Notifymodel->load($param, '') && $Notifymodel->save()){
            return ApiReturn::success('添加成功');
        }else{
            $errors = $Notifymodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /*
     * 通知更新
     * @return array|\common\models\json
     */
    public function actionEdit()
    {
        $param = Yii::$app->request->post();
        $Notifymodel =  Notify::findOne($param['Notify_id']);

        $param['is_pop'] = $param['is_pop']== true ? 1 : 0;

        $Notifymodel->u_id = $this->_user['u_id'];
        $Notifymodel->utime = date('Y-m-d H:i:s');

        if($Notifymodel->load($param, '') && $Notifymodel->save()){
            return ApiReturn::success('更新成功');
        }else{
            $errors = $Notifymodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /*
     * 通知删除
     * @return array|\common\models\json
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('Notify_id');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = Notify::findOne($id);
        $model->is_del = 1;
        $model->utime = date('Y-m-d H:i:s');
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::success('删除失败');
        }
    }

    /*
     * 设置为已读
     * @return array|\common\models\json
     */
    public function actionRead()
    {
        $id = Yii::$app->request->post('Notify_id');
        $status = Yii::$app->request->post('n_is_read');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = Notify::findOne($id);
        $model->n_is_read = 1;
        $model->utime = date('Y-m-d H:i:s');
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('设置已读成功');
        } else {
            return ApiReturn::success('设置已读失败');
        }
    }




}



