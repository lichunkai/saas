<?php
namespace backend\controllers;

use backend\models\ZhSettingQujian;
use common\models\ApiReturn;
use Yii;

/**
 * 区间设置控制器
 */
class SettingqujianController extends AuthController
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
     * 区间配置列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;
        $row = ZhSettingQujian::find();
        $row->where(['company_id'=>$this->_user['company_id']]);
        if(isset($param['kw']) && $param['kw']){
            //$row->andWhere(['like','qujian_name',trim($param['kw'])]);
            $row->andWhere(['or', ['like','qujian_name',trim($param['kw'])], ['like', 'qujian_shorthand', trim($param['kw'])]]);
        }
        $data['list']=$row->orderBy('qujian_id DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $data['totalnum'] = $row->count();
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 区间配置添加
     */
    public function actionAdd(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingQujian();
            $result = $model->UpdateSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['qujian_id']) && $param['qujian_id']){
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
     * 区间配置修改
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingQujian();
            $result = $model->UpdateSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['qujian_id']) && $param['qujian_id']){
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
     * 区间子配置添加
     */
    public function actionAddchild(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingQujian();
            $result = $model->UpdateChildSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['qujian_id']) && $param['qujian_id']){
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
     * 区间子配置修改
     */
    public function actionEditchild(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingQujian();
            $result = $model->UpdateChildSetting($post,$this->_user());
            $message = '添加';
            if(isset($param['qujian_id']) && $param['qujian_id']){
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
     * 区间子配置删除
     */
    public function actionDelchild(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingQujian();
            $result = $model->DelChildSetting($post,$this->_user());
            if($result){
                return ApiReturn::success('删除成功');
            }else{
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }

    /**
     * 获取子配置
     */
	public function actionGetchildlist(){
		$post = Yii::$app->request->post();
		if(!Yii::$app->request->isPost||empty($post['qujian_id'])){
			return ApiReturn::wrongParams('参数有误');
		}
		$setting = ZhSettingQujian::find()->where(['qujian_id'=>$post['qujian_id']])->asArray()->one();
		if($setting&&!empty($setting)){
			return ApiReturn::success('获取成功',json_decode($setting['qujian_desp'], true));
		}else{
			return ApiReturn::wrongParams('没有找到相关子配置');
		}
	}
}
