<?php
if(isset($_GET['m'])) $modulo = $_GET['m'];
if(isset($_GET['t'])) $tela = $_GET['t'];
?>

<div id="painel">
 	<h3><img src="img/Seta.png" width="10" height="10"/> &nbsp;Quem Somos <!-- O link ativa do "menubody" --></h3>
  <!-- Adicionar conteúdo da loja -->
	<?php 
	if(isset($modulo) && isset($tela)):
		loadModuo($modulo,$tela);
	else:
		echo '<h3>Escolha uma opção de menu para iniciar.</h3>';
	endif;	
	?>  
</div>