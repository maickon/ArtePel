<?php
require_once(dirname(__FILE__)."/autoload.class.php");
protegeArquivo(basename(__FILE__));

class base extends banco {
	//propiedades
	public $tabela = NULL;
	public $campos_valores = array();
	public $campo_pk = NULL;
	public $valor_pk = NULL;
	public $extras_select = "";
	
	//metodos
	public function add_campo($campo = NULL,$valor = NULL){
		if($campo != NULL):
			$this->campos_valores[$campo] = $valor;
		endif;
		
	}//add_campo
	public function del_campo($campo = NULL){
		if(array_key_exists($campo, $this->campos_valores)):
			unset($this->campos_valores[$campo]);
		endif;
	}//del_campo
	
	public function setValor($campo = NULL,$valor = NULL){
		if($campo != NULL && $valor != NULL):
			$this->campos_valores[$campo] = $valor;
		endif;
	}//setValor
	
	public function getValor($campo = NULL){
		if($campo != NULL && array_key_exists($campo, $this->campos_valores)):
			return $this->campos_valores[$campo];
		else:
			return FALSE;
		endif;
	}//getValor
}//fim classe Base

?>