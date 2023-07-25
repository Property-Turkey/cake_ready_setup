<?php

namespace App\Controller\Component;

// require_once('ArGD.php');

use Cake\Controller\Component;
// use ArabicGD;
// use DoComponent;

class ImagesComponent extends Component
{

    public $components = ['Do'];

    var $allowed_ext = array(
        'images' => array('jpg', 'jpeg', 'gif', 'png'),
        'media' => array('swf', 'flv'),
        'doc' => array('doc', 'pdf')
    );
    var $max_upload_size = 3500; // per kilobyte
    var $Error_Msg = array();
    var $photosname = array();

    public function url_createWebp($filepath, $savedir, $fileName=null, $thumb=[], $watermark=true)
    {
        // debug($filepath);
        // debug($savedir);
        // debug($fileName);
        // debug($thumb);
        // dd($watermark);
        if($this->getExt($fileName) == 'gif'){return false;} // skip gifs
        if($fileName){
            $special = array('!', '’', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+',  '|', '[', ']', ':', ';', '<', '>', '?', ',', '`', '~', '/', '!', '&', '*');
            $fileName = str_replace($special, '',$this->delExt($fileName));
            $fileName = str_replace(' ', '-', $fileName) ;
        }

        $img = $this->createImage( getcwd().'/'.$filepath );
        if(is_string($img)){return false;}// make sure image created
        
        // create original image as webp
        @imagewebp($img, $savedir . '/' .  $fileName . '.webp', 75);
        imagedestroy($img);
        
        // waternmark
        if($watermark){
            $this->addWaterMark_img(
                $savedir . '/' .  $fileName . '.webp', 
                'img/stamp.png'
            );
        }

        // create thumbnails
        for ($i = 0; $i < count($thumb); $i++) {
            // dd($savedir . '/' . $fileName . '.webp');
            $this->resizer_webp(
                  $savedir . '/' . $fileName . '.webp', 
                  $savedir . '/' . $thumb[$i]['dst'] .'/'. $fileName . '.webp', 
                $thumb[$i]['w'], 
                $thumb[$i]['h']
            );
        }
        return $fileName . '.webp';
    }

    function addWaterMark_img($im, $stamp)
    {
        $copy = getcwd() . '/' . $im; // new image holder
        $stamp = $this->createImage( $stamp );
        $im = $this->createImage( $im );

        if ($im) {
            imagecopy($im, $stamp, 10, imagesy($im) - 30, 0, 0, imagesx($stamp), imagesy($stamp));
            @imagewebp($im, $copy, 75);
            @imagedestroy($im);
        } else {
            return false;
        }
    }

    function createImage($src)
    {

        $type = $this->getExt($src);
        // dd($type);
        if (file_exists( $src )) {
            switch ($type) {
                case 'bmp':
                    $res = @imagecreatefromwbmp($src);
                    break;
                case 'gif':
                    $res = @imagecreatefromgif($src);
                    break;
                case 'jpg':
                case 'jpeg':
                    $res = @imagecreatefromjpeg($src);
                    break;
                case 'png':
                    $res = @imagecreatefrompng($src);
                    if($res){
                        @imagepalettetotruecolor($res);
                        @imagepalettetotruecolor($res);
                        @imagealphablending($res, true);
                        @imagesavealpha($res, true);
                    }else{
                        $res = 'Unsupported type';
                    }
                    break;
                case 'webp': 
                    $res = @imagecreatefromwebp($src); 
                    break;

                default:
                    $res = 'Unsupported type';
            }
        }else{
            $res = 'file not exist';
        }
        return $res;
    }
    
    function resizer_webp($source_file, $destination_file, $width, $height, $quality=75, $crop=FALSE) 
    {
        if(!file_exists($source_file)){ return false; }
        list($current_width, $current_height) = getimagesize($source_file);
        $rate = $current_width / $current_height;
        if ($crop) {
            if ($current_width > $current_height) {
                $current_width = ceil($current_width-($current_width*abs($rate-$width/$height)));
            } else {
                $current_height = ceil($current_height-($current_height*abs($rate-$width/$height)));
            }
            $newwidth = $width;
            $newheight = $height;
        } else {
            if ($width/$height > $rate) {
                $newwidth = $height*$rate;
                $newheight = $height;
            } else {
                $newheight = $width/$rate;
                $newwidth = $width;
            }
        }
        $src_file = imagecreatefromwebp($source_file);
        $dst_file = imagecreatetruecolor((int)$newwidth, (int)$newheight);
        imagecopyresampled($dst_file, $src_file, 0, 0, 0, 0, (int)$newwidth, (int)$newheight, (int)$current_width, (int)$current_height);
    
        imagewebp($dst_file, $destination_file, $quality);
    }

    function resizer($src, $dst, $width, $height, $crop=0, $watermark=false)
    {       
		$this->Error_Msg = array();
		if(!list($w, $h) = @getimagesize($src)){
			$this->Error_Msg[] = 'dimensions_error'; 
			return "Unsupported picture type!";
		}
        $img = $this->createImage($src);
        if(is_string($img)){return false;}// make sure image created
        
		// resize
		if($crop){
			if($w < $width or $h < $height){
				return "Picture is too small!";
				$this->Error_Msg[] = 'picture_small';
			}
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		}else{
			if($w < $width and $h < $height){
				return "Picture is too small!";
				$this->Error_Msg[] = 'picture_small';
			}
			$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;
		}

        
        $width = floor( $width );
        $height = floor( $height );
		$new = @imagecreatetruecolor($width, $height);

		$type = pathinfo($src,PATHINFO_EXTENSION);
		// preserve transparency
		if($type == "gif" or $type == "png" or $type == "webp"){
			@imagecolortransparent($new, @imagecolorallocatealpha($new, 0, 0, 0, 127));
			@imagealphablending($new, false);
			@imagesavealpha($new, true);
		}
        
        if($img){
            imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
        }
        
		switch($type){
			case 'bmp': @imagewbmp($new, $dst); break;
			case 'gif': @imagegif($new, $dst); break;
			case 'jpg': @imagejpeg($new, $dst); break;
			case 'png': @imagepng($new, $dst); break;
            case 'webp': @imagewebp($new, $dst); break;
		}
		return true;
	}

    function getExt($file)
    {
        $fileext = pathinfo($file,PATHINFO_EXTENSION);
        switch ($fileext) {
            case 'jpeg':
            case 'jpg':
                $res = 'jpg';
                break;

            default:
                $res = $fileext;
                break;
        }
        return $res;
    }

    function delExt($filename)
    {
        return pathinfo($filename,PATHINFO_FILENAME);
    }

    function getPhotosNames()
    {
        $imgs = implode(",", $this->photosname);
        $this->photosname = array();
        return $imgs;
    }

    function deleteFile($path, $img)
    {
        if (is_array($img)) {
            foreach ($img as $file) {
                if (is_array($file)) {
                    continue;
                }
                @unlink($path . '/' . trim($file));
                @unlink($path . '/thumb/' . trim($file));
                @unlink($path . '/middle/' . trim($file));
            }
            return true;
        }
        @unlink($path . '/' . trim($img));
        @unlink($path . '/thumb/' . trim($img));
        @unlink($path . '/middle/' . trim($img));
        return true;
    }
}
