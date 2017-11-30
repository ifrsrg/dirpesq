<?php

// Inicializa o verificador personalizado de atualização do tema

$updateChecker = new ThemeUpdateChecker(
	'ufpel-2016',
	'http://stw-dev.ufpel.edu.br/temas/ufpel-2016/info.json'
);

if ($pagenow == "themes.php"){
		$updateChecker->checkForUpdates();
}

?>