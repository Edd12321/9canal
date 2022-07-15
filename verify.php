<?php
session_start();
$pos = random_int(-5, 10);

$swirl = random_int(30, 50);
$freq = random_int(1, 2);
$wave = random_int(1, 2);
$lines = random_int(3, 9);

if (!isset($_SESSION["solve"])) {
    $chars = 'QqWwEeRrTtYyUuIiOoPpAaSsDdFfGgHhJjKkLlZzXxCcVvBbNnMm1234567890';
    for ($i = 0; $i < 6; ++$i) {
        $_SESSION["solve"] .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
}
$image = new Imagick();
$back = new ImagickPixel();
$back->setColor('white');
$draw = new ImagickDraw();

for ($i = 0; $i < $lines; ++$i)
    $draw->line(random_int(0, 120), random_int(0, 120), random_int(0, 120), random_int(0, 120));

$font = 'LessPerfectDOSVGA.ttf';
if (!file_exists('postnum.txt')) {
    $font = '../' . $font;
    if (is_numeric(basename(getcwd())) && !is_file('lock.dat'))
        $font = '../' . $font;
}

$draw->setFont($font);
$draw->setFontSize('40');

$image->newImage(130, 45, $back);

if (isset($_SESSION["solve"])) {
    $image->annotateImage($draw, 10, 30, $pos, $_SESSION["solve"]);
}
$image->swirlImage($swirl);
$image->waveImage($freq, $wave);

$image->drawImage($draw);
$image->setImageFormat('bmp');

$_SESSION["captcha"] = '<img src="data:image/jpg;base64, ' . base64_encode($image->getImageBlob()) . '" alt="" align="left" />';
?>
