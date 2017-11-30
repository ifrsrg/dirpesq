<div id='configDestaque' class="configs" style="display:none;">
    <fieldset class="blococonfig">
        <legend>DESTAQUES</legend>
	<a href="#" id="fechaconfig">X</a>
	Título do bloco:<br />
	<input type="text" size="40" />
	<br /><br />
	Categoria:<br />
	<select >
		<?php $categories = get_categories( array( 'hide_empty' => 0 ) );
		foreach ($categories as $category) {
			echo "<option value='$category->cat_ID'>$category->cat_name</option>";
		} ?>
	</select>
        <br />
	<button type="button" id="salvaconfig">SALVAR</button>
    </fieldset>
</div>