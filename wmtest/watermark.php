<?php
    $source = "source";

    $destination = "destination";

    $watermark = imagecreatefrompng("watermark.png");

    $marginRight = 5;
    $marginBottom = 5;

    $sx = imagesx($watermark);
    $sy = imagesy($watermark);

    $images = array_diff(scandir($source), array("..", "."));

    foreach($images as $image){
        $img = imagecreatefromgif($source."/".$image);

        imagecopy($img, $watermark, imagesx($img) - $sx - $marginRight, imagesy($img) - $sy - $marginBottom, 0, 0, $sx, $sy);

        $i = imagegif($img, $destination."/".$image);

        imagedestroy($img);
    }

?>