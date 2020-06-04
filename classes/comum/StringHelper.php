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
            
            //verifica se o tamanho do código informado é válido
            if ($intTamanho != 9 && $intTamanho != 12){
                return "00.000.000/0000-00"; 
            }

            if ($booFormatado){ 
                // seleciona a máscara para cpf ou cnpj
                $strMascara = ($intTamanho == 9) ? '###.###.###-##' : '##.###.###/####-##'; 

                $intIndice = -1;
                
                for ($intI=0; $intI < strlen($strMascara); $intI++) {
                    if ($strMascara[$intI] == '#') $strMascara[$intI] = $strCodigoLimpo[++$intIndice];
                }
                
                //retorna o campo formatado
                $strRetorno = $strMascara;
            }else{
                //se não quer formatado, retorna o campo limpo
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
            
            // configura��o mes		
            switch ($intMes){
                case 1:  $strMes = "Janeiro"; break;
                case 2:  $strMes = "Fevereiro"; break;
                case 3:  $strMes = "Mar�o"; break;
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

            // configura��o semana
            switch ($intSemana) {
                case 0: $strSemana = "Domingo"; break;
                case 1: $strSemana = "Segunda-Feira"; break;
                case 2: $strSemana = "Ter�a-Feira"; break;
                case 3: $strSemana = "Quarta-Feira"; break;
                case 4: $strSemana = "Quinta-Feira"; break;
                case 5: $strSemana = "Sexta-Feira"; break;
                case 6: $strSemana = "S�bado"; break;
            }
            
            // agora basta imprimir na tela
            return $strSemana.", ".$intDia." de ".$strMes." de ".$intAno;
	}
        
        public static function RemoverAcentuacao($strTexto){
            $strFrom  = utf8_encode("��������������������������"); 
                        
            $strFinal = str_replace(utf8_encode("�"), "A", $strTexto); 
            $strFinal = str_replace(utf8_encode("�"), "A", $strFinal); 
            $strFinal = str_replace(utf8_encode("�"), "A", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "A", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "E", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "E", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "I", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "O", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "O", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "O", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "U", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "U", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "C", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "a", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "e", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "e", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "i", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "o", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "o", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "o", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "u", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "u", $strFinal);
            $strFinal = str_replace(utf8_encode("�"), "c", $strFinal);
            
            return $strFinal;
        }
    }
?>
