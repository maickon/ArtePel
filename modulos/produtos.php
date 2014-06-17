<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
// echo basename(__FILE__); retorna o nome do arquivo
loadJs('jquery_validate');
loadJs('jquery_validate_messages');
loadJs('geral');
loadJs('../'.CKEDITORPATH.'ckeditor');
protegeArquivo(basename(__FILE__));

$pagina_nome = 'Produtos';
$objeto = 'produto';
$classe = 'produtos';
$modulo = 'produtos';

switch ($tela):
	case 'incluir':
		if(isset($_POST['cadastrar'])):
			$objeto = new $classe(array(
				"nome"		=> $_POST['nome'],
				"tipo"		=> $_POST['tipo'],
				"descricao"	=> $_POST['descricao'],
				"img"		=> gerar_nome($_FILES['arquivo']['name'])
			));
			$objeto->inserir($objeto);
			if($objeto->linhas_afetadas == 1):
				upload($_FILES['arquivo'],$objeto->getValor('img'));
				printMsg('Dados inserido com sucesso <a href="'.ADMURL.'?m='.$modulo.'&t=listar">Exibir cadastros</a>');
				unset($_POST);
			endif;			
		endif;	
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".userForm").validate({
				rules:{
					nome:{required:true},
					img:{required:true},
				}
			});
		});
	</script>
    <form class="userForm" method="post" action="" enctype="multipart/form-data">
        <fieldset><legend>Informe os dados para cadastrar um produto.</legend>
            <ul>	
                <li>
                    <label for="nome">Nome:</label> <input type="text" size="50" name="nome" value="<?php echo $_POST['nome']='' ?>">
                </li>
               	<li><label for="tipo">Tipo:</label> 
                		<select name=tipo>
							<option value="papelaria_escritorio">Papelaria/Escritório</option>
							<option value="artigo_presente">Artigos p/ presentes</option>
							<option value="artigo_festa">Artigos p/ festas</option>
						</select>
				</li>
                <li>
                    <label for="img">Imagem:</label> <input type="file" size="50" name="arquivo" value="<?php echo $_POST['arquivo']='' ?>">
                </li>
                <li>
					<label for="texto">Descrição:</label>
					<br />
					<textarea  class="ckeditor" id="editor1" name="descricao" cols="20" rows="20"></textarea>
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
		echo '<h2>Produtos cadastradas no sistema</h2>';
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
					<th>Nome</th>
                    <th>Tipo</th>
                    <th>Cadastrado</th>
                    <th>Descrição</th>
                    <th>Ação</th>
			 	</tr>
			</thead>
			  
			<tbody>
			<?php 
			$objeto = new $classe();
			$objeto->seleciona_tudo($objeto);
			while($resp = $objeto->retorna_dados()):
				echo '<tr>'; 
					printf('<td class="center">%s</td>',$resp->nome);
					printf('<td class="center">%s</td>',$resp->tipo);
					printf('<td class="center">%s</td>',date("d/m/Y",strtotime($resp->data_cadastro)));
					printf('<td class="center">%s</td>',$resp->descricao);
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
		echo '<h2>Editando '.$pagina_nome.'</h2>'; 
		if(isAdmin() == TRUE || $sessao->getVar('id_user') == $_GET['id']):
			if(isset($_GET['id'])):
				$id = $_GET['id'];
				if(isset($_POST['editar'])):
					$objeto = new $classe(array(
						"nome" 			=> $_POST['nome'],
						"tipo"			=> $_POST['tipo'],
						"data_cadastro"	=> tempo(),
						"descricao"		=> $_POST['descricao'],
						"img"			=> gerar_nome($_FILES['arquivo']['name'])
					));
					$objeto->valor_pk = $id;
					$objeto->extras_select =  " WHERE id=$id";
					$objeto->seleciona_tudo($objeto);
					$resp = $objeto->retorna_dados();
					$objeto->atualizar($objeto);
					if($objeto->linhas_afetadas == 1):
						unlink(IMGPRODPATH.$resp->img);
						upload($_FILES['arquivo'],$objeto->getValor('img'));
						//rename(IMGPRODPATH.$resp->img,IMGPRODPATH.$objeto->getValor('img'));
						printMsg('Dados alterados com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir produtos</a>');
						unset($_POST);
					else:
						printMsg('Nenhum dado foi alterado. <a href="?m='.$modulo.'&t=listar">Exibir produtos</a>','alerta');	
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
            <fieldset><legend>Editando um produto.</legend>
            <ul>			
                <li>
                    <label for="nome">Nome:</label> <input type="text" size="50" name="nome" value="<?php if($objeto_resp) echo $objeto_resp->nome ?>">
                </li>
                
                <li><label for="tipo">Tipo:</label> 
                		<select name=tipo>
							<option value="papelaria_escritorio">Papelaria/Escritório</option>
							<option value="artigo_presente">Artigos p/ presentes</option>
							<option value="artigo_festa">Artigos p/ festas</option>
						</select>
				</li>
                
                <li>
                    <label for="img">Imagem:</label> <input type="file" size="50" name="arquivo" value="<?php if($objeto_resp) echo $objeto_resp->img ?>">
                </li>
                
                <li>
					<label for="texto">Descrição:</label>
					<br />
					<textarea  class="ckeditor" id="editor1" name="descricao" cols="20" rows="20"><?php if($objeto_resp) echo $objeto_resp->descricao ?></textarea>
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
		echo '<h2>Exclusão de produtos</h2>'; 
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
						unlink(IMGPRODPATH.$resp->img);
						printMsg('Dados deletados com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>');
						unset($_POST);
					else:
						printMsg('Nenhum dado foi deletado. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>','alerta');	
					endif;
				endif;
				$objeto_exibir = new $classe();
				$objeto_exibir->extras_select = " WHERE id=$id";
				$objeto_exibir->seleciona_tudo($objeto_exibir);
				$objeto_resp = $objeto_exibir->retorna_dados();
			endif;
		?>				
        <form class="userForm" method="post" action="">
        	<fieldset><legend>Excluir produto.</legend>	
            <ul>
                <li>
                    <label for="nome">Nome:</label> <input type="text" size="50" name="nome" disabled="disabled" value="<?php if($objeto_resp) echo $objeto_resp->nome ?>">
                </li>
                
                <li>
                    <label for="nome">Tipo:</label> <input type="text" size="50" name="nome" disabled="disabled" value="<?php if($objeto_resp) echo $objeto_resp->tipo ?>">
                </li>
            	
                 <li>
					<label for="texto">Descrição:</label>
					<br />
					<textarea  class="ckeditor" id="editor1" name="descricao" disabled="disabled" cols="20" rows="20"><?php if($objeto_resp) echo $objeto_resp->descricao ?></textarea>
				</li>
                
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