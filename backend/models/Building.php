<?php

namespace backend\models;

use common\models\gii\BuildingGii;
use Yii;
use common\helps\Tools;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Building extends BuildingGii
{
    /*
 * 添加楼栋
 */
    public function UpdateBuilding($param,$user){
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        //得到最大的楼栋
        $r_count =max($param['bu_ridgepole']);

        try {
            for($i=1;$i<=$r_count;$i++){
                if(in_array($i,$param['bu_ridgepole'])){
                    $b_data=Building::find()->where(['bu_ridgepole'=>$i,'h_id'=> $param['h_id']])->one();
                    if(!empty($b_data)){
                        //如果楼栋存在就把他的状态设置为未删除
                        if($b_data->is_del){
                            $b_data->is_del=0;
                            if($b_data->save() === false){
                                $transaction->rollBack();
                                return false;
                            };
                        }
                    }else{
                        //数据库里面没有这个楼栋，新增
                        $model = new Building();
                        $model->bu_ridgepole = $i;
                        $model->h_id = $param['h_id'];
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

                    //如果楼栋存在就把他的状态设置为删除
                    $b_data=Building::find()->where(['bu_ridgepole'=>$i,'h_id'=> $param['h_id']])->one();
                    if($b_data &&  !$b_data->is_del){
                        $b_data->is_del=1;
                        if($b_data->save() === false){
                            $transaction->rollBack();
                            return false;
                        };
                    }
                };
            }
            //循环判断本次提交的楼栋，来确定把本次未选的楼栋设置为删除状态

            $b_data=Building::find()->where(['h_id'=> $param['h_id']])->andWhere(['>','bu_ridgepole',$r_count])->all();
              //把本次比最大值的楼栋都大的楼栋全部设置为删除
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