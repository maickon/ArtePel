<?php
require_once(dirname(__FILE__)."/autoload.class.php");
protegeArquivo(basename(__FILE__));

abstract class usuario extends base {
	public function __construct($campos = array()){
		parent::__construct();
		$this->tabela = 'usuario';
		if(sizeof($campos) <= 0):
			$this->campos_valores = array(
				"nome" 			=> NULL,
				"email"			=> NULL,
				"login"			=> NULL,
				"senha"			=> NULL,
				"ativo"			=> 's',
				"tipo"			=> 'administrador',
				"data_cadastro"	=> NULL
			);
		else:
			$this->campos_valores = $campos;	
		endif;
		$this->campo_pk = "id";
	}//construct
	
	function usuJaExiste($campo = NULL, $valor = NULL){
		if($campo != null && $valor != NULL):
			is_numeric($valor) ? $valor = $valor : $valor = "'".$valor."'";
			$this->extras_select = " WHERE $campo = $valor";
			$this->seleciona_tudo($this);
			if($this->linhas_afetadas > 0):
				return TRUE;
			else:
				return FALSE;
			endif;
		else:
			$this->tratar_erro(__FILE__,__FUNCTION__,NULL,'Faltam parâmetros para executar a função.',TRUE);
		endif;
	}//fim usuJaExiste	
}//fim classe usuario

?>