<?php
	//VERIFICANDO MENSAGEM INICIAL...
	$anoINI = date("Y") + 1;
	$sqlINI = "SELECT PAN_Ano, PAN_MsgInicial, PAN_DataExpira FROM tb_plano_ano WHERE MUN_IDMunicipio = ".$_SESSION["sIDMunicipio"];
	$sqlINI.= " AND PAN_DataExpira >= '".$anoINI."-".date("m-d")."'";
	$rowINI = $banco->listarArray($sqlINI);

	$msgINI = $rowINI[0]["PAN_MsgInicial"];
	$msgINI = str_replace("[ANO]", $rowINI[0]["PAN_Ano"], $msgINI);
	$msgINI = str_replace("[DATA]", $conv->desconverteData($rowINI[0]["PAN_DataExpira"]), $msgINI);
	
	//verifica se tem acesso a TUDO EM RELAวรO AO USUARIOS...
	$sqlACE = "SELECT a.APL_ID, a.APL_Nome, a.APL_Acao FROM tb_aplicacoes a ";
	$sqlACE.= "INNER JOIN tb_apl_modulo am ON (a.APL_ID = am.APL_ID) ";
	$sqlACE.= "INNER JOIN tb_mod_usuarios mu ON (mu.MOD_ID = am.MOD_ID) ";
	$sqlACE.= "WHERE a.APL_Status = 'A' AND mu.USU_IDUsuario = ".$_SESSION["sIDUSUARIO"];
	$sqlACE.= " AND APL_Acao = 'incluirUsuarios' ORDER BY a.APL_Nome";
	$rowACE = $banco->listarArray($sqlACE);
	
	if (!empty($_GET["mod"])){
		$sqlMOD = "SELECT CONCAT('-> ', MOD_Nome) AS MOD_Nome FROM tb_modulos WHERE MOD_ID = ".base64_decode($_GET["mod"]);	
		$rowMOD = $banco->listarArray($sqlMOD);		
	}

	if ( (base64_decode($_GET["mod"]) != "1") && (base64_decode($_GET["mod"]) != "7") && (base64_decode($_GET["mod"]) != "8") ){
		$msgINI = "";
	} 
?>