<?php

ini_set('memory_limit', '-1');
ini_set("gd.jpeg_ignore_warning", 1);


/* ���ʏ����Ăяo�� */

require_once 'common.php';



/* �ʏ�o�� */


	//���U�C�N�A�[�g�̌��ƂȂ��������摜�ƃ��U�C�N�A�[�g�̕����摜����������
	function MosaicSynthesis($file_back,$file_alpha){

		//$quality���`
		$quality = 100;

		//common.php��load_dir�֐����Ăяo��
		$back_dir = load_dir($file_back);

		//common.php��load_dir�֐����Ăяo��
		$alpha_dir = load_dir($file_alpha);

				//���U�C�N�A�[�g�̌��ƂȂ��������摜�ƃ��U�C�N�A�[�g�̕����摜���A�������s��
				for($count = 0; $count <= count($back_dir); $count++){

						//��̉摜�𐶐�
						//�w�i�ƂȂ�摜
						$image_back = @imagecreatefromjpeg($back_dir[$count]);
						//��̉摜�𐶐�
						//���߂�����摜
						$image_alpha = @imagecreatefromjpeg($alpha_dir[$count]);

						//�w�i�ƂȂ�摜�̏��𓾂�
						list($width, $height, $type) = @getimagesize($back_dir[$count]);

						//�ăT���v�����O
						@imagecopymerge(
							//�w�i�摜
							$image_back,
							//�R�s�[���摜
							$image_alpha,
							//�w�i�摜�� x ���W
							0,
							//�w�i�摜�� y ���W
							0,
							//�R�s�[���� x ���W
							0,
							//�R�s�[���� y ���W
							0,
							//�R�s�[���摜�t�@�C���̕�
							$width,
							//�R�s�[���摜�t�@�C���̍���
							$height,
							//���ߓx(%)
							30
						);

						//jpeg�`���ŉ摜��ۑ�
						@imagejpeg($image_back,$back_dir[$count],$quality);

						//$image_back��j��
						@imagedestroy($image_back);
						//$image_alpha��j��
						@imagedestroy($image_alpha);

				}

	}



	//�ׂ������������摜���܂��ɕ��������摜�ɓ��Ă͂߂�
	function ImageChange($dir,$number,$flag){

		//common.php��load_dir�֐����Ăяo��
		$large_dir = load_dir("../LargeSplit/");


			//$flag��2��������
			//�`��ς������U�C�N�A�[�g���o�͂���ꍇ
			if($flag == 2){
				//png�̏ꍇ
				//$quality���`
				$quality = 9;
			}else{
				//jpg�̏ꍇ
				//$quality���`
				$quality = 100;
			}

		//�z���0����X�^�[�g�Ȃ̂ŁA1�������ē��Ă͂܂镪���摜�ɍs���悤�ɂ���
		$number = $number - 1;

		//���ݓǂݍ���ł���摜�ɓ��Ă͂܂镪���摜���`
		$image = $large_dir[$number];

				//�摜�t�@�C���̏��𓾂�
				list($targetsize_x,$targetsize_y) = @getimagesize($image);

				//�摜�̖��O���`
				$outfile = $image;

				//�擾���������Q�Ƃ��ĉ摜�𐶐�����
				$new_image = @imagecreatetruecolor($targetsize_x,$targetsize_y);

					//$flag��2��������
					//�`��ς������U�C�N�A�[�g���o�͂���ꍇ
					if($flag == 2){
						//�u�����h���[�h�𖳌��ɂ���
						@imagealphablending($new_image, false);
						//���S�ȃA���t�@�`���l������ۑ�����t���O��on�ɂ���
						@imagesavealpha($new_image, true);
					}

				//common.php��create_image�֐����Ăяo��
				$small_dir = create_image($dir);

				//$x���`
				$x = 0;
				//$y���`
				$y = 0;
				//$index���`
				$index = 0;

					//�������I���܂Ŗ������[�v������
					while(true){

						//$small_dir�̔z��ɓ����Ă���摜��ǂݍ���ŁA�����`
						$width = @imagesx($small_dir[$index]);
						//$small_dir�̔z��ɓ����Ă���摜��ǂݍ���ŁA�������`
						$height = @imagesy($small_dir[$index]);

						//�摜���ăT���v�����O����
						@imagecopy(
							$new_image,
							$small_dir[$index],
							$x,
							$y,
							0,
							0,
							$width,
							$height
						);

						//�V���������`
						$x = $x + $width;
							//�t�@�C�����̉摜�̕������݂̕��ȏ�̏ꍇ
							if($x >= $targetsize_x){
								//�V����$x���`
								$x = 0;
								//�V�����������`
								$y = $y + $height;

									//�t�@�C�����̉摜�̍��������݂̍����ȏ�̏ꍇ
									if($y >= $targetsize_y){
										//�������I��点��
										break;
									}
							}

						//���̔z��Ɉڂ�
						$index++;

							//���݂̔z��̃L�[��$small_dir�̔z��ɓ����Ă���摜�����傫���ꍇ
							if($index >= count($small_dir)){
								//�V����$index���`
								$index = 0;
							}

					}

			//�摜��ۑ�����
			//$flag��0�̏ꍇ
			if($flag == 0){

			//�ʏ�o��
			//jpeg�`���ŉ摜��ۑ�
			@imagejpeg($new_image,$outfile,$quality);

			//$flag��3�̏ꍇ
			}else if($flag == 2){

			//�`��ς��ďo��
			//png�`���ŉ摜��ۑ�
			@imagepng($new_image,"../FormLargeSplit/".basename($outfile,".jpg").".png",$quality);

			//$flag��1�̏ꍇ
			}else{

			//�F��ς��ďo��
			//jpeg�`���ŉ摜��ۑ�
			@imagejpeg($new_image,"../ColorLargeSplit/".basename($outfile),$quality);

			}

			//$new_image��j��
			@imagedestroy($new_image);

	}

?>