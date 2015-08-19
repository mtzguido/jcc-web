<?php
	function tituloCharla($ID){
		global $charlas;
		echo "<a href='#" . $ID ."' class='facebox-frame'>";
		echo $charlas[$ID]['shortTitle'];
		echo "</a>";
	}

	function extract_attr($contents, $attr) {
		preg_match("/(^|\n)" . $attr . ": *(.+)(\n|$)/", $contents, $matches);
		if (count($matches) < 3)
			return "";

		return trim($matches[2]);
	}

	# Can only be (reasonably) used for the last attr
	function extract_attr_multiline($contents, $attr) {
		preg_match("/(^|\n)" . $attr . ": *(.+)$/s", $contents, $matches);
		if (count($matches) < 3)
			return "";

		return trim($matches[2]);
	}

	$charlas = array();
	$charlas_dir = scandir("data/");

	foreach ($charlas_dir as $file) {
		if ($file == '.' || $file == '..')
			continue;

		if (!preg_match("/.txt$/", $file))
			continue;

		$contents = file_get_contents("data/" . $file);

		$author =	extract_attr($contents, "Author");
		$ID =		extract_attr($contents, "ID");
		$title =	extract_attr($contents, "Title");
		$shtitle =	extract_attr($contents, "ShortTitle");
		$inst =		extract_attr($contents, "Institution");
		$link =		extract_attr($contents, "Link");
		$slides =	extract_attr($contents, "Slides");
		$abstract =	extract_attr_multiline($contents, "Abstract");

		if ($ID != ""){
			$charlas[$ID]['author'] = $author;
			$charlas[$ID]['id'] = $ID;
			$charlas[$ID]['title'] = $title;
			$charlas[$ID]['shortTitle'] = $shtitle;
			$charlas[$ID]['inst'] = $inst;
			$charlas[$ID]['abstract'] = $abstract;
			$charlas[$ID]['link'] = $link;
			$charlas[$ID]['slides'] = $slides;
		}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>

<title>Jornadas de Ciencias de la Computación 2015</title>
<link rel="shortcut icon" href="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<!-- lighbox -->
<script src="lightbox.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('a[class*=facebox-frame]').facebox();
		$('[class*=descr-charla]').hide();
	})
</script>
<!-- /lighbox -->

<link rel="stylesheet" type="text/css" href="estilos.css" />

</head>
<body>

<div id="header" class="row">
	<div class="column izq">
	<a href="index.php" title="XIII Jornadas de Ciencias de la Computación">
		<img id="logo" src="logo.png" alt="L.C.C." />
	</a>
	</div>
	<div class="column der">
	<h1>XIII Jornadas de Ciencias de la Computación</h1>
	<div class="fechalugar">21, 22 y 23 de Octubre de 2015 - Rosario, Santa Fe, Argentina</div>
	</div>
</div>

