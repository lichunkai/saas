<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\House;
use backend\models\Customer;
use backend\models\Customer_daikan;
use backend\models\Customer_daikan_house;
use backend\models\Customer_follow;
use backend\models\HouseFollowup;
use common\models\gii\HouseKeyGii;
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
class BatchController extends AuthController
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
     * 批量更新 客源带看 客源跟进 房源带看
     * @return array|\common\models\json
     */
    public function actionCount_ch()
    {
		 ini_set('memory_limit', '200M');
		//公客 更新维护人
        $data_gk=Customer::find()->andWhere(['is_del'=>0,'customer_private'=>"公客"])->all();
        if (!empty($data_gk)) {
            foreach ($data_gk as $vg) {
                if($vg->c_id){
                    $vg->c_id=NULL;
                    echo '客源'.$vg->customer_id.$vg->customer_private.'维护人不是null</br>';
                    $vg->save();
                }

            }
        }
		//房源批量更新维护人
        $data_gp=House::find()->andWhere(['is_del'=>0,'house_private'=>0])->all();
		if (!empty($data_gp)) {
            foreach ($data_gp as $vg) {
                if($vg->private_user){
                    $vg->private_user=NULL;
                    echo '房源'.$vg->house_id.'维护人不是null</br>';
                    $vg->save();
                }

            }
        }
        $data=Customer::find()->andWhere(['is_del'=>0])->all();
        foreach($data as $v){
            //等级纠正
            Customer::dengjipanding($v->customer_id);
            //查找当前的客户带看
            $daikan_data=Customer_daikan::find()->andWhere(['is_del'=>0,'customer_id'=> $v->customer_id])->all();
            foreach($daikan_data as $b){
                //查找带看的房源
               $dk_count =Customer_daikan_house::find()->andWhere(['is_del'=>0,'d_id'=>  $b->d_id])->count();
                if(!$dk_count){
                        echo $b->d_id.'没有带看房源';
                     Customer_daikan::find()->andWhere(['is_del'=>0,'d_id'=> $b->d_id])->one()->delete();
                    echo '<br>';
                    echo $dk_count;
                    echo '<br>';
                }
            }
            //计算带看数
            $daikan=  Customer_daikan::find()->andWhere(['is_del'=>0,'customer_id'=> $v->customer_id])->count();
            //计算跟进数
            $gengjin=Customer_follow::find()->andWhere(['is_del'=>0,'customer_id'=> $v->customer_id])->count();
            $v->genjincishu=$gengjin;
            $v->daikancishu=$daikan;
            $v->save();
        }
        //房源批量更新
        $data=House::find()->andWhere(['is_del'=>0])->all();
        foreach($data as $v){
            //带看次数
            $daikan=  Customer_daikan_house::find()->andWhere(['is_del'=>0,'house_id'=> $v->house_id])->count();
            $v->daikancishu=$daikan;
            //带看时间
            $daikan_shijian= Customer_daikan_house::find()->andWhere(['is_del'=>0,'house_id'=> $v->house_id])->orderBy('ctime DESC')->one();
            if(!empty($daikan_shijian)){
                $v->daikanshijian= $daikan_shijian->ctime;
            }
            //跟进时间
            $genjin_shijian= HouseFollowup::find()->andWhere(['is_del'=>0,'house_id'=> $v->house_id])->orderBy('ctime DESC')->one();
            if(!empty($genjin_shijian)){
                if($v->house_private==0){
                    $v->quanyuangenjin= $genjin_shijian->ctime;
                }
                if($v->house_private==1){
                    $v->weihurengenjin= $genjin_shijian->ctime;
                }
            }
            //计算跟进次数
            $gengjin=  HouseFollowup::find()->andWhere(['is_del'=>0,'house_id'=> $v->house_id])->count();
            $v->genjincishu=$gengjin;
            $ys_count=HouseKeyGii::find()->where(['is_del'=>0,'house_id'=> $v->house_id])->andWhere('hk_status=1 or hk_status=2')->count();
            if($ys_count){
                $v->is_yaoshi=1;
                echo $v->house_sn.'有钥匙';
                echo '<br>';
                echo '钥匙数'.$ys_count;
                echo '<br>';
            }else{
                $v->is_yaoshi=0;
                echo $v->house_sn.'没钥匙';
                echo '<br>';
                echo '钥匙数'.$ys_count;
                echo '<br>';
            }
            $v->save();
        }


       echo '更新成功';
    }

}
