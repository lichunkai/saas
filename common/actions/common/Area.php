<?php

namespace common\actions\common;

use backend\models\YsArea;
use common\models\ApiReturn;
use Yii;
use yii\base\Action;
use yii\helpers\Html;


class Area extends Action
{
    public function run()
    {
        //yii::
        $request = Yii::$app->request;
        // 返回所有参数
        $params['parentId']= $request->get("parentId");
        $params['level']= $request->get("level");
        $parentId = isset($params["parentId"])&&$params["parentId"]?$params["parentId"]:0;
        $level = isset($params["level"])&&$params["level"]?$params["level"]:1;
        $model = new YsArea();
        $area = $model::find()->select('ar_id,ar_name')->where(['ar_p_id' => $parentId,'ar_level' => $level])->asArray()->all();

        return ApiReturn::success('查询成功',$area);
    }
}