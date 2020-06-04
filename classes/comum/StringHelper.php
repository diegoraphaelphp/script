<?php 
    class StringHelper{
        public static function ConverterDataBancoParaDataUsuario($strData){
            if($strData != ""){
                $arrStrData = explode("-", $strData);
                return $arrStrData[2]."/".$arrStrData[1]."/".$arrStrData[0];            
            }
        }
        
        public static function ConverterDataUsuarioParaDataBanco($strData){
            if($strData != ""){
                $arrStrData = explode("/", $strData);
                return $arrStrData[2]."-".$arrStrData[1]."-".$arrStrData[0];            
            }
        }
        
        public static function RemoverCaracteresParaBanco($strTexto){
            $strTexto = str_replace(".", "", $strTexto);
            $strTexto = str_replace(",", "", $strTexto);
            $strTexto = str_replace("(", "", $strTexto);
            $strTexto = str_replace(")", "", $strTexto);            
            $strTexto = str_replace("-", "", $strTexto);
            $strTexto = str_replace("/", "", $strTexto);
            
            return trim($strTexto);
        }
        
        public static function RemoverCaracteresDinheiroParaBanco($strValor){
            $strValor = str_replace("R$", "", $strValor);
            $strValor = str_replace(".", "", $strValor);
            $strValor = str_replace(",", ".", $strValor);
            
            return trim($strValor);
        }
        
        public static function FormatarCPFouCNPJ($strCampo, $booFormatado = true){
            //retira formato
            $strCodigoLimpo = @ereg_replace("[' '-./ t]", '', $strCampo);
            
            // pega o tamanho da string menos os digitos verificadores
            $intTamanho = (strlen($strCodigoLimpo) - 2);
            
            //verifica se o tamanho do cÃ³digo informado Ã© vÃ¡lido
            if ($intTamanho != 9 && $intTamanho != 12){
                return "00.000.000/0000-00"; 
            }

            if ($booFormatado){ 
                // seleciona a mÃ¡scara para cpf ou cnpj
                $strMascara = ($intTamanho == 9) ? '###.###.###-##' : '##.###.###/####-##'; 

                $intIndice = -1;
                
                for ($intI=0; $intI < strlen($strMascara); $intI++) {
                    if ($strMascara[$intI] == '#') $strMascara[$intI] = $strCodigoLimpo[++$intIndice];
                }
                
                //retorna o campo formatado
                $strRetorno = $strMascara;
            }else{
                //se nÃ£o quer formatado, retorna o campo limpo
                $strRetorno = $strCodigoLimpo;
            }

            return $strRetorno;
        }
        
        public static function FormatarTelefone($strTelefone){
            if(trim($strTelefone) != ""){
                $strPattern = '/(\d{2})(\d{4})(\d*)/';	
                return preg_replace($strPattern, '($1)$2.$3', $strTelefone);
            }
        }
        
        public static function FormatarCEP($strCep){
            $strCepFormatado = "";
            
            if(trim($strCep) != ""){
                for($intI=0; $intI<strlen($strCep); $intI++){
                    $strCepFormatado .= $strCep[$intI];
                    
                    if($intI == 4){
                        $strCepFormatado .= "-";
                    }
                }
            }
            
            return $strCepFormatado;
        }
        
        public static function AntiSQLInjection($str){
            @$str = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $str);
            @$str = trim($str);
            @$str = strip_tags($str);
            @$str = addslashes($str);
            
            return $str;
	}
        
        public static function DataPorExtenso(){	
            // leitura das datas
            $intDia    = date('d');
            $intMes    = date('m');
            $intAno    = date('Y');
            $intSemana = date('w');
            
            $strMes    = '';
            $strSemana = '';
            
            // configuração mes		
            switch ($intMes){
                case 1:  $strMes = "Janeiro"; break;
                case 2:  $strMes = "Fevereiro"; break;
                case 3:  $strMes = "Março"; break;
                case 4:  $strMes = "Abril"; break;
                case 5:  $strMes = "Maio"; break;
                case 6:  $strMes = "Junho"; break;
                case 7:  $strMes = "Julho"; break;
                case 8:  $strMes = "Agosto"; break;
                case 9:  $strMes = "Setembro"; break;
                case 10: $strMes = "Outubro"; break;
                case 11: $strMes = "Novembro"; break;
                case 12: $strMes = "Dezembro"; break;		
            }		

            // configuração semana
            switch ($intSemana) {
                case 0: $strSemana = "Domingo"; break;
                case 1: $strSemana = "Segunda-Feira"; break;
                case 2: $strSemana = "Terça-Feira"; break;
                case 3: $strSemana = "Quarta-Feira"; break;
                case 4: $strSemana = "Quinta-Feira"; break;
                case 5: $strSemana = "Sexta-Feira"; break;
                case 6: $strSemana = "Sábado"; break;
            }
            
            // agora basta imprimir na tela
            return $strSemana.", ".$intDia." de ".$strMes." de ".$intAno;
	}
        
        public static function RemoverAcentuacao($strTexto){
            $strFrom  = utf8_encode("ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç"); 
                        
            $strFinal = str_replace(utf8_encode("À"), "A", $strTexto); 
            $strFinal = str_replace(utf8_encode("Á"), "A", $strFinal); 
            $strFinal = str_replace(utf8_encode("Ã"), "A", $strFinal);
            $strFinal = str_replace(utf8_encode("Â"), "A", $strFinal);
            $strFinal = str_replace(utf8_encode("É"), "E", $strFinal);
            $strFinal = str_replace(utf8_encode("Ê"), "E", $strFinal);
            $strFinal = str_replace(utf8_encode("Í"), "I", $strFinal);
            $strFinal = str_replace(utf8_encode("Ó"), "O", $strFinal);
            $strFinal = str_replace(utf8_encode("Ó"), "O", $strFinal);
            $strFinal = str_replace(utf8_encode("Ô"), "O", $strFinal);
            $strFinal = str_replace(utf8_encode("Ú"), "U", $strFinal);
            $strFinal = str_replace(utf8_encode("Ü"), "U", $strFinal);
            $strFinal = str_replace(utf8_encode("Ç"), "C", $strFinal);
            $strFinal = str_replace(utf8_encode("à"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("á"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("ã"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("â"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("é"), "e", $strFinal);
            $strFinal = str_replace(utf8_encode("ê"), "e", $strFinal);
            $strFinal = str_replace(utf8_encode("í"), "i", $strFinal);
            $strFinal = str_replace(utf8_encode("ó"), "o", $strFinal);
            $strFinal = str_replace(utf8_encode("õ"), "o", $strFinal);
            $strFinal = str_replace(utf8_encode("ô"), "o", $strFinal);
            $strFinal = str_replace(utf8_encode("ú"), "u", $strFinal);
            $strFinal = str_replace(utf8_encode("ü"), "u", $strFinal);
            $strFinal = str_replace(utf8_encode("ç"), "c", $strFinal);
            
            return $strFinal;
        }
    }
?>
