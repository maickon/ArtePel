<?php
//require_once(dirname(__FILE__)."/autoload.class.php");
//protegeArquivo(basename(__FILE__));

abstract  class  banco{
	public $servidor			= DBHOST;
	public $usuario				= DBUSER;
	public $senha				= DBPASS;
	public $nome_banco			= DBNAME;		 
	public $conexao				= NULL;	
	public $data_set			= NULL; // vai armazenar os resultados da pesquisa
	public $linhas_afetadas		= -1; // quantidade de linhas afetadas no banco de dados
	
	
	//metodos
	public function __construct(){
		$this->conecta();
	}//construct
	
	public function __destruct(){
		if($this->conexao != NULL):
			mysql_close($this->conexao);
		endif;
	}//destruct
	
	public function conecta(){
		$this->conexao = mysql_connect($this->servidor, $this->usuario, $this->senha,TRUE) 
		or die($this->tratar_erro(__FILE__,__FUNCTION__,mysql_errno(),mysql_error(),TRUE));
		mysql_select_db($this->nome_banco) or die($this->tratar_erro(__FILE__,__FUNCTION__,mysql_errno(),mysql_error(),TRUE));
		mysql_query("SET NAMES 'utf-8'");
		mysql_query("SET character_set_connection=utf-8");
		mysql_query("SET character_set_client=utf-8");
		mysql_query("SET character_set_results=utf-8");
	}//conecta
	
	public function inserir($objeto){
		$sql = "INSERT INTO ".$objeto->tabela." (";
		for($i=0;$i<count($objeto->campos_valores);$i++):
			$sql .=key($objeto->campos_valores);
			if($i < count($objeto->campos_valores)-1):
				$sql .= ", ";
			else:
				$sql .= ") ";
			endif;
			next($objeto->campos_valores);
		endfor;
		reset($objeto->campos_valores);
		$sql .= "VALUES (";
		for($i=0;$i<count($objeto->campos_valores);$i++):
			$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ? 
				$objeto->campos_valores[key($objeto->campos_valores)] :
				"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
			next($objeto->campos_valores);
			if($i < count($objeto->campos_valores)-1):
				$sql .= ", ";
			else:
				$sql .= ") ";
			endif;
		endfor;
		return $this->executaSQL($sql);
	}//inserir
	
	public function atualizar($objeto){
		$sql = "UPDATE ".$objeto->tabela." SET ";
		for($i=0;$i<count($objeto->campos_valores);$i++):
			$sql .=key($objeto->campos_valores). "=";
			$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ? 
				$objeto->campos_valores[key($objeto->campos_valores)] :
				"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
			if($i < (count($objeto->campos_valores)-1)):
				$sql .= ", ";
			else:
				$sql .= " ";
			endif;	
			next($objeto->campos_valores);
		endfor;
		$sql .= "WHERE ".$objeto->campo_pk." = ";
		$sql .= is_numeric($objeto->valor_pk) ? $objeto->valor_pk : "'".$objeto->valor_pk."'";
		return $this->executaSQL($sql);
	}//atualizar
	
	public function deletar($objeto){
		$sql = "DELETE FROM ".$objeto->tabela;
		$sql .= " WHERE ".$objeto->campo_pk." = ";
		$sql .= is_numeric($objeto->valor_pk) ? $objeto->valor_pk : "'".$objeto->valor_pk."'";
		return $this->executaSQL($sql);
	}//deletar
	
	public function seleciona_tudo($objeto){
		$sql = "SELECT * FROM ".$objeto->tabela;
		if($objeto->extras_select != NULL):
			$sql .= " ".$objeto->extras_select;
		endif;
		return $this->executaSQL($sql);	
	}//seleciona_tudo
	
	public function seleciona_campos($objeto){
		$sql = "SELECT ";
		for($i=0;$i<count($objeto->campos_valores);$i++):
			$sql .=key($objeto->campos_valores);
			if($i < count($objeto->campos_valores)-1):
				$sql .= ", ";
			else:
				$sql .= " ";
			endif;
			next($objeto->campos_valores);
		endfor;
		$sql .= " FROM ".$objeto->tabela;
		if($objeto->extras_select != NULL):
			$sql .= " ".$objeto->extras_select;
		endif;
		
		return $this->executaSQL($sql);	
	}
	public function retorna_dados($tipo = NULL){
		switch(strtolower($tipo)):
		case "array":
			return mysql_fetch_array($this->data_set);
			break;
		case "assoc":
			return mysql_fetch_assoc($this->data_set);
			break;
		case "object":
			return mysql_fetch_object($this->data_set);
			break;
		default:
			return mysql_fetch_object($this->data_set);
			break;
		endswitch; 
	}//retorna_dados
	
	function executaSQL($sql = NULL){
		if($sql != NULL):
			$query = mysql_query($sql) or die($this->tratar_erro(__FILE__,__FUNCTION__));
			$this->linhas_afetadas = mysql_affected_rows($this->conexao);
			if(substr(trim(strtolower($sql)),0,6) == 'select'):
				$this->data_set = $query;
				return $query;
			else:
				return $this->linhas_afetadas;
			endif;
		else:
			$this->tratar_erro(__FILE__,__FUNCTION__,NULL,'Comando Sql nao informado na rotina ',FALSE);
		endif;
		
	}//executaSQL

	public function tratar_erro($arquivo=NULL,$rotina=NULL,$numero_erro=NULL,$msg_erro=NULL,$gera_exeption=FALSE){
		if($arquivo == NULL) $arquivo = "Nao informado";
		if($rotina == NULL) $rotina = "Nao informada";
		if($numero_erro == NULL) $numero_erro = mysql_errno($this->conexao);
		if($msg_erro == NULL) $msg_erro = mysql_error($this->conexao);
		$resultado = 	'Ocorreu um erro com os seguintes detalhes:<br />
						<strong>Arquivo: </strong>'.$arquivo.'<br />
						<strong>Rotina: </strong>'.$rotina.'<br />
						<strong>Codigo: </strong>'.$numero_erro.'<br />
						<strong>Mensagem: </strong>'.$msg_erro;
		if($gera_exeption == FALSE):
			echo($resultado);
		else:
			die($resultado);
		endif;
	}//testa_erro
}//fim da classe banco
?>
