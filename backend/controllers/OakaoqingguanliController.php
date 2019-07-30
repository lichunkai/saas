<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaKaoqingGuanli;
use backend\models\OaKaoqingSetting;
use backend\models\OaKaoqingTpl;
use backend\models\OaKaoqingUserSetting;
use backend\models\ZhSettingJuece;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;

/**
 * 考勤管理控制器
 */
class OakaoqingguanliController extends AuthController
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
     * 考勤管理列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();
        $page = isset($param['page'])? $param['page'] :1;
        $pagesize = isset($param['pagesize'])? $param['pagesize'] :10;
        $start = ($page - 1) * $pagesize;

        $row = OaKaoqingGuanli::find()->where(['a.is_del' => 0])->alias('a')
            ->leftJoin('zh_user as u','u.u_id = a.staff_id and u.is_del=0')
            ->leftJoin('zh_depart as d','d.d_id = a.d_id')
            ->select('a.*, d.d_name, u.u_name');
        $row->where(['a.company_id' => $this->_user['company_id']]);
        $departTree = $this->_getListDepartTreeByAuthId(191);  // 考勤管理权限

        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere(['a.d_id' => trim($param['d_id'])]);
        }else{
            $this->_getTreeDeptIds($departTree);
            $tDeptIds = Yii::$app->redis->get('treeDepartIds') ? json_decode(Yii::$app->redis->get('treeDepartIds'), true) : [];
            Yii::$app->redis->del('treeDepartIds');
            $row->andWhere(['in', 'a.d_id', $tDeptIds]);
        }
        if (isset($param['dateRange']) && $param['dateRange']){
            $daterange = $param['dateRange'];
            $row->andFilterWhere(['between','a.kq_date',$daterange[0], $daterange[1]]);
        }else{
            $row->andWhere(['a.kq_date' => date('Y-m-d')]);
        }
        if (isset($param['kw']) && $param['kw']){
            $row->andWhere("u.u_name like '%" . $param["kw"]."%'"
                . " or u.u_phone like '%" . $param["kw"]."%'"
                . " or u.u_employee_id like '%" . $param["kw"]."%'");
        }


        $list = $row->orderBy('a.ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        //echo $row->createCommand()->getRawSql();
        $data['list'] = $list;

        //$depart = Depart::find()->where(['is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        $data['departchoose'] = $departTree; //Tools::listToTree($depart, 'value', 'd_pid', 'children');

        $data['totalnum'] = $row->count();

        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 考勤管理添加
     */
    public function actionUpdate(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new OaKaoqingGuanli();
            $result = $model->UpdateKq($post,$this->_user());
            $message = '修改';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 考勤模板查询
     */
    public function actionGetTpl(){
        $user = $this->_user();
        $condArr = ['1'=>'auth_uid','2'=>'auth_rid','3'=>'auth_sid','4'=>'auth_aid','5'=>'auth_baid'];
        $u_dept_id = $user['u_dept_id']; // 部门ID
        $res = OaKaoqingSetting::find(['d_id' => $u_dept_id])->asArray()->one();
        if($res){
            return ApiReturn::success('获取成功');
        }else{
            return ApiReturn::wrongParams('失败成功');
        }
    }

    /**
     * 员工考勤信息获取（前端）
     */
    public function actionKaoq(){
        //$post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            // 查询打卡情况
            $user = $this->_user();
            $res = OaKaoqingGuanli::find()->where(['kq_date' => date('Y-m-d'), 'is_del' => 0, 'staff_id' => $user['u_id']])->one();
            if(!$res){
                return ApiReturn::wrongParams('无法获取您的排班信息');
            }
            $rt_data = ['df_st' => $res->df_st, 'df_ed' => $res->df_ed, 'sj_st' => $res->sj_st, 'sj_ed' => $res->sj_ed];

            $post = Yii::$app->request->post();
            $lat = $post['lat'];
            $lng = $post['lng'];
            //$xiawu = $post['xiawu']; // 0表示上午, 1表示下午

            /*if(!$xiawu){
                $dept_id = $user['u_dept_id'];
                $rt_data['sign'] = 0;
                for($i=0; $i<10; $i++){
                    $depart = Depart::findOne($dept_id);
                    if($depart && $depart->d_pid != 0){
                        $dept_id = $depart->d_pid;
                        $d_principal_id = $depart->d_principal_id;
                        $res = OaKaoqingGuanli::find()->where(['kq_date' => date('Y-m-d'), 'staff_id' => $d_principal_id])->asArray()->one();
                        if(!$d_principal_id){
                            $rt_data['sign'] = 1;
                            return ApiReturn::wrongParams('您的上级还未分配，不能打卡', $rt_data);
                        }elseif(!$res){
                            $rt_data['sign'] = 2;
                            return ApiReturn::wrongParams('您的上级今天的排班没有生成，不能打卡', $rt_data);
                        }else{
                            if($res['sj_st'] == ''){
                                $rt_data['sign'] = 3;
                                return ApiReturn::wrongParams('您的上级还未打卡，不能打卡', $rt_data);
                            }
                        }
                    }else{
                        break;
                    }
                }
            }*/

            $depart = Depart::findOne($user['u_dept_id']);
            $d_location = $depart->d_location ? explode(',', $depart->d_location) : [];
            //$d_location = Tools::gcjTObd($d_location[0], $d_location[1]);
            if(!$depart->d_location){
                $rt_data['sign'] = 4;
                return ApiReturn::wrongParams('公司或门店的定位失败,无法打卡', $rt_data);
            }
            if(!$lat || !$lng){
                $rt_data['sign'] = 5;
                return ApiReturn::wrongParams('您的手机定位失败，无法获取您的经纬度', $rt_data);
            }

            // 搜索经纬度范围
            $juece = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'kaoqingjulifanwei'])->asArray()->one();
            $range = $juece['val'];

            // 计算距离
            //$url = 'http://api.map.baidu.com/routematrix/v2/driving?output=json&origins='.$lat.','.$lng.'&destinations='.$dlat.','.$dlng.'&ak='.$ak;
            $getDis = Tools::gcjTObd($lng, $lat);
            $distance = Tools::getDistance($getDis[1], $getDis[0], $d_location[0], $d_location[1]);
            if($distance >= $range){
                $rt_data['sign'] = 6;
                return ApiReturn::wrongParams('您已经超过公司或门店与您的距离,没法打卡', $rt_data);
            }

            if($res){
                return ApiReturn::success('信息获取！', $rt_data);
            }
        }
    }

    /**
     * 检测员工经纬度，考勤打卡(手机端)
     */
    public function actionDaka(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            // 查询上级打卡情况
            $user = $this->_user();

            $lat = $post['lat'];
            $lng = $post['lng'];
            //$sign = $post['sign'];  // 0表示上班卡，1表示下班卡
            $waichu = $post['waichu'];  // 0表示公司打卡，1表示外出打卡
            $url = $post['url'];
            $content = $post['content'];

            $res = OaKaoqingGuanli::find()->where(['kq_date' => date('Y-m-d'), 'staff_id' => $user['u_id']])->asArray()->one();
            if($res){
                $model = OaKaoqingGuanli::findOne($res['kq_mg_id']);

                $u_dept_id = $user['u_dept_id'];
                $paiban = OaKaoqingSetting::find()->where(['d_id' => $u_dept_id])->asArray()->one();   // 部门排班
                $upaiban = OaKaoqingUserSetting::find()->where(['u_id' => $user['u_id'], 'kq_date' => date('Y-m-d')])->asArray()->one();
                //$kq_tp_st = '';
                //$kq_tp_ed = '';
                if($upaiban){
                    $kq_tp_st = $upaiban['kq_st'];
                    $kq_tp_ed = $upaiban['kq_ed'];
                }elseif($paiban){
                    $paiban = OaKaoqingTpl::findOne($paiban['kq_tp_id']);
                    $kq_tp_st = $paiban['kq_tp_st'];
                    $kq_tp_ed = $paiban['kq_tp_ed'];
                }else{
                    $p_paiban = $this->_getParentKqTpl($u_dept_id);
                    $paiban = OaKaoqingTpl::findOne($p_paiban['kq_tp_id']);
                    $kq_tp_st = $paiban['kq_tp_st'];
                    $kq_tp_ed = $paiban['kq_tp_ed'];
                }
                $df_st = strtotime(date("Y-m-d")." ".$kq_tp_st.":00");
                $df_ed = strtotime(date("Y-m-d")." ".$kq_tp_ed.":00");

                $now = time();
                $sign = $now < ($df_st + $df_ed)/2 ? 0 : 1;  // 若打卡时间小于中间时间，0表示上班卡，1表示下班卡
                if($sign == 1){  // 下班卡
                    $model->ed_lat_lng = $lat.','.$lng;
                    $model->ed_content = $content;
                    $model->ed_waichu = $waichu;
                    $model->sj_ed = date('H:i:s');
                    $model->ed_photo = $url;
                    if($model->sj_st){
                        if($now < $df_ed){
                            if($model->flag == 2){
                                $model->flag = 7;  //早退
                            }elseif($model->flag == 3){
                                $model->flag = 6;  //迟到早退
                            }
                        }else{
                            if($model->flag == 2){
                                $model->flag = 9;   // 正常
                            }elseif($model->flag == 3){
                                $model->flag = 5;  //迟到
                            }
                        }
                    }else{  // 若上班卡未打
                        if($now < $df_ed){
                            $model->flag = 4;   // 上班未打卡早退
                        }else{
                            $model->flag = 1;   // 上班未打卡
                        }
                    }
                }else{  // 上班卡
                    $model->st_lat_lng = $lat.','.$lng;
                    $model->st_content = $content;
                    $model->st_waichu = $waichu;
                    $model->sj_st = date('H:i:s');
                    $model->st_photo = $url;
                    if($now <= $df_st){
                        $model->flag = 2;   // 下班未打卡
                    }else{
                        $model->flag = 3;   // 迟到下班未打卡
                    }
                }
                $model->company_id = $user['company_id'];
                if($model->save()){
                    return ApiReturn::success('打卡成功');
                }else{
                    return ApiReturn::wrongParams('打卡失败');
                }
            }else{
                return ApiReturn::wrongParams('没有生成您的考勤记录，无法打卡');
            }

        }
    }

    /**
     * 考勤管理编辑
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new OaKaoqingGuanli();
            $result = $model->UpdateKq($post,$this->_user());
            $message = '更新';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 查询父类的考勤模板
     */
    private function getParentKqTpl($d_id){
        $res = OaKaoqingSetting::find()->where(['d_id' => $d_id])->asArray()->one();
        if($res){
            return $res;
        }else{
            $sres = Depart::find()->where(['d_id' => $d_id])->asArray()->one();
            if($sres['d_pid']){
                return $this->getParentKqTpl($sres['d_pid']);
            }else{
                return false;
            }
        }
    }
}
