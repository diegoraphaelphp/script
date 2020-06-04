<?php
	//verifica se tem acesso a AÇÃO...
	$sqlAM = "SELECT DISTINCT(u.USU_IDUsuario) AS USU_IDUsuario, u.USU_Nome, a.APL_Nome FROM tb_usuarios u ";
	$sqlAM.= "INNER JOIN tb_mod_usuarios mu ON (mu.USU_IDUsuario = u.USU_IDUsuario) ";
	$sqlAM.= "INNER JOIN tb_apl_modulo am ON (am.MOD_ID = mu.MOD_ID) ";
	$sqlAM.= "INNER JOIN tb_aplicacoes a ON (a.APL_ID = am.APL_ID) ";
	$sqlAM.= "WHERE a.APL_Acao = '".base64_decode($_GET["acao"])."' AND u.USU_IDUsuario = ".$_SESSION["sIDUSUARIO"];
	$rowAM = $banco->listarArray($sqlAM);
	if (count($rowAM) == "0"){

		goto2("../lib/Fachada.php?acao=".base64_encode("frmAcesso"));
		
	}else{
	
		//lista os MÓDULOS DOS USUARIOS...
		$sqlMU = "SELECT DISTINCT(m.MOD_Nome) AS MOD_Nome, m.MOD_ID FROM tb_modulos m ";
		$sqlMU.= "INNER JOIN tb_apl_modulo am ON (am.MOD_ID = m.MOD_ID) ";
		$sqlMU.= "INNER JOIN tb_mod_usuarios mu ON (mu.MOD_ID = m.MOD_ID) ";
		$sqlMU.= "WHERE mu.USU_IDUsuario = ".$_SESSION["sIDUSUARIO"];
		$rowMU = $banco->listarArray($sqlMU);
		$MOD   = "";
		$menuGRAF = false;
		foreach($rowMU as $mu){

		    $mu["MOD_Nome"] = str_replace("<br>", " ", $mu["MOD_Nome"]);
               
			if ($mu["MOD_Nome"] == "Gerência Regional" || $mu["MOD_Nome"] == "Gerência Estadual" || $mu["MOD_Nome"] == "Administração" || $mu["MOD_Nome"] == "A T E R Planejamento"){
				$menuGRAF = true;
			}
			$MOD.= $mu["MOD_Nome"]." | ";
		}
	
		$tam = strlen($MOD);
		$MOD = substr($MOD, 0, $tam-2);	
	
		//inserindo LOG do Sistema...
		$ip       = $_SERVER["REMOTE_ADDR"];
		$acaoLOG  = base64_decode($_GET["acao"]);
		
		if (empty($_SESSION["sUSU_Codigo"])) $_SESSION["sUSU_Codigo"] = 0;
	
		$sqlLOG = "INSERT INTO tb_log (USU_IDUsuario, LOG_Data, LOG_Hora, LOG_IP, LOG_Acao) ";
		$sqlLOG.= "VALUES (".$_SESSION["sIDUSUARIO"].", '".date("Y-m-d")."', '".date("H:i:s")."', '".$ip."', '".$acao."')";
		$qryLOG = $banco->executarQuery($sqlLOG);
        
        $titulo = $rowAM[0]["APL_Nome"];
	}	
?>