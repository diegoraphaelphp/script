<?php
	date_default_timezone_set("America/Recife");

    require_once("lib/util.php");
	require_once("class/Conexao.php");;	
	
	function removerAcentos($string){
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}

	$banco = Conexao::singleton();

	// Abre o Arquvio no Modo r (para leitura)
	$arquivo = fopen ('tmp/nacionalidades.txt', 'r');
	$intI = 0;

    while(!feof($arquivo)){
		//Mostra uma linha do arquivo
		$linha = fgets($arquivo, 1024);
		
		$explode = explode(" - ", $linha);
		
		$strDescricaoSemAcento = antInjection(strtoupper(removerAcentos($explode[0])));
		$strDescricao          = antInjection($explode[0]);
		$strNaturalidade       = antInjection($explode[1]);
		
		$strSQL   = "SELECT * FROM sis_pai_paises WHERE PAI_Descricao = '".$strDescricaoSemAcento."'";
		$arrDados = $banco->listarArray($strSQL);			
		
		if ($arrDados){
			$intI++;
			$strSQL = "UPDATE sis_pai_paises SET PAI_Descricao = '".$strDescricao."', PAI_Naturalidade = '".$strNaturalidade."' WHERE PAI_ID = ".$arrDados[0]['PAI_ID'];
			#$banco->executarQuery($strSQL);
		}
	}	
	
	

	fclose($arquivo);
	exit('Total:'.$intI);
	
	$strSQL   = "SELECT * FROM sis_pai_paises";
	$arrDados = $banco->listarArray($strSQL);			
	
	pa($arrDados); exit;

	#$qry = $banco->executarQuery($sql);
?>