<?php require_once('funcoes.php'); ?>
<?php inicializa(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="css/adm.css"/>
<link rel="stylesheet" type="text/css" href="css/index.css"/>
<link rel="stylesheet" type="text/css" href="css/slide_festas.css"/>
<link rel="stylesheet" type="text/css" href="engine1/style.css" />
<script type="text/javascript" src="engine1/jquery.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Arte & Pel</title>
</head>
<body>

<?php require_once('cabecalho.php');?>


<?php require_once('menubar.php');?> 

<?php

if(isset($_GET['p']) && $_GET['p'] != 'home'):

else:
    require_once('slider.php');
endif;

?>

<?php require_once('menubody.php');?>

<?php
if(isset($_GET['p'])):
    switch($_GET['p']):
         case 'adm':  loadModuo('usuario','login');
        break;
		
		case 'produtos': loadPage('produtos','index');
        break;
        
        case 'quem_somos': loadPage('quem_somos','index');
        break;
        
        case 'contato': loadPage('contato','index');
        break;
        
        case 'papelaria_escritorio': loadPage('papelaria_escritorio','index');
        break;
        
        case 'artigo_presentes': loadPage('artigo_presentes','index');
        break;
        
        case 'artigo_festas': loadPage('artigo_festas','index'); 
        break;
        
        case 'escritorio': loadPage('escritorio','index');
        break;
        
        case 'developer': loadPage('developer','index');
        break;
        
        default: loadPage('home','index');
        
    endswitch;
else:
    loadPage('home','index');
endif;

?>

<?php require_once('footer.php');?>

	<script type="text/javascript" src="engine1/wowslider.js"></script>
	<script type="text/javascript" src="engine1/script.js"></script>
	<!-- End WOWSlider.com BODY section -->


</body>
</html>