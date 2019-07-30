<?php
namespace common\components;

use backend\models\Button;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class LoadData  extends Component
{

	public $button = [];
    public $range = null;
    public $field = ['0'=>'none','1'=>'auth_uid','2'=>'auth_rid','3'=>'auth_sid','4'=>'auth_aid','5'=>'auth_baid','6'=>'all'];
	
	public function __construct()
	{
	}

	/**
	 * 根据权限组装列表按钮
     * @param string $buttons
	 * @param array $data
	 * @param array $user
	 */
	public function listButton($key, $data, $user)
	{
//	    var_dump($user['buttons']);die;
        $buttons = $user['buttons'][$key];
        $auths = ArrayHelper::index($user['auths'], 'p_url');
        $buttonshow = [];
//        var_dump($buttons);
        foreach ($buttons as $k => $button){
            if($button['b_type'] == 2){
                if(ArrayHelper::keyExists($button['b_url'], $auths)){
                    $button['datarange'] = $auths[$button['b_url']]['data_range'];
                    $buttonshow[] = $button;
                }
            }
        }
//        var_dump($auths);
		foreach ($data as $key => $item){
             foreach ($buttonshow as $kk => $button){
                 if($button['datarange'] == 0){
                     $data[$key]['button'][$button['b_name']] = 0;
                 }else if ($button['datarange'] >= 1 && $button['datarange'] < 6){
                     $column = $this->field[$button['datarange']];
                     if($item[$column] == $user[$column]){
                         $data[$key]['button'][$button['b_name']] = 1;
                     }else{
                         $data[$key]['button'][$button['b_name']] = 0;
                     }
                 }else if($button['datarange'] == 6){
                     $data[$key]['button'][$button['b_name']] = 1;
                 }
             }
        }
//        var_dump($data);die;
		return $data;
	}

    /**
     * 根据权限组装顶部按钮
     * @param $key
     * @param $user
     * @return mixed
     */
	public function topButton($key, $user)
    {
        $buttons = $user['buttons'][$key];
        $auths = ArrayHelper::index($user['auths'], 'p_url');
        $buttonshow = [];
        //var_dump($buttons);
        foreach ($buttons as $k => $button){
            if($button['b_type'] == 1){ //var_dump($button);var_dump($auths);
                if(ArrayHelper::keyExists($button['b_url'], $auths)){
                    //$button['datarange'] = $auths[$button['b_url']]['data_range'];
                    $buttonshow[$button['b_name']] = 1;
                }
            }
        }

	    return $buttonshow;
    }

    /**
     * 根据权限显示数据
     * @return bool
     */
    public function checkDataByUser($url,$user)
    {
        $auths = ArrayHelper::index($user['auths'],'p_url');
        if(isset($auths[$url]) && $auths[$url]){
            $data_range = $auths[$url]['data_range'];
            $authdata = [];
            switch ($data_range){
                case 0 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = '';
                    break;
                case 1 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = $user[$this->field[$data_range]];
                    break;
                case 2 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = $user[$this->field[$data_range]];
                    break;
                case 3 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = $user[$this->field[$data_range]];
                    break;
                case 4 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = $user[$this->field[$data_range]];
                    break;
                case 5 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = $user[$this->field[$data_range]];
                    break;
                case 6 :
                    $authdata['key'] = $this->field[$data_range];
                    $authdata['value'] = '';
                    break;
            }
            //var_dump($authdata);die;
            return $authdata;
        }
    }
}
