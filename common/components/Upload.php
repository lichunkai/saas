<?php
namespace common\components;

use Yii;
use yii\base\Component;

class Upload  extends Component{
	public $dir = "";
	public $moveDir = "";
    public $fileDir = "";
	public $name;
	public $allowed = array();
	public $maxSize = 20971520;
	public $maxFolder;
	public $error;
	public $type=false;
	public $ext;
	
	private $srcWidth;//源宽度
	private $srcHeight;//源高度
	private $dstWidth;//目标高度
	private $dstHeight;//目标高度
	
	public function __construct($params = array())
	{
        $this->moveDir = Yii::$app->params['upload']["move_dir"];
        $this->fileDir = Yii::$app->params['upload']["file_dir"];
        $this->dir     = Yii::$app->params['upload']["tmp_dir"];
	}
	
	public function setType($type)
	{
		$this -> type = $type;
	}
	public function setMaxSize($size)
	{
		$this -> maxSize = $size;
	}
	public function setMaxFolder($folder)
	{
		$this -> maxFolder = $folder;
	}
	public function setAllowed($array)
	{
		$this->allowed = $array;
	}

	/**
	 * setError
	 *
	 * @param String $key
	 * @param Mix $value
	 */
	public function setError($key, $value)
	{
		$this->error[$key] = $value;
	}

	/**
	 * 获取错误信息
	 *
	 * @param String $key
	 * @return Mix
	*/
	public function getError($key)
	{
		if(!empty($key))
		{
			return $this->error[$key];
		}
		return $this->error;
	}

	/**
	 * 上传文件
	 *
	 * @param String $filed
	 * @param Array $configure array('path' => 'data/tmp',
	 *						'maxSize' => 1024*1024,
	 *						'maxFolder' => 100);
	 */
	public function upload($file, $configure = array())
	{
		$data = array();
		if($file)
		{
			//如果文件路径不存在就自动创建
			if(!empty($configure['path']))
			{
				$this -> setDir($configure['path']);
			}
			if(!empty($configure['maxSize']))
			{
				$this -> setMaxSize($configure['maxSize']);
			}
			if(!empty($configure['maxFolder']))
			{
				$this -> setMaxFolder($configure['maxFolder']);
			}
			
			if(!is_dir($this->dir))
			{
                $this -> mkdir($this->dir);
			}
			
			if(is_array($file['name'])) //多个图片或文件
			{
				foreach ($file['name'] AS $key => $val)
				{
					if($file['error'][$key])
					{
						$this->setError($key, $file['error'][$key]);
						$data[$key] = '';
					}
					else 
					{
						$extension = pathinfo($file['name'][$key], PATHINFO_EXTENSION);
						$this -> name = $this -> getFilename($extension);
                        $fillPath = $this -> dir;
                        $filePath = rtrim($fillPath, '/');
                        if(!is_dir($fillPath))
                        {
                            mkdir($fillPath, 0777, true);
                        }
						if(move_uploaded_file($file['tmp_name'][$key], $filePath . '/' . $this->name))
						{
							$data[$key] =  $filePath . '/' . $this->name;
						}
						else 
						{
							$data[$key] = '';
							$this->setError($key, 'MOVE_UPLOADED_FILE_FALSE');
						}
					}
				}
			}
			else //单个图片或文件
			{
				$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

				$this->name = $this->getFilename($extension);
                $fillPath = $this -> dir;
                $filePath = rtrim($fillPath, '/');
                if(!is_dir($fillPath))
                {
                    mkdir($fillPath, 0777, true);
                }

				if(move_uploaded_file($file['tmp_name'], $fillPath . '/' . $this->name))
				{
					$data[] =  $fillPath . '/' . $this->name;
				}
				else 
				{
					$data[] = '';
				}
			}
		}
		$data = array_filter($data);
		return $data;
	}

    /**
     * 将文件移动到正式目录
     */
    public function move($file, $image_type, $is_small= true)
    {
        if(!is_dir($this->moveDir))
        {
            $this -> mkdir($this->moveDir);
        }
        $pos = strrpos($file, ".");
        $suffix = substr($file,$pos+1);
        if(in_array(strtolower($suffix), $image_type)){
            //原
            $files["full"] = $this -> copyImg($file);
            //生成中图
            //$files['m'] = $this -> createMiddleImg($file);
            //生成小图
            /* apleshi commented as no need small pic.
            if($is_small){
                $files['small'] = $this -> createSmallImg($file);
            }
            */
            //删除临时文件夹的图片
            unlink($file);
            $files["small"] = $files["full"];
        }else{
            $files["full"] = "";
            if($is_small){
                $files["small"] = "";
            }
        }
        return $files;
    }

