<div id="mostruario">
 	<h3><img src="img/Seta.png" width="10" height="10"/> &nbsp;Artigos Para Presentes <!-- O link ativa do "menubody" --></h3>
  <!-- Adicionar conteúdo da loja -->
	<?php 
	exibir_pagina($home_page='artigo_presente_page',$classe='artigo_presente_page');    
    $num = 6; 
	$pg = pagina('produto','produtos',$num,true,'artigo_presente');  // pega o tipo no BD
	$quantidade_reg = total_registros('produto','produtos', true, 'artigo_presente');  // pega o tipo no BD
	paginacao($quantidade_reg, $pg, $num, 'artigo_presentes');   //pega a página
	?>
</div>