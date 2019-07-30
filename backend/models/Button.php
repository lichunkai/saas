<?php

namespace backend\models;

use common\models\gii\ButtonGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Button extends ButtonGii
{
    public $button = [];

    /**
     * 获取所有按钮，并按照页面分组
     * @return array
     */
    public static function getButton(){
        $result = Button::find()->select('b_id,b_name,b_desc,b_html,b_url,b_type')->where(['is_del'=>0])->asArray()->all();
        foreach ($result as $key => $item){
            if(isset($buttons[$item['b_html']])){
                $buttons[$item['b_html']][] = $item;
            }else{
                $buttons[$item['b_html']][] = $item;
            }
        }
        return $buttons;
    }
}
