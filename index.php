<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<!-- CSSを呼び出す  -->
<link href="Internal/style.css" rel="stylesheet" type="text/css">
<title>Hello Mosaic!!</title>
<!-- jqueryを使用するため呼び出す  -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- 使用される画像をプレビューとして表示する -->
<script type="text/javascript">
$(function(){
	var setFileInput = $('.imgInput'),
	setFileImg = $('.imgView');

	setFileInput.each(function(){
        	var selfFile = $(this),
        	selfInput = $(this).find('input[type=file]'),
        	prevElm = selfFile.find(setFileImg),
        	orgPass = prevElm.attr('src');

        	selfInput.change(function(){
            		var file = $(this).prop('files')[0],
            		fileRdr = new FileReader();
 
            			if (!this.files.length){
                			prevElm.attr('src', orgPass);
                			return;

            			} else {

                		if (!file.type.match('image.*')){
                    			prevElm.attr('src', orgPass);
                    			return;
                		} else {
                    			fileRdr.onload = function() {
                        		prevElm.attr('src', fileRdr.result);
                    		}

                    fileRdr.readAsDataURL(file);

                }
            }
        });
    });
});
</script>
</head>

<body>
<div class="title" style="width: 728px">Hello Mosaic!!</div><br/>
<form action="custom.php" method="POST" enctype="multipart/form-data">
<div class="imgInput">
<div class="back">
<div class="button"><label for="file_photo">写真をアップロード<input type="file" id="file_photo" name="photo" style="display:none;" accept="image/*"></label></div>
</div>
<!-- ポップアップで表示 -->
<div class="link"><a href="javascript:w=window.open('photo_upload.php','','scrollbars=yes,Width=300,Height=150');w.focus();">素材のアップロードはこちらから</a></div>
<div class="img">
<img src="Source/Sample/NoImage.png" alt="モザイクアートになる画像" class="imgView" width="600" height="500">
<br/><p>横に配置するタイル数<br/>
<select name='tile'>
<option value='10'>10</option>
<option value='20'>20</option>
<option value='30'>30</option>
<option value='40'>40</option>
<option value='50' selected>50</option>
<option value='60'>60</option>
<option value='70'>70</option>
<option value='80'>80</option>
<option value='90'>90</option>
<option value='100'>100</option>
</select>
</p><br/>
<p>素材のタイルのサイズ比<br/>
<input type="radio" name="ratio" value="Square"> 1:1(正方形)
<input type="radio" name="ratio" value="DSLR"> 3:2(長方形)
<input type="radio" name="ratio" value="Rectangle"> 4:3(長方形)
<input type="radio" name="ratio" value="Movie"> 19:6(長方形)
</p><br/>
<p>素材のタイルのパターン<br/>
<input type="radio" name="pattern" value="Landscape"> 横向き
<input type="radio" name="pattern" value="Portrait"> 縦向き
</p><br/>
</div>
</div>

<div class="back2">
<input type="submit" value="SET" />
</form>
</div>
</body>

</html>