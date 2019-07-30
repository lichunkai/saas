<?php

namespace backend\models;

use common\models\gii\ElementGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Element extends ElementGii
{
    /*
* 添加单元
*/
    public function Updateelement($param,$user){
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        //得到最大的单元
        $r_count =max($param['el_element']);
        try {
            for($i=1;$i<=$r_count;$i++){
                //循环判断本次提交的单元，来确定把本次未选的单元设置为删除状态
                if(in_array($i,$param['el_element'])){
                    $b_data=Element::find()->where(['el_element'=>$i,'h_id'=> $param['h_id'],'bu_id'=>$param['bu_id']])->one();
                    if($b_data){
                        //如果单元存在就把他的状态设置为未删除
                        if($b_data->is_del){
                            $b_data->is_del=0;
                            if($b_data->save() === false){
                                $transaction->rollBack();
                                return false;
                            };
                        }
                    }else{
                        //数据库里面没有这个单元，新增
                        $model = new Element();
                        $model->el_element = $i;
                        $model->h_id = $param['h_id'];
                        $model->bu_id = $param['bu_id'];
                        $model->cid = $user['u_id'];
                        $model->uid = $user['u_id'];
                        $model->utime = date('Y-m-d H:i:s');
                        $model->ctime = date('Y-m-d H:i:s');
                        if($model->save() === false){
                            $transaction->rollBack();
                            return false;
                        };
                    }
                }else{
                    //如果单元存在就把他的状态设置为删除
                    $b_data=Element::find()->where(['el_element'=>$i,'h_id'=> $param['h_id'],'bu_id'=>$param['bu_id']])->one();
                    if($b_data &&  !$b_data->is_del){
                        $b_data->is_del=1;
                        if($b_data->save() === false){
                            $transaction->rollBack();
                            return false;
                        };
                    }
                };
            }
            $b_data=Element::find()->where(['h_id'=> $param['h_id'],'bu_id'=>$param['bu_id']])->andWhere(['>','el_element',$r_count])->all();
            //把本次比最大值的单元都大的单元全部设置为删除
            if($b_data){
                foreach ($b_data as $k => $v) {
                    $v->is_del = 1;
                    if ($v->update() === false) {
                        $transaction->rollBack();
                        return false;
                    };
                }
            }

            $transaction->commit();
            return true;
        }catch(Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

}