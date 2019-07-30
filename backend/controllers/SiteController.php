<?php
namespace backend\controllers;

use backend\models\Agency;
use backend\models\Broker;
use backend\models\Customer;
use backend\models\Customer_daikan;
use backend\models\Customer_daikan_house;
use backend\models\Depart;
use backend\models\House;
use backend\models\News;
use backend\models\Notice;
use backend\models\Notify;
use backend\models\OrderSell;
use backend\models\OrgCompany;
use backend\models\User;
use backend\models\UserAuth;
use backend\models\Yscustomer;
use backend\models\YsHouse;
use backend\models\YsOrder;
use common\helps\Tools;
use common\helps\Upload;
use common\helps\Zipfile;
use common\models\ApiReturn;
use common\models\gii\HouseKeyGii;
use common\models\gii\HouseWeituoGii;
use common\models\gii\ZhMessageCodeGii;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * 首页控制器
 */
class SiteController extends AuthController
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
     * 选择分类的页面
     * @return \common\models\json
     */
    public function actionLoading()
    {
        $type = Yii::$app->request->post('type');
        $u_id = $this->_user['u_id'];

        list($auth,$menu) = UserAuth::getUserAuth($u_id,$type);//用户权限及菜单
        $result['menu'] = $menu;
        $result['type'] = $type;
        return ApiReturn::success('查询成功',$result);
    }

    /**
     * loading也公告弹出
     */
    public function actionNoticepop()
    {
        $notice = Notice::find()->select('notice_id as id,notice_title as title,notice_image as image,notice_content as content')->where(['company_id'=>$this->_user['company_id'],'is_pop'=>1,'is_del'=>0])->asArray()->all();
        $news = News::find()->select('news_id as id,news_title as title,news_images as image,news_content as content')->where(['company_id'=>$this->_user['company_id'],'is_pop'=>1,'is_del'=>0])->asArray()->all();
        $data = array_merge($notice, $news);

        return ApiReturn::success('查询成功',$data);
    }

	/**
	 * @return array|\common\models\json
	 * 上传图片
	 */
	public function actionUpload(){
		$return = Upload::uploadImage();
		if($return['code']==1){
			$fullArr = explode('/', $return['data']['full']);
			$data = [
				"name" => $fullArr[count($fullArr) -1],
				"url" => $return['data']['full'] ,
				"data" =>Yii::$app->request->post(),
			];
//			var_dump($data);die;
			return ApiReturn::success('保存成功',$data);
		}else{
			return ApiReturn::wrongParams('保存失败'.$return['data']);
		}
	}

	public function actionDownload()
    {

        $zip = new Zipfile();


        $dateString = date('Ymd_');
        $rand = (string)(rand(10000,90000));
        $time = time();
        $filename = 'images-'.$dateString.md5($time.$rand).'.zip';
        $filepath = '../../data/upload/download/'.$filename; //下载的默认文件名
        $downloadurl = Yii::$app->params['img_host'].'/download/'.$filename;
        //以下是需要下载的图片数组信息，将需要下载的图片信息转化为类似即可
        $params = Yii::$app->request->post();
        $images = json_decode($params['images'],true);

        foreach($images as $k=>$v){
            $zip->add_file(file_get_contents($v['image_src']),  $v['image_name']);
            // 添加打包的图片，第一个参数是图片内容，第二个参数是压缩包里面的显示的名称, 可包含路径
            // 或是想打包整个目录 用 $zip->add_path($image_path);
        }
        $zip->output($filepath);
        if(file_exists($filepath)){
            return ApiReturn::success('保存成功',$downloadurl);
        }

    }

	/***
	 * 房屋类型
	 * @return \common\models\json
	 */
	public function actionGethousetypes(){
		return ApiReturn::success('获取成功',Yii::$app->params['HouseType']);
	}

	/***
	 * 客户类型
	 * @return \common\models\json
	 */
	public function actionGetcustomertypes(){
		return ApiReturn::success('获取成功',Yii::$app->params['CustomerType']);
	}

    /**
     * 首页搜索员工
     */
    public function actionGetstaff(){
        $param = Yii::$app->request->get();
        $row = User::find()->where(['is_del' => 0, 'u_status' => 1])->with(['depart' => function ($query) {
            $query->select(['d_id', "d_name"]);
        }])->with(['role' => function ($query) {
            $query->select(['role_id', 'role_name']);
        }]);

        if (isset($param['did']) && $param['did']) {
            $row->andWhere(['u_dept_id' => $param['did']]);
        }

        $result = $row->asArray()->all();
        $data = [];
        if($result){
            foreach($result as $val){
                $data[] = array('value' => $val['u_id'], 'label' => $val['u_name']);
            }
        }
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 二手房新增房源数，新增客源数，新增成交数
     */

    /**
     * 二手房新增房源数，新增客源数，新增成交数
     */
    public function actionGetaddmount(){
        $param = Yii::$app->request->get();

        if(empty($param['daterange'][0])||!isset($param['daterange'] )){
            $daterange=[date('Y-m',time()), date('Y-m-d',time())];
        }else{
            $daterange = $param['daterange'];
            if(is_string($daterange)){
                $daterange = explode(';', $daterange);
            }
        }

        $daterange[0] = date('Y-m-d',strtotime($daterange[0])).' 00:00:01';
        $daterange[1] = date('Y-m-d',strtotime($daterange[1])).' 23:59:59';
        $arr_days = $this->getDateFromRange($daterange[0],$daterange[1]);
        $systype = isset($param['systype']) ? $param['systype'] : 1;
        $user = $this->_user();
        $u_id = $user['u_id'];
        $ud_id = $user['u_dept_id'];
        $users_depart = $this->_getUsersByDepartId($ud_id);
        $users_depart_str = implode(',',$users_depart);
//        var_dump($users_depart_str);die;
//        if($users_depart){
//            $departCond_depart = "( `c_id` in ($users_depart_str))";
//        }
        // 查找部门类型
        $departTypeArr = array();
        $departCond = '';
        if(isset($param['did']) && $param['did']){
            if(is_string($param['did'])){
                $json = json_decode($param['did'], true);
                $searchDid = $json[0]['value'];
            }else{
                $subDidArr = $param['did'][count($param['did']) - 1];
                $searchDid = $subDidArr['value'];
            }
            $users = $this->_getUsersByDepartId($searchDid);
            $departCond = ['in', 'c_id', $users];

            /*$depart = Depart::find()->where(['d_id' => $searchDid, 'is_del' => 0])->asArray()->one();
            $condArr = [
                '1' => 'auth_rid',
                '2' => 'auth_sid',
                '3' => 'auth_aid',
                '4' => 'auth_baid',
                '5' => 'auth_cid',
            ];
            $d_type = $depart['d_type'];
            $departTypeArr = array_key_exists($d_type, $condArr) ? [$condArr[$d_type] => $depart['d_id']] : [];

            //查找子部门
            if($depart['d_type'] > 1){
                Yii::$app->redis->del('childDepartIds');
                $this->_getChildDepartIds($searchDid, $d_type);
                $childDepartIds = json_decode(Yii::$app->redis->get('childDepartIds'), true);
                foreach($childDepartIds as $childId){
                    $res = Depart::find()->where(['d_id' => $childId])->asArray()->one();
                    $res_type = array_key_exists($d_type, $condArr) ? $condArr[$res['d_type']] : '';
                    if($res_type){
                        if(array_key_exists($res_type, $departTypeArr)){
                            if(is_array($departTypeArr[$res_type])){
                                $departTypeArr[$res_type][] = $childId;
                            }else{
                                $str = $departTypeArr[$res_type];
                                $departTypeArr[$res_type] = [];
                                $departTypeArr[$res_type] = [$childId, $str];
                            }
                        }else{
                            $departTypeArr[$res_type] = [$childId];
                        }
                    }
                }
            }

            $arr = [];
            if($departTypeArr){
                foreach($departTypeArr as $key => $val){
                    if(is_array($val)){
                        $arr[] = $key.' in ('.join(',', $val).')';
                    }else{
                        $arr[] = $key.'='.$val;
                    }
                }
                $departCond = '('.join(' or ', $arr).')';
            }*/
        }

        if($systype == '2'){  // 一手房
            // 新增项目数
            $yshouseRow = YsHouse::find()->where(['is_del' => 0]);
            //if(isset($param['daterange']) && $param['daterange']){
            $yshouseRow->andFilterWhere(['between','ctime',$daterange[0], $daterange[1]]);
            //}
            if(isset($param['uid']) && $param['uid']){
                $yshouseRow->andWhere(['cid' => $param['uid']]);
            }
            $houseCount = $yshouseRow->count();
            $data['houseCount'] = $houseCount;

            // 新增客户数
            $yscustomerRow = YsCustomer::find()->where(['is_del' => 0]);
            //if(isset($param['daterange']) && $param['daterange']){
            $yscustomerRow->andFilterWhere(['between','ctime',$daterange[0], $daterange[1]]);
            //}
            if(isset($param['uid']) && $param['uid']){
                $yscustomerRow->andWhere(['c_id' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    //$houseRow->andWhere($departTypeArr);
                    //$yscustomerRow->andWhere($departTypeArr);
                    $yscustomerRow->andWhere($departCond);
                }else{
                    $yscustomerRow->andWhere(['c_id' => $u_id]);
                }
            }
            $custCount = $yscustomerRow->count();
            $data['custCount'] = $custCount;

            // 成交合同数
            $ysOrderRow = YsOrder::find()->where(['is_del' => 0]);
            //if(isset($param['daterange']) && $param['daterange']){
            $ysOrderRow->andFilterWhere(['between','ctime',$daterange[0], $daterange[1]]);
            //}
            if(isset($param['uid']) && $param['uid']){
                $ysOrderRow->andWhere(['c_id' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    //$houseRow->andWhere($departTypeArr);
                    //$ysOrderRow->andWhere($departTypeArr);
                    $ysOrderRow->andWhere($departCond);
                }else{
                    $ysOrderRow->andWhere(['c_id' => $u_id]);
                }
            }
            $orderSellCount = $ysOrderRow->count();
            $data['orderSellCount'] = $orderSellCount;

            // 最新成交时间
            $orderBy[] =  new \yii\db\Expression("deal_date desc");
            $res = YsOrder::find()->orderBy($orderBy)->asArray()->one();
            $data['orderLastDealData'] = $res ? $res['deal_date'] : '0000-00-00';
        }else{ // 二手房
            // 新增房源数---------------------------------------------

            $houseRow = House::find()->where(['is_del' => 0]);
            $houseRow->andWhere(['house_status' => 1]);  // 有效

//            $houseRow->andFilterWhere(['between','ctime',$daterange[0], $daterange[1]]);
            //$houseRow->andWhere(['house_private' => 1]); // 仅查询私盘数据
            if(isset($param['uid']) && $param['uid']){
                $houseRow->andWhere(['private_user' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    //$houseRow->andWhere($departTypeArr);
                    $houseRow->andWhere($departCond);
                }else{
                    $houseRow->andWhere(['c_id' => $u_id]);
                }
            }

            $gdHouseRow = clone $houseRow;
            $czHouseRow = clone $houseRow;
            $csHouseRow = clone $houseRow;
            $ysHouseRow = clone $houseRow;
            $djHouseRow = clone $houseRow;


            $houseCount = $houseRow->count();
            $data['houseCount'] = $houseCount;  // 房源总数

            //echo $houseRow->createCommand()->getRawSql();
            //高端
            $gdHouseCount = $gdHouseRow->andWhere(['house_private' => 1, 'sale_type' => 3])->count();
            $data['gdHouseCount'] = $gdHouseCount;

            //出售
            $csHouseCount = $csHouseRow->andWhere(['house_private' => 1, 'sale_type' => 2])->count();
            $data['csHouseCount'] = $csHouseCount;

            //出租
            $czHouseCount = $czHouseRow->andWhere(['house_private' => 1, 'sale_type' => 1])->count();
//            echo $csHouseRow->createCommand()->getRawSql();die;
            $data['czHouseCount'] = $czHouseCount;
            $data['houseCount'] = 0;  // 房源总数
            foreach($arr_days as $k=>$v){

                if($users_depart && empty($param['did'])){
                    $departCond_depart = "( `c_id` in ($users_depart_str))";
                    $sql = " SELECT count(*) as counts FROM `zh_house` WHERE (`is_del`=0) AND (`house_status`=1) AND (`sale_type`=2) AND ".$departCond_depart." AND (`ctime` BETWEEN '".$v.' 00:00:00'."' AND '".$v.' 23:59:59'."')";
                }elseif($departCond) {
                    $users_str = implode(',',$users);
                    $departCond = "(`c_id` in($users_str))";
                    $sql = " SELECT count(*) as counts FROM `zh_house` WHERE (`is_del`=0) AND (`house_status`=1) AND (`sale_type`=2) AND ".$departCond."AND (`ctime` BETWEEN '".$v.' 00:00:00'."' AND '".$v.' 23:59:59'."')";
                }else{
                    $sql = " SELECT count(*) as counts FROM `zh_house` WHERE (`is_del`=0) AND (`house_status`=1) AND  ((`house_private`=1) AND (`sale_type`=2)) AND (`ctime` BETWEEN '".$v.' 00:00:00'."' AND '".$v.' 23:59:59'."')";

                }

                $housecounts[] =  Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
//            echo $csHouseRow->createCommand()->getRawSql();die;
            foreach($housecounts as $kh=>$vh){
                $housecountlist[] = intval($vh[0]['counts']);
            }
//            var_dump($csHouseCount);die;
            // 钥匙
            $ysHouseRow = HouseKeyGii::find()->alias('a')
                ->where(['a.is_del' => '0','b.is_del'=>'0'])
                ->leftJoin('zh_house as b','a.house_id=b.house_id');
            $ysHouseRow->andWhere(['b.house_status' => 1]);  // 有效
//            $ysHouseRow->andFilterWhere(['between','a.ctime',$daterange[0], $daterange[1]]);
            //$houseRow->andWhere(['house_private' => 1]); // 仅查询私盘数据
            if(isset($param['uid']) && $param['uid']){
                $ysHouseRow->andWhere(['a.hk_deyaoshiren' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    //$houseRow->andWhere($departTypeArr);

                    $ysHouseRow->andWhere(['in','a.c_id',$users]);
//                    $ysHouseRow->andWhere($departCond);
                }else{
                    $ysHouseRow->andWhere(['a.hk_deyaoshiren' => $u_id]);
                }
            }
            //$ysHouseCount = $ysHouseRow->andWhere(['is_yaoshi' => 1])->count();
            foreach($arr_days as $ky=>$vy) {
                if ($users_depart && empty($param['did'])) {
                    $departCond_depart = "( `a`.`c_id` in ($users_depart_str))";
                    $sql = "SELECT count(*) as ys FROM `zh_house_key` `a` LEFT JOIN `zh_house` `b` ON a.house_id=b.house_id WHERE ((`a`.`is_del`='0') AND (`b`.`is_del`='0')) AND (`b`.`house_status`=1) AND " . $departCond_depart . " AND (`b`.`ctime` BETWEEN '" . $vy . ' 00:00:00' . "' AND '" . $vy . ' 23:59:59' . "') ";
                }elseif($departCond){
                    $users_str = implode(',',$users);
                    $departCond = "(`a`.`c_id` in($users_str))";
                    $sql = "SELECT count(*) as ys FROM `zh_house_key` `a` LEFT JOIN `zh_house` `b` ON a.house_id=b.house_id WHERE ((`a`.`is_del`='0') AND (`b`.`is_del`='0')) AND (`b`.`house_status`=1) AND " . $departCond . " AND (`b`.`ctime` BETWEEN '" . $vy . ' 00:00:00' . "' AND '" . $vy . ' 23:59:59' . "') ";
                }else{
                    $sql = "SELECT count(*) as ys FROM `zh_house_key` `a` LEFT JOIN `zh_house` `b` ON a.house_id=b.house_id WHERE ((`a`.`is_del`='0') AND (`b`.`is_del`='0')) AND (`b`.`house_status`=1) AND (`a`.`hk_deyaoshiren`='".$param['uid']."') AND (`b`.`ctime` BETWEEN '".$vy.' 00:00:00'."' AND '".$vy.' 23:59:59'."') ";
                }

                $yshousecounts[] =  Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
            foreach($yshousecounts as $kys=>$vys){
                $ysHouselist[] = intval($vys[0]['ys']);
            }
//            echo $ysHouseRow->createCommand()->getRawSql();die;
            $ysHouseCount = $ysHouseRow->count();
            $data['ysHouseCount'] = $ysHouseCount;

            // 独家委托
            $djHouseRow = HouseWeituoGii::find()->alias('a')
                ->leftJoin('zh_house as b','a.house_id=b.house_id');
            $djHouseRow->andWhere(['b.house_status' => 1]);  // 有效
//            $djHouseRow->andFilterWhere(['between','a.ctime',$daterange[0], $daterange[1]]);

            if(isset($param['uid']) && $param['uid']){
                $djHouseRow->andWhere(['a.hw_u_id' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
//                    $djHouseRow->andWhere($departCond);
                    $djHouseRow->andWhere(['in','a.c_id',$users]);
                }else{
                    $djHouseRow->andWhere(['a.hw_u_id' => $u_id]);
                }
            }
            //$djHouseCount = $djHouseRow->andWhere(['is_dujia' => 1])->count();
            $djHouseCount = $djHouseRow->count();
            $data['djHouseCount'] = $djHouseCount;
            foreach($arr_days as $kd=>$vd){
                if($users_depart && empty($param['did'])){
                    $departCond_depart = "( `a`.`c_id` in ($users_depart_str))";
                    $sql = "SELECT count(*) as dj FROM `zh_house_weituo` `a` LEFT JOIN `zh_house` `b` ON a.house_id=b.house_id WHERE (`b`.`house_status`=1) AND ".$departCond_depart." AND (`b`.`ctime` BETWEEN '".$vd.' 00:00:00'."' AND '".$vd.' 23:59:59'."')";
                }elseif($departCond){
                    $users_str = implode(',',$users);
                    $departCond = "(`a`.`c_id` in($users_str))";
                    $sql = "SELECT count(*) as dj FROM `zh_house_weituo` `a` LEFT JOIN `zh_house` `b` ON a.house_id=b.house_id WHERE (`b`.`house_status`=1) AND ".$departCond." AND (`b`.`ctime` BETWEEN '".$vd.' 00:00:00'."' AND '".$vd.' 23:59:59'."')";
                }else{
                    $sql = "SELECT count(*) as dj FROM `zh_house_weituo` `a` LEFT JOIN `zh_house` `b` ON a.house_id=b.house_id WHERE (`b`.`house_status`=1) AND (`a`.`hw_u_id` ='".$param['uid']."') AND (`b`.`ctime` BETWEEN '".$vd.' 00:00:00'."' AND '".$vd.' 23:59:59'."')";
                }

                $djHouseCounts[] = Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
            foreach($djHouseCounts as $kdj=>$vdj){
                $djHouselist[] = intval($vdj[0]['dj']);
            }
//            echo $djHouseRow->createCommand()->getRawSql();die;
            // 新增客源数----------------------------------------------
            $custRow = Customer::find()->where(['is_del' => 0]);
            $custRow->andWhere(['zhuangtai' => '有效']);  // 有效
//            $custRow->andFilterWhere(['between','ctime',$daterange[0], $daterange[1]]);
//            $custRow->andWhere(['customer_private' => '私客']); // 仅查询私盘数据
            foreach($arr_days as $kc=>$vc){
                if($users_depart && empty($param['did'])){
                    $departCond_depart = "( `c_id` in ($users_depart_str))";
                    $sql = "SELECT count(*) as cust FROM `zh_customer` WHERE (`is_del`=0) AND (`zhuangtai`='有效') AND ".$departCond_depart." AND(`ctime` BETWEEN '".$vc.' 00:00:00'."' AND '".$vc.' 23:59:59'."') ";
                }elseif($departCond){
                    $users_str = implode(',',$users);
                    $departCond = "(`c_id` in($users_str))";
                    $sql = "SELECT count(*) as cust FROM `zh_customer` WHERE (`is_del`=0) AND (`zhuangtai`='有效') AND ".$departCond." AND(`ctime` BETWEEN '".$vc.' 00:00:00'."' AND '".$vc.' 23:59:59'."') ";
                }else{
                    $sql = "SELECT count(*) as cust FROM `zh_customer` WHERE (`is_del`=0) AND (`zhuangtai`='有效') AND (`company_id` = ".$user['company_id'].") AND(`ctime` BETWEEN '".$vc.' 00:00:00'."' AND '".$vc.' 23:59:59'."') ";
                }

                $custCounts[] = Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
            foreach($custCounts as $kcu => $vcu){
                $custcountlist[] = intval($vcu[0]['cust']);;
            }
//            var_dump($custcountlist);die;
//            echo $custRow->createCommand()->getRawSql();die;
            if(isset($param['uid']) && $param['uid']){
                $custRow->andWhere(['c_id' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    //$custRow->andWhere($departTypeArr);
                    $custRow->andWhere($departCond);
                }else{
                    $custRow->andWhere(['c_id' => $u_id]);
                }
            }
            $mmCustRow = clone $custRow;  //买卖
            $zlCustRow = clone $custRow;  //租赁
            $gdCustRow = clone $custRow;  //高端

            $custCount = $custRow->count();
            $data['custCount'] = $custCount;

//            echo $mmCustRow->createCommand()->getRawSql();

            $mmCustCount = $mmCustRow->andWhere(['customer_type' => 0])->count();
            $zlCustCount = $zlCustRow->andWhere(['customer_type' => 1])->count();
            $gdCustCount = $gdCustRow->andWhere(['customer_type' => 2])->count();
            $data['mmCustCount'] = $mmCustCount;
            $data['zlCustCount'] = $zlCustCount;
            $data['gdCustCount'] = $gdCustCount;

            // 新成交数-----------------------------------------------------
            $orderSellRow = OrderSell::find()->where(['is_del' => 0]);
//            $orderSellRow->andFilterWhere(['between','ctime',$daterange[0], $daterange[1]]);
            $orderSellRow->andWhere(['order_status' => '进行中']);
            foreach($arr_days as $ko=>$vo){
                if($users_depart && empty($param['did'])){
                    $departCond_depart = "( `c_id` in ($users_depart_str))";
                    $sql = "SELECT count(*) as orcounts FROM `zh_order_sell` WHERE (`is_del`=0) AND  (`order_status`='进行中') AND ".$departCond_depart." AND (`company_id` = ".$user['company_id'].")  AND (`ctime` BETWEEN '".$vo.' 00:00:00'."' AND '".$vo.' 23:59:59'."') ";
                }elseif($departCond){
                    $users_str = implode(',',$users);
                    $departCond = "(`c_id` in($users_str))";
                    $sql = "SELECT count(*) as orcounts FROM `zh_order_sell` WHERE (`is_del`=0) AND  (`order_status`='进行中') AND ".$departCond." AND (`company_id` = ".$user['company_id'].")  AND (`ctime` BETWEEN '".$vo.' 00:00:00'."' AND '".$vo.' 23:59:59'."') ";
                }else{
                    $sql = "SELECT count(*) as orcounts FROM `zh_order_sell` WHERE (`is_del`=0) AND  (`order_status`='进行中') AND (`c_id`=".$param['uid'].") AND (`company_id` = ".$user['company_id'].")  AND (`ctime` BETWEEN '".$vo.' 00:00:00'."' AND '".$vo.' 23:59:59'."') ";
                }

                $orderSellcounts[] = Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
            foreach($orderSellcounts as $kor => $vor){
                $orderSelllist[] = intval($vor[0]['orcounts']);
            }
//            var_dump($orderSelllist);die;
//            echo $orderSellRow->createCommand()->getRawSql();die;
            if(isset($param['uid']) && $param['uid']){
                $orderSellRow->andWhere(['c_id' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    //$orderSellRow->andWhere($departTypeArr);
                    $orderSellRow->andWhere($departCond);
                }else{
                    $orderSellRow->andWhere(['c_id' => $u_id]);
                }
            }



            $mmOrderSellRow = clone $orderSellRow; //买卖
            $zlOrderSellRow = clone $orderSellRow; //租赁
            $gdOrderSellRow = clone $orderSellRow; //高端

            $orderSellCount = $orderSellRow->count();
            $data['orderSellCount'] = $orderSellCount;

            $mmOrderSellCount = $mmOrderSellRow->andWhere(['order_type' => 2])->count();
            $zlOrderSellCount = $zlOrderSellRow->andWhere(['order_type' => 1])->count();
            $gdOrderSellCount = $gdOrderSellRow->andWhere(['order_type' => 3])->count();
            $data['mmOrderSellCount'] = $mmOrderSellCount;
            $data['zlOrderSellCount'] = $zlOrderSellCount;
            $data['gdOrderSellCount'] = $gdOrderSellCount;

            // 最新成交时间
            $orderBy[] =  new \yii\db\Expression("order_deal_date desc");
            $res = OrderSell::find()->orderBy($orderBy)->asArray()->one();
            $data['orderLastDealData'] = $res ? $res['order_deal_date'] : '0000-00-00';

            // 房源带看--------------------------------------------------------
            $houseDaikan = Customer_daikan_house::find()->alias('h');
            $houseDaikan->select('hd.`house_sn`,hd.`house_uuid`');
            //$houseDaikan->leftJoin('zh_customer_daikan as d','d.d_id=h.d_id');
            $houseDaikan->leftJoin('zh_house as hd','hd.house_uuid=h.house_uuid');
            $houseDaikan->where(['h.is_del'=>0]);
            $houseDaikan->andWhere(['hd.house_status'=>1]);  // 有效
//            $houseDaikan->andFilterWhere(['between','h.`ctime`',$daterange[0], $daterange[1]]);

            foreach($arr_days as $khd=>$vhd){
                if ($users_depart && empty($param['did'])) {
                    $departCond_depart = "( `h`.`c_id` in ($users_depart_str))";
                    $sql = "SELECT `hd`.`house_sn`, `hd`.`house_uuid`,count(*) as hd FROM `zh_customer_daikan_house` `h` LEFT JOIN `zh_house` `hd` ON hd.house_uuid=h.house_uuid WHERE (`h`.`is_del`=0) AND (`hd`.`house_status`=1) AND (`h`.`company_id`=".$user['company_id'].") AND ".$departCond_depart." AND (`h`.`ctime` BETWEEN '".$vhd.' 00:00:00'."' AND '".$vhd.' 23:59:59'."')";
                }elseif($departCond){
                    $users_str = implode(',',$users);
                    $departCond = "(`h`.`c_id` in($users_str))";
                    $sql = "SELECT `hd`.`house_sn`, `hd`.`house_uuid`,count(*) as hd FROM `zh_customer_daikan_house` `h` LEFT JOIN `zh_house` `hd` ON hd.house_uuid=h.house_uuid WHERE (`h`.`is_del`=0) AND (`hd`.`house_status`=1) AND (`h`.`company_id`=".$user['company_id'].") AND ".$departCond." AND (`h`.`ctime` BETWEEN '".$vhd.' 00:00:00'."' AND '".$vhd.' 23:59:59'."')";
                }else{
                    $sql = "SELECT `hd`.`house_sn`, `hd`.`house_uuid`,count(*) as hd FROM `zh_customer_daikan_house` `h` LEFT JOIN `zh_house` `hd` ON hd.house_uuid=h.house_uuid WHERE (`h`.`is_del`=0) AND (`hd`.`house_status`=1) AND (`h`.`company_id`=".$user['company_id'].") AND (`h`.`c_id`=".$param['uid'].") AND (`h`.`ctime` BETWEEN '".$vhd.' 00:00:00'."' AND '".$vhd.' 23:59:59'."')";
                }

                $houseDaikancounts[] = Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
            foreach($houseDaikancounts as $khdc => $vhdc){
                $houseDaikanlist[] = intval($vhdc[0]['hd']);
            }
//            var_dump($houseDaikanlist);die;
//            echo $houseDaikan->createCommand()->getRawSql();die;
            if(isset($param['uid']) && $param['uid']){
                $houseDaikan->andWhere(['h.c_id' => $param['uid']]);
            }else{
                // 查找部门类型
                if($departCond){
                    /*$arr=[];
                    foreach($departTypeArr as $key => $val){
                        if(is_array($val)){
                            $arr[] = 'h.'.$key.' in ('.join(',', $val).')';
                        }else{
                            $arr[] = 'h.'.$key.'='.$val;
                        }
                    }
                    $departCondStr = '('.join(' or ', $arr).')';
                    $houseDaikan->andWhere($departCondStr);*/
                    $houseDaikan->andWhere(['in', 'h.c_id', $departCond[2]]);
                }else{
                    $houseDaikan->andWhere(['h.c_id' => $u_id]);
                }
            }
            $mmhouseDaikan = clone $houseDaikan;
            $czhouseDaikan = clone $houseDaikan;
            $gdhouseDaikan = clone $houseDaikan;

            $data['gdhouseDkCount'] = $gdhouseDaikan->andWhere(['hd.sale_type' => 3])->count();  // 高端
            $data['mmhouseDkCount'] = $mmhouseDaikan->andWhere(['hd.sale_type' => 2])->count();  // 买卖
            $data['czhouseDkCount'] = $czhouseDaikan->andWhere(['hd.sale_type' => 1])->count();  // 出租

            // 客源带看---------------------------------------------------------
            $custDaikan = Customer_daikan::find()->alias('cd');
            $custDaikan->select('cd.customer_uuid');
            $custDaikan->leftJoin('zh_customer as hc', 'hc.customer_uuid=cd.customer_uuid');
            $custDaikan->where(['hc.is_del' => 0]);
            $custDaikan->andWhere(['hc.zhuangtai' => '有效']); // 有效
//            $custDaikan->andFilterWhere(['between','cd.ctime',$daterange[0], $daterange[1]]);
            foreach($arr_days as $kcd=>$vcd){
                if ($users_depart && empty($param['did'])) {
                    $departCond_depart = "( `cd`.`c_id` in ($users_depart_str))";
                    $sql = "SELECT `cd`.`customer_uuid`, count(*) as cdc FROM `zh_customer_daikan` `cd` LEFT JOIN `zh_customer` `hc` ON hc.customer_uuid=cd.customer_uuid WHERE (`hc`.`is_del`=0) AND (`hc`.`zhuangtai`='有效') AND (`cd`.`company_id`=".$user['company_id'].") AND ".$departCond_depart." AND (`cd`.`ctime` BETWEEN '".$vcd.' 00:00:00'."' AND '".$vcd.' 23:59:59'."')";
                }elseif($departCond){
                    $users_str = implode(',',$users);
                    $departCond = "(`cd`.`c_id` in($users_str))";
                    $sql = "SELECT `cd`.`customer_uuid`, count(*) as cdc FROM `zh_customer_daikan` `cd` LEFT JOIN `zh_customer` `hc` ON hc.customer_uuid=cd.customer_uuid WHERE (`hc`.`is_del`=0) AND (`hc`.`zhuangtai`='有效') AND (`cd`.`company_id`=".$user['company_id'].") AND ".$departCond." AND (`cd`.`ctime` BETWEEN '".$vcd.' 00:00:00'."' AND '".$vcd.' 23:59:59'."')";
                }else{
                    $sql = "SELECT `cd`.`customer_uuid`, count(*) as cdc FROM `zh_customer_daikan` `cd` LEFT JOIN `zh_customer` `hc` ON hc.customer_uuid=cd.customer_uuid WHERE (`hc`.`is_del`=0) AND (`hc`.`zhuangtai`='有效') AND (`cd`.`company_id`=".$user['company_id'].") AND (`cd`.`c_id`=".$param['uid'].") AND (`cd`.`ctime` BETWEEN '".$vcd.' 00:00:00'."' AND '".$vcd.' 23:59:59'."')";
                }

                $custDaikancounts[] = Yii::$app->getDb()->createCommand($sql)->queryAll();
            }
            foreach($custDaikancounts as $kcdc => $vcdc){
                $custDaikanlist[] = intval($vcdc[0]['cdc']);
            }
//            echo $custDaikan->createCommand()->getRawSql();die;
            if(isset($param['uid']) && $param['uid']){
                $custDaikan->andWhere(['cd.c_id' => $param['uid']]);
            }else{
                // 查找部门类型
                $arr= [];
                //if($departTypeArr){
                if($departCond){
                    /*foreach($departTypeArr as $key => $val){
                        if(is_array($val)){
                            $arr[] = 'hc.'.$key.' in ('.join(',', $val).')';
                        }else{
                            $arr[] = 'hc.'.$key.'='.$val;
                        }
                    }
                    $departCondStr = '('.join(' or ', $arr).')';
                    $custDaikan->andWhere($departCondStr);*/
                    $custDaikan->andWhere(['in', 'cd.c_id', $departCond[2]]);
                }else{
                    $custDaikan->andWhere(['cd.c_id' => $u_id]);
                }
            }
            $mmcustDaikan = clone $custDaikan;
            $zlcustDaikan = clone $custDaikan;
            $gdcustDaikan = clone $custDaikan;
            $data['mmCustDkCount'] = $mmcustDaikan->andWhere(['hc.customer_type' => 0])->count();
            $data['zlCustDkCount'] = $zlcustDaikan->andWhere(['hc.customer_type' => 1])->count();
            $data['gdCustDkCount'] = $gdcustDaikan->andWhere(['hc.customer_type' => 2])->count();

        }

        // 搜索时间段
        $data['defaultDaterange'] = $daterange;
        $legend = array('房源总数','买卖客源总数','买卖成交总数','收钥匙数','独家委托总数','房源带看数','客源带看数');

        $data['fyseries'] = $housecountlist;
        $data['kyseries'] = $custcountlist;
        $data['cjseries'] = $orderSelllist;
        $data['keyseries'] = $ysHouselist;
        $data['dujiaseries'] = $djHouselist;
        $data['fydkseries'] = $houseDaikanlist;
        $data['kydkseries'] = $custDaikanlist;
        $data['legend'] = $legend;
        $data['days'] = $arr_days;
        return ApiReturn::success('获取成功', $data);
    }


    /**
     * 二手房新增房源数，新增客源数，新增成交数
     */
    public function actionDealmount(){
        $m = date('Y-m-d', mktime(0,0,0,date('m'),1,date('Y')));
        $t = date('t',strtotime($m)); //月共多少天

        $start = date('Y-m-d', mktime(0,0,0,date('m'),1,date('Y')));
        $end = date('Y-m-d', mktime(0,0,0,date('m'),$t,date('Y')));
        //echo 15*24*3600;
        //echo 30*24*3600;
//        $start_time=strtotime($start);
//        $jieshu_time=strtotime($end);

        $user = $this->_user();
        $u_id = $user['u_id'];
        $ud_id = $user['u_dept_id'];
        $users_depart = $this->_getUsersByDepartId($ud_id);
        $departCond = ['in', 'c_id', $users_depart];
//        var_dump($u_id,$departCond);die;
        $houseRow = House::find()->where(['is_del' => 0]);
        $houseRow->andWhere(['house_status' => 1]);  // 有效
        $houseRow->andWhere($departCond);
        $houseRow->andFilterWhere(['between','ctime',$start." 00:00:00", $end." 23:59:59"]);
        $csHouseRow = clone $houseRow;
        $csHouseCount = $houseRow->andWhere(['house_private' => 1, 'sale_type' => 2])->count();
        $data['csHouseCount'] = $csHouseCount;
//        var_dump($data);die;
//        echo $csHouseRow->createCommand()->getRawSql();die;
        $custRow = Customer::find()->where(['is_del' => 0]);
        $custRow->andWhere(['zhuangtai' => '有效']);  // 有效
        $custRow->andWhere($departCond);
        $custRow->andFilterWhere(['between','ctime',$start." 00:00:00", $end." 23:59:59"]);
        $mmCustRow = clone $custRow;  //买卖
        $custCount = $custRow->count();
        $data['custCount'] = $custCount;
        $mmCustCount = $mmCustRow->andWhere(['customer_type' => 0])->count();
        $data['mmCustCount'] = $mmCustCount;

        $orderSellRow = OrderSell::find()->where(['is_del' => 0,'order_status'=>'进行中'])->select('sum(order_price) as sums');
        $orderSellRow->andFilterWhere(['between','ctime',$start." 00:00:00", $end." 23:59:59"]);
//        $orderSellRow->andWhere($departCond);
        $mmOrderSellRow = clone $orderSellRow; //买卖
        $orderSellCount = $orderSellRow->count();
        $data['orderSellCount'] = $orderSellCount;
        $mmOrderSellCount = $mmOrderSellRow->andWhere(['order_type' => 2])->asArray()->one();
        $data['mmOrderSellCount'] = $mmOrderSellCount['sums'];

        return ApiReturn::success('获取成功', $data);

    }

    // 获取未读信息条数
    public function actionGetmessagecount(){
        $data['setMessageCount'] = Notify::find()->where(['n_is_read' => 0])->count();
        return ApiReturn::success('获取成功', $data);
    }

    // 获取可看组织架构
    public function actionGetdepttree(){
        $user = $this->_user();
        $userdepart = Depart::find()->where(['d_id' => $user['u_dept_id'], 'is_del' => 0])->select('d_id as value,d_name as label,d_pid')->asArray()->one();
        Yii::$app->redis->set('departTree', json_encode([$userdepart]));
        $field = 'd_id as value,d_name as label,d_pid';
        $this->_getChildDepart($userdepart['value'], $field);
        $departlist = json_decode(Yii::$app->redis->get('departTree'), true);
        $data['departlist'] = Tools::listToSubTree($departlist, 'value', 'd_pid', 'children');
        return ApiReturn::success('获取成功', $data);
    }

    /**
     * 个人排行月
     */
    public function actionStaffmonth(){
        House::find()->where()->count();
    }


    /** * 递归获取父节点 * @param $id * @param $arr * @return array */
    private function _getChildNode($id, &$arr) {
        $arr[] = $id;
        $ret = Depart::find()->where(['d_pid' => $id])->select('d_id')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node['d_id'];
                $this->_getChildNode($node['d_id'], $arr);
            }
        } return array_unique($arr);
    }

    private function _getUsersByDeparts($departs) {
        $users = [];
        $result = User::find()->where(['is_del' => '0'])->andWhere(['in', 'u_dept_id', $departs])->asArray()->all();
        foreach ($result as $item) {
            $users[] = $item['u_id'];
        }
        return $users;
    }

    private function _getUsersByDepartId($d_id) {
        $arr = [];
        $departs = $this->_getChildNode($d_id, $arr);
        return $this->_getUsersByDeparts($departs);
    }

    private function getDateFromRange($startdate, $enddate){
        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);
        $houseRow = House::find()->where(['is_del' => 0]);
        $houseRow->andWhere(['house_status' => 1]);  // 有效
        // 计算日期段内有多少天
        $days = ($etimestamp-$stimestamp)/86400;
        // 保存每天日期
        $date = array();
        for($i=0; $i<$days; $i++){
            $date[] = date('Y-m-d', $stimestamp+(86400*$i));


        }

        return $date;

    }

}
