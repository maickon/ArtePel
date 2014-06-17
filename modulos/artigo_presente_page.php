<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
// echo basename(__FILE__); retorna o nome do arquivo
loadJs('jquery_validate');
loadJs('jquery_validate_messages');
loadJs('geral');
loadJs('../'.CKEDITORPATH.'ckeditor');

protegeArquivo(basename(__FILE__));
$pagina_nome = 'Artigos p/ presentes';
$objeto = 'artigo_presente_page';
$classe = 'artigo_presente_page';
$modulo = 'artigo_presente_page';
switch ($tela):
	case 'incluir':
		if(isset($_POST['cadastrar'])):
			$objeto = new $classe(array(
				"texto"	=> $_POST['texto']
			));
			$objeto->inserir($objeto);
			if($objeto->linhas_afetadas == 1):
				printMsg('Página criada com sucesso <a href="'.ADMURL.'?m='.$modulo.'&t=listar">Exibir cadastros</a>');
				unset($_POST);
			endif;			
		endif;	
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".userForm").validate({
				rules:{
					texto:{required:true},
				}
			});
		});
	</script>
    <form class="userForm" method="post" action="" enctype="multipart/form-data">
        <fieldset><legend>Monte sua página <?php echo $pagina_nome ?> aqui.</legend>
            <ul>
            <?php $objeto = new $classe();?>	
				<li>
					<label for="texto">Minha página:</label>
					<br />
					<textarea  class="ckeditor" id="editor1" name="texto" cols="20" rows="30"></textarea>
				</li>
                <li class="center">
                    <input type="button" onclick="location.href='?m=<?php echo $modulo; ?>&t=listar'" value="Cancelar" /> 
                    <input type="submit" name="cadastrar" value="Salvar dados" />
                </li>
            </ul>
        </fieldset>
    </form>
	<?php 
	break;	
	
	case 'listar':
		echo '<div align="right">';
			echo '<h2 align="left">';
				echo '<a href="?m='.$modulo.'&t=incluir" title="Novo cadastro"><img src="img/plus.png" alt="Novo cadastro" />Nova Página</a>';
			echo '</h2>';	
		echo '</div>';
		loadCss('data_table',NULL,TRUE);
		loadJs('jquery_datatables');
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#listausers').dataTable({
				"oLanguage":{
					"sLengthMenu": "Mostrar _MENU_ elementos por página",
					"sZeroRecords": "Nenhum dado encontrado para exibição",
					"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ de registros",
					"sInfoEmpty": "Nenhum registro para ser exibido",
					"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
					"sSearch": "Pesquisar"
				}, 
					"sSrollY": "400px",
					"bPaginatc": false,
					"aaSorting": [[0, "asc"]]
				});
			});
		</script>
		<table cellspacing="0" cellpadding="0" border="0" class="display" id="listausers">
			<thead>
			 	<tr>
                    <th>Página</th>
                    <th>Ação</th>
			 	</tr>
			</thead>
			  
			<tbody>
			<?php 
			$objeto = new $classe();
			$objeto->seleciona_tudo($objeto);
			while($resp = $objeto->retorna_dados()):
				echo '<tr>'; 
					printf('<td class="center">%s</td>',$resp->texto);
					printf('
					<td class="center">
						<div>
							<a href="?m='.$modulo.'&t=incluir" title="Novo cadastro"><img src="img/plus.png" alt="Novo cadastro" /></a>
							<a href="?m='.$modulo.'&t=editar&id=%s" title="Editar"><img src="img/edit.png" alt="Editar" /></a> 
							<a href="?m='.$modulo.'&t=excluir&id=%s" title="Excluir"><img src="img/cancel.png" alt="Excluir" /></a>
						</div>
					</td>',$resp->id,$resp->id,$resp->id);
				echo '</tr>';
			endwhile;
			?>
          </tbody>
	</table>
	<?php 
	break;	
		
	case 'editar':
		echo '<h2>Editando a página '.$pagina_nome.'</h2>'; 
		if(isAdmin() == TRUE || $sessao->getVar('id_user') == $_GET['id']):
			if(isset($_GET['id'])):
				$id = $_GET['id'];
				if(isset($_POST['editar'])):
					$objeto = new $classe(array(
						"texto" => $_POST['texto']
					));
					$objeto->valor_pk = $id;
					$objeto->extras_select =  " WHERE id=$id";
					$objeto->seleciona_tudo($objeto);
					$resp = $objeto->retorna_dados();
					$objeto->atualizar($objeto);
					if($objeto->linhas_afetadas == 1):
						printMsg('Página editada com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir página</a>');
						unset($_POST);
					else:
						printMsg('Nenhum dado foi alterado. <a href="?m='.$modulo.'&t=listar">Exibir página</a>','alerta');	
					endif;
				endif;				
				$objeto_exibir = new $classe();
				$objeto_exibir->extras_select = " WHERE id=$id";
				$objeto_exibir->seleciona_tudo($objeto_exibir);
				$objeto_resp = $objeto_exibir->retorna_dados();
			endif;
		?>
		
		<script type="text/javascript">
		$(document).ready(function(){
			$(".userForm").validate({
				rules:{
					nome:{required:true},
					img:{required:true}
				}
			});
		});
		</script>
		<form class="userForm" method="post" action="" enctype="multipart/form-data">
            <fieldset><legend>Editando a página <?php echo $pagina_nome ?>.</legend>
			<ul>			
				<li>
					<label for="texto">Minha Página:</label>
                	<br />
                	<textarea class="ckeditor" id="editor1" name="texto" cols="20" rows="30"><?php if($objeto_resp) echo $objeto_resp->texto ?></textarea>
                </li>
                
                <li class="center">
                    <input type="button" onclick="location.href='?m=<?php echo $modulo; ?>&t=listar'" value="Cancelar" /> 
                    <input type="submit" name="editar" value="Editar dados" />
                </li>
            </ul>
            </fieldset>
		</form>
	<?php 
		else:
			printMsg('Você não tem permissão para acessar esta página. <a href="#" onclik="history.back()">Voltar</a>','erro');
		endif;
	break;	
	
	case 'excluir':
		echo '<h2>Excluindo dados da página.</h2>'; 
		$sessao = new sessao();
		if(isAdmin() == TRUE || $sessao->getVar('id_user') == $_GET['id']):
			if(isset($_GET['id'])):
				$id = $_GET['id'];
				if(isset($_POST['excluir'])):
					$objeto = new $classe(array());
					$objeto->valor_pk = $id;
					$objeto->extras_select =  " WHERE id=$id";
					$objeto->seleciona_tudo($objeto);
					$resp = $objeto->retorna_dados();
					
					$objeto->deletar($objeto);
					if($objeto->linhas_afetadas == 1):
						printMsg('Dados deletados com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir página</a>');
						unset($_POST);
					else:
						printMsg('Nenhum dado foi deletado. <a href="?m='.$modulo.'&t=listar">Exibir página</a>','alerta');	
					endif;
				endif;
				$objeto_exibir = new $classe();
				$objeto_exibir->extras_select = " WHERE id=$id";
				$objeto_exibir->seleciona_tudo($objeto_exibir);
				$objeto_resp = $objeto_exibir->retorna_dados();
			endif;
		?>				
        <form class="userForm" method="post" action="">
        	<fieldset><legend>Excluir dados da página <?php echo $pagina_nome ?>.</legend>	
            <ul>
            	<li>
                	<label for="texto">Minha Página:</label>
                    <textarea name="texto" class="ckeditor" id="editor1" cols="20" rows="20" disabled="disabled">
						<?php if($objeto_resp) echo $objeto_resp->texto ?>
					</textarea>
            
                <li class="center">
                	<input type="button" onclick="location.href='?m=<?php echo $modulo; ?>&t=listar'" value="Cancelar" /> 
                	<input type="submit" name="excluir" value="Excluir dados" />
                </li>
        	</ul>
        	</fieldset>
        </form>
        <?php 
        else:
        	printMsg('Você não tem permissão para acessar esta página. <a href="#" onclik="history.back()">Voltar</a>','erro');
        endif;	
	break;	
endswitch;

?>