    /**
     * 将文件移动到正式目录
     */
    public function moveFile($file, $file_type)
    {
        if (! is_dir($this->fileDir)) {
            $this->mkdir($this->fileDir);
        }
        $pos = strrpos($file, ".");
        $suffix = substr($file, $pos + 1);
        if (in_array(strtolower($suffix), $file_type)) {
            $files = $this->copyFile($file,$suffix);
            unlink($file);
        } else {
            $files = "";
        }
        return $files;
    }

    /**
     * 生成文件名
     */
    public function getFilename($ext='')
    {
        if($ext) $ext = '.'.$ext;
        $dateString = date('Ymd_');
        $rand = (string)(rand(10000,90000));
        $time = time();
        return $dateString.md5($time.$rand).$ext;
    }

    /**
     * 拼接文件路径和文件名
     * @param string $file
     */
    public function getNewImageName($file,$ext='')
    {
        if($this->ext) $ext = $this -> ext;
        $fileInfo = pathinfo($file);
        if(!$ext) $ext = $fileInfo['extension'];
        if(!is_dir($this->moveDir))
        {
            $this -> mkdir($this->moveDir);
        }
        $path = $this->moveDir . '/' . substr($fileInfo["basename"], 0, 6) . '/' . substr($fileInfo["basename"], 6, 2);


        $newFile = $fileInfo["basename"];
        if(!is_dir($path))
        {
            $this -> mkdir($path);
        }
        return $path.'/'.$newFile;
    }

    /**
     * 拼接文件路径和文件名
     * @param string $file
     */
    public function getNewFileName($file,$ext='')
    {
        if($this->ext) $ext = $this -> ext;
        $fileInfo = pathinfo($file);
        if(!$ext) $ext = $fileInfo['extension'];
        if(!is_dir($this->fileDir))
        {
            $this -> mkdir($this->fileDir);
        }
        $path = $this->fileDir . '/' . substr($fileInfo["basename"], 0, 6) . '/' . substr($fileInfo["basename"], 6, 2);

        $newFile = $fileInfo["basename"];
        if(!is_dir($path))
        {
            $this -> mkdir($path);
        }
        return $path.'/'.$newFile;
    }

    /**
     * 创建目录
     * @param unknown_type $dir
     */
    private function mkdir($dir)
    {
        mkdir($dir, 0777, true);
    }
	
	/**
	 * 自动重命名文件，如果没有指定目录就重命名，否则移动文件
	 *
	 * @param Mix $oldName
	 * @param Mix $newName
	 * @param Bool $keepExtension 是否保持当前文件名
	 * @return Array|String
	 */
	public function rename($oldName, $newName, $keepExtension = false)
	{
		$path = array();
		if(is_array($oldName))
		{
			foreach ($oldName AS $key => $val)
			{
				$path[] = autoRename($val, $newName[$key]);
			}
		}
		else 
		{
			$oldNameArray = pathinfo($oldName);
			$newNameArray = pathinfo($newName);
			
			$dirName = $oldNameArray['dirname'];
			//如果第二个文件夹没有包含路径就直接重新命名，否则移动到新的路径下
			if($newNameArray['dirname'] == substr($newName,0, strlen($newNameArray['dirname'])))
			{
				$dirName = $newNameArray['dirname'];
			}
			$fileName = $oldNameArray['filename'];
			if($newNameArray['filename'])
			{
				$fileName = $newNameArray['filename'];
			}
			$extension = '.' . $oldNameArray['extension'];
			if(!isset($newNameArray['extension']) && !$keepExtension)
			{
				$extension = '';
			}
			$newPath =  $dirName .'/'. $fileName . $extension;
			rename($oldName, $newPath);
			return $newPath;
		}
		return $path;
	}
	/**
	 * 过滤images
	 *
	 * @param Array $images
	 * @return Array
	 */
	public function filterImages($images)
	{
		$data = array();
		if(!is_array($images))
		{
			return $data;
		}
		foreach ($images AS $image)
		{
			if(!empty($image)) $data[] = $image;
		}
		return $data;
	}
	
