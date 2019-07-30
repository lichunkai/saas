<?php
namespace backend\controllers;

use backend\models\Class_if;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingQujian;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 房源等级控制器
 */
class Class_ifController extends AuthController
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
    /*
     *房源等级列表
     */
    public function actionIndex(){
        $param = Yii::$app->request->get();
        $row=Class_if::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'ci_type'=>$param['ci_type']]);
        $list=$row->asArray()->all();
        $data['list'] = $list;

        $row=ZhSettingBase::find()->where(['base_id'=>27]);
        $data['ci_class']=$row->asArray()->all();
        $row=ZhSettingBase::find()->where(['base_id'=>28]);
        $data['ci_if_czcs']=$row->asArray()->all();
        if($param['ci_type'] ==3 || $param['ci_type'] ==5 ) {
            $row = ZhSettingBase::find()->where(['base_id' => 76]);
            $data['ci_if_qgqz'] = $row->asArray()->all();
        }elseif($param['ci_type'] ==6){
            $row = ZhSettingBase::find()->where(['base_id' => 104]);
            $data['ci_if_qgqz'] = $row->asArray()->all();
        }
        else{
            $row = ZhSettingBase::find()->where(['base_id' => 1085]);
            $data['ci_if_qgqz'] = $row->asArray()->all();
        }
        $row=ZhSettingBase::find()->where(['base_id'=>30]);
        $data['ci_property']=$row->asArray()->all();
        $row=ZhSettingBase::find()->where(['base_id'=>32]);
        $data['ci_fitment']=$row->asArray()->all();
        $row=ZhSettingQujian::find()->where(['qujian_id'=>14]);
        $data['ci_price']=$row->asArray()->all();
        $row=ZhSettingQujian::find()->where(['qujian_id'=>15]);
        $data['ci_area']=$row->asArray()->all();
        $row=ZhSettingBase::find()->where(['base_id'=>35]);
        $data['ci_house_use']=$row->asArray()->all();
        //二手房条件
        if($param['ci_type'] == 1){
            $row=ZhSettingBase::find()->where(['base_id'=>84]);
            $data['fangyuan_tag']=$row->asArray()->all();
            $row = ZhSettingBase::find()->where(['base_id' => 84]);
            $data['ci_if_qgqz'] = $row->asArray()->all();
        }else if($param['ci_type'] == 0 || $param['ci_type'] == 2){
            $row=ZhSettingBase::find()->where(['base_id'=>54]);
            $data['fangyuan_tag']=$row->asArray()->all();
            $row = ZhSettingBase::find()->where(['base_id' => 54]);
            $data['ci_if_qgqz'] = $row->asArray()->all();
        }

        return ApiReturn::success('查询成功',$data);
    }
    /*
   * 添加
   */
    public function actionAdd(){
        $post=Yii::$app->request->post();
        if(!empty($post)){
            $model=new Class_if;
            $model->ci_type= $post['ci_type'];
            $model->ci_class= $post['ci_class'];
            $model->ci_name= $post['ci_name'];
            $model->ci_if= $post['ci_if'];
            $model->ci_daikan= !empty($post['ci_daikan'])?$post['ci_daikan']:null;
            $model->ci_property= !empty($post['ci_property'])?$post['ci_property']:null;
            $model->ci_fitment= !empty($post['ci_fitment'])?$post['ci_fitment']:null;
//            $model->ci_price_min= $post['ci_price_min'];
//            $model->ci_price_max= $post['ci_price_max'];
//            $model->ci_area_min= $post['ci_area_min'];
//            $model->ci_area_max= $post['ci_area_max'];
            $model->ci_house_use= !empty($post['ci_house_use'])?$post['ci_house_use']:null;
            $user=$this->_user();
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->auth_cid = $user['auth_cid'];
            $model->company_id = $this->_user['company_id'];
            $model->auth_rid = $user['auth_rid'];
            $model->auth_sid = $user['auth_sid'];
            $model->auth_aid = $user['auth_aid'];
            $model->auth_baid = $user['auth_baid'];
            $result = $model->save();
            if($result){
                return ApiReturn::success('保存成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
        }
    }
    /*
     * 编辑
     */
    public function actionEdit(){
        $post=Yii::$app->request->post();
        if(!empty($post)){
            $model=Class_if::find()->where(['ci_id'=>$post['ci_id'],'company_id'=>$this->_user['company_id']])->one();
            $model->ci_type= $post['ci_type'];
            $model->ci_class= $post['ci_class'];
            $model->ci_name= $post['ci_name'];
            $model->ci_if= $post['ci_if'];
            $model->ci_daikan= !empty($post['ci_daikan'])?$post['ci_daikan']:null;
            $model->ci_property= !empty($post['ci_property'])?$post['ci_property']:null;
            $model->ci_fitment= !empty($post['ci_fitment'])?$post['ci_fitment']:null;
//            $model->ci_price_min= $post['ci_price_min'];
//            $model->ci_price_max= $post['ci_price_max'];
//            $model->ci_area_min= $post['ci_area_min'];
//            $model->ci_area_max= $post['ci_area_max'];
            $model->ci_house_use= !empty($post['ci_house_use'])?$post['ci_house_use']:null;
            $user=$this->_user();
            $model->c_id = $user['u_id'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return ApiReturn::success('保存成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
         }

    }
    /*
 * 删除
 */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(empty($post['ci_id'])){
            return ApiReturn::wrongParams('数据异常');
        }
        $Class_if = Class_if::findOne($post['ci_id']);
        $Class_if->is_del = 1;
        $result = $Class_if->update();

        if($result){
            return ApiReturn::success('删除成功');
        }else{
            return ApiReturn::wrongParams('删除失败');
        }
    }


}