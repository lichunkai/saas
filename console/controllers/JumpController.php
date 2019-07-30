<?php
namespace console\controllers;
use yii\console\Controller;
use common\models\gii\ProjectGii;
use common\models\ApiReturn;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\gii\ComBizGii;
use common\models\gii\ComCommunityGii;
use common\models\gii\LianjiaXiaoquGii;

/**
 * 跳公控制器
 */
class JumpController extends Controller
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
     *客源列表
     */
    public function actionIndex(){
        $data=ProjectGii::find()->where(['is_del'=>0])->asArray()->all();
        if(!empty($data)){
            foreach($data as $v){
               $c= $v['project_class'];
               $f= $v['project_fnc'];
                $c::$f();
            }

        }
    }

	public function actionSetcom(){

		for($i=0;$i<60;$i++){
			$xqList = LianjiaXiaoquGii::find()->select('*')->alias('a')->leftJoin('lianjia_xiaoqu_xq as b','a.id=b.id')->limit(100)->offset($i*100)->asArray()->all();
			if(is_array($xqList)){
				foreach ($xqList as $item){

					$count = ComCommunityGii::find()->where(['cu_name'=>$item['xiaoqu']])->count();
					if($count>0){
						continue;
					}

					$cmu = new ComCommunityGii();
					$biz = ComBizGii::find()->where(['biz_name'=>$item['bizcircle']])->asArray()->one();
					if($biz){
						$cmu->biz_name = $biz['biz_name'];
						$cmu->biz_id = $biz['biz_id'];
					}else{
						echo $item['bizcircle'];
					}
					$cmu->cu_name = $item['xiaoqu'];
					$cmu->cu_price = intval($item['unitprice']);
					$cmu->cu_address = $item['address'];
					$cmu->cu_fwzs = intval($item['fwzs']);
					$cmu->cu_jctime = $item['jctime'];
					$cmu->cu_kfs = $item['kfs'];
					$cmu->cu_wyf = $item['wyf'];
					$cmu->cu_status = 0;
					$cmu->cu_ldzs = intval($item['ldzs']);
					$cmu->cu_wygs = $item['wygs'];

					$cmu->c_id = $cmu->u_id=0;
					$cmu->ctime = $cmu->utime =date('Y-m-d H:i:s',time());
					if($cmu->save()){
						echo "成功\t\n";
					}else{

						var_dump($cmu->getErrors());
						echo "失败\t\n";
						exit;
					}
					unset($biz);
					unset($cmu);

				}
			}
			unset($xqList);

		}

	}

}