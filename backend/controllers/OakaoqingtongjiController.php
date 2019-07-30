<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaHrStaff;
use backend\models\OaKaoqingGuanli;
use backend\models\OaKaoqingSetting;
use backend\models\OaKaoqingTpl;
use backend\models\OaQingjia;
use backend\models\User;
use backend\models\ZhSettingBase;
use common\helps\Tools;
use common\models\ApiReturn;
use common\models\gii\OaQingjiaGii;
use Yii;

/**
 * 考勤统计管理控制器
 */
class OakaoqingtongjiController extends AuthController
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
     * 考勤统计管理列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();

        $arr = $this->_getData($param);
        $data['list'] = $arr[0];

        $row = User::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']]);
        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere(['u_dept_id' => $param['d_id']]);
        }
        $data['totalnum'] = $row->count();

        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 导出考勤统计
     */
    public function actionExport(){
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $param = Yii::$app->request->get();
        $res = $this->_getData($param, false);
        $list = $res[0];

        //表格头的输出
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','序号');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','门店');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','员工');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','月份');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','天数');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','应到(天)');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','实到(天)');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','请假(次)');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','迟到(天)');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','早退(次)');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K1','下班未打卡(次)');

        $line = 2;
        foreach($list as $key => $val){
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$line, $line - 1);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$line, $val['d_name']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$line, $val['u_name']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$line, $val['kq_month']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$line, $val['tianshu']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$line, $val['yingdao']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$line, $val['shidao']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$line, $val['qingjia']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$line, $val['chidao']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$line, $val['zaotui']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$line, $val['xiabanweidaka']);
            $line++;
        }

        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();

        header("Content-type: text/html; charset=utf-8");
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'kaoqintongji-'.date("Y-m-j").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');
    }

    private function _getData($param, $flag = true){
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $month = date('Y-m');
        if(isset($param['selMonth']) && $param['selMonth']){
            $month = $param['selMonth'];
            $days = date('t', strtotime($param['selMonth'].'-01'));
        }else{
            //$date = date('Y-m-d');
            $days = date('t');
        }

        $weekList = array(0=>'周日', 1=>'周一', 2=>'周二', 3=>'周三', 4=>'周四', 5=>'周五', 6=>'周六');

        // 遍历部门
        $res = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->all();
        $departWorkDays = [];
        foreach($res as $key => $val){
            $paiban = OaKaoqingSetting::find()->where(['d_id' => $val['d_id']])->asArray()->one();
            if($paiban){
                $tpl = OaKaoqingTpl::findOne($paiban['kq_tp_id']);
            }else{
                $p_paiban = $this->_getParentKqTpl($val['d_pid']);
                $tpl = OaKaoqingTpl::findOne($p_paiban['kq_tp_id']);
            }
            $kq_tp_days = explode(',', $tpl['kq_tp_days']);
            $workDays = 0;
            for($i=1; $i<=$days; $i++){
                $week = $weekList[date("w", strtotime($month.'-'.$i))];
                if(in_array($week, $kq_tp_days)){
                    $workDays++;
                }
            }
            $departWorkDays[$val['d_id']] = $workDays;
        }

        // 员工请假次数查询
        $res = Yii::$app->getDb()->createCommand('select staff_id, count(*) as qj from oa_qingjia where is_del=0 and company_id="'.$this->_user['company_id'].'"
            and (st_time between "'.$month.'-01" and "'.$month.'-'.$days.'"
            or ed_time between "'.$month.'-01" and "'.$month.'-'.$days.'") group by staff_id')->queryAll();
        $staffQingjia = [];
        foreach($res as $key => $val){
            $staffQingjia[$val['staff_id']] = $val['qj'];
        }

        $d_cond = '';
        if (isset($param['d_id']) && $param['d_id'] && $param['d_id'] != "undefined") {
            $d_cond = ' and a.d_id = '.$param['d_id'];
        }

        // 员工考勤
        $sql = 'select DATE_FORMAT(kq_date,\'%Y-%m\') kq_month, \''.$days.'\' as tianshu,
            sum(case when a.flag in (\'0\', \'1\', \'2\', \'3\', \'4\') then 1 else 0 end) as \'weidaka\', 
            sum(case a.flag when 2 then 1 else 0 end) as \'xiabanweidaka\', 
            sum(case when a.flag in (\'3\', \'5\', \'6\') then 1 else 0 end) as \'chidao\', 
            sum(case when a.flag in (\'4\', \'6\', \'7\') then 1 else 0 end) as \'zaotui\', 
            sum(case when a.flag in (\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'9\') then 1 else 0 end) as \'shidao\', 
            sum(case a.flag when 8 then 1 else 0 end) as \'zhengchang\', 
            u.u_name, d.d_name, d.d_id, a.staff_id from oa_kaoqing_guanli a left join zh_user u on u.u_id = a.staff_id left join zh_depart d on d.d_id = a.d_id
            WHERE (a.is_del=0) and kq_date between "'.$month.'-01" and "'.$month.'-'.$days.'" and flag != 8 '. $d_cond . '
            group by staff_id ';
        $totalList = Yii::$app->getDb()->createCommand($sql)->queryAll();
        $sql .= ($flag ? 'limit '.$start.', '.$pagesize : '');
        $list = Yii::$app->getDb()->createCommand($sql)->queryAll();

        foreach($list as $key => $val){
            $val['yingdao'] = array_key_exists($val['d_id'], $departWorkDays) ? $departWorkDays[$val['d_id']] : '未知';
            $val['qingjia'] = array_key_exists($val['staff_id'], $staffQingjia) ? $staffQingjia[$val['staff_id']] : 0;
            $list[$key] = $val;
        }

        return [$list, count($totalList)];
    }

}
