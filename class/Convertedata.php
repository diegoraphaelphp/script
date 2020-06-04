<?php
  class ConverteData{   

    private $rep;
    public static $instance;  
	
	public static function singleton() {
		if (!isset(self::$instance)) {			
			self::$instance = new ConverteData();
		}
		return self::$instance;
	}	
      
    public function converte($data){ 
	  if ((substr($data,2,3) == "jan") || (substr($data,3,3) == "jan")){ 
		$mes = "01"; 
	  }else 
	  if ((substr($data,2,3) == "fev") || (substr($data,3,3) == "fev")){ 
		$mes = "02"; 
	  }else 
	  if ((substr($data,2,3) == "mar") || (substr($data,3,3) == "mar")){ 
		$mes = "03";
      }else 
	  if ((substr($data,2,3) == "abr") || (substr($data,3,3) == "abr")){ 
	    $mes = "04"; 
	  }else 
	  if ((substr($data,2,3) == "mai") || (substr($data,3,3) == "mai")){ 
	    $mes = "05"; 
      }else 
	  if ((substr($data,2,3) == "jun") || (substr($data,3,3) == "jun")){ 
		$mes = "06"; 
	  }else 
	  if ((substr($data,2,3) == "jul") || (substr($data,3,3) == "jul")){ 
		$mes = "07"; 
	  }else 
	  if ((substr($data,2,3) == "ago") || (substr($data,3,3) == "ago")){ 
	  	$mes = "08"; 
	  }else 
	  if ((substr($data,2,3) == "set") || (substr($data,3,3) == "set")){ 
		$mes = "09"; 
	  }else 
	  if ((substr($data,2,3) == "out") || (substr($data,3,3) == "out")){ 
		$mes = "10"; 
	  }else 
	  if ((substr($data,2,3) == "nov") || (substr($data,3,3) == "nov")){ 
		$mes = "11"; 
	  }else 
	  if ((substr($data,2,3) == "dez") || (substr($data,3,3) == "dez")){ 
		$mes = "12"; 
	  }			
					
	  if(strlen(trim(substr($data,0,2))) == 1){
		$dia = 0 . trim(substr($data,0,2));
		$ano = substr($data,6,4); 
		}else{
		  $dia = substr($data,0,2);
		  $ano = substr($data,7,4);
		}
		  $dataConvertida = $dia."/".$mes."/".$ano; 
		  return $dataConvertida; 		
		}
		
		public function numeroDias($mes){
		
		  if($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12){
		    $dias = 31;
		  }elseif($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11){
		    $dias = 30;
		  }else{
			$dias = 28;
		  }
			return $dias;
		}
		
		public function conData($pardata) {
			$dia = substr($pardata,0,2);
			$mes = substr($pardata,3,2);
			$ano = substr($pardata,6,4);
			$pardata = $ano."-".$mes."-".$dia;
			return $pardata;
	    }
		
		public function desconverteData($pardata) {
			$ano = substr($pardata,0,4);
			$mes = substr($pardata,5,2);
			$dia = substr($pardata,8,2);
			$pardata = $dia."/".$mes."/".$ano;
			return $pardata;
		}
		
		public function nomeMes($mes){
		
					if($mes == 01 || $mes == 1){
						$nome = "Janeiro";	
					}elseif($mes == 02 || $mes == 2){
						$nome = "Fevereiro";
					}elseif($mes == 03 || $mes == 3){
						$nome = "Marco";
					}elseif($mes == 04 || $mes == 4){
						$nome = "Abril";
					}elseif($mes == 05 || $mes == 5){
						$nome = "Maio";
					}elseif($mes == 06 || $mes == 6){
						$nome = "Junho";
					}elseif($mes == 07 || $mes == 7){
						$nome = "Julho";
					}elseif($mes == 08 || $mes == 8){
						$nome = "Agosto";
					}elseif($mes == 09 || $mes == 9){
						$nome = "Setembro";
					}elseif($mes == 10){
						$nome = "Outubro";
					}elseif($mes == 11){
						$nome = "Novembro";
					}elseif($mes == 12){
						$nome = "Dezembro";
					}					
					return $nome;				
		}
		
		
	public function somar_dias_uteis($str_data,$int_qtd_dias_somar){
		// Caso seja informado uma data do MySQL do tipo DATETIME - aaaa-mm-dd 00:00:00
		// Transforma para DATE - aaaa-mm-dd
		$str_data = substr($str_data,0,10);
		// Se a data estiver no formato brasileiro: dd/mm/aaaa
		// Converte-a para o padrao americano: aaaa-mm-dd
		if (preg_match("@/@",$str_data) == 1)
			$str_data = implode("-", array_reverse(explode("/",$str_data)));
			
		$count_days         = 0;
		$int_qtd_dias_uteis = 0;
		while ($int_qtd_dias_uteis < $int_qtd_dias_somar){
			$count_days++;
			if (($dias_da_semana = date('w', strtotime('+'.$count_days.' day'))) != '0' &&
				$dias_da_semana != '6'){
					$int_qtd_dias_uteis++;
			}
		}
		return date('d/m/Y',strtotime('+'.$count_days.' day',strtotime($str_data)));
	}
	
	public function diferenca_datas($date_ini, $date_end, $round = 0){ 
	    $date_ini = strtotime($date_ini); 
	    $date_end = strtotime($date_end); 
		
		$date_diff = ($date_end - $date_ini) / 86400; 
		if($round != 0) 
	        return floor($date_diff); 
	    else 
	        return $date_diff; 
	} 

		
}
?>