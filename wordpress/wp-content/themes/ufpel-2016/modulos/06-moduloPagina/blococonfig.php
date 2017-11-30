<div id='configPagina' class="configs" style="display:none;">
    <fieldset class="blococonfig">
        <legend>PÁGINA ESTÁTICA</legend>
	<a href="#" id="fechaconfig">X</a>
	Título do bloco:<br />
	<input type="text" size="40" />
	<br /><br />
	Exibir conteúdo da Página:<br />
<?php
	wp_dropdown_pages();
?>
	<br /><br />
   	Exibir barra lateral:
	<select>
		<option value="s">Sim</option>
		<option value="n">Não</option>
	</select>
	<br /><br />

	<button type="button" id="salvaconfig">SALVAR</button>
    </fieldset>
</div>