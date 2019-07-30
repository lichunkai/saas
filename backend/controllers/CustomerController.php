<?php
namespace backend\controllers;

use backend\models\Agency;
use backend\models\HuaWei;
use backend\models\Commission;
use backend\models\Customer;
use backend\models\Role;
use backend\models\CustomColumns;
use backend\models\Customer_daikan;
use backend\models\Customer_log;
use backend\models\Customer_tel;
use backend\models\District;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingQujian;
use backend\models\Depart;
use backend\models\House;
use backend\models\SystemLog;
use backend\models\User;
use backend\models\Customer_follow;
use backend\models\ZhSettingRequired;
use backend\models\ZhSettingJuece;
use backend\models\Verify;
use common\helps\Tools;
use common\helps\Upload;
use common\models\CommSetting;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use backend\models\District_region;
use backend\models\District_slice;

/**
 * Common controller
 */
class CustomerController extends AuthController
{
	/*
	**
	*测试接受虚拟号信息
	*/
	public function actionInsms(){	
		$param = file_get_contents('php://input');		
		file_put_contents('log.txt',$param.PHP_EOL, FILE_APPEND);
		$param = str_replace("'", "", $param);
		$arr = json_decode($param, true);
		$huawei = new HuaWei();			
		if(isset($arr)){			
			if(isset($arr['eventType'])){
				$huawei->eventType=$arr['eventType'];
			}
			if(isset($arr['statusInfo']['sessionId'])){
				$huawei->sessionId=$arr['statusInfo']['sessionId'];
			}
			if(isset($arr['statusInfo']['timestamp'])){
				$huawei->timestamp=$arr['statusInfo']['timestamp'];
			}
			if(isset($arr['statusInfo']['caller'])){
				$huawei->caller=$arr['statusInfo']['caller'];
			}
			if(isset($arr['statusInfo']['called'])){
				$huawei->called=$arr['statusInfo']['called'];
			}
			if(isset($arr['statusInfo']['stateCode'])){
				$huawei->stateCode=$arr['statusInfo']['stateCode'];
			}
			if(isset($arr['statusInfo']['stateDesc'])){
				$huawei->stateDesc=$arr['statusInfo']['stateDesc'];
			}
			if(isset($arr['statusInfo']['subscriptionId'])){
				$huawei->subscriptionId=$arr['statusInfo']['subscriptionId'];
			}	
			$aa=$huawei->save();
		}
		return ApiReturn::success('成功');
	}	
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
     * @return \common\models\json
     * 获取客户列表
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get();
        $user = $this->_user;
        $u_dept_id = $user['u_dept_id'];
        //部门数据
        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        //获取本人本组大区本大区的方法
        if (!empty($param['customer_type']) && $param['customer_type'] == 1) {
            $bendianbenzu = Customer::getBumen(148, $user);
        } elseif (!empty($param['customer_type']) && $param['customer_type'] == 2) {
            $bendianbenzu = Customer::getBumen(150, $user);
        } else {
            $bendianbenzu = Customer::getBumen(146, $user);
        }
        //如果有部门查询 就找出当前查询部门的所有数据
        if (!empty($param['d_id'])) {
            $departlist = Customer::getTree($depart, $param['d_id']);
        } else {
            /*
             * 初始化部门
             */
            //获取子集的树
            $departlist = Customer::getTree($depart, $bendianbenzu);

            //把 树转换为列
            $departlist = Customer::setlistname($departlist);
            //获取父
            $departzhuyaode = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id'], 'd_id' => $bendianbenzu])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();

            //子集加上父集
            if (!empty($departlist) && !empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid'] = 0;
                $departlist = array_merge($departlist, $departzhuyaode);
            } else if (!empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid'] = 0;
                $departlist = $departzhuyaode;
            }

            //数据排序一下
            if (!empty($departlist)) {
                $departlist = Tools::listToTree($departlist, 'value', 'd_pid', 'children');
            }
        }

        //判断是否为真和是否为本人
        if ($bendianbenzu && $bendianbenzu != $user['u_id']) {
            $u_dept_id = $bendianbenzu;
            if (empty($param['d_id'])) {
                if ($bendianbenzu <> 'sys') {
                    foreach ($departlist as $v) {
                        //判断与他自己的部门
                        if ($v['value'] == $u_dept_id) {
                            $benzutree[] = $v;
                        }
                    }
                } else {
                    $benzutree = $data['peizhi']['departlist'];
                }

            } else {
                $benzutree = $departlist;
            }

            if (!empty($benzutree)) {

                $data['peizhi']['benzu'] = $benzutree;
                //把多维 树形数据变成一维
                $benzu = Customer::setlist($benzutree);
                $benzuin = !empty($param['d_id']) ? $param['d_id'] . ',' : '';
                //用in 的方法查出包含部门的所用用户数据。
                foreach ($benzu as $v) {
                    $benzuin .= $v['value'] . ',';
                }
                $benzuin = substr($benzuin, 0, strlen($benzuin) - 1);
            }
        }
        if (!empty($param['d_id']) && empty($benzuin)) {
            $benzuin = $param['d_id'];
        }
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = Customer::find()
            ->select('a.*')->alias('a')
            ->leftJoin('zh_user as u', 'u.u_id=a.c_id')
            ->where(['a.is_del' => '0', 'a.company_id' => $this->_user['company_id']]);
        $sszhuangtai = !empty($param['sszhuangtai']) ? trim($param['sszhuangtai']) : 0;
        if ($sszhuangtai && $sszhuangtai != '不限') {
            if ($sszhuangtai == '有效(全部)' && $bendianbenzu && $bendianbenzu != $user['u_id']) {
                $row->andWhere(['a.zhuangtai' => '有效']);
            } else if ($sszhuangtai == '有效(全部)' && $bendianbenzu == $user['u_id']) { //判断是否是本人
                $u_id = $user['u_id'];
                $row->andWhere(['a.zhuangtai' => '有效']);
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");
            }
            if ($sszhuangtai == '无效') {
                $row->andWhere(['a.zhuangtai' => '无效']);
            }
            if ($sszhuangtai == '其他') {
                $row->andWhere(['a.zhuangtai' => '其他']);
            }
            if ($sszhuangtai == '成交') {
                $row->andWhere(['a.zhuangtai' => '成交']);
            }
            if ($sszhuangtai == '有效(公客)') {
                $row->andWhere(['a.zhuangtai' => '有效', 'a.customer_private' => '公客']);
            }
            if ($sszhuangtai == '有效(私客)' && $bendianbenzu && $bendianbenzu != $user['u_id']) {
                $row->andWhere(['a.zhuangtai' => '有效', 'a.customer_private' => '私客']);
            } else if ($sszhuangtai == '有效(私客)' && $bendianbenzu == $user['u_id']) { //判断是否是本人
                $row->andWhere(['a.zhuangtai' => '有效', 'a.c_id' => $user['u_id'], 'a.customer_private' => '私客']);
            }
        } else {
            //判断是本人显示所有类型
            if ((!$bendianbenzu or $bendianbenzu == $user['u_id'])) {
                $u_id = $user['u_id'];
                $row->andWhere("a.zhuangtai='有效' or a.zhuangtai='无效' or a.zhuangtai='其他'");
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");
            }
        }

        if (!empty($param['xqqy'][0]) && $param['xqqy'][0] <> 'null') {
            $row->andWhere(['a.dts_id' => $param['xqqy'][0]]);
        }
        if (!empty($param['xqqy'][1]) && $param['xqqy'][1] <> 'null') {
            $row->andWhere(['a.rn_id' => $param['xqqy'][1]]);
        }
        if (!empty($param['xiaoqu'])) {
            $row->andWhere(['a.village' => $param['xiaoqu']]);
        }
        if (!empty($param['ssk'])) {
            $ssk = $param['ssk'];
            $row->leftJoin('zh_customer_tel as t', 'a.customer_uuid=t.customer_uuid');
            $row->andWhere("a.xuqiubianhao like '%$ssk%' or a.customer_name like '%$ssk%' or t.tel_phone like '%$ssk%' ");
        }
        //判断是否有部门搜索

        if (!empty($param['d_id'])) {
            $benzuin = !empty($benzuin) ? $benzuin : 0;
            $row->andWhere("a.u_dept_id in ($benzuin) ");
        } elseif (!empty($benzuin)) {
            $row->andWhere("a.u_dept_id in ($benzuin) or a.c_id is  null");
        }
        if (!empty($param['customer_type'])) {
            $row->andWhere(['a.customer_type' => $param['customer_type']]);
        } else {
            $row->andWhere(['a.customer_type' => 0]);
        }
        if (!empty($param['xuqiujiage_min']) or !empty($param['xuqiujiage_max'])) {
            $row->andWhere(['>=', 'a.xuqiujiage_min', intval($param['xuqiujiage_min'])]);
            $row->andWhere(['<=', 'a.xuqiujiage_max', intval($param['xuqiujiage_max'])]);
        }
        if (!empty($param['xuqiumianji_min']) or !empty($param['xuqiumianji_max'])) {
            $row->andWhere(['>=', 'a.xuqiumianji_min', intval($param['xuqiumianji_min'])]);
            $row->andWhere(['<=', 'a.xuqiumianji_max', intval($param['xuqiumianji_max'])]);
        }
        if (!empty($param['xuqiuhuxing_min']) or !empty($param['xuqiuhuxing_max'])) {
            $row->andWhere(['>=', 'a.xuqiuhuxing_min', intval($param['xuqiuhuxing_min'])]);
            $row->andWhere(['<=', 'a.xuqiuhuxing_max', intval($param['xuqiuhuxing_max'])]);
        }
        if (!empty($param['xuqiulouceng_min']) or !empty($param['xuqiulouceng_max'])) {
            $row->andWhere(['>=', 'a.xuqiulouceng_min', intval($param['xuqiulouceng_min'])]);
            $row->andWhere(['<=', 'a.xuqiulouceng_max', intval($param['xuqiulouceng_max'])]);
        }
        if (!empty($param['xuqiufangling_min']) or !empty($param['xuqiufangling_max'])) {
            $row->andWhere(['>=', 'a.xuqiufangling_min', intval($param['xuqiufangling_min'])]);
            $row->andWhere(['<=', 'a.xuqiufangling_max', intval($param['xuqiufangling_max'])]);
        }

