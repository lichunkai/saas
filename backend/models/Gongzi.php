<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\GongziGii;
use backend\models\User;
use backend\models\OrderSellCommission;
use backend\models\OrderSellDivide;
use backend\models\Salary_config_mingcheng_yeji;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Gongzi extends GongziGii
{
    private static $user;
    private static $_tokens;
    public function init(){
        parent::init();
        $headeToken =  Yii::$app->request->headers->get('X-Access-Token');
        $getToken =  Yii::$app->request->get('token');
        $postToken =  Yii::$app->request->get('token');
        if($headeToken&&!empty($headeToken)){
            self::$_tokens=$headeToken;
        }elseif($getToken&&!empty($getToken)){
            self::$_tokens=$getToken;
        }elseif($postToken&&!empty($postToken)){
            self::$_tokens=$postToken;
        }
        self::$user = json_decode(Yii::$app->redis->get(self::$_tokens),'true');
    }
    //把树 变成一维数组带名字
    public function setlistname($arr, $parentid = 0)
    {
        $array = array();
        if (!empty($arr)) {
            foreach ($arr as $val) {
                $indata = array("value" => $val["value"], "label" => $val["label"], "d_pid" => $val["d_pid"]);
                $array[] = $indata;
                if (isset($val["children"])) {
                    $children = $this->setlistname($val["children"], $val["d_pid"]);
                    if ($children) {
                        $array = array_merge($array, $children);
                    }
                }
            }
        }


        return $array;
    }

    //数组 树化
    public function getTree($data, $pId)
    {
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v['d_pid'] == $pId) {         //父亲找到儿子

                $v['children'] = $this->getTree($data, $v['value']);
                $tree[] = $v;
                //unset($data[$k]);
            }
        }
        return $tree;
    }

    //工资重算
    public function rsEdit($param, $user)
    {
        $kaishiriqi = strtotime($param['kaishiriqi']);
        $jieshuriqi = $param['jieshuriqi'];
        $jieshuriqi = strtotime("$jieshuriqi+ 1 day");
        $v = User::find()->where(['u_id' => $param['u_id']])->asArray()->one();

        //奖励罚款
        $jiangli = Salary_config_biandong::find()->select('sum(jine) as jine')->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'zhengjianleixing' => '奖', 'renyuan' => $v['u_id']]);
        $jiangli = $jiangli->andWhere("unix_timestamp(biandongriqi)>=$kaishiriqi and unix_timestamp(biandongriqi)<$jieshuriqi")->asArray()->one();
        $fakuan = Salary_config_biandong::find()->select('sum(jine) as jine')->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'zhengjianleixing' => '罚', 'renyuan' => $v['u_id']]);
        $fakuan = $fakuan->andWhere("unix_timestamp(biandongriqi)>=$kaishiriqi and unix_timestamp(biandongriqi)<$jieshuriqi")->asArray()->one();

        //大区业绩
        $daqu_osd = '';
        //区经业绩 管理店
        $qu_osd = '';
        //直营店
        $zy_osd = '';
        //负责人业绩查看
        $fz_yejifangan = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['fuzerenticheng_id']])->asArray()->one();
        $fz_yeji = json_decode($fz_yejifangan['yeji']);
        //大区业绩统计
        $daqu_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
            ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
            ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
        $daqu_osd->andWhere(['<>', 'oc.user_id', $v['u_id']]);
        $daqu_osd->andWhere(['oc.tc_dqfzr' => $v['u_id']]);
        $daqu_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
        $daqu_osd = $daqu_osd->asArray()->one();

        //区经业绩 管理店
        $qu_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
            ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
            ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
        //店负责人不等于他自己，区等于他 就是管理店
        $qu_osd->andWhere(['<>', 'oc.tc_dfzr', $v['u_id']]);
        $qu_osd->andWhere(['<>', 'oc.user_id', $v['u_id']]);
        $qu_osd->andWhere(['oc.tc_qfzr' => $v['u_id']]);
        $qu_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
        $qu_osd = $qu_osd->asArray()->one();

        //直营店
        $zy_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
            ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
            ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
        //店负责人等于他自己就是直营店
        $zy_osd->andWhere(['oc.tc_dfzr' => $v['u_id']]);
        $zy_osd->andWhere(['<>', 'oc.user_id', $v['u_id']]);
        $zy_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
        $zy_osd = $zy_osd->asArray()->one();

        //  个人业绩 计算
        $gr_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
            ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
            ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
        $gr_osd->andWhere(['oc.user_id' => $v['u_id']]);
        $gr_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
        $gr_osd = $gr_osd->asArray()->one();
        // 个人业绩分配方案
        $gr_fangan_d = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['ticheng_id']])->asArray()->one();
        $gr_fangan = json_decode($gr_fangan_d['yeji']);

        // 管理店分配方案
        $gld_fangan_d = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['fuzerenticheng_id']])->asArray()->one();
        $gld_fangan = json_decode($gld_fangan_d['yeji']);
        // 直营店分配方案
        $zyd_fangan_d = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['fuzerenticheng_zyid']])->asArray()->one();
        $zyd_fangan = json_decode($zyd_fangan_d['yeji']);
        //直营店提成方案
        $zyd_zongticheng = 0;
        //直营店计算过程
        $zyd_yjjs = '';
        if (!empty($zyd_fangan)) {
            if ($zy_osd['yeji']) {
                foreach ($zyd_fangan as $key => $b) {
                    //提成金额标准
                    if ($zy_osd['yeji'] > $b->name && $b->name > 0) {
                        //如果业绩大于当前的标准金额，
                        if ($key < (count($zyd_fangan) - 1)) {
                            //如果是第一个则满算
                            if ($key < 1) {
                                $zyd_yjjs .= $b->name . '*' . $b->fencheng . '%+';
                                $zyd_zongticheng += $b->name * ($b->fencheng / 100);
                            } else {//如果是第一个以上则减去 上一个
                                $zyd_yjjs .= ($b->name - $zyd_fangan[$key - 1]->name) . '*' . $b->fencheng . '%+';
                                $zyd_zongticheng += ($b->name - $zyd_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            }

                        } else {
                            $zyd_yjjs .= ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                            $zyd_zongticheng += ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            break;
                        }
                    } else {
                        if ($key < 1) {
                            $zyd_yjjs .= $zy_osd['yeji'] . '*' . $b->fencheng . '%';
                            $zyd_zongticheng += $zy_osd['yeji'] * ($b->fencheng / 100);
                            break;
                        } else {
                            $zyd_yjjs .= ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                            $zyd_zongticheng += ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            break;
                        }
                    }
                }
            }
        }
        if (!empty($qu_osd['yeji']) && !empty($daqu_osd['yeji'])) {
            $gld_osd['yeji'] = $qu_osd['yeji'] + $daqu_osd['yeji'];
        } else {
            $gld_osd['yeji'] = !empty($qu_osd['yeji']) ? $qu_osd['yeji'] : ($daqu_osd['yeji'] ? $daqu_osd['yeji'] : 0);
        }
        if($zyd_yjjs){
            $zyd_yjjs ='('.$zyd_yjjs.')*0.9';
        }
        $zyd_zongticheng=$zyd_zongticheng*0.9;
        //管理店提成方案
        $gld_zongticheng = 0;
        //管理店计算过程
        $gld_yjjs = '';
        if (!empty($gld_fangan)) {
            if ($gld_osd['yeji']) {
                foreach ($gld_fangan as $key => $b) {
                    //提成金额标准
                    if ($gld_osd['yeji'] > $b->name && $b->name > 0) {
                        //如果业绩大于当前的标准金额，
                        if ($key < (count($gld_fangan) - 1)) {
                            //如果是第一个则满算
                            if ($key < 1) {
                                $gld_yjjs .= $b->name . '*' . $b->fencheng . '%+';
                                $gld_zongticheng += $b->name * ($b->fencheng / 100);
                            } else {//如果是第一个以上则减去 上一个
                                $gld_yjjs .= ($b->name - $gld_fangan[$key - 1]->name) . '*' . $b->fencheng . '%+';
                                $gld_zongticheng += ($b->name - $gld_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            }

                        } else {
                            $gld_yjjs .= ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                            $gld_zongticheng += ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            break;
                        }
                    } else {
                        if ($key < 1) {
                            $gld_yjjs .= $gld_osd['yeji'] . '*' . $b->fencheng . '%';
                            $gld_zongticheng += $gld_osd['yeji'] * ($b->fencheng / 100);
                            break;
                        } else {
                            $gld_yjjs .= ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                            $gld_zongticheng += ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            break;
                        }
                    }
                }
            }
        }
        if($gld_yjjs){
            $gld_yjjs ='('.$gld_yjjs.')*0.9';
        }
        $gld_zongticheng=$gld_zongticheng*0.9;

        //个人提成方案
        $gr_zongticheng = 0;
        //个人计算过程
        $gr_yjjs = '';
        if (!empty($gr_fangan)) {
            if ($gr_osd['yeji']) {
                foreach ($gr_fangan as $key => $b) {
                    //提成金额标准
                    if ($gr_osd['yeji'] > $b->name && $b->name > 0) {
                        //如果业绩大于当前的标准金额，
                        if ($key < (count($gr_fangan) - 1)) {
                            //如果是第一个则满算
                            if ($key < 1) {
                                $gr_yjjs .= $b->name . '*' . $b->fencheng . '%+';
                                $gr_zongticheng += $b->name * ($b->fencheng / 100);
                            } else {//如果是第一个以上则减去 上一个
                                $gr_yjjs .= ($b->name - $gr_fangan[$key - 1]->name) . '*' . $b->fencheng . '%+';
                                $gr_zongticheng += ($b->name - $gr_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            }

                        } else {
                            $gr_yjjs .= ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                            $gr_zongticheng += ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            break;
                        }
                    } else {
                        if ($key < 1) {
                            $gr_yjjs .= $gr_osd['yeji'] . '*' . $b->fencheng . '%';
                            $gr_zongticheng += $gr_osd['yeji'] * ($b->fencheng / 100);
                            break;
                        } else {
                            $gr_yjjs .= ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                            $gr_zongticheng += ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) * ($b->fencheng / 100);
                            break;
                        }
                    }
                }
            }
        }
        if($gr_yjjs){
            $gr_yjjs ='('.$gr_yjjs.')*0.9';
        }
        $gr_zongticheng=$gr_zongticheng*0.9;
        //查看本月是否已经生成工资了，如果生成过了，就更新，否则就创建
        $model = Gongzi::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'gongziriqi' => date('Y-m', $kaishiriqi), 'user_id' => $v['u_id']])->one();

        if (!empty($model)) {
            $jiangli = !empty($jiangli['jine']) ? $jiangli['jine'] : 0;
            $fakuan = !empty($fakuan['jine']) ? $fakuan['jine'] : 0;
            $model->jiangli = $jiangli;
            $model->fakuan = $fakuan;
            if (!empty($benzuin)) {
                $model->benzuin = $benzuin;
            }

            //管理店
            if (!empty($gld_fangan_d)) {
                $model->gld_tichengfangan = $gld_fangan_d['yejimingcheng'];
                $model->gld_tichengfangan_json = $gld_fangan_d['yeji'];
                $model->gld_scmy_id = $gld_fangan_d['scmy_id'];
                $model->gld_scm_id = $gld_fangan_d['scm_id'];

            }
            $model->gld_zongyeji = !empty($gld_osd['yeji']) ? $gld_osd['yeji'] : 0;
            $model->gld_tichengjine = !empty($gld_zongticheng) ? $gld_zongticheng : 0;
            $model->gld_yjjs = $gld_yjjs;
            //个人
            if (!empty($gr_fangan_d)) {
                $model->gr_scmy_id = $gr_fangan_d['scmy_id'];
                $model->gr_scm_id = $gr_fangan_d['scm_id'];
                $model->gr_tichengfangan = $gr_fangan_d['yejimingcheng'];
                $model->gr_tichengfangan_json = $gr_fangan_d['yeji'];
            }
            $model->gr_zongyeji = !empty($gr_osd['yeji']) ? $gr_osd['yeji'] : 0;
            $model->gr_tichengjine = !empty($gr_zongticheng) ? $gr_zongticheng : 0;
            $model->gr_yjjs = $gr_yjjs;
            //直营店
            if (!empty($zyd_fangan_d)) {
                $model->zyd_scmy_id = $zyd_fangan_d['scmy_id'];
                $model->zyd_scm_id = $zyd_fangan_d['scm_id'];
                $model->zyd_tichengfangan = $zyd_fangan_d['yejimingcheng'];
                $model->zyd_tichengfangan_json = $zyd_fangan_d['yeji'];

            }
            $model->zyd_zongyeji = !empty($zy_osd['yeji']) ? $zy_osd['yeji'] : 0;
            $model->zyd_tichengjine = !empty($zyd_zongticheng) ? $zyd_zongticheng : 0;
            $model->zyd_yjjs = $zyd_yjjs;
            //基本工资
            $jibengongzi = isset($v['jibengongzi']) ? $v['jibengongzi'] : 0;
            $wuxiangeren = isset($v['wuxiangeren']) ? $v['wuxiangeren'] : 0;
            $wuxianyijingeren = isset($v['wuxianyijingeren']) ? $v['wuxianyijingeren'] : 0;
            $shebao = !empty($wuxiangeren) ? $wuxiangeren : $wuxianyijingeren;
            $model->jibengongzi = $jibengongzi;
            $model->wuxiangeren = $wuxiangeren;
            $model->wuxianyijingeren = $wuxianyijingeren;
            $model->gongziriqi = date('Y-m', $kaishiriqi);
            $model->kaishiriqi = $param['kaishiriqi'];

            $model->jieshuriqi = $param['jieshuriqi'];
            $model->user_id = $v['u_id'];
            $model->hejigongzi = $zyd_zongticheng + $gld_zongticheng + $gr_zongticheng + $jibengongzi - $shebao + $jiangli - $fakuan;
            $model->utime = date('Y-m-d H:i:s');
            $model->u_id = $user['u_id'];
            if ($model->save() == false) {
                return false;
            } else {
                return true;
            }
        }

    }

    public function updateGongzi($param, $user)
    {
        $kaishiriqi = strtotime($param['kaishiriqi']);
        $jieshuriqi = $param['jieshuriqi'];
        $jieshuriqi = strtotime("$jieshuriqi+ 1 day");
        $row = User::find()->alias('a')->select('a.*,b.d_name,c.dts_name')->where(['a.is_del' => 0,'a.company_id'=>self::$user['company_id']])->leftJoin('zh_depart b', 'a.u_dept_id=b.d_id')->leftJoin('zh_district_slice c', 'b.d_district=c.dts_id and c.is_del=0')->with(['role' => function ($query) {
            $query->select(['role_id', 'role_name']);
        }]);
        $users = $row->orderBy('a.ctime DESC')->asArray()->all();
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            foreach ($users as $v) {
                //奖励罚款
                $jiangli = Salary_config_biandong::find()->select('sum(jine) as jine')->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'zhengjianleixing' => '奖', 'renyuan' => $v['u_id']]);
                $jiangli = $jiangli->andWhere("unix_timestamp(biandongriqi)>=$kaishiriqi and unix_timestamp(biandongriqi)<$jieshuriqi")->asArray()->one();
                $fakuan = Salary_config_biandong::find()->select('sum(jine) as jine')->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'zhengjianleixing' => '罚', 'renyuan' => $v['u_id']]);
                $fakuan = $fakuan->andWhere("unix_timestamp(biandongriqi)>=$kaishiriqi and unix_timestamp(biandongriqi)<$jieshuriqi")->asArray()->one();

                //大区业绩
                $daqu_osd = '';
                //区经业绩 管理店
                $qu_osd = '';
                //直营店
                $zy_osd = '';
                //负责人业绩查看
                $fz_yejifangan = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['fuzerenticheng_id']])->asArray()->one();
                $fz_yeji = json_decode($fz_yejifangan['yeji']);
                //大区业绩统计
                $daqu_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
                    ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
                    ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
                $daqu_osd->andWhere(['<>', 'oc.user_id', $v['u_id']]);
                $daqu_osd->andWhere(['oc.tc_dqfzr' => $v['u_id']]);
                $daqu_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
                $daqu_osd = $daqu_osd->asArray()->one();

                //区经业绩 管理店
                $qu_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
                    ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
                    ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
                //店负责人不等于他自己，区等于他 就是管理店
                $qu_osd->andWhere(['<>', 'oc.tc_dfzr', $v['u_id']]);
                $qu_osd->andWhere(['<>', 'oc.user_id', $v['u_id']]);
                $qu_osd->andWhere(['oc.tc_qfzr' => $v['u_id']]);
                $qu_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
                $qu_osd = $qu_osd->asArray()->one();

                //直营店
                $zy_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
                    ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
                    ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
                //店负责人等于他自己就是直营店
                $zy_osd->andWhere(['oc.tc_dfzr' => $v['u_id']]);
                $zy_osd->andWhere(['<>', 'oc.user_id', $v['u_id']]);
                $zy_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
                $zy_osd = $zy_osd->asArray()->one();

                //  个人业绩 计算
                $gr_osd = OrderSellDivide::find()->alias('d')->select('sum(d.divide_money) as yeji')
                    ->leftJoin('zh_order_sell_commission as oc', 'd.commission_id=oc.oc_id')
                    ->where(['oc.is_del' => 0,'oc.company_id'=>self::$user['company_id']]);
                $gr_osd->andWhere(['oc.user_id' => $v['u_id']]);
                $gr_osd->andWhere("unix_timestamp(d.ctime)>=$kaishiriqi and unix_timestamp(d.ctime)<$jieshuriqi");
                $gr_osd = $gr_osd->asArray()->one();
                // 个人业绩分配方案
                $gr_fangan_d = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['ticheng_id']])->asArray()->one();
                $gr_fangan = json_decode($gr_fangan_d['yeji']);

                // 管理店分配方案
                $gld_fangan_d = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['fuzerenticheng_id']])->asArray()->one();
                $gld_fangan = json_decode($gld_fangan_d['yeji']);
                // 直营店分配方案
                $zyd_fangan_d = Salary_config_mingcheng_yeji::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'scmy_id' => $v['fuzerenticheng_zyid']])->asArray()->one();
                $zyd_fangan = json_decode($zyd_fangan_d['yeji']);
                //直营店提成方案
                $zyd_zongticheng = 0;
                //直营店计算过程
                $zyd_yjjs = '';
                if (!empty($zyd_fangan)) {
                    if ($zy_osd['yeji']) {
                        foreach ($zyd_fangan as $key => $b) {
                            //提成金额标准
                            if ($zy_osd['yeji'] > $b->name && $b->name > 0) {
                                //如果业绩大于当前的标准金额，
                                if ($key < (count($zyd_fangan) - 1)) {
                                    //如果是第一个则满算
                                    if ($key < 1) {
                                        $zyd_yjjs .= $b->name . '*' . $b->fencheng . '%+';
                                        $zyd_zongticheng += $b->name * ($b->fencheng / 100);
                                    } else {//如果是第一个以上则减去 上一个
                                        $zyd_yjjs .= ($b->name - $zyd_fangan[$key - 1]->name) . '*' . $b->fencheng . '%+';
                                        $zyd_zongticheng += ($b->name - $zyd_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    }

                                } else {
                                    $zyd_yjjs .= ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                                    $zyd_zongticheng += ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    break;
                                }
                            } else {
                                if ($key < 1) {
                                    $zyd_yjjs .= $zy_osd['yeji'] . '*' . $b->fencheng . '%';
                                    $zyd_zongticheng += $zy_osd['yeji'] * ($b->fencheng / 100);
                                    break;
                                } else {
                                    $zyd_yjjs .= ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                                    $zyd_zongticheng += ($zy_osd['yeji'] - $zyd_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    break;
                                }
                            }
                        }
                    }
                }
                if($zyd_yjjs){
                    $zyd_yjjs ='('.$zyd_yjjs.')*0.9';
                }
                $zyd_zongticheng=$zyd_zongticheng*0.9;
                if (!empty($qu_osd['yeji']) && !empty($daqu_osd['yeji'])) {
                    $gld_osd['yeji'] = $qu_osd['yeji'] + $daqu_osd['yeji'];
                } else {
                    $gld_osd['yeji'] = !empty($qu_osd['yeji']) ? $qu_osd['yeji'] : ($daqu_osd['yeji'] ? $daqu_osd['yeji'] : 0);
                }

                //管理店提成方案
                $gld_zongticheng = 0;
                //管理店计算过程
                $gld_yjjs = '';
                if (!empty($gld_fangan)) {
                    if ($gld_osd['yeji']) {
                        foreach ($gld_fangan as $key => $b) {
                            //提成金额标准
                            if ($gld_osd['yeji'] > $b->name && $b->name > 0) {
                                //如果业绩大于当前的标准金额，
                                if ($key < (count($gld_fangan) - 1)) {
                                    //如果是第一个则满算
                                    if ($key < 1) {
                                        $gld_yjjs .= $b->name . '*' . $b->fencheng . '%+';
                                        $gld_zongticheng += $b->name * ($b->fencheng / 100);
                                    } else {//如果是第一个以上则减去 上一个
                                        $gld_yjjs .= ($b->name - $gld_fangan[$key - 1]->name) . '*' . $b->fencheng . '%+';
                                        $gld_zongticheng += ($b->name - $gld_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    }

                                } else {
                                    $gld_yjjs .= ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                                    $gld_zongticheng += ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    break;
                                }
                            } else {
                                if ($key < 1) {
                                    $gld_yjjs .= $gld_osd['yeji'] . '*' . $b->fencheng . '%';
                                    $gld_zongticheng += $gld_osd['yeji'] * ($b->fencheng / 100);
                                    break;
                                } else {
                                    $gld_yjjs .= ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                                    $gld_zongticheng += ($gld_osd['yeji'] - $gld_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    break;
                                }
                            }
                        }
                    }
                }
                if($gld_yjjs){
                    $gld_yjjs ='('.$gld_yjjs.')*0.9';
                }
                $gld_zongticheng=$gld_zongticheng*0.9;

                //个人提成方案
                $gr_zongticheng = 0;
                //个人计算过程
                $gr_yjjs = '';
                if (!empty($gr_fangan)) {
                    if ($gr_osd['yeji']) {
                        foreach ($gr_fangan as $key => $b) {
                            //提成金额标准
                            if ($gr_osd['yeji'] > $b->name && $b->name > 0) {
                                //如果业绩大于当前的标准金额，
                                if ($key < (count($gr_fangan) - 1)) {
                                    //如果是第一个则满算
                                    if ($key < 1) {
                                        $gr_yjjs .= $b->name . '*' . $b->fencheng . '%+';
                                        $gr_zongticheng += $b->name * ($b->fencheng / 100);
                                    } else {//如果是第一个以上则减去 上一个
                                        $gr_yjjs .= ($b->name - $gr_fangan[$key - 1]->name) . '*' . $b->fencheng . '%+';
                                        $gr_zongticheng += ($b->name - $gr_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                    }

                                } else {
                                    $gr_yjjs .= ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                                    $gr_zongticheng += ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                }
                            } else {
                                if ($key < 1) {
                                    $gr_yjjs .= $gr_osd['yeji'] . '*' . $b->fencheng . '%';
                                    $gr_zongticheng += $gr_osd['yeji'] * ($b->fencheng / 100);
                                } else {
                                    $gr_yjjs .= ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) . '*' . $b->fencheng . '%';
                                    $gr_zongticheng += ($gr_osd['yeji'] - $gr_fangan[$key - 1]->name) * ($b->fencheng / 100);
                                }
                            }
                        }
                    }
                }
                if($gr_yjjs){
                    $gr_yjjs ='('.$gr_yjjs.')*0.9';
                }
                $gr_zongticheng=$gr_zongticheng*0.9;
                //查看本月是否已经生成工资了，如果生成过了，就更新，否则就创建


                $model = Gongzi::find()->where(['is_del'=>0,'company_id'=>self::$user['company_id'], 'gongziriqi' => date('Y-m', $kaishiriqi), 'user_id' => $v['u_id']])->one();
                if (!empty($model)) {
                    $jiangli = !empty($jiangli['jine']) ? $jiangli['jine'] : 0;
                    $fakuan = !empty($fakuan['jine']) ? $fakuan['jine'] : 0;
                    $model->jiangli = $jiangli;
                    $model->fakuan = $fakuan;
                    if (!empty($benzuin)) {
                        $model->benzuin = $benzuin;
                    }

                    //管理店
                    if (!empty($gld_fangan_d)) {
                        $model->gld_tichengfangan = $gld_fangan_d['yejimingcheng'];
                        $model->gld_tichengfangan_json = $gld_fangan_d['yeji'];
                        $model->gld_scmy_id = $gld_fangan_d['scmy_id'];
                        $model->gld_scm_id = $gld_fangan_d['scm_id'];

                    }
                    $model->gld_zongyeji = !empty($gld_osd['yeji']) ? $gld_osd['yeji'] : 0;
                    $model->gld_tichengjine = !empty($gld_zongticheng) ? $gld_zongticheng : 0;
                    $model->gld_yjjs = $gld_yjjs;
                    //个人
                    if (!empty($gr_fangan_d)) {
                        $model->gr_scmy_id = $gr_fangan_d['scmy_id'];
                        $model->gr_scm_id = $gr_fangan_d['scm_id'];
                        $model->gr_tichengfangan = $gr_fangan_d['yejimingcheng'];
                        $model->gr_tichengfangan_json = $gr_fangan_d['yeji'];
                    }
                    $model->gr_zongyeji = !empty($gr_osd['yeji']) ? $gr_osd['yeji'] : 0;
                    $model->gr_tichengjine = !empty($gr_zongticheng) ? $gr_zongticheng : 0;
                    $model->gr_yjjs = $gr_yjjs;
                    //直营店
                    if (!empty($zyd_fangan_d)) {
                        $model->zyd_scmy_id = $zyd_fangan_d['scmy_id'];
                        $model->zyd_scm_id = $zyd_fangan_d['scm_id'];
                        $model->zyd_tichengfangan = $zyd_fangan_d['yejimingcheng'];
                        $model->zyd_tichengfangan_json = $zyd_fangan_d['yeji'];

                    }
                    $model->zyd_zongyeji = !empty($zy_osd['yeji']) ? $zy_osd['yeji'] : 0;
                    $model->zyd_tichengjine = !empty($zyd_zongticheng) ? $zyd_zongticheng : 0;
                    $model->zyd_yjjs = $zyd_yjjs;
                    //基本工资
                    $jibengongzi = isset($v['jibengongzi']) ? $v['jibengongzi'] : 0;
                    $wuxiangeren = isset($v['wuxiangeren']) ? $v['wuxiangeren'] : 0;
                    $wuxianyijingeren = isset($v['wuxianyijingeren']) ? $v['wuxianyijingeren'] : 0;
                    $shebao = !empty($wuxiangeren) ? $wuxiangeren : $wuxianyijingeren;
                    $model->jibengongzi = $jibengongzi;
                    $model->wuxiangeren = $wuxiangeren;
                    $model->wuxianyijingeren = $wuxianyijingeren;
                    $model->gongziriqi = date('Y-m', $kaishiriqi);
                    $model->kaishiriqi = $param['kaishiriqi'];

                    $model->jieshuriqi = $param['jieshuriqi'];
                    $model->user_id = $v['u_id'];
                    $model->hejigongzi = $zyd_zongticheng + $gld_zongticheng + $gr_zongticheng + $jibengongzi - $shebao + $jiangli - $fakuan;
                    $model->utime = date('Y-m-d H:i:s');
                    $model->u_id = $user['u_id'];
                    if ($model->save() == false) {
                        $transaction->rollBack();
                        return false;
                    }
                } else {
                    $model = new Gongzi();
                    $jiangli = !empty($jiangli['jine']) ? $jiangli['jine'] : 0;
                    $fakuan = !empty($fakuan['jine']) ? $fakuan['jine'] : 0;
                    $model->jiangli = $jiangli;
                    $model->fakuan = $fakuan;
                    if (!empty($benzuin)) {
                        $model->benzuin = $benzuin;
                    }

                    //管理店
                    if (!empty($gld_fangan_d)) {
                        $model->gld_tichengfangan = $gld_fangan_d['yejimingcheng'];
                        $model->gld_tichengfangan_json = $gld_fangan_d['yeji'];
                        $model->gld_scmy_id = $gld_fangan_d['scmy_id'];
                        $model->gld_scm_id = $gld_fangan_d['scm_id'];

                    }
                    $model->gld_zongyeji = !empty($gld_osd['yeji']) ? $gld_osd['yeji'] : 0;
                    $model->gld_tichengjine = !empty($gld_zongticheng) ? $gld_zongticheng : 0;
                    $model->gld_yjjs = $gld_yjjs;
                    //个人
                    if (!empty($gr_fangan_d)) {
                        $model->gr_scmy_id = $gr_fangan_d['scmy_id'];
                        $model->gr_scm_id = $gr_fangan_d['scm_id'];
                        $model->gr_tichengfangan = $gr_fangan_d['yejimingcheng'];
                        $model->gr_tichengfangan_json = $gr_fangan_d['yeji'];
                    }
                    $model->gr_zongyeji = !empty($gr_osd['yeji']) ? $gr_osd['yeji'] : 0;
                    $model->gr_tichengjine = !empty($gr_zongticheng) ? $gr_zongticheng : 0;
                    $model->gr_yjjs = $gr_yjjs;
                    //直营店
                    if (!empty($zyd_fangan_d)) {
                        $model->zyd_scmy_id = $zyd_fangan_d['scmy_id'];
                        $model->zyd_scm_id = $zyd_fangan_d['scm_id'];
                        $model->zyd_tichengfangan = $zyd_fangan_d['yejimingcheng'];
                        $model->zyd_tichengfangan_json = $zyd_fangan_d['yeji'];

                    }
                    $model->zyd_zongyeji = !empty($zy_osd['yeji']) ? $zy_osd['yeji'] : 0;
                    $model->zyd_tichengjine = !empty($zyd_zongticheng) ? $zyd_zongticheng : 0;
                    $model->zyd_yjjs = $zyd_yjjs;
                    //基本工资
                    $jibengongzi = isset($v['jibengongzi']) ? $v['jibengongzi'] : 0;
                    $wuxiangeren = isset($v['wuxiangeren']) ? $v['wuxiangeren'] : 0;
                    $wuxianyijingeren = isset($v['wuxianyijingeren']) ? $v['wuxianyijingeren'] : 0;
                    $shebao = !empty($wuxiangeren) ? $wuxiangeren : $wuxianyijingeren;
                    $model->jibengongzi = $jibengongzi;
                    $model->wuxiangeren = $wuxiangeren;
                    $model->wuxianyijingeren = $wuxianyijingeren;
                    $model->gongziriqi = date('Y-m', $kaishiriqi);
                    $model->kaishiriqi = $param['kaishiriqi'];

                    $model->jieshuriqi = $param['jieshuriqi'];
                    $model->user_id = $v['u_id'];
                    $model->hejigongzi = $zyd_zongticheng + $gld_zongticheng + $gr_zongticheng + $jibengongzi - $shebao + $jiangli - $fakuan;
                    $model->auth_cid = $user['auth_cid'];
                    $model->company_id = self::$user['company_id'];
                    $model->auth_rid = $user['auth_rid'];
                    $model->auth_sid = $user['auth_sid'];
                    $model->auth_aid = $user['auth_aid'];
                    $model->auth_baid = $user['auth_baid'];
                    $model->ctime = date('Y-m-d H:i:s');
                    $model->utime = date('Y-m-d H:i:s');
                    $model->c_id = $user['u_id'];
                    $model->u_id = $user['u_id'];
                    if ($model->save() == false) {
                        $transaction->rollBack();
                        return false;
                    }

                }


            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

}
