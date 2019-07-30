<?php

namespace backend\controllers;


use backend\models\CustomColumns;
use backend\models\OrderSell;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingQujian;
use common\models\ApiReturn;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 房屋出租订单控制器
 */
class OrderrentController extends AuthController
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
        $company_id = $this->_user['company_id'];
        $order_status = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'order_status'])->select('base_desp')->asArray()->one();
        $result['order_status'] = json_decode($order_status['base_desp'],true);
        $house_use = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'house_use'])->select('base_desp')->asArray()->one();
        $result['house_use'] = json_decode($house_use['base_desp'],true);
        $order_timechoose = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'order_timechoose'])->select('base_desp')->asArray()->one();
        $result['order_timechoose'] = json_decode($order_timechoose['base_desp'],true);
        $order_usertype = ZhSettingBase::find()->where(['company_id'=>$company_id,'base_shorthand' => 'order_usertype'])->select('base_desp')->asArray()->one();
        $result['order_usertype'] = json_decode($order_usertype['base_desp'],true);
        $jgqj = ZhSettingQujian::find()->where(['company_id'=>$company_id,'qujian_shorthand' => 'jgqj'])->select('qujian_desp')->asArray()->one();
        $result['jgqj'] = json_decode($jgqj['qujian_desp'],true);
        $mjqj = ZhSettingQujian::find()->where(['company_id'=>$company_id,'qujian_shorthand' => 'mjqj'])->select('qujian_desp')->asArray()->one();
        $result['mjqj'] = json_decode($mjqj['qujian_desp'],true);

        //加载自定义列表
        $customcolumns = CustomColumns::find()->select('columns')->where(['company_id'=>$company_id,'u_id'=>$this->_user['u_id'],'module'=>1,'is_del'=>0])->asArray()->one();
        $result['customcolumns'] = json_decode($customcolumns['columns'],true);

        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user); //var_dump($result['topbutton']);die;
        if ($result) {
            return ApiReturn::success('查询成功', $result);
        } else {
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /*
    * 房屋出售订单列表
    * @return array|\common\models\json
    */
    public function actionGetindex()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 10;
        $start = ($page - 1) * $pagesize;
        $company_id = $this->_user['company_id'];

        $row = OrderSell::find()->where(['company_id'=>$company_id,'is_del' => 0,'order_type'=>1])->with(['created' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }])->with(['updated' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }]);

        if (isset($param['order_status']) && $param['order_status']) {
            $row->andWhere(['order_status' => trim($param['order_status'])]);
        }
        if (isset($param['house_use']) && $param['house_use']) {
            $row->andWhere(['house_type' => trim($param['house_use'])]);
        }
        $datecolumn = 'order_deal_date';
        if (isset($param['order_timechoose']) && $param['order_timechoose']) {
            if($param['order_timechoose'] = '成交时间'){
                $datecolumn = 'order_deal_date';
            }else if($param['order_timechoose'] = '合同结案时间'){
                $datecolumn = 'order_contract_date';
            }else if($param['order_timechoose'] = '佣金结案时间'){
                $datecolumn = 'order_commission_date';
            }
        }

        if (isset($param['daterange']) && $param['daterange']) {
            $row ->andWhere(['>=',$datecolumn,$param['daterange'][0]])->andWhere(['<=',$datecolumn,$param['daterange'][1].' 23:59:59']);
        }
        if (isset($param['house_area']) && !empty($param['house_area'][0])) {
            $row ->andWhere(['>=','house_area',$param['house_area'][0]])->andWhere(['<=','house_area',$param['house_area'][1]]);
        }
        if (isset($param['order_price']) && empty($param['order_price'][0])) {
            $row ->andWhere(['>=','order_price',0])->andWhere(['<=','order_price',$param['order_price'][1]]);
        }else if(isset($param['order_price']) && empty($param['order_price'][1])){
            $row ->andWhere(['>=','order_price',$param['order_price'][0]]);
        }else if(isset($param['order_price'])){
            $row ->andWhere(['>=','order_price',$param['order_price'][0]])->andWhere(['<=','order_price',$param['order_price'][1]]);
        }
        if (isset($param['kw']) && $param['kw']) {
            $kw = trim($param['kw']);
            $row->andWhere(['or', ['like', 'order_sn', $kw], ['like', 'owner_sn', $kw], ['like', 'customer_sn', $kw]]);
        }

        $listdata['totalnum'] = $row->count();
        $listdata['listdata'] = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        $authwhere = Yii::$app->LoadData->checkDataByUser($this->id.'/'.$this->action->id,$this->_user);
        foreach ($listdata['listdata'] as $key => $val){
            $listdata['listdata'][$key]['c_user'] = $val['created']['u_name'];
            $listdata['listdata'][$key]['u_user'] = $val['updated']['u_name'];
            $listdata['listdata'][$key]['contract_image_num'] = count(json_decode($val['contract_image'],true));
            $listdata['listdata'][$key]['is_jump'] = 0;
            if($authwhere['key'] == 'all'){
                $listdata['listdata'][$key]['is_jump'] = 1;
            }else{
                if($authwhere['value'] == $val[$authwhere['key']]){
                    $listdata['listdata'][$key]['is_jump'] = 1;
                }
            }
        }
        //echo $row->createCommand()->getRawSql();

        return ApiReturn::success('获取成功', $listdata);
    }


    /*
     * @经纪人导出
     */
    public function actionExport()
    {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $company_id = $this->_user['company_id'];

        $row = OrderSell::find()->where(['company_id'=>$company_id,'is_del' => 0,'order_type'=>1])->with(['created' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }])->with(['updated' => function ($query) {
            $query->select(['u_id', 'u_name']);
        }]);
