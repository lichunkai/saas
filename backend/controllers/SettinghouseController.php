<?php
namespace backend\controllers;

use backend\models\ZhSettingHouse;
use common\models\ApiReturn;
use Yii;

/**
 * 房源补充设置控制器
 */
class SettinghouseController extends AuthController
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
     * 房源补充配置列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;
        $row = ZhSettingHouse::find()->where(['company_id'=>$this->_user['company_id']]);
        if(isset($param['kw']) && $param['kw']){
            $row->andWhere(['like','hsetting_type',trim($param['kw'])]);
        }
        $data['list']=$row->orderBy('hsetting_id DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $data['totalnum'] = $row->count();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 房源补充配置添加
     */
    public function actionAdd(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingHouse();
            $result = $model->UpdateSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['hsetting_id']) && $param['hsetting_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 房源补充配置编辑
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingHouse();
            $result = $model->UpdateSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['hsetting_id']) && $param['hsetting_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 房源补充配置子配置添加
     */
    public function actionAddchild(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingHouse();
            $result = $model->UpdateChildSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['hsetting_id']) && $param['hsetting_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 房源补充配置子配置项更新
     */
    public function actionEditchild(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingHouse();
            $result = $model->UpdateChildSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['hsetting_id']) && $param['hsetting_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 房源补充配置子配置项删除
     */
    public function actionDelchild(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingHouse();
            $result = $model->DelChildSetting($post,$this->_user());
            if($result){
                return ApiReturn::success('删除成功');
            }else{
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }

    /**
     * 获取房源补充配置子配置
     */
	public function actionGetchildlist(){
		$post = Yii::$app->request->post();
		if(!Yii::$app->request->isPost||empty($post['hsetting_id'])){
			return ApiReturn::wrongParams('参数有误');
		}
		$setting = ZhSettingHouse::find()->where(['hsetting_id'=>$post['hsetting_id']])->asArray()->one();
		if($setting&&!empty($setting)){
			return ApiReturn::success('获取成功',json_decode($setting['hsetting_child'], true));
		}else{
			return ApiReturn::wrongParams('没有找到相关子配置');
		}
	}
}
