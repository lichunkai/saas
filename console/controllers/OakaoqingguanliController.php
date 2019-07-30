<?php
namespace console\controllers;
use backend\models\OaHrStaff;
use backend\models\OaKaoqingTpl;
use backend\models\OaKaoqingUserSetting;
use backend\models\OaQingjia;
use yii\console\Controller;

use backend\models\Depart;
use backend\models\Role;
use backend\models\User;
use backend\models\OaKaoqingGuanli;
use backend\models\OaKaoqingSetting;
use common\models\ApiReturn;
use Yii;

/**
 * 考勤管理控制器
 */
class OakaoqingguanliController extends Controller
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
     * 生成考勤记录
     */
    public function actionAdd(){
        $weekList = array(0=>'周日', 1=>'周一', 2=>'周二', 3=>'周三', 4=>'周四', 5=>'周五', 6=>'周六');
        $week = $weekList[date("w")];

        // 遍历部门
        $res = Depart::find()->where(['is_del' => 0])->asArray()->all();
        // 查询部门是否有排班，没有排班继承父部门
        $deptKqTpl = [];
        foreach($res as $val){
            $paiban = OaKaoqingSetting::find()->where(['d_id' => $val['d_id']])->asArray()->one();
            if($paiban){
                $tpl = OaKaoqingTpl::findOne($paiban['kq_tp_id']);
                $deptKqTpl[$val['d_id']] = $tpl;
            }else{
                $p_paiban = $this->_getParentKqTpl($val['d_pid']);
                $tpl = OaKaoqingTpl::findOne($p_paiban['kq_tp_id']);
                $deptKqTpl[$val['d_id']] = $tpl;
            }
        }

        // 遍历员工
        $res = User::find()->where(['is_del' => 0])->asArray()->all();
        $curdate = date('Y-m-d');
        $data = [];
        foreach($res as $val){
            $uSetting = OaKaoqingUserSetting::find()->where(['kq_date' => $curdate, 'u_id' => $val['u_id']])->asArray()->one();
            if($uSetting){
                $tpl = OaKaoqingTpl::findOne($uSetting['kq_tp_id']);
                $arr = [
                    'staff_id' => $val['u_id'],
                    'd_id' => $val['u_dept_id'],
                    'kq_date' => date('Y-m-d'),
                    'status' => '上班',
                    'tp_name' => $tpl->kq_tp_name,
                    'df_st' => $uSetting['kq_st'],
                    'df_ed' => $uSetting['kq_ed'],
                    'flag' => '0',
                    'ctime' => date('Y-m-d H:i:s'),
                    'is_del' => 0,
                    'company_id' => $val['company_id'],
                ];
                $data[] = $arr;
            }else{
                if(isset($deptKqTpl[$val['u_dept_id']]) && $deptKqTpl[$val['u_dept_id']]){
                    $paiban = $deptKqTpl[$val['u_dept_id']];
                    $kq_tp_days = explode(',', $paiban['kq_tp_days']);
                    $arr = [
                        'staff_id' => $val['u_id'],
                        'd_id' => $val['u_dept_id'],
                        'kq_date' => date('Y-m-d'),
                        'status' => in_array($week, $kq_tp_days) ? '上班': '休息',
                        'tp_name' => $paiban['kq_tp_name'],
                        'df_st' => $paiban['kq_tp_st'],
                        'df_ed' => $paiban['kq_tp_ed'],
                        'flag' => in_array($week, $kq_tp_days) ? '0': '8',
                        'ctime' => date('Y-m-d H:i:s'),
                        'is_del' => 0,
                        'company_id' => $val['company_id'],
                    ];
                    $data[] = $arr;
                }else{
                    continue;
                }
            }
        }
        //执行批量插入
        if ($data)
        {
            Yii::$app->db->createCommand()
                ->batchInsert(OaKaoqingGuanli::tableName(),
                    ['staff_id','d_id','kq_date','status', 'tp_name', 'df_st', 'df_ed', 'flag', 'ctime', 'is_del'],
                    $data)->execute();
        }
    }

    /**
     * 查询父类的考勤模板
     */
    private function _getParentKqTpl($d_id){
        $res = OaKaoqingSetting::find()->where(['d_id' => $d_id])->asArray()->one();
        if($res){
            return $res;
        }else{
            $sres = Depart::find()->where(['d_id' => $d_id])->asArray()->one();
            if($sres['d_pid']){
                return $this->_getParentKqTpl($sres['d_pid']);
            }else{
                return false;
            }
        }
    }

    /**
     *
     */
    public function actionInsertdoc(){
        // 遍历员工
        $res = User::find()->where(['is_del' => 0])->asArray()->all();
        $data = [];
        foreach($res as $val){
            $dp = Depart::findOne($val['u_dept_id']);
            $role = Role::findOne($val['u_role_id']);
            $arr = [
                'staff_id' => $val['u_id'],
                'staff_name' => $val['u_name'],
                'staff_no' => $val['u_employee_id'],
                'd_id' => $val['u_dept_id'],
                'sex' => $val['u_sex'],
                'lianxifangshi' => $val['u_phone'],
                'birthday' => $val['u_birthday_time'],
                'ruzhishijian' => $val['u_entry_time'],
                'shenfenzheng' => $val['u_identity'],
                'ctime' => date('Y-m-d H:i:s'),
                'suoshubumen' => $dp ? $dp->d_name : '',
                'zhiwu' => $role ? $role->role_name : '',
                'is_del' => 0,
                'company_id' => $val['company_id'],
            ];
            $data[] = $arr;
        }
        //执行批量插入
        if ($data)
        {
            Yii::$app->db->createCommand()
                ->batchInsert(OaHrStaff::tableName(),
                    ['staff_id','staff_name','staff_no', 'd_id','sex', 'lianxifangshi', 'birthday', 'ruzhishijian', 'shenfenzheng', 'ctime', 'suoshubumen', 'zhiwu', 'is_del', 'company_id'],
                    $data)->execute();
        }
    }
}
