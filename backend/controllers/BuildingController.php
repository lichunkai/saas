<?php
namespace backend\controllers;

use backend\models\Building;
use backend\models\Element;
use backend\models\School_district;
use backend\models\Tower;
use Codeception\Command\Build;
use common\models\ApiReturn;
use Yii;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel2007;
use PHPExcel_Style_Alignment;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * 用户控制器
 */
class BuildingController extends AuthController
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
     * 添加房源楼栋字典
     */
    public function actionChoosebuilding()
    {
        $param = Yii::$app->request->get();
        if(!isset($param['id']) || empty($param['id'])){
            return ApiReturn::wrongParams('参数错误');
        }
        $h_id = $param['id'];
        $data['list'] = Building::find()->select(['bu_id','bu_ridgepole as label', 'h_id'])->where(['h_id' => $h_id,'is_del' => 0])->asArray()->all();
        //获取学区
        $school = School_district::find()->alias('a')->select('b.s_name')->where(['a.rn_id'=>$param['id']])->leftJoin('zh_school b','a.s_id=b.s_id')->asArray()->one();
        $data['school'] = $school['s_name'];
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 添加房源单元字典
     */
    public function actionChooseelement()
    {
        $param = Yii::$app->request->get();
        if(!isset($param['id']) || empty($param['id'])){
            return ApiReturn::wrongParams('参数错误');
        }
        $bu_id = $param['id'];
        $data['list'] = Element::find()->select(['el_id','el_element as label'])->where(['bu_id' => $bu_id,'is_del' => 0])->asArray()->all();
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 添加房源房号字典
     */
    public function actionChoosetower()
    {
        $param = Yii::$app->request->get();
        if(!isset($param['id']) || empty($param['id'])){
            return ApiReturn::wrongParams('参数错误');
        }
        $el_id = $param['id'];
        $data['list'] = Tower::find()->select(['t_id','bu_h_number as label'])->where(['el_id' => $el_id,'is_del' => 0])->asArray()->all();
        return ApiReturn::success('获取成功', $data);
    }

    /*
    * 楼栋列表
    */
    public function actionBuildinglist()
    {
        $param = Yii::$app->request->get();
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 1888;
        $start = ($page - 1) * $pagesize;
        $h_id = $param['h_id'];
        $row = Building::find()->select(['bu_id','remark','bu_ridgepole as label', 'h_id'])->where(['is_del' => 0, 'h_id' => $h_id]);
        $data['list'] = $row->orderBy('bu_ridgepole ASC')->limit($pagesize)->offset($start)->asArray()->all();
        /**
         * 项目销售统计已售
         * @return \yii\db\ActiveQuery
         */
        foreach ($data['list'] as $key => $v) {
            $row = Tower::find()->where(['bu_id' => $v['bu_id'], 'bu_market' => 1, 'is_del' => 0]);
            $keshou_count = $row->count();
            $data['list'][$key]['keshou_count'] = $keshou_count ? $keshou_count : 0;
        }

        // 项目销售统计可售
        $row = Tower::find()->where(['h_id' => $h_id, 'bu_market' => 1, 'is_del' => 0]);
        $keshou_count = $row->count();
        $data['keshou_count'] = $keshou_count ? $keshou_count : 0;
        // 项目销售统计已售
        $row = Tower::find()->where(['h_id' => $h_id, 'bu_market' => 2, 'is_del' => 0]);
        $yishou_rg = $row->count();
        $row = Tower::find()->where(['h_id' => $h_id, 'bu_market' => 3, 'is_del' => 0]);
        $yishou_qy = $row->count();
        $data['yishou_count'] = ($yishou_rg + $yishou_qy) ? ($yishou_rg + $yishou_qy) : 0;;
        return ApiReturn::success('获取成功', $data);
    }

    /*
     * 添加楼栋
     */

    public function actionUpdatebuilding()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            $model = new Building();
            $result = $model->UpdateBuilding($post, $this->_user());
            if ($result) {
                return ApiReturn::success('保存成功');
            }
        }
    }

    /*
     * 备注
     */
    public function actionBq()
    {
        $post = Yii::$app->request->post();
        if(empty($post['gaibian'])){
            exit;
        }
        $b_data=Building::find()->where(['bu_id'=>$post['bu_id']])->one();
        if($b_data){
            //如果楼栋存在就把他的状态设置为未删除
                $b_data->remark=$post['gaibian'];
                if($b_data->save()){
                    return ApiReturn::success('保存成功');
                };
            }
    }

    /*
     * 单元列表
    */
    public function actionElement()
    {
        $param = Yii::$app->request->get();
        $bu_id = Yii::$app->request->get('bu_id');
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 1888;
        $start = ($page - 1) * $pagesize;
        $row = Element::find()->select(['bu_id', 'el_element as label', 'el_id', 'h_id'])->where(['is_del' => 0, 'bu_id' => $bu_id]);
        $data['list'] = $row->orderBy('el_element ASC')->limit($pagesize)->offset($start)->asArray()->all();
        /**
         * 楼栋销售统计已售
         * @return \yii\db\ActiveQuery
         */
        foreach ($data['list'] as $key => $v) {
            $row = Tower::find()->where(['el_id' => $v['el_id'], 'bu_market' => 1, 'is_del' => 0]);
            $keshou_count = $row->count();
            $data['list'][$key]['keshou_count'] = $keshou_count ? $keshou_count : 0;
        }

        // 项目销售统计可售
        $row = Tower::find()->where(['bu_id' => $bu_id, 'bu_market' => 1, 'is_del' => 0]);
        $keshou_count = $row->count();
        $data['keshou_count'] = $keshou_count ? $keshou_count : 0;
        // 项目销售统计已售
        $row = Tower::find()->where(['bu_id' => $bu_id, 'bu_market' => 2, 'is_del' => 0]);
        $yishou_rg = $row->count();
        $row = Tower::find()->where(['bu_id' => $bu_id, 'bu_market' => 3, 'is_del' => 0]);
        $yishou_qy = $row->count();
        $data['yishou_count'] = ($yishou_rg + $yishou_qy) ? ($yishou_rg + $yishou_qy) : 0;
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 添加单元
     */
    public function actionUpdateelement()
    {
        $post = Yii::$app->request->post();
        if (yii::$app->request->isPost) {
            $model = new Element();
            $result = $model->Updateelement($post, $this->_user());
            if ($result) {
                return ApiReturn::success('保存成功');
            }
        }
    }

    /*
     * 房号列表
     */
    public function actionTowerlist()
    {
        $param = Yii::$app->request->get();
        $el_id = Yii::$app->request->get('el_id');
        $page = isset($param['page']) ? $param['page'] : 1;
        $pagesize = isset($param['pagesize']) ? $param['pagesize'] : 1888;
        $start = ($page - 1) * $pagesize;
        $row = Tower::find()->where(['is_del' => 0, 'el_id' => $el_id]);
        if ($param['kw'] <> '') {
            $row->andWhere(['=', 'bu_market', $param['kw']]);
        }
        $data['list'] = $row->orderBy(['sort' => SORT_ASC])->limit($pagesize)->offset($start)->asArray()->all();
        $row1 = Tower::find()->select(['bu_floor'])->where(['is_del' => 0, 'el_id' => $el_id]);
        if ($param['kw'] <> '') {
            $row1->andWhere(['like', 'bu_market', $param['kw']]);
        }
        $data['list1'] = $row1->orderBy(['sort' => SORT_ASC])->groupBy('bu_floor')->limit($pagesize)->offset($start)->asArray()->all();
        // 单元销售统计可售
        $row = Tower::find()->where(['el_id' => $el_id, 'bu_market' => 1, 'is_del' => 0]);
        $keshou_count = $row->count();
        $data['keshou_count'] = $keshou_count ? $keshou_count : 0;
        // 单元销售统计销控
        $row = Tower::find()->where(['el_id' => $el_id, 'bu_market' => 0, 'is_del' => 0]);
        $xiaokong_count = $row->count();
        $data['xiaokong_count'] = $xiaokong_count ? $xiaokong_count : 0;
        // 单元销售统计认购
        $row = Tower::find()->where(['el_id' => $el_id, 'bu_market' => 2, 'is_del' => 0]);
        $yishou_rg = $row->count();
        $data['yishou_rg'] = $yishou_rg ? $yishou_rg : 0;
        // 单元销售统计签约
        $row = Tower::find()->where(['el_id' => $el_id, 'bu_market' => 3, 'is_del' => 0]);
        $yishou_qy = $row->count();
        $data['yishou_qy'] = $yishou_qy ? $yishou_qy : 0;
        return ApiReturn::success('获取成功', $data);
    }

    /*
     * 房号更新
     */
    public function actionUpdatatower()
    {
        $post = Yii::$app->request->post();
        if (isset($post['t_id']) && $post['t_id'] && $post['bu_market'] <> "") {
            $model = Tower::findOne($post['t_id']);
            $model->bu_market = $post['bu_market'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if ($result) {
                return ApiReturn::success('保存成功');
            } else {
                return ApiReturn::success('保存失败');
            }
        }
    }

    /*
     * 上传房号
     */
    public function actionImport()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        $post = Yii::$app->request->post();
        $file = UploadedFile::getInstanceByName('file');//获取上传的文件实例
        if ($file) {

            if ($file->type == 'application/vnd.ms-excel') {
                $excelFile = $file->tempName;//获取文件名name
                //这里就是导入PHPExcel包了，要用的时候就加这么两句，方便吧
                $phpexcel = new PHPExcel;
                $excelReader = PHPExcel_IOFactory::createReader('Excel5');

                $phpexcel = $excelReader->load($excelFile)->getSheet(0);//载入文件并获取第一个sheet
                $total_line = $phpexcel->getHighestRow();
                $total_column = $phpexcel->getHighestColumn();
                try {
                    $b_data = Tower::find()->where(['el_id' => $post['el_id']])->all();
                    foreach ($b_data as $v) {
                        $v->is_del = 1;
                        if ($v->update() === false) {
                            $transaction->rollBack();
                            return false;
                        };
                    }
                    for ($row = 2; $row <= $total_line; $row++) {
                        $data = array();
                        for ($column = 'A'; $column <= $total_column; $column++) {
                            $data[] = trim($phpexcel->getCell($column . $row)->getValue());
                        }
                        $b_data = Tower::find()->where(['el_id' => $post['el_id'], 'bu_floor' => $data[1], 'bu_h_number' => $data[2]])->one();
                        if (!empty($b_data) && $data[2]) {

//                            $h_data = Order::find()->where(['tower_id' => $b_data->t_id])->one();
//                            if (!empty($h_data)) {
//                                if ($h_data['order_status'] != $data[5]) {
//                                    $transaction->rollBack();
//                                    $msg = "房号 (" . $b_data->bu_h_number . " ) 与产生的订单 (" . $h_data['case_sn'] . ") 的销控状态冲突";
//                                    return ApiReturn::success($msg);
//                                }
//                            }

                            $b_data->sort = $data[0];
                            $b_data->bu_floor = $data[1];
                            $b_data->bu_h_number = $data[2];
                            $b_data->bu_acreage = $data[3];
                            $b_data->bu_total = $data[4];
                            $b_data->bu_market = 1;
//                            switch ($data[5]) {
//                                case '销控':
//                                    $b_data->bu_market = 0;
//                                    break;
//                                case '可售':
//                                    $b_data->bu_market = 1;
//                                    break;
//                                case '认购':
//                                    $b_data->bu_market = 2;
//                                    break;
//                                case '网签':
//                                    $b_data->bu_market = 3;
//                                    break;
//                                case '草签':
//                                    $b_data->bu_market = 4;
//                                    break;
//                            }
                            $b_data->is_del = 0;
                            if ($b_data->update() === false) {
                                $transaction->rollBack();
                                return ApiReturn::success('导入失败');
                            };
                        } else if ($data[2]) {
                            $model = new Tower();
                            $model->h_id = $post['h_id'];
                            $model->bu_id = $post['bu_id'];
                            $model->el_id = $post['el_id'];
                            $model->sort = $data[0];
                            $model->bu_floor = $data[1];
                            $model->bu_h_number = $data[2];
                            $model->bu_acreage = $data[3];
                            $model->bu_total = $data[4];
                            $model->bu_market = 1;
//                            switch ($data[5]) {
//                                case '销控':
//                                    $model->bu_market = 0;
//                                    break;
//                                case '可售':
//                                    $model->bu_market = 1;
//                                    break;
//                                case '认购':
//                                    $model->bu_market = 2;
//                                    break;
//                                case '网签':
//                                    $model->bu_market = 3;
//                                    break;
//                                case '草签':
//                                    $model->bu_market = 4;
//                                    break;
//
//                            }
                            $model->cid = $this->_user['u_id'];
                            $model->uid = $this->_user['u_id'];
                            $model->utime = date('Y-m-d H:i:s');
                            $model->ctime = date('Y-m-d H:i:s');
                            if ($model->save() === false) {
                                $transaction->rollBack();
                                return ApiReturn::success('导入失败');
                            };
                        }
                    }

                    $transaction->commit();
                    return ApiReturn::success('导入成功');
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return false;
                }

            }
        }

    }

}
