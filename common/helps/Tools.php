<?php

namespace common\helps;

use Yii;

class Tools{

    /*
     * 生成用户唯一标识id
     */
    public static function create_uuid($prefix = ""){    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return strtoupper($prefix . $uuid);
    }

    /**
     * 截取字符串
     * @param String $str 源字符串
     * @param int $len 截取的长度
     * @param String $suffix 拼接的后缀
     * @return string 截取后的字符串
     */
    public static function MySubStr($str, $len, $suffix = "...")
    {
        $rsstr = $str;
        if (mb_strlen($str, 'UTF-8') > $len) {
            $rsstr = mb_substr($str, 0, $len, 'UTF-8') . $suffix;
        }
        return $rsstr;
    }

    /**
     * 删除空格
     * @param $str
     * @return mixed
     */
    public static function trimall($str)
    {
        $qian = array(" ", "　", "\t", "\n", "\r");
        $hou = array("", "", "", "", "");
        return str_replace($qian, $hou, $str);
    }

    /**
     * 验证手机号是否正确
     * @param  string $mobile
     * @return bool
     */
    public static function isValidMobile($mobile)
    {
        return preg_match("/^1[34578]\d{9}$/", trim($mobile));
    }

    /**
     * 二维数组根据某个字段去重
     * @param $arr
     * @param $key
     * @return array
     */
    public static function arrayUnsetRepeat($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                //有：销毁
                unset($value[$key]);
            }
            else{
                $res[$value[$key]] = $value;
            }
        }

        return array_values($res);
    }

    /**
     * 验证电子邮件是否正确
     * @param  string $mobile
     * @return bool
     */
    public static function isValidEmail($email)
    {
        return preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", trim($email));
    }

    /**
     * @获取随机字符串
     * @param int $length
     */
    public static function randChar( $length = 6 ) {

        $string = 'abcdefghijklmnopqrstuvwxyz123456789';
        $chars = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $chars .= $string[ mt_rand(0, strlen($string) - 1) ];
        }
        return $chars;
    }

    /**
     * 模拟curl的get请求
     * @param $uri
     * @return mixed
     */
    public static function curl_get($uri)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);

        $rval = curl_exec($ch);
        //echo curl_error($ch);
        curl_close($ch);

        return $rval;
    }

    /**
     * 模拟curl的post请求
     * @param $uri
     * @return mixed
     */
    public static function curl_post($uri, $data = array(), $is_array = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);
        if($is_array){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $rval = curl_exec($ch);
        // echo curl_error($ch);
        curl_close($ch);

        return $rval;
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    public static function listToTree($list, $pk='id', $pid = 'pid', $child = 'children', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 把返回的数据集转换成Tree by 2018-05-28 liuz 子部门树结构
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    public static function listToSubTree($list, $pk='id', $pid = 'pid', $child = 'children') {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }else{
                    $tree[] =& $list[$key];
                }
            }
        }
        return $tree;
    }


    /**
     * 加密函数
     * @param string $txt 需要加密的字符串
     * @param string $key 密钥
     * @return string 返回加密结果
     */
    public static function encrypt($txt, $key = ''){
        if (empty($txt)) return $txt;
        if (empty($key)) $key = md5('zhailele');
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
        $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
        $nh1 = rand(0,64);
        $nh2 = rand(0,64);
        $nh3 = rand(0,64);
        $ch1 = $chars{$nh1};
        $ch2 = $chars{$nh2};
        $ch3 = $chars{$nh3};
        $nhnum = $nh1 + $nh2 + $nh3;
        $knum = 0;$i = 0;
        while(isset($key{$i})) $knum +=ord($key{$i++});
        $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
        $txt = base64_encode(time().'_'.$txt);
        $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
        $tmp = '';
        $j=0;$k = 0;
        $tlen = strlen($txt);
        $klen = strlen($mdKey);
        for ($i=0; $i<$tlen; $i++) {
            $k = $k == $klen ? 0 : $k;
            $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
            $tmp .= $chars{$j};
        }
        $tmplen = strlen($tmp);
        $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
        $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
        $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
        return $tmp;
    }

    /**
     * 解密函数
     * @param string $txt 需要解密的字符串
     * @param string $key 密匙
     * @return string 字符串类型的返回结果
     */
    public static function decrypt($txt, $key = '', $ttl = 0){
        if (empty($txt)) return $txt;
        if (empty($key)) $key = md5('zhailele');

        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
        $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
        $knum = 0;$i = 0;
        $tlen = strlen($txt);
        while(isset($key{$i})) $knum +=ord($key{$i++});
        $ch1 = $txt{$knum % $tlen};
        $nh1 = strpos($chars,$ch1);
        $txt = substr_replace($txt,'',$knum % $tlen--,1);
        $ch2 = @$txt{$nh1 % $tlen};
        $nh2 = @strpos($chars,$ch2);
        $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
        $ch3 = $txt{$nh2 % $tlen};
        $nh3 = @strpos($chars,$ch3);
        $txt = substr_replace($txt,'',$nh2 % $tlen--,1);
        $nhnum = $nh1 + $nh2 + $nh3;
        $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
        $tmp = '';
        $j=0; $k = 0;
        $tlen = strlen($txt);
        $klen = strlen($mdKey);
        for ($i=0; $i<$tlen; $i++) {
            $k = $k == $klen ? 0 : $k;
            $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
            while ($j<0) $j+=64;
            $tmp .= $chars{$j};
        }
        $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
        $tmp = trim(base64_decode($tmp));

        if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
            if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
                $tmp = null;
            }else{
                $tmp = substr($tmp,11);
            }
        }
        return $tmp;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 根据报备要求的的间隔时间返回时间
     * @param string $reminder
     * @return false|string
     */
    public static function getReminderTime($reminder = ''){
        if (empty($reminder)){
            return date('Y-m-d H:i:s');
        }
        switch ($reminder) {
            case 'O.5H':
                $contact_time = date('Y-m-d H:i:s', strtotime("+0.5 hour"));
                break;
            case '1H':
                $contact_time = date('Y-m-d H:i:s', strtotime("+1 hour"));
                break;
            case '3H':
                $contact_time = date('Y-m-d H:i:s', strtotime("+3 hour"));
                break;
            case '1D':
                $contact_time = date('Y-m-d H:i:s', strtotime("+1 day"));
                break;
            case '3D':
                $contact_time = date('Y-m-d H:i:s', strtotime("+3 day"));
                break;
            case '1W':
                $contact_time = date('Y-m-d H:i:s', strtotime("+1 week"));
                break;
            default:
                $contact_time = date('Y-m-d H:i:s', strtotime("+1 day"));
                break;
        }
        return $contact_time;
    }

    //火星坐标(GCJ02坐标，高德，谷歌，腾讯坐标)到百度坐标BD-09
    public static function gcjTObd($src_lon, $src_lat){
        $x_pi = 3.14159265358979324*3000.0/180.0;
        $x = $src_lon;
        $y = $src_lat;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $to_lon = $z * cos($theta) + 0.0065;
        $to_lat = $z * sin($theta) + 0.006;

        return [$to_lon, $to_lat];
    }

    /**
     * @desc 根据两点间的经纬度计算距离
     * @param float $lat 纬度值
     * @param float $lng 经度值
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000;
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance, 4);
    }

    public static function buildGeoCodingUrl($output, $addr)
    {
        $ak = Yii::$app->params['baiduMapAk'];
        $sk = Yii::$app->params['baiduMapSk'];
        $uri = '/geocoder/v2/';
        $url = "http://api.map.baidu.com/geocoder/v2/?" . "address=" . urlencode($addr) . "&output=" . $output . "&ak=" . Yii::$app->params['baiduMapAk'];

        $querystring_arrays = array (
            'address' => $addr,
            'output' => $output,
            'ak' => Yii::$app->params['baiduMapAk']
        );

        $sn = static::caculateAKSN($ak, $sk, $uri, $querystring_arrays);

        return $url . "&sn=" . $sn;
    }

    public static function buildGeoCodingUrlWithPos($ak, $sk, $output, $location, $pois = 0)
    {
        $uri = '/geocoder/v2/';
        $url = "http://api.map.baidu.com/geocoder/v2/?". "location=" . urlencode($location) . "&output=" . $output . "&ak=" . $ak;

        $querystring_arrays = array (
            'location' => $location,
            'output' => $output,
            'ak' => $ak
        );

        $sn = static::caculateAKSN($ak, $sk, $uri, $querystring_arrays);

        return $url . "&sn=" . $sn;
    }

    public static function buildGeoCodingWithJuli($output, $lat, $lng, $dlat, $dlng, $ak, $sk){
        $uri = '/routematrix/v2/';
        $url = 'http://api.map.baidu.com/routematrix/v2/driving?output='.$output.'&origins='.$lat.','.$lng.'&destinations='.$dlat.','.$dlng.'&ak='.$ak;

        $querystring_arrays = array (
            'output' => $output,
            'origins' => $lat.','.$lng,
            'destinations' => $dlat.','.$dlng,
            'ak' => $ak
        );

        $sn = static::caculateAKSN($ak, $sk, $uri, $querystring_arrays);

        return $url . "&sn=" . $sn;
    }

    public static function caculateAKSN($ak, $sk, $url, $querystring_arrays, $method = 'GET')
    {
        if ($method === 'POST'){
            ksort($querystring_arrays);
        }

        $querystring = http_build_query($querystring_arrays);

        $ret_hash =  md5(urlencode($url.'?'.$querystring.$sk));

        return $ret_hash;
    }

	/***
	 * 隐藏手机号码
	 * @param $str
	 * @return mixed
	 */
    public static function yc_phone($str){
	    $resstr=substr_replace($str,'****',3,4);
	    return $resstr;
	}


	public static function unicode_encode($name)
	{
		$name = iconv('UTF-8', 'UCS-2', $name);
		$len = strlen($name);
		$str = '';
		for ($i = 0; $i < $len - 1; $i = $i + 2)
		{
			$c = $name[$i];
			$c2 = $name[$i + 1];
			if (ord($c) > 0)
			{    // 两个字节的文字
				$str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
			}
			else
			{
				$str .= $c2;
			}
		}
		return $str;
	}

    public static function cr6868($mobile,$content){
        $argv = array(
            'name'=>Yii::$app->params['sms']['cr6868']['name'],     //必填参数。用户账号
            'pwd'=>Yii::$app->params['sms']['cr6868']['pwd'],     //必填参数。（web平台：基本资料中的接口密码）
            'content'=>$content,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
            'mobile'=>$mobile,   //必填参数。手机号码。多个以英文逗号隔开
            'stime'=>'',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
            'sign'=>Yii::$app->params['sms']['cr6868']['sign'],     //必填参数。用户签名。
            'type'=>'pt',  //必填参数。固定值 pt
            'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
        );
        $flag=0;
        $params='';
        foreach ($argv as $key=>$value) {
            if ($flag!=0) {
                $params .= "&";
                $flag = 1;
            }
            $params.= $key."="; $params.= urlencode($value);// urlencode($value);
            $flag = 1;
        }
        $url = "http://web.cr6868.com/asmx/smsservice.aspx?".$params; //提交的url地址
        $con = strstr( file_get_contents($url), ',', true );  //获取信息发送后的状态
        return $con;
    }


	/**
	 * 首字符排序
	 * @param $s
	 * @return bool|string
	 */
	public static function getFirstChar($s)
	{
		$s0 = mb_substr($s,0,3); //获取名字的姓
		$s1 = iconv("UTF-8", "gbk", $s0); //将UTF-8转换成GBk编码
		$s2 = iconv("gbk", "UTF-8", $s1);
		if ($s2 == $s0) {
			$s = $s1;
		} else {
			$s = $s0;
		}

		if (ord($s0)>128) { //汉字开头，汉字没有以U、V开头的
			$asc=ord($s{0})*256+ord($s{1})-65536;
			if($asc>=-20319 and $asc<=-20284)return "A";
			if($asc>=-20283 and $asc<=-19776)return "B";
			if($asc>=-19775 and $asc<=-19219)return "C";
			if($asc>=-19218 and $asc<=-18711)return "D";
			if($asc>=-18710 and $asc<=-18527)return "E";
			if($asc>=-18526 and $asc<=-18240)return "F";
			if($asc>=-18239 and $asc<=-17760)return "G";
			if($asc>=-17759 and $asc<=-17248)return "H";
			if($asc>=-17247 and $asc<=-17418)return "I";
			if($asc>=-17417 and $asc<=-16475)return "J";
			if($asc>=-16474 and $asc<=-16213)return "K";
			if($asc>=-16212 and $asc<=-15641)return "L";
			if($asc>=-15640 and $asc<=-15166)return "M";
			if($asc>=-15165 and $asc<=-14923)return "N";
			if($asc>=-14922 and $asc<=-14915)return "O";
			if($asc>=-14914 and $asc<=-14631)return "P";
			if($asc>=-14630 and $asc<=-14150)return "Q";
			if($asc>=-14149 and $asc<=-14091)return "R";
			if($asc>=-14090 and $asc<=-13319)return "S";
			if($asc>=-13318 and $asc<=-12839)return "T";
			if($asc>=-12838 and $asc<=-12557)return "W";
			if($asc>=-12556 and $asc<=-11848)return "X";
			if($asc>=-11847 and $asc<=-11056)return "Y";
			if($asc>=-11055 and $asc<=-10247)return "Z";
			if($asc==-6698)return "L"; //特殊字的判断
		}else if(ord($s)>=48 and ord($s)<=57){ //数字开头
			switch(iconv_substr($s,0,1,'utf-8')){
				case 1:return "Y";
				case 2:return "E";
				case 3:return "S";
				case 4:return "S";
				case 5:return "W";
				case 6:return "L";
				case 7:return "Q";
				case 8:return "B";
				case 9:return "J";
				case 0:return "L";
			}
		}else if(ord($s)>=65 and ord($s)<=90){ //大写英文开头
			return substr($s,0,1);
		}else if(ord($s)>=97 and ord($s)<=122){ //小写英文开头
			return strtoupper(substr($s,0,1));
		} else {
			return iconv_substr($s0,0,1,'utf-8');//中英混合的词语，不适合上面的各种情况，因此直接提取首个字符即可
		}
	}


}
?>