<?php
	session_start();
	if (!empty($_SESSION["sIDUSUARIO"])){
	
		require_once("../class/UsuarioException.php");
		require_once("../class/TecnicoException.php");	
		require_once("../class/Conexao.php");
	
		$banco = Conexao::singleton();
	
		$diego = "http://".$_SERVER["SERVER_NAME"]."/".$_SERVER["PHP_SELF"];
		$diego = str_replace("lib/Fachada.php", "", $diego);
		
		$sqlV = "SELECT u.*, m.MUN_Descricao, m.REG_ID FROM tb_usuarios u ";
		$sqlV.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = u.MUN_IDMunicipio) ";
		$sqlV.= "WHERE u.USU_IDUsuario = ".$_SESSION["sIDUSUARIO"];// base64_decode($_GET["identification"]);
		$rowV = $banco->listarArray($sqlV);
		
		$_SESSION["sIDUSUARIO"]         = $rowV[0]["USU_IDUsuario"];
		$_SESSION["sNOME_USUARIO"]      = $rowV[0]["USU_Nome"];
		$_SESSION["sLOGIN_USUARIO"]     = $rowV[0]["USU_Login"];
		$_SESSION["sIDMunicipio"]       = $rowV[0]["MUN_IDMunicipio"];
		$_SESSION["sMUN_Descricao"]     = $rowV[0]["MUN_Descricao"];			  
		$_SESSION["sIDRegional"]        = $rowV[0]["REG_ID"];
		$_SESSION["sPAGINACAO_USUARIO"] = $rowV[0]["USU_Paginacao"];

	}else{

	    $_SESSION["sQUERY_STRING"] = $_SERVER["QUERY_STRING"];
		header("location: ../index.php?expirou=0");
		exit;

	}
    
    if (!empty($_GET["empresa"])) $IDCONEXAONFE = base64_decode($_GET["empresa"]); else $IDCONEXAONFE = $_SESSION["sEMP_IDEmpresa"];
	$cor2 = "#00CCFF";	
?>