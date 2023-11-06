<?php
$sourceFolder = 'input/';
$destinationFolder = 'output/';
$text = readline('Enter watermark text: ');

if (!is_dir($destinationFolder)) {
    mkdir($destinationFolder);
}

$files = scandir($sourceFolder);

foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $imageInfo = getimagesize($sourceFolder . $file);
        $imageType = $imageInfo[2];

        if ($imageType === IMAGETYPE_GIF) {
            continue;
        }

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourceFolder . $file);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourceFolder . $file);
                break;
            default:
                continue 2;
        }
        // set your watermark opacity
        $opcaity = 50;
        
        $red = imagecolorallocatealpha($image, 255, 0, 0, $opcaity);
        $orange = imagecolorallocatealpha($image, 255, 165, 0, $opcaity);
        $yellow = imagecolorallocatealpha($image, 255, 255, 0, $opcaity);
        $green = imagecolorallocatealpha($image, 0, 128, 0, $opcaity);
        $blue = imagecolorallocatealpha($image, 0, 0, 255, $opcaity);
        $purple = imagecolorallocatealpha($image, 128, 0, 128, $opcaity);
        $black = imagecolorallocatealpha($image, 0, 0, 0, $opcaity);
        $white = imagecolorallocatealpha($image, 255, 255, 255, $opcaity);

        // set your watermark color
        $color = $orange;

        $width = imagesx($image);
        $height = imagesy($image);

        $textSize = $width / 20;
        $textX = ($width - (strlen($text) * 10)) / 4;
        $textY = $height / 2;

        imagettftext($image, $textSize, 0, $textX, $textY, $color, 'ariblk.ttf', $text);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $destinationFolder . $file);
                break;
            case IMAGETYPE_PNG:
                imagepng($image, $destinationFolder . $file);
                break;
        }

        imagedestroy($image);
    }
}
