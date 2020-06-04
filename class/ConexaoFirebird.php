<?php
	/* CONEXÃO FIREBIRD */
	//importando...
	require_once("../lib/verifica.php");	
	require_once("UsuarioException.php");
	require_once("TecnicoException.php");	
	require_once("Conexao.php");
	require_once("../lib/util.php");
	
	//instancias...
	$banco = Conexao::singleton();
	
	$lista = array("desenvolvimento@ipa.br"); 

	$sqlBASE = "SELECT b.*, e.EMP_Descricao FROM tb_bases_empresas b ";
    $sqlBASE.= "INNER JOIN tb_empresas e ON (e.EMP_IDEmpresa = b.EMP_IDEmpresa) ";
    $sqlBASE.= "WHERE e.EMP_Status = 'A' AND e.EMP_IDEmpresa = '".$IDCONEXAONFE."'";
//    echo $sqlBASE;
	$rowBASE = $banco->listarArray($sqlBASE);
	if (count($rowBASE) == 0){

		$texto = "A Base de dados da unidade ".strtoupper($_SESSION["sEMP_Descricao"])." não possui nenhuma base de dados definida.";
	
//		modelo_email($lista, "NUT - Base de dados da Unidade ".strtoupper($_SESSION["sEMP_Descricao"]).")", $texto);

	    echo "
		<table width=\"630px\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align='center'>
			<tr>
				<td align=\"center\" style='background-color:#FF4040;color:#FFFFFF;font-family:Verdana, Arial, sans-serif;font-size:13px;font-weight:bold;'>ATEN&Ccedil;&Atilde;O!</td>
			</tr>
			<tr>
				<td align=\"center\" style='background-color:#FFE0E0; border-color:#406080;border-style:solid;border-width:1px;color:#000000;font-family:Tahoma, Arial, sans-serif;font-size:13px;'><b>".strtoupper($_SESSION["sNOME_USUARIO"])."</b>, esta unidade n&atilde;o tem conex&atilde;o definida no Firebird. Consulte o Administrador pelo (81) - 3184-7308.</td>
			</tr>
		</table><br><br>";
        exit;
	}else{
		//VALIDA A CONEXÃO DE ACORDO COM O HOST DA EMPRESA
		//$res = ibase_pconnect("172.16.220.155:C:\SRI\DADOSVR\SRICASH.FDB", "SYSDBA", "masterkey") or die("<br>".ibase_errmsg());
		@$res = ibase_pconnect($rowBASE[0]["BAS_Host"], "SYSDBA", "masterkey"); // or die("<br>".ibase_errmsg());		
		if ($res == false){
			
			$texto = "O usuário ".strtoupper($_SESSION["sLOGIN_USUARIO"]).", não conseguiu conectar na base de dados da unidade ".strtoupper($_SESSION["sEMP_Descricao"])."<br>Host: ".$rowBASE[0]["BAS_Host"];
		
			//modelo_email($lista, "NUT - Erro em conectar na base de dados (".strtoupper($_SESSION["sEMP_Descricao"]).")", $texto);
            
    	    echo "
    		<table width=\"630px\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align='center'>
    			<tr>
    				<td align=\"center\" style='background-color:#FF4040;color:#FFFFFF;font-family:Verdana, Arial, sans-serif;font-size:13px;font-weight:bold;'>ATEN&Ccedil;&Atilde;O!</td>
    			</tr>
    			<tr>
    				<td align=\"center\" style='background-color:#FFE0E0; border-color:#406080;border-style:solid;border-width:1px;color:#000000;font-family:Tahoma, Arial, sans-serif;font-size:13px;'><b>".strtoupper($_SESSION["sNOME_USUARIO"])."</b>, Ocorreu um erro para conectar na Base de dados da unidade <b>".strtoupper($rowBASE[0]["EMP_Descricao"])."</b>. Foi enviado um e-mail para o NUT - Núcleo de Tecnologia do IPA para solicitar o problema.</td>
    			</tr>
    		</table><br><br>";
			exit;
//			alert(strtoupper($_SESSION["sNOME_USUARIO"]).", Ocorreu um erro para conectar na Base de dados da unidade ".strtoupper($_SESSION["sEMP_Descricao"]).". Foi enviado um e-mail para o NUT - Núcleo de Tecnologia do IPA para solicitar o problema.");
	//		anterior(-1);
		}

	}
?>