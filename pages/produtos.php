<div id="mostruario">
 	<h3><img src="img/Seta.png" width="10" height="10"/> &nbsp;Produtos <!-- O link ativa do "menubody" --></h3>
  <!-- Adicionar conteúdo da loja -->
	<?php exibir_pagina($home_page='produtos_page',$classe='produtos_page'); ?>
    <?php
	$num = 6; 
	$pg = pagina('produto','produtos',$num);
	$quantidade_reg = total_registros('produto','produtos');
	paginacao($quantidade_reg, $pg, $num);
	?>    
</div>