<?php if(isset($_GET['p']) && ($_GET['p'] == 'contato' || $_GET['p'] == 'quem_somos')):?>

<?php else:?>
<nav class="menubody">
	 <h3>PRODUTOS</h3>
	<a href="index.php?p=papelaria_escritorio"><img src="img/Seta.png" width="8" height="8"/> &nbsp;Papelaria/Escritório</a><br><br>
	<a href="index.php?p=artigo_presentes"><img src="img/Seta.png" width="8" height="8"/> &nbsp;Artigos para Presentes</a><br><br>
	<a href="index.php?p=artigo_festas"><img src="img/Seta.png" width="8" height="8"/> &nbsp;Artigos para Festas</a><br><br>
</nav>
<?php endif;?>
