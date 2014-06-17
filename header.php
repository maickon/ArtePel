<?php 
require_once("funcoes.php");
protegeArquivo(basename(__FILE__));
verificaLogin();
$sessao = new Sessao();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php 
loadCss('data_table'); 
loadCss('reset'); 
loadCss('index'); 
loadJs('geral');
loadJs('jquery_datatables');
loadJs('jquery_validate_messages');
loadJs('jquery_validate');
loadJs('jquery');
//loadJs('https://jquery.com/jquery/jquery.js',TRUE);
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Art&amp;pel</title>
</head>
<body>
	
	<div id="wrapper">
		<a href="<?php echo BASEURL.'?p=home' ?>" class="link">
			<div id="header">
            	<h4 class="painel_adm_time" align="right"><?php echo hora_atual().'<br>'.data_atual($sessao->getVar('nome_user'));?></h4>
				<h2 class="painel_adm" align="left">Painel adminstrativo</h2>
					
			</div><!-- fim header -->
		</a>
		
		