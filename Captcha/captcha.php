<?php
	session_start();
	$chars = 'abdefhknrstyz23456789'; // Задаем символы, используемые в капче. Разделитель использовать не надо.
		  $length = rand(4, 7); // Задаем длину капчи, в нашем случае - от 4 до 7
		  $numChars = strlen($chars); // Узнаем, сколько у нас задано символов
	$string = "";
	for ($i = 0; $i < $length; $i++)
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	
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