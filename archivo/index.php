<?php
	$talks = array();

	function talk_link($ID) {
		global $talks;
		echo '<a href="#' . $ID .'" class="facebox-frame">';
		echo $talks[$ID]['shortTitle'];
		echo '</a>';
	}

	function makebox($talk) {
		echo '<div id="' . $talk['id'] . '" class="descr-charla">';
		echo '<h3>' . $talk['title'] . '</h3>';
		echo $talk['abstract'];
		echo '<p class="autor"> por ' . $talk['author'];
		if ($talk['inst'] != "" && $talk['link'] != "")
			echo ' (<a target="_blank" href="' . $talk['link'] . '">' . $talk['inst'] . '</a>)';
		elseif ($talk['inst'] != "")
			echo ' (' . $talk['inst'] . ')';
		echo "</p>";

		if ($talk['slides'] != "") {
			echo '<p>';
			echo '<a href="slides/' . $talk['slides'] . '" >Slides</a>';
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

			$author =	extract_attr($contents, "Author");
			$ID =		extract_attr($contents, "ID");
			$title =	extract_attr($contents, "Title");
			$shtitle =	extract_attr($contents, "ShortTitle");
			$inst =		extract_attr($contents, "Institution");
			$link =		extract_attr($contents, "Link");
			$slides =	extract_attr($contents, "Slides");
			$abstract =	extract_attr_multiline($contents, "Abstract");

			if ($ID != ""){
				if ($shtitle == "")
					$shtitle = $title;

				$talks[$ID]['author'] = $author;
				$talks[$ID]['id'] = $ID;
				$talks[$ID]['title'] = $title;
				$talks[$ID]['shortTitle'] = $shtitle;
				$talks[$ID]['inst'] = $inst;
				$talks[$ID]['abstract'] = $abstract;
				$talks[$ID]['link'] = $link;
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
			echo "<b>" . $talk['author'] . "</b>: " . $talk['shortTitle'];

			if ($talk['abstract']) {
				echo ' (<a href=#' .
					$talk['id'] .
					' class="facebox-frame">Abstract</a>)';
			}

			if ($talk['slides']) {
				echo " (<a href=" . $basedir . "/slides/" . $talk['slides'] . ">Slides</a>)";
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
