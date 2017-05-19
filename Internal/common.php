<?php

/* 共通処理 */

ini_set('memory_limit', '-1');


	//画像フォルダの中身を全て配列に入れる
	function load_dir($dir){

		//受け取った画像フォルダを読み込み、情報を格納する
		$list = scandir($dir);

		//$sourceを配列として定義
		$source = array();

			//読み込んだ画像フォルダの数分、処理する
			foreach((array)$list as $value){

				//読み込んだ画像が存在するか確認
    				if(is_file($dir . $value)){

					//読み込んだ画像を$source配列の中に格納する
        				$source[] = $dir . $value;

    				}
			}

		//$source配列に格納した情報を返す
		return $source;

	}


	//画像フォルダの数を数える
	function count_dir($path,$number){

		//$arrayを配列として定義
		$array = array();

		//指定されたパスと同じ名前のフォルダを調べてその数分、処理をする
		foreach(glob($path) as $path){

			//定義されている数字を$arrayの配列の中に格納する
			$array[] = $number;
			//数字を新しく定義
			$number++;

		}

		//$array配列に格納した情報を返す
		return $array;
	}


	//$random配列を作成する
	function photo_random($photo_number){

		//$randomを配列として定義
		$random = array();
		//1から素材フォルダの数の範囲内から配列を作成
		$photo = range(1, $photo_number);
		//素材フォルダをシャッフルする
		shuffle($photo);

			//先ほど作成した配列の数分、処理する
			foreach((array)$photo as $photos){

				//シャッフルした配列を新しく別の配列に格納する
				$random[] = $photos;

			}

		//$random配列に格納した情報を返す
		return $random;

	}


	//$random配列が空だったら、フラグを立てる
	function check_array($random,$photo_random){

		//配列の中身が空じゃないか確認
		if(empty($random[$photo_random])){

			//空じゃない場合
			//$flagを1と定義
			$flag = 1;

		}else{

			//空だった場合
			//$flagを0と定義
			$flag = 0;

		}

		//定義した$flagを返す
		return $flag;

	}


	//画像フォルダの中身分、画像を作る
	function create_image($dir){

		//受け取った画像フォルダを読み込み、情報を格納する
		$list = scandir($dir);

		//$sourceを配列として定義
		$sources = array();

			//読み込んだ画像フォルダの数分、処理する
			foreach((array)$list as $value){

				//読み込んだ画像が存在するか確認
				if(is_file($dir . $value)){

					//拡張子がpngだったら
					if(pathinfo($dir . $value, PATHINFO_EXTENSION) == "png"){

						//形を変えて出力
						//pngの場合
						//空のpng画像を$sources配列の中に格納する
						$sources[] = @imagecreatefrompng($dir . $value);

					//その他はすべて拡張子がjpg
					}else{

						//色を変えて出力
						//通常出力
						//空のjpg画像を$sources配列の中に格納する
						$sources[] = @imagecreatefromjpeg($dir . $value);

					}
				}
			}

		//$sources配列に格納した情報を返す
		return $sources;
	}


	//新規ディレクトリを作成する
	function create_dir($path){

		//ディレクトリが存在しているか
		if(!is_dir($path)){

			//存在していなかったら新しくディレクトリを作成
			mkdir($path, 0777);

		}

		//ディレクトリのパスを返す
		return $path;

	}


	//画像の拡張子を取得する
	function get_type($type){

		//拡張子を取得する
		$image_type = @image_type_to_extension($type,false);
		//拡張子に合わせて関数を変え、$create_imageに定義
		$create_image = "imagecreatefrom".$image_type;

		//$create_imageで定義した情報を返す
		return $create_image;

	}




	/* ここから先のソースコードはこちらから引用しました
	http://jp2.php.net/manual/ja/function.imagecreate.php#81604
	bmp変換に対応するためのソースコード
	*/

	/*********************************************/
	/* Fonction: ImageCreateFromBMP              */
	/* Author:   DHKold                          */
	/* Contact:  admin@dhkold.com                */
	/* Date:     The 15th of June 2005           */
	/* Version:  2.0B                            */
	/*********************************************/

	function imagecreatefrombmp($filename){
		//Ouverture du fichier en mode binaire
		   if (! $f1 = fopen($filename,"rb")) return FALSE;

		//1 : Chargement des ent?tes FICHIER
		   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
		   if ($FILE['file_type'] != 19778) return FALSE;

		//2 : Chargement des ent?tes BMP
		   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
		                 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
		                 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
		   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
		   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
		   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
		   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
		   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
		   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
		   $BMP['decal'] = 4-(4*$BMP['decal']);
		   if ($BMP['decal'] == 4) $BMP['decal'] = 0;

		//3 : Chargement des couleurs de la palette
		   $PALETTE = array();
		   if ($BMP['colors'] < 16777216)
		   {
		    $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
		   }

		//4 : Cr?ation de l'image
		   $IMG = fread($f1,$BMP['size_bitmap']);
		   $VIDE = chr(0);

		   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
		   $P = 0;
		   $Y = $BMP['height']-1;
		   while ($Y >= 0)
		   {
		    $X=0;
		    while ($X < $BMP['width'])
		    {
		     if ($BMP['bits_per_pixel'] == 24)
		        $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
		     elseif ($BMP['bits_per_pixel'] == 16)
		     {  
		        $COLOR = unpack("n",substr($IMG,$P,2));
		        $COLOR[1] = $PALETTE[$COLOR[1]+1];
		     }
		     elseif ($BMP['bits_per_pixel'] == 8)
		     {  
		        $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
		        $COLOR[1] = $PALETTE[$COLOR[1]+1];
		     }
		     elseif ($BMP['bits_per_pixel'] == 4)
		     {
		        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
		        if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
		        $COLOR[1] = $PALETTE[$COLOR[1]+1];
		     }
		     elseif ($BMP['bits_per_pixel'] == 1)
		     {
		        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
		        if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
		        elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
		        elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
		        elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
		        elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
		        elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
		        elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
		        elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
		        $COLOR[1] = $PALETTE[$COLOR[1]+1];
		     }
		     else
		        return FALSE;
		     imagesetpixel($res,$X,$Y,$COLOR[1]);
		     $X++;
		     $P += $BMP['bytes_per_pixel'];
		    }
		    $Y--;
		    $P+=$BMP['decal'];
		   }

		//Fermeture du fichier
		   fclose($f1);

		return $res;
	}
?>