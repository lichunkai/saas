<?php
/**
 * Created by PhpStorm.
 * User: daiyu
 * Date: 2017/12/26
 * Time: 16:59
 */

namespace common\actions\common;

use backend\models\YsDept;
use backend\models\YsEmployee;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;
use yii\base\Action;
use yii\helpers\Html;

class GetEmployeeListAction extends Action
{
    public function run()
    {
        $request = Yii::$app->request;
        $param = $request->post();

        $model = new YsEmployee();
        $query = $model::find()->alias('a')->select('a.*,b.*')->leftJoin('ys_employee_post b','a.employee_id=b.employee_id')->where(['a.is_del' => 0]);
        if(isset($param['id']) && $param['id']){
            $dept_str = explode(',',trim($this->get_dept_id($param['id']),','));
            $query->andWhere(['in','b.dept_id',$dept_str]);
        }

        $employee = $query->orderBy('a.ctime desc')->asArray()->all();
        //echo $query->createCommand()->getRawSql();
        return ApiReturn::success('获取成功',$employee);
    }

    private function get_dept_id($id)
    {
        $id_str = $id.',';
        $child_dept = YsDept::find()->where(['dept_pid'=>$id,'is_del'=>0])->asArray()->all();
        foreach( $child_dept as $key => $val )
            $id_str .= $this->get_dept_id( $val["dept_id"]);
        return $id_str;

    }
}