        if (!empty($param['user'])) {
            $row->andWhere(['=', 'a.c_id', $param['user']]);
        }
        if (!empty($param['kehudengji'])) {
            $row->andWhere(['=', 'a.kehudengji', trim($param['kehudengji'])]);
        }
        if (!empty($param['butong'])) {
            $row->andWhere(['<>', 'a.customer_uuid', $param['butong']]);
        }
        if (!empty($param['zhutui'])) {
            $row->andWhere(['=', 'a.zhutui', $param['zhutui']]);
        }
        if (!empty($param['laiyuan'])) {
            $row->andWhere(['=', 'a.kehulaiyuan', $param['laiyuan']]);
        }

        if (!empty($param['shijian'])) {
            $row->andWhere(['>=', 'unix_timestamp(a.ctime)', strtotime($param['shijian'][0])]);
            $row->andWhere(['<=', 'unix_timestamp(a.ctime)', strtotime($param['shijian'][1])]);
        }

        if (!empty($param['ssxz'])) {
            foreach ($param['ssxz'] as $v) {
                if ($v == '急切') {
                    $row->andWhere(['like', 'a.duoxuanbiaoqian', '急切']);
                }
                if ($v == '学区') {
                    $row->andWhere(['like', 'a.duoxuanbiaoqian', '学区']);
                }
                if ($v == '意向金') {
                    $row->andWhere(['like', 'a.xiading', '意向金']);
                }
                if ($v == '有跟进') {
                    $row->andWhere(['>', 'a.genjincishu', '0']);
                }
                if ($v == '有带看') {
                    $row->andWhere(['>', 'a.daikancishu', '0']);
                }
                if ($v == '封盘') {
                    $row->andWhere(['=', 'a.is_fengpan', '1']);
                }
                if ($v == '7天未跟进') {
                    $sj = strtotime("-7 day");
                    if ($sszhuangtai === '有效(私客)') {
                        $row->andWhere(['<', 'unix_timestamp(a.weihurengenjin)', $sj]);
                    }
                    if ($sszhuangtai === '有效(公客)') {
                        $row->andWhere(['<', 'unix_timestamp(a.quanyuangenjin)', $sj]);
                    }
                }
                if ($v == '30天未带看') {
                    $sj = strtotime("-30 day");

                    $row->andWhere(['<', 'unix_timestamp(a.daikanshijian)', $sj]);
                }

            }
        }
        $row->with(
            ['tel' => function ($query) {
                $query->select(['customer_uuid', 'tel_phone', 'tel_type'])->where(['is_del' => 0, 'company_id' => $this->_user['company_id']]);
            }]
        );
        if (!empty($param['paixu'])) {
            switch ($param['paixu']) {
                case 1:
                    $row->orderBy('a.genjincishu ASC');
                    break;
                case 2:
                    $row->orderBy('a.genjincishu DESC');
                    break;
                case 3:
                    $row->orderBy('a.daikancishu ASC');
                    break;
                case 4:
                    $row->orderBy('a.daikancishu DESC');
                    break;
                case 5:
                    $row->orderBy('ctime ASC');
                    break;
                case 6:
                    $row->orderBy('ctime DESC');
                    break;

            }
        } else {
            $row->orderBy('a.utime DESC');
        }
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();		

