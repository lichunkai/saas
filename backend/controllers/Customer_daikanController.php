<?php
namespace backend\controllers;

use backend\models\Customer_daikan;
use backend\models\Customer_daikan_house;
use backend\models\Depart;
use backend\models\Customer;
use backend\models\User;
use backend\models\House;
use common\models\ApiReturn;
use backend\models\Customer_log;
use common\models\CommSetting;
use common\helps\Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 买卖客源
 */
class Customer_daikanController extends AuthController
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
    /*
     *买卖客源带看列表
     */
	public function actionIndex(){
		$param = Yii::$app->request->get();
		$page = isset($param["page"])&&$param["page"] ? $param["page"] : 0;
		$pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
		$start = ($page-1)*$pagesize;

		$row= Customer_daikan_house::find()->alias('h');
		if(Yii::$app->request->isGet){			
			$row->select('hd.`house_sn`,hd.`house_title`,hd.`sale_type`,hd.`house_uuid`,hd.sale_type,h.khpj,c.customer_name,c.xuqiubianhao,c.customer_type,c.daikancishu,d.*');
		}
		$row->leftJoin('zh_customer_daikan as d','d.d_id=h.d_id');
		$row->leftJoin('zh_house as hd','hd.house_uuid=h.house_uuid');
		$row->leftJoin('zh_customer as c', 'c.customer_uuid=h.customer_uuid');
		$row->where(['d.company_id'=>$this->_user['company_id'],'d.is_del'=>0]);
		if(isset($param['customer_uuid'])){
			$row->andwhere(['d.customer_uuid'=>$param['customer_uuid']]);
		}

		if(isset($param['house_uuid'])){
			$row->andwhere(['h.house_uuid'=>$param['house_uuid']]);
		}

		$post = Yii::$app->request->post(); // POST
		if(Yii::$app->request->isPost){			
			if(isset($post['daikan']) && $post['daikan'] == 'cust'){
				$row->select('c.customer_uuid, c.customer_name,c.xuqiubianhao,c.customer_type,d.c_id,c.daikancishu,d.ctime,d.d_pingjia')->distinct(true);
			}else{				
				$row->select('hd.`house_sn`,hd.`house_uuid`,hd.sale_type,hd.daikancishu,c.customer_name,c.xuqiubianhao,c.customer_type,d.*');
			}
			$page = isset($post["page"])&&$post["page"] ? $post["page"] : 0;
			$pagesize = isset($post["pagesize"])&&$post["pagesize"] ? $post["pagesize"] : 10;
			$start = ($page-1)*$pagesize;
		}
		if (isset($post["f_type"]) && $post["f_type"]) { //房源类型
			$row->andWhere(['hd.sale_type' => trim( $post['f_type'])]);
		}
		if (isset($post["c_type"]) && $post["c_type"] != '') { //客源类型
			$row->andWhere(['c.customer_type' => trim( $post['c_type'])]);
		}
		if (isset($post["keywd"]) && $post['keywd']) { //房号
			$row->andWhere("hd.house_sn like '%" . $post["keywd"]."%'".
				" or hd.village_name like '%" . $post["keywd"]."%'");
		}
		if (isset($post['dateRange']) && $post['dateRange']){ // 时间段
			$daterange = $post['dateRange'];
			if($daterange[1] != 'undefined'){
				$row->andFilterWhere(['between','h.ctime',$daterange[0].' 00:00:01', $daterange[1].' 23:59:59']);
			}
		}
		if(isset($post['u_id']) && $post['u_id']){
			$row->andWhere([ 'h.c_id' => $post['u_id']]);
		}else{
			if(isset($post['departpath'])&&is_array($post['departpath'])){ //判断部门
				$users = $this->_getUsersByDepartId(end($post['departpath']));
				$row->andWhere([ 'in', 'h.c_id',$users]);
			}
		}

		$list=$row->orderBy('ctime DESC')->limit($pagesize)->offset($start)->asArray()->all();		
		foreach($list as $key=>$v){
			if($v['c_id']){
				$user= User::find()->where(['u_id'=>$v['c_id'],'is_del'=>0])->asArray()->one();
				if($user['u_dept_id']){
					$depart= Depart::find()->where(['d_id'=>$user['u_dept_id'],'is_del'=>0])->asArray()->one();
					$list[$key]['bumen']=$depart['d_name'];
				}
				$list[$key]['daikanren']=$user['u_name'];

				// 一个房源的带看客源数
				$list[$key]['fyCountCust'] = (isset($post['daikan']) && $post['daikan'] == 'cust') ? 0 : Customer_daikan_house::find()->select('customer_uuid, house_uuid, c_id')
					->where(['is_del' => 0, 'house_uuid' => $v['house_uuid']])->count();

				// 一个客源的带看房源数
				$list[$key]['cstCountFy'] = Customer_daikan_house::find()->select('customer_uuid, house_uuid, c_id')
					->where(['is_del' => 0, 'customer_uuid' => $v['customer_uuid']])->count();
			}
		}
		$data['list'] = $list;
		$data['count'] = $row->count();
		return ApiReturn::success('查询成功',$data);
	}

    /**
     * 根据房源/客源查看带看列表
     */
    public function actionDaikan(){
        $param = Yii::$app->request->post();
	    $page = isset($param["page"]) && $param["page"] && $param["page"] !='undefined' ? $param["page"] : 0;
        $pagesize = isset($param["pagesize"])&&$param["pagesize"] ? $param["pagesize"] : 10;
        $start = ($page-1)*$pagesize;

        $row= Customer_daikan_house::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);

        $post = Yii::$app->request->post();
        if (isset($post["customer_uuid"]) && $post["customer_uuid"]) { //客源
            $row->andWhere(['customer_uuid' => trim( $post['customer_uuid'])]);
        }
        if (isset($post["house_uuid"]) && $post["house_uuid"]) { //房源
            $row->andWhere(['house_uuid' => trim( $post['house_uuid'])]);
        }

        $list = $row->asArray()->all();
        $ids = [];
        foreach($list as $key => $val){
            $ids['customer_uuid'][] = $val['customer_uuid'];
            $ids['house_uuid'][] = $val['house_uuid'];
        }

        $row = [];
        if (isset($post["customer_uuid"]) && $post["customer_uuid"]) { //一个客源的带看房源
            $row = House::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
            $row->andWhere(['in', 'house_uuid', $ids['house_uuid']]);
        }

        if (isset($post["house_uuid"]) && $post["house_uuid"]) { //一个房源的带看客源
            $row = Customer::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']]);
            $row->andWhere(['in', 'customer_uuid', $ids['customer_uuid']]);
        }

        if($row){
            $data['list'] = $row->limit($pagesize)->offset($start)->asArray()->all();
            $data['count'] = $row->count();
            return ApiReturn::success('查询成功',$data);
        }else{
            return ApiReturn::wrongParams('查询错误');
        }

    }

    /*
   * 添加客源带看
   */
    public function actionAdd(){	
		
        $post=Yii::$app->request->post();		
	
        if(!empty($post)){
           			
			foreach($post['house_uuid'] as $key=>$v){	
				 $model=new Customer_daikan;
				 $model->d_pingjia= $post['d_pingjia'];
				 $model->customer_uuid= $post['customer_uuid'];
				 $user=$this->_user();
				 $model->c_id = $user['u_id'];
				 $model->u_id = $user['u_id'];
				 $model->company_id = $this->_user['company_id'];
				 $model->ctime = date('Y-m-d H:i:s');
				 $model->utime = date('Y-m-d H:i:s');
				 $model->auth_cid = $user['auth_cid'];
				 $model->auth_rid = $user['auth_rid'];
				 $model->auth_sid = $user['auth_sid'];
				 $model->auth_aid = $user['auth_aid'];
				 $model->auth_baid = $user['auth_baid'];
				 $model->d_pingjia = $v['khpj'];
				 $result = $model->save();				 
			}
			
            if(!empty($post['house_uuid'])){
            	if(isset($post['xcx'])&&!empty($post['xcx'])){
            		$house_uuids=json_decode($post['house_uuid'],true);
            		if($house_uuids){
			            $post['house_uuid']=[];
            			foreach ($house_uuids as $key=>$v){
				            $post['house_uuid'][$key]['house_uuid']=$v;
			            }
		            }
	            }


                foreach($post['house_uuid'] as $key=>$v){
                    $modelhouse=Customer_daikan_house::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'d_id'=>$model->d_id,'house_uuid'=>$v['house_uuid'],'customer_uuid'=>$post['customer_uuid']])->one();
                    if(empty($modelhouse)){
						$house=House::find()->where(['house_uuid'=>$v['house_uuid']])->one();						
                        $house->daikancishu=$house->daikancishu+1;
                        $house->daikanshijian=date('Y-m-d H:i:s');
                        $house->save();
                        $modelhouse =new Customer_daikan_house();
                        $modelhouse->d_id=$model->d_id;
                        $modelhouse->house_uuid=$v['house_uuid'];
                        $modelhouse->customer_uuid=$post['customer_uuid'];
                        $modelhouse->c_id = $user['u_id'];
                        $modelhouse->u_id = $user['u_id'];
                        $modelhouse->company_id = $this->_user['company_id'];
                        $modelhouse->ctime = date('Y-m-d H:i:s');
                        $modelhouse->utime = date('Y-m-d H:i:s');
                        $modelhouse->auth_cid = $user['auth_cid'];
                        $modelhouse->auth_rid = $user['auth_rid'];
                        $modelhouse->auth_sid = $user['auth_sid'];
                        $modelhouse->auth_aid = $user['auth_aid'];
                        $modelhouse->auth_baid = $user['auth_baid'];
						$modelhouse->khpj = $v['khpj'];
                        $modelhouse = $modelhouse->save();
	                    House::setLog($v['house_uuid'],'20','添加了带看',$user);
                    }
					
					
                }
            }
            $cus=Customer::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'customer_uuid'=>$post['customer_uuid']])->one();
            $cus->daikancishu=$cus->daikancishu+count($post['house_uuid']);
            $cus->daikanshijian=date('Y-m-d H:i:s');
            $cus->save();
            //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)
            $Customer_log=new Customer_log();
            $Customer_log->log($post['customer_uuid'],7,'添加带看',$this->_user());
            if($result){
                return ApiReturn::success('保存成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
        }
    }
    /*
     * 编辑客源带看
     */
    public function actionEdit(){
        $post=Yii::$app->request->post();
        if(!empty($post)){
            $model= Customer_daikan::find()->where(['d_id'=>$post['d_id'],'company_id'=>$this->_user['company_id']])->one();
            $model->img= $post['img'];
            $model->d_pingjia= $post['d_pingjia'];
            $model->customer_uuid= $post['customer_uuid'];
            $user=$this->_user();
            $model->c_id = $user['u_id'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if(!empty($post['house_uuid'])){
                foreach($post['house_uuid'] as $key=>$v){
                    $modelhouse=Customer_daikan_house::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'d_id'=>$post['d_id'],'house_uuid'=>$v,'customer_uuid'=>$post['customer_uuid']])->all();
                    if(empty($modelhouse)){
                        $modelhouse =new Customer_daikan_house();
                        $modelhouse->d_id=$post['d_id'];
                        $modelhouse->house_uuid=$v;
                        $modelhouse->customer_uuid=$post['customer_uuid'];
                        $modelhouse->c_id = $user['u_id'];
                        $modelhouse->u_id = $user['u_id'];
                        $modelhouse->ctime = date('Y-m-d H:i:s');
                        $modelhouse->utime = date('Y-m-d H:i:s');
                        $modelhouse = $modelhouse->save();
                    }else{
                        if($modelhouse){
                            foreach ($modelhouse as $k => $v) {
                                $v->is_del = 1;
                                $v->update();
                            }
                        }
                        $modelhouse=Customer_daikan_house::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id'],'d_id'=>$post['d_id'],'house_uuid'=>$v,'customer_uuid'=>$post['customer_uuid']])->one();
                        if($modelhouse &&  $modelhouse->is_del){
                            $modelhouse->is_del=0;
                            $modelhouse->save();
                        }else{
                            $modelhouse =new Customer_daikan_house();
                            $modelhouse->d_id=$post['d_id'];
                            $modelhouse->house_uuid=$v;
                            $modelhouse->company_id=$this->_user['company_id'];
                            $modelhouse->customer_uuid=$post['customer_uuid'];
                            $modelhouse->c_id = $user['u_id'];
                            $modelhouse->u_id = $user['u_id'];
                            $modelhouse->ctime = date('Y-m-d H:i:s');
                            $modelhouse->utime = date('Y-m-d H:i:s');
                            $modelhouse = $modelhouse->save();
                        }
                    }
                }
            }
            if($result){
                return ApiReturn::success('保存成功');
            }else{
                return ApiReturn::wrongParams('保存失败');
            }
        }else{
            return ApiReturn::wrongParams('保存失败');
         }

    }
    /*
 * 删除
 */
    public function actionDel(){
        $post = Yii::$app->request->post();
        if(empty($post['f_id'])){
            return ApiReturn::wrongParams('数据异常');
        }
        $customer_follow = Customer_follow::findOne($post['f_id']);
        $customer_follow->is_del = 1;
        $result = $customer_follow->update();
        //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)
        $Customer_log=new Customer_log();
        $Customer_log->log($customer_follow['customer_uuid'],8,'删除带看',$this->_user());
        if($result){
            return ApiReturn::success('删除成功');
        }else{
            return ApiReturn::wrongParams('删除失败');
        }
    }

    private function _getUsersByDeparts($departs){
        $users =[];
        $result = User::find()->where(['is_del'=>0,'company_id'=>$this->_user['company_id']])->andWhere(['in','u_dept_id',$departs])->asArray()->all();
        foreach ($result as $item){
            $users[] = $item['u_id'];
        }
        return $users;
    }

    private function _getUsersByDepartId($d_id){
        $arr=[];
        $departs = $this->_getChildNode($d_id,$arr);
        return $this->_getUsersByDeparts($departs);
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
        $ret = Depart::find()->where(['d_pid' => $id,'company_id'=>$this->_user['company_id']])->select('d_id')->asArray()->all();
        if (!empty($ret[0])) {
            foreach ($ret as $k => $node) {
                $arr[] = $node['d_id'];
                $this->_getChildNode($node['d_id'], $arr);
            }
        }
        return array_unique($arr);
    }

}