<?php
  class Config{
    private $rep;
    public static $instance;
	
    private function __contruct($rep="") {
      $this->rep = $rep;	  
	}
	
	public static function singleton() {
		if (!isset(self::$instance)) {			
			self::$instance = new Config();
		}
		return self::$instance;
	}
  
    public function Titulo(){
	  $titulo =  htmlentities("..: SISPLAN - SISTEMA DE PLANEJAMENTO DAS AÇÕES DO IPA :..");
	  return $titulo;
	}
	
	public function RodaPe(){
	  $titulo = "<a href='mailto:diegoraphael.php@gmail.com' title='Desenvolvedor WEB PHP: diegoraphael.php@gmail.com'>COPYRIGHT © 2010 GOVERNO DE PERNAMBUCO<br>
Av. General San Martin, 1371 - San Martin - Recife - PE - CEP: 50761-000 - PABX: (81) 3184-7200</a><br><br>";
	  return $titulo;
	}
	
	public function removeStrings($string,$remove) {
		$tam = strlen($remove);
		$spaces = "";
		for ($i=0; $i < $tam; $i++) {
			$spaces .= " ";
		}
		return $string = str_replace(" ","",strtr($string,$remove,$spaces));
		
	}		
	
	public function organiza_moeda($valor) {
	  $tamanho    = strlen($valor);
	  $novo_valor = "";
	  
	  if (stristr($valor,'.') <> false) {
		  //$novo_valor = "R$ ";
		  $novo_valor .=  number_format($valor, 2, ',', '.');
	  } else {
		if ($tamanho >3) {
			$sub_valor=substr($valor,0,$tamanho-3);
			$sub_valor2=substr($valor,$tamanho-3, $tamanho);
			$novo_valor.="$sub_valor.$sub_valor2,00";
		} else {
			$novo_valor.="$valor,00";
		}
	 }
	 return $novo_valor;
	}
	
	
	public function retornaDiaSemana($data){
	
		$dados->data = $data;
		$dia = substr($dados->data,8,2); 
		$mes = substr($dados->data,5,2); 
		$ano = substr($dados->data,0,4); 
		$data = mktime(0,0,0,$mes,$dia,$ano); 

		$dias=array("Domingo", "Segunda-feira", "Terca-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sabado");
		$dia_semana = $dias[date("w",$data)];
		$meses = array("", "Janeiro", "Fevereiro", "Marco", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro",
		"Novembro","Dezembro");
		$nome_mes   = $meses[date("n",$data)]; 
		return $dia_semana;
	}
	
	
	
	
	
  }
?>