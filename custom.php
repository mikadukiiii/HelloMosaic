<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<!-- CSSを呼び出す  -->
<link href="Internal/style.css" rel="stylesheet" type="text/css">
<title>Hello Mosaic!!</title>
<?php

/*処理の呼び出し*/

ini_set( 'display_errors', 1 );

//写真のアップロード処理
require_once 'Internal/upload.php';
//選択項目が正しくチェックされているか確認処理
require_once 'Internal/check.php';


//モザイクアートの素材となるフォルダの定義
$dir = "Source/Photo/";

/*SettingManager.phpを呼び出す*/
require_once 'Internal/SettingManager.php';

//POSTから取得したデータを配列に格納
$image_date = array('tile_width' => $tile_width , 'ratio_width' => $ratio_width , 'ratio_height' => $ratio_height);

//オリジナルのリサイズ
$image_size = OriginalResize($uploadfile,$image_date,$pattern);

//素材のリサイズ
PhotoResize($dir,$image_size,$pattern);

//画像の分割
OriginalSplit($image_size,$pattern);

?>
<script type="text/javascript">
<!-- サンプル画像を別窓で呼び出し  -->
function ImageUp($image) {
<!-- サンプル画像を別窓で表示  -->
window.open($image ,"Sample","width=4000");
}
</script>
</head>
<body>
<div class="title" style="width: 728px">Hello Mosaic!!</div><br/><br/>
<div class="img"><img alt="モザイクアートの元の画像" height="400" src="<?php echo $uploadfile; ?>" width="500"></div>
<br/><br/><div class="text">

精密さを重視→類似画像で最も似ているものだけが出力されます。綺麗なモザイクアートを作りたい人におすすめです<br/>
枚数を重視→類似画像のしきい値1～5の中からランダムで出力されます。多くの画像を見せたい人におすすめです<br/><br/>

Lab色空間変換 出来上がり例:<a href="javascript:ImageUp('./Source/Sample/Lab.jpg');">Sample1</a> <a href="javascript:ImageUp('./Source/Sample/Lab2.jpg')">Sample2</a><br/>
人間の視覚の近似するように作られています<br/>
<div class="back2">
<a class="button2" href="finish.php?id=3&first=0&last=0">精密さを重視</a>
<a class="button2" href="finish.php?id=3&first=0&last=4">枚数を重視</a>
</div><br/><br/>


RGB変換 出来上がり例:<a href="javascript:ImageUp('./Source/Sample/RGB.jpg');">Sample1</a> <a href="javascript:ImageUp('./Source/Sample/RGB2.jpg');">Sample2</a><br/>
パソコンのフルカラー表現として活用されています<br/>
<div class="back2">
<a class="button2" href="finish.php?id=1&first=0&last=0">精密さを重視</a>
<a class="button2" href="finish.php?id=1&first=0&last=4">枚数を重視</a>
</div><br/><br/>


HSV変換 出来上がり例:<a href="javascript:ImageUp('./Source/Sample/HSV.jpg');">Sample1</a> <a href="javascript:ImageUp('./Source/Sample/HSV2.jpg');">Sample2</a><br/>
明度を重視する傾向にあります<br/>
<div class="back2">
<a class="button2" href="finish.php?id=2&first=0&last=0">精密さを重視</a>
<a class="button2" href="finish.php?id=2&first=0&last=4">枚数を重視</a>
</div><br/><br/>

</div>
</body>

</html>