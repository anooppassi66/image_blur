<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="submit">
    </form>
</body>
</html>
<?php 
if(isset($_POST)){

    

    function image_blurred_bg($image, $dest, $width, $height){
        try{
            $info = getimagesize($image);
        } catch (Exception $e){
            return false;
        }
        
        $mimetype = image_type_to_mime_type($info[2]);
        switch ($mimetype) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($image);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($image);
                break;
            case 'image/png':
                $image = imagecreatefrompng($image);
                break;
            default:
                return false;
        }
        
        $wor = imagesx($image);
        $hor = imagesy($image);
        $back = imagecreatetruecolor($width, $height);
        
        $maxfact = max($width/$wor, $height/$hor);
        $new_w = $wor*$maxfact;
        $new_h = $hor*$maxfact;
        imagecopyresampled($back, $image, -(($new_w-$width)/2), -(($new_h-$height)/2), 0, 0, $new_w, $new_h, $wor, $hor);
        
        // Blur Image
        for ($x=1; $x <=40; $x++){
            imagefilter($back, IMG_FILTER_GAUSSIAN_BLUR, 999);
        }
        imagefilter($back, IMG_FILTER_SMOOTH,99);
        imagefilter($back, IMG_FILTER_BRIGHTNESS, 10);
        
        $minfact = min($width/$wor, $height/$hor);
        $new_w = $wor*$minfact;
        $new_h = $hor*$minfact;
        
        $front = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($front, $image, 0, 0, 0, 0, $new_w, $new_h, $wor, $hor);
        
        imagecopymerge($back, $front,-(($new_w-$width)/2), -(($new_h-$height)/2), 0, 0, $new_w, $new_h, 100);
        
        // output new file
        imagejpeg($back,$dest,90);
        imagedestroy($back);
        imagedestroy($front);
        
        return true;
        }

        image_blurred_bg($_FILES["file"]["tmp_name"],'/image','500','500');

    
}