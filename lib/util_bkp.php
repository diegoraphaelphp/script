<?php

	function modelo_email($email_modelo, $assunto_modelo, $texto_modelo){
	
		$headers = "MIME-Version: 1.1\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "From: IPA - NUT (Núcleo de Tecnologia <root@ipa.br>\r\n"; // remetente
		$headers .= "Return-Path: root@ipa.br\r\n"; // return-path
		
		$msg_modelo = "
			<html xmlns=\"http://www.w3.org/1999/xhtml\">
			<head>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
			<link rel='stylesheet' type='text/css' href='http://www.ipa.br/sistemas/pam/css/styles.css'>
			<title>:: IPA - Instituto de Pesquisas Agronômicas de Pernambuco ::</title>
			</head>
			<body>
			<table width='550px' height='263' border='0'  cellpadding='0' cellspacing='0' align='center'>
			  <tr>
			  	<td colspan='2' align='left' class='fontMenu'>
					<img src='http://www.ipa.br/sistemas/pam/img/logo_cliente.png' border='0' width='764px' />
				</td>
			  </tr>
			  <tr>
			  	<td>&nbsp;</td>
			  </tr>
			  <tr>
			  	<td colspan='2' align='center' class='fontText'>".$assunto_modelo."</td>
			  </tr>
			  <tr>
			  	<td>&nbsp;</td>
			  </tr>			  
			  <tr>
			  	<td colspan='2' align='center' class='fontText'>".$texto_modelo."</td>
			  </tr>			  			  
			  <tr>
			  	<td>&nbsp;</td>
			  </tr>
			  <tr>
			  	<td colspan='2' align='center' class='fontText'>
					<a href='mailto:diegoraphael.php@gmail.com' title='Desenvolvedor WEB PHP: diegoraphael.php@gmail.com'>
						COPYRIGHT © 2010 GOVERNO DE PERNAMBUCO<br>Av. General San Martin, 1371 - San Martin - Recife - PE - CEP: 50761-000 - PABX: (81) 3184-7200</a>				
				</td>
			  </tr>
			  <tr>
			  	<td>&nbsp;</td>
			  </tr>
			</table>
			</body>
			</html>";
		
			for($i=0;$i<count($email_modelo);$i++){
				mail($email_modelo[$i], $assunto_modelo, $msg_modelo, $headers);
			}	
	}

	function clearBrowserCache(){
		header("Content-type: text/html; charset=iso-8859-1");	
		header("Pragma: no-cache");
		header("Cache: no-cache");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}

	function exibe_mes($id){
		$mes    = array(1=> "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		$select = "";
		$chk    = "";
		for($i=1;$i<=count($mes);$i++){
			$m = $mes[$i];
			if (strlen($i) == 1) $i = "0".$i;
			if ($id == $i){
				$select.= "<option selected value='".$i."'>".$m."</option>";			
			}else{
				$select.= "<option value='".$i."'>".$m."</option>";			
			}
		}
		return $select;
	}
	
	function mesID($id){
		$id = intval($id);	
		$mes = array(1=> "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		return $mes[$id];
	}

	function data_extenso(){
	
		// leitura das datas
		$dia    = date('d');
		$mes    = date('m');
		$ano    = date('Y');
		$semana = date('w');
		
		// configuração mes		
		switch ($mes){
			case 1: $mes = "Janeiro"; break;
			case 2: $mes = "Fevereiro"; break;
			case 3: $mes = "Março"; break;
			case 4: $mes = "Abril"; break;
			case 5: $mes = "Maio"; break;
			case 6: $mes = "Junho"; break;
			case 7: $mes = "Julho"; break;
			case 8: $mes = "Agosto"; break;
			case 9: $mes = "Setembro"; break;
			case 10: $mes = "Outubro"; break;
			case 11: $mes = "Novembro"; break;
			case 12: $mes = "Dezembro"; break;		
		}		
		
		// configuração semana
		
		switch ($semana) {
		
		case 0: $semana = "Domingo"; break;
		case 1: $semana = "Segunda-Feira"; break;
		case 2: $semana = "Terça-Feira"; break;
		case 3: $semana = "Quarta-Feira"; break;
		case 4: $semana = "Quinta-Feira"; break;
		case 5: $semana = "Sexta-Feira"; break;
		case 6: $semana = "Sábado"; break;
		
		}
		//Agora basta imprimir na tela...
		return "$semana, $dia de $mes de $ano";
	}

	function addEspaco($n, $tam=150){
		$quantidade_zeros = $tam-strlen($n);
		for ($c=0; $c<$quantidade_zeros; $c++) {
			$zeros .= " ";
		}
		return $n.$zeros;
	}

	function Calculo_Primeiro_Digito(&$cpf){
	
		//Soma inicializa com valor ZERO
		$soma=0;
	
		/* Para faça que o $i começa em ZERO e termina em OITO. O $j começa em DEZ e termina em DOIS */
		for($i=0, $j=10; $i<9; $j--, $i++){
			/* Variavel $cpf_novo, que será utilizado para obter os resultados das multiplicaçoes */
			$cpf_novo[$i] = $cpf[$i] * $j;
			/* Variavel $soma, que faz a soma do valor digitado pelo seu correspondente. Soma a cada passagem do FOR */
			$soma += $cpf_novo[$i];
		}
		// Variavel que recebe o resto da divisão da $soma por 11
		$resto = $soma % 11;
	
		//Condições
		if ($resto < 2){
			$digito_um = 0;
			return $digito_um;//Ira retornar para função Calculo_Primeiro_Digito
		}else{
			$digito_um = 11 - $resto;
			return $digito_um;//Ira retornar para função Calculo_Primeiro_Digito
		}
	}//Fim da funcao Calculo_Primeiro_Digito
	
	function Calculo_Segundo_Digito(&$cpf, &$retorno_um){
	
		//Soma inicializa com valor ZERO
		$soma=0;
	
		/* Para faça que o $i começa em ZERO e termina em OITO. O $j começa em ONZE e termina em TRES */
		for($i=0, $j=11; $i<9; $j--, $i++){
			/* Variavel $cpf_novo, que será utilizado para obter os resultados das multiplicaçoes */
			$cpf_novo[$i] = $cpf[$i] * $j;
			/* Variavel $soma, que faz a soma do valor digitado pelo seu correspondente. Soma a cada passagem do FOR */
			$soma += $cpf_novo[$i];
		}//Fim do FOR
		/* Variavel $soma que faz a soma do valor primeiro digito encontrado MULT 2 e soma com seu valor correspondente */
		$soma = ($retorno_um * 2) + $soma;
		// Variavel que recebe o resto da divisão da $soma por 11
		$resto = $soma % 11;
	
		//Condições
		if($resto < 2){
			$digito_dois = 0;
			return $digito_dois;//Ira retornar para função Calculo_Segundo_Digito
		}else{
			$digito_dois = 11 - $resto;
			return $digito_dois;//Ira retornar para função Calculo_Segundo_Digito
		}	
	}//Fim da função Calculo_Segundo_Digito	
	
	
	function Validar_cpf(&$cpf, &$retorno_um, &$retorno_dois){
		/* Condições
		Compara se o numero digitado pelo usuario é igual ao que o calculo retornou, se for igual, informa ao usuario que o CPF é valido, senao informa que é invalido
		e se não tem números repetidos
		*/
	
		$invalidos = array('00000000000', '11111111111', '22222222222', '33333333333', '44444444444', '55555555555', '66666666666', '77777777777', '88888888888', '99999999999');
	
		if (($cpf[9]==$retorno_um) && ($cpf[10]==$retorno_dois) && (!in_array($cpf, $invalidos))) {
		/*
			echo "<center>CPF VALIDO</center>\n";
			echo "<center>Esse CPF está correto ";
		*/
		}else{
			exit("
			<table width=\"15%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
			  <tr>
				<td colspan='2' align=\"center\" class=\"titulo_erro\">ATEN&Ccedil;&Atilde;O!</td>
			  </tr>
			  <tr>
				<td colspan='2' align=\"center\" class=\"msg_erro\">CPF Inv&aacute;lido!</td>
			  </tr>
			</table>");
		}
	}//fim funcao Validar_cpf

	function cor($str, $procura){
		$cores = array("#FFFF00", "#00FFFF", "#FF99FF", "#66FFFF", "#66FF00", "#FFFF99", "#9999FF", "#CCFFFF");
		$ind   = rand(0, 7);
		return str_ireplace($procura, "<span style=\"background-color:".$cores[$ind].";font-weight:bold\">".strtoupper($procura)."</span>", $str, $qtd);		
	}

	function pegaIP(){	
		$ip       = ""; 
		$ip_proxy = "";
		if (getenv(HTTP_X_FORWARDED_FOR)){ 
		  if (getenv(HTTP_CLIENT_IP)){ 
		    $ip = getenv(HTTP_CLIENT_IP); 
		  }else{ 
		    $ip = getenv(HTTP_X_FORWARDED_FOR); 
		  } 
		  $ip_proxy = getenv(REMOTE_ADDR); 
		}else{ 
		  $ip = getenv(REMOTE_ADDR); 
		}
		return explode("###", $ip."###".$ip_proxy);
	}

	function limpaSessao($array){	  
	  session_start();
	  $tot   = 0;
	  $tot   = count($array);
	  $array = array_keys($array);
	  for($x=0;$x<$tot;$x++){
	  	session_unregister($array[$x]);	  
	  }
	  session_destroy();
	}
	
	function maskCEP($campo){	
	  $part1 = substr($campo, 0, 5);
	  $part2 = substr($campo, 5, 3);	  
	  $campo = $part1."-".$part2;
	  return $campo;
	}

	function uploadImg($img, $local){
	
		if($img["name"] != ""){
		  if ($img["type"] != "image/pjpeg" && $img["type"] != "image/x-png" && $img["type"] != "image/jpeg" 
			&& $img["type"] != "image/png" && $img["type"] != "image/gif"){
				return false;
		  }else{
			$uploaddir = $local;
			$uploaddir.= $img["name"];
			
			if(move_uploaded_file($img["tmp_name"], $uploaddir)){
			  $nome         = retiraAcento($img["name"]);
			  $nome_arquivo = rand(9, 99999).$nome;
			  rename($local.$img["name"], $local.$nome_arquivo);
			  return $nome_arquivo;		  
			}else{
			  return false;
			} 
		  }
		}
	}
	
	function antInjection($str){
		@$str = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$str);
		@$str = trim($str);
		@$str = strip_tags($str);
		@$str = addslashes($str);
		return $str;
	}
	
	function alert($mensagem){
			echo "<script>";
			echo "alert('".$mensagem."')";
			echo "</script>";
	}
	
	function fechar(){
			echo "
			<script>
				window.close();
			</script>";
	}
	
	function goto2($pagina){
		echo "<script>window.location.href = '".$pagina."';</script>";
		exit;
	}
	
	function anterior($x) {
		echo "<script>javascript:history.back(".$x.")</script>";
		exit;
	}
	
	function completarComZero($n,$qtd_zeros=5) {
		$quantidade_zeros = $qtd_zeros-strlen($n);	
		$zeros            = "";
		for ($c=0; $c<$quantidade_zeros; $c++) {
			$zeros.= "0";
		}
		return $zeros.$n;
	}
	
	function existKey($table,$key,$value) {
		$result = mysql_query("SELECT $key FROM $table WHERE $key='$value'");
	
			if (mysql_num_rows($result)>0) {
				return true;
			} else {
				return false;
			}
	}
	
	function gerarCombo($sql,$selected="",$encode=0) {
		$result = mysql_query($sql);
		while ($dados = mysql_fetch_array($result)) {
			if ($encode) {
				$text	= 	urlencode($dados[0]);
				$value 	=	urlencode($dados[1]);
			} else {
				$text	= 	$dados[0];
				$value 	=	$dados[1];
			}
			
			if (is_array($selected)) {
				for ($i=0; $i < count($selected); $i++) {
					if ($value == $selected[$i]) {
						$s = "selected";
						break;
					} else {
						$s = "";
					}
				}
				print ("\t<option $s value='$value'>$text</option>\n");
				$s = "";
			} else {
				if ($value == $selected) {
					print ("\t<option selected value='$value'>$text</option>\n");
				} else {
					print ("\t<option value='$value'>$text</option>\n");
				}
			}
		}
	}
	
	function equalToOut($v1, $v2, $out) {
		if ($v1 == $v2) {
			echo $out;
		}
	}
	
	function verificaCamposObrigatorios($campos, $destino) {
		/****** testando os dados (inicio) ******/
		for ($i=0; $i < count($campos); $i++) {
			if ($campos[$i] == "" or $campos[$i] == NULL) {
				alert("Verifique os campos obrigatórios.");
				goto2($destino);
				exit();			
			}
		}	
		/****** testando os dados (fim) ******/	
	}
	
	function escapestrings($b) {
		//se magic_quotes nao estiver ativado, escapa a string
		if (!get_magic_quotes_gpc()) {
			return mysql_escape_string($b); // funcao nativa do php para escapar variaveis.
		} else { 
			// caso contrario
			return $b; // retorna a variavel sem necessidade de escapar duas vezes
		}
	}
	
	function removeStrings($string,$remove) {
		$tam = strlen($remove);
		$spaces = "";
		for ($i=0; $i < $tam; $i++) {
			$spaces .= " ";
		}
		return $string = str_replace(" ","",strtr($string,$remove,$spaces));
		
	}
	
	function formatDateBrazilToAmerican($date,$separate,$newSeparate=" ") {
		$newDate = explode($separate,$date);
		if ($newSeparate == " ") $newSeparate = $separate;
		return $newDate[2] . $newSeparate . $newDate[1] . $newSeparate . $newDate[0];
	}
	
	function formatDateAmericanToBrazil($date,$separate,$newSeparate=" ") {
		$newDate = explode($separate,$date);
		if ($newSeparate == " ") $newSeparate = $separate;
		return $newDate[2] . $newSeparate . $newDate[1] . $newSeparate . $newDate[0];
	}
	
	function inverterFormatoData($date,$separate,$newSeparate=" ") {
		$newDate = explode($separate,$date);
		if ($newSeparate == " ") $newSeparate = $separate;
		return $newDate[2] . $newSeparate . $newDate[1] . $newSeparate . $newDate[0];
	}
	
	function mascara($expr,$mask) {
	# EXEMPLO: echo mascara("12345678901234","99.999.999/9999-99");#
		$ret="";
		$j=0;
		for ($i = 0; $i < strlen($expr); $i ++)
			{
			if ( ( $mask[$j]!="9" ) and ( $mask[$j]!="X" ) and ( $mask[$j]!="#" ) )
			{
			$ret.=$mask[$j];
			$j++;
			}
			$ret.=$expr[$i];
			$j++;
			}
		return $ret;
	}
	
	function escreveData($data) {  
		$vardia = substr($data, 8, 2); 
		$varmes = substr($data, 5, 2); 
		$varano = substr($data, 0, 4); 
		
		$convertedia = date ("w", mktime (0,0,0,$varmes,$vardia,$varano)); 
		
		$diaSemana = array("Domingo", "Segunda-feira", "Terca-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sabado");  
			  
		$mes = array(1=>"Janeiro", "Fevereiro", "Marco", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		
		//return $diaSemana[$convertedia] . ", " . $vardia  . " de " . $mes[$varmes] . " de " . $varano;
		//pega o dia por extenso + a data + o mês + o ano!
		$varmes = settype($varmes,"int");
		return $vardia  . " de " . $mes[$varmes] . " de " . $varano;
	}
	
	function soNumeros($str) {
		$numeros = "0123456789";
		for ($i=0; $i < strlen($str); $i++) {
			for ($j=0; $j < strlen($numeros); $j++) {
				if ($str[$i] != $numeros[$j]) {
					$nao_numero = true;
				} else {
					$nao_numero = false;
				}	
				if ($nao_numero) {
					$str = str_replace($str[$i],"",$str);
				}
			}
		}
		return $str;
	}
	
	#******************#
	# Funcões de Banco #
	#******************#
	
	// traz os numeros dos registros
	function bd_query($query) {
	//  	if (!mysql_query($query)) exit("ERRO!!");
		return  mysql_query($query);
	}
	
	function bd_result($result,$indice,$campo) {
		return mysql_result($result,$indice,$campo);
	}
	
	function bd_fetch_array($result) {
		return mysql_fetch_array($result);
	}
	
	function bd_num_rows($result) {
		return mysql_num_rows($result);
	}
	
	function bd_error() {
		return mysql_error();
	}
	
	function bd_ultimo_id($sql){
		 return mysql_insert_id();
	 }
	
	#************************#
	# #END; Funcões de Banco #
	#************************#
	
	function retiraAcento($texto) {
	$com = array("a", "à", "â", "a", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "u", "ù", "û", "ü", "c"
				, "a", "À", "Â", "a", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "u", "Ù", "Û", "Ü", "c" ); 
	$sem = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
				, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
	return str_replace( $com, $sem, $texto ); 
	}
	
	function organiza_moeda($valor) {
	  $tamanho=strlen($valor);
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
	
	function escreveExtenso($valor=0,$tipo=1,$caixa="alta") {
		
		$valor = strval($valor);
		$valor = str_replace(",",".",$valor);
	
		if ($tipo==1) {
			$singular = array("centavo", "real", "mil", "milhao", "bilhao", "trilhao", "quatrilhao");
			$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
		} elseif ($tipo==2) {
			$pos   = strpos($valor,".");
			$valor = substr($valor,0,$pos);
			$singular = array("", "", "mil", "milhao", "bilhao", "trilhao", "quatrilhao");
			$plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
		}
		
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
	"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
	"sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
	"dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
	"sete", "oito", "nove");
	
		$z=0;
	
		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];
	
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? " e " : " e ") : " ") . $r;
		}
		
		if ($caixa=="alta") {
			$rt = strtoupper($rt);
		} elseif ($caixa=="baixa") {
			$rt = strtolower($rt);
		} elseif ($caixa=="comeca_alta") {
			//$rt = ucwords($rt);
			$rt = ucfirst($rt);
		}
		
		$maiusculas = array("a","À","Â","a","É","Ê","Í","Ó","Ô","Õ","u","Û");
		$minusculas = array("a","à","â","a","é","ê","í","ó","ô","õ","u","û");
		
		for($i=0;$i<count($maiusculas);$i++){
			$rt = ereg_replace($minusculas[$i],$maiusculas[$i],$rt);
		}     
		
		return $rt;
	}
	
	function conectar_base($host,$user,$pass,$database){
		$con = @mysql_connect($host,$user,$pass,$database);
		if ($con) {
			return true;
		}else{
			return false;
		}
	}
	
	function datalocal($local = "Juiz de Fora")
	{
		$mes_array = array("janeiro", "fevereiro", "marco", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
		$dia_atual= date(d);
		$mes_atual= gmdate(m);
		$ano_atual= gmdate(Y);
		
		 $extenso = " $local, " . $dia_atual . " de " . $mes_array[$mes_atual-1] . " de " . $ano_atual;
	return $extenso;
	}
	
	function extenso($valor=0, $maiusculas=false)
	{
		// verifica se tem virgula decimal
		if (strpos($valor,",") > 0)
		{
		  // retira o ponto de milhar, se tiver
		  $valor = str_replace(".","",$valor);
	
		  // troca a virgula decimal por ponto decimal
		  $valor = str_replace(",",".",$valor);
		}
	
			$singular = array("centavo", "real", "mil", "milhao", "bilhao", "trilhao", "quatrilhao");
			$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
	"quatrilhões");
	
			$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
	"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
			$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
	"sessenta", "setenta", "oitenta", "noventa");
			$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
	"dezesseis", "dezesete", "dezoito", "dezenove");
			$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
	"sete", "oito", "nove");
	
			$z=0;
	
			$valor = number_format($valor, 2, ".", ".");
			$inteiro = explode(".", $valor);
			for($i=0;$i<count($inteiro);$i++)
					for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
							$inteiro[$i] = "0".$inteiro[$i];
	
			$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
			for ($i=0;$i<count($inteiro);$i++) {
					$valor = $inteiro[$i];
					$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
					$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
					$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
					$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
	$ru) ? " e " : "").$ru;
					$t = count($inteiro)-1-$i;
					$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
					if ($valor == "000")$z++; elseif ($z > 0) $z--;
					if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
					if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
	($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
			}
	
			 if(!$maiusculas){
							  return($rt ? $rt : "zero");
			 } elseif($maiusculas == "2") {
							  return (strtoupper($rt) ? strtoupper($rt) : "Zero");
			 } else {
							  return (ucwords($rt) ? ucwords($rt) : "Zero");
			 }
	
	}
	
	
	//extensoMoeda( 12345678.90, "real", "reais", "centavo", "centavos" ) ;
	function extensoMoeda( $valor, $moedaSing, $moedaPlur, $centSing, $centPlur ) {
	
	   $centenas = array( 0,
		   array(0, "cento",        "cem"),
		   array(0, "duzentos",     "duzentos"),
		   array(0, "trezentos",    "trezentos"),
		   array(0, "quatrocentos", "quatrocentos"),
		   array(0, "quinhentos",   "quinhentos"),
		   array(0, "seiscentos",   "seiscentos"),
		   array(0, "setecentos",   "setecentos"),
		   array(0, "oitocentos",   "oitocentos"),
		   array(0, "novecentos",   "novecentos") ) ;
	
	   $dezenas = array( 0,
				"dez",
				"vinte",
				"trinta",
				"quarenta",
				"cinqüenta",
				"sessenta",
				"setenta",
				"oitenta",
				"noventa" ) ;
	
	   $unidades = array( 0,
				"um",
				"dois",
				"três",
				"quatro",
				"cinco",
				"seis",
				"sete",
				"oito",
				"nove" ) ;
	
	   $excecoes = array( 0,
				"onze",
				"doze",
				"treze",
				"quatorze",
				"quinze",
				"dezeseis",
				"dezesete",
				"dezoito",
				"dezenove" ) ;
	
	   $extensoes = array( 0,
		   array(0, "",       ""),
		   array(0, "mil",    "mil"),
		   array(0, "milhao", "milhões"),
		   array(0, "bilhao", "bilhões"),
		   array(0, "trilhao","trilhões") ) ;
	
	   $valorForm = trim( number_format($valor,2,".",",") ) ;
	
	   $inicio    = 0 ;
	
	   if ( $valor <= 0 ) {
		  return ( $valorExt ) ;
	   }
	
	   for ( $conta = 0; $conta <= strlen($valorForm)-1; $conta++ ) {
		  if ( strstr(",.",substr($valorForm, $conta, 1)) ) {
			 $partes[] = str_pad(substr($valorForm, $inicio, $conta-$inicio),3," ",STR_PAD_LEFT) ;
			 if ( substr($valorForm, $conta, 1 ) == "." ) {
				break ;
			 }
			 $inicio = $conta + 1 ;
		  }
	   }
	
	   $centavos = substr($valorForm, strlen($valorForm)-2, 2) ;
	
	   if ( !( count($partes) == 1 and intval($partes[0]) == 0 ) ) {
		  for ( $conta=0; $conta <= count($partes)-1; $conta++ ) {
	
			 $centena = intval(substr($partes[$conta], 0, 1)) ;
			 $dezena  = intval(substr($partes[$conta], 1, 1)) ;
			 $unidade = intval(substr($partes[$conta], 2, 1)) ;
	
			 if ( $centena > 0 ) {
	
				$valorExt .= $centenas[$centena][($dezena+$unidade>0 ? 1 : 2)] . ( $dezena+$unidade>0 ? " e " : "" ) ;
			 }
	
			 if ( $dezena > 0 ) {
				if ( $dezena>1 ) {
				   $valorExt .= $dezenas[$dezena] . ( $unidade>0 ? " e " : "" ) ;
	
				} elseif ( $dezena == 1 and $unidade == 0 ) {
				   $valorExt .= $dezenas[$dezena] ;
	
				} else {
				   $valorExt .= $excecoes[$unidade] ;
				}
	
			 }
	
			 if ( $unidade > 0 and $dezena != 1 ) {
				$valorExt .= $unidades[$unidade] ;
			 }
	
			 if ( intval($partes[$conta]) > 0 ) {
				$valorExt .= " " . $extensoes[(count($partes)-1)-$conta+1][(intval($partes[$conta])>1 ? 2 : 1)] ;
			 }
	
			 if ( (count($partes)-1) > $conta and intval($partes[$conta])>0 ) {
				$conta3 = 0 ;
				for ( $conta2 = $conta+1; $conta2 <= count($partes)-1; $conta2++ ) {
				   $conta3 += (intval($partes[$conta2])>0 ? 1 : 0) ;
				}
	
				if ( $conta3 == 1 and intval($centavos) == 0 ) {
				   $valorExt .= " e " ;
				} elseif ( $conta3>=1 ) {
				   $valorExt .= ", " ;
				}
			 }
	
		  }
	
		  if ( count($partes) == 1 and intval($partes[0]) == 1 ) {
			 $valorExt .= $moedaSing ;
	
		  } elseif ( count($partes)>=3 and ((intval($partes[count($partes)-1]) + intval($partes[count($partes)-2]))==0) ) {
			 $valorExt .= " de " + $moedaPlur ;
	
		  } else {
			 $valorExt = trim($valorExt) . " " . $moedaPlur ;
		  }
	
	   }
	
	   if ( intval($centavos) > 0 ) {
	
		  $valorExt .= (!empty($valorExt) ? " e " : "") ;
	
		  $dezena  = intval(substr($centavos, 0, 1)) ;
		  $unidade = intval(substr($centavos, 1, 1)) ;
	
		  if ( $dezena > 0 ) {
			 if ( $dezena>1 ) {
				$valorExt .= $dezenas[$dezena] . ( $unidade>0 ? " e " : "" ) ;
	
			 } elseif ( $dezena == 1 and $unidade == 0 ) {
				$valorExt .= $dezenas[$dezena] ;
	
			 } else {
				$valorExt .= $excecoes[$unidade] ;
			 }
	
		  }
	
		  if ( $unidade > 0 and $dezena != 1 ) {
			 $valorExt .= $unidades[$unidade] ;
		  }
	
		  $valorExt .= " " . ( intval($centavos)>1 ? $centPlur : $centSing ) ;
	
	   }
	
	   return ( $valorExt ) ;
	
	}
	
	//echo somadata("31/01/2004",1);
	function somadata2($pData, $pDias){
		if(ereg("([0-9]{2})/([0-9]{2})/([0-9]{4})", $pData, $vetData)){;
			$fDia = $vetData[1];
			$fMes = $vetData[2];
			$fAno = $vetData[3];
	
			for($x=0;$x<$pDias;$x++){
				if($fMes == 1 || $fMes == 3 || $fMes == 5 || $fMes == 7 || $fMes == 8 || $fMes == 10 || $fMes == 12){
					$fMaxDia = 31;
				}elseif($fMes == 4 || $fMes == 6 || $fMes == 9 || $fMes == 11){
					$fMaxDia = 30;
				}else{
					if($fMes == 2 && $fAno % 4 == 0 && $fAno % 100 != 0){
						$fMaxDia = 29;
					}elseif($fMes == 2){
						$fMaxDia = 28;
					}
				}
				$fDia++;
				if($fDia > $fMaxDia){
					if($fMes == 12){
						$fAno++;
						$fMes = 1;
						$fDia = 1;
					}else{
						$fMes++;
						$fDia = 1;
					}
				}
			}
			
			if(strlen($fDia) == 1) $fDia = "0" . $fDia;
			if(strlen($fMes) == 1) $fMes = "0" . $fMes;
			return "$fDia/$fMes/$fAno";

		}else{
			return "Data Invalida.";
		}
	}
	
	function pegaDataIniFimSemana(){
		
		$semana = date("w");
		$ini 	= 0;
		$fim 	= 0;
		$ini 	= ($semana - 6);
		$ini 	= ($ini) * (-1);
		$dtfim  = somadata(date("Ymd"), $ini);
		$dtini  = subtrairData(date("Ymd"), $semana);	
		return array($dtini, $dtfim);
	}

	function somadata($date,$days){
	
		 $thisyear  = substr($date, 0, 4);
		 $thismonth = substr($date, 4, 2);
		 $thisday   = substr($date, 6, 2);
		 $nextdate  = mktime(0, 0, 0, $thismonth, $thisday + $days, $thisyear );
		 $data      = strftime("%Y%m%d", $nextdate);
		 return substr($data, 0,4)."-".substr($data, 4,2)."-".substr($data, 6,2);
	}
	
	function subtrairData($date,$days){
	
		 $thisyear  = substr( $date, 0, 4);
		 $thismonth = substr( $date, 4, 2);
		 $thisday   = substr( $date, 6, 2);
		 $nextdate  = mktime(0, 0, 0, $thismonth, $thisday - $days, $thisyear );
		 $data      = strftime("%Y%m%d", $nextdate);
		 return substr($data, 0,4)."-".substr($data, 4,2)."-".substr($data, 6,2);		 

	}

	
	function TamanhoArquivo($cFile) { 
			 if ( file($cFile) ){ 
				$nSize = filesize($cFile); 
				if ($nSize<1024) { return strval($nSize).' bytes'; } 
				if ($nSize<pow(1024,2)) { return inttostr( $nSize/1024, 1).' KB' ; } 
				if ($nSize<pow(1024,3)) { return inttostr( $nSize/pow(1024,2), 1).' MB'; } 
				if ($nSize<pow(1024,4)) { return inttostr( $nSize/pow(1024,3), 1).' GB'; } 
			 } 
	} 
		
	function inttostr( $nNum, $nDecimais ) { 
			 $ResConv = strval($nNum); 
			 $Pos = strrpos ($ResConv, '.'); 
			 if ($pos === false) { 
				 return $ResConv; 
			 } else { 
				 return substr($ResConv,0,$Pos+$nDecimais+1); 
			 } 
	}  
	
	
	function gerar_grafico($vetor, $info, $info2){
	
	//Define a quantidade de parametros
	$tamanho = count($vetor[qtd]);
	
	//define o maior parametro
	$maior = 0;
	$total = 0;
	for($i=0;$i<$tamanho;$i++):
	  if ($vetor[qtd][$i]>$maior):
		$maior=$vetor[qtd][$i];
		$vetor_maior=$i;
	  endif;	  
	  $vr    = $vetor[qtd][$i];//str_replace(",", ".", removeStrings($vetor[qtd][$i], "."));
	  $total = $total+$vr;
	endfor;
	
	//Calcula a altura e largura ideais
	$largura= 50*$tamanho+100;
	if ($largura<350)
	  $largura=350;
	$altura= 20*$tamanho+310;
	
	//Cria a imagem
	$img = imagecreate($largura,$altura);
	
	//Define cores
	$fundo = imagecolorallocate($img,230,239,248);
	$vermelho = imagecolorallocate($img,255,0,0);
	$branco = imagecolorallocate($img,255,255,255);
	$corret = imagecolorallocate($img,51,153,255);
	$cinza = imagecolorallocate($img,100,100,100);
	$azul = imagecolorallocate($img,0,0,255);
	$azulescuro = imagecolorallocate($img,102,153,204);
	$preto = imagecolorallocate($img,0,0,0);
	
	//Define o numero à esquerda
	$numero_esquerda = ($maior/4);
	
	
	//Define a altura certa para os retangulos
	for($i=0;$i<$tamanho;$i++):
	  $var[$i] = 180 - (($vetor[qtd][$i]*150)/$maior);
	endfor;
	
	//Gera as linhas intermediarias
	imagefilledrectangle($img,40,30,$largura-20,31,$azulescuro);
	imagefilledrectangle($img,40,68,$largura-20,69,$azulescuro);
	imagefilledrectangle($img,40,104,$largura-20,105,$azulescuro);
	imagefilledrectangle($img,40,142,$largura-20,143,$azulescuro);
	
	//   Gera os triangulos pequenos que ligam os retangulos principais
	// com os retangulos sombreados para formar uma imagem 3D.
	$r=60;
	$s=65;
	for ($i=0;$i<$tamanho;$i++):
	
	if ($vetor[qtd][$i]!=0):
	$values_cima = array(
	  0  => $r,    			// x1
	  1  => $var[$i],    		// y1
	  2  => $s,   			// x2
	  3  => $var[$i]-5,	     	// y2
	  4  => $r+5,    		// x3
	  5  => $var[$i],   		// y3
	);
	
	$values_baixo = array(
	  0  => $r+31,    		// x1
	  1  => 181,    		// y1
	  2  => $r+31,   		// x2
	  3  => 176,    		// y2
	  4  => $r+35,    		// x3
	  5  => 176,   			// y3
	);
	$r=$r+50;
	$s=$s+50;
	imagefilledpolygon($img, $values_baixo, 3, $cinza );
	imagefilledpolygon($img, $values_cima, 3, $cinza );
	endif;
	endfor;
	
	//Gera os retangulos principais e sombreados
	$x1=60;
	$x2=90;
	for ($i=0;$i<$tamanho;$i++):
	  if ($vetor[qtd][$i]!=0):
		imagefilledrectangle($img,$x1+5,$var[$i]-5,$x2+5,176,$cinza);
		imagefilledrectangle($img,$x1,$var[$i],$x2,180,$corret);
	  endif;
	  $x1=$x1+50;
	  $x2=$x2+50;
	endfor;
	
	//Gera as linhas principais
	imagefilledrectangle($img,20,181,$largura-20,183,$preto);
	imagefilledrectangle($img,38,00,40,200,$preto);
	
	//Gera o numero do parametro
	$v=72;
	for($i=0;$i<$tamanho;$i++):
	  imagestring($img, 2, $v, 185, $i+1, $preto);
	  $v=$v+50;
	endfor;
	
	//Gera os numeros das linhas intermediarias
	imagestring($img, 2, 05, 24, $numero_esquerda*4, $preto);
	imagestring($img, 2, 05, 61, $numero_esquerda*3, $preto);
	imagestring($img, 2, 05, 96, $numero_esquerda*2, $preto);
	imagestring($img, 2, 05, 135, $numero_esquerda, $preto);
	
	//Gera o nome dos parametros
	$alt=225;
	for($i=0;$i<$tamanho;$i++):
	  imagestring($img, 3, 05, $alt, ($i+1)."-", $corret);
	  imagestring($img, 3, 25, $alt, $vetor[nome][$i] , $preto);
	  $alt=$alt+13;
	endfor;
	imagefilledrectangle($img,20,$alt+10,$largura-20,$alt+11,$cinza);
	
	//Gera resumo embaixo da imagem
	
	imagestring($img, 5, 20, $alt+16, "Total Geral : ", $corret);
	imagestring($img, 5, 145, $alt+16, "R$ ".$total, $preto);
	imagestring($img, 5, 05, $alt+29, "Periodo Maior : ", $corret);
	imagestring($img, 5, 145, $alt+29, $vetor[nome][$vetor_maior], $preto);		

	imagestring($img, 5, 65, $alt+42, "Usuário: ", $corret);
	imagestring($img, 5, 145, $alt+42, $info, $preto);

	imagestring($img, 5, 65, $alt+58, "Receita: ", $corret);
	imagestring($img, 5, 145, $alt+58, $info2, $preto);

	//Numero de qtd de cada filme
	$num=67;
	for($i=0;$i<$tamanho;$i++):
	  for($j=0;$j<=9;$j++):
		if ($vetor[qtd][$i]==$j):
		  $vetor[qtd][$i] = "0".$vetor[qtd][$i];
		endif;
	  endfor;
	  if ($vetor[qtd][$i]!=0):
		imagestring($img,3,$num,$var[$i]-18,$vetor[qtd][$i],$preto);
		else:
		  imagestring($img,3,$num,168,$vetor[qtd][$i],$preto);
	  endif;
	  $num = $num+50;
	endfor;
	
	imagepng($img);
	imagedestroy($img);
  }		
  
	/*
	Retorna diferença entre as datas em Dias, Horas ou Minutos
	
	Function Diferenca(data maior, [data menos],[dias horas ou minutos])
	
	Primeiro parametro, Data de inicio, no formato 04/05/2006 12:00
	Se não passado o seundo parametro, dá o valor da data atual
	Terceiro parametro, diferença a ser retornada:
	
	 "m" Minutos
	 "H" Horas
	 "h": Horas arredondada
	 "D": Dias 
	 "d": Dias arredontados
	
	Gambiarra.com.br
	Bozo@gambiarra.com.br
	*/
	
	function Diferenca($data1, $data2="",$tipo=""){
	
		if($data2=="") $data2 = date("d/m/Y H:i");
		if($tipo=="") $tipo = "h";
		
		for($i=1;$i<=2;$i++){
			${"dia".$i} = substr(${"data".$i},0,2);
			${"mes".$i} = substr(${"data".$i},3,2);
			${"ano".$i} = substr(${"data".$i},6,4);
			${"horas".$i} = substr(${"data".$i},11,2);
			${"minutos".$i} = substr(${"data".$i},14,2);
		}
		
		$segundos = mktime($horas2,$minutos2,0,$mes2,$dia2,$ano2) - mktime($horas1,$minutos1,0,$mes1,$dia1,$ano1);
		
		switch($tipo){
		
		case "m": $difere = $segundos/60;    break;
		case "H": $difere = $segundos/3600;    break;
		case "h": $difere = round($segundos/3600);    break;
		case "D": $difere = $segundos/86400;    break;
		case "d": $difere = round($segundos/86400);    break;
	
		}
		
		return $difere;
	}
	
	function avaliaArgParaSql($arg, $ehString=1)	{
		if ($ehString)	{
			if(trim($arg))	{
				return "'".trim($arg)."'";
			}else{
				return "null";
			}
		}else{
			if($arg)	{
				return trim($arg);
			}else{
				return "null";
			}
		}	
	}
	
	function maskCpf($cpf)	{
		return substr($cpf, 0, 3).'.'.substr($cpf, 3, 3).'.'.substr($cpf, 6, 3).'-'.substr($cpf, 9, 2);
	}
	
	// Função que valida o CPF
	function validaCPF($cpf){	// Verifiva se o número digitado contém todos os digitos
		$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
		
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'){
			return false;
		}else{   // Calcula os números para verificar se o CPF é verdadeiro
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf{$c} * (($t + 1) - $c);
				}
				
				$d = ((10 * $d) % 11) % 10;
				
				if ($cpf{$c} != $d) return false;
			}
			
			return true;
		}
	}
    
    function validaCNPJ($cnpj) {
        
        //10.912.293/0001-37
        //
        //$cnpj = "10912293000137";
        /*
        $cnpj = str_replace(".", "", $cnpj);
        $cnpj = str_replace("/", "", $cnpj);
        $cnpj = str_replace("-", "", $cnpj);
        */
        //$cnpj = substr($cnpj, 0, 2).".".substr($cnpj, 2, 3).".".substr($cnpj, 5, 3)."/".substr($cnpj, 8, 4)."-".substr($cnpj, 12, 2).' teste<br>';
        //echo $cnpj.' teste';
        
        $cnpj = preg_replace ("@[./-]@", "", $cnpj);
        if (strlen ($cnpj) <> 14 or !is_numeric ($cnpj))
        {
        return 0;
        }
        $j = 5;
        $k = 6;
        $soma1 = "";
        $soma2 = "";
        
        for ($i = 0; $i < 13; $i++)
        {
        $j = $j == 1 ? 9 : $j;
        $k = $k == 1 ? 9 : $k;
        $soma2 += ($cnpj{$i} * $k);
        
        if ($i < 12)
        {
        $soma1 += ($cnpj{$i} * $j);
        }
        $k--;
        $j--;
        }
        
        $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
        $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
        return (($cnpj{12} == $digito1) and ($cnpj{13} == $digito2));
    }
    
	function pa($array){
		echo "<pre><font color='#000000' style='font-size:12px'><b>";
		print_r($array);
		echo "</b></font></pre>";
	}

?>