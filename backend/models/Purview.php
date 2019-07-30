<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\PurviewGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Purview extends PurviewGii
{
    public static function getPurviewName($url)
    {
        $action = static::find()->where(['p_url'=>$url,'is_del'=>0])->select('p_name')->one();
        if($action){
            return $action->p_name;
        }else{
            return '';
        }
    }

    /**
     * 获取权限设置的展示页数据
     */
    public static function getPurviewList()
    {
        $purview = Purview::find()->where(['is_auth'=>1,'is_del'=>'0'])->orderBy('sort asc')->asArray()->all();
        $actionlength  = Purview::find()->select('system_type,count(p_id) as number')->where(['is_auth'=>1,'is_del'=>'0'])->andWhere(['<>','p_pid',0])->groupBy('p_pid')->asArray()->all();
        ArrayHelper::multisort($actionlength, 'number', SORT_ASC);
        $actionlength = ArrayHelper::map($actionlength,'system_type','number');

        //var_dump($actionlength);die;
        $systems = Yii::$app->params['systemType'];
        foreach ($systems as $key => $system){
            $tmppurviews[$system['name']]['length'] = isset($actionlength[$system['id']]) ? $actionlength[$system['id']] : 0;
            $tmppurviews[$system['name']]['list'] = [];
            foreach ($purview as $k => $item){
                if($system['id']==$item['system_type']){
                    $tmppurviews[$system['name']]['list'][]= $item;
                }
            }
        }
        //var_dump($tmppurviews);die;
        foreach ($tmppurviews as $kk => $tmppurview){
            $treedata = Tools::listToTree($tmppurview['list'], 'p_id', 'p_pid', 'action');//var_dump($treedata);die;
            foreach ($treedata as $kkk => $tree){
                $length = count($tree['action']);
                if($tmppurview['length'] > $length){
                    $max = $tmppurview['length'] - $length;
                    for ($i=1;$i<=$max; $i++){
                        array_push($treedata[$kkk]['action'],['p_type'=>2]);
                    }
                }
            }
            //var_dump($treedata);
            $purviewBySystem[$kk] = $treedata;
        }

        //die;
        return $purviewBySystem;
    }
}

