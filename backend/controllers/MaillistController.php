<?php

namespace backend\controllers;

use backend\models\Depart;
use backend\models\User;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 通讯录控制器
 */
class MaillistController extends AuthController
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
     * 获取选择参数
     * @return array|\common\models\json
     */
    public function actionGetsetting()
    {
        $depart = Depart::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $result['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /*
     * 通讯录列表
     * @return array|\common\models\json
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;

        //判断是否显示离职人员
        $loginuser = User::find()->alias('a')->select('a.*,b.role_id,b.role_name,b.role_type')->where(['a.company_id'=>$this->_user['company_id'],'a.u_id'=>$this->_user['u_id']])->leftJoin('zh_role b','a.u_role_id = b.role_id')->asArray()->one();
        $roleType = Yii::$app->params['roleType'];
        $listdata['showstatus'] = 0;
        if(isset($roleType[$loginuser['role_type']]) && $roleType[$loginuser['role_type']] == '大区'){
            $listdata['showstatus'] = 1;
        }

        $row = User::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->with(['depart' => function ($query) {
            $query->select(['d_id', "d_name"]);
        }])->with(['role' => function ($query) {
            $query->select(['role_id', 'role_name']);
        }]);

        if (isset($param['did']) && $param['did']) {
            $row->andWhere(['u_dept_id' => $param['did']]);
        }
        if($listdata['showstatus'] == 1){
            if (isset($param['sid']) && $param['sid']) {
                $row->andWhere(['u_status' => $param['sid']]);
            }else{
                $row->andWhere(['or','u_status=1','u_status=2','u_status=3']);
            }
        }else{
            $row->andWhere(['or','u_status=1','u_status=2','u_status=3']);
        }

        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['like', 'u_name', $kw]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['userlist'] = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();die;

        foreach ($listdata['userlist'] as $key => $item) {
            $listdata['userlist'][$key]['u_sex_text'] = $listdata['userlist'][$key]['u_sex'] == 1 ? '男' : ($listdata['userlist'][$key]['u_sex'] == 2 ? '女' : '-');
            $listdata['userlist'][$key]['depart_name'] = $item['depart']['d_name'];
            $listdata['userlist'][$key]['role_name'] = $item['role']['role_name'];
            if($listdata['userlist'][$key]['u_status'] == 1){
                $listdata['userlist'][$key]['u_status_text'] = '在职';
            }elseif($listdata['userlist'][$key]['u_status'] == 2){
                $listdata['userlist'][$key]['u_status_text'] = '休假';
            }elseif($listdata['userlist'][$key]['u_status'] == 3){
                $listdata['userlist'][$key]['u_status_text'] = '锁定';
            }elseif($listdata['userlist'][$key]['u_status'] == 4){
                $listdata['userlist'][$key]['u_status_text'] = '离职';
            }elseif($listdata['userlist'][$key]['u_status'] == 5){
                $listdata['userlist'][$key]['u_status_text'] = '开除';
            }
            $arr = [];
            $listdata['userlist'][$key]['departpath'] = $this->_getDepartpath($item['u_dept_id'], $arr);
        }
        //var_dump($listdata);die;
        return ApiReturn::success('获取成功', $listdata);
    }

    /*
     * @经纪人导出
     */
    public function actionExport()
    {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);

        $row = User::find()->where(['company_id'=>$this->_user['company_id'],'is_del' => 0])->with(['role' => function ($query) {
            $query->select(['role_id', 'role_name']);
        }]);

        $result['list']=$row->orderBy('u_id ASC')->asArray()->all();
        $data = $result['list'];
        $n=0;
        foreach ( $data as $v )
        {
            //报表头的输出
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','通讯录');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','通讯录');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','通讯录');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','姓名');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','员工编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','性别');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','联系方式');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','职务');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','入职时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('A3:G3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('A3:G3' )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:G3' )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:K3' )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:G3' )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:G3' )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+4) ,$v['u_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+4) ,$v['u_employee_id']);
            if($v['u_sex'] ==1 ){
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,'男');
            }else if ($v['u_sex'] ==2){
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,'女');
            }else {
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,'未知');
            }
            $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+4) ,$v['u_phone']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+4) ,$v['role']['role_name']);
            if($v['u_status'] ==1 ){
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,'在职');
            }else if ($v['u_status'] ==2){
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,'休假');
            }else if ($v['u_status'] ==3){
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,'锁定');
            }else if ($v['u_status'] ==4){
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,'离职');
            }else if ($v['u_status'] ==5){
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,'开除');
            }
            $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+4) ,$v['u_entry_time']);
            $n = $n +1;
        }

        //设置分页显示
        //$objectPHPExcel->getActiveSheet()->setBreak( 'I55' , PHPExcel_Worksheet::BREAK_ROW );
        //$objectPHPExcel->getActiveSheet()->setBreak( 'I10' , PHPExcel_Worksheet::BREAK_COLUMN );
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header("Content-type: text/html; charset=utf-8");
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'tongxunlu-'.date("Y/m/j").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');
    }

}
