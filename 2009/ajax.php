<?php
if ($_POST["cmd"] == "inscripcion")
{
	$archivo = "inscriptos.txt";
	if (file_exists($archivo))
	{
		$file = file($archivo);
		foreach ($file as $f)
		{
			list($n, $m, $d) = explode(":", $f);
			
			if ($m == $_POST["mail"])
			{
				echo "-2";
				exit();
			}
			if ($d == $_POST["dni"])
			{
				echo "-1";
				exit();
			}
		}
	}
	
	if (!($fp = fopen($archivo, "a")))
	{
		echo "-3";
		exit();
	}
	
	fwrite($fp, str_replace(":", " ", $_POST["nya"]).":".str_replace(":", " ", $_POST["mail"]).":".str_replace(":", " ", $_POST["dni"])."\n");
	fclose($fp);
	
	if (count($file) < 50)
		echo "1";
	else
		echo "0";
}
?>
