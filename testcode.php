<?php

ini_set( 'display_errors', 1 );

require_once 'Internal/SettingManager.php';
require_once 'Internal/OutputManager.php';
require_once 'Internal/ParallelManager.php';


$image = "Source/original.png";
$dir = "Source/Photo/";

$type = 3;
$min = 0;
$max = 4;
$season = 1;
$mask = 2;
$output = 1;
$tile_width = 50; //20 ~ 100
$ratio_width = 3; //1,2,3,9
$ratio_height = 4; //1,3,4,16
$pattern = 'Landscape'; //横長
//$pattern = 'Portrait'; //縦長


$image_date = array('tile_width' => $tile_width , 'ratio_width' => $ratio_width , 'ratio_height' => $ratio_height);

$start_time=microtime(true);
echo "開始時間： ".date('Y-m-d H:i:s',(int)$start_time)."<br>";

//$image_size = OriginalResize($image,$image_date,$pattern);

//PhotoResize($dir,$image_size,$pattern);

//OriginalSplit($image_size,$pattern);

MosaicArtCreate($type,$min,$max);

//FormMosaicActOutput();

MosaicActOutput();

//ColorMosaicActOutput();

$end_time=microtime(true);
echo "終了時間： ".date('Y-m-d H:i:s',(int)$end_time)."<br>";
$syori_zikan=$end_time - $start_time;
echo "処理時間：".sprintf('%0.5f',$syori_zikan)."秒<br>";

?>