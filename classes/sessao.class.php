<?php
require_once(dirname(__FILE__)."/autoload.class.php");
protegeArquivo(basename(__FILE__));

class sessao{
	protected $id;
	protected $nvars; 
	
	public function __construct($inicia = TRUE){
		if($inicia == TRUE):
			$this->start();
		endif;
	}//fim construct
	
	public function start(){
		@session_start();
		ob_start();
		$this->id = session_id();
		$this->setNvars();
	}//fim start
	
	private function setNvars(){
		$this->nvars = sizeof($_SESSION);
	}//fim setNvars
	
	public function getNvars(){
		return $this->nvars;
	}//fim getNvars
	
	public function setVar($var,$valor){
		$_SESSION[$var] = $valor;
		$this->setNvars();
	}//fim setVars
	
	public function unsetVar($var){
		unset($_SESSION[$var]);
		$this->setNvars();
	}//fim unsetVar
	
	public function getVar($var){
		if(isset($_SESSION[$var])):
			return $_SESSION[$var];
		else:
			return NULL;
		endif;
	}//fim getVar
	
	public function destroy($inicia = FALSE){
		session_unset();
		session_destroy();
		$this->setNvars();
		if ($inicia == TRUE):
			$this->start();
		endif;
	}//fim destroy
	
	public function printAll(){
		foreach ($_SESSION as $k => $v):
			printf("%s = %s<br />",$k,$v);
		endforeach;;
	}//fim printAll
	
}//fim classe sessao

?>