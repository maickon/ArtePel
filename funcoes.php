<?php 
date_default_timezone_set('America/Sao_Paulo');
inicializa();
function redireciona($url=''){
	header('Location:'.BASEURL.$url);
}//fim redireciona
function inicializa(){
	if (file_exists(dirname(__FILE__)."/config.php")):
		require_once(dirname(__FILE__)."/config.php");
	else:
		die(utf8_decode('O arquivo de inicialização não foi localizado, contate o administrador.'));
	endif;
	if (!defined("BASEPATH") || !defined("BASEURL")):
		die(utf8_decode('Faltam configurações básicas do sistema, contate o administrador.'));
	endif;
	require_once(BASEPATH.CLASSPATH.'autoload.class.php');
	if(isset($_GET['logoff']) == TRUE):
		$user = new usuarioAdmin();
		$user->logoff();
		exit;
	endif;
}//fim inicializa

function loadCss($arquivo, $media='screen', $import=FALSE){
	if ($arquivo != NULL):
		if ($import == TRUE):
			echo '<style type="text/css>">@import url("'.BASEURL.CLASSPATH.$arquivo.'.css");</style>'."\n";
		else:
			echo '<link rel="stylesheet" type="text/css" href="'.BASEURL.CSSPATH.$arquivo.'.css" media="'.$media.'" />'."\n";
		endif;
	endif;
}//fim loadCss

function loadJs($arquivo=NULL, $remoto=FALSE){
	if ($arquivo != NULL):
		if ($remoto == FALSE) $arquivo = BASEURL.JSPATH.$arquivo.'.js';
			echo '<script type="text/javascript" src="'.$arquivo.'"></script>'."\n";
	endif;
}//fim loadJs

function loadModuo($modulo=NULL, $tela=NULL){
	if($modulo == NULL || $tela == NULL):
		echo '<p>Erro na função <strong>'.__FUNCTION__.'</strong> faltam parâmetros para execução.</p>';
	else: 
		if(file_exists(MODULOSPATH."$modulo.php")):
			include_once(MODULOSPATH."$modulo.php");
		else:
			echo '<p>Módulo inexistente neste sistema.</p>';
		endif; 
	endif;
}//fim loadModulo

function loadPage($page=NULL, $tela=NULL){
	if($page == NULL || $tela == NULL):
		echo utf8_decode('<p>Erro na função <strong>'.__FUNCTION__.'</strong> faltam parâmetros para execução.</p>');
	else: 
		if(file_exists(PAGEPATH."$page.php")):
			include_once(PAGEPATH."$page.php");
		else:
			echo '<p>Página inexistente neste sistema.</p>';
		endif; 
	endif;
}//fim loadPage

function protegeArquivo($nome_arquivo, $redir_para='index.php?p=adm&erro=3'){
	$url = $_SERVER["PHP_SELF"];
	if(preg_match("/$nome_arquivo/i", $url)):
		//redireciona para outra URL
		redireciona($redir_para);
	endif;
}//fim protegeArquivo

function codificarSenha($senha){
	return md5($senha);
}

function verificaLogin(){
	$sessao = new Sessao();
	if($sessao->getNvars() <= 0 || $sessao->getVar('logado') != TRUE || $sessao->getVar('ip') != $_SERVER['REMOTE_ADDR']):
		redireciona('index.php?p=adm&erro=3');
	endif;
}

function sair_index(){
	$sessao = new Sessao();
		if ($sessao->getNvars() > 0 || $sessao->getVar('logado') == TRUE || $sessao->getVar('ip') == $_SERVER['REMOTE_ADDR']):
			return "<li class='has-sub '><a href='?logoff=true'><span>Sair</span></a>";
		else:
			return '';
		endif;
}

function printMsg($msg = NULL, $tipo = NULL){
	if($msg != NULL):
		switch($tipo):
			case 'erro':
				echo '<div class="erro">'.$msg.'</div>';
				break;
			case 'alerta':
				echo '<div class="alerta">'.$msg.'</div>';
				break;
			case 'pergunta':
				echo '<div class="pergunta">'.$msg.'</div>';
				break;
			case 'secesso':
				echo '<div class="sucesso">'.$msg.'</div>';
				break;
			default:
				echo '<div class="sucesso">'.$msg.'</div>';
				break;
		endswitch;
	endif;
}

