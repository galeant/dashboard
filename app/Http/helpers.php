<?php
namespace App\Http;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Config;

class helpers{
	public static function salutation(){
		return ['Mr'=> 'Mr.','Mrs'=>'Mrs.','Ms'=>'Ms.'];
	}
	public static function typeCity(){
		return ['Kota'=> 'Kota','Kabupaten'=>'Kabupaten'];
	}
	public static function encodeSpecialChar($string)
	{
	    $replace  = str_replace(' ', '-', $string);
	    // $replace  = str_replace(".", "-",$replace);
	    $replace  = str_replace("&", "-",$replace);
	    $replace  = str_replace(" ", "-",$replace);
	    $replace  = str_replace("  ", "-",$replace);
	    $replace  = str_replace("   ", "-",$replace);
	    $replace  = str_replace("$", "-",$replace);
	    $replace  = str_replace("+", "-",$replace);
	    $replace  = str_replace("! ", "-",$replace);
	    $replace  = str_replace("@", "-",$replace);
	    $replace  = str_replace("#", "-",$replace);
	    $replace  = str_replace("$", "-",$replace);
	    $replace  = str_replace("%", "-",$replace);
	    $replace  = str_replace("^", "-",$replace);
	    $replace  = str_replace("&", "-",$replace);
	    $replace  = str_replace("*", "-",$replace);
	    $replace  = str_replace("(", "-",$replace);
	    $replace  = str_replace(")", "-",$replace);
	    $replace  = str_replace("/", "-",$replace);
	    $replace  = str_replace("+", "-",$replace);
	    $replace  = preg_replace('/[^A-Za-z0-9\-]/', '', $replace);
	    $replace  = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $replace);
	    $replace = preg_replace('/-+/', '-', $replace);
	    return $replace;
	}
	public static function reduction(){
		return ['percentage' => 'Percentage','amount' =>'Amount'];
	}

	public static function saveImage($image = null,$path ='images',$thumbnail = false,$ratio = null)
	{
		$year  = date('Y');
		$month = date('m');
		$day   = date('d');
		$path  = md5($path).'/'.$year.'/'.$month.'/'.$day;
		// dd($path);
		$extension      = $image->getClientOriginalExtension();

    	$filename       = self::encodeSpecialChar(pathinfo($image->getClientOriginalName())['filename']);
    	$filename		= $filename.'.'.$extension;
        $sizeimg        = $image->getSize();
        $mime           = $image->getMimeType();
        $bag = new MessageBag();
		if($image == null){
			$bag->add('error', 'Missing Image !');
			return $bag;
		}
		try {
	        if($thumbnail == true){
	        	$originalSize =getimagesize($image);
				$width = $originalSize[0];
				$height = $originalSize[1];
	        	try {
			        Storage::disk('s3')->put($path.'/original/'.$filename,file_get_contents($image),Config::get('s3'));
			        $thumbnail = self::autoRatio($width,$height,$ratio);
			        $tempPath = 'tmp';
			        $deleteTemp = [];
			        
			        foreach ($thumbnail as $key => $size) {
			        	if( ! \File::isDirectory($tempPath.'/'.$key) ) 
				        {
				            File::makeDirectory($tempPath.'/'.$key, 0777, true , true);
				        }
				        $savePath = $tempPath.'/'.$key.'/'.$filename;
				        array_push($deleteTemp,$savePath);
			        	$img[$key] = Image::make($image)->resize($size['width'], $size['height'])->save($savePath);
			        	$img[$key] = Storage::disk('s3')->put($path.'/'.$key.'/'.$filename,file_get_contents($savePath),Config::get('s3'));
	        			gc_collect_cycles();
			        }
			        foreach($deleteTemp as $value){
	                    try {
	                        if(!is_writable($value)){
					            $bag->add('delete', 'Permission denied delete file!');
					            return $bag;
	                        }
	                        unlink($value);
	                    }
	                    catch(Exception $e) { 
				            $bag->add('delete', 'Permission denied delete file!');
				            return $bag;
				        }
	                        
	                }
                }catch(Exception $e) {
                	// dd($e);
			        $bag->add('error', $e->getMessage());
		            return $bag;
			    }
	        }
	        else{
	        	$img = Storage::disk('s3')->put($path.'/'.$filename,file_get_contents($image),Config::get('s3'));
	        	gc_collect_cycles();
	        }
	        if($img){
		        return  [
			        		'path' => $path,
			        		'extension' => $extension,
			        		'size' => $sizeimg,
			        		'content_type' => $mime,
			        		'filename' => $filename
		        	    ];
        	}
        } catch (Exception $e) {
        	$bag->add('error', $e->getMessage());
            return $bag;
		}
	}
	public static function autoRatio($width,$height,$ratio=null,$autoResize=false)
	{	
		$perbandingan=['xsmall'=>2,'small'=>5,'medium' => 10,'large'=>15,'xlarge'=>20];
		if($width <= 175){
			$value = 'xsmall';
		}elseif($width > 175 && $width <=375){
			$value = 'small';

		}elseif($width > 375 && $width <= 625){
			$value = 'medium';
		}
		elseif($width > 625 && $width <= 875){
			$value = 'large';
		}else{
			$value = 'xlarge';
		}
		$t = 100;
		$data = array();
		foreach($perbandingan as $name => $angka){
			if($autoResize == false){
				$data[$name]['width'] = ($perbandingan[$name]/$perbandingan['xsmall'])*$t;
			}
			else{
				$data[$name]['width'] = ($perbandingan[$name]/$perbandingan[$value])*$width;
			}
			$data[$name]['height'] = ($ratio === null ? $height/$width*$data[$name]['width'] : $data[$name]['width']/($ratio[0]/$ratio[1]));
		}
		return $data;
	}
}