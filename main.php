<?php
$arr = pathinfo($_SERVER['REDIRECT_URL']);
$url = $arr['basename'];
if ($url =='construction') {
    $url ='index';
}
$url.='.php';
$title ='';
$meta =[];
$css =[];
$js = [];
$fonts = [];

include_once 'global.php';
include_once 'function.php';
include_once $url;

$pageResult = isset($pageResult) ? $pageResult : [];

view('tmp/head', [
    'title' => $title,
    'meta' => $meta,
    'css' => $css,
    'js' => $js,
    'fonts' => $fonts
]);
view('tmp/header', ['url' => $url]);

view('tmp/'.substr($url, 0, strrpos($url, '.')), $pageResult);
view('tmp/footer');
view('tmp/scripts', ['js' => $js]);