function isAdmin(){
	verificaLogin();
	$sessao = new Sessao();
	$userAdmin = new usuarioAdmin(array(
		'tipo' => NULL,
	));
	$id_user = $sessao->getVar('id_user');
	$userAdmin->extras_select = " WHERE id=$id_user";
	$userAdmin->seleciona_campos($userAdmin);
	$resp = $userAdmin->retorna_dados();
	if($resp = $resp->tipo == 'administrador'):
		return TRUE;
	else:
		return FALSE;
	endif;
}

function antiInject($string){
	$string = preg_replace("/(from|select|insert|delet|where|drop table|show table|#|\*|--|\\\\)/i","",$string);
	$string = trim($string);//limpa espaços vazios
	$string = strip_tags($string);//tiras tags php e html
	if(!get_magic_quotes_gpc())
		$string = addslashes($string);//adiciona barras invertidas a uma string	
	return $string;
}

function hora_atual(){
	$tempo = localtime(time(),TRUE);
	$hora = $tempo['tm_hour'];
	$min = $tempo['tm_min'];
	$segundos = $tempo['tm_sec'];
	
	return "$hora : $min : $segundos ";
}

function tempo(){
	$tempo = localtime(time(),TRUE);
	$hora = $tempo['tm_hour'];
	$min = $tempo['tm_min'];
	$segundos = $tempo['tm_sec'];
	$dia = $tempo['tm_mday'];
	$mes = $tempo['tm_mon']+1;
	$ano = $tempo['tm_year']+1900;
	return "$ano-$mes-$dia $hora:$min:$segundos";
}

function data_atual($sessao){
	$tempo = localtime(time(),TRUE);
	$dia = $tempo['tm_mday'];
	$mes = $tempo['tm_mon'];
	$ano = $tempo['tm_year']+1900;
	$semana = $tempo['tm_wday'];
	
	return "Bom dia ".$sessao.", hoje é ".descobrir_samana($semana).", $dia de ". descobrir_mes($mes)." de $ano";
}

function descobrir_samana($dia){
	switch($dia):
		case 0:$semana = 'Domingo';
			break;
		case 1:$semana = 'Segunda feira';
			break;
		case 2:$semana = 'Terça feira';
			break;
		case 3:$semana = 'Quarta feira';
			break;
		case 4:$semana = 'Quinta feira';
			break;
		case 5:$semana = 'Sexta feira';
			break;
		case 6:$semana = 'Sábado';
			break;	
		default:$semana = 'Erro, o parâmerto está incorreto';						
	endswitch;
	return $semana;
}

function descobrir_mes($mes){
	switch($mes):
		case 0:$novo_mes = 'Janeiro';
			break;
		case 1:$novo_mes = 'Fevereiro';
			break;
		case 2:$novo_mes = 'Março';
			break;
		case 3:$novo_mes = 'Abril';
			break;
		case 4:$novo_mes = 'Maio';
			break;
		case 5:$novo_mes = 'Junho';
			break;
		case 6:$novo_mes = 'Julho';
			break;					
		case 7:$novo_mes = 'Agosto';
			break;
		case 8:$novo_mes = 'Setembro';
			break;
		case 9:$novo_mes = 'Outubro';
			break;
		case 10:$novo_mes = 'Novembro';
			break;
		case 11:$novo_mes = 'Dezembro';
			break;		
		default:$novo_mes = 'Erro, o parâmerto está incorreto';	
	endswitch;
	return $novo_mes;
}

function gerar_nome($imagem_atual,$ext = NULL){
	preg_match("/.(gif|bmp|png|jpg|jpeg){1}$/i", $imagem_atual, $ext);
	$nova_imagem = md5(uniqid(time())) . "." . $ext[1];
	return $nova_imagem;	
}

function preparar_nome($file){
	$imagem = $file;
	$nome= gerar_nome($imagem['name']);
	return $nome;
}

function total_registros($objeto, $classe, $select=false, $tipo=''){
	$objeto = new $classe();
	if($select != false):
		$objeto->extras_select = $objeto->extras_select.' WHERE tipo='."'$tipo'".'';
	endif;
	$objeto->seleciona_tudo($objeto);
	$count = 0;
	while($objeto_resp = $objeto->retorna_dados()):
		$count++;
	endwhile;
	
	return $count;
}

