<?php
namespace console\controllers;
use backend\models\Purview;
use backend\models\SystemLog;
use Yii;
use yii\console\Controller;
use common\models\ApiReturn;
use common\helps\Tools;


/**
 * 缓存日志写入Mysql数据库控制器
 */
class LogcacheController extends Controller
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
     *读取redis数据写入文档
     */
    public function actionIndex()
    {
        $length = 5000;
        $redislength = Yii::$app->redis->llen('sass_log');
        if($length > $redislength){
            $redisInfo = Yii::$app->redis->lrange('sass_log', 0, $redislength);
        }else{
            $redisInfo = Yii::$app->redis->lrange('sass_log', 0, $length);
        }
//        var_dump($length);
//        var_dump($redislength);die;

        foreach ($redisInfo as $key => $item){
            $logdata[$key] = json_decode($item,true);
            $action = Purview::getPurviewName($logdata[$key]['log_url']);
            if(empty($action)){
                unset($logdata[$key]);
                continue;
            }
            $logdata[$key]['log_param'] = json_encode($logdata[$key]['log_param']);
            $logdata[$key]['log_desp'] = '['.$logdata[$key]['log_uname'].']执行了{'.$action.'}操作';
            $logdata[$key]['is_del'] = 0;
        }
        //var_dump($logdata);die;
        if(!empty($logdata)){
            $columns = ['log_url','log_param','company_id','log_ip','log_uid','log_uname','depart_id','role_id','ctime','log_desp','is_del'];
            Yii::$app->db->createCommand()->batchInsert(SystemLog::tableName(),$columns,$logdata)->execute();
        }

        Yii::$app->redis->ltrim('sass_log', $length+1, -1);

        //var_dump(Yii::$app->redis->llen('sass_log'));die;
    }
}