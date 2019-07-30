<?php

namespace common\controllers;

use backend\models\Depart;
use backend\models\OaKaoqingSetting;
use backend\models\OrderSell;
use backend\models\User;
use common\helps\Tools;
use common\models\gii\ComDistrictGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Common controller
 */
class CommonController extends Controller
{
    public $host = '';
    public $imghost = '';
    public $css = null;
    public $js = null;
    public $headJs = null;
    public $footerJs = null;
    public $initJs = null;
    public $crumbs = "";
    public $curMenuItem = null;
    private $rl = ["result" => 0, "message" => ""];
    public $search_p_did = '';

    public function init()
    {
        parent::init();
        $this->host = Yii::$app->params['admin_host'];
        $this->imghost = Yii::$app->params['img_host'];
        $this->css = [
        ];
        $this->js = [
        ];
        $this->headJs = [
        ];
        $this->footerJs = [
        ];
        $this->initJs = [
        ];
    }

    public function addCss(array $css_urls)
    {
        if ($css_urls) {
            $this->css = array_merge($this->css, $css_urls);
        }
    }

    public function addJs(array $js_urls)
    {
        if ($js_urls) {
            $this->js = array_merge($this->js, $js_urls);
        }
    }

    public function addHeadJs(array $js_urls)
    {
        if ($js_urls) {
            $this->headJs = array_merge($this->headJs, $js_urls);
        }
    }

    public function addFooterJs(array $footer_js)
    {
        if ($footer_js) {
            $this->footerJs = array_merge($this->footerJs, $footer_js);
        }
    }

    public function addInitJs(array $initJs)
    {
        if ($initJs) {
            $this->initJs = array_merge($this->initJs, $initJs);
        }
    }

    protected function _error($message, $code = 0)
    {
        $this->rl["code"] = $code;
        $this->rl["msg"] = $message;
        echo Json::encode($this->rl);
        die();
    }

    protected function _success($message, $code = 1)
    {
        $this->rl["code"] = $code;
        $this->rl["msg"] = $message;
        echo Json::encode($this->rl);
        die();
    }

    protected function _user()
    {
        $this->_user = json_decode(Yii::$app->redis->get($this->_tokens), 'true');
        return $this->_user;
    }

    public function renderError($message)
    {
        $errorMsg = '<div style="width:100%; float:left; text-align:center; font-size:24px; color: red; padding-top:5%;"><span>' . $message . '</span></div>';
        return $this->renderContent($errorMsg);
    }

    /**
     * 生成用户权限
     * @param $d_id
     * @return mixed
     */
    protected function userAuth($u_id, $d_id)
    {
        $autharr = [];
        $userdepart = Depart::find()->where(['d_id' => $d_id])->asArray()->one();
        if ($userdepart['d_type'] == 5) {
            $autharr['auth_uid'] = -1;
            $autharr['auth_rid'] = -1;
            $autharr['auth_sid'] = -1;
            $autharr['auth_aid'] = -1;
            $autharr['auth_baid'] = -1;
            $autharr['auth_cid'] = -1;
            return $autharr;
        }
        $arr = [];
        $departlist = $this->_getDepartpath($userdepart['d_pid'], $arr);
        if ($departlist) {
            $departlist = array_merge($departlist, [$userdepart]);
        } else {
            $departlist = [$userdepart];
        }
        $autharr['auth_uid'] = $u_id;
        foreach ($departlist as $key => $item) {
            switch ($item['d_type']) {
                case 1:
                    $autharr['auth_rid'] = $item['d_id'];
                    $autharr['auth_ruid'] = $item['d_principal_id'];
                    break;
                case 2:
                    $autharr['auth_sid'] = $item['d_id'];
                    $autharr['auth_suid'] = $item['d_principal_id'];
                    break;
                case 3:
                    $autharr['auth_aid'] = $item['d_id'];
                    $autharr['auth_auid'] = $item['d_principal_id'];
                    break;
                case 4:
                    $autharr['auth_baid'] = $item['d_id'];
                    $autharr['auth_bauid'] = $item['d_principal_id'];
                    break;
                case 5:
                    $autharr['auth_cid'] = $item['d_id'];
                    break;
            }
        }

        return $autharr;
    }

