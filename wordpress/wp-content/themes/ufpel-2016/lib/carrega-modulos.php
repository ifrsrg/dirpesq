<?php

function carregaModulos( $listamodulos ) {

	if ($listamodulos) {
		foreach(explode(",", $listamodulos) as $modulo) {
			$modulo = explode("-", $modulo);
			$func = "modulo".$modulo[0];

			if (!function_exists($func)) {
				$dir = dirname(__FILE__).'/../modulos';
				$diratual = getcwd();
				chdir($dir);
				$arquivos = glob("*$func");
				chdir($diratual);
				foreach ($arquivos as $item) {
					echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_url').'/modulos/'.$item.'/estilo.css" />';
					include "$dir/$item/modulo.php";
				}
			}

			$func($modulo[1], $modulo[2]);
		}
	}
}

?>