<div id="body" class="row">
	<div class="column unica">
		<div id="social" class="row">
			<div id="contactanos" class="column izq">
				
			</div>
			<div id="botones" class="column der">
				<a href="http://fb.com/jccfceia" target="_blank" title="Facebook de las JCC" class="fb">Facebook</a><a href="https://plus.google.com/112531481325644410892" rel="publisher" class="goog">Google+</a><a href="https://www.youtube.com/channel/UC-CReVEx4-3AfJOH1Tr-udw" class="yt">You<span style="color:red">Tube</span></a>
			</div>
		</div>

		<div class="cont">

			<h2>Sobre las jornadas</h2>

			<p>Las Jornadas de Ciencias de la Computación (JCC) se presentan como una iniciativa del
			Departamento de Ciencias de la Computación de la Facultad de Ciencias Exactas, Ingeniería y
			Agrimensura de la Universidad Nacional de Rosario, de carácter abierto y gratuito con el
			objetivo de promover el contacto de los alumnos de la Facultad con investigadores y
			profesionales en temas relacionados con el ámbito de las ciencias de la computación, al
			mismo tiempo que nos permite mantenernos actualizados sobre las tendencias en investigación
			y desarrollo que se realizan en la región.</p>

			<p>La edición 2015 de las JCC se llevará a cabo los días 21, 22 y 23 de Octubre en
			la Facultad de Ciencias Exactas, Ingeniería y Agrimensura de Rosario, en
			el horario de 10:00 a 20:00</p>

			<p>Las JCC se llevaron a cabo por primera vez en noviembre del año 2000. Año tras año
			han participado decenas de personas provenientes de empresas de desarrollo
			de software local, estudiantes e investigadores de esta casa de estudios y de universidades
			destacadas de la zona, entre las cuales podemos mencionar a la Universidad Nacional de La
			Plata, Universidad Nacional de Córdoba, Universidad Nacional de Río Cuarto y la Universidad
			de la República (Montevideo-Uruguay). La realización de las JCC es un proceso que continúa
			año a año y constituye un logro significativo del cuerpo de docentes y de estudiantes de la
			carrera Licenciatura en Ciencias de la Computación.</p>

			<p>Próximamente estaremos publicando más información acerca de esta edición de las jornadas.</p>

		</div>
		<div class="cont">
			<h2>Charlas confirmadas</h2>
			<ul id="exposiciones">
			<?php
				foreach($charlas as $charla){
					if ($charla['author'] != "" && $charla['title'] != "" && $charla['id'] != "")
						echo '<li> <a href="#' .
							$charla['id'] .
							'" class="facebox-frame"> <b> '.
							$charla['author'] .
							( $charla['inst'] != "" ? ' (' . $charla['inst'] . ')' : '' ) . '</b> - ' .
							$charla['title'] . "</a> </li>\n";
				}
			?>
			</ul>
		</div>

		<?php
			foreach($charlas as $charla){
				if ($charla['id'] != "" && $charla['title'] != "" && $charla['author'] != "" && $charla['abstract'] != ""){
					echo '<div id="' . $charla['id'] . '" class="descr-charla">';
					echo '<h3>' . $charla['title'] . '</h3>';
					echo $charla['abstract'];
					echo '<p class="autor"> por ' . $charla['author'];
					if ($charla['inst'] != "" && $charla['link'] != "")
						echo ' (<a target="_blank" href="' . $charla['link'] . '">' . $charla['inst'] . '</a>)';
					elseif ($charla['inst'] != "")
						echo ' (' . $charla['inst'] . ')';
					echo "</p>";

					if ($charla['slides'] != "") {
						echo '<p>';
						echo '<a href="slides/' . $charla['slides'] . '" >Slides</a>';
						echo '</p>';
					}

					echo "</div>\n";
				}
			}
		?>
		<div id="cena" class="descr-charla">
			<h3>Cena de camaradería</h3>
			<p>
			<b>Lugar:</b> </p>
		</div>
		<div class="cont">

		</div>

		<div class="cont">
			<h2>Patrocinan</h2>
			<table class="tabla-spons-auspic">
				<tr>
					<td><a target=_blank title="Google" href="http://www.google.com"><img src="logos/logo-google.png" width=350 /></a></td>
					<td><a target=_blank title="Fundación Sadosky" href="http://www.fundacionsadosky.org.ar/"><img src="logos/logo-sadosky.png" width=250 /></a></td>
				</tr>
			</table>
		</div>
		<div class="cont">
			<h2>Auspiciantes</h2>
			<table class="tabla-spons-auspic">
				<tr>
					<td><a target=_blank title="CIFASIS" href="http://www.cifasis-conicet.gov.ar/"><img src="logos/logo-cifasis.png" /></a></td>
					<td><a target=_blank title="Facultad de Ciencias Exactas, Ingeniería y Agrimensura" href="http://www.fceia.unr.edu.ar/"><img src="logos/logo-fceia.png" /></a></td>
					<td><a target=_blank title="Universidad Nacional de Rosario" href="http://www.unr.edu.ar/"><img src="logos/unr.png" /></a></td>
				</tr>
			</table>
		</div>

	</div>
</div>

<div id="mega-footer">
	<div id="footer-t" class="row">
		<div class="column izq">
			<div class="cont">
				<h3>Links de interés</h3>
				<ul>
					<li><a target=_blank href="http://www.fceia.unr.edu.ar/lcc" title="Sitio web de la carrera Licenciatura en Ciencias de la Computación">Licenciatura en Ciencias de la Computación</a></li>
					<li><a target=_blank href="http://www.fceia.unr.edu.ar/" title="Sitio web de la Facultad de Ciencias Exactas, Ingeniería y Agrimensura">Facultad de Ciencias Exactas, Ingeniería y Agrimensura</a></li>
					<li><a target=_blank href="http://www.unr.edu.ar/" title="Sitio web de la Universidad Nacional de Rosario">Universidad Nacional de Rosario</a></li>
				</ul>
			</div>
		</div>
		<div class="column der">
			<div class="cont">
				<h3>Contacto</h3>
				<ul>
					<li><strong>Dirección:</strong> Pellegrini 250 - Rosario, Santa Fe, Argentina <a target="_blank" href=http://goo.gl/YsiHGS>(mapa)</a></li>
					<li><strong>Teléfono:</strong> 0341-48026 49 al 59 int 231 (de 8 a 14hs)</li>
					<li><strong>Fax:</strong> 0341-4802654</li>
					<li><strong>E-mail:</strong> <tt>jcc [@] fceia.unr.edu.ar</tt></li>
				</ul>
			</div>
		</div>
	</div>
	<div id="footer-b" class="row">
		<div class="column completa">
			<div class="cont">
				<strong>Otras ediciones:</strong>
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2005">2005</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2006">2006</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2007">2007</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2008">2008</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2009">2009</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2010">2010</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2011">2011</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2012">2012</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2013">2013</a>,
				<a target=_blank href="http://www.fceia.unr.edu.ar/lcc/jcc/2014">2014</a>
			</div>
		</div>
	</div>
</div>

</body>
</html>
