<?php
namespace backend\controllers;

use backend\models\Class_customer;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 房源等级控制器
 */
class Class_customerController extends AuthController
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
        $row=Class_customer::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
        if(!empty($param['cc_type'])){
            $row->andWhere(['cc_type'=>$param['cc_type']]);
        }else{
            $row->andWhere(['cc_type'=>$param['cc_type']]);
        }
        $list=$row->asArray()->all();
        if(empty($list)){
            //初始化
            $row=Class_customer::find()->where(['is_del'=>0,'company_id'=>0]);
            if(!empty($param['cc_type'])){
                $row->andWhere(['cc_type'=>$param['cc_type']]);
            }else{
                $row->andWhere(['cc_type'=>$param['cc_type']]);
            }
            $list=$row->all();
            foreach($list as $v){
                $model= new Class_customer;
                $model->cc_name=$v->cc_name;
                $model->cc_type=$v->cc_type;
                $model->cc_private_return=$v->cc_private_return;
                $model->cc_private_creturn=$v->cc_private_creturn;
                $model->cc_public_return=$v->cc_public_return;
                $model->cc_public_creturn=$v->cc_public_creturn;
                $model->cc_private_look=$v->cc_private_look;
                $model->cc_private_clook=$v->cc_private_clook;
                $model->cc_public_look=$v->cc_public_look;
                $model->cc_public_clook=$v->cc_public_clook;
                $model->company_id=$this->_user['company_id'];
                $model->c_id=$this->_user['u_id'];
                $model->u_id=$this->_user['u_id'];
                $model->ctime=date('Y-m-d H:i:s');
                $model->utime=date('Y-m-d H:i:s');
                $model->save();

            }
            $row=Class_customer::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'cc_type'=>$param['cc_type']]);
            $list=$row->asArray()->all();
        }

        $data['list'] = $list;
        return ApiReturn::success('查询成功',$data);
    }
    /*
     * 编辑
     */
    public function actionEdit(){
        $post=Yii::$app->request->post();
        foreach($post['cc_id'] as $key=>$v){
            $model=Class_customer::find()->where(['cc_id'=>$post['cc_id'][$key],'company_id'=>$this->_user['company_id']])->one();
            $model->cc_private_return= $post['cc_private_return'][$key];
            $model->cc_private_creturn= $post['cc_private_creturn'][$key];
            $model->cc_public_return= $post['cc_public_return'][$key];
            $model->cc_public_creturn= $post['cc_public_creturn'][$key];
            $model->cc_private_look= $post['cc_private_look'][$key];
            $model->cc_private_clook= $post['cc_private_clook'][$key];
            $model->cc_public_look= $post['cc_public_look'][$key];
            $model->cc_public_clook= $post['cc_public_clook'][$key];
            $result = $model->save();
        }
        if($result){
            return ApiReturn::success('保存成功');
        }else{
            return ApiReturn::wrongParams('保存失败');
        }

    }
}