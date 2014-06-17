<?php
require_once(dirname(__FILE__)."/autoload.class.php");
protegeArquivo(basename(__FILE__));

class artigo_presente_page extends base {
	public function __construct($campos = array()){
		parent::__construct();
		$this->tabela = 'artigo_presente_page';
		if(sizeof($campos) <= 0):
			$this->campos_valores = array(
				"texto" 			=> NULL,
			);
		else:
			$this->campos_valores = $campos;	
		endif;
		$this->campo_pk = "id";
	}//construct
		
}//fim classe home

?>