    /**
     * 获取部门的层级
     * @param $depart
     * @return mixed
     */
    public function _getDepartpath($id, &$arr)
    {
        $ret = Depart::find()->where(['d_id' => $id])->asArray()->one();//var_dump($ret);die;
        if (!empty($ret) && $ret['d_pid'] == 0) {
            $arr[] = $ret;
        } else if (!empty($ret) && $ret['d_pid'] != 0) {
            $arr[] = $ret;
            $this->_getDepartpath($ret['d_pid'], $arr);
        }
        return array_reverse($arr);
    }

    /**
     * 获取部门的层级
     * @param $depart
     * @return mixed
     */
    public function _getDepartid($id, &$arr)
    {
        $ret = Depart::find()->where(['d_id' => $id])->select('d_id,d_pid')->asArray()->one();

        if (!empty($ret) && $ret['d_pid'] == 0) {
            $arr[] = $ret['d_id'];
        } else if (!empty($ret) && $ret['d_pid'] != 0) {
            $arr[] = $ret['d_id'];
            $this->_getDepartid($ret, $arr);
        }

        return array_reverse($arr);
    }

    /**
     * 查看列表是否只为本人范围
     */
    public function _checkSelfRange($auth_id)
    {
        $user = $this->_user();
        $auths = $user['auths'];
        foreach ($auths as $key => $val) {
            if ($val['p_id'] == $auth_id) {
                return $val['data_range'] == '1' ? true : false;
                break;
            } else {
                continue;
            }
        }
        return false;
    }

    /**
     * 用户查看范围条件
     */
    public function _getReadRange($auth_id)
    {
        $user = $this->_user();
        $auths = $user['auths'];
        foreach ($auths as $key => $val) {
            if ($val['p_id'] == $auth_id) {
                $data_range = $val['data_range'];
                $condArr = ['0' => 'none', '1' => 'auth_uid', '2' => 'auth_rid', '3' => 'auth_sid', '4' => 'auth_aid', '5' => 'auth_baid', '6' => 'all'];
                $cond = $condArr[$data_range];
                if ($cond == 'all' || $cond == 'none') {
                    return $cond;
                } else {
                    $search_did = $user[$cond];
                    return [$cond => $search_did];
                }
                break;
            } else {
                continue;
            }
        }
        return false;
    }

