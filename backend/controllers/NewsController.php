<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\News;
use backend\models\Notice;
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
 * 资讯控制器
 */
class NewsController extends AuthController
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
        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /*
     * 公告列表
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;

        $row = News::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0]);

        if (isset($param['daterange']) && $param['daterange']) {
            $row ->andWhere(['>=','ctime',$param['daterange'][0]])->andWhere(['<=','ctime',$param['daterange'][1].' 23:59:59']);
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['like', 'news_title', $kw]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['newslist'] = $row->orderBy(['is_top'=>SORT_DESC,'utime'=>SORT_DESC])->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;
        $listdata['newslist'] = Yii::$app->LoadData->listButton($this->id, $listdata['newslist'], $this->_user);
//        var_dump($listdata['newslist']);die;
        return ApiReturn::success('获取成功', $listdata);
    }

    /*
     * 公告添加
     * @return array|\common\models\json
     */
    public function actionAdd()
    {
        $param = Yii::$app->request->post();
        $noticemodel = new News();

        $param['is_pop'] = $param['is_pop'] === "true" ? 1 : 0;

        $noticemodel->company_id = $this->_user['company_id'];
        $noticemodel->cid = $this->_user['u_id'];
        $noticemodel->uid = $this->_user['u_id'];
        $noticemodel->ctime = date('Y-m-d H:i:s');
        $noticemodel->utime = date('Y-m-d H:i:s');


        if($noticemodel->load($param, '') && $noticemodel->save()){
            return ApiReturn::success('添加成功');
        }else{
            $errors = $noticemodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /*
     * 公告更新
     * @return array|\common\models\json
     */
    public function actionEdit()
    {
        $param = Yii::$app->request->post();


        $param['is_pop'] = $param['is_pop'] === "true" ? 1 : 0;

        $result = $this->_validateDataOwner('zh_news','news_id',$param['news_id'],$this->_user['company_id'],false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }
        $noticemodel =  News::findOne($param['news_id']);

        $noticemodel->uid = $this->_user['u_id'];
        $noticemodel->utime = date('Y-m-d H:i:s');

        if($noticemodel->load($param, '') && $noticemodel->save()){
            return ApiReturn::success('更新成功');
        }else{
            $errors = $noticemodel->getErrors();
            return ApiReturn::wrongParams(current($errors));
        }
    }

    /*
     * 公告删除
     * @return array|\common\models\json
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('news_id');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }

        $result = $this->_validateDataOwner('zh_news','news_id',$id,$this->_user['company_id'],false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }
        $model = News::findOne($id);
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
     * 公告置顶
     * @return array|\common\models\json
     */
    public function actionTop()
    {
        $id = Yii::$app->request->post('news_id');
        $status = Yii::$app->request->post('status');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $result = $this->_validateDataOwner('zh_news','news_id',$id,$this->_user['company_id'],false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }
        $model =  News::findOne($id);

        $model->is_top = $status;
        $model->utime = date('Y-m-d H:i:s');
        $result = $model->save();
        if ($result) {
            return ApiReturn::success('置顶成功');
        } else {
            return ApiReturn::success('置顶失败');
        }
    }

    /*
    * 快讯弹出
    * @return array|\common\models\json
    */
    public function actionEject()
    {
        $id = Yii::$app->request->post('news_id');
        $status = Yii::$app->request->post('status');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $result = $this->_validateDataOwner('zh_news','news_id',$id,$this->_user['company_id'],false);
        if($result === false){  //验证数据
            return ApiReturn::forbidden('无数据或无查看权限！');
        }
        $model =  News::findOne($id);

        $model->is_pop = $status;
        $model->utime = date('Y-m-d H:i:s');
        $result = $model->save();
        if ($result) {
            if($status == 1){
                return ApiReturn::success('弹出成功');
            }else{
                return ApiReturn::success('取消成功');
            }

        } else {
            return ApiReturn::success('弹出失败');
        }
    }
}
