<?php

namespace common\models;

use common\models\gii\CommSettingGii;


class CommSetting extends CommSettingGii
{
    /**
     * 获取系统参数设置
     * @param $company_id int 公司ID
     * @return array 设置数据组
    */
    public static function getCommSetting($company_id){
        $settingTypes=[
            'chaoxiang'=>'1',//朝向
            'yaoshi'=>'2',//钥匙
            'zhuangxiu'=>'3',//装修
            'wuyeleixing'=>'4',//物业类型
            'panyuanleixin'=>'5',//盘源类型
            'suoshuleixin'=>'6',//所属类型
            'chanquanxingzhi'=>'7',//产权类型
            'chuanquanxianzhuang'=>'8',//产权现状
            'shuifeizhengce'=>'9',//税费政策
            'fukuanfangshi_shou'=>'10',//付款方式
            'weituofangshi'=>'11',//委托方式
            'fangyuanbiaoqian'=>'12',//房源标签
            'leixing'=>'13',//类型
            'jibie'=>'14',//级别
            'mudi'=>'15',//目的
            'kehuleixing'=>'16',//客户类型
            'gengjingjieduan'=>'17',//客源跟进阶段
            'laiyuan'=>'18',//来源
            'kehubiaoqian'=>'19',//客户标签
            'huxing'=>'20',//户型
            'fukuanfangshi_zu'=>'21',//租房付款方式
            'yajinyaoqiu' => '22',//押金要求
            'qiuzuyaoqiu' => '23', //求租类型
            'jiasi' => '24', // 家私
            'zhoubianpeitao' => '25', //周边配套
            'house_tags' => '26',  // 房源标签
            'house_genjinjieduan' => '27',  // 房源跟进状态
            'shop_type' => '28',  // 房源跟进状态
        ];
        $settings = [];
        foreach ($settingTypes as $key=>$item){
            $settings[$key] = static::find()->select('id,type,company_id,value')
                ->where(['OR','company_id=:company_id','company_id=:system_id'],[':company_id'=>$company_id,':system_id'=>'0'])
                ->andWhere(['type' => $item])->asArray()->all();
        }
        return $settings;
    }
}
