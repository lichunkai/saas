<?php

namespace backend\controllers;


use backend\models\CustomColumns;

use backend\models\Depart;
use backend\models\House;
use backend\models\HouseFollowup;
use backend\models\HouseImg;
use backend\models\Notify;

use backend\models\HouseDescribe;
use backend\models\School;
use backend\models\School_district;
use backend\models\User;
use backend\models\Verify;
use backend\models\ZhSettingBase;
use backend\models\ZhSettingJuece;
use backend\models\ZhSettingQujian;
use backend\models\ZhSettingRequired;
use backend\models\ZhSettingTransfer;
use common\helps\Tools;
use common\models\gii\ComDistrictGii;
use common\models\gii\ComVillageGii;
use common\models\gii\HouseKeyGii;
use common\models\gii\HouseLogGii;
use common\models\gii\HousePhoneGii;
use common\models\gii\HouseUserGii;
use common\models\gii\HouseWeituoGii;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Common controller
 */
class HouseController extends AuthController
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
     * 获取设置
     *
     */
    public function actionGetsetting()
    {
        $param = Yii::$app->request->get();
        //获取小区列表
        $list = [
            'fangyuan_xingmingbeizhu' => 'customer_sex',
            'fangyuan_dianhuabeizhu' => 'customer_type',
            'fangyuan_chaoxiang' => 'chaoxiang',
            'fangyuan_zhuangxiu' => 'zhuangxiu',
            'fangyuan_xianzhuang' => 'xianzhuang',
            'fangyuan-fangwuleixin' => 'fangwuleixing',
            'fangyuan_jianzhujiegou' => 'jianzhujiegou',
            'fangyuan-chanquanxinzhi' => 'chanquanxingzhi',
            'fangyuan_chanquanninaxian' => 'chanquannianxian',
            'fangyuan-fangyuanshuifei' => 'fangyuanshuifei',
            'fangyuan-tuijianbiaoqian' => 'house_tuijian_tag',
            'fangyuan-kanfangfangshi' => 'kanfangfangshi',
            'fangyuan-laiyuan' => 'laiyuan',
            'chushoufangyuanfukuanfangshi' => 'fukuanfangshi',
            'fangyuan-tag' => 'chushoutag',
            'fangyuangengjinfangshi' => 'gengjinfangshi',
            'fangyuan-peitao' => 'peitao',
            'yaoshi_yaoshishixiaoxuanyin' => 'shixiaoyuanyin',
            'zufangbiaoqian' => 'zufangtag',
            'fangyuanchexiaoyuanyin' => 'fangyuanchexiaoyuanyin',
        ];
        $data = [];
        foreach ($list as $key => $item) {
            $data[$item] = ZhSettingBase::getBaseSettings($key, $this->_user['company_id']);
        }

        //所有部门数据
        $depart = Depart::find()->where(['company_id' => $this->_user['company_id'], 'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->orderBy('d_sort DESC')->asArray()->all();
        $data['alldepartlist'] = Tools::listToTree($depart, 'value', 'd_pid', 'children');
        $data['process'] = ZhSettingTransfer::find()->select('transfer_id,transfer_name')->asArray()->all();

        $data['fydj'] = ['A级', 'B级', 'C级', 'D级'];
        //获取片区小区树信息
        $data['dts'] = CommonController::getDtsList($this->_user['city_id'], $this->_user['company_id']);

        //搜索区间参数
        $jgqj = ZhSettingQujian::find()->where(['company_id' => $this->_user['company_id'], 'qujian_shorthand' => 'jgqj'])->select('qujian_desp')->asArray()->one();
        $data['jgqj'] = json_decode($jgqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id' => $this->_user['company_id'], 'qujian_shorthand' => 'mjqj'])->select('qujian_desp')->asArray()->one();
        $data['mjqj'] = json_decode($mjqj['qujian_desp'], true);
        $mjqj = ZhSettingQujian::find()->where(['company_id' => $this->_user['company_id'], 'qujian_shorthand' => 'zuling_jigeqj'])->select('qujian_desp')->asArray()->one();
        $data['zlqj'] = json_decode($mjqj['qujian_desp'], true);
        $data['xuequ'] = School::find()->select('s_id,s_name')->where(['company_id' => $this->_user['company_id'], 'is_del' => '0'])->asArray()->all();

        //图片配置
        $data['house_imgs'] = [
            ['name' => '封面', 'type' => 1, 'is_cover' => 1],
            ['name' => '楼栋号', 'type' => 2, 'is_cover' => 0],
            ['name' => '房号', 'type' => 3, 'is_cover' => 0],
            ['name' => '主卧', 'type' => 4, 'is_cover' => 0],
            ['name' => '次卧', 'type' => 5, 'is_cover' => 0],
            ['name' => '客厅', 'type' => 6, 'is_cover' => 0],
            ['name' => '餐厅', 'type' => 7, 'is_cover' => 0],
            ['name' => '阳台', 'type' => 8, 'is_cover' => 0],
            ['name' => '卫生间', 'type' => 9, 'is_cover' => 0],
            ['name' => '小区外观', 'type' => 10, 'is_cover' => 0],
            ['name' => '户型图', 'type' => 11, 'is_cover' => 0],
            ['name' => '其他', 'type' => 17, 'is_cover' => 0],
        ];

        $data['house_other_imgs'] = [
            ['name' => '委托合同', 'type' => 12, 'is_cover' => 0],
            ['name' => '独家合同', 'type' => 13, 'is_cover' => 0],
            ['name' => '产证图片', 'type' => 14, 'is_cover' => 0],
            ['name' => '身份证正面', 'type' => 15, 'is_cover' => 0],
            ['name' => '身份证反面', 'type' => 16, 'is_cover' => 0],
        ];

        $data['sell_house_validata'] = $this->_getRequied('sale_type_es0');
//        $data['hight_house_validata'] = $this->_getRequied('sale_type_es2');
//        $data['rental_house_validata'] = $this->_getRequied('sale_type_es1');

        //加载自定义列表
        $customcolumns = CustomColumns::find()->where(['company_id' => $this->_user['company_id'], 'u_id' => $this->_user['u_id'], 'is_del' => 0])->andWhere(['or', 'module=4', 'module=5', 'module=6'])->asArray()->all();
        foreach ($customcolumns as $key => $val) {
            $data['customcolumns'][$val['module']] = json_decode($val['columns'], true);
        }
        //顶部按钮
        $data['topbutton'] = Yii::$app->LoadData->topButton($this->id, $this->_user);
        return ApiReturn::success('查询成功', $data);
    }

    /**
     * 获取数据
     * @param page int 当前页面
     * @param pageSize int 每页行数
     * @param keyWord string 搜索关键词
     */
    public function actionGetindex()
    {
        $param = Yii::$app->request->post();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;

        $row = House::find()->where(['sale_type' => 2, 'is_del' => '0']);

        if (isset($param["dts_id"]) && is_array($param["dts_id"])) {  //片区
            if (count($param["dts_id"]) > 1) {
                $row->andWhere(['dts_id' => end($param['dts_id'])]);
            } else {
                $dts_ids = ComDistrictGii::find()->select('dts_id')->where(['is_del' => '0', 'area_id' => end($param['dts_id'])])->andWhere(['OR', 'dts_status=0', 'dts_status=1 AND company_id=' . $this->_user['company_id']])->asArray()->all();
                if ($dts_ids) {
                    $row->andWhere(['IN', 'dts_id', $dts_ids]);
                }
            }
        }
        if (isset($param['village_id']) && !empty($param['village_id'])) { //小区
            $row->andWhere(['village_id' => $param['village_id']]);
        }
        if (isset($param["loudong_name"]) && $param["loudong_name"]) { //楼栋
            $row->andWhere(['loudong_name' => $param['loudong_name']]);
        }
        if (isset($param["danyuan_name"]) && $param["danyuan_name"]) { //单元
            $row->andWhere(['danyuan_name' => $param['danyuan_name']]);
        }
        if (isset($param["fanghao_name"])) { //房号
            $row->andWhere("fanghao_name like '%" . $param["fanghao_name"] . "%'");
        }
        if (isset($param["huxing_shi"]) && !empty($param["huxing_shi"])) { //室
            $row->andWhere(['huxing_shi' => $param["huxing_shi"]]);
        }
        if (isset($param["huxing_ting"]) && !empty($param["huxing_ting"])) { //厅
            $row->andWhere(['huxing_ting' => $param["huxing_ting"]]);
        }
        if (isset($param["huxing_wei"]) && !empty($param["huxing_wei"])) { //卫
            $row->andWhere(['huxing_wei' => $param["huxing_wei"]]);
        }
        if (isset($param["huxing_chu"]) && !empty($param["huxing_chu"])) { //厨
            $row->andWhere(['huxing_chu' => $param["huxing_chu"]]);
        }
        if (isset($param["huxing_yangtai"]) && !empty($param["huxing_yangtai"])) { //阳台
            $row->andWhere(['huxing_yangtai' => $param["huxing_yangtai"]]);
        }
        if (isset($param["keyword"]) && $param["keyword"]) {     //关键词 关键词可以是 房源标题、客户姓名、客户手机号、房源编号、片区小区
            $row->andWhere("house_title like '%" . $param["keyword"] . "%'"
                . " or `customer_name` like '%" . $param["keyword"] . "%'"
                . " or `customer_phone` like '%" . $param["keyword"] . "%'"
                . " or `house_sn` like '%" . $param["keyword"] . "%'"
                . " or `dts_name` like '%" . $param["keyword"] . "%'"
                . " or `village_name` like '%" . $param["keyword"] . "%'"
            );
        }
        if (isset($param["statustext"]) && $param["statustext"]) { //状态搜索
            if ($param["statustext"] == 'all') {
                $row->andWhere(['house_status' => 1]);
            } elseif ($param["statustext"] == 'share') {
                $row->andWhere(['house_status' => 1, 'house_private' => 1]);
            } elseif ($param["statustext"] == 'public') {
                $row->andWhere(['house_status' => 1])->andWhere(['<>', 'house_private', 1]);
            } elseif ($param["statustext"] == 'deal') {
                $row->andWhere(['house_status' => 2]);
            } elseif ($param["statustext"] == 'invalid') {
                $row->andWhere(['house_status' => 3]);
            }
        }
        if (isset($param["myshare"]) && $param["myshare"] == 'true') { //我的共享盘
            $row->andWhere(['house_status' => 1, 'house_private' => 1, 'private_user' => $this->_user['u_id']]);
        }
        if (isset($param["companyshare"]) && $param["companyshare"]) { //公司共享盘
            $row->andWhere(['house_status' => 1, 'house_private' => 1, 'private_company' => $this->_user['company_id']]);
        }
        if (isset($param["is_yaoshi"]) && $param["is_yaoshi"] == 'true') { //是否有钥匙
            $row->andWhere(['is_yaoshi' => '1']);
        }
        if (isset($param["is_yaoshi"]) && $param["is_yaoshi"] == 'false') { //是否有钥匙
            $row->andWhere(['is_yaoshi' => '0']);
        }
        if (isset($param["sanrixinshang"]) && $param["sanrixinshang"] == 'true') { //三日新上
            $row->andWhere(['>', 'ctime', date('Y-m-d 00:00:00', strtotime('-2 day', time()))]);
        }
        if (isset($param["dujia"]) && $param["dujia"] == 'true') {  //独家
            $row->andWhere(['is_dujia' => '1']);
        }
        if (isset($param["xuequfang"]) && $param["xuequfang"]) {  //学区房
            $tmpList = School_district::find()->where(['is_del' => '0'])->asArray()->all();
            $villages = [];
            if (!empty($tmpList)) {
                foreach ($tmpList as $item) {
                    $villages[] = $item['rn_id'];
                }
            }
            $row->andWhere(['in', 'village_id', $villages]);
        }
        if (isset($param['is_fengpan']) && $param['is_fengpan'] == true) { //封盘
            $row->andWhere(['is_fengpan' => '1']);
        }
        if (isset($param["jiqie"]) && $param["jiqie"] == 'true') {  //急切
            $row->andWhere(['like', 'house_tag', '急卖']);
        }
        if (isset($param["main"]) && ($param["main"] == 1 || $param["main"] == 'true')) { //是否为主推
            $row->andWhere(['is_main' => 1]);
        }
        if (isset($param["daikan"]) && $param["daikan"] == 'true') {  //有带看
            $row->andWhere(['>=', 'daikancishu', '1']);
        }
        if (isset($param["genjin"]) && $param["genjin"] == 'true') {  //有跟进
            $row->andWhere(['>=', 'genjincishu', '1']);
        }
        if (isset($param["sell_jgqj"]) && $param["sell_jgqj"] && $param["sell_jgqj"] != 'undefined') {  //价格区间
            $tmpData = explode("-", $param["sell_jgqj"]);
            $row->andWhere(['between', 'sell_price', $tmpData[0], $tmpData[1]]);
        }
        if (isset($param["mjqj"]) && $param["mjqj"] && $param["mjqj"] != 'undefined') {  //面积区间
            $tmpData = explode("-", $param["mjqj"]);
            $row->andWhere(['between', 'jianzhumianji', $tmpData[0], $tmpData[1]]);
        }
        if (isset($param["xuequ"]) && $param["xuequ"] && $param["xuequ"] != 'undefined') {  //学区
            $tmpList = School_district::find()->where(['s_id' => $param['xuequ'], 'is_del' => '0'])->asArray()->all();
            $villages = [];
            if (!empty($tmpList)) {
                foreach ($tmpList as $item) {
                    $villages[] = $item['rn_id'];
                }
            }
            $row->andWhere(['in', 'village_id', $villages]);
        }
        if (isset($param['laiyuan']) && $param['laiyuan']) { //来源
            $row->andWhere(['laiyuan' => $param['laiyuan']]);
        }

        if (!empty($param['paixu'])) {
            switch ($param['paixu']) {
                case 1:
                    $row->orderBy('genjincishu ASC');
                    break;
                case 2:
                    $row->orderBy('genjincishu DESC');
                    break;
                case 3:
                    $row->orderBy('daikancishu ASC');
                    break;
                case 4:
                    $row->orderBy('daikancishu DESC');
                    break;
                case 5:
                    $row->orderBy('ctime ASC');
                    break;
                case 6:
                    $row->orderBy('ctime DESC');
                    break;

            }
        } else {
            $row->orderBy('utime DESC');
        }
        $allList = $row->asArray()->all();
        $list = $row->limit($pagesize)->offset($start)->asArray()->all();
        //echo $list->createCommand()->getRawSql();die;
        foreach ($list as $key => &$item) {
            $item = $this->housedataFormat($item);
        }
        $data['list'] = $list;
        $data['count'] = $row->count();

        //计算平均值
        if (!empty($allList)) {
            $num = 0;
            $count = 0;
            foreach ($allList as $v) {
                if (!empty($v['low_sell_price']) && !empty($v['jianzhumianji']) && $v['low_sell_price'] != 0 && $v['jianzhumianji'] != 0) {
                    $num++;
                    $count += ($v['low_sell_price'] * 10000) / $v['jianzhumianji'];
                }
            }
            if ($num == 0 || $count == 0) {
                $data['alavg'] = 0;
            } else {
                $data['alavg'] = round($count / $num, 2);
            }
        } else {
            $data['alavg'] = 0;
        }

        return ApiReturn::success('查询成功', $data);
    }

    /**
     * 房源导出
     */
    public function actionExport()
    {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);

        $param = Yii::$app->request->get();

        $row = House::find()->where(['sale_type' => 2, 'is_del' => '0']);

        if (isset($param["dts_id"])) {  //片区
            $dts_arr = explode(',', $param["dts_id"]);
            if (count($dts_arr) > 1) {
                $row->andWhere(['dts_id' => end($dts_arr)]);
            } else {
                $dts_ids = ComDistrictGii::find()->select('dts_id')->where(['is_del' => '0', 'area_id' => end($dts_arr)])->andWhere(['OR', 'dts_status=0', 'dts_status=1 AND company_id=' . $this->_user['company_id']])->asArray()->all();
                if ($dts_ids) {
                    $row->andWhere(['IN', 'dts_id', $dts_ids]);
                }
            }
        }
        if (isset($param['village_id']) && !empty($param['village_id'])) { //小区
            $row->andWhere(['village_id' => $param['village_id']]);
        }
        if (isset($param["loudong_name"]) && $param["loudong_name"]) { //楼栋
            $row->andWhere(['loudong_name' => $param['loudong_name']]);
        }
        if (isset($param["danyuan_name"]) && $param["danyuan_name"]) { //单元
            $row->andWhere(['danyuan_name' => $param['danyuan_name']]);
        }
        if (isset($param["fanghao_name"])) { //房号
            $row->andWhere("fanghao_name like '%" . $param["fanghao_name"] . "%'");
        }
        if (isset($param["huxing_shi"]) && !empty($param["huxing_shi"])) { //室
            $row->andWhere(['huxing_shi' => $param["huxing_shi"]]);
        }
        if (isset($param["huxing_ting"]) && !empty($param["huxing_ting"])) { //厅
            $row->andWhere(['huxing_ting' => $param["huxing_ting"]]);
        }
        if (isset($param["huxing_wei"]) && !empty($param["huxing_wei"])) { //卫
            $row->andWhere(['huxing_wei' => $param["huxing_wei"]]);
        }
        if (isset($param["huxing_chu"]) && !empty($param["huxing_chu"])) { //厨
            $row->andWhere(['huxing_chu' => $param["huxing_chu"]]);
        }
        if (isset($param["huxing_yangtai"]) && !empty($param["huxing_yangtai"])) { //阳台
            $row->andWhere(['huxing_yangtai' => $param["huxing_yangtai"]]);
        }
        if (isset($param["keyword"]) && $param["keyword"]) {     //关键词 关键词可以是 房源标题、客户姓名、客户手机号、房源编号、片区小区
            $row->andWhere("house_title like '%" . $param["keyword"] . "%'"
                . " or `customer_name` like '%" . $param["keyword"] . "%'"
                . " or `customer_phone` like '%" . $param["keyword"] . "%'"
                . " or `house_sn` like '%" . $param["keyword"] . "%'"
                . " or `dts_name` like '%" . $param["keyword"] . "%'"
                . " or `village_name` like '%" . $param["keyword"] . "%'"
            );
        }
        if (isset($param["statustext"]) && $param["statustext"]) { //状态搜索
            if ($param["statustext"] == 'all') {
                $row->andWhere(['house_status' => 1]);
            } elseif ($param["statustext"] == 'share') {
                $row->andWhere(['house_status' => 1, 'house_private' => 1]);
            } elseif ($param["statustext"] == 'public') {
                $row->andWhere(['house_status' => 1])->andWhere(['<>', 'house_private', 1]);
            } elseif ($param["statustext"] == 'deal') {
                $row->andWhere(['house_status' => 2]);
            } elseif ($param["statustext"] == 'invalid') {
                $row->andWhere(['house_status' => 3]);
            }
        }
        if (isset($param["myshare"]) && $param["myshare"] == 'true') { //我的共享盘
            $row->andWhere(['house_status' => 1, 'house_private' => 1, 'private_user' => $this->_user['u_id']]);
        }
        if (isset($param["companyshare"]) && $param["companyshare"]) { //公司共享盘
            $row->andWhere(['house_status' => 1, 'house_private' => 1, 'private_company' => $this->_user['company_id']]);
        }
        if (isset($param["is_yaoshi"]) && $param["is_yaoshi"] == 'true') { //是否有钥匙
            $row->andWhere(['is_yaoshi' => '1']);
        }
        if (isset($param["is_yaoshi"]) && $param["is_yaoshi"] == 'false') { //是否有钥匙
            $row->andWhere(['is_yaoshi' => '0']);
        }
        if (isset($param["sanrixinshang"]) && $param["sanrixinshang"] == 'true') { //三日新上
            $row->andWhere(['>', 'ctime', date('Y-m-d 00:00:00', strtotime('-2 day', time()))]);
        }
        if (isset($param["dujia"]) && $param["dujia"] == 'true') {  //独家
            $row->andWhere(['is_dujia' => '1']);
        }
        if (isset($param["xuequfang"]) && $param["xuequfang"]) {  //学区房
            $tmpList = School_district::find()->where(['is_del' => '0'])->asArray()->all();
            $villages = [];
            if (!empty($tmpList)) {
                foreach ($tmpList as $item) {
                    $villages[] = $item['rn_id'];
                }
            }
            $row->andWhere(['in', 'village_id', $villages]);
        }
        if (isset($param['is_fengpan']) && $param['is_fengpan'] == true) { //封盘
            $row->andWhere(['is_fengpan' => '1']);
        }
        if (isset($param["jiqie"]) && $param["jiqie"] == 'true') {  //急切
            $row->andWhere(['like', 'house_tag', '急卖']);
        }
        if (isset($param["main"]) && ($param["main"] == 1 || $param["main"] == 'true')) { //是否为主推
            $row->andWhere(['is_main' => 1]);
        }
        if (isset($param["daikan"]) && $param["daikan"] == 'true') {  //有带看
            $row->andWhere(['>=', 'daikancishu', '1']);
        }
        if (isset($param["genjin"]) && $param["genjin"] == 'true') {  //有跟进
            $row->andWhere(['>=', 'genjincishu', '1']);
        }
        if (isset($param["sell_jgqj"]) && $param["sell_jgqj"] && $param["sell_jgqj"] != 'undefined') {  //价格区间
            $tmpData = explode("-", $param["sell_jgqj"]);
            $row->andWhere(['between', 'sell_price', $tmpData[0], $tmpData[1]]);
        }
        if (isset($param["mjqj"]) && $param["mjqj"] && $param["mjqj"] != 'undefined') {  //面积区间
            $tmpData = explode("-", $param["mjqj"]);
            $row->andWhere(['between', 'jianzhumianji', $tmpData[0], $tmpData[1]]);
        }
        if (isset($param["xuequ"]) && $param["xuequ"] && $param["xuequ"] != 'undefined') {  //学区
            $tmpList = School_district::find()->where(['s_id' => $param['xuequ'], 'is_del' => '0'])->asArray()->all();
            $villages = [];
            if (!empty($tmpList)) {
                foreach ($tmpList as $item) {
                    $villages[] = $item['rn_id'];
                }
            }
            $row->andWhere(['in', 'village_id', $villages]);
        }
        if (isset($param['laiyuan']) && $param['laiyuan']) { //来源
            $row->andWhere(['laiyuan' => $param['laiyuan']]);
        }

        if (!empty($param['paixu'])) {
            switch ($param['paixu']) {
                case 1:
                    $row->orderBy('genjincishu ASC');
                    break;
                case 2:
                    $row->orderBy('genjincishu DESC');
                    break;
                case 3:
                    $row->orderBy('daikancishu ASC');
                    break;
                case 4:
                    $row->orderBy('daikancishu DESC');
                    break;
                case 5:
                    $row->orderBy('ctime ASC');
                    break;
                case 6:
                    $row->orderBy('ctime DESC');
                    break;

            }
        } else {
            $row->orderBy('utime DESC');
        }
        $list = $row->asArray()->all();
        foreach ($list as $k => &$v) {
            $v = $this->housedataFormat($v);
        }
        //var_dump($list);die;
        $n = 0;
        foreach ($list as $v) {

            //报表头的输出
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:V1');
            $objectPHPExcel->getActiveSheet()->setCellValue('A1', '房源列表');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '房源列表');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '房源列表');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '日期：' . date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('V2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '房源编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '片区');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '小区');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '楼层');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '使用面积');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '售价(万)');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '装修');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '朝向');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '房型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', '建筑面积');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', '建筑年代');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', '产权性质');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', '等级');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', '录入日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', '修改日期');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', '部门');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3', '维护人');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('S3', '维护人最后跟进');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('T3', '委托编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('U3', '备注');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('V3', '全员最后跟进');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A3:V3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($n + 4), $v['house_sn']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B' . ($n + 4), $v['house_status_text']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n + 4), $v['dts_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n + 4), $v['village_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n + 4), $v['louceng']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n + 4), $v['shiyongmianji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('G' . ($n + 4), $v['sell_price']);
            $objectPHPExcel->getActiveSheet()->setCellValue('H' . ($n + 4), $v['zhuangxiu']);
            $objectPHPExcel->getActiveSheet()->setCellValue('I' . ($n + 4), $v['chaoxiang']);
            $objectPHPExcel->getActiveSheet()->setCellValue('J' . ($n + 4), $v['fangxing']);
            $objectPHPExcel->getActiveSheet()->setCellValue('K' . ($n + 4), $v['jianzhumianji']);
            $objectPHPExcel->getActiveSheet()->setCellValue('L' . ($n + 4), $v['jianzaoniandai']);
            $objectPHPExcel->getActiveSheet()->setCellValue('M' . ($n + 4), $v['chanquanxingzhi']);
            $objectPHPExcel->getActiveSheet()->setCellValue('N' . ($n + 4), $v['house_level']);
            $objectPHPExcel->getActiveSheet()->setCellValue('O' . ($n + 4), $v['ctime']);
            $objectPHPExcel->getActiveSheet()->setCellValue('P' . ($n + 4), $v['utime']);
            $objectPHPExcel->getActiveSheet()->setCellValue('Q' . ($n + 4), $v['bumen']);
            $objectPHPExcel->getActiveSheet()->setCellValue('R' . ($n + 4), $v['weihuren']);
            $objectPHPExcel->getActiveSheet()->setCellValue('S' . ($n + 4), $v['weihurengenjin']);
            $objectPHPExcel->getActiveSheet()->setCellValue('T' . ($n + 4), $v['weituobianhao']);
            $objectPHPExcel->getActiveSheet()->setCellValue('U' . ($n + 4), $v['mark']);
            $objectPHPExcel->getActiveSheet()->setCellValue('V' . ($n + 4), $v['zuihougenjin']);
            $n = $n + 1;
        }

        //设置分页显示
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header("Content-type: text/html; charset=utf-8");
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="fangyuan-' . date("Y/m/j") . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');

        $objWriter->save('php://output');
    }

    /**
     * 格式化列表数据
     */
    private function housedataFormat($data)
    {
        $userList = User::find()->alias('a')->select('a.u_id,a.u_name,b.d_name')->leftJoin('zh_depart as b', 'a.u_dept_id=b.d_id')->where(['a.is_del' => '0'])->asArray()->all();
        $userList = ArrayHelper::index($userList, 'u_id');
        //状态
        if ($data['house_status'] == 1) {
            $data['house_status_text'] = '有效';
        } else if ($data['house_status'] == 2) {
            $data['house_status_text'] = '成交';
        } else if ($data['house_status'] == 3) {
            $data['house_status_text'] = '无效';
        } else {
            $data['house_status_text'] = '--';
        }
        //楼盘
        if ($data['house_private'] == 1) {
            $data['house_private_text'] = '共享盘';
        } else {
            $data['house_private_text'] = '公盘';
        }
        //处理楼层
        $data['louceng'] = $this->loucengFormat($data['louceng_now'], $data['louceng_total']);
        //处理方形
        $data['fangxing'] = $data['huxing_shi'] . '室' . $data['huxing_ting'] . '厅' . $data['huxing_chu'] . '厨' . $data['huxing_wei'] . '卫' . $data['huxing_yangtai'] . '阳台';
        $data['peitao'] = json_decode($data['peitao'], true) ? json_decode($data['peitao'], true) : [];
        $data['house_tuijian_tag'] = json_decode($data['house_tuijian_tag'], true) ? json_decode($data['house_tuijian_tag'], true) : [];
        //店名称
        if (!empty($data['auth_sid'])) {
            $data['dian'] = $this->_getYaoshidian($data['auth_sid']);
        } else {
            $data['dian'] = '';
        }
        $data['house_tag_qianpei'] = false; //签赔
        if($data['qianpei']>0 && !empty($data['qianpei_img'])){
            $data['house_tag_qianpei'] = true; //签赔
        }
        $data['house_tag_ji'] = false; //急切
        $data['house_tag_du'] = false;//独家委托
        $data['house_tag_quan'] = false;//全款
        $data['house_tag_yao'] = false;//钥匙
        $data['house_tag_images'] = false;//图片
        $data['house_tag_geng'] = false;
        $data['house_tag_dai'] = false;
        $data['house_tag'] = json_decode($data['house_tag'], true) ? json_decode($data['house_tag'], true) : [];
        if (!empty($data['house_tag'])) {
            if (is_array($data['house_tag'])) {
                foreach ($data['house_tag'] as $value) {
                    if ($value == '急卖') {
                        $data['house_tag_ji'] = true;
                    }
                    if ($value == '全款') {
                        $data['house_tag_quan'] = true;
                    }
                }
            }
            if (!empty($data['is_yaoshi'])) {
                $data['house_tag_yao'] = true;
            }
            if (!empty($data['is_dujia'])) {
                $data['house_tag_du'] = true;
            }

            if (!empty($data['is_images'])) {
                $data['house_tag_images'] = true;
                $data['images'] = HouseImg::find()->where(['house_id' => $data['house_id'], 'hi_is_cover' => '1'])->asArray()->one();
            } else {
                $data['images'] = false;
            }
        }
        $gengCount = $data['genjincishu'];
        if ($gengCount > 0) {
            $data['house_tag_geng'] = true;
            $data['house_tag_geng_num'] = $gengCount;
        }
        if ($data['daikancishu'] > 0) {
            $data['house_tag_dai'] = true;
            $data['house_tag_dai_num'] = $data['daikancishu'];
        }
        $data['weihuren'] = '';
        $data['bumen'] = '';
        $data['tianjiaren'] = '';
        if (!empty($data['private_user']) && isset($userList[$data['private_user']])) {
            $data['weihuren'] = $userList[$data['private_user']]['u_name'];
            $data['bumen'] = $userList[$data['private_user']]['d_name'];
        }
        if (!empty($data['c_id']) && isset($userList[$data['c_id']])) {
            $data['tianjiaren'] = $userList[$data['c_id']]['u_name'];
        }

        //查看跟时空
        $gengjingList = HouseFollowup::find()->where(['house_id' => $data['house_id'], 'is_del' => '0'])->orderBy('ctime DESC')->asArray()->all();
        $data['weihurengenjin'] = $data['utime'];
        $data['zuihougenjin'] = $data['utime'];
        if ($gengjingList) {
            $data['zuihougenjin'] = $gengjingList[0]['ctime'];
            foreach ($gengjingList as $gengjin) {
                if ($gengjin['c_id'] == $data['private_user']) {
                    $data['weihurengenjin'] = $gengjin['ctime'];
                }
            }
        }
        return $data;
    }

    /**
     * 添加
     * @param
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $house_sn = Yii::$app->redis->get('house_sn');

            if (empty($house_sn)) {
                $house_sn = 1;
                Yii::$app->redis->set('house_sn', $house_sn);
            } else {
                $house_sn = $house_sn + 1;
                Yii::$app->redis->set('house_sn', $house_sn);
            }
            $post['house_sn'] = 'CSFY-' . date('ymd') . '-' . str_pad($house_sn, 4, "0", STR_PAD_LEFT);
            $post['house_uuid'] = Tools::create_uuid('CSFY-');
            $post['loudong_name'] = trim($post['loudong_name']);
            $post['danyuan_name'] = trim($post['danyuan_name']);
            $post['fanghao_name'] = trim($post['fanghao_name']);

            //判断房源重复
            $house = House::find()->select('house_sn,house_uuid')->where(['village_id' => $post['village_id'], 'loudong_name' => $post['loudong_name'], 'fanghao_name' => $post['fanghao_name'], 'sale_type' => 2, 'is_del' => "0"])->asArray()->one();
            if ($house) {
                $this->addhouseuser($post['house_uuid'], 1, '');
                return ApiReturn::exist('添加房源与【' . $house['house_sn'] . '】重复,请查看',$house);
            }

            if (isset($post['house_tag']) && is_array($post['house_tag'])) {
                foreach ($post['house_tag'] as $key => $value) {
                    if ($value == 'undefined') {
                        unset($post['house_tag'][$key]);
                    }
                }
            } else {
                $post['house_tag'] = [];
            }

            $model = new House();
            $house_id = $model->updateHouse($post, $this->_user);//var_dump($house_id);die;
            if ($house_id) {
                return ApiReturn::success('添加成功', ['house_id' => $house_id]);
            } else {
                return ApiReturn::wrongParams('添加失败');
            }
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /***
     * 房源详情
     * @return array|\common\models\json
     */
    public function actionDetail()
    {
        $param = Yii::$app->request->get();
        if (!empty($param['house_id'])) {
            $house = House::find()->select('a.*,b.u_name as tianjiaren,c.u_name as weihuren,d.u_name as yaoshiren,e.u_name as weituoren,f.u_name as tupianren,g.area_id,g.area_name')->alias('a')
                ->leftJoin('zh_user b', 'a.c_id=b.u_id')
                ->leftJoin('zh_user c', 'a.private_user=c.u_id')
                ->leftJoin('zh_user d', 'a.is_yaoshi_user=d.u_id')
                ->leftJoin('zh_user e', 'a.is_dujia_user=e.u_id')
                ->leftJoin('zh_user f', 'a.is_images_user=f.u_id')
                ->leftJoin('com_district g', 'a.dts_id=g.dts_id')
                ->where(['a.house_uuid' => $param['house_id']])->asArray()->one();
            if ($house) {
                $house = $this->housedataFormat($house);
                $house['house_phone'] = HousePhoneGii::find()->where(['house_id' => $param['house_id'], 'is_del' => '0'])->asArray()->all();
                //房源划成人
                $house_user = HouseUserGii::find()->alias('a')->select('a.id,a.type,a.user_id,a.depart_id,a.company_id,b.u_name,b.u_phone,c.company_id,c.company_title')
                    ->where(['a.house_id' => $param['house_id'], 'a.is_del' => '0'])
                    ->leftJoin('zh_user b', 'a.user_id=b.u_id')
                    ->leftJoin('org_company c', 'a.company_id=c.company_id')->asArray()->all();
                $house['house_user'] = ['luru' => [], 'weihu' => [], 'tupian' => [], 'yaoshi' => [], 'yiban' => [], 'dujia' => []];
                $authuser = [];
                $canedituser = [];
                foreach ($house_user as $key => $item) {
                    if ($item['type'] == 1) {
                        $authuser[] = $item['user_id'];
                        $item['devide_type'] = '录入人';
                        $item['canedit'] = $this->_checkDivideAuth($item['user_id'],$this->_user);
                        $house['house_user']['luru'][] = $item;
                    } elseif ($item['type'] == 2) {
                        $authuser[] = $item['user_id'];
                        $item['devide_type'] = '维护人';
                        $item['canedit'] = $this->_checkDivideAuth($item['user_id'],$this->_user);
                        $house['house_user']['weihu'] = $item;
                    } elseif ($item['type'] == 3) {
                        $item['devide_type'] = '图片人';
                        $item['canedit'] = $this->_checkDivideAuth($item['user_id'],$this->_user);
                        $house['house_user']['tupian'] = $item;
                    } elseif ($item['type'] == 4) {
                        $item['devide_type'] = '钥匙人';
                        $item['canedit'] = $this->_checkDivideAuth($item['user_id'],$this->_user);
                        $house['house_user']['yaoshi'][] = $item;
                    } elseif ($item['type'] == 5) {
                        $item['devide_type'] = '一般委托人';
                        $item['canedit'] = $this->_checkDivideAuth($item['user_id'],$this->_user);
                        $house['house_user']['yiban'][] = $item;
                    } elseif ($item['type'] == 6) {
                        $item['devide_type'] = '独家委托人';
                        $item['canedit'] = $this->_checkDivideAuth($item['user_id'],$this->_user);
                        $house['house_user']['dujia'] = $item;
                    }
                }
                //页面操作权限的判断及数据显示
                $authuser = $this->_getLeaderByUser($authuser); //获取录入人和维护人及其直系领导
                $house['buttonauth'] = 0;
                if (in_array($this->_user['u_id'], $authuser)) {
                    $house['buttonauth'] = 1;
                    $house['userlist'] = User::find()->select('u_id,u_name')->where(['company_id'=>$this->_user['company_id'],'is_del'=>0])->orWhere(['u_status'=>1,'u_status'=>2,'u_status'=>3])->asArray()->all();

                }

                if ($house['house_private'] == 4) { //公盘
                    $house['showdata'] = 1;
                    $house['setprivate'] = 1;
                } elseif ($house['house_private'] == 3) { //公司公盘
                    if ($house['private_company'] != $this->_user['company_id'] && !in_array($this->_user['u_id'], $authuser)) {
                        $house['showdata'] = 0;
                        $house['setprivate'] = 0;
                        $house['low_sell_price'] = '******';
                        $house['loudong_name'] = '******';
                        $house['danyuan_name'] = '******';
                        $house['fanghao_name'] = '******';
                        $house['customer_name'] = '******';
                        $house['customer_phone'] = '******';
                        foreach ($house['house_phone'] as $key => $item) {
                            $house['house_phone'][$key]['hp_phone'] = '******';
                        }
                    } else {
                        $house['setprivate'] = 1;
                        $house['showdata'] = 1;
                    }
                } elseif ($house['house_private'] == 2) { //门店公盘
                    if ($house['private_store'] != $this->_user['u_dept_id'] && !in_array($this->_user['u_id'], $authuser)) {
                        $house['showdata'] = 0;
                        $house['setprivate'] = 0;
                        $house['low_sell_price'] = '******';
                        $house['loudong_name'] = '******';
                        $house['danyuan_name'] = '******';
                        $house['fanghao_name'] = '******';
                        $house['customer_name'] = '******';
                        $house['customer_phone'] = '******';
                        foreach ($house['house_phone'] as $key => $item) {
                            $house['house_phone'][$key]['hp_phone'] = '******';
                        }
                    } else {
                        $house['setprivate'] = 1;
                        $house['showdata'] = 1;
                    }
                } else { //共享盘
                    if ($house['private_user'] != $this->_user['u_id'] && !in_array($this->_user['u_id'], $authuser)) {
                        $house['showdata'] = 0;
                        $house['setprivate'] = 0;
                        $house['low_sell_price'] = '******';
                        $house['loudong_name'] = '***';
                        $house['danyuan_name'] = '***';
                        $house['fanghao_name'] = '***';
                        $house['customer_name'] = '******';
                        $house['customer_phone'] = '******';
                        foreach ($house['house_phone'] as $key => $item) {
                            $house['house_phone'][$key]['hp_phone'] = '******';
                        }
                    } else {
                        $house['setprivate'] = 0;
                        $house['showdata'] = 1;
                    }
                }
                //图片权限判断
                $house['images_show'] = 0;
                if (empty($house['is_images_user'])) {
                    $house['images_show'] = 1;
                } else {
                    if ($house['is_images_user'] == $this->_user['u_id']) {
                        $house['images_show'] = 1;
                    }
                }

                //获取房源图片
                $tmpImgs = [
                    ['name' => '封面', 'type' => 1, 'is_cover' => 1],
                    ['name' => '楼栋号', 'type' => 2, 'is_cover' => 0],
                    ['name' => '房号', 'type' => 3, 'is_cover' => 0],
                    ['name' => '主卧', 'type' => 4, 'is_cover' => 0],
                    ['name' => '次卧', 'type' => 5, 'is_cover' => 0],
                    ['name' => '客厅', 'type' => 6, 'is_cover' => 0],
                    ['name' => '餐厅', 'type' => 7, 'is_cover' => 0],
                    ['name' => '阳台', 'type' => 8, 'is_cover' => 0],
                    ['name' => '卫生间', 'type' => 9, 'is_cover' => 0],
                    ['name' => '小区外观', 'type' => 10, 'is_cover' => 0],
                    ['name' => '户型图', 'type' => 11, 'is_cover' => 0],
                    ['name' => '委托合同', 'type' => 12, 'is_cover' => 0],
                    ['name' => '独家合同', 'type' => 13, 'is_cover' => 0],
                    ['name' => '产证图片', 'type' => 14, 'is_cover' => 0],
                    ['name' => '身份证正面', 'type' => 15, 'is_cover' => 0],
                    ['name' => '身份证反面', 'type' => 16, 'is_cover' => 0],
                    ['name' => '其他', 'type' => 17, 'is_cover' => 0],
                ];
                $images = HouseImg::find()->where(['house_id' => $house['house_id'], 'is_del' => '0'])->asArray()->all();
                foreach ($tmpImgs as $tmp) {
                    if (!empty($images)) {
                        foreach ($images as $key => $item) {
                            if ($tmp['type'] < 12 || $tmp['type'] > 16) {
                                if ($tmp['type'] == $item['hi_type']) {
                                    $house['images'][$tmp['type']][] = $item;
                                }
                            } else {
                                if ($tmp['type'] == $item['hi_type']) {
                                    $house['other_images'][$tmp['type']][] = $item;
                                }
                            }
                        }
                        if ($tmp['type'] < 12 || $tmp['type'] > 16) {
                            if (empty($house['images'][$tmp['type']])) {
                                $house['images'][$tmp['type']] = [];
                            }
                        } else {
                            if (empty($house['other_images'][$tmp['type']])) {
                                $house['other_images'][$tmp['type']] = [];
                            }
                        }
                    } else {
                        if ($tmp['type'] < 12 || $tmp['type'] > 16) {
                            $house['images'][$tmp['type']] = [];
                        } else {
                            $house['other_images'][$tmp['type']] = [];
                        }
                    }
                }
                $house['imglist'] = [];
                foreach ($house['images'] as $image) {
                    if ($image && is_array($image)) {
                        foreach ($image as $item) {
                            $house['imglist'][] = $item;
                        }
                    }
                }
                $house['other_images_list'] = [];
                foreach ($house['other_images'] as $image) {
                    if ($image && is_array($image)) {
                        foreach ($image as $item) {
                            $house['other_images_list'][] = $item;
                        }
                    }
                }
                House::setLog($param['house_id'], '1', $this->_user['u_name'] . '查看了房源详情', $this->_user);
                $house['village'] = ComVillageGii::find()->where(['is_del' => 0])->andWhere(['dts_id' => $house["dts_id"]])->andWhere(['OR', 'village_status=0', 'village_status=1 AND company_id=' . $this->_user['company_id']])->asArray()->all();
                return ApiReturn::success('查询成功', $house);
            } else {
                return ApiReturn::wrongParams('获取失败');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /**
     * 修改
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            //判断房源重复
            $house = House::find()->alias('h')->select('h.house_sn,h.house_uuid')->where(['h.village_id' => $post['village_id'], 'h.loudong_name' => $post['loudong_name'], 'h.fanghao_name' => $post['fanghao_name'], 'h.sale_type' => 2, 'h.is_del' => "0"])
                ->asArray()->one();
            if (empty($house)) {
                return ApiReturn::wrongParams('参数错误');
            }

            if (isset($post['house_tag']) && is_array($post['house_tag'])) {
                foreach ($post['house_tag'] as $key => $value) {
                    if ($value == 'undefined') {
                        unset($post['house_tag'][$key]);
                    }
                }
            } else {
                $post['house_tag'] = [];
            }

            $model = new House();
            $result = $model->updateHouse($post, $this->_user);
            if ($result !== false) {
                House::setLog($post['house_id'], '1', $this->_user['u_name'] . '修改了房源', $this->_user);
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改失败1');
            }
        } else {
            return ApiReturn::wrongParams('修改失败2');
        }
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
                    $House = House::findOne($v['house_id']);
                    $House->is_del = 1;
                    if ($House->update() === false) {
                        $transaction->rollBack();
                        return false;
                    };
                    House::setLog($v['house_id'], '0', '删除了房源', $this->_user);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();

            return ApiReturn::success('删除成功');
        } else {
            return ApiReturn::wrongParams('请您选择您要删除的房源');
        }


    }

    /***
     * 变更状态
     */
    public function actionSetstatus()
    {
        $post = Yii::$app->request->post();
        //判断是否需要审核
        $xiugaifangyuanzhuangtai = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'xiugaifangyuanzhuangtai', 'company_id' => $this->_user['company_id']])->asArray()->one();

        //判断该资源有没有正在审核的项目
        if (!Verify::verifyService(1, $post['house_uuid'])) {
            return ApiReturn::wrongParams('当前资源有操作还在审核');
        }
        $status = "未知";
        switch ($post['house_status']) {
            case '1':
                $status = '有效';
                break;
            case '2':
                $status = '成交';
                break;
            case '3':
                $status = '无效';
                break;
        }
        $param = [
            'u_id' => $this->_user['u_id'],
            'house_uuid' => $post['house_uuid'],
            'house_status' => $post['house_status'],
            'utime' => date('Y-m-d H:i:s', time()),
        ];
        if ($xiugaifangyuanzhuangtai['val'] == 1) {
            $shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'house_status_verify_user', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $leader = $this->_getVefityUser($this->_user, $shengheren['val']);
            if ($leader === false) {
                return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
            }
            $post['content'] = empty($post['content']) ? '' : $post['content'];
            $model = new Verify();
            $model->u_id = $this->_user['u_id'];
            $model->company_id = $this->_user['company_id'];
            $model->v_post_user = $this->_user['u_id'];
            $model->v_type = '房源状态变更审核';
            $model->v_end_user = $leader;
            $model->v_pass_func = serialize(['backend\models' . '\House', 'setStatus']);
            $model->v_pass_param = serialize($param);
            $model->v_service_id = $post['house_uuid'];
            $model->v_service_sn = $post['house_sn'];
            $model->v_service_type = 1;
            $model->v_content = '修改房源状态为[' . $status . '],修改理由：' . $post['content'];
            $model->v_status = 0;
            $model->c_id = $model->u_id = $this->_user['u_id'];
            $model->ctime = $model->utime = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                return ApiReturn::success('状态修改已提交，等待审核');
            } else {
                var_dump($model->getErrors());
                return ApiReturn::wrongParams('状态修改失败2');
            }
        } else {
            if (House::setStatus($param)) {
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改失败1');
            }
        }
    }

    public function actionGetdealavg()
    {
        $param = Yii::$app->request->post();

        $row = House::find()->select('SUM(sell_price) as price,SUM(jianzhumianji) as area')->where(['is_del' => 0, 'sale_type' => $param['type']]);
        if (isset($param['area']) && $param['area']) {
            if (count($param['area']) == 2) {
                $row->andWhere(['village_id' => $param['area'][1]]);
            } else {
                $row->andWhere(['dts_id' => $param['area'][0]]);
            }

        }
        if (isset($param['dealtime']) && $param['dealtime']) {
            if ($param['dealtime'] == '近三个月') {
                $time = date('Y-m-01', strtotime('-2 month'));
            } elseif ($param['dealtime'] == '近半年') {
                $time = date('Y-m-01', strtotime('-5 month'));
            } elseif ($param['dealtime'] == '近一年') {
                $time = date('Y-m-01', strtotime('-11 month'));
            } elseif ($param['dealtime'] == '近两年') {
                $time = date('Y-m-01', strtotime('-23 month'));
            }
            $row->andWhere(['>=', 'ctime', $time]);
        }
        //echo $row->createCommand()->getRawSql();die;
        $data = $row->asArray()->one();
        $avg_price = '-';
        if ($data['area'] != 0) {
            $avg_price = round($data['price'] * 10000 / $data['area'], 2);
        }


        return ApiReturn::success('获取成功', $avg_price);
    }

    /***
     * 设置主推
     * @return array|\common\models\json
     */
    public function actionSetmain()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid']])->asArray()->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }
        //判断用户操作的用户是否正确
        if ($post['is_main'] == 1) {
            //判断数量
            $house_main_num = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'house_main_num',])->asArray()->one();
            $houseCount = House::find()->where(['company_id' => $this->_user['company_id'], 'is_main' => '1', 'is_del' => 0])->count();
            if ($houseCount >= $house_main_num['val']) {
                return ApiReturn::wrongParams('您最多可以推荐' . $house_main_num['val'] . '套房源，已经达到最大数量了');
            }
        }
        if (House::updateAll(['is_main' => $post['is_main']], ['house_uuid' => $post['house_uuid']])) {
            if ($post['is_main'] == 1) {
                House::setLog($post['house_uuid'], '6', $this->_user['u_name'].'设置了主推', $this->_user);
            } else {
                House::setLog($post['house_uuid'], '6', $this->_user['u_name'].'取消了主推', $this->_user);
            }
            return ApiReturn::success('操作成功');
        } else {
            return ApiReturn::success('操作失败');
        }

    }

    /***
     * 设置公盘私盘
     * @return array|\common\models\json
     */
    public function actionSetprivate()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid']])->asArray()->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }

        //判断是否需要审核
        $fangyuangongsishenhe = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'fangyuangongsishenhe', 'company_id' => $this->_user['company_id']])->asArray()->one();
        //判断是否有没有审核的项目
        if (!Verify::verifyService(1, $post['house_uuid'])) {
            return ApiReturn::wrongParams('当前资源有操作还在审核');
        }

        $param = [
            'u_id' => $this->_user['u_id'],
            'house_uuid' => $post['house_uuid'],
            'house_private' => 1,
            'utime' => date('Y-m-d H:i:s', time()),
            'daikanshijian' => date('Y-m-d H:i:s', time()),
            'weihurengenjin' => date('Y-m-d H:i:s', time()),
        ];
        $v_type = "收为共享盘审核";
        $param['private_user'] = $this->_user['u_id'];
        $param['private_depart'] = $this->_user['u_dept_id'];
        $param['private_company'] = $this->_user['company_id'];

        if ($fangyuangongsishenhe['val'] == 1) {
            $shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'house_status_verify_user', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $zhuguan = $this->_getShengheren($this->_user, $shengheren['val']);
            if (!$zhuguan) {
                return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
            }
            $model = new Verify();
            $model->u_id = $this->_user['u_id'];
            $model->company_id = $this->_user['company_id'];
            $model->v_post_user = $this->_user['u_id'];
            $model->v_type = $v_type;
            $model->v_end_user = $zhuguan;
            $model->v_pass_func = serialize(['backend\models' . '\House', 'setPrivate']);
            $model->v_pass_param = serialize($param);
            $model->v_service_id = $post['house_uuid'];
            $model->v_service_sn = $post['house_sn'];
            $model->v_service_type = 1;
            $model->v_content = '修改房源状态变为[共享盘]';
            $model->v_status = 0;
            $model->c_id = $model->u_id = $this->_user['u_id'];
            $model->ctime = $model->utime = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                return ApiReturn::success('状态修改已提交，等待审核');
            } else {
                var_dump($model->getErrors());
                return ApiReturn::wrongParams('状态修改失败2');
            }

        } else {
            if (House::setPrivate($param)) {
                return ApiReturn::success('修改成功');
            } else {
                return ApiReturn::wrongParams('修改失败1');
            }
        }

    }

    /***
     * 保存房源图片
     * @param post 上传的图片数据
     * @return  上传是否成功
     */
    public function actionImgupload()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (empty($post['images'])) {
                return ApiReturn::wrongParams('添加失败，您最少上传一张图片');
            }
            try {
                $model = new HouseImg();
                if ($model->updateHouseImg($post, $this->_user)) {
                    House::setLog($post['house_id'], '3', $this->_user['u_name'] . '上传了房源图片', $this->_user);
                    $house = House::findOne($post['house_id']);
                    $this->addhouseuser($house['house_uuid'], 3, '');
                    return ApiReturn::success('上传成功');
                } else {
                    return ApiReturn::wrongParams('上传失败2');
                }
            } catch (Exception $exception) {
                return ApiReturn::codeError('上传失败1');
            }

        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /***
     * 房源图片删除
     * @return array|\common\models\json
     */
    public function actionDelhouseimg()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['hi_id']) {
                try {
                    $model = new HouseImg();
                    if ($model->delHouseImg($post['hi_id'], $this->_user())) {
                        return ApiReturn::success('删除成功');
                    } else {
                        return ApiReturn::wrongParams('删除失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('删除失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }

    /***
     * 获取所有图片
     * @return array|\common\models\json
     */
    public function actionGethouseimgs()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['house_id']) {
                try {
                    if (isset($post['hi_type'])) {
                        $row = HouseImg::find()->where(['house_id' => $post['house_id'], 'hi_type' => $post['hi_type'], 'is_del' => 0])->asArray()->all();
                    } else {
                        $row = HouseImg::find()->where(['house_id' => $post['house_id'], 'is_del' => 0])->asArray()->all();
                    }

                    if ($row !== false) {
                        return ApiReturn::success('获取成功', $row);
                    } else {
                        return ApiReturn::wrongParams('获取失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('获取失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /***
     * 房源更进
     * @return array|\common\models\json
     */
    public function actionFollowup()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $msg = '操作成功';

            if (!empty($post['hf_notify_is_chedan']) && $post['hf_notify_is_chedan'] == 'true') {
                //判断是否需要审核
                $fengpanshenhe = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'house_chedantongzhi', 'company_id' => $this->_user['company_id']])->asArray()->one();
                //判断是否有没有审核的项目
                if (!Verify::verifyService(1, $post['house_id'])) {
                    return ApiReturn::wrongParams('当前资源有操作还在审核');
                }
                $post['hf_notify_is_chedan'] = 1;
                $house = House::find()->where(['house_uuid' => $post['house_id']])->one();
                $param = [
                    'u_id' => $this->_user['u_id'],
                    'house_id' => $post['house_id'],
                    'company_id' => $this->_user['company_id'],
                    'house_status' => 0,
                    'now_status' => $house->house_status,
                ];
                $v_type = "通知撤单";

                if ($fengpanshenhe['val'] == 1) {
                    $shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'house_chedantongzhi_user', 'company_id' => $this->_user['company_id']])->asArray()->one();

                    $zhuguan = $this->_getShengheren($this->_user, $shengheren['val']);
                    if (!$zhuguan) {
                        return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
                    }

                    $model = new Verify();
                    $model->u_id = $this->_user['u_id'];
                    $model->company_id = $this->_user['company_id'];
                    $model->v_post_user = $this->_user['u_id'];
                    $model->v_type = $v_type;
                    $model->v_end_user = $zhuguan;
//					$model->v_end_user = $zhuguan;
                    //审核通过执行
                    $model->v_pass_func = serialize(['backend\models' . '\House', 'setChedan']);
                    $model->v_pass_param = serialize($param);
                    //审核不通过执行
                    $model->v_reject_func = serialize(['backend\models' . '\House', 'bhChedan']);
                    $model->v_reject_param = serialize($param);
                    $model->v_service_id = $post['house_id'];
                    $house = House::find()->where(['house_uuid' => $post['house_id']])->one();
                    $model->v_service_sn = $house->house_sn;
                    $model->v_service_type = 1;
                    $model->v_content = '申请[撤单]' . '原因:' . $post['chedan']; //$post['hf_notify_content'];
                    $model->v_status = 0;
                    $model->c_id = $model->u_id = $this->_user['u_id'];
                    $model->ctime = $model->utime = date('Y-m-d H:i:s', time());
                    $house->house_status = 3;
                    $house->save();
                    if ($model->save()) {
                        House::setLog($post['house_id'], '7', '添加了通知撤单', $this->_user);
                        $msg = '通知撤单成功，等待审核';
                        //return ApiReturn::success('申请已提交，等待审核');
                    } else {
                        var_dump($model->getErrors());
                        return ApiReturn::wrongParams('状态修改失败2');
                    }
                } else {
                    $house = House::find()->where(['house_uuid' => $post['house_id']])->one();
                    $house->house_status = 3;
                    $house->save();
                    House::setLog($post['house_uuid'], '7', '添加了通知撤单', $this->_user);
                    $msg = '通知撤单成功';
                    //return ApiReturn::success('跟进成功', $this->_getPhone($post['house_id']));
                }
            } else {
                $post['hf_notify_is_chedan'] = 0;
            }
            if ($post['house_id']) {
                try {
                    $model = new HouseFollowup();
                    if ($model->updateHouseFollowup($post, $this->_user)) {
                        //查看是否有通知撤单
                        $house = House::find()->where(['house_uuid' => $post['house_id']])->one();
                        $house->genjincishu = $house->genjincishu + 1;
                        $house->save();

                        House::setLog($post['house_id'], '7', '添加了跟进', $this->_user);

                        return ApiReturn::success($msg, $this->_getPhone($post['house_id']));
                    } else {
                        return ApiReturn::wrongParams('跟进失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('跟进失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /***
     * 房源通知
     * @return array
     */
    public function actionNotify()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['house_id']) {
                try {
                    $notify = new Notify();
                    $notify->n_title = '房源跟进通知';
                    $notify->n_content = $post['n_content'];
                    $notify->n_u_id = $post['n_u_id'];
                    $notify->n_time = $post['n_time'];
                    $notify->n_is_read = 0;
                    $notify->n_is_notify = 0;
                    $notify->n_url = '/#/roomDetails/' . $post['house_id'];
                    $notify->c_id = $this->_user['u_id'];
                    $notify->u_id = $this->_user['u_id'];
                    $notify->utime = date('Y-m-d H:i:s', time());
                    $notify->ctime = date('Y-m-d H:i:s', time());
                    $notify->auth_rid = $this->_user['auth_rid'];
                    $notify->auth_sid = $this->_user['auth_sid'];
                    $notify->auth_aid = $this->_user['auth_aid'];
                    $notify->auth_baid = $this->_user['auth_baid'];
                    if ($notify->save()) {

                        return ApiReturn::success('提醒成功');
                    } else {
                        return ApiReturn::wrongParams('提醒失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('提醒失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /***
     * 房源跟进列表 by liuz
     */
    public function actionGetgenjin()
    {
        $param = Yii::$app->request->post();
        $page = isset($param["page"]) && $param["page"] ? $param["page"] : 1;
        $pagesize = isset($param["pagesize"]) && $param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page - 1) * $pagesize;

        $row = HouseFollowup::find()
            ->alias('a')
            ->select('a.*,b.u_name,c.d_name,h.house_sn,h.genjincishu,h.sale_type,h.village_name')->alias('a')
            ->leftJoin('zh_user as b', 'a.c_id=b.u_id')
            ->leftJoin('zh_depart as c', 'b.u_dept_id=c.d_id')
            ->leftJoin('zh_house as h', 'h.house_uuid=a.house_id')
            ->where(['a.company_id' => $this->_user['company_id'], 'a.is_del' => 0]);

        if (isset($param["f_type"]) && $param["f_type"]) { //房源类型
            $row->andWhere(['h.sale_type' => trim($param['f_type'])]);
        }
        if (isset($param["hf_type"]) && $param["hf_type"]) { //跟单方式
            $row->andWhere(['a.hf_type' => trim($param['hf_type'])]);
        }
        if (isset($param["keywd"]) && $param["keywd"]) { //房号/小区/内容
            $row->andWhere("h.house_sn like '%" . $param["keywd"] . "%'" .
                " or h.village_name like '%" . $param["keywd"] . "%'" .
                " or a.hf_notify_content like '%" . $param["keywd"] . "%'");
        }
        if (isset($param['dateRange']) && $param['dateRange']) {
            $daterange = $param['dateRange'];
            if ($daterange[1] != 'undefined') {
                $row->andFilterWhere(['between', 'a.ctime', $daterange[0] . ' 00:00:01', $daterange[1] . ' 23:59:59']);
            }
        }
        if (isset($param['u_id']) && $param['u_id']) {
            $row->andWhere(['a.c_id' => $param['u_id']]);
        } else {
            if (isset($param['departpath']) && is_array($param['departpath'])) { //判断部门
                $users = $this->_getUsersByDepartId(end($param['departpath']));
                $row->andWhere(['in', 'a.c_id', $users]);
            }
        }
        $list = $row->orderBy('a.ctime desc')->limit($pagesize)->offset($start)->asArray()->all();
        foreach ($list as $key => $item) {
            $list[$key]['genjincishu'] = $item['genjincishu'];
        }
        $data['list'] = $list;
//		$data['list'] = $row->orderBy('a.ctime desc')->limit($pagesize)->offset($start)->asArray()->all();
        $data['count'] = $row->count();

        return ApiReturn::success('获取成功', $data);
    }

    /***
     * 更进列表
     * @return array|\common\models\json
     */
    public function actionGetfollowups()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $page = isset($post["page"]) && $post["page"] ? $post["page"] : 1;
            $pagesize = isset($post["pagesize"]) && $post["pagesize"] ? $post["pagesize"] : 10;
            $start = ($page - 1) * $pagesize;
            if ($post['house_id']) {
                try {
                    $row = HouseFollowup::find()
                        ->alias('a')
                        ->select('a.*,b.u_name,c.d_name')->alias('a')
                        ->leftJoin('zh_user as b', 'a.c_id=b.u_id')
                        ->leftJoin('zh_depart as c', 'b.u_dept_id=c.d_id')
                        ->where(['a.company_id' => $this->_user['company_id'], 'a.house_id' => $post['house_id'], 'a.is_del' => 0]);
                    if ($page) {
                        $count = $row->count();
                        $res = $row->orderBy('a.ctime desc')->limit($pagesize)->offset($start)->asArray()->all();
                        $data = ['list' => $res, 'count' => $count];
                        $res = $data;
                    } else {
                        $res = $row->orderBy('a.ctime desc')->asArray()->all();
                    }
                    if ($res !== false) {
                        return ApiReturn::success('获取成功', $res);
                    } else {
                        return ApiReturn::wrongParams('获取失败');
                    }
                } catch (Exception $exception) {
                    var_dump($exception);
                    return ApiReturn::codeError('获取失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /***
     *更进删除
     * @return array|\common\models\json
     */
    public function actionDelfollowup()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['hf_id']) {
                try {
                    $model = new HouseFollowup();
                    if ($model->delHouseHouseFollowup($post['hf_id'], $this->_user())) {
                        return ApiReturn::success('删除成功');
                    } else {
                        return ApiReturn::wrongParams('删除失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('删除失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }

    /***
     * 添加房源描述
     * @return array|\common\models\json
     */
    public function actionSethousedescribe()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['house_id']) {
                try {
                    $model = new HouseDescribe();
                    if ($model->updateHouseDescribe($post, $this->_user)) {
                        House::setLog($post['house_id'], '17', '添加了房源描述', $this->_user);
                        return ApiReturn::success('上传成功');
                    } else {
                        return ApiReturn::wrongParams('上传失败2');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('上传失败1');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /***
     * 编辑描述
     * @return array|\common\models\json
     */
    public function actionEdithousedescribe()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['hd_id']) {
                try {
                    $model = new HouseDescribe();
                    if ($model->updateHouseDescribe($post, $this->_user())) {
                        House::setLog($post['house_id'], '18', '修改了房源描述', $this->_user);
                        return ApiReturn::success('编辑成功');
                    } else {
                        return ApiReturn::wrongParams('编辑失败2');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('编辑失败1');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('编辑失败');
        }
    }

    /***
     * 描述列表
     * @return array|\common\models\json
     */
    public function actionGethousedescribes()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['house_id']) {
                try {
                    $row = HouseDescribe::find()->select('a.*,b.u_name,c.d_name')->alias('a')
                        ->leftJoin('zh_user as b', 'a.c_id=b.u_id')
                        ->leftJoin('zh_depart as c', 'b.u_dept_id=c.d_id')
                        ->where(['a.house_id' => $post['house_id'], 'a.is_del' => 0])->asArray()->all();
                    if ($row !== false) {
                        return ApiReturn::success('获取成功', $row);
                    } else {
                        return ApiReturn::wrongParams('获取失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('获取失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /***
     * 删除描述
     * @return array|\common\models\json
     */
    public function actionDelhousedescribe()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['hd_id']) {
                try {
                    $model = new HouseDescribe();
                    if ($model->delHouseDescribe($post['hd_id'], $this->_user())) {
                        return ApiReturn::success('删除成功');
                    } else {
                        return ApiReturn::wrongParams('删除失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('删除失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('删除失败');
        }
    }

    /***
     * 描述详情
     * @return array|\common\models\json
     */
    public function actionGethousedescribedetial()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['hd_id']) {
                try {
                    $row = HouseDescribe::find()->select('a.*,b.u_name,c.d_name')->alias('a')
                        ->leftJoin('zh_user as b', 'a.c_id=b.u_id')
                        ->leftJoin('zh_depart as c', 'b.u_dept_id=c.d_id')
                        ->where(['a.hd_id' => $post['hd_id'], 'a.is_del' => 0])->asArray()->one();
                    if ($row !== false) {
                        return ApiReturn::success('获取成功', $row);
                    } else {
                        return ApiReturn::wrongParams('获取失败');
                    }
                } catch (Exception $exception) {
                    return ApiReturn::codeError('获取失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /***
     * 封盘
     * @return array|\common\models\json
     */
    public function actionFengpan()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid']])->asArray()->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }
        if(empty($house['private_user'])){
            return ApiReturn::wrongParams('需要先手为共享盘');
        }

        //判断是否需要审核
        $fengpanshenhe = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'fengpanshenhe', 'company_id' => $this->_user['company_id']])->asArray()->one();
        $fengpanshuliang = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'fengpanshuliang', 'company_id' => $this->_user['company_id']])->asArray()->one();
        if ($post['is_fengpan'] == 1) {
            $yixiangjinfengpanday = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'yixiangjinfengpanday', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $fengpandaoqi = date('Y-m-d H:i:s', strtotime('+'.$yixiangjinfengpanday['val'].'day', time()));
        }else if ($post['is_fengpan'] == 2) {
            $dingjinfengpanday = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'dingjinfengpanday', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $fengpandaoqi = date('Y-m-d H:i:s', strtotime('+'.$dingjinfengpanday['val'].'day', time()));
        }

        //判断是否有没有审核的项目
        if (!Verify::verifyService(1, $post['house_uuid'])) {
            return ApiReturn::wrongParams('当前资源有操作还在审核');
        }

        //查看封盘数量
        $userFengpanCount = House::find()->where(['fengpan_user' => $this->_user['u_id'], 'is_del' => '0'])->andWhere(['or', ['is_fengpan' => '1'], ['is_fengpan' => '2']])->count();
        if ($userFengpanCount >= $fengpanshuliang['val']) {
            return ApiReturn::wrongParams('您能保留的封盘为:' . $fengpanshuliang['val'] . ',已经用完了。');
        }
        $param = [
            'u_id' => $this->_user['u_id'],
            'house_uuid' => $post['house_uuid'],
            'is_fengpan' => $post['is_fengpan'],
            'company_id' => $this->_user['company_id'],
            'fengpandaoqi' => $fengpandaoqi,
            'fengpan_image' => $post['fengpan_image'],
            'fengpan_user' => $this->_user['u_id'],
            'utime' => date('Y-m-d H:i:s', time()),
        ];
        $v_type = "意向金封盘";
        if ($post['is_fengpan'] == 2) {
            $v_type = "定金封盘";
        }

        if ($fengpanshenhe['val'] == 1) {
            $shengheren = ZhSettingJuece::find()->where(['jsetting_shorthand' => 'house_fengpan_verify_user', 'company_id' => $this->_user['company_id']])->asArray()->one();
            $private_user = User::find()->where(['u_id'=>$house['private_user']])->asArray()->one();
            $leader = $this->_getVefityUser($private_user, $shengheren['val']);
            if ($leader === false) {
                return ApiReturn::wrongParams('能帮你审核的人不知道去哪了^.^');
            }

            $model = new Verify();
            $model->u_id = $this->_user['u_id'];
            $model->company_id = $this->_user['company_id'];
            $model->v_post_user = $this->_user['u_id'];
            $model->v_type = $v_type;
            $model->v_end_user = $leader;
            $model->v_pass_func = serialize(['backend\models' . '\House', 'setFengpan']);
            $model->v_pass_param = serialize($param);
            $model->v_service_id = $post['house_uuid'];
            $model->v_service_sn = $post['house_sn'];
            $model->v_service_type = 1;
            $model->v_content = $post['is_fengpan'] == 1 ? $this->_user['u_name'] . '申请[意向金封盘]' : $this->_user['u_name'] . '申请[定金封盘]';
            $model->v_status = 0;
            $model->c_id = $model->u_id = $this->_user['u_id'];
            $model->ctime = $model->utime = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                House::setLog($post['house_uuid'], '9', $this->_user['u_name'].'进行了封盘操作', $this->_user);
                return ApiReturn::success('申请已提交，等待审核');
            } else {
                var_dump($model->getErrors());
                return ApiReturn::wrongParams('封盘失败');
            }

        } else {
            if (House::setFengpan($param)) {
                House::setLog($post['house_uuid'], '9', $this->_user['u_name'] . '进行了封盘操作', $this->_user);
                return ApiReturn::success('封盘成功');
            } else {
                return ApiReturn::wrongParams('封盘失败');
            }
        }

    }

    /***
     * 更进列表
     * @return array|\common\models\json
     */
    public function actionGetlogs()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($post['house_id']) {
                try {
                    $row = HouseLogGii::find()
                        ->alias('a')
                        ->select('a.*,b.u_name,c.d_name')->alias('a')
                        ->leftJoin('zh_user as b', 'a.c_id=b.u_id')
                        ->leftJoin('zh_depart as c', 'b.u_dept_id=c.d_id')
                        ->where(['a.house_id' => $post['house_id']])->orderBy('ctime DESC')->asArray()->all();
                    if ($row !== false) {

                        foreach ($row as $key => $item) {
                            switch ($item['hl_type']) {
                                case '1':
                                    $row[$key]['hl_type_text'] = '修改信息';
                                    break;
                                case '2':
                                    $row[$key]['hl_type_text'] = '变更状态';
                                    break;
                                case '3':
                                    $row[$key]['hl_type_text'] = '上传照片';
                                    break;
                                case '4':
                                    $row[$key]['hl_type_text'] = '公盘转私盘';
                                    break;
                                case '5':
                                    $row[$key]['hl_type_text'] = '私盘转公盘';
                                    break;
                                case '6':
                                    $row[$key]['hl_type_text'] = '设为主推';
                                    break;
                                case '7':
                                    $row[$key]['hl_type_text'] = '写跟进';
                                    break;
                                case '8':
                                    $row[$key]['hl_type_text'] = '写提醒';
                                    break;
                                case '9':
                                    $row[$key]['hl_type_text'] = '封盘';
                                    break;
                                case '10':
                                    $row[$key]['hl_type_text'] = '举报';
                                    break;
                                case '11':
                                    $row[$key]['hl_type_text'] = '修改价格';
                                    break;
                                case '12':
                                    $row[$key]['hl_type_text'] = '添加电话';
                                    break;
                                case '13':
                                    $row[$key]['hl_type_text'] = '查看电话';
                                    break;
                                case '14':
                                    $row[$key]['hl_type_text'] = '查看净价';
                                    break;
                                case '15':
                                    $row[$key]['hl_type_text'] = '修改业主';
                                    break;
                                default:
                                    $row[$key]['hl_type_text'] = '未知';
                                    break;
                            }
                        }


                        return ApiReturn::success('获取成功', $row);
                    } else {
                        return ApiReturn::wrongParams('获取失败');
                    }
                } catch (Exception $exception) {
                    var_dump($exception);
                    return ApiReturn::codeError('获取失败');
                }
            } else {
                return ApiReturn::wrongParams('参数错误');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /***
     * 查看低价
     * @return array|\common\models\json
     */
    public function actionGetdijia()
    {
        $param = Yii::$app->request->get();
        if (!empty($param['house_uuid'])) {
            $house = House::find()->where(['house_uuid' => $param['house_uuid'], 'is_del' => 0])->asArray()->one();
            if ($house) {
                House::setLog($param['house_uuid'], 14, '查看了低价', $this->_user);
                $data = ['low_sell_price' => $house['low_sell_price'], 'low_rent_price' => $house['low_rent_price']];
                return ApiReturn::success('查询成功', $data);
            } else {
                return ApiReturn::wrongParams('获取失败');
            }
        } else {
            return ApiReturn::wrongParams('获取失败');
        }
    }

    /***
     * 添加电话
     * @return array|\common\models\json
     */
    public function actionAddhousephone()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid'], 'is_del' => 0])->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }
        $housePhone = new HousePhoneGii();
        $housePhone->house_id = $post['house_uuid'];
        $housePhone->hp_phone = $post['hp_phone'];
        $housePhone->hp_customer_type = $post['hp_customer_type'];
        $housePhone->u_id = $this->_user['u_id'];
        $housePhone->utime = $housePhone->ctime = date('Y-m-d H:i:s', time());
        if ($housePhone->save()) {
            House::setLog($post['house_uuid'], '12', '添加了电话', $this->_user);
            return ApiReturn::success('添加成功');
        } else {
            return ApiReturn::wrongParams('添加失败');
        }

    }


    public function actionAddweituo()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid'], 'is_del' => 0])->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }

        //查看房源是否已经有委托了
        if($post['weituo_type'] == 2){
            $count = HouseWeituoGii::find()->where(['house_id' => $post['house_uuid'], 'weituo_type' => 2, 'hw_status' => 1, 'is_del' => 0])->andWhere(['>', 'hw_end_time', date('Y-m-d')])->count();
            if ($count > 0) {
                return ApiReturn::wrongParams('当前房源有委托，无法再添加');
            }
        }
        if(!empty($post['qianpei']) && !empty($post['qianpei_img'])){
            $house_model = House::find()->where(['house_uuid'=>$post['house_uuid'],'is_del'=>0])->one();
            $house_model -> qianpei = $post['qianpei'];
            $house_model -> qianpei_img = $post['qianpei_img'];
            $house_model -> save();
        }
        $houseWeituo = new HouseWeituoGii();
        $houseWeituo->house_id = $post['house_uuid'];
        $houseWeituo->house_sn = $post['house_sn'];
        $houseWeituo->weituo_type = $post['weituo_type'];
        $houseWeituo->weituo_image = $post['weituo_image'];
        $houseWeituo->company_id = $this->_user['company_id'];
        $houseWeituo->hw_status = 1;
        $houseWeituo->hw_d_id = end($post['departpath']);
        $houseWeituo->hw_u_id = $post['hw_u_id'];
        $houseWeituo->hw_sn = empty($post['hw_sn']) ? '' : $post['hw_sn'];
        $houseWeituo->hw_start_time = isset($post['hw_start_time']) ? $post['hw_start_time'] : '';
        $houseWeituo->hw_end_time = isset($post['hw_end_time']) ? $post['hw_end_time'] : '';
        $houseWeituo->c_id = $houseWeituo->u_id = $this->_user['u_id'];
        $houseWeituo->utime = $houseWeituo->ctime = date('Y-m-d H:i:s', time());
        if ($houseWeituo->save()) {
            if ($post['weituo_type'] == 2) {
                $house->is_dujia = '1';
                $house->is_dujia_user = $post['hw_u_id'];
                $result = $house->save();
                if ($result == false) {
                    return ApiReturn::wrongParams('添加失败');
                }
                $content = $this->_user['u_name'] . '添加了独家委托';
                $this->addhouseuser($post['house_uuid'], 6, $post['hw_u_id']);
            } else {
                $content = $this->_user['u_name'] . '添加了一般委托';
                $this->addhouseuser($post['house_uuid'], 5, $post['hw_u_id']);
            }
            House::setLog($post['house_uuid'], '16', $this->_user['u_name'] . '添加了委托', $this->_user);
            return ApiReturn::success('添加成功');
        } else {
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /**
     * 添加钥匙
     * @return array|\common\models\json
     */
    public function actionAddkey()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid'], 'is_del' => 0])->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }

        $store = User::findOne($post['hk_deyaoshiren']);

        $houseKey = new HouseKeyGii();
        $houseKey->house_id = $post['house_uuid'];
        $houseKey->hk_status = 1;
        $houseKey->hk_dian = $store->auth_sid;
        $houseKey->hk_num = $post['hk_num'];
        $houseKey->hk_shouju = $post['hk_shouju'];
        $houseKey->hk_deyaoshiren = $post['hk_deyaoshiren'];
        $houseKey->company_id = $this->_user['company_id'];
        $houseKey->c_id = $houseKey->u_id = $this->_user['u_id'];
        $houseKey->utime = $houseKey->ctime = date('Y-m-d H:i:s', time());
        if ($houseKey->save()) {
            $house->yaoshi_dian = (string)$store->auth_sid;
            $house->is_yaoshi = '1';
            $house->is_yaoshi_user = $post['hk_deyaoshiren'];
            if ($house->save()) {
                House::setLog($post['house_uuid'], '16', $this->_user['u_name'] . '添加了钥匙', $this->_user);
                $this->addhouseuser($post['house_uuid'], 4, $post['hk_deyaoshiren']);
                return ApiReturn::success('添加成功');
            } else {
                return ApiReturn::wrongParams('添加失败2');
            }


        } else {
            var_dump($houseKey->getErrors());
            return ApiReturn::wrongParams('添加失败');
        }
    }

    /*
     * 查看电话
     */
    public function actionGettel()
    {
        $get = Yii::$app->request->get();
        House::setLog($get['house_id'], '17', '查看电话', $this->_user);
        return ApiReturn::success('查看电话成功', $this->_getPhone($get['house_id']));
    }


    /***
     * 修改业主
     * @return array|\common\models\json
     */
    public function actionEdithousecustomer()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid'], 'is_del' => 0])->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }
        $house->customer_name = $post['customer_name'];
        $house->customer_sex = $post['customer_sex'];
        $house->u_id = $this->_user['u_id'];
        $house->utime = date('Y-m-d H:i:s', time());
        if ($house->save()) {
            House::setLog($post['house_uuid'], '15', '修改了业主', $this->_user);
            return ApiReturn::success('添加成功');
        } else {
            return ApiReturn::wrongParams('添加失败');
        }

    }

    /***
     * 修改低价
     * @return array|\common\models\json
     */
    public function actionEditlowprice()
    {
        $post = Yii::$app->request->post();
        $house = House::find()->where(['house_uuid' => $post['house_uuid'], 'is_del' => 0])->one();
        if (!$house) {
            return ApiReturn::wrongParams('参数错误');
        }

        $oldPrice = $house->low_sell_price;
        $house->low_sell_price = $post['low_price'];
        $house->u_id = $this->_user['u_id'];
        $house->utime = date('Y-m-d H:i:s', time());
        if ($house->save()) {
            House::setLog($post['house_uuid'], '11', $this->_user['u_name'] . '修改了低价[' . $oldPrice . '=>' . $post['low_price'] . ']', $this->_user);
            return ApiReturn::success('修改成功');
        } else {
            return ApiReturn::wrongParams('修改失败');
        }

    }

    public function actionEditdivideuser()
    {
        $post = Yii::$app->request->post();
        $user = User::find()->where(['u_id'=>$post['new_user_id']])->asArray()->one();
        if(empty($user)){
            return ApiReturn::noData('数据不存在');
        }
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $houseuser = HouseUserGii::find()->where(['id'=>$post['id']])->one();
            $houseuser->user_id = $user['u_id'];
            $houseuser->depart_id = $user['u_dept_id'];
            $houseuser->company_id = $user['company_id'];
            $houseuser->u_id = $this->_user['u_id'];
            $houseuser->utime = date('Y-m-d H:i:s');
            $result = $houseuser->save();
            if ($result === false) {
                $transaction->rollBack();
                return ApiReturn::wrongParams('修改失败');
            }
            if($post['type'] == 1){
                $house = House::find()->where(['house_uuid'=>$houseuser->house_id])->one();
                if($house->c_id == $post['old_user_id']){
                    $house->c_id = $user['u_id'];
                    $houseresult = $house->save();
                    if ($houseresult === false) {
                        $transaction->rollBack();
                        return ApiReturn::wrongParams('修改失败');
                    }
                }

            }elseif($post['type'] == 2){
                $house = House::find()->where(['house_uuid'=>$houseuser->house_id])->one();
                if($house->private_user == $post['old_user_id']){
                    $house->private_user = $user['u_id'];
                    $house->private_store = $user['u_dept_id'];
                    $house->private_company = $user['company_id'];
                    $houseresult = $house->save();
                    if ($houseresult === false) {
                        $transaction->rollBack();
                        return ApiReturn::wrongParams('修改失败');
                    }
                }
            }elseif($post['type'] == 3){
                $house = House::find()->where(['house_uuid'=>$houseuser->house_id])->one();
                if($house->is_images_user == $post['old_user_id']){
                    $house->is_images_user = $user['u_id'];
                    $houseresult = $house->save();
                    if ($houseresult === false) {
                        $transaction->rollBack();
                        return ApiReturn::wrongParams('修改失败');
                    }
                }
            }elseif($post['type'] == 4){
                $house = House::find()->where(['house_uuid'=>$houseuser->house_id])->one();
                if($house->is_yaoshi_user == $post['old_user_id']){
                    $house->is_yaoshi_user = $user['u_id'];
                    $house->yaoshi_dian = $user['u_dept_id'];
                    $houseresult = $house->save();
                    if ($houseresult === false) {
                        $transaction->rollBack();
                        return ApiReturn::wrongParams('修改失败');
                    }
                }
                $housekey = HouseKeyGii::find()->where(['house_id'=>$houseuser->house_id,'hk_deyaoshiren'=>$post['old_user_id']])->all();
                if($housekey){
                    foreach ($housekey as $key=> $keymodel){
                        $keymodel->hk_dian = $user['u_dept_id'];
                        $keymodel->hk_deyaoshiren = $user['u_id'];
                        $keyresult = $keymodel->save();
                        if ($keyresult === false) {
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('修改失败');
                        }
                    }
                }
            }elseif($post['type'] == 5){
                $weituohouse = HouseWeituoGii::find()->where(['house_id'=>$houseuser->house_id,'weituo_type'=>1,'hw_u_id'=>$post['old_user_id']])->all();
                if($weituohouse){
                    foreach ($weituohouse as $key=> $weituo){
                        $weituo->hw_u_id = $user['u_id'];
                        $weituo->hw_d_id = $user['u_dept_id'];
                        $weituoresult = $weituo->save();
                        if ($weituoresult === false) {
                            $transaction->rollBack();
                            return ApiReturn::wrongParams('修改失败');
                        }
                    }
                }
            }elseif($post['type'] == 6){
                $house = House::find()->where(['house_uuid'=>$houseuser->house_id])->one();
                if($house->is_dujia_user == $post['old_user_id']){
                    $house->is_dujia_user = $user['u_id'];
                    $houseresult = $house->save();
                    if ($houseresult === false) {
                        $transaction->rollBack();
                        return ApiReturn::wrongParams('修改失败');
                    }
                }
                $weituohouse = HouseWeituoGii::find()->where(['house_id'=>$houseuser->house_id,'weituo_type'=>2,'hw_u_id'=>$post['old_user_id']])->one();
                if($weituohouse){
                    $weituohouse->hw_u_id = $user['u_id'];
                    $weituohouse->hw_d_id = $user['u_dept_id'];
                    $weituoresult = $weituohouse->save();
                    if ($weituoresult === false) {
                        $transaction->rollBack();
                        return ApiReturn::wrongParams('修改失败');
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            return ApiReturn::wrongParams('修改失败');
        }
        return ApiReturn::success('修改成功');
    }



    protected function addhouseuser($house_uuid, $type, $user)
    {
        if ($user) {
            $userinfo = User::find()->select('u_id,u_dept_id,company_id')->where(['u_id'=>$user])->asArray()->one();
        }
        if($type == 1){
            $oldusers = HouseUserGii::find()->where(['house_id' => $house_uuid,'type'=>1,'user_id'=>$this->_user['u_id']])->one();
            if(!empty($oldusers)){
                return true;
            }
            $houseuser = new HouseUserGii();
            $houseuser->house_id = $house_uuid;
            $houseuser->type = $type;
            $houseuser->user_id = $this->_user['u_id'];
            $houseuser->depart_id = $this->_user['u_dept_id'];
            $houseuser->company_id = $this->_user['company_id'];
            $houseuser->c_id = $this->_user['u_id'];
            $houseuser->u_id = $this->_user['u_id'];
            $houseuser->ctime = date('Y-m-d H:i:s', time());
            $houseuser->utime = date('Y-m-d H:i:s', time());
            $houseuser->is_del = 0;
            $result = $houseuser->save();
            return $result;
        } elseif ($type == 4 || $type == 5) {
            $houseuser = new HouseUserGii();
            $houseuser->house_id = $house_uuid;
            $houseuser->type = $type;
            $houseuser->user_id = $type == 1 ? $this->_user['u_id'] : $user;
            $houseuser->depart_id = $type == 1 ? $this->_user['u_dept_id'] : $userinfo['u_dept_id'];
            $houseuser->company_id = $type == 1 ? $this->_user['company_id'] : $userinfo['company_id'];
            $houseuser->c_id = $this->_user['u_id'];
            $houseuser->u_id = $this->_user['u_id'];
            $houseuser->ctime = date('Y-m-d H:i:s', time());
            $houseuser->utime = date('Y-m-d H:i:s', time());
            $houseuser->is_del = 0;
            $result = $houseuser->save();
            return $result;
        } elseif ($type == 6) {
            $oldusers = HouseUserGii::find()->where(['house_id' => $house_uuid])->andWhere(['or', 'type=2', 'type=4', 'type=6'])->all();
            if(!empty($oldusers)){
                foreach ($oldusers as $key => $olduser){
                    $olduser->delete();
                }
            }
            $keyusers = HouseKeyGii::find()->where(['house_id' => $house_uuid])->all();
            if(!empty($keyusers)){
                foreach ($keyusers as $key => $keyuser){
                    $keyuser->is_del=1;
                    $keyuser->u_id=$user;
                    $keyuser->utime=date('Y-m-d H:i:s');
                }
            }
            $housekey = new HouseKeyGii();
            $housekey->house_id = $house_uuid;
            $housekey->hk_status = 1;
            $housekey->hk_dian = $userinfo['u_dept_id'];
            $housekey->hk_num = '';
            $housekey->hk_shouju = '';
            $housekey->hk_deyaoshiren = $user;
            $housekey->c_id = $user;
            $housekey->u_id = $user;
            $housekey->ctime = date('Y-m-d H:i:s');
            $housekey->utime = date('Y-m-d H:i:s');
            $housekey->is_del = 0;
            $housekey->is_del = $userinfo['company_id'];
            $result = $housekey->save();
            $data = [
                [$house_uuid, 2, $user, $userinfo['u_dept_id'], $userinfo['company_id'], $this->_user['u_id'], $this->_user['u_id'], date('Y-m-d H:i:s', time()), date('Y-m-d H:i:s', time()), 0],
                [$house_uuid, 4, $user, $userinfo['u_dept_id'], $userinfo['company_id'], $this->_user['u_id'], $this->_user['u_id'], date('Y-m-d H:i:s', time()), date('Y-m-d H:i:s', time()), 0],
                [$house_uuid, 6, $user, $userinfo['u_dept_id'], $userinfo['company_id'], $this->_user['u_id'], $this->_user['u_id'], date('Y-m-d H:i:s', time()), date('Y-m-d H:i:s', time()), 0],
            ];
            Yii::$app->db->createCommand()->batchInsert(HouseUserGii::tableName(),
                ['house_id', 'type', 'user_id', 'depart_id', 'company_id', 'c_id', 'u_id', 'ctime', 'utime', 'is_del'],
                $data
            )->execute();
        } else {
            $houseuser = HouseUserGii::find()->where(['house_id' => $house_uuid, 'type' => $type])->one();
            if ($houseuser) {
                $houseuser->user_id = $this->_user['u_id'];
                $houseuser->depart_id = $this->_user['u_dept_id'];
                $houseuser->company_id = $this->_user['company_id'];
                $houseuser->u_id = $this->_user['u_id'];
                $houseuser->utime = date('Y-m-d H:i:s', time());
            } else {
                $houseuser = new HouseUserGii();
                $houseuser->house_id = $house_uuid;
                $houseuser->type = $type;
                $houseuser->user_id = $this->_user['u_id'];
                $houseuser->depart_id = $this->_user['u_dept_id'];
                $houseuser->company_id = $this->_user['company_id'];
                $houseuser->c_id = $this->_user['u_id'];
                $houseuser->u_id = $this->_user['u_id'];
                $houseuser->ctime = date('Y-m-d H:i:s', time());
                $houseuser->utime = date('Y-m-d H:i:s', time());
                $houseuser->is_del = 0;
            }
            $result = $houseuser->save();
            return $result;
        }

    }

    /**
     * 计算楼层
     * @param $louceng
     * @param $total
     * @return string
     */
    protected function loucengFormat($louceng, $total)
    {
        $low = $base = ceil($total / 3);
        $middle = $base * 2;
        $louceng_text = '低楼层';
        if ($louceng <= $low) {
            $louceng_text = '低楼层';
        }
        if ($low < $louceng && $louceng <= $middle) {
            $louceng_text = '中楼层';
        }
        if ($middle < $louceng) {
            $louceng_text = '高楼层';
        }
        return $louceng_text;
    }

    private function _getPhone($house_id)
    {
        $house = House::find()->where(['house_id' => $house_id])->asArray()->one();
        $row = HousePhoneGii::find()->where(['house_id' => $house_id, 'is_del' => '0'])->asArray()->all();
        $data = ['customer_phone' => $house['customer_phone'], 'customer_type' => $house['customer_type']];
        $data['house_phone'] = $row;
        return $data;

    }

    /**
     * 递归获取父节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getSubNode($id, &$arr)
    {
        $arr[] = $id;
        $ret = Depart::find()->where(['d_id' => $id])->select('d_pid')->asArray()->one();
        if (!empty($ret)) {
            if ($ret['d_pid'] != 0) {
                array_unshift($arr, $ret['d_pid']);
                $this->_getSubNode($ret['d_pid'], $arr);
            }
        }
        return $arr;
    }

    private function _getYaoshidian($id)
    {
        $ret = Depart::find()->where(['d_id' => $id])->select('d_name')->asArray()->one();
        if ($ret) {
            return $ret['d_name'];
        } else {
            return '';
        }
    }


    /**
     * 递归获取父节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getChildNode($id, &$arr)
    {
        $arr[] = $id;
        $ret = Depart::find()->where(['d_pid' => $id])->select('d_id')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node['d_id'];
                $this->_getChildNode($node['d_id'], $arr);
            }
        }
        return array_unique($arr);
    }

    private function _getUsersByDeparts($departs)
    {
        $users = [];
        $result = User::find()->where(['is_del' => '0'])->andWhere(['in', 'u_dept_id', $departs])->asArray()->all();
        foreach ($result as $item) {
            $users[] = $item['u_id'];
        }
        return $users;
    }

    private function _getUsersByDepartId($d_id)
    {
        $arr = [];
        $departs = $this->_getChildNode($d_id, $arr);
        return $this->_getUsersByDeparts($departs);
    }


    private function _getRequied($type)
    {
        $result = ZhSettingRequired::find()->where(['rsetting_type' => $type, 'company_id' => $this->_user['company_id']])->asArray()->one();
        $options = json_decode($result['rsetting_options'], true);
        $values = explode(',', $result['rsetting_desp']);
        foreach ($values as $value) {
            foreach ($options as $key => $option) {
                if ($option['option_value'] == $value) {
                    unset($options[$key]);
                }
            }
        }
        $data = [];
        foreach ($options as $item) {
            $data[] = $item['option_value'];
        }
        return $data;
    }
}
