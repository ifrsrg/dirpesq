<div id='configVitrine' class="configs" style="display:none;">
    <fieldset class="blococonfig">
        <legend>VITRINE</legend>
	<a href="#" id="fechaconfig">X</a>
	Título do bloco:<br />
	<input type="text" size="40" />
	<br /><br />
	Categoria:
	<select >
		<option value="">Todos os posts</option>
		<?php $categories = get_categories( array( 'hide_empty' => 0 ) );
		foreach ($categories as $category) {
			echo "<option value='$category->cat_ID'>$category->cat_name</option>";
		} ?>
	</select>
    <br /><br />

    Número de posts a exibir:
	<input type="text" size="10" maxlength="3" /><br /><br />

    Intervalo de rotação:
	<input type="text" size="10" maxlength="5" /> milissegundos <br /><br />

	Exibir título do post:
	<select>
		<option value="">Sempre</option>
		<option value="movel">Ao passar o cursor</option>
		<option value="clean">Ocultar ao passar o cursor</option>
		<option value="oculto">Nunca</option>
	</select><br /><br />

	Exibir resumo do post:
	<select>
		<option value="">Junto com título</option>
		<option value="rmovel">Ao passar o cursor</option>
		<option value="oculto">Nunca</option>
	</select><br /><br />

	Ordem dos posts:
	<select>
		<option value="date">Cronológica</option>
		<option value="rand">Aleatória</option>
	</select><br /><br />

	Ignorar posts fixos:
	<select>
		<option value="n">Não</option>
		<option value="s">Sim</option>
	</select><br /><br />

	Exibir nas páginas internas:
	<select>
		<option value="n">Não</option>
		<option value="c">Cabeçalho</option>
		<option value="r">Rodapé</option>
	</select><br /><br />

	<button type="button" id="salvaconfig">SALVAR</button>
    </fieldset>
</div>