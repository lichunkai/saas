<?php
namespace backend\controllers;

use backend\models\ZhSettingRequired;
use common\models\ApiReturn;
use Yii;

/**
 * 普通设置控制器
 */
class SettingrequiredController extends AuthController
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
     * 必填项配置列表
     */
    public function actionGetlist()
    {

//        $aaa = json_decode('[{"option_id":"1525398587-24805","option_value":"village_name","option_text":"\u5c0f\u533a"},{"option_id":"1525398628-20660","option_value":"customer_name","option_text":"\u59d3\u540d"},{"option_id":"1525398649-21825","option_value":"customer_sex","option_text":"\u6027\u522b"},{"option_id":"1525398669-11228","option_value":"customer_phone","option_text":"\u5ba2\u6237\u7535\u8bdd"},{"option_id":"1525398688-6838","option_value":"customer_type","option_text":"\u5ba2\u6237\u7c7b\u522b"},{"option_id":"1525398703-22482","option_value":"loudong_name","option_text":"\u5ea7\u680b"},{"option_id":"1525398715-7793","option_value":"danyuan_name","option_text":"\u5355\u5143"},{"option_id":"1525398723-16367","option_value":"fanghao_name","option_text":"\u623f\u53f7"},{"option_id":"1525398734-28118","option_value":"house_title","option_text":"\u623f\u6e90\u6807\u9898"},{"option_id":"1525398743-5546","option_value":"sell_price","option_text":"\u552e\u4ef7"},{"option_id":"1525398761-3972","option_value":"jianzhumianji","option_text":"\u4f7f\u7528\u9762\u79ef"},{"option_id":"1525398780-14583","option_value":"shiyongmianji","option_text":"\u4ea7\u8bc1\u9762\u79ef"},{"option_id":"1525398805-18240","option_value":"louceng_now","option_text":"\u6240\u5728\u697c"},{"option_id":"1525398829-23384","option_value":"louceng_total","option_text":"\u603b\u697c\u5c42"},{"option_id":"1525398840-10853","option_value":"chaoxiang","option_text":"\u671d\u5411"},{"option_id":"1525398850-691","option_value":"zhuangxiu","option_text":"\u88c5\u4fee"},{"option_id":"1525398866-13078","option_value":"huxing_shi","option_text":"\u5ba4"},{"option_id":"1525398874-6392","option_value":"huxing_ting","option_text":"\u5385"},{"option_id":"1525398884-22878","option_value":"huxing_wei","option_text":"\u536b"},{"option_id":"1525398892-31865","option_value":"huxing_chu","option_text":"\u53a8"},{"option_id":"1525398900-30452","option_value":"huxing_yangtai","option_text":"\u9633\u53f0"},{"option_id":"1525398918-27155","option_value":"house_tuijian_tag","option_text":"\u63a8\u8350\u6807\u7b7e"},{"option_id":"1525398974-32566","option_value":"peitao","option_text":"\u914d\u5957"},{"option_id":"1525398984-29114","option_value":"xianzhuang","option_text":"\u73b0\u72b6"},{"option_id":"1525399000-19476","option_value":"fangwuleixing","option_text":"\u623f\u5c4b\u7c7b\u578b"},{"option_id":"1525399013-4166","option_value":"jianzhujiegou","option_text":"\u5efa\u7b51\u7ed3\u6784"},{"option_id":"1525399022-20257","option_value":"jianzaoniandai","option_text":"\u5efa\u7b51\u5e74\u4ee3"},{"option_id":"1525399031-8364","option_value":"chanquanxingzhi","option_text":"\u4ea7\u6743\u6027\u8d28"},{"option_id":"1525399040-12392","option_value":"chanzhengriqi","option_text":"\u4ea7\u8bc1\u65e5\u671f"},{"option_id":"1525399053-10254","option_value":"chanquannianxian","option_text":"\u4ea7\u6743\u5e74\u9650"},{"option_id":"1525399063-28859","option_value":"fangyuanshuifei","option_text":"\u623f\u6e90\u7a0e\u8d39"},{"option_id":"1525399083-23370","option_value":"house_tag","option_text":"\u623f\u6e90\u6807\u7b7e"},{"option_id":"1525399102-5004","option_value":"kanfangfangshi","option_text":"\u770b\u623f\u65b9\u5f0f"},{"option_id":"1525399111-29160","option_value":"laiyuan","option_text":"\u6765\u6e90"},{"option_id":"1525399123-13468","option_value":"weituobianhao","option_text":"\u59d4\u6258\u7f16\u53f7"},{"option_id":"1525399133-14748","option_value":"low_sell_price","option_text":"\u51fa\u552e\u5e95\u4ef7"},{"option_id":"1525399144-20474","option_value":"yaoshi_dian","option_text":"\u94a5\u5319\u5e97"},{"option_id":"1525399152-14006","option_value":"hk_shouju","option_text":"\u94a5\u5319\u6536\u636e\u53f71"},{"option_id":"1525399173-30508","option_value":"mark","option_text":"\u5907\u6ce8"},{"option_id":"1527648961-1619608107","option_value":"fukuanfangshi","option_text":"\u4ed8\u6b3e\u65b9\u5f0f"}]',true);
//        $bbb = json_encode($aaa,JSON_UNESCAPED_UNICODE);
//        var_dump($aaa);
//        var_dump($bbb);die;
        $row = ZhSettingRequired::find();
        $list=$row->where(['company_id'=>$this->_user['company_id']])->asArray()->all();
        $data = array();
        foreach($list as $key => $val){
            $data[$val['rsetting_type']] = $val;
        }
        return ApiReturn::success('获取成功',$data);
    }

    /**
     * 必填项配置
     */
    public function actionEdit(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingRequired();
            $result = $model->UpdateSetting($post,$this->_user);
            $message = '更新';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }else{
            return ApiReturn::wrongParams('提交失败');
        }
    }

    /**
     * 添加必填配置的选项
     */
    public function actionAddoption(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingRequired();
            $result = $model->UpdateOptionsSetting($post,$this->_user);
            $message = '更新';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }else{
            return ApiReturn::wrongParams('提交失败');
        }
    }

    /**
     * 必填选项复制
     */
    public function actionCopyoption(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingRequired();
            $result = $model->CopyOptionsSetting($post,$this->_user);
            $message = '复制';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 必填选项更新
     */
    public function actionEditoption(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingRequired();
            $result = $model->UpdateOptionsSetting($post,$this->_user());
            $message = '更新';
            if($result){
                return ApiReturn::success($message.'成功');
            }else{
                return ApiReturn::wrongParams($message.'失败');
            }
        }
    }

    /**
     * 必填选项删除
     */
    public function actionDeloption(){
        $post = Yii::$app->request->post();
        if(yii::$app->request->isPost){
            $model=new ZhSettingRequired();
            $result = $model->DelOptionsSetting($post,$this->_user());
            if($result){
                return ApiReturn::success('删除成功');
            }else{
                return ApiReturn::wrongParams('删除失败');
            }
        }
    }

    /**
     * 获取必填项所有项
     */
    /*public function actionGetoptions(){
        $post = Yii::$app->request->post();
        if(!Yii::$app->request->isPost||empty($post['rsetting_id'])){
            return ApiReturn::wrongParams('参数有误');
        }
        $setting = ZhSettingRequired::find()->where(['rsetting_id'=>$post['rsetting_id']])->asArray()->one();
        if($setting&&!empty($setting)){
            return ApiReturn::success('获取成功',json_decode($setting['rsetting_options'], true));
        }else{
            return ApiReturn::wrongParams('没有找到相关子配置');
        }
    }*/
}
