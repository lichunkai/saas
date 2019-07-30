<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\News;
use backend\models\Notice;
use backend\models\Purview;
use backend\models\Rank;
use backend\models\Role;
use backend\models\OrgCompany;
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
class User_deployController extends AuthController
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
     * user数据
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        //var_dump($listdata['userlist']);die;
        $this->_user['company']=OrgCompany::find()->where(['company_id'=> $this->_user['company_id']])->asArray()->one();
        return ApiReturn::success('获取成功', $this->_user);
    }


}
