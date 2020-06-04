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
        
        public static function DataHoje(){
            $arrStrMeses = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Mar&ccedil;o", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
            $arrStrDiasDaSemana = array (1 => "Segunda-Feira",2 => "Ter&ccedil;a-Feira",3 => "Quarta-Feira",4 => "Quinta-Feira",5 => "Sexta-Feira",6 => "S&aacute;bado",0 => "Domingo");
            $arrStrHoje = getdate();
            $intDia = $arrStrHoje["mday"];
            $intMes = $arrStrHoje["mon"];
            $strNomeMes = $arrStrMeses[$intMes];
            $intAno = $arrStrHoje["year"];
            $intDiaDaSemana = $arrStrHoje["wday"];
            $strNomeDiaDaSemana = $arrStrDiasDaSemana[$intDiaDaSemana];
            
            return $strNomeDiaDaSemana.', '.$intDia.' de '.$strNomeMes.' de '.$intAno;
        }
        
        public static function AntiSQLInjection($str){
            @$str = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $str);
            @$str = trim($str);
            @$str = strip_tags($str);
            @$str = addslashes($str);
            
            return $str;
	}
        
        public static function RemoverAcentos($strString){
            $strString = htmlentities($strString, ENT_QUOTES, 'UTF-8');
            
            $arrStrPadrao = array (
                // vogais
                '/&agrave;/' => 'a',
                '/&egrave;/' => 'e',
                '/&igrave;/' => 'i',
                '/&ograve;/' => 'o',
                '/&ugrave;/' => 'u',
                
                // vogais
                '/&Agrave;/' => 'A',
                '/&Egrave;/' => 'E',
                '/&Igrave;/' => 'I',
                '/&Ograve;/' => 'O',
                '/&Ugrave;/' => 'U',

                '/&aacute;/' => 'a',
                '/&eacute;/' => 'e',
                '/&iacute;/' => 'i',
                '/&oacute;/' => 'o',
                '/&uacute;/' => 'u',
                
                '/&Aacute;/' => 'A',
                '/&Eacute;/' => 'E',
                '/&Iacute;/' => 'I',
                '/&Oacute;/' => 'O',
                '/&Uacute;/' => 'U',

                '/&acirc;/' => 'a',
                '/&ecirc;/' => 'e',
                '/&icirc;/' => 'i',
                '/&ocirc;/' => 'o',
                '/&ucirc;/' => 'u',
                
                '/&Acirc;/' => 'A',
                '/&Ecirc;/' => 'E',
                '/&Icirc;/' => 'I',
                '/&Ocirc;/' => 'O',
                '/&Ucirc;/' => 'U',

                '/&atilde;/' => 'a',
                '/&etilde;/' => 'e',
                '/&itilde;/' => 'i',
                '/&otilde;/' => 'o',
                '/&utilde;/' => 'u',
                
                '/&Atilde;/' => 'A',
                '/&Etilde;/' => 'E',
                '/&Itilde;/' => 'I',
                '/&Otilde;/' => 'O',
                '/&Utilde;/' => 'U',

                '/&auml;/' => 'a',
                '/&euml;/' => 'e',
                '/&iuml;/' => 'i',
                '/&ouml;/' => 'o',
                '/&uuml;/' => 'u',
                
                '/&Auml;/' => 'A',
                '/&Euml;/' => 'E',
                '/&Iuml;/' => 'I',
                '/&Ouml;/' => 'O',
                '/&Uuml;/' => 'U',

                '/&auml;/' => 'a',
                '/&euml;/' => 'e',
                '/&iuml;/' => 'i',
                '/&ouml;/' => 'o',
                '/&uuml;/' => 'u',
                
                '/&Auml;/' => 'A',
                '/&Euml;/' => 'E',
                '/&Iuml;/' => 'I',
                '/&Ouml;/' => 'O',
                '/&Uuml;/' => 'U',

                // outras letras e caracteres especiais
                '/&aring;/' => 'a',
                '/&ntilde;/' => 'n',
                '/&ccedil;/' => 'c',
                '/&Ccedil;/' => 'C',

                // agregar mais caracteres se necessario
            );
 
            $strString = preg_replace(array_keys($arrStrPadrao), array_values($arrStrPadrao), $strString);
            return $strString;
        }
        
        public static function Upper($strTexto){
            return addslashes(strtoupper(StringHelper::AntiSQLInjection(StringHelper::RemoverAcentos($strTexto))));
        }
        
        public static function AlterarVirgulaPorPonto($strNumero){
            return str_replace(",", ".", $strNumero);
        }
        
        public static function NumberFormat($strNumero){
            return number_format($strNumero, 2, ',', '.'); 
        }
    }
?>