    /**
     * 根据用户权限获取列表查看范围,生成部门树，目前适用于房源/简报统计 by liuz at 2018-05-29
     */
    public function _getListDepartTreeByAuthId($auth_id)
    {
        $user = $this->_user();

        $ret = Depart::find()->where(['d_id' => $user['u_dept_id'], 'is_del' => 0])->asArray()->one();
        $auths = $user['auths'];

        if ($ret) {
            $d_type = $ret['d_type'];   // 查询部门级别
            $d_pid = $ret['d_pid'];
            foreach ($auths as $key => $val) {
                if ($val['p_id'] == $auth_id) {
                    $data_range = $val['data_range'];
                    if ($data_range == 6) {  // 可查看所有
                        $depart = Depart::find()->where(['company_id' => $user['company_id'], 'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
                        $departlist = Tools::listToTree($depart, 'value', 'd_pid', 'children');
                        return $departlist;
                    } else {
                        $condArr = ['0' => 'none', '1' => 'auth_uid', '2' => 'auth_rid', '3' => 'auth_sid', '4' => 'auth_aid', '5' => 'auth_baid', '6' => 'all'];
                        $cond = $condArr[$data_range];
                        $search_did = $user[$cond];
                        return $this->_getDepartTreeByDid($search_did);

                        /*if($data_range > $d_type){  // 用户权限大于部门级别权限，则以用户权限为主查询部门的上级分支
                            if($d_pid == 0){ // 顶级部门
                                $depart = Depart::find()->where(['is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
                                $departlist = Tools::listToTree($depart, 'value', 'd_pid', 'children');
                                return $departlist;
                            }else{ // 查询父级部门获取父级部门ID
                                $this->_getParentDepart($d_pid, $data_range);
                                $p_did = $this->search_p_did;
                                return $this->_getDepartTreeByDid($p_did);
                            }
                        }else{ // 其他按照部门级别权限来给定
                            return $this->_getDepartTreeByDid($user['u_dept_id']);
                        }*/
                    }
                    break;
                } else {
                    continue;
                }
            }
        }
        return [];
    }

    /**
     * 通过部门ID生成部门树，目前适用于房源/简报统计 by liuz at 2018-05-29
     */
    public function _getDepartTreeByDid($did)
    {
        $user = $this->_user();
        $userdepart = Depart::find()->where(['company_id' => $user['company_id'], 'd_id' => $did, 'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->asArray()->one();
        Yii::$app->redis->set('departTree', json_encode([$userdepart]));
        $field = 'd_id as value,d_name as label,d_pid';
        $this->_getChildDepart($userdepart['value'], $field);
        $departlist = json_decode(Yii::$app->redis->get('departTree'), true);
        Yii::$app->redis->del('departTree');
        $departlist = Tools::listToSubTree($departlist, 'value', 'd_pid', 'children');
        return $departlist;
    }

    /**
     * 获取父级部门类型级别，目前适用于房源/简报统计 by liuz at 2018-05-29
     */
    public function _getParentDepart($d_pid, $max_type)
    {
        $ret = Depart::find()->where(['d_id' => $d_pid, 'is_del' => 0])->asArray()->one();
        if ($ret) {
            if ($ret['d_type'] < $max_type) {  // 若小于继续查找
                if ($ret['d_pid'] == 0) {
                    $this->search_p_did = $ret['d_id'];
                    //return $ret['d_pid'];
                } else {
                    $this->_getParentDepart($ret['d_pid'], $max_type);
                }
            } else {
                $this->search_p_did = $ret['d_id'];
                //return $ret['d_pid'];
            }
        } else {
            $this->search_p_did = $d_pid;
            //return $d_pid;
        }
    }

    /**
     * 获取子部门信息，目前适用于房源/简报统计 by liuz at 2018-05-29
     *
     */
    public function _getChildDepart($id, $field_str = '', $d_level = 1)
    {
        $ret = Depart::find()->where(['d_pid' => $id, 'is_del' => 0])->select($field_str)->asArray()->all();
        if ($d_level > 5) {
            return;
        } else {
            $d_level++;
            if ($ret) {
                $departTree = json_decode(Yii::$app->redis->get('departTree'), true);
                $departTree = array_merge($departTree, $ret);
                Yii::$app->redis->set('departTree', json_encode($departTree));
                foreach ($ret as $key => $val) {
                    $this->_getChildDepart($val['value'], $field_str, $d_level);
                }
            } else {
                return;
            }
        }
    }

    /**
     * 获取子部门IDs
     */
    public function _getChildDepartIds($id, $d_level)
    {
        $ret = Depart::find()->where(['d_pid' => $id, 'is_del' => 0])->asArray()->all();
        if ($d_level <= 1) {
            return;
        } else {
            $d_level++;
            if ($ret) {
                $childDepartIds = json_decode(Yii::$app->redis->get('childDepartIds'), true);
                foreach ($ret as $key => $val) {
                    $childDepartIds[] = $val['d_id'];
                }
                Yii::$app->redis->set('childDepartIds', json_encode($childDepartIds));
                foreach ($ret as $key => $val) {
                    $this->_getChildDepartIds($val['d_id'], $d_level);
                }
            } else {
                Yii::$app->redis->set('childDepartIds', json_encode([$id]));
                return;
            }
        }
    }

    /**
     * 查询父类的考勤模板
     */
    public function _getParentKqTpl($d_id)
    {
        $res = OaKaoqingSetting::find()->where(['d_id' => $d_id])->asArray()->one();
        if ($res) {
            return $res;
        } else {
            $sres = Depart::find()->where(['d_id' => $d_id])->asArray()->one();
            if ($sres['d_pid']) {
                return $this->_getParentKqTpl($sres['d_pid']);
            } else {
                return false;
            }
        }
    }

    /***
     * 获取审核人
     * @param $user object 用户obj
     * @param $level int 审核等级 1=本人  2=本部  3=本公司
     * @return bool|mixed 如果找到了就是主管ID 如果没有找到 返回false
     */
    public function _getShengheren($user, $level)
    {
        //查看这个用户所有的部门
        $tem = [
            1 => 'auth_rid',
            2 => 'auth_sid',
            3 => 'auth_aid',
            4 => 'auth_baid',
        ];

        $shenherenuid = false;
        for ($i = $level; $i < 5; $i++) {
            if (!empty($user[$tem[$i]]) && $user[$tem[$i]] != 0 && $user[$tem[$i]] != -1) {
                $depart = Depart::find()->where(['d_id' => $user[$tem[$i]]])->one();
                if (isset($depart->d_principal_id) && !empty($depart->d_principal_id)) {
                    $shenherenuid = $depart->d_principal_id;
                    break;
                }
            }
        }
        return $shenherenuid;
    }

    /**
     * 遍历部门树获取部门ID
     */
    public function _getTreeDeptIds($tree)
    {
        foreach ($tree as $key => $val) {
            if (array_key_exists('value', $val)) {
                $deptIds = Yii::$app->redis->get('treeDepartIds') ? json_decode(Yii::$app->redis->get('treeDepartIds'), true) : [];
                $deptIds[] = $val['value'];
                Yii::$app->redis->set('treeDepartIds', json_encode($deptIds));
            }
            if (array_key_exists('children', $val) && $val['children']) {
                $this->_getTreeDeptIds($val['children'], $deptIds);
            }
        }
    }


    /**
     * 验证订单的归属，用户是否有权限操作
     * @param $order_id 订单号
     * @param $user 用户数据
     * @param bool $returndata 是否返回查询数据 默认不返回
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function _validateOrderOwner($order_id, $user, $returndata = false)
    {
        $data = OrderSell::find()->where(['company_id' => $user['company_id'], 'order_id' => $order_id, 'is_del' => 0])->one();
        if (empty($data)) {
            return false;
        }
        if ($data['order_type'] == 1) {
            $authwhere = Yii::$app->LoadData->checkDataByUser('ordersell/getindex', $user);
        } else if ($data['order_type'] == 2) {
            $authwhere = Yii::$app->LoadData->checkDataByUser('orderrent/getindex', $user);
        } else if ($data['order_type'] == 3) {
            $authwhere = Yii::$app->LoadData->checkDataByUser('orderhigh/getindex', $user);
        }
        if ($authwhere == null) {
            return false;
        }
        if ($authwhere['key'] != 'all') {
            if ($authwhere['value'] != $data[$authwhere['key']]) {
                return false;
            }
        }
        if ($returndata) {
            return $data;
        }
        return true;
    }

    /**
     * 验证数据归属，用户是否有权限操作
     * @param $table 表名
     * @param $column 列名
     * @param $id id值
     * @param $company_id 公司id
     * @param bool $returndata
     * @return array|bool
     */
    public function _validateDataOwner($table, $column, $id, $company_id, $returndata = false)
    {
        $connection = Yii::$app->db;
        $data = $connection->createCommand('SELECT * FROM ' . $table . ' WHERE company_id=' . $company_id . ' AND ' . $column . '=' . $id)->queryAll();

        if (empty($data)) {
            return false;
        }
        if ($returndata) {
            return $data;
        }
        return true;
    }

    /**
     * 通过城市ID 和公司ID来获取片区
     * @param $city_id int 城市ID
     * @param  $company_id 公司ID
     * @return  tree;
     */
    public static function getDtsList($city_id, $company_id)
    {
        $DtsList = ComDistrictGii::find()->where(['is_del' => 0, 'city_id' => $city_id])->andWhere(['OR', 'dts_status=0', 'dts_status=1 AND company_id=' . $company_id])
            ->asArray()->all();
        $areaList = [];
        if ($DtsList) {
            foreach ($DtsList as $item) {
                $areaList[$item['area_id']]['value'] = $item['area_id'];
                $areaList[$item['area_id']]['label'] = $item['area_name'];
                foreach ($DtsList as $value) {
                    if ($item['area_id'] == $value['area_id']) {
                        $areaList[$item['area_id']]['children'][$value['dts_id']]['value'] = $value['dts_id'];
                        $areaList[$item['area_id']]['children'][$value['dts_id']]['label'] = $value['dts_name'];
                    }
                }
            }
        }
        foreach ($areaList as $key => $value) {
            if ($areaList[$key]['children']) {
                $areaList[$key]['children'] = array_values($areaList[$key]['children']);
            }
        }
        return array_values($areaList);
    }

    /**
     * 验证是否有房源划成人修改权限
     * @param $authuser 验证人
     * @param $user 当前用户
     */
    public function _checkDivideAuth($authuser,$user)
    {
        $leaderlist = Depart::find()->select('d_id,d_principal_id')->where(['d_id'=>$user['u_dept_id']])->asArray()->one();
        if($leaderlist['d_principal_id'] != $user['u_id']){
            return 0;
        }
        $userlist = User::find()->select('auth_rid,auth_sid,auth_aid,auth_baid,auth_cid')->where(['u_id'=>$authuser])->asArray()->one();
        $departlist = [];
        foreach ($userlist as $key => $item) {
            if ($item['auth_rid'] != 0 && $item['auth_rid'] != -1 && $item['auth_rid'] != null) {
                $departlist[] = $item['auth_rid'];
            }
            if ($item['auth_sid'] != 0 && $item['auth_sid'] != -1 && $item['auth_sid'] != null) {
                $departlist[] = $item['auth_sid'];
            }
            if ($item['auth_aid'] != 0 && $item['auth_aid'] != -1 && $item['auth_aid'] != null) {
                $departlist[] = $item['auth_aid'];
            }
            if ($item['auth_baid'] != 0 && $item['auth_baid'] != -1 && $item['auth_baid'] != null) {
                $departlist[] = $item['auth_baid'];
            }
            if ($item['auth_cid'] != 0 && $item['auth_cid'] != -1 && $item['auth_cid'] != null) {
                $departlist[] = $item['auth_cid'];
            }
        }
        $departlist = array_unique($departlist);
        if(in_array($user['u_dept_id'],$departlist)){
            return 1;
        }else{
            return 0;
        }

    }

    /**
     * 获取房源用户的直级领导
     * @param $authuser
     */
    public function _getLeaderByUser($authuser)
    {
        $authuser = array_unique($authuser);
        $userlist = User::find()->select('auth_rid,auth_sid,auth_aid,auth_baid,auth_cid')->where(['in', 'u_id', $authuser])->asArray()->all();
        $departlist = [];
        foreach ($userlist as $key => $item) {
            if ($item['auth_rid'] != 0 && $item['auth_rid'] != -1 && $item['auth_rid'] != null) {
                $departlist[] = $item['auth_rid'];
            }
            if ($item['auth_sid'] != 0 && $item['auth_sid'] != -1 && $item['auth_sid'] != null) {
                $departlist[] = $item['auth_sid'];
            }
            if ($item['auth_aid'] != 0 && $item['auth_aid'] != -1 && $item['auth_aid'] != null) {
                $departlist[] = $item['auth_aid'];
            }
            if ($item['auth_baid'] != 0 && $item['auth_baid'] != -1 && $item['auth_baid'] != null) {
                $departlist[] = $item['auth_baid'];
            }
            if ($item['auth_cid'] != 0 && $item['auth_cid'] != -1 && $item['auth_cid'] != null) {
                $departlist[] = $item['auth_cid'];
            }
        }
        $departlist = array_unique($departlist);
        $leaderlist = Depart::find()->select('d_id,d_principal_id')->where(['in', 'd_id', $departlist])->asArray()->all();
        $leader = array_unique(ArrayHelper::getColumn($leaderlist, 'd_principal_id'));
        $authuser = array_merge($authuser, $leader);
        return $authuser;
    }

    /***
     * 获取审核人
     * @param $user 维护人和录入人
     * @param $level int 审核等级 1=本组  2=本店  3=本区  4=大区
     * @return bool|mixed 如果找到了就是主管ID 如果没有找到 返回false
     */
    public function _getVefityUser($user, $level)
    {
        //查看这个用户所有的部门
        if ($level == 1) {
            if($user['auth_rid'] == 0 && $user['auth_rid'] == -1 && $user['auth_rid'] == null){
                return false;
            }
            $leader = Depart::find()->select('d_principal_id')->where(['d_id'=>$user['auth_rid']])->asArray()->one();
            if(empty($leader)){
                return false;
            }
            return $leader['d_principal_id'];
        } elseif ($level == 2) {
            if($user['auth_sid'] == 0 && $user['auth_sid'] == -1 && $user['auth_sid'] == null){
                return false;
            }
            $leader = Depart::find()->select('d_principal_id')->where(['d_id'=>$user['auth_sid']])->asArray()->one();
            if(empty($leader)){
                return false;
            }
            return $leader['d_principal_id'];
        } elseif ($level == 3) {
            if($user['auth_aid'] == 0 && $user['auth_aid'] == -1 && $user['auth_aid'] == null){
                return false;
            }
            $leader = Depart::find()->select('d_principal_id')->where(['d_id'=>$user['auth_aid']])->asArray()->one();
            if(empty($leader)){
                return false;
            }
            return $leader['d_principal_id'];
        }elseif ($level == 4){
            if($user['auth_baid'] == 0 && $user['auth_baid'] == -1 && $user['auth_baid'] == null){
                return false;
            }
            $leader = Depart::find()->select('d_principal_id')->where(['d_id'=>$user['auth_baid']])->asArray()->one();
            if(empty($leader)){
                return false;
            }
            return $leader['d_principal_id'];
        }
    }
}
