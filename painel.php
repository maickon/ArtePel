<?php
include('header.php');
if(isset($_GET['m'])) $modulo = $_GET['m'];
if(isset($_GET['t'])) $tela = $_GET['t'];
?>
<?php include('sidebar.php');?>
<div id="content">
	<?php 
	if(isset($modulo) && isset($tela)):
		loadModuo($modulo,$tela);
	else:
		echo '<h3>Escolha uma opção de menu para iniciar.</h3>';
	endif;	
	?>
	
<?php include('footer.php');?>