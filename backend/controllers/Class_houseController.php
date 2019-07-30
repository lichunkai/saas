<?php
namespace backend\controllers;

use backend\models\Class_house;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 房源等级控制器
 */
class Class_houseController extends AuthController
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
        $row=Class_house::find()->where(['is_del'=>0,'company_id'=>0,'ch_type'=>$param['ch_type']]);
        $list=$row->asArray()->all();
//        if(empty($list)){
//            //初始化
//            $row=Class_house::find()->where(['is_del'=>0,'company_id'=>0,'ch_type'=>$param['ch_type']]);
//            $list=$row->all();
//            foreach($list as $v){
//               $model= new Class_house;
//                $model->ch_name=$v->ch_name;
//                $model->ch_type=$v->ch_type;
//                $model->ch_private_return=$v->ch_private_return;
//                $model->ch_private_creturn=$v->ch_private_creturn;
//                $model->ch_public_return=$v->ch_public_return;
//                $model->ch_public_creturn=$v->ch_public_creturn;
//                $model->ch_private_look=$v->ch_private_look;
//                $model->ch_private_clook=$v->ch_private_clook;
//                $model->ch_public_look=$v->ch_public_look;
//                $model->ch_public_clook=$v->ch_public_clook;
//                $model->company_id=$this->_user['company_id'];
//                $model->c_id=$this->_user['u_id'];
//                $model->u_id=$this->_user['u_id'];
//                $model->ctime=date('Y-m-d H:i:s');
//                $model->utime=date('Y-m-d H:i:s');
//                $model->save();
//
//            }
//            $row=Class_house::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'ch_type'=>$param['ch_type']]);
//            $list=$row->asArray()->all();
//        }
        $data['list'] = $list;
        return ApiReturn::success('查询成功',$data);
    }
    /*
     * 编辑
     */
    public function actionEdit(){
        $post=Yii::$app->request->post();
        foreach($post['ch_id'] as $key=>$v){
            $model=Class_house::find()->where(['ch_id'=>$post['ch_id'][$key],'company_id'=>0])->one();
            $model->ch_private_genjin= $post['ch_private_genjin'][$key];
            $model->ch_private_visit= $post['ch_private_visit'][$key];
            $model->ch_store_genjin= $post['ch_store_genjin'][$key];
            $model->ch_store_visit= $post['ch_store_visit'][$key];
            $model->ch_company_genjin= $post['ch_company_genjin'][$key];
            $model->ch_company_visit= $post['ch_company_visit'][$key];
            $result = $model->save();
        }
        if($result){
            return ApiReturn::success('保存成功');
        }else{
            return ApiReturn::wrongParams('保存失败');
        }

    }
}