        foreach ($list as $key => $v) {
            $list[$key]['mianji'] = ($v['xuqiumianji_min'] ? $v['xuqiumianji_min'] : 0) . '-' . ($v['xuqiumianji_max'] ? $v['xuqiumianji_max'] : 0) . '平方米';
            if ($param['customer_type'] == 1) {
                $list[$key]['jiage'] = ($v['xuqiujiage_min'] ? $v['xuqiujiage_min'] : 0) . '-' . ($v['xuqiujiage_max'] ? $v['xuqiujiage_max'] : 0) . '元';
            } else {
                $list[$key]['jiage'] = ($v['xuqiujiage_min'] ? $v['xuqiujiage_min'] : 0) . '-' . ($v['xuqiujiage_max'] ? $v['xuqiujiage_max'] : 0) . '万';
            }
            $list[$key]['huxing'] = ($v['xuqiuhuxing_min'] ? $v['xuqiuhuxing_min'] : 0) . '-' . ($v['xuqiuhuxing_max'] ? $v['xuqiuhuxing_max'] : 0) . '室';
            if ($v['rn_name'] or $v['dts_name'] or $v['village_name']) {
                $list[$key]['xuqiuquyu'] = $v['dts_name'] . ';' . $v['rn_name'] . ';' . $v['village_name'];
            } else {
                $list[$key]['xuqiuquyu'] = '暂无';
            }
            if ($v['zhuangtai'] == '有效') {
                $list[$key]['zhuangtai'] = $v['customer_private'];
            }
            if ($v['customer_private'] == '私客') {
                $list[$key]['quanyuangenjin'] = '';
            }
            if ($v['daikanshijian'] == '0000-00-00 00:00:00') {
                $list[$key]['daikanshijian'] = '';
            }

            $customer_phone = Customer_tel::find()->andWhere(['customer_uuid' => $v['customer_uuid']])->one();
            $list[$key]['customer_phone'] = $customer_phone['tel_phone'];
            /*
             * 标签
             */
            if ($v['duoxuanbiaoqian']) {
                $duoxuanbiaoqian = explode(';', $v['duoxuanbiaoqian']);
                $list[$key]['biaoqian']['duoxuanbiaoqian'] = $duoxuanbiaoqian;
            }
            if ($v['xiading']) {
                $list[$key]['biaoqian']['xiading'] = $v['xiading'];
            }
            if ($v['is_fengpan']) {
                $list[$key]['biaoqian']['is_fengpan'] = 1;
            }
            $list[$key]['biaoqian']['genjincishu'] = $v['genjincishu'];
            $list[$key]['biaoqian']['daikancishu'] = $v['daikancishu'];
            if ($v['u_dept_id']) {
                $depart = Depart::find()->where(['d_id' => $v['u_dept_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                $list[$key]['bumen'] = $depart['d_name'];
            }
            if ($v['c_id']) {
                $user = User::find()->where(['u_id' => $v['c_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                $list[$key]['weihuren'] = $user['u_name'];
            }
			if ($v['u_id']) {
			    $user = User::find()->where(['u_id' => $v['u_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
			    $list[$key]['lururen'] = $user['u_name'];
			}
        }
        $data['list'] = $list;
        $data['count'] = $row->count();
        //配置项
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_gongsike', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['customer_private'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhuangtai', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['zhuangtai'] = $row->asArray()->all();
        if ($param['customer_type'] == 1) {
            $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhulinduoxuan', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['duoxuanbiaoqian'] = $row->asArray()->all();
        } else {
            $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_duoxuanbiaoqian', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['duoxuanbiaoqian'] = $row->asArray()->all();
        }
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehuchenghu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['customer_sex'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_chaoxiang', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['chaoxiang'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_fangyuanleixing', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fangwuleixing'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhuangxiu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['zhuangxiu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_yongtu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['yongtu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehuchenghu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['kehuchenghu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dianhuasuoshu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['dianhuasuoshu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_gongsike', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['gongsipan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_jiaotonggongju', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['jiaotonggongju'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_mingzu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['mingzu'] = $row->asArray()->all();
		 $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dengji', 'company_id' => $this->_user['company_id']]);
		$data['peizhi']['kehudengji'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_guoji', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['guoji'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_xiaofeilinian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['xiaofeilinian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehulaiyuan', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['kehulaiyuan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_goutongjieduan', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['goutongjieduan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_tuijianbiaoqian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['tuijianbiaoqian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_peitao', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['peitao'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'mjqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['xuqiumianji'] = $row->asArray()->all();
        if ($param['customer_type'] == 1) {
            $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'zuling_jigeqj', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['xuqiujiage'] = $row->asArray()->all();
        } else {
            $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'jgqj', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['xuqiujiage'] = $row->asArray()->all();
        }
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'hxqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['huxing'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'flqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fangling'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'lcqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['louceng'] = $row->asArray()->all();
        //客源等级
		$row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dengji', 'company_id' => $this->_user['company_id']]);
		$data['peizhi']['kydj'] = $row->asArray()->all();
        //$data['peizhi']['kydj'] = ['A级', 'B级', 'C级', 'D级'];	
        //获取片区小区树信息
        $data['peizhi']['villages'] = CommonController::getDtsList($this->_user['city_id'], $this->_user['company_id']);
        //员工数据
        $principal = User::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->all();
        foreach ($principal as $key => $item) {
            $data['peizhi']['users'][$item['u_dept_id']][] = $item;
        }
        //加载自定义列表
        $customcolumns = CustomColumns::find()->where(['u_id' => $this->_user['u_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->andWhere(['or', 'module=7', 'module=8', 'module=9'])->asArray()->all();
        if (!empty($customcolumns)) {
            foreach ($customcolumns as $key => $val) {
                $data['customcolumns'][$val['module']] = json_decode($val['columns'], true);
            }
        } else {
            $data['customcolumns'] = null;
        }
        //顶部按钮
        $data['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user);

        //必填项$param['customer_type']rsetting_options
        $row = ZhSettingRequired::find()->where(['rsetting_type' => 'customer_type' . $param['customer_type'], 'company_id' => $this->_user['company_id']]);
        $btx = $row->asArray()->one();

        $jgqj = ZhSettingQujian::find()->where(['qujian_shorthand' => 'jgqj', 'company_id' => $this->_user['company_id']])->select('qujian_desp')->asArray()->one();
        $data['peizhi']['jgqj'] = json_decode($jgqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['qujian_shorthand' => 'mjqj', 'company_id' => $this->_user['company_id']])->select('qujian_desp')->asArray()->one();
        $data['peizhi']['mjqj'] = json_decode($mjqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['qujian_shorthand' => 'zuling', 'company_id' => $this->_user['company_id']])->select('qujian_desp')->asArray()->one();
        $data['peizhi']['zlqj'] = json_decode($mjqj['qujian_desp'], true);

        $data['peizhi']['bitianxiang'] = $btx['rsetting_desp'];
        return ApiReturn::success('查询成功', $data);

    }

    /*
     * 刪除
     */
    public function actionAlldel()
    {
        $param = Yii::$app->request->post();
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        if (!empty($param['selection']) && $param['selection'] <> 'undefined') {
            try {
                foreach ($param['selection'] as $v) {
                    $Customer = Customer::find()->andWhere(['customer_uuid' => $v['customer_uuid'], 'company_id' => $this->_user['company_id']])->one();

                    $Customer->is_del = 1;
                    if ($Customer->update() === false) {
                        $transaction->rollBack();
                        return false;
                    };
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('请您选择您要删除的客源');
        }


    }

    /*
     * @客源导出
     */
    public function actionExport()
    {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);


        $param = Yii::$app->request->get();
        $customer_type = isset($param["type"]) ? $param["type"] : 0;

        if ($customer_type == 0) {
            $title = '买卖客源';
        } elseif ($customer_type == 1) {
            $title = '租赁客源';
        } elseif ($customer_type == 2) {
            $title = '高端客源';
        }

        $row = Customer::find()
            ->select('a.*')->alias('a')
            ->leftJoin('zh_customer_tel as t', 'a.customer_uuid=t.customer_uuid')
            ->leftJoin('zh_user as u', 'u.u_id=a.c_id')
            ->where(['a.customer_type' => $customer_type, 'a.is_del' => '0', 'a.company_id' => $this->_user['company_id']]);

        if (isset($param["aid"]) && $param["aid"]) {
            $row->andWhere(['a.dts_id' => $param['aid']]);
        }
        if (isset($param["vid"]) && $param["vid"]) {
            $row->andWhere(['a.rn_id' => $param['vid']]);
        }

        if (isset($param["sprice"]) && $param["sprice"]) {
            $tmpData = explode("-", $param["sprice"]);
            $row->andWhere(['>=', 'a.xuqiujiage_min', intval($tmpData[0])]);
            $row->andWhere(['<=', 'a.xuqiujiage_max', intval($tmpData[1])]);
        }
        if (isset($param["rprice"]) && $param["rprice"]) {  //价格区间
            $tmpData = explode("-", $param["rprice"]);
            $row->andWhere(['>=', 'a.xuqiujiage_min', intval($tmpData[0])]);
            $row->andWhere(['<=', 'a.xuqiujiage_max', intval($tmpData[1])]);
        }

        if (isset($param["area"]) && $param["area"]) {  //面积区间
            $tmpData = explode("-", $param["area"]);
            $row->andWhere(['>=', 'a.xuqiumianji_min', intval($tmpData[0])]);
            $row->andWhere(['<=', 'a.xuqiumianji_max', intval($tmpData[1])]);
        }

        $list = $row->orderBy('a.utime DESC')->asArray()->all();
        //var_dump($list);die;
        foreach ($list as $key => $v) {
            $list[$key]['mianji'] = ($v['xuqiumianji_min'] ? $v['xuqiumianji_min'] : 0) . '-' . ($v['xuqiumianji_max'] ? $v['xuqiumianji_max'] : 0) . '平方米';
            if ($v['customer_type'] == 1) {
                $list[$key]['jiage'] = ($v['xuqiujiage_min'] ? $v['xuqiujiage_min'] : 0) . '-' . ($v['xuqiujiage_max'] ? $v['xuqiujiage_max'] : 0) . '元';
            } else {
                $list[$key]['jiage'] = ($v['xuqiujiage_min'] ? $v['xuqiujiage_min'] : 0) . '-' . ($v['xuqiujiage_max'] ? $v['xuqiujiage_max'] : 0) . '万';
            }
            $list[$key]['huxing'] = ($v['xuqiuhuxing_min'] ? $v['xuqiuhuxing_min'] : 0) . '-' . ($v['xuqiuhuxing_max'] ? $v['xuqiuhuxing_max'] : 0) . '室';
            if ($v['rn_name'] or $v['dts_name']) {
                $list[$key]['xuqiuquyu'] = $v['dts_name'] . ';' . $v['rn_name'];
            } else {
                $list[$key]['xuqiuquyu'] = '暂无';
            }
            if ($v['zhuangtai'] == '有效') {
                $list[$key]['zhuangtai'] = $v['customer_private'];
            }
            if ($v['customer_private'] == '私客') {
                $list[$key]['quanyuangenjin'] = '';
            }
            if ($v['daikanshijian'] == '0000-00-00 00:00:00') {
                $list[$key]['daikanshijian'] = '';
            }
            /*
             * 标签
             */
            if ($v['duoxuanbiaoqian']) {
                $duoxuanbiaoqian = explode(';', $v['duoxuanbiaoqian']);
                $list[$key]['biaoqian']['duoxuanbiaoqian'] = $duoxuanbiaoqian;
            }
            if ($v['xiading']) {
                $list[$key]['biaoqian']['xiading'] = $v['xiading'];
            }
            if ($v['is_fengpan']) {
                $list[$key]['biaoqian']['is_fengpan'] = 1;
            }
            $list[$key]['biaoqian']['genjincishu'] = $v['genjincishu'];
            $list[$key]['biaoqian']['daikancishu'] = $v['daikancishu'];
            if ($v['u_dept_id']) {
                $depart = Depart::find()->where(['d_id' => $v['u_dept_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                $list[$key]['bumen'] = $depart['d_name'];
            }
            $list[$key]['weihuren'] = '';
            if ($v['c_id']) {
                $user = User::find()->where(['u_id' => $v['c_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                $list[$key]['weihuren'] = $user['u_name'];
            }
        }

        //var_dump($list);die;
        $n = 0;
        foreach ($list as $v) {
            //报表头的输出
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:R1');
            $objectPHPExcel->getActiveSheet()->setCellValue('A1', $title);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $title);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $title);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '日期：' . date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('R2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '客源编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '需求用途');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '客户姓名');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '客户来源');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '需求区域');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '面积');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '价格');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '户型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '沟通阶段');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', '备注');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', '等级');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', '录入日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', '全员最后跟进');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', '最后带看时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', '维护人最后跟进');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', '维护人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3', '部门');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($n + 4), $v['xuqiubianhao']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B' . ($n + 4), $v['zhuangtai']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n + 4), $v['yongtu']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n + 4), $v['customer_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n + 4), $v['kehulaiyuan']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n + 4), $v['xuqiuquyu']);
            $objectPHPExcel->getActiveSheet()->setCellValue('G' . ($n + 4), $v['mianji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('H' . ($n + 4), $v['jiage']);
            $objectPHPExcel->getActiveSheet()->setCellValue('I' . ($n + 4), $v['huxing']);
            $objectPHPExcel->getActiveSheet()->setCellValue('J' . ($n + 4), $v['goutongjieduan']);
            $objectPHPExcel->getActiveSheet()->setCellValue('K' . ($n + 4), $v['mark']);
            $objectPHPExcel->getActiveSheet()->setCellValue('L' . ($n + 4), $v['tiaojiandengji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('M' . ($n + 4), $v['ctime']);
            $objectPHPExcel->getActiveSheet()->setCellValue('N' . ($n + 4), $v['quanyuangenjin']);
            $objectPHPExcel->getActiveSheet()->setCellValue('O' . ($n + 4), $v['daikanshijian']);
            $objectPHPExcel->getActiveSheet()->setCellValue('P' . ($n + 4), $v['weihurengenjin']);
            $objectPHPExcel->getActiveSheet()->setCellValue('Q' . ($n + 4), $v['weihuren']);
            $objectPHPExcel->getActiveSheet()->setCellValue('R' . ($n + 4), $v['bumen']);
            $n = $n + 1;
        }

        //设置分页显示
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header("Content-type: text/html; charset=utf-8");
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="ky-' . date("Y/m/j") . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');

        $objWriter->save('php://output');
    }

    /**
     * @return \common\models\json
     * 获取日志
     */
    public function actionLog()
    {
        $param = Yii::$app->request->get();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = Customer_log::find()->where(['customer_uuid' => $param['customer_uuid']]);
        $list = $row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();
        foreach ($list as $key => $v) {
            if ($v['c_id']) {
                $user = User::find()->where(['u_id' => $v['c_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                if ($user['u_dept_id']) {
                    $depart = Depart::find()->where(['d_id' => $user['u_dept_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                    $list[$key]['bumen'] = $depart['d_name'];
                }
                $list[$key]['caozuoren'] = $user['u_name'];
            }
            switch ($v['cl_type']) {
                case 1:
                    $list[$key]['cl_type'] = '查看客源详情';
                    break;
                case 2:
                    $list[$key]['cl_type'] = '添加客源手机号码';
                    break;
                case 3:
                    $list[$key]['cl_type'] = '修改客源信息';
                    break;
                case 4:
                    $list[$key]['cl_type'] = '添加客源信息';
                    break;
                case 5:
                    $list[$key]['cl_type'] = '删除客源';
                    break;
                case 6:
                    $list[$key]['cl_type'] = '添加意向金';
                    break;
                case 7:
                    $list[$key]['cl_type'] = '添加带看';
                    break;
                case 8:
                    $list[$key]['cl_type'] = '删除带看';
                    break;
                case 9:
                    $list[$key]['cl_type'] = '添加跟进';
                    break;
                case 10:
                    $list[$key]['cl_type'] = '跟进列表';
                    break;
                case 11:
                    $list[$key]['cl_type'] = '查看电话';
                    break;
                case 12:
                    $list[$key]['cl_type'] = '申请封盘';
                    break;
                case 21:
                    $list[$key]['cl_type'] = '客源状态审核';
                    break;
                case 22:
                    $list[$key]['cl_type'] = '客源状态修改';
                    break;
                case 23:
                    $list[$key]['cl_type'] = '申请[封路径]';
                    break;
                case 24:
                    $list[$key]['cl_type'] = '客源删除';
                    break;
                case 25:
                    $list[$key]['cl_type'] = '自动跳为公客';
                    break;
            }
        }
        $data['list'] = $list;
        return ApiReturn::success('查询成功', $data);
    }

    /*
     * 获取单条客户信息
     */
    public function actionGetcustomer()
    {
        $param = Yii::$app->request->get();
        if (empty($param["customer_uuid"])) {
            return ApiReturn::wrongParams('客户id不能为空');
        }
        $user = $this->_user;
        $u_dept_id = $user['u_dept_id'];
        //部门数据
        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        //获取本人本组大区本大区的方法
        if (!empty($param['customer_type']) && $param['customer_type'] == 1) {
            $bendianbenzu = Customer::getBumen(148, $user);
        } elseif (!empty($param['customer_type']) && $param['customer_type'] == 2) {
            $bendianbenzu = Customer::getBumen(150, $user);
        } else {
            $bendianbenzu = Customer::getBumen(146, $user);
        }
        //如果有部门查询 就找出当前查询部门的所有数据
        if (!empty($param['d_id'])) {
            $departlist = Customer::getTree($depart, $param['d_id']);
        } else {
            /*
             * 初始化部门
             */
            //获取子集的树
            $departlist = Customer::getTree($depart, $bendianbenzu);

            //把 树转换为列
            $departlist = Customer::setlistname($departlist);
            //获取父
            $departzhuyaode = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id'], 'd_id' => $bendianbenzu])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();

            //子集加上父集
            if (!empty($departlist) && !empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid'] = 0;
                $departlist = array_merge($departlist, $departzhuyaode);
            } else if (!empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid'] = 0;
                $departlist = $departzhuyaode;
            }

            //数据排序一下
            if (!empty($departlist)) {
                $departlist = Tools::listToTree($departlist, 'value', 'd_pid', 'children');
            }
        }

        //判断是否为真和是否为本人
        if ($bendianbenzu && $bendianbenzu != $user['u_id']) {
            $u_dept_id = $bendianbenzu;
            if (empty($param['d_id'])) {
                if ($bendianbenzu <> 'sys') {
                    foreach ($departlist as $v) {
                        //判断与他自己的部门
                        if ($v['value'] == $u_dept_id) {
                            $benzutree[] = $v;
                        }
                    }
                } else {
                    $benzutree = $data['peizhi']['departlist'];
                }

            } else {
                $benzutree = $departlist;
            }

            if (!empty($benzutree)) {

                $data['peizhi']['benzu'] = $benzutree;
                //把多维 树形数据变成一维
                $benzu = Customer::setlist($benzutree);
                $benzuin = !empty($param['d_id']) ? $param['d_id'] . ',' : '';
                //用in 的方法查出包含部门的所用用户数据。
                foreach ($benzu as $v) {
                    $benzuin .= $v['value'] . ',';
                }
                $benzuin = substr($benzuin, 0, strlen($benzuin) - 1);
            }
        }
        $customer_uuid = $param["customer_uuid"];
        $row = Customer::find()
            ->select('a.*')->alias('a')
            ->leftJoin('zh_customer_tel as t', 'a.customer_uuid=t.customer_uuid')
            ->where(['a.is_del' => '0', 'a.company_id' => $this->_user['company_id'], 'a.customer_uuid' => $customer_uuid])->with(
                ['tel' => function ($query) {
                    $query->select(['customer_uuid', 'tel_phone', 'tel_type'])->where(['is_del' => 0, 'company_id' => $this->_user['company_id']]);
                }]
            );
        //客源本人本组权限
        if (!empty($benzuin) && $bendianbenzu <> $user['u_id']) {
            $row->andWhere("a.u_dept_id in ($benzuin) or a.c_id is  null");
        } else {
            if ((!$bendianbenzu or $bendianbenzu == $user['u_id'])) {
                $u_id = $user['u_id'];
                $row->andWhere("a.zhuangtai='有效' or a.zhuangtai='无效' or a.zhuangtai='其他'");
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");
            }
        }
        $list = $row->asArray()->one();
        //下一页
        $is_next = Customer::find()->select(['customer_uuid', 'customer_type'])->where(['is_del' => 0, 'company_id' => $this->_user['company_id'], 'customer_type' => $param['customer_type']])->andWhere(['<', 'utime', $list['utime']]);
        if (!empty($benzuin) && $bendianbenzu <> $user['u_id']) {
            $is_next->andWhere("u_dept_id in ($benzuin) or c_id is  null");
        } else {
            if ((!$bendianbenzu or $bendianbenzu == $user['u_id'])) {
                $u_id = $user['u_id'];
                $is_next->andWhere("zhuangtai='有效' or zhuangtai='无效' or zhuangtai='其他'");
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $is_next->andWhere("(customer_private = '私客' and c_id='$u_id') or (customer_private = '公客' and is_fengpan = '0') or (is_fengpan='1' and fengpan_user='$u_id' and unix_timestamp(fengpandaoqi)>'$sjtime')");
            }
        }
        $data['is_next'] = $is_next->limit(1)->orderBy('utime DESC')->asArray()->one();
        //上一页
        $is_last = Customer::find()->select(['customer_uuid', 'customer_type'])->where(['is_del' => 0, 'company_id' => $this->_user['company_id'], 'customer_type' => $param['customer_type']])->andWhere(['>', 'utime', $list['utime']]);
        if (!empty($benzuin) && $bendianbenzu <> $user['u_id']) {
            $is_last->andWhere("u_dept_id in ($benzuin) or c_id is  null");
        } else {
            if ((!$bendianbenzu or $bendianbenzu == $user['u_id'])) {
                $u_id = $user['u_id'];
                $is_last->andWhere("zhuangtai='有效' or zhuangtai='无效' or zhuangtai='其他'");
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $is_last->andWhere("(customer_private = '私客' and c_id='$u_id') or (customer_private = '公客' and is_fengpan = '0') or (is_fengpan='1' and fengpan_user='$u_id' and unix_timestamp(fengpandaoqi)>'$sjtime')");
            }
        }
        $data['is_last'] = $is_last->limit(1)->orderBy('utime ASC')->asArray()->one();
        $list['mianji'] = (!empty($list['xuqiumianji_min']) ? $list['xuqiumianji_min'] : 0) . '-' . (!empty($list['xuqiumianji_max']) ? $list['xuqiumianji_max'] : 0) . '平方米';
        if ($param['customer_type'] == 1) {
            $list['jiage'] = (!empty($list['xuqiujiage_min']) ? $list['xuqiujiage_min'] : 0) . '-' . (!empty($list['xuqiujiage_max']) ? $list['xuqiujiage_max'] : 0) . '元';
        } else {
            $list['jiage'] = (!empty($list['xuqiujiage_min']) ? $list['xuqiujiage_min'] : 0) . '-' . (!empty($list['xuqiujiage_max']) ? $list['xuqiujiage_max'] : 0) . '万';
        }
        $list['huxing'] = (!empty($list['xuqiuhuxing_min']) ? $list['xuqiuhuxing_min'] : 0) . '-' . (!empty($list['xuqiuhuxing_max']) ? $list['xuqiuhuxing_max'] : 0) . '室';

        $role = Role::find()->where(['role_id' => $this->_user['u_role_id'], 'company_id' => $this->_user['company_id']])->asArray()->one();
        $data['peizhi']['bendianbenzu'] = '';
        if (!empty($list['tel']) && $role['role_type'] != 4 && $list['c_id'] != $this->_user['u_id']) {
            foreach ($list['tel'] as $key1 => $val) {
                $list['tel'][$key1]['tel_phone'] = substr_replace($val['tel_phone'], '****', 3, 4);
            }
        } else {
            $data['peizhi']['bendianbenzu'] = $role['role_type'];
        }
        if (!empty($list['rn_name']) or !empty($list['dts_name'])) {
            $list['xuqiuquyu'] = $list['dts_name'] . ';' . $list['rn_name'];
        } else {
            $list['xuqiuquyu'] = '暂无';
        }
        if (!empty($list['u_dept_id'])) {
            $depart = Depart::find()->where(['d_id' => $list['u_dept_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
            $list['bumen'] = $depart['d_name'];
        }
        if (!empty($list['c_id'])) {
            $user = User::find()->where(['u_id' => $list['c_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
            $list['weihuren'] = $user['u_name'];
        }
        if (!empty($list['customer_type']) == 0) {
            $list['customer_type'] = "求购";
        }
        if (!empty($list['customer_type']) == 1) {
            $list['customer_type'] = "租赁";
        }
        if (!empty($list['customer_type']) == 2) {
            $list['customer_type'] = "高端求购";
        }

        $data['list'] = $list;
        $data['count'] = $row->count();
        //配置项
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_gongsike', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['customer_private'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'fengpanyuanyin', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fengpanyuanyin'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_genjinfangshi', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['genjinfangshi'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhuangtai', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['zhuangtai'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_duoxuanbiaoqian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['duoxuanbiaoqian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehuchenghu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['customer_sex'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_chaoxiang', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['chaoxiang'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_fangyuanleixing', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fangwuleixing'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhuangxiu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['zhuangxiu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_yongtu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['yongtu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehuchenghu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['kehuchenghu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dianhuasuoshu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['dianhuasuoshu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_gongsike', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['gongsipan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_jiaotonggongju', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['jiaotonggongju'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_mingzu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['mingzu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_guoji', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['guoji'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_xiaofeilinian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['xiaofeilinian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehulaiyuan', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['kehulaiyuan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_goutongjieduan', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['goutongjieduan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_tuijianbiaoqian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['tuijianbiaoqian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_peitao', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['peitao'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'mjqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['xuqiumianji'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'hxqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['huxing'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'flqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fangling'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'lcqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['louceng'] = $row->asArray()->all();
		 $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dengji', 'company_id' => $this->_user['company_id']]);
		$data['peizhi']['kehudengji'] = $row->asArray()->all();
        if ($param['customer_type'] == 1) {
            $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'zuling_jigeqj', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['xuqiujiage'] = $row->asArray()->all();
        } else {
            $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'jgqj', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['xuqiujiage'] = $row->asArray()->all();
        }
        $order_pay = ZhSettingBase::find()->where(['base_shorthand' => 'collectionPayway', 'company_id' => $this->_user['company_id']])->select('base_desp')->asArray()->one();
        $data['peizhi']['way'] = json_decode($order_pay['base_desp'], true);
        //部门数据
        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        //员工数据
        $principal = User::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']]);
        $principal->andWhere('u_status=1 or u_status=2');
        $principal=$principal->asArray()->all();
        foreach ($principal as $key => $item) {
            $data['peizhi']['users'][$item['u_dept_id']][] = $item;
        }
        //获取片区小区树信息
        $data['peizhi']['villages'] = CommonController::getDtsList($this->_user['city_id'], $this->_user['company_id']);
        //必填项$param['customer_type']rsetting_options
        $row = ZhSettingRequired::find()->where(['rsetting_type' => 'customer_type' . $param['customer_type'], 'company_id' => $this->_user['company_id']]);
        $btx = $row->asArray()->one();

        $data['peizhi']['bitianxiang'] = $btx['rsetting_desp'];
        $data['peizhi']['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user);
        //写日志 1(查看客源详情)
        $Customer_log = new Customer_log();
        $Customer_log->log($customer_uuid, 1, '查看客源信息', $this->_user());
        return ApiReturn::success('查询成功', $data);
    }

    //查看电话号码
    public function actionGettel()
    {
        $param = Yii::$app->request->get();
        if (empty($param["customer_uuid"])) {
            return ApiReturn::wrongParams('客户id不能为空');
        }

        $customer_uuid = $param["customer_uuid"];
        $row = Customer::find()
            ->select('a.*')->alias('a')
            ->leftJoin('zh_customer_tel as t', 'a.customer_uuid=t.customer_uuid')
            ->where(['a.is_del' => '0', 'a.customer_uuid' => $customer_uuid, 'a.company_id' => $this->_user['company_id']])->with(
                ['tel' => function ($query) {
                    $query->select(['customer_uuid', 'tel_phone', 'tel_type'])->where(['is_del' => 0, 'company_id' => $this->_user['company_id']]);
                }]
            );

        $list = $row->asArray()->one();
        $data['list'] = $list;

        //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话)',
        $Customer_log = new Customer_log();
        $Customer_log->log($customer_uuid, 11, '查看电话', $this->_user());
        return ApiReturn::success('查看成功', $data);
    }

    /***
     * 变更状态
     */
    public function actionSetstatus()
    {
        $post = Yii::$app->request->post();
        $xiugaifangyuanzhuangtai = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_biangeng_zhuangtai_shr', 'company_id' => $this->_user['company_id']])->asArray()->one();

        //判断是否有没有审核的项目 2为客源
        if (!Verify::verifyService(2, $post['customer_uuid'])) {
            return ApiReturn::wrongParams('当前资源有操作还在审核');
        }
        $user = $this->_user();
        $param = [
            'u_id' => $user['u_id'],
            'company_id' => $user['company_id'],
            'customer_uuid' => $post['customer_uuid'],
            'customer_status' => $post['customer_status'],
            'utime' => date('Y-m-d H:i:s', time()),
        ];
        if ($xiugaifangyuanzhuangtai['val'] == 1) {
            $shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_biangeng_zhuangtai', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $zhuguan = $this->_getShengheren($this->_user, $shengheren['val']);

            if (!$zhuguan) {
                return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
            }
            $model = new Verify();
            $model->u_id = $user['u_id'];
            $model->v_post_user = $user['u_id'];
            $model->v_type = '客源状态审核';
            $model->v_end_user = $zhuguan;
            $model->v_pass_func = serialize(['backend\models' . '\Customer', 'setStatus']);
            $model->v_pass_param = serialize($param);
            $model->v_service_id = $param['customer_uuid'];
            $model->v_service_sn = $post['customer_sn'];
            $model->v_service_type = 2;
            $model->company_id = $this->_user['company_id'];
            $model->v_content = '修改客源状态为[' . $post['customer_status'] . '],修改理由：' . $post['content'];
            $model->v_status = 0;
            $model->c_id = $model->u_id = $user['u_id'];
            $model->ctime = $model->utime = date('Y-m-d H:i:s', time());

            if ($model->save()) {
                //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话) 21客源状态审核',
                $Customer_log = new Customer_log();
                $Customer_log->log($post['customer_uuid'], 21, '客源状态审核', $this->_user());
                return ApiReturn::success('状态修改已提交，等待审核');
            } else {
                var_dump($model->getErrors());
                return ApiReturn::wrongParams('状态修改失败2');
            }

        } else {
            if (customer::setStatus($param)) {
                //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话) 21客源状态审核',
                $Customer_log = new Customer_log();
                $Customer_log->log($post['customer_uuid'], 22, '客源状态修改', $this->_user());
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改失败1');
            }
        }

    }

    //客源封盘
    public function actionFengpan()
    {
        $post = Yii::$app->request->post();
        $customer = Customer::find()->where(['customer_uuid' => $post['customer_uuid'], 'company_id' => $this->_user['company_id']])->asArray()->one();
        if (!$customer) {
            return ApiReturn::wrongParams('参数错误');
        }
        //判断是否需要审核
        $fengpanshenhe = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_fengpan_shenhe_shr', 'company_id' => $this->_user['company_id']])->asArray()->one();
        $fengpanzuiduodaoqitianshu = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_fengpan_shijian', 'company_id' => $this->_user['company_id']])->asArray()->one();
        $fengpanshuliang = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_sike_shuliang', 'company_id' => $this->_user['company_id']])->asArray()->one();

        //判断是否有没有审核的项目
        if (!Verify::verifyService(2, $post['customer_uuid'])) {
            return ApiReturn::wrongParams('当前资源有操作还在审核');
        }

        //查看封盘数量
        $userFengpanCount = Customer::find()->where(['fengpan_user' => $this->_user['u_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->andWhere(['or', ['is_fengpan' => '1'], ['is_fengpan' => '2']])->count();
        if ($userFengpanCount >= $fengpanshuliang['val']) {
            return ApiReturn::wrongParams('您能保留的封盘为:' . $fengpanshuliang['val'] . ',已经用完了。');
        }
        $fengpanNum = $fengpanzuiduodaoqitianshu['val'];
        $param = [
            'u_id' => $this->_user['u_id'],
            'customer_uuid' => $post['customer_uuid'],
            'is_fengpan' => 1,
            'fengpandaoqi' => date('Y-m-d H:i:s', strtotime("+$fengpanNum day", time())),
            'fengpan_user' => $this->_user['u_id'],
            'company_id' => $this->_user['company_id'],
            'utime' => date('Y-m-d H:i:s', time()),
        ];
        $v_type = "封路径";

        if ($fengpanshenhe['val'] == 1) {
            $shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_fengpan_shenhe', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $zhuguan = $this->_getShengheren($this->_user, $shengheren['val']);

            if (!$zhuguan) {
                return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
            }
            $model = new Verify();
            $model->u_id = $this->_user['u_id'];
            $model->v_post_user = $this->_user['u_id'];
            $model->v_type = $v_type;
            $model->v_end_user = $zhuguan;
            $model->v_pass_func = serialize(['backend\models' . '\Customer', 'setFengpan']);
            $model->v_pass_param = serialize($param);
            $model->v_service_id = $param['customer_uuid'];
            $model->v_service_sn = $post['xuqiubianhao'];
            $model->v_service_type = 2;
            $model->company_id = $this->_user['company_id'];
            $model->v_content = '申请[封路径]' . '原因:' . $post['fengpanyuanyin'] . '备注:' . $post['beizhu'];
            $model->v_status = 0;
            $model->c_id = $model->u_id = $this->_user['u_id'];
            $model->ctime = $model->utime = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话) 21客源状态审核',
                $Customer_log = new Customer_log();
                $Customer_log->log($post['customer_uuid'], 23, '申请[封路径]', $this->_user());
                return ApiReturn::success('申请已提交，等待审核');
            } else {
                var_dump($model->getErrors());
                return ApiReturn::wrongParams('状态修改失败2');
            }

        } else {
            if (Customer::setFengpan($param)) {
                //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话) 21客源状态审核',
                $Customer_log = new Customer_log();
                $Customer_log->log($post['customer_uuid'], 23, '申请[封路径]', $this->_user());
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改失败1');
            }
        }
    }

    /*
     * 设为公客私客
     */
    public function actionGksk()
    {
        $param = Yii::$app->request->post();
        $Customer = Customer::find()->where(['customer_uuid' => $param['customer_uuid'], 'company_id' => $this->_user['company_id'], 'is_del' => 0])->one();
        if ($param['customer_private'] == '公客') {
            $sikeduodaoqitianshu = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_sike_shuliang', 'company_id' => $this->_user['company_id']])->asArray()->one();
            //查看公客私客
            $userFengpanCount = Customer::find()->where(['c_id' => $this->_user['u_id'], 'customer_private' => '私客', 'is_del' => 0, 'company_id' => $this->_user['company_id'], 'customer_type' => $Customer->customer_type])->count();
            if ($userFengpanCount >= $sikeduodaoqitianshu['val']) {
                return ApiReturn::wrongParams('您能保留的私客为:' . $sikeduodaoqitianshu['val'] . ',已经用完了。');
            }
            $telture = Customer_tel::find()->where(['customer_uuid' => $param['customer_uuid'], 'company_id' => $this->_user['company_id'], 'is_del' => '0',])->all();

            $c = 0;
            if (!empty($telture)) {//判断 手机号码是否重复
                foreach ($telture as $val) {
                    $user = $this->_user;
                    $row = Customer_tel::find()->select('t.*')->alias('t')
                        ->leftJoin('zh_customer as c', 'c.customer_uuid=t.customer_uuid')
                        ->where(['t.tel_phone' => $val->tel_phone, 'c.is_del' => '0', 'c.customer_private' => '私客', 'c.c_id' => $user['u_id'], 'c.company_id' => $this->_user['company_id'], 'c.customer_type' => $Customer->customer_type]);
                    $count = $row->andWhere(['<>', 'c.customer_uuid', $param['customer_uuid']])->count();
                    if ($count > 0) {
                        return ApiReturn::wrongParams('设为私客失败，您的私客已经拥有该客源');
                    }
                    $val->customer_private = '私客';
                    $val->save();
                }
            }
            $model = Customer::find()->where(['customer_uuid' => $param['customer_uuid'], 'company_id' => $this->_user['company_id'], 'is_del' => 0])->one();
            $model->customer_private = "私客";
            $model->weihurengenjin = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->c_id = $this->_user['u_id'];
            $model->u_dept_id = $this->_user['u_dept_id'];
            $model->auth_uid = $this->_user['auth_uid'];
            $model->auth_rid = $this->_user['auth_rid'];
            $model->auth_sid = $this->_user['auth_sid'];
            $model->auth_aid = $this->_user['auth_aid'];
            $model->auth_baid = $this->_user['auth_baid'];
            $model->u_id = $this->_user['u_id'];
            if ($model->save()) {
                return ApiReturn::success('设为私客成功');
            } else {
                return ApiReturn::wrongParams('设为私客失败');
            }
        } else {
            $model = Customer::find()->where(['customer_uuid' => $param['customer_uuid'], 'company_id' => $this->_user['company_id'], 'is_del' => 0])->one();
            $telture = Customer_tel::find()->where(['customer_uuid' => $param['customer_uuid'], 'company_id' => $this->_user['company_id'], 'is_del' => 0])->all();
            $c = 0;
            if (!empty($telture)) {
                foreach ($telture as $val) {
                    $rw = Customer_tel::find()->select('t.*')->alias('t')
                        ->leftJoin('zh_customer as c', 'c.customer_uuid=t.customer_uuid')
                        ->where(['t.tel_phone' => $val->tel_phone, 'c.company_id' => $this->_user['company_id'], 'c.is_del' => '0', 'c.customer_private' => '公客', 'c.customer_type' => $Customer->customer_type]);
                    $count = $rw->andWhere(['<>', 'c.customer_uuid', $Customer->customer_uuid])->count();
                    $val->customer_private = '公客';
                    if ($count > 0) {
                        $val->is_del = 1;
                        $c = 1;
                    }
                    $val->save();
                }
            }
            if ($c == 1) {
                $model->is_del = 1;
                $model->customer_private = '公客';
                if ($model->save()) {
                    //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话) 21客源状态审核 24 客源删除',
                    $Customer_log = new Customer_log();

                    $Customer_log->log($param['customer_uuid'], 24, '公盘中已经存在该客源，当前客源已经删除', $this->_user());
                    return ApiReturn::success('公盘中已经存在该客源，当前客源已经删除');
                } else {
                    return ApiReturn::success('操作异常！');
                }
            }
            $model->customer_private = "公客";
            $model->c_id = NULL;
            $model->quanyuangenjin = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->u_id = $this->_user['u_id'];
            if ($model->save()) {
                //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加意向金)7(添加带看)8(删除带看)9(添加跟进)10(查看跟进列表)11(查看电话) 21客源状态审核 24 客源删除',
                $Customer_log = new Customer_log();
                $Customer_log->log($param['customer_uuid'], 25, '设为公客成功', $this->_user());
                return ApiReturn::success('设为公客成功');
            } else {
                return ApiReturn::wrongParams('设为公客失败');
            }

        }
    }

    /*
     * 设为主推
     */
    public function actionSwzt()
    {
        $param = Yii::$app->request->post();
        if ($param['zhutui'] == 1) {
            $model = Customer::find()->where(['customer_uuid' => $param['customer_uuid'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->one();
            $model->zhutui = 0;
            if ($model->save()) {
                return ApiReturn::success('取消主推成功');
            } else {
                return ApiReturn::wrongParams('取消主推失败');
            }
        } else {
            $model = Customer::find()->where(['customer_uuid' => $param['customer_uuid'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->one();
            $model->zhutui = 1;
            if ($model->save()) {
                return ApiReturn::success('设为主推成功');
            } else {
                return ApiReturn::wrongParams('设为主推失败');
            }

        }
    }

    public function actionAddtel()
    {
        $param = Yii::$app->request->post();
        $modeltel = new Customer_tel();
        $Customer = Customer::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id'], 'customer_uuid' => $param['customer_uuid']])->one();
        $customer_tel = new Customer_tel();
        if (!$customer_tel::validatePhone($param['tel_phone'], $param['customer_private'], $this->_user, $Customer->customer_type)) {
            return ApiReturn::wrongParams('系统中已经存在此用户的手机号了,请勿重复添加');
        }
        $modeltel->tel_phone = trim($param['tel_phone']);
        $modeltel->tel_type = trim($param['tel_type']);
        $modeltel->customer_private = trim($param['customer_private']);
        $modeltel->customer_uuid = $param['customer_uuid'];
        $modeltel->company_id = $this->_user['company_id'];
        $user = $this->_user();
        $modeltel->u_id = $user['u_id'];
        $modeltel->c_id = $user['u_id'];
        $modeltel->utime = date('Y-m-d H:i:s');
        $modeltel->auth_cid = $user['auth_cid'];
        $modeltel->auth_rid = $user['auth_rid'];
        $modeltel->auth_sid = $user['auth_sid'];
        $modeltel->auth_aid = $user['auth_aid'];
        $modeltel->auth_baid = $user['auth_baid'];
        $result = $modeltel->save();
        //写日志 1(查看客源详情) 2(添加客源手机号码)
        $Customer_log = new Customer_log();
        $Customer_log->log($param['customer_uuid'], 2, '添加客源手机号码' . substr_replace($param['tel_phone'], '****', 3, 4), $this->_user());
        if ($result) {
            return ApiReturn::success('添加成功');
        } else {
            return ApiReturn::wrongParams('添加成功');
        }
    }

    /**
     * 修改
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {
            //var_dump($post);die;
            $model = new Customer();
            $result = $model->updateCustomer($post, $this->_user);
            $message = '添加';
            if (isset($post['customer_uuid']) && $post['customer_uuid']) {
                $message = '更新';
            }
            if ($result) {
                return ApiReturn::success($message . '成功');
            } else {
                return ApiReturn::wrongParams($message . '失败');
            }
        }
    }

    /**
     * 添加客户
     */
    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {
            if (empty($post['customer_uuid'])) {
                //判断手机号有没有重复
                $customer_tel = new Customer_tel();
                if (!$customer_tel::validatePhone($post['tel_phone'], $post['customer_private'], $this->_user, $post['customer_type'])) {
                    return ApiReturn::wrongParams('系统中已经存在此用户的手机号了,请勿重复添加');
                }
                $sikeduodaoqitianshu = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'customer_sike_shuliang', 'company_id' => $this->_user['company_id']])->asArray()->one();
                //查看公客私客
                $userFengpanCount = Customer::find()->where(['c_id' => $this->_user['u_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id'], 'customer_private' => '私客', 'customer_type' => $post['customer_type']])->count();
                if ($userFengpanCount >= $sikeduodaoqitianshu['val']) {
                    return ApiReturn::wrongParams('您能保留的私客为:' . $sikeduodaoqitianshu['val'] . ',已经用完了。请把多余私客设为公客。');
                }
            }
            $model = new Customer();
            $result = $model->updateCustomer($post, $this->_user());
            $message = '添加';
            if (isset($post['customer_uuid']) && $post['customer_uuid']) {
                $message = '更新';
            }
            if ($result) {
                return ApiReturn::success($message . '成功');
            } else {
                return ApiReturn::wrongParams($message . '失败');
            }
        }
    }

    /**
     * 删除客户
     */
    public function actionCustomerdel()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ApiReturn::wrongParams('参数有误');
        }
        $model = Customer::find()->where(['customer_uuid' => $id, 'is_del' => 0, 'company_id' => $this->_user['company_id']])->one();
        $model->is_del = 1;
        $result = $model->save();
        //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)
        $Customer_log = new Customer_log();
        $Customer_log->log($id, 5, '删除客源', $this->_user());
        if ($result) {
            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::success('删除失败');
        }
    }
	
	/*
	**
	** 新版导出
	*/
    public function actionExportn()
    {
		$objectPHPExcel = new \PHPExcel();
		$objectPHPExcel->setActiveSheetIndex(0);
		$title = '买卖客源';
        $param = Yii::$app->request->get();				
        $user = $this->_user;
        $u_dept_id = $user['u_dept_id'];		
        //部门数据
        $depart = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['peizhi']['departlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');

        //获取本人本组大区本大区的方法
        if (!empty($param['customer_type']) && $param['customer_type'] == 1) {
            $bendianbenzu = Customer::getBumen(148, $user);
        } elseif (!empty($param['customer_type']) && $param['customer_type'] == 2) {
            $bendianbenzu = Customer::getBumen(150, $user);
        } else {
            $bendianbenzu = Customer::getBumen(146, $user);
        }
        //如果有部门查询 就找出当前查询部门的所有数据
        if (!empty($param['d_id'])) {
            $departlist = Customer::getTree($depart, $param['d_id']);
        } else {
            /*
             * 初始化部门
             */
            //获取子集的树
            $departlist = Customer::getTree($depart, $bendianbenzu);

            //把 树转换为列
            $departlist = Customer::setlistname($departlist);
            //获取父
            $departzhuyaode = Depart::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id'], 'd_id' => $bendianbenzu])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();

            //子集加上父集
            if (!empty($departlist) && !empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid'] = 0;
                $departlist = array_merge($departlist, $departzhuyaode);
            } else if (!empty($departzhuyaode)) {
                $departzhuyaode[0]['d_pid'] = 0;
                $departlist = $departzhuyaode;
            }

            //数据排序一下
            if (!empty($departlist)) {
                $departlist = Tools::listToTree($departlist, 'value', 'd_pid', 'children');
            }
        }

        //判断是否为真和是否为本人
        if ($bendianbenzu && $bendianbenzu != $user['u_id']) {
            $u_dept_id = $bendianbenzu;
            if (empty($param['d_id'])) {
                if ($bendianbenzu <> 'sys') {
                    foreach ($departlist as $v) {
                        //判断与他自己的部门
                        if ($v['value'] == $u_dept_id) {
                            $benzutree[] = $v;
                        }
                    }
                } else {
                    $benzutree = $data['peizhi']['departlist'];
                }

            } else {
                $benzutree = $departlist;
            }

            if (!empty($benzutree)) {

                $data['peizhi']['benzu'] = $benzutree;
                //把多维 树形数据变成一维
                $benzu = Customer::setlist($benzutree);
                $benzuin = !empty($param['d_id']) ? $param['d_id'] . ',' : '';
                //用in 的方法查出包含部门的所用用户数据。
                foreach ($benzu as $v) {
                    $benzuin .= $v['value'] . ',';
                }
                $benzuin = substr($benzuin, 0, strlen($benzuin) - 1);
            }
        }
        if (!empty($param['d_id']) && empty($benzuin)) {
            $benzuin = $param['d_id'];
        }
       $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;
        $row = Customer::find()
            ->select('a.*')->alias('a')
            ->leftJoin('zh_user as u', 'u.u_id=a.c_id')
            ->where(['a.is_del' => '0', 'a.company_id' => $this->_user['company_id']]);
        $sszhuangtai = !empty($param['sszhuangtai']) ? trim($param['sszhuangtai']) : 0;
        if ($sszhuangtai && $sszhuangtai != '不限') {
            if ($sszhuangtai == '有效(全部)' && $bendianbenzu && $bendianbenzu != $user['u_id']) {
                $row->andWhere(['a.zhuangtai' => '有效']);
            } else if ($sszhuangtai == '有效(全部)' && $bendianbenzu == $user['u_id']) { //判断是否是本人
                $u_id = $user['u_id'];
                $row->andWhere(['a.zhuangtai' => '有效']);
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");
            }
            if ($sszhuangtai == '无效') {
                $row->andWhere(['a.zhuangtai' => '无效']);
            }
            if ($sszhuangtai == '其他') {
                $row->andWhere(['a.zhuangtai' => '其他']);
            }
            if ($sszhuangtai == '成交') {
                $row->andWhere(['a.zhuangtai' => '成交']);
            }
            if ($sszhuangtai == '有效(公客)') {
                $row->andWhere(['a.zhuangtai' => '有效', 'a.customer_private' => '公客']);
            }
            if ($sszhuangtai == '有效(私客)' && $bendianbenzu && $bendianbenzu != $user['u_id']) {
                $row->andWhere(['a.zhuangtai' => '有效', 'a.customer_private' => '私客']);
            } else if ($sszhuangtai == '有效(私客)' && $bendianbenzu == $user['u_id']) { //判断是否是本人
                $row->andWhere(['a.zhuangtai' => '有效', 'a.c_id' => $user['u_id'], 'a.customer_private' => '私客']);
            }
        } else {
            //判断是本人显示所有类型
            if ((!$bendianbenzu or $bendianbenzu == $user['u_id'])) {
                $u_id = $user['u_id'];
                $row->andWhere("a.zhuangtai='有效' or a.zhuangtai='无效' or a.zhuangtai='其他'");
                $sjtime = time();
                //判断 封盘  私盘 公盘 的条件
                $row->andWhere("(a.customer_private = '私客' and a.c_id='$u_id') or (a.customer_private = '公客' and a.is_fengpan = '0') or (a.is_fengpan='1' and a.fengpan_user='$u_id' and unix_timestamp(a.fengpandaoqi)>'$sjtime')");
            }
        }

        if (!empty($param['xqqy'][0]) && $param['xqqy'][0] <> 'null') {
            $row->andWhere(['a.dts_id' => $param['xqqy'][0]]);
        }
        if (!empty($param['xqqy'][1]) && $param['xqqy'][1] <> 'null') {
            $row->andWhere(['a.rn_id' => $param['xqqy'][1]]);
        }
        if (!empty($param['xiaoqu'])) {
            $row->andWhere(['a.village' => $param['xiaoqu']]);
        }
        if (!empty($param['ssk'])) {
            $ssk = $param['ssk'];
            $row->leftJoin('zh_customer_tel as t', 'a.customer_uuid=t.customer_uuid');
            $row->andWhere("a.xuqiubianhao like '%$ssk%' or a.customer_name like '%$ssk%' or t.tel_phone like '%$ssk%' ");
        }
        //判断是否有部门搜索

        if (!empty($param['d_id'])) {
            $benzuin = !empty($benzuin) ? $benzuin : 0;
            $row->andWhere("a.u_dept_id in ($benzuin) ");
        } elseif (!empty($benzuin)) {
            $row->andWhere("a.u_dept_id in ($benzuin) or a.c_id is  null");
        }
        if (!empty($param['customer_type'])) {
            $row->andWhere(['a.customer_type' => $param['customer_type']]);
        } else {
            $row->andWhere(['a.customer_type' => 0]);
        }
        if (!empty($param['xuqiujiage_min']) or !empty($param['xuqiujiage_max'])) {
            $row->andWhere(['>=', 'a.xuqiujiage_min', intval($param['xuqiujiage_min'])]);
            $row->andWhere(['<=', 'a.xuqiujiage_max', intval($param['xuqiujiage_max'])]);
        }
        if (!empty($param['xuqiumianji_min']) or !empty($param['xuqiumianji_max'])) {
            $row->andWhere(['>=', 'a.xuqiumianji_min', intval($param['xuqiumianji_min'])]);
            $row->andWhere(['<=', 'a.xuqiumianji_max', intval($param['xuqiumianji_max'])]);
        }
        if (!empty($param['xuqiuhuxing_min']) or !empty($param['xuqiuhuxing_max'])) {
            $row->andWhere(['>=', 'a.xuqiuhuxing_min', intval($param['xuqiuhuxing_min'])]);
            $row->andWhere(['<=', 'a.xuqiuhuxing_max', intval($param['xuqiuhuxing_max'])]);
        }
        if (!empty($param['xuqiulouceng_min']) or !empty($param['xuqiulouceng_max'])) {
            $row->andWhere(['>=', 'a.xuqiulouceng_min', intval($param['xuqiulouceng_min'])]);
            $row->andWhere(['<=', 'a.xuqiulouceng_max', intval($param['xuqiulouceng_max'])]);
        }
        if (!empty($param['xuqiufangling_min']) or !empty($param['xuqiufangling_max'])) {
            $row->andWhere(['>=', 'a.xuqiufangling_min', intval($param['xuqiufangling_min'])]);
            $row->andWhere(['<=', 'a.xuqiufangling_max', intval($param['xuqiufangling_max'])]);
        }

        if (!empty($param['user'])) {
            $row->andWhere(['=', 'a.c_id', $param['user']]);
        }
        if (!empty($param['kehudengji'])) {
            $row->andWhere(['=', 'a.kehudengji', trim($param['kehudengji'])]);
        }
        if (!empty($param['butong'])) {
            $row->andWhere(['<>', 'a.customer_uuid', $param['butong']]);
        }
        if (!empty($param['zhutui'])) {
            $row->andWhere(['=', 'a.zhutui', $param['zhutui']]);
        }
        if (!empty($param['laiyuan'])) {
            $row->andWhere(['=', 'a.kehulaiyuan', $param['laiyuan']]);
        }

        if (!empty($param['shijian'])) {
            $row->andWhere(['>=', 'unix_timestamp(a.ctime)', strtotime($param['shijian'][0])]);
            $row->andWhere(['<=', 'unix_timestamp(a.ctime)', strtotime($param['shijian'][1])]);
        }

        if (!empty($param['ssxz'])) {
            foreach ($param['ssxz'] as $v) {
                if ($v == '急切') {
                    $row->andWhere(['like', 'a.duoxuanbiaoqian', '急切']);
                }
                if ($v == '学区') {
                    $row->andWhere(['like', 'a.duoxuanbiaoqian', '学区']);
                }
                if ($v == '意向金') {
                    $row->andWhere(['like', 'a.xiading', '意向金']);
                }
                if ($v == '有跟进') {
                    $row->andWhere(['>', 'a.genjincishu', '0']);
                }
                if ($v == '有带看') {
                    $row->andWhere(['>', 'a.daikancishu', '0']);
                }
                if ($v == '封盘') {
                    $row->andWhere(['=', 'a.is_fengpan', '1']);
                }
                if ($v == '7天未跟进') {
                    $sj = strtotime("-7 day");
                    if ($sszhuangtai === '有效(私客)') {
                        $row->andWhere(['<', 'unix_timestamp(a.weihurengenjin)', $sj]);
                    }
                    if ($sszhuangtai === '有效(公客)') {
                        $row->andWhere(['<', 'unix_timestamp(a.quanyuangenjin)', $sj]);
                    }
                }
                if ($v == '30天未带看') {
                    $sj = strtotime("-30 day");

                    $row->andWhere(['<', 'unix_timestamp(a.daikanshijian)', $sj]);
                }

            }
        }
        $row->with(
            ['tel' => function ($query) {
                $query->select(['customer_uuid', 'tel_phone', 'tel_type'])->where(['is_del' => 0, 'company_id' => $this->_user['company_id']]);
            }]
        );
        if (!empty($param['paixu'])) {
            switch ($param['paixu']) {
                case 1:
                    $row->orderBy('a.genjincishu ASC');
                    break;
                case 2:
                    $row->orderBy('a.genjincishu DESC');
                    break;
                case 3:
                    $row->orderBy('a.daikancishu ASC');
                    break;
                case 4:
                    $row->orderBy('a.daikancishu DESC');
                    break;
                case 5:
                    $row->orderBy('ctime ASC');
                    break;
                case 6:
                    $row->orderBy('ctime DESC');
                    break;

            }
        } else {
            $row->orderBy('a.utime DESC');
        }
        $list = $row->asArray()->all();
		

        foreach ($list as $key => $v) {
            $list[$key]['mianji'] = ($v['xuqiumianji_min'] ? $v['xuqiumianji_min'] : 0) . '-' . ($v['xuqiumianji_max'] ? $v['xuqiumianji_max'] : 0) . '平方米';
            if ($param['customer_type'] == 1) {
                $list[$key]['jiage'] = ($v['xuqiujiage_min'] ? $v['xuqiujiage_min'] : 0) . '-' . ($v['xuqiujiage_max'] ? $v['xuqiujiage_max'] : 0) . '元';
            } else {
                $list[$key]['jiage'] = ($v['xuqiujiage_min'] ? $v['xuqiujiage_min'] : 0) . '-' . ($v['xuqiujiage_max'] ? $v['xuqiujiage_max'] : 0) . '万';
            }
            $list[$key]['huxing'] = ($v['xuqiuhuxing_min'] ? $v['xuqiuhuxing_min'] : 0) . '-' . ($v['xuqiuhuxing_max'] ? $v['xuqiuhuxing_max'] : 0) . '室';
            if ($v['rn_name'] or $v['dts_name'] or $v['village_name']) {
                $list[$key]['xuqiuquyu'] = $v['dts_name'] . ';' . $v['rn_name'] . ';' . $v['village_name'];
            } else {
                $list[$key]['xuqiuquyu'] = '暂无';
            }
            if ($v['zhuangtai'] == '有效') {
                $list[$key]['zhuangtai'] = $v['customer_private'];
            }
            if ($v['customer_private'] == '私客') {
                $list[$key]['quanyuangenjin'] = '';
            }
            if ($v['daikanshijian'] == '0000-00-00 00:00:00') {
                $list[$key]['daikanshijian'] = '';
            }

            $customer_phone = Customer_tel::find()->andWhere(['customer_uuid' => $v['customer_uuid']])->one();
            $list[$key]['customer_phone'] = $customer_phone['tel_phone'];
            /*
             * 标签
             */
            if ($v['duoxuanbiaoqian']) {
                $duoxuanbiaoqian = explode(';', $v['duoxuanbiaoqian']);
                $list[$key]['biaoqian']['duoxuanbiaoqian'] = $duoxuanbiaoqian;
            }
            if ($v['xiading']) {
                $list[$key]['biaoqian']['xiading'] = $v['xiading'];
            }
            if ($v['is_fengpan']) {
                $list[$key]['biaoqian']['is_fengpan'] = 1;
            }
            $list[$key]['biaoqian']['genjincishu'] = $v['genjincishu'];
            $list[$key]['biaoqian']['daikancishu'] = $v['daikancishu'];
            if ($v['u_dept_id']) {
                $depart = Depart::find()->where(['d_id' => $v['u_dept_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                $list[$key]['bumen'] = $depart['d_name'];
            }
            if ($v['c_id']) {
                $user = User::find()->where(['u_id' => $v['c_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->one();
                $list[$key]['weihuren'] = $user['u_name'];
            }
        }
        $data['list'] = $list;
		//var_dump($list);
       /* $data['count'] = $row->count();
        //配置项
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_gongsike', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['customer_private'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhuangtai', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['zhuangtai'] = $row->asArray()->all();
        if ($param['customer_type'] == 1) {
            $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhulinduoxuan', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['duoxuanbiaoqian'] = $row->asArray()->all();
        } else {
            $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_duoxuanbiaoqian', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['duoxuanbiaoqian'] = $row->asArray()->all();
        }
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehuchenghu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['customer_sex'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_chaoxiang', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['chaoxiang'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_fangyuanleixing', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fangwuleixing'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_zhuangxiu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['zhuangxiu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_yongtu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['yongtu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehuchenghu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['kehuchenghu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dianhuasuoshu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['dianhuasuoshu'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_gongsike', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['gongsipan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_jiaotonggongju', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['jiaotonggongju'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_mingzu', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['mingzu'] = $row->asArray()->all();
		 $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dengji', 'company_id' => $this->_user['company_id']]);
		$data['peizhi']['kehudengji'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_guoji', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['guoji'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_xiaofeilinian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['xiaofeilinian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_kehulaiyuan', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['kehulaiyuan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_goutongjieduan', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['goutongjieduan'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_tuijianbiaoqian', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['tuijianbiaoqian'] = $row->asArray()->all();
        $row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_peitao', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['peitao'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'mjqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['xuqiumianji'] = $row->asArray()->all();
        if ($param['customer_type'] == 1) {
            $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'zuling_jigeqj', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['xuqiujiage'] = $row->asArray()->all();
        } else {
            $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'jgqj', 'company_id' => $this->_user['company_id']]);
            $data['peizhi']['xuqiujiage'] = $row->asArray()->all();
        }
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'hxqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['huxing'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'flqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['fangling'] = $row->asArray()->all();
        $row = ZhSettingQujian::find()->where(['qujian_shorthand' => 'lcqj', 'company_id' => $this->_user['company_id']]);
        $data['peizhi']['louceng'] = $row->asArray()->all();
        //客源等级
		$row = ZhSettingBase::find()->where(['base_shorthand' => 'keyuan_dengji', 'company_id' => $this->_user['company_id']]);
		$data['peizhi']['kydj'] = $row->asArray()->all();
        //$data['peizhi']['kydj'] = ['A级', 'B级', 'C级', 'D级'];	
        //获取片区小区树信息
        $data['peizhi']['villages'] = CommonController::getDtsList($this->_user['city_id'], $this->_user['company_id']);
        //员工数据
        $principal = User::find()->where(['is_del' => 0, 'company_id' => $this->_user['company_id']])->asArray()->all();
        foreach ($principal as $key => $item) {
            $data['peizhi']['users'][$item['u_dept_id']][] = $item;
        }
        //加载自定义列表
        $customcolumns = CustomColumns::find()->where(['u_id' => $this->_user['u_id'], 'is_del' => 0, 'company_id' => $this->_user['company_id']])->andWhere(['or', 'module=7', 'module=8', 'module=9'])->asArray()->all();
        if (!empty($customcolumns)) {
            foreach ($customcolumns as $key => $val) {
                $data['customcolumns'][$val['module']] = json_decode($val['columns'], true);
            }
        } else {
            $data['customcolumns'] = null;
        }
        //顶部按钮
        $data['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user);

        //必填项$param['customer_type']rsetting_options
        $row = ZhSettingRequired::find()->where(['rsetting_type' => 'customer_type' . $param['customer_type'], 'company_id' => $this->_user['company_id']]);
        $btx = $row->asArray()->one();

        $jgqj = ZhSettingQujian::find()->where(['qujian_shorthand' => 'jgqj', 'company_id' => $this->_user['company_id']])->select('qujian_desp')->asArray()->one();
        $data['peizhi']['jgqj'] = json_decode($jgqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['qujian_shorthand' => 'mjqj', 'company_id' => $this->_user['company_id']])->select('qujian_desp')->asArray()->one();
        $data['peizhi']['mjqj'] = json_decode($mjqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['qujian_shorthand' => 'zuling', 'company_id' => $this->_user['company_id']])->select('qujian_desp')->asArray()->one();
        $data['peizhi']['zlqj'] = json_decode($mjqj['qujian_desp'], true);

        $data['peizhi']['bitianxiang'] = $btx['rsetting_desp']; */
       // return ApiReturn::success('查询成功', $data);
		
		
		//var_dump($data['list']);die;
		
		 $n = 0;
		foreach ($list as $v) {
		    //报表头的输出
		    $objectPHPExcel->getActiveSheet()->mergeCells('A1:R1');
		    $objectPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $title);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $title);
		    $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
		    $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
		        ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '日期：' . date("Y年m月j日"));
		    $objectPHPExcel->setActiveSheetIndex(0)->getStyle('R2')
		        ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		    //表格头的输出
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '客源编号');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '状态');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '需求用途');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '客户姓名');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '客户来源');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '需求区域');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '面积');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '价格');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '户型');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '沟通阶段');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', '备注');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', '等级');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', '录入日期');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', '全员最后跟进');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', '最后带看时间');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', '维护人最后跟进');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', '维护人');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3', '部门');
		    $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
		
		    //设置居中
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
		        ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		    //设置边框
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
		        ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
		        ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
		        ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
		        ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')
		        ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		
		    //设置颜色
		    $objectPHPExcel->getActiveSheet()->getStyle('A3:R3')->getFill()
		        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
		
		    //明细的输出
		    $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($n + 4), $v['xuqiubianhao']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('B' . ($n + 4), $v['zhuangtai']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n + 4), $v['yongtu']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n + 4), $v['customer_name']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n + 4), $v['kehulaiyuan']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n + 4), $v['xuqiuquyu']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('G' . ($n + 4), $v['mianji']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('H' . ($n + 4), $v['jiage']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('I' . ($n + 4), $v['huxing']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('J' . ($n + 4), $v['goutongjieduan']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('K' . ($n + 4), $v['mark']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('L' . ($n + 4), $v['tiaojiandengji']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('M' . ($n + 4), $v['ctime']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('N' . ($n + 4), $v['quanyuangenjin']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('O' . ($n + 4), $v['daikanshijian']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('P' . ($n + 4), $v['weihurengenjin']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('Q' . ($n + 4), $v['weihuren']);
		    $objectPHPExcel->getActiveSheet()->setCellValue('R' . ($n + 4), $v['bumen']);
		    $n = $n + 1;
		}
		
		//设置分页显示
		$objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
		$objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
		ob_end_clean();
		ob_start();
		header("Content-type: text/html; charset=utf-8");
		header('Content-Type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename="ky-' . date("Y/m/j") . '.xls"');
		$objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
		
		$objWriter->save('php://output');

    }
	
}