function exibir_pagina($objeto,$classe){
		$objeto = new $classe();
		$objeto->seleciona_tudo($objeto);
		while($objeto_resp = $objeto->retorna_dados()):
			echo '<div class="home_page">';
				echo $objeto_resp->texto; 
        	echo '</div>';
		endwhile;
}

function pagina($objeto, $classe, $num_reg, $select=false, $tipo=''){
	if(!isset($_GET['pg'])):
		$pg = 0;
	else:
		$pg = $_GET['pg'];	
	endif;
	$inicial = $pg*$num_reg;
	
	$objeto = new $classe();
	if($select != false):
		$objeto->extras_select = $objeto->extras_select.' WHERE tipo='."'$tipo'".' LIMIT '.$inicial.','.$num_reg.'';
	else:
		$objeto->extras_select = 'LIMIT  '.$inicial.','.$num_reg.'';
	endif;
	$objeto->seleciona_tudo($objeto);
	
	while($objeto_resp = $objeto->retorna_dados()): 	
        echo utf8_encode('<div class="item_vitrine">
            <a href="#" title="'.$objeto_resp->nome.'">
                <img src="'.IMGPRODPATH.$objeto_resp->img.'" alt="'.$objeto_resp->nome.'" class="item"/>
            </a> 
            <div class="nome">
            	'.$objeto_resp->nome.'
            </div>
			 <div class="descricao">
            	<p>'.$objeto_resp->descricao.'</p>
            </div>         
        </div>');
	endwhile;    
	
	return $pg;
}

function paginacao($quantidade_reg, $pg, $num_reg, $pagina_nome='produtos'){

	$qtd_pag = ceil($quantidade_reg/$num_reg);
	$qtd_pag++;
	
	echo '<div class="page_links">';
	echo '<div class="grupo_links">';
	if($pg > 0):
		echo '<a href="?p='.$pagina_nome.'&pg='.($pg-1).'">
        		<div class="page">&laquo;</div>
        	  </a>';
	else:
		//echo '<div class="page">&laquo;</div>';
	endif;
	 
	for($i_pg=1; $i_pg<$qtd_pag; $i_pg++):
		if($pg == ($i_pg-1)):
			echo '<div class="page atual">'.$i_pg.'</div>';	
		else:
			$i_pg2 = $i_pg-1;
			echo '<a href="?p='.$pagina_nome.'&pg='.$i_pg2.'">
        			<div class="page">'.$i_pg.'</div>
        		 </a>';
		endif;	
    endfor; 
        
	if(($pg+2) < $qtd_pag):
		echo '<a href="?p='.$pagina_nome.'&pg='.($pg+1).'">
        		<div class="page">&raquo;</div>
        	 </a>';
	endif;
	
   echo '</div>';   
   echo '</div>';
}

function upload($arquivo, $nome){
	
	$_UP['pasta'] = IMGPRODPATH;
	$_UP['tamanho'] = 1024*1024*2; // 2Mb
	$_UP['extencoes'] = array('jpg','png','gif');
	$_UP['renomeia'] = false;
	$_UP['erros'][0] = 'Não houve erros';
	$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
	$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
	$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
	$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

	if($_FILES['arquivo']['error'] != 0):
		die('<div class="erro">Não foi possível fazer o upload, erro:<br />'.$_UP['erros'][$_FILES['arquivo']['error']].'</div>');
		exit;
	endif;
	;
	$nome_arquivo = explode('.', $_FILES['arquivo']['name']);
	$extencao = strtolower(end($nome_arquivo));
	if(array_search($extencao,$_UP['extencoes'])=== false):
		echo '<div class="alerta">Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif</div>';
	elseif($_UP['tamanho'] < $_FILES['arquivo']['size']):
		echo '<div class="alerta">O arquivo enviado é muito grande, envie arquivos de até 2Mb.</div>';
	endif;	
	
	if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'].$nome)):
		echo '<div class="sucesso">Upload efetuado com sucesso!</div>';
	else:
		echo '<div class="erro">Não foi possível enviar o arquivo, tente novamente</div>';
	endif;
}
?>