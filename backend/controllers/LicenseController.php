<?php
namespace backend\controllers;

use backend\models\Button;
use backend\models\User;
use backend\models\UserAuth;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\LicenseGii;
use Yii;

/**
 * 登录控制器
 */
class LicenseController extends AuthController
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

    public function actionGetindex(){

	    $param = Yii::$app->request->post();
	    $page = isset($param["page"])&&$param["page"] ? $param["page"] : 1;
	    $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
	    $start = ($page-1)*$pagesize;
	    $row=LicenseGii::find()->where(['company_id'=>$this->_user['company_id'],'is_del'=>0]);
	    if(isset($param["is_pass"])){
		    $row->andWhere(['is_pass'=>$param["is_pass"]]);
	    }
	    if(!empty($param["kw"])){
		    $row->andWhere("mendian like '%" . $param["kw"]."%'"
			    . " or `shenqingren` like '%" . $param["kw"]."%'"
			    . " or `remake` like '%" . $param["kw"]."%'"
		    );
	    }

	    if (isset($param["daterange"]) && $param["daterange"]) {  //时间
		    $row->andWhere(['between','ctime',$param["daterange"][0], $param["daterange"][1]]);
	    }
	    $list=$row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
	    $data['list'] = $list;
	    $data['count'] = $row->count();
	    return ApiReturn::success('查询成功',$data);
    }

    public function actionSetpass(){
	    $param = Yii::$app->request->post();
	    $License = LicenseGii::findOne($param['id']);
	    if($License){
	    	$License->is_pass = $param['is_pass'];
	    	$License->utime= date("Y-m-d H:i:s",time());
	    	if($License->save()){
			    return ApiReturn::success('修改成功');
		    }else{
			    return ApiReturn::success('修改失败');
		    }
	    }else{
		    return ApiReturn::wrongParams('修改失败');
	    }
    }





}