	public function autoResize($images, $configure)
	{
		$data = array();
		if(is_array($images))
		{
			foreach ($images AS $image)
			{
				$data[] = $this->autoResize($image, $configure);
			}
		}
		else 
		{
			$thumbs = array();
			foreach ($configure['size'] AS $key => $value)
			{
				$pathInfo = pathinfo($images);
				$thumbs[$key] = $small = $pathInfo['dirname'] .'/'. $pathInfo['filename'] .'.'. $key.'.'. $pathInfo['extension'];
				$this->img_cut_scale($images, $small, $configure['size'][$key]['width'], $configure['size'][$key]['height']);
				$this->img_resize_samll($small, $small, $configure['size'][$key]['width']);
			}
			return $thumbs;
		}
		return $data;
	}
	/**
	 * 计算存放的文件夹
	 *
	 * @param String $fileName
	 * @param Int $maxFolder
	 * @return String
	 */
	public function distribution($fileName, $maxFolder = 100)
	{
		if(empty($maxFolder) || $maxFolder == 1)
		{
			return '';
		}
		$folder = ord($fileName) % $maxFolder;
		if($folder == 0)
		{
			return $maxFolder;
		}
		return $folder;
	}
	/**
	 * 图片缩放
	 *
	 * @param String $big_img
	 * @param String $small_img
	 * @param Int $width
	 * @return Bool
	 */
	public function img_resize_samll($big_img, $small_img, $width = 392)
	{
		// 图片路径
		if(!file_exists($big_img))
		{
			$this->setError('img_resize_samll', $big_img . "文件不存在");
			return false;
		}
		else
		{
			ini_set("memory_limit", "128M");
			$filename = $big_img;
			// 获取原图片的尺寸
			list($width_orig, $height_orig) = getimagesize($filename);
			//根据比例，计算新图片的尺寸
			$height = ($width / $width_orig) * $height_orig;
			//新建一个真彩色图像
			$destImage = imagecreatetruecolor($width, $height);
			//从 JPEG 文件或 URL 新建一图像
			$image = imagecreatefromjpeg($filename);
			//重采样拷贝部分图像并调整大小
			imagecopyresampled($destImage, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			// 将图片保存到服务器
			imagejpeg($destImage, $small_img, 100);
			//销毁图片，释放内存
			imagedestroy($destImage);
			return true;
		}
	}
	public function img_cut_scale($big_img, $small_img = 'test.jpg', $width = 90, $height = 130)
	{
		if(!file_exists($big_img))
		{
			$this->setError('img_cut_scale', $big_img . "文件不存在");
			return;
		}
		ini_set("memory_limit", "128M");
		//大图文件地址，缩略宽，缩略高，小图地址
	    $imgage = getimagesize($big_img);//获取大图信息
	    switch ($imgage[2]){//判断图像类型
		    case 1:
		     	$im=imagecreatefromgif($big_img);
		     break;
		    case 2:
		     	$im = imagecreatefromjpeg($big_img);
		     break;
		    case 3:
		    	 $im = imagecreatefrompng($big_img);
		     break;
	    }

	    $src_W = imagesx($im);//获取大图宽
	    $src_H = imagesy($im);//获取大图高

	    //计算比例
	    //检查图片高度和宽度
	    $srcScale = sprintf("%.2f", ($src_W / $src_H));//原图比例
	    $destScale = sprintf("%.2f", ($width/$height));//缩略图比例

	    //echo "<p>原始比例:".$srcScale."；目标比例".$destScale."</p>";
	    if($srcScale > $destScale)
	    {
	    	//说明高度不够,就以高度为准
		    	$myH = $src_H;
		    	$myW = intval($src_H * ($width/$height));
		    	//获取开始位置
		    	$myY = 0;
		    	$myX = intval(($src_W-$myW)/2);
	    }
	    elseif($srcScale < $destScale)
	    {
	    	//宽度不够就以宽度为准
	    	$myW = $src_W;
	    	$myH = intval($src_W *($height/$width));
	    	$myX = 0;
	    	$myY = intval(($src_H-$myH)/2);
	    }
	    else
	    {
		    if($src_W > $src_H)
		    {
		    	//echo "<p>case 1:</p>";
		    	$myH = $src_H;
		    	$myW = intval($src_H * ($width/$height));
		    	//获取开始位置
		    	$myY = 0;
		    	$myX = intval(($src_W-$myW)/2);
		    }
		    if($src_W < $src_H)
		    {
		    	//echo "case 2";
		    	$myW = $src_W;
		    	$myH = intval($src_W *($height/$width));
		    	$myX = 0;
		    	$myY = intval(($src_H-$myH)/2);
		    }
	    }
	    if($src_W == $src_H)
	    {
	    	$myW = intval($src_H*($width/$height));
	    	$myH = $src_H;

	    	$myX = intval(($src_W-$myW)/2);
	    	$myY = 0;
	    }
	    //echo "<p>SW:" . $src_W ."W:" .$myW . "</p><p>X".$myX."</p><p>SH".$src_H.";H:" . $myH ."<p>Y".$myY."</p>";
	    //从中间截取图片

	    $tn = imagecreatetruecolor($myW, $myH);//创建小图
	    imagecopy($tn, $im, 0, 0, $myX, $myY, $myW, $myH);
	    imagejpeg($tn, $small_img, 100);//输出图像
	}
	/**
	 *
	 * 剪切圖片到指定大小
	 * @param String $big_img 原始圖片
	 * @param Int $width 寬
	 * @param Int $height高
	 * @param String $small_img 縮放後保存的圖片
	 */
	public function img_cut_small($big_img, $small_img, $width, $height)
	{
		if(!file_exists($big_img))
		{
			return;
		}
		ini_set("memory_limit", "128M");
		//大图文件地址，缩略宽，缩略高，小图地址
	    $imgage=getimagesize($big_img);//获取大图信息
	    switch ($imgage[2]){//判断图像类型
		    case 1:
		     	$im=imagecreatefromgif($big_img);
		     break;
		    case 2:
		     	$im=imagecreatefromjpeg($big_img);
		     break;
		    case 3:
		     	$im=imagecreatefrompng($big_img);
		     break;
	    }
	    $src_W = imagesx($im);//获取大图宽
	    $src_H = imagesy($im);//获取大图高
	    $tn = imagecreatetruecolor($width, $height);//创建小图
	    imagecopy($tn, $im, 0, 0, 0, 0, $width, $height);
	    imagejpeg($tn, $small_img, 100);//输出图像
	}
	
	/**
	 * 二进制数据流
	 */
	public function streamToFile()
	{
		$files = array();
		
		$fileName = $this -> getFilename();
		
		if(!is_dir($this -> dir)) $this -> mkdir($this -> dir);
		
		$binary = file_get_contents('php://input');
		
		$data = $binary ? $binary : gzuncompress($GLOBALS['HTTP_RAW_POST_DATA']);
        //数据流不为空，则进行保存操作  
        if(!empty($data)){
        	//创建该文件  
        	$filePath = $this->dir.'/'.$fileName;
			@touch($filePath);
			
            //创建并写入数据流，然后保存文件  
            if (@$fp = fopen($filePath,'w+')) {  
                fwrite($fp,$data);  
                fclose($fp); 
                $files[] = $filePath; 
            } 
        }
        return $files;
	}

    public function copyFile($srcFile, $suffix)
    {
		$filename = $this -> getNewFileName($srcFile, '1' , $suffix);
		if(copy($srcFile,$filename)){
            $filename = str_replace($this->moveDir,'',$filename);
            return $filename;
        }
        return false;
    }

	public function copyImg($srcImg)
	{
		$filename = $this -> getNewImageName($srcImg, '1' , 'jpg');
		if(copy($srcImg,$filename)){
            $filename = str_replace($this->moveDir,'',$filename);
            return $filename;
        }
        return true;
	}
	
	/**
	 * 创建中图
	 * @param unknown_type $srcImg
	 */
	public function createMiddleImg($srcImg)
	{
		$srcImgSize = getimagesize($srcImg);

		$this->srcWidth = $srcImgSize[0];
		$this->srcHeight = $srcImgSize[1];
		$newfilename = $this -> getNewFileName($srcImg, '2' , 'jpg');
		
		if($this->srcWidth <= 1024 && $this->srcHeight <= 640)//尺寸未超
		{
			@copy($srcImg,$newfilename);
		}
		else if($this->srcWidth <= 1024 && $this->srcHeight > 640)//高度超了
		{
			$this->dstHeight = 640;
			$this->dstWidth = (int)(($this->srcWidth/$this->srcHeight)*$this->dstHeight);
			$this -> resizeTo($srcImg,$newfilename,$this->dstWidth,$this->dstHeight,$this->srcWidth,$this->srcHeight);
		}
		else if($this->srcWidth > 1024 && $this->srcHeight <= 640)//宽度超了
		{
			$this->dstWidth = 1024;
			$this->dstHeight = (int)(($this->srcHeight/$this->srcWidth)*$this->dstWidth);
			$this -> resizeTo($srcImg,$newfilename,$this->dstWidth,$this->dstHeight,$this->srcWidth,$this->srcHeight);
		}
		else //宽高都超了
		{
			if((1024/640) >= ($this->srcWidth/$this->srcHeight))
			{
				$this->dstWidth = 1024;
				$this->dstHeight = (int)(($this->srcHeight/$this->srcWidth)*$this->dstWidth);
				$this -> resizeTo($srcImg,$newfilename,$this->dstWidth,$this->dstHeight,$this->srcWidth,$this->srcHeight);
			}
			else
			{
				$this->dstHeight = 640;
				$this->dstWidth = (int)(($this->srcWidth/$this->srcHeight)*$this->dstHeight);
				$this -> resizeTo($srcImg,$newfilename,$this->dstWidth,$this->dstHeight,$this->srcWidth,$this->srcHeight);
			}
		}
		
		return $newfilename;
	}
	
	public function createSmallImg($srcImg)
	{
		$newFileName = $this -> getNewFileName($srcImg, '3' ,'jpg');
		$srcImgSize = getimagesize($srcImg);
		$width = 90;
		$height = 90;

		$this->srcWidth = $srcImgSize[0];
		$this->srcHeight = $srcImgSize[1];
		
		if($this->srcWidth <= $width && $this->srcHeight <= $height)//尺寸未超
		{
			@copy($srcImg,$newFileName);
		}
		else if($this->srcWidth <= $width && $this->srcHeight > $height)//高度超了
		{
			$this->dstHeight = $height;
			$srcY = ($this->srcHeight - $height)/2;
			$srcX = 0;
			$this -> thumbTo($srcImg,$newFileName,$srcX,$srcY,$this->srcWidth,$this->dstHeight);
		}
		else if($this->srcWidth > $width && $this->srcHeight <= $height)//宽度超了
		{
			$this->dstWidth = $width;
			$srcX = ($this->srcWidth - $width)/2;
			$srcY = 0;
			$this -> thumbTo($srcImg,$newFileName,$srcX,$srcY,$this->dstWidth,$this->srcHeight);
		}
		else //宽高都超了
		{
			if(($width/$height) == ($this->srcWidth/$this->srcHeight))
			{
				$srcImg = $this -> resizeTo($srcImg, $newFileName, $width, $height, $this->srcWidth, $this->srcHeight,false,false);
				$this -> thumbTo($srcImg,$newFileName,0,0,$width,$height,true);
			}
			elseif(($this->srcWidth/$this->srcHeight) > ($width/$height))//宽超
			{
				$tmpHeight = $height;
				$tmpWidth = (int)(($this->srcWidth/$this->srcHeight)*$tmpHeight);
				$srcImg = $this -> resizeTo($srcImg, $newFileName, $tmpWidth, $tmpHeight, $this->srcWidth, $this->srcHeight,false,false);
				$srcY = 0;
				$srcX = ($tmpWidth - $width)/2;
				$this -> thumbTo($srcImg,$newFileName,$srcX,$srcY,$width,$height,true);
			} 
			else//高超
			{
				$tmpWidth = $width;
				$tmpHeight = (int)(($this->srcHeight/$this->srcWidth)*$width);
				$srcImg = $this -> resizeTo($srcImg, $newFileName, $tmpWidth, $tmpHeight, $this->srcWidth, $this->srcHeight,false,false);
				$srcY = ($tmpHeight - $height)/2;
				$srcX = 0;
				$this -> thumbTo($srcImg,$newFileName,$srcX,$srcY,$width,$height,true);
			}
		}
		
		return $newFileName;
	}


	
	/**
	 * 缩放到指定大小
	 * @param unknown_type $srcImg
	 * @param unknown_type $newFilename
	 */
	private function resizeTo($srcImg,$newFilename,$dstWidth,$dstHeight,$srcWidth,$srcHeight,$isSource=false,$isOutput=true)
	{
		//if(!file_exists($srcImg)) return;
		ini_set("memory_limit", "128M");
		
		$dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
		
		$imgInfo = getimagesize($srcImg);
		switch($imgInfo[2])
		{
			case 1:
				$srcImg = imagecreatefromgif($srcImg);
				break;
			case 2:
				$srcImg = imagecreatefromjpeg($srcImg);
				break;
			case 3:
				$srcImg = imagecreatefrompng($srcImg);
				break;
		}
		
		if(function_exists('imagecopyresampled'))
			imagecopyresampled($dstImg,$srcImg,0,0,0,0,$dstWidth,$dstHeight,$srcWidth,$srcHeight);
		else
			imagecopyresized($dstImg,$srcImg,0,0,0,0,$dstWidth,$dstHeight,$srcWidth,$srcHeight);
		
		if($isOutput)
		{
			imagejpeg($dstImg, $newFilename, 100);
			imagedestroy($dstImg);
			return true;
		}
		else
		{
			return $dstImg;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $srcImg
	 * @param unknown_type $newFileName
	 */
	private function thumbTo($srcImg,$newFileName,$srcX=0,$srcY=0,$dstWidth=0,$dstHeight=0,$isSource=false,$isOutput=true)
	{
		ini_set("memory_limit", "128M");
		
		//大图文件地址，缩略宽，缩略高，小图地址
		if(!$isSource) 
		{
			$imgInfo = getimagesize($srcImg);
			switch($imgInfo[2])
			{
				case 1:
					$im = imagecreatefromgif($srcImg);
					break;
				case 2:
					$im = imagecreatefromjpeg($srcImg);
					break;
				case 3:
					$im = imagecreatefrompng($srcImg);
					break;
			}
		}
		else $im = $srcImg;
		
	    $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);//创建小图

	    imagecopy($dstImg, $im, 0, 0, $srcX, $srcY, $dstWidth, $dstHeight);
	    
	    if($isOutput)
	    {
		    imagejpeg($dstImg, $newFileName, 100);//输出图像
	
		    imagedestroy($dstImg);
		    
		    return true;
	    }
	    else 
	    {
	    	return $dstImg;
	    }
	}
	
	/**
	 * 下载操作
	 */
	public function downloadImg($url)
	{
		if(!$url) return false;
		
		$ext='jpg';
		
		//$filename = $this -> getFilename2($ext);
		$filename = $this->dir.'/'.$this -> getFilename($ext);
		ob_start();
		
		readfile($url);
		
		$img = ob_get_contents();
		
		ob_end_clean();
		
		$im = imagecreatefromstring($img);
		
		$dir = pathinfo($filename,PATHINFO_DIRNAME);
		if(!is_dir($dir))
		{
			$this -> mkdir($dir);
		}
		imagejpeg($im,$filename);
		imagedestroy($im);
//		$fileInfo = pathinfo($filename);
//		$file = str_replace('_', '/', $fileInfo['basename']);
//		$filename2 = $fileInfo['dirname'].'/'.$file;
//		$fileInfo2 = pathinfo($filename2);
		
//		$filename3 = $fileInfo2['dirname'].'/'.$fileInfo2['filename'].'1'.'.'.$fileInfo2['extension'];
//		$filename4 = $fileInfo2['dirname'].'/'.$fileInfo2['filename'].'2'.'.'.$fileInfo2['extension'];
//		$filename5 = $fileInfo2['dirname'].'/'.$fileInfo2['filename'].'3'.'.'.$fileInfo2['extension'];
//		
//		$dir = pathinfo($filename2,PATHINFO_DIRNAME);
//		if(!is_dir($fileInfo2['dirname']))
//		{
//			$this -> mkdir($fileInfo2['dirname']);
//		}
//		
//		imagejpeg($im,$filename3);
//		imagejpeg($im,$filename4);
//		imagejpeg($im,$filename5);
//		
//		imagedestroy($im);

		$this -> copyImg($filename);
		$this -> createMiddleImg($filename);
		$this -> createSmallImg($filename);
		unlink($filename);
		
		return $filename; 
	}

	/**
	 * 生成文件名
	 */
	private function getFilename2($ext)
	{
		$dateString = date('Ym_d_');
		$rand = (string)(rand(10000,90000));
		$time = time();
		return $this->moveDir.'/'.$dateString.md5($time.$rand).$ext;
	}

}

