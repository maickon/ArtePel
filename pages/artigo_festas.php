<div id="mostruario">
 	<h3><img src="img/Seta.png" width="10" height="10"/> &nbsp;Artigos Para Festas <!-- O link ativa do "menubody" --></h3>
  <!-- Adicionar conteúdo da loja -->
	<?php 
	exibir_pagina($home_page='artigo_festa_page',$classe='artigo_festa_page');
	$num = 6; 
	$pg = pagina('produto','produtos',$num,true,'artigo_festa'); // pega o tipo no BD
	$quantidade_reg = total_registros('produto','produtos',true,'artigo_festa'); // pega o tipo no BD
	paginacao($quantidade_reg, $pg, $num, 'artigo_festas','artigo_festas');  //pega a página
	 ?>  
</div>

<div id="wowslider-container2" class="banner">
    <div class="ws_images">
        <ul>
            <li><img src="img/festas/artpel_1.jpg" alt="Art&amp;Pel " title="Art&amp;Pel " id="wows1_0"/>Bem vindo à Art&amp;Pel</li>
            <li><img src="img/festas/artpel_2.jpg" alt="Art&amp;Pel " title="Art&amp;Pel " id="wows1_1"/>Bem vindo à Art&amp;Pel</li>
            <li><img src="img/festas/artpel_3.jpg" alt="Art&amp;Pel " title="Art&amp;Pel " id="wows1_2"/>Bem vindo à Art&amp;Pel</li>
            <li><img src="img/festas/artpel_3.jpg" alt="Art&amp;Pel " title="Art&amp;Pel " id="wows1_2"/>Bem vindo à Art&amp;Pel</li>
        </ul>
    </div>
    <div class="ws_bullets">
        <div>
            <a href="#" title="slide 1"><img src="img/festas/tooltips/artpel_1.jpg" alt="1"/>1</a>
            <a href="#" title="slide 2"><img src="img/festas/tooltips/artpel_2.jpg" alt="2"/>2</a>
            <a href="#" title="slide 3"><img src="img/festas/tooltips/artpel_3.jpg" alt="3"/>3</a>
            <a href="#" title="slide 3"><img src="img/festas/tooltips/artpel_3.jpg" alt="3"/>3</a>
        </div>
    </div>
</div>