<?php 
    class SelectHelper{
       public static function ExibirMeses($strMesFornecido){
            $arrStrMes   = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
            $strOption   = "";
            $strSelected = "";
            $strMes      = "";

            for($intI=0; $intI<count($arrStrMes); $intI++){
                $strMes = $arrStrMes[$intI];
                $strVal = null;
                
                if(($intI + 1) < 10){
                    $strVal = "0".($intI + 1);
                }else{
                    $strVal = ($intI + 1);
                }
                
                if ($strMesFornecido == $strVal){
                    $strSelected .= "selected";			
                }
                
                $strOption .= "<option value='".$strVal."'>".$strMes."</option>";
                $strSelected = "";
            }

            return $strOption;
	}
        
        public static function ExibirAnos($strAnoFornecido){
            $strOption   = "";
            $strSelected = "";

            for($intI=1998; $intI<(date("Y") + 10); $intI++){                
                $strVal = $intI;
                
                if ($strAnoFornecido == $strVal){
                    $strSelected = "selected";			
                }
                
                $strOption  .= "<option value='".$strVal."' ".$strSelected.">".$strVal."</option>";
                $strSelected = "";
            }

            return $strOption;
	}
    }
?>