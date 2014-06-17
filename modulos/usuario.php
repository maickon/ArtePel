<?php 
require_once(dirname(dirname(__FILE__))."/funcoes.php");
loadJs('jquery_validate');
loadJs('jquery_validate_messages');
loadJs('geral');
protegeArquivo(basename(__FILE__));
$pagina_nome = 'Usuário';
$objeto = 'user';
$classe = 'usuarioAdmin';
$modulo = 'usuario';

switch ($tela):
	case 'login':
		$sessao = new sessao();
		if ($sessao->getNvars() > 0 && $sessao->getVar('logado') == TRUE && $sessao->getVar('ip') == $_SERVER['REMOTE_ADDR']) redireciona('painel.php'); 
		if(isset($_POST['logar'])):
			$objeto = new $classe();
			$objeto->setValor('login',antiInject($_POST['usuario']));
			$objeto->setValor('senha',antiInject($_POST['senha']));
			if($objeto->logar($objeto)):
				redireciona('painel.php?usu=');
			else:
				redireciona('?p=adm&erro=2');
			endif;
		endif;
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".userForm").validate({
				rules:{
				usuario:{required:true, minlength:3},
				senha:{required:true, rangelength:[4,10]}
				}
			});
		});
		</script>
        
		<div id="mostruario" align="center">              
            <form class="userForm loginform" method="post" action="">
            <fieldset>
                <legend><?php echo utf8_encode('Área de administrador, identifique-se') ?>.</legend>
                    <label for="usuario">Login</label>
                    <input type="text" size="35" name="usuario" autofocus="autofocus" value="<?php echo $_POST['usuario']=''; ?>" />
                  
                    <label for="senha">Senha</label>
                    <input type="password" size="35" name="senha" value="<?php echo $_POST['senha']=''; ?>" />
                    <br />
                    <input class="botenter" type="submit" name="logar" value="ENTRAR" />                
                    <br />
            
                    <?php
                    if(isset($_GET['erro'])):
                        $erro = $_GET['erro'];
                    else:
                        $erro = '';
                    endif;	
                        
                    switch ($erro):
                        case 1:
                            echo utf8_encode('<div class="sucesso">Você fez logoff do sistema.</div>');
                        break;
                        
                        case 2:
                            echo utf8_encode('<div class="erro">Dados incorretos ou você não é um administrador.</div>');
                        break;
                        
                        case 3:
                            echo utf8_encode('<div class="erro">Faça login de antes de acessar a página solicitada.</div>');
                        break;
                        
                        case 4:
                            echo utf8_encode('<div class="alerta">Alerta.</div>');
                        break;
                        
                        case 5:
                            echo '<div class="pergunta">Pergunta.</div>';
                        break;	
                    endswitch;
                    ?>
                    </fieldset>
                </form>
   		         
		</div>
		<?php 
        break;
        
        case 'incluir':
			echo '<h2>Cadastro de usuários</h2>';
			if(isset($_POST['cadastrar'])):
				switch($_POST['tipo']):
					case 'administrador':
						$objeto = new $classe(array(
						'nome'=>$_POST['nome'],
						'email'=>$_POST['email'],
						'login'=>$_POST['login'],
						'senha'=>codificarSenha($_POST['senha']),
						'ativo'=>'s',
						'tipo'=>$_POST['tipo'],	
						));
			if($objeto->usuJaExiste('login',$_POST['login'])):
				printMsg('Este login ja está cadastrado, escolha outro nome de usuário.','erro');
				$duplicado = TRUE;
			else:
				$duplicado = FALSE;
			endif;
			if($duplicado != TRUE):
				$objeto->inserir($objeto);
				if($objeto->linhas_afetadas == 1):
					printMsg('Dados inserido com sucesso <a href="'.ADMURL.'?m='.$modulo.'&t=listar">Exibir cadastros</a>');
					unset($_POST);
				endif;
			endif;	
		break;		
	endswitch;
	endif;
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".userForm").validate({
				rules:{
					nome:{required:true, minlength:3},	
					email:{required:true},
					login:{required:true, minlength:5},
					senha:{required:true, rangelength:[4,10]},
					senhaconf:{required:true,equalTo:"#senha"},
					tipo:{required:true, tipo:true}
			}
		});
    });
    </script>
    <form class="userForm" method="post" action="">
        <fieldset><legend>Informe os dados para o cadastro</legend>
        <ul>
            <li>
            	<label for="nome">Nome:</label> <input type="text" size="50" name="nome" autofocus="autofocus" value="<?php echo $_POST['nome']='' ?>">
            </li>
            
            <li>
            	<label for="email">Email:</label> <input type="text" size="50" name="email" value="<?php echo $_POST['email']='' ?>">
            </li>
            
            <li>
            	<label for="login">Login</label> <input type="text" size="50" name="login" value="<?php echo $_POST['login']='' ?>">
            </li>
            
            <li>
            	<label for="senha">Senha:</label> <input type="password" size="25" name="senha" id="senha" value="<?php echo $_POST['senha']='' ?>">
            </li>
            
            <li>
            	<label for="senhaconf">Repita a senha:</label><input type="password" size="25" id="senha" name="senhaconf" value="<?php echo $_POST['senhaconf']='' ?>">
            </li>	
            
            <li>
            	<label for="tipo">Tipo de usuário:</label> 
                    <select name="tipo">
                        <option></option>
                        <option>administrador</option>
                        <option>usuário comum</option>
                    </select>
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
		echo '<h2>Usuários cadastrados</h2>';
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
					<th>Email</th>
					<th>Login</th>
					<th>Tipo</th>
					<th>Cadastrado</th>
					<th>Ativo</th>
					<th>Ações</th>
				</tr>
			</thead>
		<tbody>
		<?php 
		$objeto = new $classe();
		$objeto->seleciona_tudo($objeto);
		while($resp = $objeto->retorna_dados()):
			if($resp->tipo == 'administrador')$link = '<a href="?m='.$modulo.'&t=incluir" title="Novo cadastro"><img src="img/plus.png" alt="Novo cadastro" /></a> ';
			echo '<tr>'; 
				printf('<td class="center">%s</td>',$resp->nome);
				printf('<td class="center">%s</td>',$resp->email);
				printf('<td class="center">%s</td>',$resp->login);
				printf('<td class="center">%s</td>',$resp->tipo);
				printf('<td class="center">%s</td>',date("d/m/Y",strtotime($resp->data_cadastro)));
				printf('<td class="center">%s</td>',$resp->ativo);
				printf('
				<td class="center">
					<div>'
						.$link.
					   '<a href="?m='.$modulo.'&t=editar&id=%s" title="Editar"><img src="img/edit.png" alt="Editar" /></a> 
						<a href="?m='.$modulo.'&t=senha&id=%s" title="Alterar senha"><img src="img/pass.png" alt="Alterar senha" /></a>
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
		echo '<h2>Edição de usuários</h2>';
		$sessao = new sessao();
		if(isAdmin() == TRUE || $sessao->getVar('id_user') == $_GET['id']):
			if(isset($_GET['id'])):
				$id = $_GET['id'];
				if(isset($_POST['editar'])):
					$objeto = new $classe(array(
						'nome' =>$_POST['nome'],
						'email' =>$_POST['email'],
						'tipo' =>$_POST['tipo'],
						'ativo' =>($_POST['ativo']=='on') ? 's' : 'n'
					));
					$objeto->valor_pk = $id;
					$objeto->extras_select =  " WHERE id=$id";
					$objeto->seleciona_tudo($objeto);
					$resp = $objeto->retorna_dados();
					if(isset($duplicado) != TRUE):
						$objeto->atualizar($objeto);
						if($objeto->linhas_afetadas == 1):
							printMsg('Dados alterados com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>');
							unset($_POST);
						else:
							printMsg('Nenhum dado foi alterado. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>','alerta');	
						endif;
					endif;
				endif;
				
				$objeto_edit = new $classe();
				$objeto_edit->extras_select = " WHERE id=$id";
				$objeto_edit->seleciona_tudo($objeto_edit);
				$objeto_resp = $objeto_edit->retorna_dados();
			else:
				printMsg('Usuário não definido, <a href="m='.$modulo.'&t=listar">Escolha um usuário para alterar</a>','erro');
			endif;
			?>
			
			<script type="text/javascript">
			$(document).ready(function(){
				$(".userForm").validate({
					rules:{
						nome:{required:true, minlength:3},
						email:{required:true},
						tipo:{required:true, tipo:true}
					}
				});
			});
			</script>
			<form class="userForm" method="post" action="">
			<fieldset><legend>Informe os dados para o alteração</legend>
			<ul>
				<li>
                	<label for="nome">Nome:</label> 
                    <input type="text" size="50" name="nome" autofocus="autofocus" value="<?php if($objeto_edit) echo $objeto_resp->nome ?>" />
				</li>
                
				<li>
                	<label for="email">Email:</label>
                    <input type="text" size="50" name="email" value="<?php if($objeto_edit) echo $objeto_resp->email ?>" />
				</li>
				
                <li>
                	<label for="login">Login</label>
                    <input type="text" disabled="disabled" size="50" name="login" value="<?php if($objeto_edit) echo $objeto_resp->login ?>">
				</li>
				
                <li>
                	<label for="ativo">Ativo:</label> 
                    <input type="checkbox" name="ativo" "<?php if($objeto_resp->ativo == 's') echo 'checked = "checked"'; ?>" />
				</li>	
				
                <li>
                	<label for="tipo">Tipo de usuário:</label> 
                    <select name="tipo">
                        <option><?php if($objeto_edit) echo $objeto_resp->tipo; ?></option>
                        <option></option>
                        <option>administrador</option>
                        <option>usuário comum</option>
					</select>
                </li>
				
                <li class="center">
                	<input type="button" onclick="location.href='?m=<?php echo $modulo; ?>&t=listar'" value="Cancelar" />
                	<input type="submit" name="editar" value="Salvar alterações" />
                </li>
			</ul>
			</fieldset>
			</form>			
		<?php 
		else:
			printMsg('Você não tem permissão para acessar esta página. <a href="#" onclik="history.back()">Voltar</a>','erro');
		endif;	
	break;	
				
	case 'senha':
		echo '<h2>Edição de senha</h2>';
		$sessao = new sessao();
		if(isAdmin() == TRUE || $sessao->getVar('id_user') == $_GET['id']):
			if(isset($_GET['id'])):
				$id = $_GET['id'];
				if(isset($_POST['mudasenha'])):
					$objeto = new $classe(array(
						'senha'=>codificarSenha($_POST['senha']),
					));
					$objeto->valor_pk = $id;
					$objeto->extras_select =  " WHERE id=$id";
					$objeto->seleciona_tudo($objeto);
					$resp = $objeto->retorna_dados();
				
					$objeto->atualizar($objeto);
					if($objeto->linhas_afetadas == 1):
						printMsg('Senha alterada com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>');
						unset($_POST);
					else:
						printMsg('Nenhum dado foi alterado. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>','alerta');	
					endif;
				endif;
				
				$objeto_edit = new $classe();
				$objeto_edit->extras_select = " WHERE id=$id";
				$objeto_edit->seleciona_tudo($objeto_edit);
				$objeto_resp = $objeto_edit->retorna_dados();
			else:
				printMsg('Usuário não definido, <a href="m='.$modulo.'&t=listar">Escolha um usuário para alterar</a>','erro');
			endif;
			?>
			
			<script type="text/javascript">
			$(document).ready(function(){
				$(".userForm").validate({
					rules:{
						login:{required:true, minlength:5},
						senha:{required:true, rangelength:[4,10]},
						senhaconf:{required:true,equalTo:"#senha"}
					}
				});
			});
			</script>
			<form class="userForm" method="post" action="">
			<fieldset><legend>Informe os dados para o alteração</legend>
			<ul>
				<li>
					<label for="nome">Nome:</label> 
                    <input type="text" disabled="disabled" size="50" name="nome" value="<?php if($objeto_edit) echo $objeto_resp->nome ?>" />
				</li>
				
				<li>
                    <label for="email">Email:</label>
                    <input type="text" disabled="disabled" size="50" name="email" value="<?php if($objeto_edit) echo $objeto_resp->email ?>" />
				</li>
				
                <li>
                	<label for="login">Login</label>
                    <input type="text" disabled="disabled" size="50" name="login" value="<?php if($objeto_edit) echo $objeto_resp->login ?>">
				</li>
                
				<li>
                    <label for="login">Tipo de usuário</label>
                    <input type="text" disabled="disabled" size="50" name="login" value="<?php if($objeto_edit) echo $objeto_resp->tipo ?>">
				</li>
				
                <li>
                	<label for="senha">Senha:</label>
                    <input type="password" size="25" name="senha" autofocus="autofocus" id="senha" value="<?php echo $_POST['senha']='' ?>">
				</li>
                
				<li>
                	<label for="senhaconf">Repita a senha:</label>
                    <input type="password" size="25" id="senha" name="senhaconf" value="<?php echo $_POST['senhaconf']='' ?>">
                </li>	
				<li class="center">
               		<input type="button" onclick="location.href='?m=<?php echo $modulo; ?>&t=listar'" value="Cancelar" />
                    <input type="submit" name="mudasenha" value="Salvar alterações" />
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
		echo '<h2>Exclusão de usuários</h2>';
		$sessao = new sessao();
		if(isAdmin() == TRUE):
			if(isset($_GET['id'])):
				$id = $_GET['id'];
				if(isset($_POST['excluir'])):
					$objeto = new $classe();
					$objeto->valor_pk = $id;
					$objeto->extras_select = " WHERE id=$id";
					$objeto->seleciona_tudo($objeto);
					$resp = $objeto->retorna_dados();

					$objeto->deletar($objeto);				
					if($objeto->linhas_afetadas == 1):
						printMsg('Registro excluido com sucesso. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>');
						unset($_POST);
					else:
						printMsg('Nenhum registro foi excluido. <a href="?m='.$modulo.'&t=listar">Exibir cadastros</a>','alerta');	
					endif;
				endif;
				
				$objeto_edit = new $classe();
				$objeto_edit->extras_select = " WHERE id=$id";
				$objeto_edit->seleciona_tudo($objeto_edit);
				$objeto_resp = $objeto_edit->retorna_dados();
			else:
				printMsg('Usuário não definido, <a href="m='.$modulo.'&t=listar">Escolha um usuário para excluir</a>','erro');
			endif;
			?>
			<form class="userForm" method="post" action="">
			<fieldset><legend>Confira os dados para exclusão</legend>
			
			<ul>
				<li>
                    <label for="nome">Nome:</label> 
                    <input type="text" disabled="disabled" size="50" name="nome" value="<?php if(isset($objeto_resp->nome)) echo $objeto_resp->nome ?>" />
				</li>
                
				<li>
                    <label for="email">Email:</label> 
                    <input type="text" disabled="disabled" size="50" name="email" value="<?php if(isset($objeto_resp->email)) echo $objeto_resp->email ?>" />
				</li>
				
                <li>
                    <label for="validade">Validade:</label>
                    <input type="text" disabled="disabled" size="50" name="validade" value="<?php if(isset($objeto_resp->validade)) echo $objeto_resp->validade ?>" />
				</li>
                
				<li>
                    <label for="telefone">Telefone:</label>
                    <input type="text" disabled="disabled" size="50" name="telefone" value="<?php if(isset($objeto_resp->telefone)) echo $objeto_resp->telefone ?>" />
				</li>
                
				<li>
                    <label for="login">Login</label>
                    <input type="text" disabled="disabled" size="50" name="login" value="<?php if(isset($objeto_resp->login)) echo $objeto_resp->login ?>">
				</li>
                
				<li>
                	<label for="ativo">Ativo:</label>
                    <input type="checkbox" name="ativo" disabled="disabled" <?php if(isset($objeto_resp->ativo) && $objeto_resp->ativo == 's') echo 'checked = "checked"'; ?> />
				</li>	
                
				<li>
                	<label for="login">Tipo de usuário</label>
                    <input type="text" disabled="disabled" size="50" name="login" value="<?php if(isset($objeto_resp->tipo)) echo $objeto_resp->tipo ?>">
				</li>
                
				<li class="center">
                	<input type="button" onclick="location.href='?m=<?php echo $modulo; ?>&t=listar'" value="Cancelar" />
                    <input type="submit" name="excluir" value="Confirmar exclusão" />
                </li>
			</ul>
			</fieldset>
			</form>
			
		<?php 
		else:
			printMsg('Você não tem permissão para acessar esta página. <a href="#" onclik="history.back()">Voltar</a>','erro');
		endif;	
	break;
		
default:
		echo '<p>A tela solicitada não existe.</p>';
	break;
endswitch;
?>