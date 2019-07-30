<?php
namespace backend\controllers;

use backend\models\Purview;
use backend\models\RoleAuth;
use backend\models\UserAuth;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;

/**
 * 功能模块控制器
 */
class PurviewController extends AuthController
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
     * 功能模块列表
     * @return \common\models\json
     */
    public function actionList()
    {
        $post = Yii::$app->request->post();

        $purviews = Purview::find()->where(['is_del'=>'0'])->asArray()->all();
        //将功能节点和用户权限匹配，r_id为角色，u_id为用户
        if(isset($post['r_id']) && $post['r_id']){
            $auths = RoleAuth::find()->select('p_id,p_type')->where(['r_id'=>$post['r_id']])->asArray()->all();
        }elseif (isset($post['u_id']) && $post['u_id']){
            $auths = UserAuth::find()->select('p_id,p_type')->where(['u_id'=>$post['u_id']])->asArray()->all();
        }
        if(!empty($auths)){
            foreach ($purviews as $kk => $purview){
                $purviews[$kk]['ishas'] = 0;
                $purviews[$kk]['data_range'] = 0;
                foreach ($auths as $auth){
                    if($purview['p_id'] == $auth['p_id']){
                        $purviews[$kk]['ishas'] = 1;
                        $purviews[$kk]['showdata'] = $auth['p_type'];
                        break;
                    }
                }
            }
        }
        //var_dump($purviews);die;

        $tree = Tools::listToTree($purviews,'p_id','p_pid','children');
        $system = [['id'=>1,'name'=>'管理后台'],['id'=>2,'name'=>'业务平台']];
        foreach ($system as $key => $value){
            foreach ($tree as $k => $item){
                if($value['id']==$item['system_type']){
                    $system[$key]['purview'][]= $item;
                }
            }
        }
        return ApiReturn::success('查询成功',$system);
    }

    /**
     * 添加功能模块
     * @return \common\models\json
     */
    public function actionUpdate()
    {
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost){
            if(isset($post['p_id']) && $post['p_id']){
                $model = Purview::findOne($post['p_id']);
                $model->uid = $this->_user['admin_id'];
                $model->utime = date('Y-m-d H:i:s');
                if ($model->load($post,'') && $model->save()) {
                    return ApiReturn::success('更新成功');
                }else{
                    return ApiReturn::wrongParams('更新失败');
                }
            }else{
                $model = new Purview();
                $model->p_name = $post['p_name'];
                $model->p_desp = $post['p_desp'];
                $model->p_url = $post['p_url'];
                $model->system_type = $post['system_type'];
                $model->p_type = trim($post['p_type']);
                $model->p_ico = trim($post['p_ico']);
                $model->p_pid = trim($post['p_pid']);
                $model->sort = trim($post['sort']);
                $model->cid = $this->_user['u_id'];
                $model->uid = $this->_user['u_id'];
                $model->ctime = date('Y-m-d H:i:s');
                $model->utime = date('Y-m-d H:i:s');
                $model->is_del = 0;
                $result = $model->save();
                if($result){
                    return ApiReturn::success('添加成功');
                }else{
                    return ApiReturn::wrongParams('添加失败');
                }
            }
        }
    }

    /**
     * 删除功能点及子节点
     * @return array|\common\models\json
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('id');

        $subnode = $this->_getSubNode($id,$arr);
        if(!empty($subnode)){
            array_unshift($subnode,$id);
        }else{
            $subnode[] = $id;
        }
        if(!$subnode){
            return ApiReturn::wrongParams('参数有误');
        }

        $depart = new Purview();
        $result = $depart->DeletePurview($subnode,$this->_user);

        if($result){
            return ApiReturn::success('删除成功');
        }else{
            return ApiReturn::wrongParams('删除失败');
        }
    }

    /**
     * 递归获取子节点
     * @param $id
     * @param $arr
     * @return array
     */
    private function _getSubNode($id, &$arr)
    {

        $ret = Purview::find()->where(['p_pid'=>$id])->select('p_id')->asArray()->all();
        if(!empty($ret[0])){
            foreach ($ret as $k => $node){
                $arr[] = $node['p_id'];
                $this->_getSubNode($node['p_id'], $arr);
            }
        }
        return $arr;
    }
}
