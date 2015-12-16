<?php
	session_start();
	//Генерируем 5 символов капчи
	$string = "";
	for ($i = 0; $i < 5; $i++)
		$string .= chr(rand(97, 122));
	
	$_SESSION['rand_code'] = $string;	//Записываем сроку в сессию

	$dir = "fonts/";	//Ссылка на шрифт

	$image = imagecreatetruecolor(170, 60);	//Холст для капчи
	$black = imagecolorallocate($image, 0, 0, 0);	//
	$color = imagecolorallocate($image, 200, 100, 90);	//Цвет капчи
	$white = imagecolorallocate($image, 255, 255, 255);	//Цвет фона

	imagefilledrectangle($image,0,0,399,99,$white);	//Прямоугольник
	imagettftext ($image, 30, 0, 10, 40, $color, $dir."verdana.ttf", $_SESSION['rand_code']); //Текст капчи

	header("Content-type: image/png");	//Возвращаем изображение
	imagepng($image);		//Вывод капчи
?>