//        $authwhere = Yii::$app->LoadData->checkDataByUser('orderrent/getindex',$this->_user);
//        if($authwhere['key'] == 'none'){
//            return ApiReturn::success('无查看权限！');
//        }else if($authwhere['key'] != 'all'){
//            $columns = $authwhere['key'];
//            $row->andWhere([$columns=>$authwhere['value']]);
//        }
        $result['list'] = $row->orderBy('order_deal_date DESC')->asArray()->all();
        $data = $result['list'];
        $n=0;
        foreach ( $data as $v )
        {
            //报表头的输出
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:S1');
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','租赁成交');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','租赁成交');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','租赁成交');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('S2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','合同编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','订单状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','分成状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','成交日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','区域');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','小区');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','座栋');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','单元');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','门牌号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','业主');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3','客户');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3','面积');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3','成交价');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3','协议编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3','房源编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3','客源编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3','维护人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3','成交人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('S3','录入人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3' )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A3:S3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+4) ,$v['order_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+4) ,$v['order_status']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,$v['order_divide_status']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+4) ,$v['order_deal_date']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+4) ,$v['dts_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,$v['village_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+4) ,$v['house_building']);
            $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n+4) ,$v['house_unit']);
            $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n+4) ,$v['house_door']);
            $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n+4) ,$v['owner_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('K'.($n+4) ,$v['customer_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('L'.($n+4) ,$v['house_area']);
            $objectPHPExcel->getActiveSheet()->setCellValue('M'.($n+4) ,$v['order_price']);
            $objectPHPExcel->getActiveSheet()->setCellValue('N'.($n+4) ,$v['agreement_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('O'.($n+4) ,$v['owner_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('P'.($n+4) ,$v['customer_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('Q'.($n+4) ,$v['order_deal_username']);
            $objectPHPExcel->getActiveSheet()->setCellValue('R'.($n+4) ,$v['created']['u_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('S'.($n+4) ,$v['updated']['u_name']);
            $n = $n +1;
        }

        //设置分页显示
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header("Content-type: text/html; charset=utf-8");
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'chengjiao-'.date("Y/m/j").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');
    }
}
