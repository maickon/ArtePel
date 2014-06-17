<div id="mostruario">
 	<h3><img src="img/Seta.png" width="10" height="10"/> &nbsp;Papelaria/Escritório<!-- O link ativa do "menubody" --></h3>
  <!-- Adicionar conteúdo da loja -->
	<?php 
	exibir_pagina($home_page='papelaria_escritorio_page',$classe='papelaria_escritorio_page');   
    $num = 6; 
	$pg = pagina('produto','produtos',$num,true,'papelaria_escritorio');
	$quantidade_reg = total_registros('produto','produtos',true,'papelaria_escritorio');
	paginacao($quantidade_reg, $pg, $num,'papelaria_escritorio');
	?>  
</div>