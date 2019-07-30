<?php
namespace backend\controllers;

use backend\models\Depart;
use backend\models\OaKaoqingSetting;
use backend\models\OaKaoqingTpl;
use backend\models\OaKaoqingUserSetting;
use backend\models\User;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;

/**
 * 考勤排班控制器
 */
class OakaoqingsettingController extends AuthController
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
     * 考勤排班列表
     */
    public function actionGetlist(){
        $param = Yii::$app->request->get();

        $row = Depart::find()->where(['a.is_del' => 0])->alias('a')
            ->leftJoin('zh_district_slice as b','a.d_district = b.dts_id and b.is_del=0')
            ->leftJoin('oa_kaoqing_setting as os','os.d_id = a.d_id')
            ->leftJoin('oa_kaoqing_tpl as at', 'at.kq_tp_id = os.kq_tp_id')
            ->select('a.*,b.dts_name,at.kq_tp_name,at.kq_tp_id')->where(['a.company_id' => $this->_user['company_id']]);
        $departTree = $this->_getListDepartTreeByAuthId(189);  // 考勤设置权限
        if (isset($param['d_id']) && $param['d_id']) {
            $row->andWhere(['a.d_id' => trim($param['d_id'])]);
        }else{
            $this->_getTreeDeptIds($departTree);
            $tDeptIds = Yii::$app->redis->get('treeDepartIds') ? json_decode(Yii::$app->redis->get('treeDepartIds'), true) : [];
            Yii::$app->redis->del('treeDepartIds');
            $row->andWhere(['in', 'a.d_id', $tDeptIds]);
        }

        $departlist = $row->orderBy('a.d_sort DESC')->asArray()->all();

        //$depart = Depart::find()->where(['is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('ctime DESC')->asArray()->all();
        //$data['departchoose'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        $data['departchoose'] = $departTree;

        $topPids = [];
        foreach($departTree as $val){
            $topPids[] = $val['d_pid'];
        }
        foreach ($departlist as $key => $item) {
            $arr = [];
            if($item['dts_name'] == null || $item['dts_name'] == ''){
                $departlist[$key]['dts_name'] = '---';
            }
            $departlist[$key]['departpath'] = $this->_getDepartid($item['d_pid'], $arr);
            if (in_array($item['d_pid'], $topPids) || (isset($param['d_id']) && $item['d_id'] == $param['d_id'])) {
                $departlist[$key]['d_show_name'] = '　+ ' . $departlist[$key]['d_name'];
                $topdepart[$item['d_id']] = $departlist[$key];
            }
        }

        $data['departlist'] = [];
        foreach ($topdepart as $kk => $depart) {
            $childdepart = [];
            $childdepart[] = $depart;
            $childdepart = $this->_getSubNodeinfo($depart['d_id'], $departlist, $childdepart, 1);
            $data['departlist'] = array_merge($data['departlist'], $childdepart);
        }

        $row = OaKaoqingTpl::find()->asArray()->all();
        $data['kqtpllist'] = $row;

        $data['weekArr'] = $this->_calcWeekarr();
        $data['curMonth'] = date('Y年m月');
        $data['curDay'] = date('d') + 0;
        $data['curDays'] = date('t');
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 获取员工信息列表
     */
    public function actionGetstaff(){
        if(Yii::$app->request->isPost){
            $param = Yii::$app->request->post();
            if(isset($param['d_id']) && $param['d_id']){
                $depart = Depart::findOne($param['d_id']);
                $paiban = OaKaoqingSetting::find()->where(['d_id' => $param['d_id']])->asArray()->one();
                $kq_tp_id = '0';
                if($paiban){
                    $tpl = OaKaoqingTpl::findOne($paiban['kq_tp_id']);
                    $kq_tp_id = $tpl['kq_tp_id'];
                }else{
                    $p_paiban = $this->_getParentKqTpl($depart->d_pid);
                    $kq_tp_id = $p_paiban['kq_tp_id'];
                }

                $list = User::find()->where(['is_del' => 0, 'u_dept_id' => $param['d_id']])->andWhere(['or' , ['=' , 'u_status' , 1] , ['=' , 'u_status' , 2], ['=' , 'u_status' , 3]])->select('u_id, u_name')->asArray()->all();
                $data['list'] = $list;
                $rowpaiban = [];

                $weekArr = $this->_calcWeekarr();
                $staffpaiban = [];
                foreach($list as $key =>$val){
                    $rowpaiban[$key] = $kq_tp_id ? $val['u_id'].'_'.$kq_tp_id : '0';
                    foreach($weekArr as $wkey => $wval){
                        $res = OaKaoqingUserSetting::find()->where(['u_id' => $val['u_id'], 'kq_date' => $wval['val2']])->asArray()->one();
                        $staffpaiban[$key][$wkey] = $res ? $val['u_id'].'_'.$res['kq_tp_id'].'_'.$wval['val2'] : 0;
                    }
                    //$staffpaiban[$key][7] = 0;
                }
                $data['rowpaiban'] = $rowpaiban;
                $data['staffpaiban'] = $staffpaiban;

                return ApiReturn::success('获取成功', $data);
            }
        }else{
            return ApiReturn::wrongParams('查询失败');
        }
    }

    /**
     * 员工排班设置
     */
    public function actionStaffupdate(){
        if(Yii::$app->request->isPost){
            $param = Yii::$app->request->post();
            $staffpaiban = $param['staffpaiban'];
            $d_id = $param['d_id'];

            foreach($staffpaiban as $key => $val){
                for($i=0; $i<count($val); $i++){
                    if(!$val[$i]){
                        continue;
                    }else{
                        $arr = explode('_', $val[$i]);
                        $kq_tp_id = $arr[1];
                        $kq_date = $arr[2];
                        $res = OaKaoqingUserSetting::find()->where(['u_id' => $arr[0], 'kq_date' => $kq_date])->asArray()->one();
                        $kqTp = OaKaoqingTpl::findOne($kq_tp_id);

                        $model = $res ? OaKaoqingUserSetting::findOne($res['kq_user_id']) : new OaKaoqingUserSetting();
                        $model->kq_date = $kq_date;
                        $model->kq_md = substr($kq_date, 5);
                        $model->kq_tp_id = $kq_tp_id;
                        $model->kq_st = $kqTp->kq_tp_st;
                        $model->kq_ed = $kqTp->kq_tp_ed;

                        $model->u_id = $arr[0];
                        $model->utime = date('Y-m-d H:i:s');
                        $model->d_id = $d_id;
                        if(!$res){
                            $model->ctime = date('Y-m-d H:i:s');
                        }
                        $model->save();
                    }
                }
            }

            return ApiReturn::success('保存成功');
        }else{
            return ApiReturn::wrongParams('查询失败');
        }
    }

    private function _calcWeekarr(){
        /*$cur_w = date("w");
        $weekArr = ['一', '二', '三', '四', '五', '六', '日'];
        $res = [];
        for($i=1; $i<=7; $i++){
            $res[$i-1]['val0'] = $weekArr[$i > 7 ? $i-8 : $i-1];
            $diff = ($i <= $cur_w) ? -($cur_w - $i) : $i - $cur_w;
            $res[$i-1]['val1'] = date('m-d', strtotime("$diff day"));
            $res[$i-1]['val2'] = date('Y-m-d', strtotime("$diff day"));
        }*/
        $res = [];
        $t = date('t');
        for($i=1; $i<=$t; $i++){
            $res[$i-1]['val0'] = strtotime(date('Y-m').'-'.$i) < strtotime(date('Y-m-d')) ? true : false;
            $res[$i-1]['val1'] = (strlen($i) == 1 ? '0':'').$i;
            $res[$i-1]['val2'] = date('Y-m').'-'.$i;
        }
        return $res;
    }

    /**
     * 递归获取子节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getSubNodeinfo($id, $departlist, &$arr, $level)
    {
        $ret = [];
        foreach ($departlist as $kk => $vv) {
            if ($vv['d_pid'] == $id) {
                $ret[] = $vv;
                unset($departlist[$kk]);
            }
        }
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $str = '';
                for ($i = 1; $i <= $level; $i++) {
                    $str .= '　　';
                }
                $str .= '└ ';
                $node['d_show_name'] = $str . $node['d_name'];
                $arr[] = $node;
                $this->_getSubNodeinfo($node['d_id'], $departlist, $arr, $node['d_level']);
            }
        }
        return $arr;
    }

    /**
     * 考勤排班设置
     */
    public function actionUpdate(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $message = '设置';
            if(isset($post['d_id']) && $post['d_id'] && isset($post['kq_tp_id']) && $post['kq_tp_id']){
                $res = OaKaoqingSetting::find()->where(['d_id' => $post['d_id']])->asArray()->one();
                if($res){
                    $post['kq_st_id'] = $res['kq_st_id'];
                }
                $model=new OaKaoqingSetting();
                $result = $model->UpdateSetting($post,$this->_user());
                if($result){
                    return ApiReturn::success($message.'成功');
                }else{
                    return ApiReturn::wrongParams($message.'失败');
                }
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

}
