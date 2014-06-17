<?php 
require_once("funcoes.php");
loadJs('geral');
loadCss('menu_dropdown');
protegeArquivo(basename(__FILE__));
?>
<div>
<div id='cssmenu'>
			<ul>
			   <li class='has-sub '><a href="?m=usuario&t=listar"><span>Usuários</span></a>
		           <ul>
			           <li><a href="?m=usuario&t=incluir"><span>Cadastrar </span></a></li>
			           <li><a href="?m=usuario&t=listar"><span>Exibir</span></a></li>
		           </ul>
			   </li>
               
               <li class='has-sub '><a href="?m=produtos&t=listar"><span>Produtos da Loja</span></a>
		           <ul>
			           <li><a href="?m=produtos&t=incluir"><span>Cadastrar </span></a></li>
			           <li><a href="?m=produtos&t=listar"><span>Exibir</span></a></li>
		           </ul>
			   </li>
               
               <li class='has-sub '><a href="?m=home_page&t=listar"><span>Páginas</span></a>
		           <ul>
			           <li><a href="?m=home_page&t=listar"><span>home page</span></a></li>
			           <li><a href="?m=produtos_page&t=listar"><span>Produtos</span></a></li>
                       <li><a href="?m=quem_somos_page&t=listar"><span>Quem Somos</span></a></li>
			           <li><a href="?m=contato_page&t=listar"><span>Contato</span></a></li>
                       <li><a href="?m=papelaria_escritorio_page&t=listar"><span>Papelaria &amp; Escritório</span></a></li> 
                       <li><a href="?m=developer_page&t=listar"><span>Desenvolvedores</span></a></li> 
		           </ul>
			   </li>
         	  
			   <?php 
				//$sessao = new sessao();
				$meu_id = $sessao->getVar('id_user');
				?>
				<li class='has-sub '><a href="?m=usuario&t=senha&id=<?php echo $meu_id; ?>"><span>Alterar senha </span></a></li>
				<li class='has-sub '><a href="?logoff=true"><span>Sair</span></a></li>
			</ul>
		</div>
</div>		