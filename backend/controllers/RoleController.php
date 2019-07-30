<?php
namespace backend\controllers;

use backend\models\Purview;
use backend\models\Role;
use backend\models\RoleAuth;
use backend\models\UserAuth;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 用户角色模块控制器
 */
class RoleController extends AuthController
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
     * 用户角色模块列表
     * @return \common\models\json
     */
    public function actionRolelist()
    {
        $result['rolelist'] = Role::find()->where(['company_id'=>$this->_user['company_id'],'is_del'=>'0'])->asArray()->all();
        $result['rolelist'] = Yii::$app->LoadData->listButton($this->id,$result['rolelist'],$this->_user);
        $result['purview'] = Purview::getPurviewList();
        $result['roletype'] = Yii::$app->params['roleType'];
        $result['dataauth'] = Yii::$app->params['dataAuth'];
        $result['showtab'] = array_keys($result['purview'])[0];
        //权限循环
        $connection=Yii::$app->db;
        $purviewdata = $connection->createCommand('SELECT p_id,p_type,0 as type_value FROM zh_purview WHERE is_auth=1 AND is_del=0 AND p_pid<>0')->queryAll();
        $purviewdata = ArrayHelper::index($purviewdata,'p_id');
        foreach ($purviewdata as $k => $data){
            if($data['p_type'] == 1){
                $result['purviewdata'][$data['p_id']] = false;
            }else{
                $result['purviewdata'][$data['p_id']] = (int)$data['type_value'];
            }

        }
        //var_dump($result['purviewdata']);die;
        foreach ($result['rolelist'] as $key =>$item){
            $roleAuth = $connection->createCommand('SELECT p_id,data_range FROM zh_role_auth WHERE company_id='.$this->_user['company_id'].' and r_id='.$item['role_id'])->queryAll();
            $roleAuth = ArrayHelper::map($roleAuth,'p_id','data_range');
            $result['rolelist'][$key]['rolepurview'] =$result['purviewdata'];
            foreach ($roleAuth as $k => $priview){
                if (isset($purviewdata[$k])) {
                    if($purviewdata[$k]['p_type'] == 1){
                        $result['rolelist'][$key]['rolepurview'][$k] = $priview ==6 ? true : false;
                    }else{
                        $result['rolelist'][$key]['rolepurview'][$k] = (int)$priview;
                    }
                }
            }
        }
        $result['topbutton'] = Yii::$app->LoadData->topButton($this->id,$this->_user);

        return ApiReturn::success('查询成功',$result);
    }

    /**
     * 添加用户角色
     * @return \common\models\json
     */
    public function actionUpdaterole()
    {
        $post = Yii::$app->request->post();//var_dump($post);die;
        if (Yii::$app->request->isPost){
            $model = new Role();
            $result = $model->UpdateRole($post,$this->_user);
            $message = '添加';
            if(isset($post['r_id']) && $post['r_id']){
                $message = '更新';
            }
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 删除用户角色
     * @return array|\common\models\json
     */
    public function actionDelrole()
    {
        $id = Yii::$app->request->post('id');
        $shop = new Role();
        $result = $shop->DeleteRole($id,$this->_user);
        if($result){
            return ApiReturn::success('删除成功');
        }else{
            return ApiReturn::success('删除失败');
        }
    }
}
