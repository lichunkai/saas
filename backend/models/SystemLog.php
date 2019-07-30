<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\SystemLogGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class SystemLog extends SystemLogGii
{
    //添加日志
    public function addLog($url,$log_param,$user)
    {
        $action = Purview::getPurviewName($url);
        if(empty($action)){
            return false;
        }
        $desp = '['.$user['u_name'].']执行了{'.$action.'}操作';
        $ip = Tools::get_client_ip() ;

        $this->log_url = $url;
        $this->log_desp = $desp;
        $this->log_param = $log_param;
        $this->company_id = $user['company_id'];
        $this->log_ip = $ip;
        $this->log_uid = $user['u_id'];
        $this->log_uname = $user['u_name'];
        $this->depart_id = $user['u_dept_id'];
        $this->role_id = $user['u_role_id'];
        $this->ctime = date('Y-m-d H:i:s');
        $this->is_del = 0;
        $this->save();
    }

    //获取日志
    public function getLog(){

    }
}
