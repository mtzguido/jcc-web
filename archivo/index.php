<?php
	$talks = array();

	function talk_link($ID) {
		global $talks;
		echo '<a href="#' . $ID .'" class="facebox-frame">';
		echo $talks[$ID]['shortTitle'];
		echo '</a>';
	}

	function render_authors_brief($talk) {
		for ($i = 0; $i < count($talk['authors']); ++$i) {
			if ($i != 0)
				echo ", ";

			if ($talk['insts'][$i] == "" && $talk['links'][$i] == "") {
				echo $talk['authors'][$i];
			} else if ($talk['insts'][$i] != "" && $talk['links'][$i] == "") {
				echo $talk['authors'][$i] .
					' (' . $talk['insts'][$i] . ')';
			} else if ($talk['insts'][$i] != "" && $talk['links'][$i] != "") {
				echo $talk['authors'][$i] .
					' (<a target=_blank href=' . $talk['links'][$i] . '>' .
					$talk['insts'][$i] . '</a>)';
			} else if ($talk['insts'][$i] == "" && $talk['links'][$i] != "") {
				echo '<a target=_blank href=' . $talk['links'][$i] . '>' .
					$talk['authors'][$i] . '</a>';
			}
		}
	}

	function render_authors($talk) {
		echo '<p class="autor"> por ';

		render_authors_brief($talk);

		echo "</p>";
	}

	function makebox($talk) {
		echo '<div id="' . $talk['id'] . '" class="descr-charla">';
		echo '<h3>' . $talk['title'] . '</h3>';
		echo $talk['abstract'];

		render_authors($talk);

		if ($talk['slides'] != "") {
			echo '<p>';
			echo '<a target=_blank href="slides/' . $talk['slides'] . '" >Slides</a>';
			echo '</p>';
		}

		echo "</div>";
	}

	function extract_attr($contents, $attr) {
		preg_match("/(^|\n)" . $attr . ": *(.+)(\n|$)/", $contents, $matches);
		if (count($matches) < 3)
			return "";

		return trim($matches[2]);
	}

	function extract_attr_list($contents, $attr) {
		$t = extract_attr($contents, $attr);
		return array_map('trim', explode(',', $t));
	}

	# Can only be (reasonably) used for the last attr
	function extract_attr_multiline($contents, $attr) {
		preg_match("/(^|\n)" . $attr . ": *(.+)$/s", $contents, $matches);
		if (count($matches) < 3)
			return "";

		return trim($matches[2]);
	}

	function setup($basedir, $info) {
		global $talks;
		$talks = array();
		$talks_dir = scandir($basedir . "/data/");

		foreach ($talks_dir as $file) {
			if ($file == '.' || $file == '..')
				continue;

			if (!preg_match("/.txt$/", $file))
				continue;

			$contents = file_get_contents($basedir . "/data/" . $file);

			/*
			 * Authors, Institutions y Links son arrays
			 * separados por comas, para poder representar
			 * cuando hay mas de un disertante posiblemente de
			 * de distintas facultades/empresas. La 's' al final
			 * es opcional.
			 */
			$authors =	extract_attr_list($contents, "Authors?");
			$insts =	extract_attr_list($contents, "Institutions?");
			$links =	extract_attr_list($contents, "Links?");

			/*
			 * Arreglamos para que todos tengan la misma longitud,
			 * cosa de no tener que preocuparnos después for
			 * acceder fuera de los límites.
			 */
			$max = count($authors);
			if (count($insts) > $max) $max = count($insts);
			if (count($links) > $max) $max = count($links);

			for ($i = count($authors); $i < $max; $i++) $authors[$i] = "";
			for ($i = count($insts); $i < $max; $i++) $insts[$i] = "";
			for ($i = count($links); $i < $max; $i++) $links[$i] = "";


			$ID =		extract_attr($contents, "ID");
			$title =	extract_attr($contents, "Title");
			$shtitle =	extract_attr($contents, "ShortTitle");
			$slides =	extract_attr($contents, "Slides");
			$abstract =	extract_attr_multiline($contents, "Abstract");


			if ($ID != ""){
				if ($shtitle == "")
					$shtitle = $title;

				$talks[$ID]['authors'] = $authors;
				$talks[$ID]['insts'] = $insts;
				$talks[$ID]['links'] = $links;

				$talks[$ID]['id'] = $ID;
				$talks[$ID]['title'] = $title;
				$talks[$ID]['shortTitle'] = $shtitle;
				$talks[$ID]['abstract'] = $abstract;
				$talks[$ID]['slides'] = $slides;

				makebox($talks[$ID]);
			}
		}
	}

	function render_info($basedir, $info) {
		global $talks;

		echo "<a href=../" . $info['year'] . ">";
		echo "<b>" . $info['year'] . ": " . $info['name'] . "</b><br>";
		echo "</a>";

		foreach ($talks as $talk) {
			echo '<b>';
			render_authors_brief($talk);
			echo '</b>: ' . $talk['shortTitle'];


			if ($talk['abstract']) {
				echo ' (<a href=#' .
					$talk['id'] .
					' class="facebox-frame">Abstract</a>)';
			}

			if ($talk['slides']) {
				echo " (<a target=_blank href=" . $basedir . "/slides/" . $talk['slides'] . ">Slides</a>)";
			}

			echo "<br>";
		}
	}
?>


<html xmlns="http://www.w3.org/1999/xhtml" >
<head>

<title>Archivo JCC</title>
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
		<a href="../index.html" title="volver a Jornadas de Ciencias de la Computación">
			<img src="logo.png" alt="Logo L.C.C." />
		</a>
	</div>
	<div class="column der">
		<h1>Jornadas de Ciencias de la Computación</h1>
		<div class="fechalugar">Registro de charlas de JCC anteriores</div>
	</div>
</div>

<div id="body" class="row">
	<div class="column unica" id="cont">
		<?php
			$years = scandir("../", SCANDIR_SORT_DESCENDING);
			foreach ($years as $dir) {
				if ($dir == '.' || $dir == '..')
					continue;

				if (!preg_match("/^[0-9]+$/", $dir))
					continue;

				if (!file_exists("../" . $dir . "/data/info.txt"))
					continue;

				$contents = file_get_contents("../" . $dir . "/data/info.txt");

				$info['year'] = extract_attr($contents, "Year");
				$info['name'] = extract_attr($contents, "Name");

				setup("../" . $dir, $info);
				render_info("../" . $dir, $info);
			}
		?>
	</div>
</div>

</body>
</html>
