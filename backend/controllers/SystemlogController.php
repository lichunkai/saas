<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\SystemLog;
use backend\models\User;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\models\gii\DepartGii;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 系统日志控制器
 */
class SystemlogController extends AuthController
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
     * 系统日志列表列表
     * @return string
     */
    public function actionLoglist()
    {
        $param = Yii::$app->request->get();

        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;

        $row = SystemLog::find()->where(['company_id'=>$this->_user['company_id'],'is_del'=>0]);

        if(isset($param['name']) && $param['name']){
            $row->andWhere(['like','log_uname',trim($param['name'])]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['loglist'] = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();

        return ApiReturn::success('获取成功',$listdata);
    }




}
