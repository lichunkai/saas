<?php
namespace backend\controllers;

use backend\models\Gongzi;
use backend\models\User;
use backend\models\Depart;
use backend\models\OrderSellDivide;
use backend\models\OrderSellCommission;
use common\models\ApiReturn;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 学区控制器
 */
class GongziController extends AuthController
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
     *工资列表
     */
    public function actionIndex(){
        $param = Yii::$app->request->get();
        $page = isset($param["page"])&&$param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page-1)*$pagesize;
        $row=Gongzi::find()->alias('g')
            ->select('g.*,u.*')
            ->leftJoin('zh_user as u','u.u_id=g.user_id')
            ->where(['g.is_del'=>0,'g.company_id'=>$this->_user['company_id']]);
        if(!empty($param["shijian"])){
            $shijian=date('Y-m',strtotime($param["shijian"]));
            $row->andWhere(['g.gongziriqi'=>$shijian]);
        }
        if(!empty($param["d_id"])){
            $row->andWhere(['u.u_dept_id'=>$param["d_id"]]);
        }
        if(!empty($param["user"])){
            $row->andWhere(['g.user_id'=>$param["user"]]);
        }
        if(empty($param["exporturl"])){
            $row->limit($pagesize)->offset($start);
        }
        $row->orderBy('gongziriqi DESC');
        $list= $row->asArray()->all();
        if(!empty($list)){
            foreach($list as $key=>$v){
               $wuxiangeren =isset($v['wuxiangeren'])?$v['wuxiangeren']:'';
               $wuxianyijingeren =isset($v['wuxianyijingeren'])?$v['wuxianyijingeren']:'';
                $list[$key]['wuxian']= $wuxiangeren?$wuxiangeren:$wuxianyijingeren;
                $list[$key]['u_employee_no']= $v['u_employee_prefix'].$v['u_employee_no'];
                $list[$key]['kaishiriqi']= date('Y-m-d',strtotime($v['kaishiriqi']));
                $list[$key]['jieshuriqi']= date('Y-m-d',strtotime($v['jieshuriqi']));
                if($v['gr_yjjs']){
                    $list[$key]['gr_tichengjine']=$v['gr_yjjs'].'='.$v['gr_tichengjine'];
                }
                if($v['gld_yjjs']){
                    $list[$key]['gld_tichengjine']=$v['gld_yjjs'].'='.$v['gld_tichengjine'];
                }
                if($v['zyd_yjjs']){
                    $list[$key]['zyd_tichengjine']=$v['zyd_yjjs'].'='.$v['zyd_tichengjine'];
                }
                if($v['is_payoff']==1){
                    $list[$key]['is_payoff']='已发';
                }else{
                    $list[$key]['is_payoff']='未发';
                }

                if($v['user_id']){
                    $user= User::find()->where(['u_id'=>$v['user_id'],'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
                    $list[$key]['jingjiren']=$user['u_name'];
                    if($user['u_dept_id']){
                        $depart= Depart::find()->where(['d_id'=>$user['u_dept_id'],'is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->one();
                        $list[$key]['bumen']=$depart['d_name'];
                    }
                }
            }
        }
        $thismonth_start=mktime(0,0,0,date('m'),1,date('Y'));
        $thismonth_end=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $data['shijian']['thismonth_start']=date('Y-m-d',$thismonth_start);
        $data['shijian']['thismonth_end']=date('Y-m-d',$thismonth_end);
        if(!empty($param["exporturl"]) && $param["exporturl"]=='yes'){
            $this->export($list);
            exit;
        }
        $data['list'] = $list;
        $data['count'] = $row->count();
        //部门数据
        $depart = Depart::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        //员工数据
        $principal = User::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->asArray()->all();
        foreach ($principal as $key => $item) {
            $data['peizhi']['users'][$item['u_dept_id']][] = $item;
        }
        return ApiReturn::success('查询成功',$data);
    }
    /*
     * 批量计算
     */
    public function actionAdd(){
        $post=Yii::$app->request->post();
        if(empty($post['kaishiriqi']) && empty($post['jieshuriqi'])){
            return ApiReturn::success('生成工资失败，时间错误');
        }
        if(yii::$app->request->isPost && !empty($post)){
            $gongzi=new Gongzi();
            $result=$gongzi->updateGongzi($post,$this->_user());
            if($result){
                return ApiReturn::success('生成工资成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('生成工资失败');
        }
    }
    /*
     *工资发放
     */
    public function actionPayoff(){
        $post=Yii::$app->request->post();
        $fagongzi =  date('Y-m', strtotime($post['fagongzi']));
        $gongzi = Gongzi::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'gongziriqi' => $fagongzi,'is_payoff'=>1])->asArray()->one();
        if(!empty($gongzi)){
            return ApiReturn::wrongParams('操作失败['.$fagongzi.']工资已经发放!');
        }
        $gongzi = Gongzi::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'], 'gongziriqi' => $fagongzi])->asArray()->one();
        if(!$gongzi){
            return ApiReturn::wrongParams('您未生成['.$fagongzi.']工资,请先生成工资。');
        }
        $request= Gongzi::updateAll(['is_payoff'=>1],['gongziriqi'=>$fagongzi]);
        if($request){
            return ApiReturn::success('发放工资成功');
        }else{
            return ApiReturn::wrongParams('发放工资失败');
        }

    }


    /*
     * 重算
     */
    public function actionRsedit(){
        $get=Yii::$app->request->get();
        if(empty($get['kaishiriqi']) && empty($get['jieshuriqi'])){
            return ApiReturn::success('生成工资失败，时间错误');
        }
        if(empty($get['u_id'])){
            return ApiReturn::success('生成工资失败，用户不能为空');
        }
        if(yii::$app->request->isGet && !empty($get)){
            $gongzi=new Gongzi();
            $result=$gongzi->rsEdit($get,$this->_user());
            if($result){
                return ApiReturn::success('重新生成工资成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('重新生成工资失败');
        }
    }
    /*
     * 获取明细
     */
    public function actionGetmingxi(){
        $param=Yii::$app->request->get();
        //业绩查看
        $kaishiriqi=strtotime($param['kaishiriqi']);
        $jieshuriqi =$param['jieshuriqi'];
       $jieshuriqi= strtotime("$jieshuriqi+ 1 day");
        $v=User::find()->where(['u_id'=>$param['user_id'],'company_id'=>$this->_user['company_id']])->asArray()->one();
        //负责人业绩查看

        $row = OrderSellDivide::find()->alias('d')->select('oc.*,d.*,d.ctime as dtime,os.*,cst.*,cst.ctime as cstctime')
            ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
            ->leftJoin('zh_order_sell as os', 'os.order_id=d.order_id')
            ->leftJoin('zh_order_sell_cost as cst', 'cst.cost_id=d.cost_id')
            ->where(['d.is_del'=>0,'d.company_id'=>$this->_user['company_id']]);
        $user_id=$param['user_id'];
        $row->andWhere("oc.tc_dfzr = '$user_id' or oc.tc_qfzr = '$user_id' or oc.tc_dqfzr = '$user_id' or oc.user_id = '$user_id'");
        if(!empty($param['kw'])){
            $kw=$param['kw'];
            $row->andWhere("os.order_sn like '%$kw%'");
        }
        $osd =$row->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi")->asArray()->all();
        foreach($osd as $key=>$v){
            $osd[$key]['dtime']=date('Y-m-d',strtotime($v['dtime']));
            if($v['cost_type']==1){
                $osd[$key]['cost_type']='佣金类型';
            }else{
                $osd[$key]['cost_type']='杂项类型';
            }
            if($v['user_id']<>$user_id && $user_id==$v['tc_dfzr']){
                $osd[$key]['user_name']=$v['user_name'].'(直营店业绩)';
            }
            if($v['user_id']<>$user_id && $user_id<>$v['tc_dfzr']){
                $osd[$key]['user_name']=$v['user_name'].'(管理店业绩)';
            }
            if($v['user_id']==$user_id){
                $osd[$key]['user_name']=$v['user_name'].'(个人业绩)';
            }
            $osd[$key]['jisuanfangfa']=$v['cost_money'].'*'.$v['divide_per'].'%';
        }
        $data['list'] = $osd;
        $data['count'] = $row->count();
        return ApiReturn::success('查询成功',$data);
    }
    function export($list){
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $n=0;
        foreach ( $list as $v )
        {
            //报表头的输出
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','工资信息表');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','工资信息表');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','工资信息表');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','员工编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6.5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','月份');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(6.5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','开始日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','结束日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','员工姓名');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','部门');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','个人总业绩');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','个人提成金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','直营店总业绩');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','直营店提成金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3','管理店总业绩');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3','管理店提成金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3','社保（个人）');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3','基本工资');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3','奖');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3','罚');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3','工资合计');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('A3:Q3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('A3:Q3' )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:Q3' )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:Q3' )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:Q3' )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:Q3' )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+4) ,$v['u_employee_no']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+4) ,$v['gongziriqi']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,$v['kaishiriqi']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+4) ,$v['jieshuriqi']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+4) ,$v['jingjiren']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,!empty($v['bumen'])?$v['bumen']:'无');
            $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+4) ,$v['gr_zongyeji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n+4) ,$v['gr_tichengjine']);
            $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n+4) ,$v['zyd_zongyeji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n+4) ,$v['zyd_tichengjine']);
            $objectPHPExcel->getActiveSheet()->setCellValue('K'.($n+4) ,$v['gld_zongyeji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('L'.($n+4) ,$v['gld_tichengjine']);
            $objectPHPExcel->getActiveSheet()->setCellValue('M'.($n+4) ,$v['wuxian']);
            $objectPHPExcel->getActiveSheet()->setCellValue('N'.($n+4) ,$v['jibengongzi']);
            $objectPHPExcel->getActiveSheet()->setCellValue('O'.($n+4) ,$v['jiangli']);
            $objectPHPExcel->getActiveSheet()->setCellValue('P'.($n+4) ,$v['fakuan']);
            $objectPHPExcel->getActiveSheet()->setCellValue('Q'.($n+4) ,$v['hejigongzi']);
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
        header('Content-Disposition:attachment;filename="'.'gzxxb-'.date("Y-m-j").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');
    }

}