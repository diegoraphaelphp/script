<?php
	//importando...
	require_once("../lib/verifica.php");
    require_once("../lib/util.php");
	require_once("../lib/log.php");
	require_once("../class/Conexao.php");
	require_once("../class/Convertedata.php");
    require_once("../class/dompdf/dompdf_config.inc.php");
	require_once("../class/Config.php");
    
	//instancias...
    set_time_limit(0);

	$banco  = Conexao::singleton();
	$conf   = Config::singleton();
    $conv   = Convertedata::singleton();    
    $dompdf = new DOMPDF();
    $dompdf->set_paper("A4", "landscape");    
//    $dompdf->set_paper($dompdf->prefs['pdf_paper_type'], $dompdf->prefs['pdf_type']); 
//	clearBrowserCache();

    ini_set("memory_limit", "256M");
    ini_set("max_execution_time", "1000");

	date_default_timezone_set("America/Recife");
    
    $css = "
        <style type=\"text/css\">
        
            .fontText {
            	font-family:Verdana, Arial, Helvetica, sans-serif;
            	font-size:10px;
            	font-weight:bold;
            	color:#000066;
            	font-style:normal;
            	text-decoration:none;
            }
            
            .fontText2 {
            	font-family:Verdana, Arial, Helvetica, sans-serif;
            	font-size:10px;
            	font-weight:normal;
            	color:#000066;
            	font-style:normal;
            	text-decoration:none;
            }
            
            .titulo_ok4 { 
            	background-color: #78B0F4; 
            	background-repeat: repeat-x; 
            	border-color: #406080; 
            	border-style: solid; 
            	border-width: 0px 0px 0px; 
            	color: #000000; 
            	font-family: Verdana, Arial, sans-serif; 
            	font-size: 11px; 
            	padding: 2px 5px 2px; 
            }
            
            .titulo_ok5 { 
            	background-color: #66CC66; 
            	border-color: #406080; 
            	border-style: solid; 
            	border-width: 0px; 
            	color: #000000; 
            	font-family: Tahoma, Arial, sans-serif; 
            	font-size: 11px; 
            	padding: 2px 5px 2px; 
            }
            
            .titulo_ok6 { 
            	background-color: #BBD9FD; 
            	border-color: #406080; 
            	border-style: solid; 
            	border-width: 0px; 
            	color: #000066; 
            	font-family: Tahoma, Arial, sans-serif; 
            	font-size: 11px; 
            	font-weight:bold;
            	padding: 2px 5px 2px; 
            }
            
            .menuTitle{
            	font-family:Verdana, Arial, Helvetica, sans-serif;
            	font-size:12px;
            	font-style:normal;
            	font-weight:bold;
            	font-variant:normal;
            	color:#000066;
            	text-decoration: none;
            }        
        </style>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
        <title>".$conf->Titulo()."</title>
        <link rel='shortcut icon' href='http://www.ipa.br/ipa.ico' type='image/x-icon' />";	

	$acao = base64_decode($_GET["acao"]);

	if (empty($acao)){
		header("location: ../index.php");
		exit;
	}

	switch($acao){
		
		case "visualizaProjDetail":						
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");			
			
			$proj = antInjection(base64_decode($_GET["proj"]));

			//PEGA ANO DO PARAMETRO DO MUNICIPIO...
		    $sqlPAR = "SELECT PAN_Ano FROM tb_plano_ano WHERE MUN_IDMunicipio = ".$_SESSION["sIDMunicipio"]." ORDER BY PAN_Ano DESC";
		    $rowPAR = $banco->listarArray($sqlPAR);

			$sql = "SELECT p.PRJ_IDProjeto, p.PRJ_Descricao, l.PLA_Ano, a.ATV_IDAtividade, l.MUN_IDMunicipio, m.MUN_Descricao, l.USU_IDUsuario FROM tb_projetos p ";			
			$sql.= "INNER JOIN tb_atividades a ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
			$sql.= "INNER JOIN tb_planoanual l ON (l.ATV_IDAtividade = a.ATV_IDAtividade) ";
			$sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = l.MUN_IDMunicipio) ";
			$sql.= "WHERE p.PRJ_Status = 'A' AND a.PRJ_IDProjeto = ".$proj." AND l.PLA_Ano = '".$rowPAR[0]["PAN_Ano"]."' ";	
			$sql.= "GROUP BY l.MUN_IDMunicipio, p.PRJ_IDProjeto ORDER BY p.PRJ_IDProjeto";
			//exit($sql);
			$row = $banco->listarArray($sql);	
			
		  	if (count($row) == 0){
			  echo "	  
				<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"25%\" align=\"center\">
		
				  <tr>
					<td colspan=\"8\" class=\"titulo_ok\">Informa&ccedil;&atilde;o</td>
				  </tr>
				  <tr>	
					<td colspan=\"8\" class=\"titulo_ok2\">Não há dados para visualização.</td>
				  </tr>
				</table>";  
		    }else{			
			
			$html = "";
			$html.= "
			<script language=\"javascript\" type=\"text/javascript\" src=\"../js/functions.js\"></script>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/styles.css\">
		  	<table width='880px' border='0' cellpadding='1' cellspacing=\"1\" align=\"center\" bgcolor='#FFFFFF'>
              <tr>
                <td>&nbsp;</td>
              </tr>	  
		      <tr>
		       <td colspan=\"7\" align=\"left\" class=\"menuTitle\" style='padding: 0px 0px 0px 7px;'>SISPLAN - VISUALIZAÇÃO DE PROJETO DETALHADA</td>
		      </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
			  <tr>
			    <td class='fontText' align='center'>&nbsp;</td>
				<td class='fontText' align='left'>ID. Projeto</td>
				<td class='fontText' align='left'>Projeto</td>
				<td class='fontText' align='left' colspan='5'>&nbsp;</td>
			  </tr>";
		
			$i 		      = 0;
			$totGERAL_QTD = 0;
			$totGERAL_FAM = 0;
			foreach($row as $l){
		
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
		
				  $html.= "
			  	  <tr>
					<td align='left' colspan='7' class='fontError' style='padding: 0px 0px 0px 7px;'>".$l["MUN_Descricao"]."</td>
				  </tr>		  
			  	  <tr id='cel$i' style='cursor:hand;background-color:".$cor.";'>
					<td align='center'>&nbsp;</td>
					<td align='left' width='18%' class='fontText'>".completarComZero($l["PRJ_IDProjeto"])."</td>
					<td align='left' class='fontText' width='70%'>".$l["PRJ_Descricao"]."</td>
					<td class='fontText' align='left' colspan='4'>&nbsp;</td>
				  </tr>
				  <tr>
					<td align='center'>&nbsp;</td>
					<td align='left' class='fontText'>CÓDIGO</td>
					<td align='left' width='65%' class='fontText'>DESCRIÇÃO</td>
					<td align='left' class='fontText' width='5%'>UND.</td>
					<td align='center' class='titulo_ok4' width='5%'>QTD.</td>
					<td align='center' class='titulo_ok5' width='5%'>FAMÍLIAS</td>
					<td align='center' class='fontText' width='5%'>ANO</td>
				  </tr>";
				  
				  $sql2 = "SELECT p.PRJ_IDProjeto, l.PLA_IDAnual, a.ATV_IDAtividade, u.UND_Descricao, a.ATV_Descricao, SUM(l.ATV_Prevfam) AS ATV_Prevfam, SUM(l.ATV_Prevqtd) AS ATV_Prevqtd, l.PLA_Ano FROM tb_atividades a ";
				  $sql2.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
				  $sql2.= "INNER JOIN tb_unidades u ON (u.UND_IDUnidade = a.UND_IDUnidade) ";
				  $sql2.= "INNER JOIN tb_planoanual l ON (l.ATV_IDAtividade = a.ATV_IDAtividade) WHERE a.PRJ_IDProjeto = ".$l["PRJ_IDProjeto"];
				  $sql2.= " AND l.PLA_Ano = '".$l["PLA_Ano"]."' AND l.MUN_IDMunicipio = ".$l["MUN_IDMunicipio"];
				  $sql2.= " GROUP BY a.ATV_IDAtividade";
				  //exit($sql2);
				  $row2 = $banco->listarArray($sql2);
				  $a      = 0;
				  $totFAM = 0;
				  $totQTD = 0;
				  foreach($row2 as $l2){
			
					  if($a % 2 == 0) $corI = "#EEE"; else $corI = "#FFF";			  
					  if (empty($l2["ATV_Prevqtd"])) $l2["ATV_Prevqtd"] = 0;
					  if (empty($l2["ATV_Prevfam"])) $l2["ATV_Prevfam"] = 0;
					  
					  $totFAM+= $l2["ATV_Prevfam"];
					  $totQTD+= $l2["ATV_Prevqtd"];
					  $totGERAL_QTD+= $l2["ATV_Prevqtd"];
					  $totGERAL_FAM+= $l2["ATV_Prevfam"];
			
					  $html.= "
				  	  <tr id='cel$a' style='cursor:hand;background-color:".$corI.";' onmouseout=\"mouseOut('$corI', this, '$a');\" onmouseover=\"mouseOver('$cor2', this);\">
						<td align='center'>
						  <input type='hidden' name='cod[]' id='cod$a' value=".base64_encode($l2["ATV_IDAtividade"]).">
						</td>
						<td align='left' class='fontText2'>".completarComZero($l2["ATV_IDAtividade"])."</td>
						<td align='left' class='fontText2'>".$l2["ATV_Descricao"]."</td>
						<td align='left' class='fontText2'>".$l2["UND_Descricao"]."</td>
						<td align='right' class='fontText2'>".organiza_moeda($l2["ATV_Prevqtd"])."</td>
						<td align='right' class='fontText2'>".$l2["ATV_Prevfam"]."</td>
						<td align='center' class='fontText2'>".$l2["PLA_Ano"]."</td>
					  </tr>";
					  $a++;
				  }
				  $html.= "
					<tr>
					  <td align='right' colspan='4' class='fontText2'>&nbsp;</td>
					  <td align='right' class='titulo_ok4'>".organiza_moeda($totQTD)."</b></td>
					  <td align='right' class='titulo_ok5'>".$totFAM."</b></td>
					  <td align='right' class='fontText2'>&nbsp;</td>
					</tr>";
		
				  $i++;
				$html.= "
					<tr>
					  <td align='right' colspan='7' class='fontText2'><b>Total de Atividade(s) por Projeto : ".count($row2)."</b></td>
					</tr>";		  
			}
		
			$html.= "
				<tr>				  
				  <td colspan='4' align='left' class='fontText2' width='300px'><b>Total de Projeto(s) : ".count($row)."</b></td>
				  <td align='right' class='fontText2'><b>".organiza_moeda($totGERAL_QTD)."</b></td>
				  <td align='right' class='fontText2'><b>".$totGERAL_FAM."</b></td>
				  <td>&nbsp;</td>				  
				</tr>";
		}
		  $html.= "
				<tr>
					<td colspan='7' align='left' class='titulo_ok3'>
						<span>Nota 1: O dado lançado será acumulado ao atual.</span><br>
						<span>Nota 2: Para alterar a informação (os dados). Ir na aba em relatório, excluir e lançar novamente.</span>
					</td>
				</tr>
		  </table>";
		  
		  echo $html;			
			
		break;		
		
		case "gerarXLSLOG":
        
			$nome  = antInjection($_POST["nome"]);
            $dtini = antInjection($_POST["dtini"]);
            $dtfim = antInjection($_POST["dtfim"]);
            $usu   = antInjection($_POST["usu"]);
            $gere  = antInjection($_POST["gere"]);

        	$sql = "SELECT l.LOG_ID, l.USU_IDUsuario, l.LOG_Data, l.LOG_Hora, l.LOG_IP, a.APL_Nome, u.USU_Login FROM tb_log l ";
        	$sql.= "INNER JOIN tb_usuarios u ON (l.USU_IDUsuario = u.USU_IDUsuario) ";
            $sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = u.MUN_IDMunicipio) "; 
            $sql.= "INNER JOIN tb_aplicacoes a ON (a.APL_Acao = l.LOG_Acao) WHERE l.LOG_ID IS NOT NULL ";
            
            
			if ( (!empty($dtini)) && (!empty($dtfim)) ){
				$sql.= " AND l.LOG_Data BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtfim)."' ";	
			}
			if (!empty($usu)) $sql.= " AND l.USU_IDUsuario = ".$usu;
            if (!empty($gere)) $sql.= " AND m.REG_ID = '".$gere."'";
			$sql.= " ORDER BY l.LOG_Data, l.LOG_Hora";
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
			
			$html = $css."
	  		<table width='400px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
			     <td align=\"center\" colspan='5'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td> 
			     <td align=\"left\" colspan='5' class='menuTitle'><b>SISPLAN - LISTAGEM DE LOG DO SISTEMA</b></td>
			   </tr>   
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
				 <td align='left' class='fontText'><b>ID.</b></td>
				 <td align='left' class='fontText'><b>Usuário</b></td>
				 <td align='left' class='fontText'><b>Data/Hora</b></td>
				 <td align='left' class='fontText'><b>IP</b></td>
				 <td align='left' class='fontText'><b>Ação</b></td>	
			   </tr>";
			
			   $i = 0;
			   foreach($row as $l){

                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                    
				  $html.= "
				  	<tr>
                      <td style='background-color:".$cor.";' class='fontText2' align='left'></td>   
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".completarComZero($l["LOG_ID"], 7)."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["USU_Login"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$conv->desconverteData($l["LOG_Data"])."/".substr($l["LOG_Hora"], 0, 5)."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["LOG_IP"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["APL_Nome"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSUnidades":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_unidades WHERE UND_IDUnidade IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND UND_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND UND_Status = '".$status."' ";
			$sql.= " ORDER BY UND_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
			
			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan='3' class='menuTitle'>SISPLAN - LISTAGEM DE UNIDADES</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='200px' class='fontText'>Unidade</td>
				 <td align='left' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
				  if ($l["UND_Status"] == "A") $l["UND_Status"] = "Ativo"; else $l["UND_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["UND_IDUnidade"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["UND_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["UND_Status"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
				
		break;
			
		case "gerarXLSUsuarios":
			
			$nome     = antInjection($_POST["nome"]);
			$gere     = antInjection($_POST["gere"]);
            $muni     = antInjection($_POST["muni"]);
            $status   = antInjection($_POST["status"]);
            
			$sql = "SELECT u.*, m.MUN_Descricao, e.EMP_Descricao FROM tb_usuarios u ";
			$sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = u.MUN_IDMunicipio) ";
			$sql.= "INNER JOIN tb_empresas e ON (e.EMP_IDEmpresa = u.EMP_IDEmpresa) ";
            $sql.= "LEFT OUTER JOIN tb_mod_usuarios o ON (o.USU_IDUsuario = u.USU_IDUsuario) WHERE u.USU_IDUsuario IS NOT NULL ";

			if (!empty($nome)){
			  $sql.= " AND (u.USU_Nome LIKE '%".$a."%' OR u.USU_Login LIKE '%".$a."%'  ";
    		  $sql.= " OR u.USU_Email LIKE '%".$a."%' OR u.USU_Telefone LIKE '%".$a."%') ";
			}
            
			if (!empty($status)) $sql.= " and u.USU_Status = '".$status."'";
            if (!empty($muni)){
                $sql.= " AND m.MUN_IDMunicipio = '".$muni."' ";
            }else{
                if (!empty($gere)) $sql.= " AND m.REG_ID = '".$gere."' ";    
            }
            
            //filtrando os MODULOS...
        	$mods = explode("@", $_POST["listamod"]);
        	$in   = "";
        	for($d=0;$d<count($mods);$d++){
        		$in.= $mods[$d].",";
        	}
        
        	$tam = 0;
        	$tam = strlen($in) - 2;
        	$in  = substr($in, 0, $tam);
            //
            if (!empty($in)) $sql.= " AND o.MOD_ID IN (".$in.") ";
            $sql.= " GROUP BY o.USU_IDUsuario";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='6'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" class='menuTitle' colspan='6'><b> SISPLAN - LISTAGEM DE USUÁRIOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Nome</td>
				 <td align='left' width='80px' class='fontText'>Login</td>
				 <td align='left' width='150px' class='fontText'>Município</td>
				 <td align='left' width='80px' class='fontText'>Telefone</td>
				 <td align='left' width='50px' class='fontText'>Status</td>				 
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";					
				  if ($l["USU_Status"] == "A") $l["USU_Status"] = "Ativo"; else $l["USU_Status"] = "Inativo"; 	

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["USU_IDUsuario"])."</td>
					  <td style='background-color:".$cor.";' align='left' width='300px' class='fontText2'>".$l["USU_Nome"]."</td>
					  <td style='background-color:".$cor.";' align='left' width='80px' class='fontText2'>".$l["USU_Login"]."</td>
					  <td style='background-color:".$cor.";' align='left' width='150px' class='fontText2'>".$l["MUN_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' width='80px' class='fontText2'>".$l["USU_Telefone"]."</td>
					  <td style='background-color:".$cor.";' align='left' width='50px' class='fontText2'>  ".$l["USU_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
                
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSRegional":
		
        	$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * FROM tb_regional WHERE REG_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND REG_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND REG_Status = '".$status."' ";			
			$sql.= " ORDER BY REG_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
			     <td align=\"left\" colspan='3' class='menuTitle'>SISPLAN - LISTAGEM DE GERENCIA REGIONAL</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Gerencia Regional</td>
				 <td align='left' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  if ($l["REG_Status"] == "A") $l["REG_Status"] = "Ativo"; else $l["REG_Status"] = "Inativo";
	
				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["REG_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["REG_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["REG_Status"]."</td>
					</tr>";			  
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSDesenv":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_regiaodesen WHERE RDE_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND RDE_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND RDE_Status = '".$status."' ";
			$sql.= " ORDER BY RDE_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE REGIÃO DE DESENVOLVIMENTO</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Região Desenvolvimento</td>
				 <td align='left' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["RDE_Status"] == "A") $l["RDE_Status"] = "Ativo"; else $l["RDE_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["RDE_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["RDE_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["RDE_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;		
		
		case "gerarXLSMeso":
			
            $nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * FROM tb_mesoregioes WHERE MES_IDMeso IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND MES_Descricao LIKE '%".$nome."%'";
			if (!empty($status)) $sql.= " AND MES_Status = '".$status."' ";
			$sql.= " ORDER BY MES_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE MESSOREGIÃO</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</b></td>
				 <td align='left' width='300px' class='fontText'><b>Messoregião</b></td>
				 <td align='left' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["MES_Status"] == "A") $l["MES_Status"] = "Ativo"; else $l["MES_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["MES_IDMeso"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MES_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MES_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;		

		case "gerarXLSMicro":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * FROM tb_microregioes WHERE MIC_IDMicro IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND MIC_Descricao LIKE '%".$nome."%'";
			if (!empty($status)) $sql.= " AND MIC_Status = '".$status."'";
			$sql.= " ORDER BY MIC_Descricao";
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan='3' class='menuTitle'>SISPLAN - LISTAGEM DE MICRORREGIÃO</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Microrregião</td>
				 <td align='left' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["MIC_Status"] == "A") $l["MIC_Status"] = "Ativo"; else $l["MIC_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["MIC_IDMicro"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MIC_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MIC_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSProjetosAtv":
        
            //pa($_POST); exit;			
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT p.* FROM tb_projetos p ";
	              $sql.= "INNER JOIN tb_atividades a ON (a.PRJ_IDProjeto = p.PRJ_IDProjeto) ";
       	       $sql.= "WHERE p.PRJ_IDProjeto >= 39 ";	
			if (!empty($nome)) $sql.= " AND p.PRJ_IDProjeto = '".$nome."' ";
			if (!empty($status)) $sql.= "AND p.PRJ_Status = '".$status."'";
			$sql.= " GROUP BY p.PRJ_IDProjeto ORDER BY p.PRJ_Descricao";
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='6'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='6' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE PROJETOS E ATIVIDADES</td>
			   </tr>   
			   <tr>
				 <td class='fontText' align='left' width='100px'>ID.</td>
				 <td class='fontText' align='left' width='350px'>Descrição</td>
				 <td class='fontText' align='center'>Meta Prioritária</td>
			 	 <td class='fontText' align='left'>Status</td>
				 <td class='fontText' align='left'>&nbsp;</td>
				 <td class='fontText' align='left'>&nbsp;</td>
			   </tr>";
		
			$i = 0;
			foreach ($row as $l){

				  if ($l["PRJ_Opcao"] == "S") $l["PRJ_Opcao"] = "SIM"; else $l["PRJ_Opcao"] = "NÃO";
				  if ($l["PRJ_Status"] == "A") $l["PRJ_Status"] = "Ativo"; else $l["PRJ_Status"] = "Inativo";

				  $i++;

				  $html.= "
				  	<tr>
				  	  <td align='left' class='fontText'>".completarComZero($l["PRJ_IDProjeto"])."</td>
					  <td align='left' class='fontText'>".$l["PRJ_Descricao"]."</td>
					  <td align='center' class='fontText'>".$l["PRJ_Opcao"]."</td>
					  <td align='left' class='fontText'>".$l["PRJ_Status"]."</td>
					  <td align='center'>&nbsp;</td>
					</tr>";
				  
					  $html.= "
					  <td colspan='6'>
					  <table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>  			
					  <tr>  	
						<td align=\"left\" width='20px' class='fontText'>ID.</td>
						<td align=\"left\" width='300px' class='fontText'>Atividade</td>
						<td align=\"center\" width='150px' class='fontText'>Quantidade</td>
						<td align=\"center\" width='150px' class='fontText'>Famílias</td>
					  </tr>";
					  
					  //LISTA AS ATIVIDADES DO PROJETO...
					  $sqlS = "SELECT a.ATV_IDAtividade, u.UND_Descricao, a.ATV_Descricao, a.ATV_Tipoficha, a.ATV_Status FROM tb_atividades a ";
					  $sqlS.= "INNER JOIN tb_unidades u ON (u.UND_IDUnidade = a.UND_IDUnidade) ";
					  $sqlS.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
					  $sqlS.= "WHERE p.PRJ_IDProjeto = ".$l["PRJ_IDProjeto"]." GROUP BY a.ATV_IDAtividade ORDER BY a.ATV_Descricao ";
					  $rowS = $banco->listarArray($sqlS);
					  $a = 0;
					  foreach ($rowS as $linhaS){
					   
                        if($a % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
						
						if ($linhaS["ATV_Tipoficha"] == "P")
						  $linhaS["ATV_Tipoficha"] = "Planejamento";
						elseif ($linhaS["ATV_Tipoficha"] == "A")
						  $linhaS["ATV_Tipoficha"] = "Acompanhamento";
						else
						  $linhaS["ATV_Tipoficha"] = "Ambos";

						$html.= "
						<tr>
							<td style='background-color:".$cor.";' align=\"left\" class='fontText2'>".completarComZero($linhaS["ATV_IDAtividade"])."</td>
							<td style='background-color:".$cor.";' align=\"left\" class='fontText2'>".$linhaS["ATV_Descricao"]."</td>
							<td style='background-color:".$cor.";' align=\"center\" class='fontText2'>____________________</td>
							<td style='background-color:".$cor.";' align=\"center\" class='fontText2'>____________________</td>
						  </tr>";
						$a++;
					  }
					  
					  $html.= "
					  	<tr>
						  <td colspan='6' align='center'>&nbsp;</td>
						</tr>
					  </table>";
			}
            exit($html);

            $dompdf->load_html($html);
            $dompdf->render();
            $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSProjetos":
        
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);			

			$sql = "SELECT * FROM tb_projetos WHERE PRJ_IDProjeto IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND PRJ_Descricao LIKE '%".$nome."%'";
			if (!empty($status)) $sql.= " AND PRJ_Status = '".$status."'";			
			$sql.= " ORDER BY PRJ_Descricao";
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='right'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
			     <td colspan='4' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE PROJETOS</td>
			   </tr>   
			   <tr>
				 <td align='left' width='100px' class='fontText'>ID.</td>
				 <td align='left' width='350px' class='fontText'>Descrição</td>
				 <td align='left' class='fontText'>Meta Prioritária</td>
				 <td align='left' class='fontText'>Status</td>
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
				  if ($l["PRJ_Opcao"] == "S") $l["PRJ_Opcao"] = "SIM"; else $l["PRJ_Opcao"] = "NÃO";
				  if ($l["PRJ_Status"] == "A") $l["PRJ_Status"] = "Ativo"; else $l["PRJ_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";  

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["PRJ_IDProjeto"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRJ_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRJ_Opcao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRJ_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='4' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSElaboracao":

			$proj = antInjection($_POST["proj"]);
			$ano  = antInjection($_POST["ano"]);
			$mun  = antInjection($_POST["mun"]);
			$tipo = antInjection($_POST["tipo"]);
            $flag = antInjection($_POST["flag"]);

			$html = "";
            
            //pa($_POST); exit;
            
			$sql = "SELECT p.PRJ_IDProjeto, p.PRJ_Descricao, l.PLA_Ano, a.ATV_IDAtividade ";
			if ($tipo == "A") $sql.= ", l.MUN_IDMunicipio, m.MUN_Descricao ";
			$sql.= " FROM tb_projetos p ";			
			$sql.= "INNER JOIN tb_atividades a ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
			$sql.= "INNER JOIN tb_planoanual l ON (l.ATV_IDAtividade = a.ATV_IDAtividade) ";
			$sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = l.MUN_IDMunicipio) WHERE p.PRJ_Status = 'A' ";	
			if (!empty($mun)) $sql.= "AND l.MUN_IDMunicipio = ".$mun;
			if (!empty($proj)) $sql.= " and a.PRJ_IDProjeto = ".$proj;
			if (!empty($ano)) $sql.= " and l.PLA_Ano = '".$ano."'";
            if ($flag == "GR" && (!empty($tipo))){
                $sql.= " AND m.RDE_ID = '".$tipo."' ";
            }else{
                if (!empty($tipo)) $sql.= " AND m.REG_ID = '".$tipo."' ";
            }
            $sql.= " GROUP BY l.PLA_Ano, p.PRJ_IDProjeto ";
            if (($flag == "GR") && (!empty($tipo)) ){
                $sql.= " ,m.RDE_ID "; 
            }else{
                if (!empty($tipo)) $sql.= " ,m.REG_ID ";   
            }

            if (!empty($_GET["mun"])) $sql.= " ,m.MUN_IDMunicipio";
            $sql.= " ORDER BY a.ATV_Tipoficha";
			$row = $banco->listarArray($sql);
		    if (count($row) == 0){
		   	  
		  	  echo "
			  	<script>
			  		alert('Não há dados para visualização.');
			  		window.close();
			  	</script>";
		
		    }else{		      
                /* MONTANDO OS DADOS DO FILTRO */
                $filtro.= "<b>DADOS DO FILTRO:</b><br>";
                $filtro.= "<b>Ano Competência:</b> ".$ano."<br>";
                if (!empty($mun)){
    
                    $sqlF = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                    $rowF = $banco->listarArray($sqlF);
    
                    $filtro.= "<b>Município:</b> ".$rowF[0]["MUN_Descricao"]."<br>";
    
                }else{
                    if (!empty($tipo)){
                        if ($flag == "RD"){
                        
                            $sqlF = "SELECT RDE_Descricao FROM tb_regiaodesen WHERE RDE_ID = '".$tipo."'";
                            $rowF = $banco->listarArray($sqlF);
                            
                            $filtro.= "<b>Região de Desenvolvimento:</b> ".$rowF[0]["RDE_Descricao"]."<br>";    
                        }else{
                            
                            $sqlF = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$tipo."'";                    
                            $rowF = $banco->listarArray($sqlF);
                            
                            $filtro.= "<b>Gerência Regional:</b> ".$rowF[0]["REG_Descricao"]."<br>";
                        }
                    }
                }
    
                if (!empty($proj)) {
                    
                    $sqlF = "SELECT PRJ_Descricao FROM tb_projetos WHERE PRJ_IDProjeto = '".$proj."'";
                    $rowF = $banco->listarArray($sqlF);
                    
                    $filtro.= "<b>Projeto:</b> ".$rowF[0]["PRJ_Descricao"]."<br>";    
                }
                /**/
    
		
				$html.= "<html><body>".$css."
			  	<table width=\"550px\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">
    			    <tr>
                        <td align='center' colspan='6'>
                            <img src='../img/logo_cliente.jpg' border='0' width='750px'>
                        </td>
    			    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
				   <tr>
                     <td>&nbsp;</td>
				     <td colspan='5' align=\"left\" class='menuTitle'><b> SISPLAN - LISTAGEM DE PLANO ANUAL (ELABORAÇÃO)</b></td>
				   </tr>";
                   
                    if ($flag == "RD"){
                        $str = "<b>Região de Desenvolvimento:</b> ";
                    
                        if (!empty($tipo)){
                            $sql33 = "SELECT RDE_Descricao FROM tb_regiaodesen WHERE RDE_ID = '".$tipo."'";
                            $row33 = $banco->listarArray($sql33);

                            $str.= " : ".strtoupper($row33[0]["RDE_Descricao"]);

                            if (!empty($mun)){
                                $sql34 = "SELECT MUN_Descricao, MUN_NumeroAgricultor FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                                $row34 = $banco->listarArray($sql34);
                                
                                if (empty($row34[0]["MUN_NumeroAgricultor"])) $row34[0]["MUN_NumeroAgricultor"] = 0;                        
                                $str.= " (".strtoupper($row34[0]["MUN_Descricao"])." - TOTAL DE AGRICULTORES - ".$row34[0]["MUN_NumeroAgricultor"].")";
                            }else{
//                                $str.= " (TOTAL DE AGRICULTORES - ".$quant.")";
                            }

                        }else{
                            $str.= " : TODOS";
                        }
                    }else{

                        $str = "<b>Gerência Regional:</b>";
                    
                        if (!empty($tipo)){
                    
                            $sql33 = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$tipo."'";
                            $row33 = $banco->listarArray($sql33);
                    
                            $str.= " : ".strtoupper($row33[0]["REG_Descricao"]);
                    
                            if (!empty($mun)){
                    
                                $sql34 = "SELECT MUN_Descricao, MUN_NumeroAgricultor FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                                $row34 = $banco->listarArray($sql34);
                    
                                if (empty($row34[0]["MUN_NumeroAgricultor"])) $row34[0]["MUN_NumeroAgricultor"] = 0;                        
                                $str.= " (".strtoupper($row34[0]["MUN_Descricao"])." - TOTAL DE AGRICULTORES - ".$row34[0]["MUN_NumeroAgricultor"].")";
                            }else{
//                                $str.= " (TOTAL DE AGRICULTORES - ".$quant.")";
                            }
                        }else{
                            $str.= " : TODOS";                    
                        }
                    }

                   $html.= "
                   <tr>
                     <td>&nbsp;</td>
                     <td class=\"fontText2\" colspan=\"5\" align=\"left\">".$filtro."</td>
                   </tr>
                    <tr>
                        <td class='fontText2'></td>
                        <td align='left' colspan='5' class='fontText2'>".$str."</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>                   
				   <tr>
					 <td align='left' width='100px' class='fontText'><b>  ID.</b></td>
					 <td align='left' width='500px' class='fontText'><b>Projeto</b></td>
                     <td colspan='4' align='left' class='fontText'>&nbsp;</td>
				   </tr>";   

					$i = 0;
					foreach($row as $l){
				
						  $html.= "
					  	  <tr>
							<td align='left' class='fontText'>".$l["MUN_Descricao"]."</td>
						  </tr>		  
					  	  <tr>
							<td align='left' class='fontText'>".completarComZero($l["PRJ_IDProjeto"])."</td>
							<td align='left' class='fontText'>".$l["PRJ_Descricao"]."</td>
						  </tr>
						  <tr>
							<td align='left' class='fontText'>CÓDIGO</td>
							<td align='left' width='350px' class='fontText'>DESCRIÇÃO</td>
							<td align='left' width='80px' class='fontText'>UND</td>
							<td align='right' width='80px' class='fontText'>QTD.</td>
							<td align='right' width='80px' class='fontText'>FAMÍLIAS</td>
							<td align='center' width='80px' class='fontText'>ANO</td>
						  </tr>";

						  $sql2 = "SELECT l.PLA_IDAnual, a.ATV_IDAtividade, u.UND_Descricao, a.ATV_Descricao, SUM(l.ATV_Prevfam) as ATV_Prevfam, ";
						  $sql2.= "SUM(l.ATV_Prevqtd) AS ATV_Prevqtd, l.PLA_Ano FROM tb_atividades a ";
						  $sql2.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
						  $sql2.= "INNER JOIN tb_unidades u ON (u.UND_IDUnidade = a.UND_IDUnidade) ";
						  $sql2.= "INNER JOIN tb_planoanual l ON (l.ATV_IDAtividade = a.ATV_IDAtividade) ";
                          $sql2.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = l.MUN_IDMunicipio) WHERE a.PRJ_IDProjeto = ".$l["PRJ_IDProjeto"];
						  if (!empty($mun)) $sql2.= " AND l.MUN_IDMunicipio = ".$mun;
						  //if (!empty($proj)) $sql2.= " AND a.PRJ_IDProjeto = ".$proj;
						  if (!empty($ano)) $sql2.= " AND l.PLA_Ano = '".$ano."'";
                          
                          if ($flag == "GR" && (!empty($tipo))){
                            $sql2.= " AND m.RDE_ID = '".$tipo."' ";
                          }else{
                            if (!empty($tipo)) $sql2.= " AND m.REG_ID = '".$tipo."' ";
                          }	
                          
                          $sql2.= " GROUP BY l.PLA_Ano, p.PRJ_IDProjeto ";
                          if ( ($flag == "RD") && (!empty($tipo)) ){
                            $sql2.= " ,m.RDE_ID ";
                          }else{
                            if (!empty($tipo)) $sql2.= " ,m.REG_ID ";
                          }
                          if (!empty($mun)) $sql2.= " ,m.MUN_IDMunicipio ";
                          
						  $sql2.= " ,l.ATV_IDAtividade ORDER BY a.ATV_Tipoficha";
                          //exit($sql2);
						  $row2 = $banco->listarArray($sql2);
						  $a      = 0;
						  foreach($row2 as $l2){
						      
                              if($a % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";  

							  if (empty($l2["ATV_Prevqtd"])) $l2["ATV_Prevqtd"] = 0;
							  if (empty($l2["ATV_Prevfam"])) $l2["ATV_Prevfam"] = 0;
					
							  $html.= "
						  	  <tr>
								<td style=\"background-color:".$cor.";\" align='left' class='fontText2'>".completarComZero($l2["ATV_IDAtividade"])."</td>
								<td style=\"background-color:".$cor.";\" align='left' class='fontText2'>".$l2["ATV_Descricao"]."</td>
								<td style=\"background-color:".$cor.";\" align='left' class='fontText2'>".$l2["UND_Descricao"]."</td>
								<td style=\"background-color:".$cor.";\" align='right' class='fontText2'>".organiza_moeda($l2["ATV_Prevqtd"])."</td>
								<td style=\"background-color:".$cor.";\" align='right' class='fontText2'>".$l2["ATV_Prevfam"]."</td>
								<td style=\"background-color:".$cor.";\" align='center' class='fontText2'>".$l2["PLA_Ano"]."</td>								
							  </tr>";
							  $a++;
						  }
						  $i++;
						$html.= "
							<tr>
							  <td colspan='6' align='right' class='fontText'>Total de Atividade(s) por Projeto : ".count($row2)."</td>
							</tr>";
					}
					$html.= "
						<tr>     	
						  <td align='right' colspan='6' class='fontText'>Total de Projeto(s) : ".count($row)."</td>
						</tr>";
				}

				$html.= "</table></body></html>";
                
                exit($html);
                
                header("Content-type: application/vnd.ms-excel; name='excel'");
    			header("Content-Disposition: filename=rel_elaboracao_".date("d-m-Y_H_i_s").".xls");
    			header("Pragma: no-cache");
    			header("Expires: 0");
                
				exit($html);

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSEquipes":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT e.*, a.ACO_Descricao, s.PES_Nome FROM tb_equipes e ";
			$sql.= "INNER JOIN tb_acoes a ON (a.ACO_ID = e.ACO_ID) ";
			$sql.= "INNER JOIN tb_pessoas s ON (s.PES_ID = e.PES_ID) WHERE e.PES_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND (a.ACO_Descricao LIKE '%".$nome."%' OR s.PES_Nome LIKE '%".$nome."%') ";
			if (!empty($status)) $sql.= " AND e.EQP_Status = '".$status."' ";			
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td colspan='4' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE EQUIPES (PESQUISA)</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>Ação</td>
				 <td align='left' class='fontText'>Pessoa</td>
				 <td align='left' class='fontText'>Tipo</td>
				 <td align='left' class='fontText'>Status</td>				 	
			   </tr>";
	
			   $i = 0;
			   foreach($row as $l){

				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  if ($l["EQP_Status"] == "A") $l["EQP_Status"] = "Ativo"; else $l["EQP_Status"] = "Inativo";
				  if ($l["EQP_Tipo"] == "R") 
				  	$l["EQP_Tipo"] = "Responsável";
				  elseif ($l["EQP_Tipo"] == "E") 	
				  	$l["EQP_Tipo"] = "Executor";
				  elseif ($l["EQP_Tipo"] == "A") 	
				  	$l["EQP_Tipo"] = "Ambos";					
	
				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["ACO_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Nome"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["EQP_Tipo"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["EQP_Status"]."</td>
					</tr>";			  
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='4' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;

		case "gerarXLSLinhap":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * FROM tb_linhas_pesquisa WHERE LIN_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND LIN_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND LIN_Status = '".$status."' ";
			$sql.= " ORDER BY LIN_Descricao";
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\"><b> SISPLAN - LISTAGEM DE LINHA DE PESQUISA</b></td>
			   </tr>   
			   <tr>
				 <td align='left' width='100px' class='fontText'>ID.</td>
				 <td align='left' width='350px' class='fontText'>Descrição</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";			
	
			   $i = 0;
			   foreach($row as $l){

				  if ($l["LIN_Status"] == "A") $l["LIN_Status"] = "Ativo"; else $l["LIN_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
	
				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left'>".completarComZero($l["LIN_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left'>".$l["LIN_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='center'>".$l["LIN_Status"]."</td>
					</tr>";			  
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSPPE":

			$nome    = antInjection($_POST["nome"]);
			$status  = antInjection($_POST["status"]);			
			$dtini   = antInjection($_POST["dtini"]);
			$dtini2  = antInjection($_POST["dtini2"]);
			$dtprev  = antInjection($_POST["dtprev"]);
			$dtprev2 = antInjection($_POST["dtprev2"]);
			$dtfim   = antInjection($_POST["dtfim"]);
			$dtfim2  = antInjection($_POST["dtfim2"]);
			$prog    = antInjection($_POST["prog"]);
			
			$sql = "SELECT q.*, p.PES_Nome, g.PRG_Descricao, l.LIN_Descricao, f.DESCRICAO FROM tb_projetos_pesquisas q ";
			$sql.= "INNER JOIN tb_programas g ON (g.PRG_IDPrograma = q.PRG_IDPrograma) ";
			$sql.= "INNER JOIN tb_pessoas p ON (p.PES_ID = q.PES_ID) ";
			$sql.= "INNER JOIN tb_fontes_recursos f ON (f.IDFONTERECURSOS = q.PPE_IDFontePrin) ";
			$sql.= "INNER JOIN tb_linhas_pesquisa l ON (l.LIN_ID = q.LIN_ID) WHERE q.PPE_ID IS NOT NULL ";
			//montando filtro...	
			if (!empty($nome)) $sql.= " AND q.PPE_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND q.PPE_Status = '".$status."' ";
			if ( (!empty($dtini)) && (!empty($dtini2)) ){
				$sql.= " AND q.PPE_DataInicio BETWEEN '".$conv->conData($dtini)."' AND  '".$conv->conData($dtini2)."' ";
			}
			if ( (!empty($dtprev)) && (!empty($dtprev2)) ){
				$sql.= " AND q.PPE_DataPrevFim BETWEEN '".$conv->conData($dtprev)."' AND  '".$conv->conData($dtprev2)."' ";
			}
				if ( (!empty($dtfim)) && (!empty($dtfim2)) ){
				$sql.= " AND q.PPE_DataFinal BETWEEN '".$conv->conData($dtfim)."' AND  '".$conv->conData($dtfim2)."' ";
			}
			if (!empty($prog)) $sql.= " AND q.PRG_IDPrograma = ".$prog;
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='7'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='7' align=\"left\" class='fontText'>SISPLAN - LISTAGEM DE PROJETOS PESQUISA</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='250px' class='fontText'>Título</td>
				 <td align='center' width='75px' class='fontText'>Data Inicial</b></td>
				 <td align='center' width='75px' class='fontText'>Data Previsão</td>
				 <td align='center' width='75px' class='fontText'>Data Final</td>
				 <td align='left' width='100px' class='fontText'>Programa</td>
				 <td align='left' width='50px' class='fontText'>Status</td>	
			   </tr>";			
	
			   $i = 0;
			   foreach($row as $l){
			     
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  if ($l["PPE_Status"] == "N")
					$l["PPE_Status"] = "Não Iniciado";
				  elseif ($l["PPE_Status"] == "E")
					$l["PPE_Status"] = "Em Andamento";
				  elseif ($l["PPE_Status"] == "C")
					$l["PPE_Status"] = "Concluído";
				  elseif ($l["PPE_Status"] == "L")
					$l["PPE_Status"] = "Cancelado";
				  elseif ($l["PPE_Status"] == "P")
					$l["PPE_Status"] = "Perdido";				  
	
				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["PPE_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PPE_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["PPE_DataInicio"])."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["PPE_DataPrevFim"])."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["PPE_DataFinal"])."</td>					  
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRG_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PPE_Status"]."</td>
					</tr>";			  
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='7' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSAcoes":
			
			$nome    = antInjection($_POST["nome"]);
			$status  = antInjection($_POST["status"]);			
			$dtini   = antInjection($_POST["dtini"]);
			$dtini2  = antInjection($_POST["dtini2"]);
			$dtprev  = antInjection($_POST["dtprev"]);
			$dtprev2 = antInjection($_POST["dtprev2"]);
			$dtfim   = antInjection($_POST["dtfim"]);
			$dtfim2  = antInjection($_POST["dtfim2"]);
			$proj    = antInjection($_POST["proj"]);
			
			$sql = "SELECT a.*, p.PPE_Descricao, s.PES_Nome FROM tb_acoes a ";
			$sql.= "INNER JOIN tb_pessoas s ON (s.PES_ID = a.PES_ID) ";
			$sql.= "INNER JOIN tb_projetos_pesquisas p ON (p.PPE_ID = a.PPE_ID) WHERE a.ACO_ID IS NOT NULL ";
			//montando filtro...	
			if (!empty($nome)) $sql.= " AND a.ACO_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND a.ACO_Status = '".$status."' ";
			if ( (!empty($dtini)) && (!empty($dtini2)) ){
				$sql.= " AND a.ACO_DataInicio BETWEEN '".$conv->conData($dtini)."' AND  '".$conv->conData($dtini2)."' ";
			}
			if ( (!empty($dtprev)) && (!empty($dtprev2)) ){
				$sql.= " AND a.ACO_DataPrevFinal BETWEEN '".$conv->conData($dtprev)."' AND  '".$conv->conData($dtprev2)."' ";
			}
				if ( (!empty($dtfim)) && (!empty($dtfim2)) ){
				$sql.= " AND a.ACO_DataFinal BETWEEN '".$conv->conData($dtfim)."' AND  '".$conv->conData($dtfim2)."' ";
			}
			if (!empty($proj)) $sql.= " AND a.PPE_ID = ".$proj;
			$row = $banco->listarArray($sql);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			
			
			$html = $css."
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='7' class='fontText'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='7' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE AÇÕES PESQUISA</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='200px' class='fontText'>Descrição</td>
				 <td align='left' width='200px' class='fontText'>Projeto Pesquisa</td>				 
				 <td align='center' width='75px' class='fontText'>Data Inicial</td>
				 <td align='center' width='75px' class='fontText'>Data Previsão</td>
				 <td align='center' width='75px' class='fontText'>Data Final</td>				 
				 <td align='left' width='50px' class='fontText'>Status</td>	
			   </tr>";			
	
			   $i = 0;
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  if ($l["ACO_Status"] == "A") $l["ACO_Status"] = "Ativo"; else $l["ACO_Status"] = "Inativo";
	
				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["ACO_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["ACO_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PPE_Descricao"]."</td>					  
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["ACO_DataInicio"])."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["ACO_DataPrevFinal"])."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["ACO_DataFinal"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["ACO_Status"]."</td>
					</tr>";			  
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='7' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
		break;
		
		case "gerarXLSSAG":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_segmentos_agro WHERE SAG_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND SAG_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND SAG_Status = '".$status."' ";
			$sql.= " ORDER BY SAG_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\" class='fontText'><b>SISPLAN - LISTAGEM DE SEGMENTOS AGROPECUÁRIOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Descrição</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["SAG_Status"] == "A") $l["SAG_Status"] = "Ativo"; else $l["SAG_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["SAG_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["SAG_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["SAG_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSEmpresa":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT e.EMP_IDEmpresa, e.EMP_Descricao, e.EMP_Status, r.REG_Descricao FROM tb_empresas e ";
			$sql.= "INNER JOIN tb_regional r ON (e.REG_ID = r.REG_ID) WHERE e.EMP_IDEmpresa IS NOT NULL ";		
			if (!empty($nome)) $sql.= " AND e.EMP_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND e.EMP_Status = '".$status."' ";
			$sql.= " ORDER BY e.EMP_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='4' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE EMPRESAS</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Descrição</td>
				 <td align='left' width='200px' class='fontText'>Regional</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  if ($l["EMP_Status"] == "A") $l["EMP_Status"] = "Ativo"; else $l["EMP_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["EMP_IDEmpresa"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["EMP_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["REG_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["EMP_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='2' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSMunicipios":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
            $gere   = antInjection($_POST["gere"]);
            $dese   = antInjection($_POST["dese"]);
			
			$sql = "SELECT m.MUN_IDMunicipio, m.MUN_Descricao, m.MUN_Codigo, m.MUN_Status, ";
			$sql.= "d.RDE_Descricao, r.REG_Descricao FROM tb_municipios m ";
			$sql.= "INNER JOIN tb_regional r ON (r.REG_ID = m.REG_ID) ";
			$sql.= "INNER JOIN tb_regiaodesen d ON (d.RDE_ID = m.RDE_ID) WHERE m.MUN_IDMunicipio IS NOT NULL ";	
			if (!empty($nome)) $sql.= " AND m.MUN_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND m.MUN_Status = '".$status."' ";
            if (!empty($gere)) $sql.= " AND m.REG_ID = '".$gere."' ";
            if (!empty($dese)) $sql.= " AND m.RDE_ID = '".$dese."' ";    
			$sql.= " ORDER BY m.MUN_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='6'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='6' align=\"left\" class='menuTitle'><b> SISPLAN - LISTAGEM DE MUNICÍPIOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'><b>ID.</b></td>
				 <td align='left' width='50px' class='fontText'><b>Código</b></td>
				 <td align='left' width='200px' class='fontText'><b>Descrição</b></td>
				 <td align='left' width='160px' class='fontText'><b>Regional</b></td>
				 <td align='left' width='150px' class='fontText'><b>Região Desenvolvimento</b></td>
				 <td align='left' class='fontText'><b>Status</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){
			     
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";                  
				  if ($l["MUN_Status"] == "A") $l["MUN_Status"] = "Ativo"; else $l["MUN_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["MUN_IDMunicipio"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MUN_Codigo"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MUN_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["REG_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["RDE_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MUN_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSProgramas":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT p.*, e.PES_Nome FROM tb_programas p ";
			$sql.= "INNER JOIN tb_pessoas e ON (e.PES_ID = p.PES_ID) ";
			$sql.= "WHERE p.PRG_IDPrograma IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND p.PRG_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND p.PRG_Status = '".$status."' ";
			$sql.= " ORDER BY p.PRG_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='5'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='5' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE PROGRAMAS</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='150px' class='fontText'>Título</td>
				 <td align='left' width='150px'>Pessoa</td>
				 <td align='left' width='200px' class='fontText'>Objetivo</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["PRG_Status"] == "A") $l["PRG_Status"] = "Ativo"; else $l["PRG_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["PRG_IDPrograma"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRG_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Nome"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRG_Objetivo"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PRG_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSInstituicoes":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * from tb_instituicoes WHERE INS_ID IS NOT NULL ";	
			if (!empty($nome)) $sql.= " AND INS_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND INS_Status = '".$status."' ";
			$sql.= " ORDER BY INS_Status";			
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\" class='menuTitle'>SISPLAN - LISTAGEM DE INSTITUIÇÕES</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='200px' class='fontText'>Descrição</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["INS_Status"] == "A") $l["INS_Status"] = "Ativo"; else $l["INS_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["INS_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["INS_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["INS_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSPessoas":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT p.PES_ID, p.PES_Nome, p.PES_Profissao, p.PES_Fone, p.PES_Celular, p.PES_Email, p.PES_Status, i.INS_Descricao, p.PES_Tipo FROM tb_pessoas p ";
			$sql.= "INNER JOIN tb_instituicoes i ON (i.INS_ID = p.INS_ID) WHERE p.PES_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND p.PES_Nome LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND p.PES_Status = '".$status."' ";
			$sql.= " ORDER BY p.PES_Nome";			
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='8'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='8' align=\"left\"><b> SISPLAN - LISTAGEM DE PESSOAS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='150px' class='fontText'>Nome</td>
				 <td align='left' width='100px' class='fontText'>Profissão</td>
				 <td align='left' width='70px' class='fontText'>Telefone</td>
				 <td align='left' width='70px' class='fontText'>Celular</td>
				 <td align='left' width='100px' class='fontText'>E-mail</td>				 
				 <td align='left' width='100px' class='fontText'>Instituição</td>
				 <td align='center'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["PES_Status"] == "A") $l["PES_Status"] = "Ativo"; else $l["PES_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["PES_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Nome"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Profissao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Fone"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Celular"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["PES_Email"]."</td>					  
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["INS_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["PES_Status"]."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='6' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSGrupos":

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * FROM tb_grupos WHERE GRP_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND GRP_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND GRP_Status = '".$status."' ";
			$sql.= " ORDER BY GRP_Descricao";			
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\" class='fontText'>SISPLAN - GRUPOS</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Nome</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["GRP_Status"] == "A") $l["GRP_Status"] = "Ativo"; else $l["GRP_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["GRP_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["GRP_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["GRP_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSDespesas":

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "SELECT * FROM tb_despesas WHERE DES_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND DES_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND DES_Status = '".$status."' ";
			$sql.= " ORDER BY DES_Descricao";			
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\" class='menuTitle'>SISPLAN - DESPESAS</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Nome</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["DES_Status"] == "A") $l["DES_Status"] = "Ativo"; else $l["DES_Status"] = "Inativo";
				  if ($l["DES_Flag"] == "D") $l["DES_Flag"] = "Despesa"; else $l["DES_Flag"] = "Receita";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";				  

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["DES_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["DES_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["DES_Status"]."</td>
					</tr>";
					$i++;

				}
                
				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSContas":
			
			$muni   = antInjection($_POST["muni"]);			
			$cont   = antInjection($_POST["cont"]);
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$venc   = antInjection($_POST["venc"]);
			$desp   = antInjection($_POST["desp"]);
			$grp    = antInjection($_POST["grp"]);

			$sql = "SELECT p.CON_ID,m.MUN_Descricao,d.DES_Descricao,g.GRP_Descricao,p.CON_IDContrato,p.CON_NomeCli,p.CON_Vencimento,p.CON_Status FROM tb_contas p ";
			$sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = p.MUN_IDMunicipio) ";
			$sql.= "INNER JOIN tb_grupos g ON (g.GRP_ID = p.GRP_ID) ";
			$sql.= "INNER JOIN tb_despesas d ON (d.DES_ID = p.DES_ID) WHERE p.CON_ID IS NOT NULL ";
		
			//MONTANDO FILTRO...
			if (!empty($muni)) $sql.= " AND m.MUN_IDMunicipio = ".$muni;
			if (!empty($cont)) $sql.= " AND p.CON_IDContrato LIKE '%".$cont."%' ";
			if (!empty($nome)) $sql.= " AND p.CON_NomeCli LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND p.CON_Status = '".$status."' ";
			if (!empty($venc)) $sql.= " AND p.CON_Vencimento = '".$venc."' ";
			if (!empty($desp)) $sql.= " AND p.DES_ID = '".$desp."' ";
			if (!empty($grp)) $sql.= " AND p.GRP_ID = '".$grp."' ";
			$sql.= " ORDER BY p.CON_NomeCli";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização!');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='8'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='8' align=\"left\" class='menuTitle'>SISPLAN - PLANO DE CONTAS</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='100px' class='fontText'>Município</td>
				 <td align='left' width='120px' class='fontText'>Grupo</td>
				 <td align='left' width='120px' class='fontText'>Despesa</td>
				 <td align='left' width='150px' class='fontText'>Nome do Cliente</td>
				 <td align='left' width='70px' class='fontText'>ID. Contrato</td>
				 <td align='left' width='70px' class='fontText'>Vencimento</td>				 
				 <td align='center'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["CON_Status"] == "A") $l["CON_Status"] = "Ativo"; else $l["CON_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["CON_ID"])."</td>					  
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MUN_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["GRP_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["DES_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_NomeCli"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_IDContrato"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_Vencimento"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='8' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSLocal":			
			
			$cont   = antInjection($_POST["cont"]);
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$venc   = antInjection($_POST["venc"]);
			$desp   = antInjection($_POST["desp"]);
			$grp    = antInjection($_POST["grp"]);

			$sql = "SELECT l.LOC_ID, l.LOC_Nome, l.MUN_IDMunicipio, l.LOC_Tipo, l.LOC_Status, m.MUN_Descricao FROM tb_local l ";
			$sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = l.MUN_IDMunicipio) WHERE l.LOC_ID IS NOT NULL "; 
		
			//MONTANDO FILTRO...
			if (!empty($cont)) $sql.= " AND p.CON_IDContrato LIKE '%".$cont."%' ";
			if (!empty($nome)) $sql.= " AND p.CON_NomeCli LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND p.CON_Status = '".$status."' ";
			if (!empty($venc)) $sql.= " AND SUBSTRING(p.CON_Vencimento, 9,2) = '".$venc."' ";
			if (!empty($desp)) $sql.= " AND p.DES_ID = '".$desp."' ";
			if (!empty($grp)) $sql.= " AND p.GRP_ID = '".$grp."' ";
			$sql.= " ORDER BY p.CON_NomeCli";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='7'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='7' align=\"left\" class='menuTitle'>SISPLAN - PLANO DE CONTAS (MUNICÍPIO - ".strtoupper($_SESSION["sMUN_Descricao"]).")</td>
			   </tr>   
			   <tr>
				 <td align='left' width='50px' class='fontText'>ID.</td>
				 <td align='left' width='120px' class='fontText'>Grupo</td>		
				 <td align='left' width='120px' class='fontText'>Despesa</td>
				 <td align='left' width='200px' class='fontText'>Cliente</td>
				 <td align='left' width='70px' class='fontText'>ID. Contrato</td>
				 <td align='left' width='70px' class='fontText'>Vencimento</td>				 
				 <td align='center'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["CON_Status"] == "A") $l["CON_Status"] = "Ativo"; else $l["CON_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["CON_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["GRP_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["DES_Descricao"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_NomeCli"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_IDContrato"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CON_Vencimento"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["CON_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='7' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
				$pdf->WriteHTML($html);
				$pdf->Output();
		break;
		
		case "gerarXLSModulos":
		
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_modulos WHERE MOD_ID IS NOT NULL ";
			//MONTANDO FILTRO...
			if (!empty($nome)) $sql.= " AND MOD_Nome LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND MOD_Status = '".$status."' ";
			$sql.= " ORDER BY MOD_Nome";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td colspan='3' align=\"left\" class='fontText'>SISPLAN - MÓDULOS</td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='180px' class='fontText'>Módulo</td>
				 <td align='center' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["MOD_Status"] == "A") $l["MOD_Status"] = "Ativo"; else $l["MOD_Status"] = "Inativo";
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  
				  $html.= "
				  	<tr>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["MOD_ID"])."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MOD_Nome"]."</td>
					  <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["MOD_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSApl":

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_aplicacoes WHERE APL_ID IS NOT NULL ";
			//MONTANDO FILTRO...
			if (!empty($nome)) $sql.= " AND (APL_Nome LIKE '%".$nome."%' OR APL_Acao LIKE '%".$nome."%') ";
			if (!empty($status)) $sql.= " AND APL_Status = '".$status."' ";
			$sql.= " ORDER BY APL_Nome";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td align=\"left\" colspan='4' class='fontText'>SISPLAN - APLICAÇÕES</td>			     
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'>ID.</td>
				 <td align='left' width='300px' class='fontText'>Aplicação</td>
				 <td align='left' width='180px' class='fontText'>Tipo</td>
				 <td align='left' class='fontText'>Status</td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  if ($l["APL_Status"] == "A") $l["APL_Status"] = "Ativo"; else $l["APL_Status"] = "Inativo";
                  
                  if ($l["APL_Tipo"] == "C") $l["APL_Tipo"] = "Controladores";
                  elseif ($l["APL_Tipo"] == "F") $l["APL_Tipo"] = "Formulário";
                  elseif ($l["APL_Tipo"] == "L") $l["APL_Tipo"] = "Link";
                  elseif ($l["APL_Tipo"] == "M") $l["APL_Tipo"] = "Movimentos";
                  elseif ($l["APL_Tipo"] == "R") $l["APL_Tipo"] = "Relatórios";

				  $html.= "
				  	<tr>
					  <td align='left' class='fontText2'>".completarComZero($l["APL_ID"])."</td>
					  <td align='left'>".$l["APL_Nome"]."</td>
					  <td align='left'>".$l["APL_Tipo"]."</td>
					  <td align='left'>".$l["APL_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='4' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
				$dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
		break;
		
		case "gerarXLSAplMod":

			$nome = antInjection($_POST["nome"]);			
			
			$sql = "SELECT m.MOD_Nome, a.APL_Nome, am.MOD_ID, am.APL_ID FROM tb_apl_modulo am ";
			$sql.= "INNER JOIN tb_aplicacoes a ON (a.APL_ID = am.APL_ID) ";
			$sql.= "INNER JOIN tb_modulos m ON (m.MOD_ID = am.MOD_ID) WHERE a.APL_Status = 'A' AND m.MOD_Status = 'A' ";
			//MONTANDO FILTRO...
			if (!empty($nome)){
				$sql.= " AND (m.MOD_Nome LIKE '%".$nome."%' OR a.APL_Nome LIKE '%".$nome."%' OR a.APL_Acao LIKE '%".$nome."%') ";
			}
			$sql.= " ORDER BY m.MOD_Nome";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0' width='480px' height='90px'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan='2'><b> SISPLAN - APLICAÇÕES DOS MÓDULOS</b></td>			     
			   </tr>   
			   <tr>
				 <td align='left' width='200px'><b>Módulo</b></td>
				 <td align='left' width='200px'><b>Aplicação</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";				  
				  
                  
                   $str = explode("<br>", $l["MOD_Nome"]);
                   if (count($str) == 2) $str = $str[0]." ".$str[1]; else $str = $str[0];
                   
                   $html.= "
				  	<tr>
					  <td align='left'>".$str."</td>
					  <td align='left'>".$l["APL_Nome"]."</td>					  
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='2' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
				$dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSModUsu":
		
        	$nome   = antInjection($_POST["nome"]);			
			
			$sql = "SELECT m.MOD_Nome, u.USU_Nome, mu.USU_IDUsuario, mu.MOD_ID FROM tb_mod_usuarios mu ";
			$sql.= "INNER JOIN tb_usuarios u ON (u.USU_IDUsuario = mu.USU_IDUsuario) ";
			$sql.= "INNER JOIN tb_modulos m ON (m.MOD_ID = mu.MOD_ID) ";
			$sql.= "WHERE m.MOD_Status = 'A' AND u.USU_Status = 'A' ";
			//MONTANDO FILTRO...			
			if (!empty($nome)){
				$sql.= " AND (m.MOD_Nome LIKE '%".$nome."%' OR u.USU_Nome LIKE '%".$nome."%' OR u.USU_Login LIKE '%".$nome."%') ";
			}
			$sql.= " ORDER BY m.MOD_Nome";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0' width='480px' height='90px'>
			     </td>
			   </tr>
			   <tr>
			     <td colspan='2' align=\"left\"><b> SISPLAN - MÓDULOS DOS USUÁRIOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' width='200px'><b>   Módulo</b></td>
				 <td align='left' width='200px'><b>   Usuário</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){				  
				  
				  $html.= "
				  	<tr>
					  <td align='left' width='200px'>".$l["MOD_Nome"]."</td>
					  <td align='left' width='200px'>".$l["USU_Nome"]."</td>					  
					</tr>";
					$i++;

				}
				$html.= "
					<tr>
					  <td colspan='2' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
                
		break;
		
		case "gerarXLSMovContas":			
			
			$con_id = antInjection($_POST["con_id"]);			
			$dtini  = antInjection($_POST["dtini"]);
			$dtini2 = antInjection($_POST["dtini2"]);
			$dtfim  = antInjection($_POST["dtfim"]);
			$dtfim2 = antInjection($_POST["dtfim2"]);			
			$status = antInjection($_POST["status"]);

			$sql = "SELECT m.MOV_ID, c.CON_IDContrato, m.MOV_Vencimento, m.MOV_Pagamento, m.MOV_ValorPrincipal, m.MOV_ValorJuros, m.MOV_ValorMulta, ";
			$sql.= "m.MOV_Status FROM tb_mov_contas m INNER JOIN tb_contas c ON (c.CON_ID = m.CON_ID) WHERE m.CON_ID IS NOT NULL ";
			//MONTANDO FILTRO...
			if (!empty($con_id)) $sql.= " AND c.CON_IDContrato LIKE '%".$con_id["con_id"]."%' ";
			if (!empty($status)) $sql.= " AND m.MOV_Status = '".$status."' ";
			if ( (!empty($dtini)) && (!empty($dtini2)) ){
				$sql.= " AND a.ACO_DataInicio BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtini2)."' ";
			}
			if ( (!empty($dtfim)) && (!empty($dtfim2)) ){
				$sql.= " AND a.ACO_DataFinal BETWEEN '".$conv->conData($dtfim)."' AND '".$conv->conData($dtfim2)."' ";
			}
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não dados para visualiza??o');
						window.close();
					</script>";
				exit;	
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='8'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='8' align=\"left\"><b> SISPLAN - MOVIMENTO DE CONTAS</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='100px'><b>Identificador</b></td>		
				 <td align='left' width='90px'><b>Data Vencimento</b></td>
				 <td align='left' width='90px'><b>Data Pagamento</b></td>
				 <td align='left' width='90px'><b>Valor Principal</b></td>
				 <td align='left' width='90px'><b>Valor Multa</b></td>
				 <td align='left' width='90px'><b>Valor Juros</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["MOV_Status"] == "A") $l["MOV_Status"] = "Ativo"; else $l["MOV_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["MOV_ID"])."</td>
					  <td align='left'>".$l["CON_IDContrato"]."</td>
					  <td align='left'>".$conv->desconverteData($l["MOV_Vencimento"])."</td>
					  <td align='left'>".$conv->desconverteData($l["MOV_Pagamento"])."</td>
					  <td align='left'>".organiza_moeda($l["MOV_ValorPrincipal"])."</td>
					  <td align='left'>".organiza_moeda($l["MOV_ValorJuros"])."</td>
					  <td align='left'>".organiza_moeda($l["MOV_ValorMulta"])."</td>					  
					  <td align='left'>".$l["MOV_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='8' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSConvenios":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT c.CON_ID, c.CON_Descricao, c.CON_Obs, f.DESCRICAO, c.CON_Status FROM tb_fontes_recursos f ";
			$sql.= "INNER JOIN tb_convenios c ON (c.IDFONTERECURSOS = f.IDFONTERECURSOS) ";
			$sql.= "WHERE f.STATUS = 'A' ";
			//montando filtro...	
			if (!empty($nome)) $sql.= " AND c.CON_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND c.CON_Status = '".$status."' ";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não dados para visualização');
						window.close();
					</script>";
				exit;
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='4' align=\"left\"><b> SISPLAN - CONVÊNIOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='150px'><b>Descrição</b></td>
				 <td align='left' width='400px'><b>Fonte de Recurso</b></td>
				 <td align='left' width='150px'><b>Status</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["CON_Status"] == "A") $l["CON_Status"] = "Ativo"; else $l["CON_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["CON_ID"])."</td>
					  <td align='left'>".$l["CON_Descricao"]."</td>
					  <td align='left'>".$l["DESCRICAO"]."</td>
					  <td align='left'>".$l["CON_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='4' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
				$pdf->WriteHTML($html);
				$pdf->Output();
		break;
		
		case "gerarXLSContasSOL":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_contas_solicitacoes WHERE CON_ID IS NOT NULL ";
			//montando filtro...	
			if (!empty($nome)) $sql.= " AND (CON_Conta LIKE '%".$nome."%' OR CON_Descricao LIKE '%".$nome."%') ";
			if (!empty($status)) $sql.= " AND CON_Status = '".$status."' ";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não dados para visualização!');
						window.close();
					</script>";
				exit;
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0' width='480px' height='90px'>
			     </td>
			   </tr>
			   <tr>
			     <td colspan='4' align=\"left\"><b>SISPLAN - CONTAS SOLICITAÇÕES</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='150px'><b>Conta</b></td>
				 <td align='left' width='250px'><b>Descrição</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["CON_Status"] == "A") $l["CON_Status"] = "Ativo"; else $l["CON_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["CON_ID"])."</td>					  
					  <td align='left'>".$l["CON_Conta"]."</td>					  
					  <td align='left'>".$l["CON_Descricao"]."</td>
					  <td align='left'>".$l["CON_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='4' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSCompras":			
			
			$nome        = antInjection($_POST["nome"]);
			$status      = antInjection($_POST["status"]);			
			$aca         = antInjection($_POST["aca"]);
			$fonte       = antInjection($_POST["fonte"]);
			$solicitante = antInjection($_POST["solicitante"]);
			$und         = antInjection($_POST["und"]);			

			$sql = "SELECT s.SOL_ID, s.DOC_ID, u.USU_Nome, s.SOL_Data, s.SOL_Hora, d.UND_Descricao, a.ACA_Codigo, a.ACA_Descricao, s.SOL_Numero, ";
			$sql.= "f.CODIGO, f.DESCRICAO, s.SOL_Status, s.SOL_DispFinanceira FROM tb_solicitacoes s ";
			$sql.= "INNER JOIN tb_usuarios u ON (u.USU_IDUsuario = s.SOL_IDSolicitante) ";
			$sql.= "INNER JOIN tb_unidades_solicitacoes d ON (d.UND_ID = s.SOL_UndSolicitante) ";
			$sql.= "INNER JOIN tb_fontes_recursos f ON (f.IDFONTERECURSOS = s.IDFONTERECURSOS) ";
			$sql.= "INNER JOIN tb_acao a ON (a.ACA_ID = s.ACA_ID) ";
			$sql.= "WHERE s.SOL_Tipo = '1' ";
		
			//montando filtro...
			if (!empty($_GET["nome"])) $sql.= " AND s.SOL_Numero LIKE '%".$_GET["nome"]."%' ";
			if (!empty($_GET["status"])) $sql.= " AND s.SOL_Status = '".$_GET["status"]."' ";	
			if (!empty($_GET["aca"])) $sql.= " AND s.ACA_ID = '".$_GET["aca"]."' ";
			if (!empty($_GET["fonte"])) $sql.= " AND s.IDFONTERECURSOS = '".$_GET["fonte"]."' ";
			if (!empty($_GET["solicitante"])) $sql.= " AND s.SOL_IDSolicitante = '".$_GET["solicitante"]."' ";
			if (!empty($_GET["und"])) $sql.= " AND s.SOL_UndSolicitante = '".$_GET["und"]."' ";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não dados para visualização!');
						window.close();
					</script>";
				exit;
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='4' align=\"left\"><b> SISPLAN - CONTAS SOLICITAÇÕES</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='150px'><b>Conta</b></td>
				 <td align='left' width='250px'><b>Descrição</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["CON_Status"] == "A") $l["CON_Status"] = "Ativo"; else $l["CON_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["CON_ID"])."</td>					  
					  <td align='left'>".$l["CON_Conta"]."</td>					  
					  <td align='left'>".$l["CON_Descricao"]."</td>
					  <td align='left'>".$l["CON_Status"]."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='4' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSProgPessoa":			
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT p.PRG_IDPrograma, e.PES_ID, p.PRG_Descricao, e.PES_Nome, pp.PPE_Flag FROM tb_programas p ";
			$sql.= "INNER JOIN tb_pessoas e ON (e.PES_ID = p.PES_ID) ";
			$sql.= "INNER JOIN tb_programa_pessoa pp ON (pp.PES_ID = p.PES_ID AND p.PRG_IDPrograma = pp.PRG_IDPrograma) ";
			$sql.= "WHERE p.PRG_IDPrograma IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND (e.PES_Nome LIKE '%".$nome."%' OR p.PRG_Descricao LIKE '%".$nome."%') ";
			if (!empty($status)) $sql.= " AND pp.PPE_Flag = '".$status."' ";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não dados para visualização!');
						window.close();
					</script>";
				exit;
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\"><b> SISPLAN - PROGRAMA DAS PESSOAS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' width='200px'><b>  Programa</b></td>
				 <td align='left' width='200px'><b>Pessoa</b></td>
				 <td align='left'><b> Tipo</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["PPE_Flag"] == "E") $l["PPE_Flag"] = "Equipe"; else $l["PPE_Flag"] = "Comissão Técnica";

				  $html.= "
				  	<tr>
					  <td align='left'>".$l["PRG_Descricao"]."</td>					  
					  <td align='left'>".$l["PES_Nome"]."</td>
					  <td align='left'>".$l["PPE_Flag"]."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='3' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSMenu":			
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_menu WHERE MEN_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND MEN_Nome LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND MEN_Status = '".$status."' ";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não dados para visualização!');
						window.close();
					</script>";
				exit;
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\"><b> SISPLAN - MENUS</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID</b></td>
				 <td align='left' width='200px'><b>Nome</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  if ($l["MEN_Status"] == "A") $l["MEN_Status"] = "Ativo"; else $l["MEN_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["MEN_ID"])."</td>
					  <td align='left'>".$l["MEN_Nome"]."</td>
					  <td align='left'>".$l["MEN_Status"]."</td>
					</tr>";
					$i++;
				}
				$html.= "
					<tr>
					  <td colspan='3' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
		break;
		
		case "gerarXLSMenuMod":

			$nome   = antInjection($_POST["nome"]);			
			
			$sql = "SELECT m.MOD_Nome, a.MEN_Nome, am.MEN_ID, am.MOD_ID FROM tb_menu_modulo am ";
			$sql.= "INNER JOIN tb_menu a ON (a.MEN_ID = am.MEN_ID) ";
			$sql.= "INNER JOIN tb_modulos m ON (m.MOD_ID = am.MOD_ID) WHERE a.MEN_Status = 'A' AND m.MOD_Status = 'A' ";
			//MONTANDO FILTRO...
			if (!empty($nome)){
				$sql.= " AND (m.MOD_Nome LIKE '%".$nome."%' OR a.MEN_Nome LIKE '%".$nome."%') ";
			}
			$sql.= " ORDER BY a.MEN_Nome";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}			

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0' width='480px' height='90px'>
			     </td>
			   </tr>  
			   <tr>
			     <td align=\"left\"><b>SISPLAN - MENU DOS MÓDULOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>Menu</b></td>
				 <td align='left' width='200px'><b>Módulo</b></td>	
			   </tr>";

			   $i = 0;			   
			   foreach($row as $l){

				  $html.= "
				  	<tr>
					  <td align='left'>".$l["MEN_Nome"]."</td>
					  <td align='left'>".$l["MOD_Nome"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='2' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
                
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
				

		break;
		
		case "gerarXLSMeto":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_metodologia WHERE MET_IDMetodologia IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND MET_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND MET_Status = '".$status."' ";
			$sql.= " ORDER BY MET_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0' width='480px' height='90px'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\"><b> SISPLAN - LISTAGEM DE METODOLOGIAS</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID. </b></td>
				 <td align='left' width='300px'><b>Nome</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["MET_Status"] == "A") $l["MET_Status"] = "Ativo"; else $l["MET_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["MET_IDMetodologia"])."</td>
					  <td align='left'>".$l["MET_Descricao"]."</td>
					  <td align='left'>".$l["MET_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;

		case "gerarXLSOrientacoes":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_orientacoes WHERE ORI_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND ORI_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND ORI_Status = '".$status."' ";
			$sql.= " ORDER BY ORI_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align=\"left\"><b>SISPLAN - LISTAGEM DE ORIENTAÇÕES</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='300px'><b>Nome</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["ORI_Status"] == "A") $l["ORI_Status"] = "Ativo"; else $l["ORI_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["ORI_ID"])."</td>
					  <td align='left'>".$l["ORI_Descricao"]."</td>
					  <td align='left'>".$l["ORI_Status"]."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='3' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;		
		
		case "gerarXLSFinanciamentos":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_fontes_financiamentos WHERE FIN_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND FIN_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND FIN_Status = '".$status."' ";
			$sql.= " ORDER BY FIN_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = $css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='3' align='left'><b> SISPLAN - LISTAGEM DE FONTES DE FINANCIAMENTOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left' class='fontText'><b>ID.</b></td>
				 <td align='left' class='fontText' width='300px'><b>Descrição</b></td>
				 <td align='left' class='fontText'><b>Status</b></td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["FIN_Status"] == "A") $l["FIN_Status"] = "Ativo"; else $l["FIN_Status"] = "Inativo";

				  $html.= "
				  	<tr>
					  <td align='left' class='fontText2'>".completarComZero($l["FIN_ID"])."</td>
					  <td align='left' class='fontText2'>".$l["FIN_Descricao"]."</td>
					  <td align='left' class='fontText2'>".$l["FIN_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;		
		
		case "gerarXLSOriProj":
			
			$nome = antInjection($_POST["nome"]);

        	$sql = "SELECT op.ORI_ID, op.ATV_ID, o.ORI_Descricao, p.ATV_Descricao FROM tb_orientacoes_x_atividades op ";
            $sql.= "INNER JOIN tb_orientacoes o ON (o.ORI_ID = op.ORI_ID) ";
            $sql.= "INNER JOIN tb_atividades p ON (p.ATV_IDAtividade = op.ATV_ID) ";
            $sql.= "WHERE o.ORI_Status = 'A' AND p.ATV_Status = 'A' ";
        	if (!empty($nome)){
        		$sql.= " AND (o.ORI_Descricao LIKE '%".$nome."%' OR p.ATV_Descricao LIKE '%".$nome."%') ";
        	}
			$sql.= " ORDER BY o.ORI_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = $css."	
	  		<table border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td colspan='2' align='center' class='fontText'><b>SISPLAN - LISTAGEM DE ORIENTAÇÕES X ATIVIDADES</b></td>
			   </tr>   
			   <tr>
                 <td align='left' width='300px' class='fontText'><b>Orientação</b></td>
				 <td align='left' width='400px' class='fontText'><b>Atividade</b></td>				 	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  $html.= "
				  	<tr>					  
					  <td align='left' class='fontText2'>".$l["ORI_Descricao"]."</td>
                      <td align='left' class='fontText2'>".$l["ATV_Descricao"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='2' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
                
                
                header("Content-type: application/vnd.ms-excel; name='excel'");
    			header("Content-Disposition: filename=rel_elaboracao_".date("d-m-Y_H_i_s").".xls");
    			header("Pragma: no-cache");
    			header("Expires: 0");
                
				exit($html);

                
                /*
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
*/
		break;		

		case "gerarXLSProcessos":
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT p.*, u.UND_Descricao FROM tb_processos p ";
			$sql.= "INNER JOIN tb_unidades_solicitacoes u ON (u.UND_ID = p.UND_ID) WHERE p.PRO_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND p.PRO_Descricao LIKE '%".$nome."%' ";
			if (!empty($status)) $sql.= " AND p.PRO_Status = '".$status."' ";
			$sql.= " ORDER BY PRO_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='6'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan=='6' align=\"left\"><b> SISPLAN - LISTAGEM DE PROCESSOS</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='150px'><b>Descrição</b></td>
				 <td align='left' width='150px'><b>Tipo Aquisição</b></td>				 
				 <td align='left' width='250px'><b>Unidade</b></td>				 
				 <td align='left' width='150px'><b>Prazo</b></td>
				 <td align='left'><b>Status</b></td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["PRO_Status"] == "A") $l["PRO_Status"] = "Ativo"; else $l["PRO_Status"] = "Inativo";

				  if ($l["PRO_Tipo"] == "1") $l["PRO_Tipo"] = "Processo Comum";
				  elseif ($l["PRO_Tipo"] == "2") $l["PRO_Tipo"] = "Licitação";
				  elseif ($l["PRO_Tipo"] == "3") $l["PRO_Tipo"] = "Compra Direta";
				  elseif ($l["PRO_Tipo"] == "4") $l["PRO_Tipo"] = "Adesão a ata de Registro de Preço";
				  
				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["PRO_ID"])."</td>
					  <td align='left'>".$l["PRO_Descricao"]."</td>
					  <td align='left'>".$l["PRO_Tipo"]."</td>
					  <td align='left'>".$l["UND_Descricao"]."</td>
					  <td align='left'>".$l["PRO_Prazo"]."</td>
					  <td align='left'>".$l["PRO_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSFamilias":

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
            $idmuni = antInjection($_POST["idmuni"]);
            $dtini = antInjection($_POST["dtini"]);
            $dtfim = antInjection($_POST["dtfim"]);

            $filtro = "<b>DADOS DO FILTRO:</b><br>";
            
			$sql = "SELECT * FROM tb_cadastro WHERE IDCADASTRO IS NOT NULL ";	
			if (!empty($nome)){ 
				$sql.= " AND (NOME LIKE '%".$nome."%' OR APELIDO LIKE  '%".$nome."%' OR NOMEPAI LIKE '%".$nome."%' ";
				$sql.= " OR NOMEMAE LIKE '%".$nome."%' OR CPF LIKE '%".$nome."%') ";
                
                $filtro.= "<b>CPF, Nome, Apelido, Nome Pai ou Nome Mãe:</b> ".$nome."<br>";
			}
            if (!empty($status)){ 
                $sql.= " AND STATUS = '".$status."'";
                
                if ($status == "A") $status = "Ativos"; else $status = "Inativos";
                $filtro.= "<b>Status:</b> ".$status."<br>";
            }else{
                
            }
            
			if (!empty($idmuni)){
			  $sql.= " AND IDMUNICIPIO = '".$idmuni."'";
              
              $sql2 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$idmuni."'";    
              $row2 = $banco->listarArray($sql2);        
              $idmuni = $row2[0]["MUN_Descricao"]; 
			}else{
			  $idmuni = "TODOS";
			}
            
            $filtro.= "<b>Município:</b> ".$idmuni."<br>";
            
             
            if ( (!empty($dtini)) && (!empty($dtfim)) ){
                $sql.= " AND DATA BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtfim)."' ";
                $filtro.= "<b>Período Cadastro:</b> ".$dtini." até ".$dtfim."<br>";
            }
            
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
            
			$html = "<html><body>".$css."	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td>&nbsp;</td>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
               <tr>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td class=\"fontText2\" colspan=\"4\" align=\"left\">".$filtro."</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
			   <tr>
			     <td colspan='5' align=\"left\" class='menuTitle'><b>SISPLAN - LISTAGEM DE FAMÍLIAS</b></td>
			   </tr>   
			   <tr>
                 <td>&nbsp;</td>
				 <td align='left' width='70px' class='fontText'>CPF</td>
				 <td align='left' width='250px' class='fontText'>Nome</td>
				 <td align='left' width='250px' class='fontText'>Apelido</td>
				 <td align='left' class='fontText'>Status</td>
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
			     
                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

				  if ($l["STATUS"] == "A") $l["STATUS"] = "Ativo"; else $l["STATUS"] = "Inativo";

				  $html.= "
				  	<tr>
                      <td>&nbsp;</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["CPF"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["NOME"]."</td>
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["APELIDO"]."</td>					  
					  <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["STATUS"]."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='5' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
    
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSProdutos":
        
            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
			require_once("../class/ConexaoFirebird.php");			
			
            $nome  = antInjection($_POST["nome"]);
			$dtini = antInjection($_POST["dtini"]);
			$dtfim = antInjection($_POST["dtfim"]);
			
			$sql = "SELECT DISTINCT(p.COD_INTERNO) AS COD_INTERNO, p.* FROM PRODUTO p ";
			$sql.= "INNER JOIN TB_PEDIDOS_ITENS i ON (i.IDPRODUTO = p.COD_INTERNO) ";
			$sql.= "INNER JOIN TB_PEDIDOS_HEADER d ON (d.IDPEDIDO = i.IDPEDIDO) WHERE p.COD_INTERNO IS NOT NULL ";
			if (!empty($nome)){
				$sql.= " AND (p.COD_PRODUTO LIKE '%".strtoupper($nome)."%' OR p.DESCRICAO LIKE '%".strtoupper($nome)."%') ";
			}
		
			if ( (!empty($_GET["dtini"])) && (!empty($_GET["dtfim"])) ){
				$sql.= " AND d.DATA BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtfim)."' ";
			}
			$sql.= " ORDER BY p.DESCRICAO";
			$qry = ibase_query($res, $sql);
			
			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='5'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan='5'><b>SISPLAN - LISTAGEM DE PRODUTOS (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</b></td>
			   </tr>
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='300px'><b>Descrição.</b></td>
				 <td align='left' width='100px'><b>Entradas</b></td>
				 <td align='left' width='100px'><b>Saídas</b></td>				 
				 <td align='right' width='200px'><b>Saldo</b></td>
			   </tr>";

			    $i  = 0;
				$ts = 0;
				$te = 0;
				$tg = 0;
				while ($row = ibase_fetch_object($qry)){			
//					echo "<pre>"; print_r($row); echo "</pre>"; exit;
					$sql2 = "SELECT p.TIPO, COUNT(i.IDPRODUTO) AS IDPRODUTO, SUM(i.VLRUNITARIO+QUANTIDADE) AS TOTAL FROM TB_PEDIDOS_ITENS i ";
					$sql2.= "INNER JOIN TB_PEDIDOS_HEADER p ON (p.IDPEDIDO = i.IDPEDIDO) ";
                    $sql2.= "INNER JOIN CFOP o ON (o.CFOP = p.TIPO) ";
					$sql2.= "WHERE i.IDPRODUTO = ".$row->COD_INTERNO." AND o.TIPO_MOVIMENTO = '1' ";
					$sql2.= "AND EXTRACT(YEAR FROM DATA) = '".date("Y")."' GROUP BY p.TIPO";
					$qry2 = ibase_query($res, $sql2);
					$row2 = ibase_fetch_object($qry2);
			
					$sql3 = "SELECT p.TIPO, COUNT(i.IDPRODUTO) AS IDPRODUTO, SUM(i.VLRUNITARIO+QUANTIDADE) AS TOTAL FROM TB_PEDIDOS_ITENS i ";
					$sql3.= "INNER JOIN TB_PEDIDOS_HEADER p ON (p.IDPEDIDO = i.IDPEDIDO) ";
                    $sql3.= "INNER JOIN CFOP o ON (o.CFOP = p.TIPO) ";
					$sql3.= "WHERE i.IDPRODUTO = ".$row->COD_INTERNO." AND o.TIPO_MOVIMENTO = '2' ";
					$sql3.= "AND EXTRACT(YEAR FROM DATA) = '".date("Y")."' GROUP BY p.TIPO";
					$qry3 = ibase_query($res, $sql3);
					$row3 = ibase_fetch_object($qry3);
			
					if (empty($row2->IDPRODUTO)) $row2->IDPRODUTO = 0;
					if (empty($row3->IDPRODUTO)) $row3->IDPRODUTO = 0;
					$total = 0;
					$total = ($row2->TOTAL - $row3->TOTAL);
					
					$te+= $row2->IDPRODUTO;
					$ts+= $row3->IDPRODUTO;
					$tg+= $total;
					
					$html.= "
				  	<tr>
					  <td align='left'>".$row->COD_INTERNO."</td>
					  <td align='left'>".$row->DESCRICAO."</td>
					  <td align='left'>".$row2->IDPRODUTO."</td>
					  <td align='left'>".$row3->IDPRODUTO."</td>
					  <td align='right'>".organiza_moeda($total)."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='2' align='left'>&nbsp;</td>
					  <td align='center' width='100px'><b>".$te."</b></td>
					  <td align='center' width='100px'><b>".$ts."</b></td>
					  <td align='right' width='200px'><b>".$tg."</b></td>
					</tr>				
					<tr>
					  <td align='right' colspan='5' width='100px'><b>Total de Registro(s): ".$i."</b></td>
					</tr>
				</table>";

				if (count($row) == 0){
					echo "
						<script>
							alert('Não há dados para visualização');
							window.close();
						</script>";
					exit;	
				}

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
	
		break;
		
		case "gerarXLSPedidos":			

            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
			require_once("../class/ConexaoFirebird.php");

			$nome      = antInjection($_POST["nome"]);
			$tipo      = antInjection($_POST["tipo"]);
			$status    = antInjection($_POST["status"]);
			$numpedido = antInjection($_POST["numpedido"]);
			$dtfim     = antInjection($_POST["dtfim"]);
			$dtini     = antInjection($_POST["dtini"]);

			$sql = "SELECT p.IDPEDIDO, p.DATA, p.TIPO, p.STATUS, c.FANTASIA FROM TB_PEDIDOS_HEADER p ";
			$sql.= "INNER JOIN CADASTRO c ON (c.COD_CADASTRO = p.IDCLIFOR) WHERE p.IDPEDIDO IS NOT NULL ";
			//montando filtro...
			if ( (!empty($_POST["dtini"])) && (!empty($_POST["dtfim"])) ){
				$sql.= " AND p.DATA BETWEEN '".$conv->conData($_POST["dtini"])."' AND '".$conv->conData($_POST["dtfim"])."'";	
			}
			if (!empty($_POST["numpedido"])) $sql.= " AND p.IDPEDIDO = '".$_POST["numpedido"]."' ";
			if (!empty($_POST["status"])) $sql.= " AND p.STATUS = '".$_POST["status"]."'";
			if (!empty($_POST["nome"])) $sql.= " AND c.FANTASIA LIKE '%".$_POST["nome"]."%' ";
			if (!empty($_POST["tipo"])) $sql.= " AND p.TIPO = '".$_POST["tipo"]."'";	
			$sql.= " ORDER BY p.IDPEDIDO DESC";	
			$qry  = ibase_query($res, $sql);

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='5'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='5' align=\"left\"><b> SISPLAN - LISTAGEM DE PEDIDOS (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</b></td>
			   </tr>
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='350px'><b>Cliente/Fornecedor</b></td>
				 <td align='left' width='70px'><b>Tipo</b></td>				 
				 <td align='left' width='70px'><b>Data</b></td>
				 <td align='left' width='70px'><b>Status</b></td>
			   </tr>";

			   $i = 0;
				while ($row = ibase_fetch_object($qry)){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  
				  if ($row->STATUS == "I") $row->STATUS = "Iniciada"; 
				  elseif ($row->STATUS == "A") $row->STATUS = "Fechada";
				  elseif ($row->STATUS == "C") $row->STATUS = "Cancelada";
				  
				  if ($row->TIPO == "E") $row->TIPO = "Entrada"; else $row->TIPO = "Saída";
				  
				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($row->IDPEDIDO)."</td>
					  <td align='left'>".$row->FANTASIA."</td>
					  <td align='left'>".$row->TIPO."</td>
					  <td align='left'>".$conv->desconverteData($row->DATA)."</td>
					  <td align='left'>".$row->STATUS."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='5' align='right'><b>Total de Registro(s): ".$i++."</b></td>
					</tr>
				</table>";
				
    			if ($i == 0){	
    				echo "
    					<script>
    						alert('Não há dados para visualização.');
    						window.close();
    					</script>";
    				exit;	
    		   }else{

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

    		   }

		break;
		
		case "gerarXLSNFE":			

			require_once("../class/ConexaoFirebird.php");

			$dtini = antInjection($_POST["dtini"]);
			$dtfim = antInjection($_POST["dtfim"]);
			$muni  = antInjection($_POST["muni"]);
			$tipo  = antInjection($_POST["tipo"]);
			
			$sql = "SELECT p.IDMUNICIPIO, p.TIPO, SUM(i.VLRUNITARIO * i.QUANTIDADE) AS TOTAL, COUNT(p.IDPEDIDO) AS IDPEDIDO FROM TB_PEDIDOS_HEADER p ";
			$sql.= "INNER JOIN TB_PEDIDOS_ITENS i ON (i.IDPEDIDO = p.IDPEDIDO) ";
			$sql.= "WHERE p.STATUS = 'T' ";
			
			//montando filtro...
			if ( (!empty($dtini)) && (!empty($dtfim)) ){
				$sql.= " AND p.DATA BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtfim)."'";	
			}
			if (!empty($muni)) $sql.= " AND p.IDMUNICIPIO  = ".$muni;
			if (!empty($tipo)) $sql.= " AND p.TIPO = '".$tipo."'";
			$sql.= " GROUP BY p.TIPO, p.IDMUNICIPIO";	
			$qry = ibase_query($res, $sql);

			$html = "	
			<style type='text/css'>
			
				.fontMenu {
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size:12px;
					font-weight:bold;
					font-style:normal;
					color:#000066;
					text-decoration:none;
				}
				
				.fontText {
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size:11px;
					font-weight:bold;
					color:#000066;
					font-style:normal;
					text-decoration:none;
				}
				
				.fontText2 {
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size:11px;
					font-weight:normal;
					color:#000066;
					font-style:normal;
					text-decoration:none;
				}
				
				.titulo_ok { 
					background-color: #78B0F4; 
					background-repeat: repeat-x; 
					border-color: #406080; 
					border-style: solid; 
					border-width: 1px 0px 1px; 
					color: #FFFFFF; 
					font-family: Verdana, Arial, sans-serif; 
					font-size: 11px; 
					font-weight:bold;
					padding: 2px 5px 2px; 
				}

				
				.titulo_ok2 { 
					background-color: #E4E8F0; 
					border-color: #406080; 
					border-style: solid; 
					border-width: 1px; 
					color: #404040; 
					font-family: Tahoma, Arial, sans-serif; 
					font-size: 11px; 
					padding: 2px 5px 2px; 
				}
				
				.titulo_ok3 { 
					background-color: #0066FF; 
					border-color: #406080; 
					border-style: solid; 
					border-width: 0px 0px 0px; 
					color: #FFFF00; 
					font-family: Verdana, Arial, sans-serif; 
					font-size: 11px; 
					font-weight: bold; 
				
				}
				
				.titulo_ok7 { 
					/*	background-color: #F8D38F;	*/ 
					background-color: #009933; 
					border-color: #406080; 
					border-style: solid; 
					border-width: 0px; 
					color: #FFFFFF; 
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size: 11px; 
					font-weight:bold;
					padding: 2px 5px 2px; 
				}
			</style>
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr>    
					<td colspan='2' align='left' class='fontMenu'>
						<img src='../img/logo_cliente.png' border='0' width='764px'>
					</td>
				</tr>
			</table>	
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr>
			     <td>&nbsp;</td>
			   </tr>
			   <tr>
			     <td align='left' class='fontMenu' colspan='4'>SISPLAN - LISTAGEM DE NFE TRANSMITIDAS</td>
			   </tr>   
			   <tr>
			     <td align='left' class='fontText' width='50%'>Município</td>
				 <td align='left' class='fontText'>Tipo</td>			
				 <td align='right' class='fontText' colspan='2'>Valor Total</td>
			   </tr>";

				$totQTD = 0;
				$totVR  = 0;	
				$dados  = false;
				while ($l = ibase_fetch_object($qry)){
					  $dados  = true;	
					  $totQTD++;
			
					  if ($l->TIPO == "E") $tipo = "Entrada"; else $tipo = "Saída";
			
					  $sqlM = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = ".$l->IDMUNICIPIO;
					  $rowM = $banco->listarArray($sqlM);
					  
					  $totVR+= $l->TOTAL;
					  
					  $html.= "
					  <tr class='titulo_ok2'>
						<td align='left' class='fontText2' width='50%'>".$rowM[0]["MUN_Descricao"]."</td>
						<td align='left' class='fontText2'>".cor($tipo, $parms)."</td>			
						<td align='right' class='fontText2' colspan='2'>".cor(organiza_moeda($l->TOTAL), $parms)."</td>
					  </tr>";
					  
					  /* DETALHE DO ITEM DO PEDIDO*/
					  $sql2 = "SELECT i.IDPRODUTO, SUM(i.QUANTIDADE) AS QUANTIDADE, i.VLRUNITARIO, t.DESCRICAO FROM TB_PEDIDOS_HEADER p ";
					  $sql2.= "INNER JOIN TB_PEDIDOS_ITENS i ON (i.IDPEDIDO = p.IDPEDIDO) ";
					  $sql2.= "INNER JOIN PRODUTO t ON (t.COD_INTERNO = i.IDPRODUTO) ";
					  $sql2.= "WHERE p.STATUS = 'T' ";
					  //montando filtro...
					  if ( (!empty($dtini)) && (!empty($dtfim)) ){
						$sql2.= " AND p.DATA BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtfim)."'";	
					  }
					  $sql2.= " AND p.IDMUNICIPIO  = ".$l->IDMUNICIPIO;
					  $sql2.= " AND p.TIPO = '".$l->TIPO."'";
					  $sql2.= " GROUP BY i.IDPRODUTO, i.VLRUNITARIO, t.DESCRICAO";
					  $qry2 = ibase_query($res, $sql2);

					  $html.= "
						  <tr>
							<td align=\"left\" class=\"fontText\">DESCRIÇÃO</td>
							<td align=\"center\" class=\"fontText\">QUANTIDADE</td>
							<td align=\"right\" class=\"fontText\">VALOR</td>
							<td align=\"right\" class=\"fontText\">TOTAL</td>
						  </tr>";
						  
					  $i      = 0;
					  $totDET = 0;
					  while ($l2 = ibase_fetch_object($qry2)){
					  
						  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
					  
						  $html.= "
						  <input type='checkbox' name='cod[]' id='cod$i' value=".$l2->IDPEDIDO."@".$l2->IDPRODUTO." style=\"visibility:hidden;position:absolute;\">
						  <tr id='cel$i' style='cursor:hand;background-color:".$cor.";'>
							<td align='left' class='fontText2'>".cor($l2->DESCRICAO, $parms)."</td>			
							<td align='center' class='fontText2'>".cor(organiza_moeda($l2->QUANTIDADE), $parms)."</td>
							<td align='right' class='fontText2'>".cor(organiza_moeda($l2->VLRUNITARIO), $parms)."</td>	
							<td align='right' class='fontText2'>".cor(organiza_moeda($l2->QUANTIDADE * $l2->VLRUNITARIO), $parms)."</td>							
						  </tr>";
						  $totDET+= ($l2->QUANTIDADE * $l2->VLRUNITARIO);
						  $i++;
					  }
					  $html.= "
						<tr>
							<td class='titulo_ok3' colspan='4' align='right'>Valor Total Geral: ".$totDET."</td>
						</tr>			  
						<tr>
							<td colspan='4'>&nbsp;</td>
						</tr>";
				}
			
				$html.= "
					<tr>
						<td class='titulo_ok7' colspan='4' align='right'>Total Geral: ".$totVR."</td>
					</tr>	
					<tr>
						<td class='titulo_ok3' colspan='4' align='right'>Total de NFE(s) : ".$totQTD."</td>
					</tr>";
					
				if (!$dados){
				  echo "<br>
					<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"25%\" align=\"center\">
			
					  <tr>
						<td colspan=\"9\" class=\"titulo_ok\">Informa&ccedil;&atilde;o</td>
					  </tr>
					  <tr>	
						<td colspan=\"9\" class=\"titulo_ok2\">Não há dados para visualização.</td>
					  </tr>
					</table><br>";  
				
				}else{
					echo $html.= "</table>";  
				}
				
				ibase_commit($res);
				ibase_free_result($qry);
				ibase_close($res);  

		break;
		
		case "gerarXLSPedidosItens":			
            
            $IDCONEXAONFE = antInjection($_POST["empresa"]); 
            
			require_once("../class/ConexaoFirebird.php");

			$nome      = antInjection($_POST["nome"]);
			$tipo      = antInjection($_POST["tipo"]);
			$status    = antInjection($_POST["status"]);
			$numpedido = antInjection($_POST["numpedido"]);
			$dtfim     = antInjection($_POST["dtfim"]);
			$dtini     = antInjection($_POST["dtini"]);

			$sql = "SELECT p.IDPEDIDO, p.DATA, p.TIPO, p.STATUS, c.FANTASIA FROM TB_PEDIDOS_HEADER p ";
			$sql.= "INNER JOIN CADASTRO c ON (c.COD_CADASTRO = p.IDCLIFOR) ";
			$sql.= "INNER JOIN TB_PEDIDOS_ITENS i ON (i.IDPEDIDO = p.IDPEDIDO) WHERE p.IDPEDIDO IS NOT NULL ";
			//montando filtro...
			if ( (!empty($_POST["dtini"])) && (!empty($_POST["dtfim"])) ){
				$sql.= " AND p.DATA BETWEEN '".$conv->conData($_POST["dtini"])."' AND '".$conv->conData($_POST["dtfim"])."'";	
			}
			if (!empty($_POST["numpedido"])) $sql.= " AND p.IDPEDIDO = '".$_POST["numpedido"]."' ";
			if (!empty($_POST["status"])) $sql.= " AND p.STATUS = '".$_POST["status"]."'";
			if (!empty($_POST["nome"])) $sql.= " AND c.FANTASIA LIKE '%".$_POST["nome"]."%' ";
			if (!empty($_POST["tipo"])) $sql.= " AND p.TIPO = '".$_POST["tipo"]."'";	
			
			$sql.= " GROUP BY p.IDPEDIDO, p.DATA, p.TIPO, p.STATUS, c.FANTASIA";	
			$qry  = ibase_query($res, $sql);

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='5'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td colspan='5' align=\"left\"><b> SISPLAN - LISTAGEM DE PEDIDOS (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left'><b>Cliente/Fornecedor</b></td>
				 <td align='left'><b>Tipo</b></td>				 
				 <td align='left'><b>Data</b></td>
				 <td align='left'><b>Status</b></td>
			   </tr>";

			   $i = 0;
				while ($row = ibase_fetch_object($qry)){
				  
				  if ($row->STATUS == "I") $row->STATUS = "Iniciada"; 
				  elseif ($row->STATUS == "A") $row->STATUS = "Fechada";
				  elseif ($row->STATUS == "C") $row->STATUS = "Cancelada";
				  
				  if ($row->TIPO == "E") $row->TIPO = "Entrada"; else $row->TIPO = "Saída";
				  
				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($row->IDPEDIDO)."</td>
					  <td align='left'>".$row->FANTASIA."</td>
					  <td align='left'>".$row->TIPO."</td>
					  <td align='left'>".$conv->desconverteData($row->DATA)."</td>
					  <td align='left'>".$row->STATUS."</td>
					</tr>";
					
					//lista os detalhes do pedidos...
					$sqlD = "SELECT i.IDPEDIDO, i.IDPRODUTO, i.QUANTIDADE, i.VLRUNITARIO, p.DESCRICAO FROM TB_PEDIDOS_ITENS i ";
					$sqlD.= "INNER JOIN PRODUTO p ON (p.COD_INTERNO = i.IDPRODUTO) ";
					$sqlD.= "WHERE i.IDPEDIDO = ".$row->IDPEDIDO;
//					echo $sqlD.'<br><br>';
					$qryD = ibase_query($res, $sqlD);
					$a = 1;
					
					$html.= "
						<tr>
							<td align='left'><b>Sequencial</b></td>
							<td align='left' width='300px'><b>Item</b></td>
							<td align='left' width='70px'><b>Valor Unitário</b></td>
							<td align='left' width='70px'><b>Quantidade</b></td>
							<td align='left' width='70px'><b>Total</b></td>
						</tr>";
					
					$tot_t = 0;
					while ($rowD = ibase_fetch_object($qryD)){
					
					$tot_g = 0;
					$tot_g = ($rowD->VLRUNITARIO * $rowD->QUANTIDADE);
					$tot_t+= $tot_g;
					
						$html.= "
							<tr>
								<td align='left'>".$a."</td>
								<td align='left'>".$rowD->DESCRICAO."</td>
								<td align='left'>".organiza_moeda($rowD->VLRUNITARIO)."</td>
								<td align='left'>".$rowD->QUANTIDADE."</td>
								<td align='left'>".organiza_moeda($tot_g)."</td>
							</tr>";
						$a++;
					}
					
					$html.= "
						<tr>
                            <td align='left' colspan='5'><b> Total Geral => ".organiza_moeda($tot_t)."</b></td>
						</tr>
						<tr>
							<td>
								<hr width='180px' colspan='5' align='center'>
							</td>
						</tr>";
                        
                        $i++;
				}

				$html.= "
					<tr>
					  <td colspan='5' align='right'><b>Total de Registro(s): ".$i++."</b></td>
					</tr>
				</table>";
				
    			if ($i == 0){	
    				echo "
    					<script>
    						alert('Não há dados para visualização.');
    						window.close();
    					</script>";
    				exit;	
    		    }else{

                    $dompdf->load_html($html);
                    $dompdf->render();
                    $dompdf->stream("sisplan_".date("dmY_his").".pdf");
  
    		    }
                			
		break;
		
		case "gerarXLSProdEmp":
            
            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
			require_once("../class/ConexaoFirebird.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_prod_empenhados WHERE PEM_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND PEM_Ano = '".$nome."' ";
			if (!empty($status)) $sql.= " AND PEM_Status = '".$status."' ";
			$sql.= " ORDER BY PEM_Ano";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='6'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td colspan='6' align=\"left\"><b> SISPLAN - LISTAGEM DE PRODUTOS EMPENHADOS (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>ID.</b></td>
				 <td align='left' width='50px'><b>ANO</b></td>
				 <td align='left' width='230px'><b>PRODUTOR</b></td>
				 <td align='left' width='230px'><b>PRODUTO</b></td>				 				 
				 <td align='left' width='50px'><b>QTD.</b></td>				 
				 <td align='left'><b>STATUS</b></td>	
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
				  if ($l["PEM_Status"] == "A") $l["PEM_Status"] = "Ativo"; else $l["PEM_Status"] = "Inativo";
				  
				  $sqlP = "SELECT FANTASIA, CNPJ FROM CADASTRO WHERE COD_CADASTRO = '".$l["PRT_ID"]."' ";
				  $qryP = ibase_query($res, $sqlP);
				  $rowP = ibase_fetch_object($qryP);
				
				  $sqlP2 = "SELECT COD_INTERNO, DESCRICAO, VENDA FROM PRODUTO WHERE COD_INTERNO = '".$l["PRD_ID"]."'";
				  $qryP2 = ibase_query($res, $sqlP2);
				  $rowP2 = ibase_fetch_object($qryP2);

				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l["PEM_ID"])."</td>
					  <td align='left'>".$l["PEM_Ano"]."</td>
					  <td align='left'>".$rowP->FANTASIA."</td>
					  <td align='left'>".$rowP2->DESCRICAO."</td>
					  <td align='left'>".$l["PEM_Qtd"]."</td>
					  <td align='left'>".$l["PEM_Status"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;	

		case "gerarXLSAcompanhamentoAcoes":

			$tipo      = antInjection($_POST["tipo"]);
			$mun	   = antInjection($_POST["mun"]);
			$proj	   = antInjection($_POST["proj"]);
			$ano	   = antInjection($_POST["ano"]);
            $acess     = antInjection($_POST["acess"]);
            $flag      = antInjection($_POST["flag"]);
            $quant     = antInjection($_POST["quant"]);
            $validamod = antInjection($_POST["validamod"]);
            
            unset($_REQUEST);            

            if (isset($_POST["meta"])) $meta = "S"; else $meta = "";
            
            $html    = "";
            $filtro  = "";

            /* MONTANDO OS DADOS DO FILTRO */
            $filtro.= "<b>DADOS DO FILTRO:</b><br>";
            $filtro.= "<b>Ano Competência:</b> ".$ano."<br>";
            if (!empty($mun)){

                $sqlF = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                $rowF = $banco->listarArray($sqlF);

                $filtro.= "<b>Município:</b> ".$rowF[0]["MUN_Descricao"]."<br>";

            }else{
                
                if (!empty($tipo)){
                    
                    if ($flag == "RD"){
                    
                        $sqlF = "SELECT RDE_Descricao FROM tb_regiaodesen WHERE RDE_ID = '".$tipo."'";
                        $rowF = $banco->listarArray($sqlF);
                        
                        $filtro.= "<b>Região de Desenvolvimento:</b> ".$rowF[0]["RDE_Descricao"]."<br>";    
                    }else{
                        
                        $sqlF = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$tipo."'";                    
                        $rowF = $banco->listarArray($sqlF);
                        
                        $filtro.= "<b>Gerência Regional:</b> ".$rowF[0]["REG_Descricao"]."<br>";
                    }
                }
            }

            if (!empty($proj)) {
                
                $sqlF = "SELECT PRJ_Descricao FROM tb_projetos WHERE PRJ_IDProjeto = '".$proj."'";
                $rowF = $banco->listarArray($sqlF);
                
                $filtro.= "<b>Projeto:</b> ".$rowF[0]["PRJ_Descricao"]."<br>";    
            }
            /**/
            if ($meta == "S") $filtro.= "<b>Meta Prioritária :</b> SIM<br>";

            $titulo = "Listagem de Lançamentos Plano Execução";
            
            if (empty($quant)) $quant = 0;
            
            $sql = "SELECT p.PRJ_IDProjeto, p.PRJ_Descricao, l.PLA_Ano, a.ATV_IDAtividade, l.MUN_IDMunicipio, m.MUN_Descricao, l.USU_IDUsuario, p.PRJ_Opcao FROM tb_projetos p ";
            $sql.= "INNER JOIN tb_atividades a ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
            $sql.= "INNER JOIN tb_planoanual l ON (l.ATV_IDAtividade = a.ATV_IDAtividade) ";
            $sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = l.MUN_IDMunicipio) ";
            $sql.= "LEFT OUTER JOIN tb_plano_execucao e ON (e.ATV_IDAtividade = l.ATV_IDAtividade AND e.MUN_IDMunicipio = l.MUN_IDMunicipio) ";
            $sql.= "WHERE p.PRJ_Status = 'A' AND m.MUN_Status = 'A' AND a.ATV_Status = 'A' ";
            
            // monta filtro
            if (!empty($ano)) $sql.= " AND l.PLA_Ano = '".$ano."'";
            if (!empty($mun)){
                $sql.= " AND l.MUN_IDMunicipio = '".$mun."' ";    
            }else{
                if (!empty($tipo)){
                    if ($flag == "RD") $sql.= " AND m.RDE_ID = ".$tipo; else $sql.= " AND m.REG_ID = ".$tipo;
                }
            }
            if (!empty($meta)) $sql.= " AND p.PRJ_Opcao = '".$meta."' ";
            if (!empty($proj)) $sql.= " AND a.PRJ_IDProjeto = '".$proj."' ";
            $sql.= " GROUP BY l.PLA_Ano, ";
            if (!empty($tipo)){
                if ($flag == "RD") $sql.= " m.RDE_ID, "; else $sql.= " m.REG_ID, ";
            }
            $sql.= " p.PRJ_IDProjeto";
            $row = $banco->listarArray($sql);
            if (count($row) == 0){
            
                echo "
                <script>
                    alert('".utf8_decode(strtoupper($_SESSION["sNOME_USUARIO"]).", Não há dados para visualização.")."');
                    window.close();
                </script>";
                exit;

            }else{

                $html.= '<html>'.$css.'
                <body>
                <table width="750px" border="0" cellpadding="1" cellspacing="1" align="center">
    			    <tr>
                        <td align=\'right\' colspan=\'13\'>
                            <img src=\'../img/logo_cliente.jpg\' border=\'0\' width="750px">
                        </td>
    			    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fontText2" colspan=\'13\' align=\"center\">'.$filtro.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>                                        
                    <tr>
                        <td class="fontText2"></td>
                        <td colspan="12" align="left" class="menuTitle">'.$titulo.'</td>
                    </tr>';
            
                    if ($flag == "RD"){
                        $str = "Região de Desenvolvimento";
                    
                        if (!empty($tipo)){
                            $sql33 = "SELECT RDE_Descricao FROM tb_regiaodesen WHERE RDE_ID = '".$tipo."'";
                            $row33 = $banco->listarArray($sql33);

                            $str.= " : ".strtoupper($row33[0]["RDE_Descricao"]);

                            if (!empty($mun)){
                                $sql34 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                                $row34 = $banco->listarArray($sql34);        
                        
                                $str.= " (".strtoupper($row34[0]["MUN_Descricao"])." - TOTAL DE AGRICULTORES - ".$quant.")";
                            }else{
                                $str.= " (TOTAL DE AGRICULTORES - ".$quant.")";
                            }

                        }else{
                            $str.= " : TODOS";
                        }
                    }else{

                        $str = "Gerência Regional";
                    
                        if (!empty($tipo)){
                    
                            $sql33 = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$tipo."'";
                            $row33 = $banco->listarArray($sql33);
                    
                            $str.= " : ".strtoupper($row33[0]["REG_Descricao"]);
                    
                            if (!empty($mun)){
                    
                                $sql34 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                                $row34 = $banco->listarArray($sql34);
                    
                                $str.= " (".strtoupper($row34[0]["MUN_Descricao"])." - TOTAL DE AGRICULTORES - ".$quant.")";
                            }else{
                                $str.= " (TOTAL DE AGRICULTORES - ".$quant.")";
                            }
                        }else{
                            $str.= " : TODOS";                    
                        }
                    }

                    $html.= '
                    <tr>
                        <td class="fontText2"></td>
                        <td align="left" colspan="12" class="menuTitle">'.$str.'</td>
                    </tr>';
            
                    $i     = 0;
                    $conta = 0;
                    foreach($row as $l){

                        if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";

                        $metaobg = "";
                        if ($l["PRJ_Opcao"] == "S") $metaobg = "<span class='fontError'>(Meta Prioritária)</span>";

                        $html.= '
                        <tr>
                            <td style="cursor:pointer;background-color:'.$cor.';" align="center">&nbsp;</td>
                            <td style="cursor:pointer;background-color:'.$cor.';" align="left" class="fontText" width="80%" colspan="3">'.$l["PRJ_Descricao"].' '.$metaobg.'</td>
                            <td style="cursor:pointer;background-color:'.$cor.';" class="fontText" align="center" colspan="3">PLANEJADO</td>
                            <td style="cursor:pointer;background-color:'.$cor.';" class="fontText" align="center" colspan="3">EXECUTADO</td>
                            <td style="cursor:pointer;background-color:'.$cor.';" class="fontText" align="center" colspan="3">PERCENTUAL</td>
                        </tr>
                        <tr>
                            <td align="center">&nbsp;</td>
                            <td align="left" class="fontText">CÓDIGO</td>
                            <td align="left" width="300px" class="fontText">DESCRIÇÃO</td>
                            <td align="center" class="fontText">UND.</td>
                            <td align="center" class="fontText" width="8%">QTD.</td>
                            <td align="center" class="fontText" width="8%" colspan="2">FAMÍLIA</td>                  
                            <td align="right" class="fontText" width="8%">QTD.</td>
                            <td align="center" class="fontText" width="8%" colspan="2">FAMÍLIAS</td>
                            <td align="right" class="fontText" width="8%">QTD.</td>
                            <td align="center" class="fontText" width="8%" colspan="2">FAM</td>
                        </tr>';
            
                        $sql2 = "SELECT l.PRJ_IDProjeto, a.ATV_IDAtividade, u.UND_Descricao, a.ATV_Descricao, SUM(l.ATV_Prevfam) AS ATV_Prevfam, SUM(l.ATV_Prevqtd) AS ATV_Prevqtd, a.ATV_Tipoficha FROM tb_atividades a ";
                        $sql2.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = a.PRJ_IDProjeto) ";
                        $sql2.= "INNER JOIN tb_unidades u ON (u.UND_IDUnidade = a.UND_IDUnidade) ";
                        $sql2.= "INNER JOIN tb_planoanual l ON (l.ATV_IDAtividade = a.ATV_IDAtividade) ";
                        $sql2.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = l.MUN_IDMunicipio) ";
                        $sql2.= "WHERE a.PRJ_IDProjeto = '".$l["PRJ_IDProjeto"]."' AND p.PRJ_Status = 'A' AND m.MUN_Status = 'A' ";
                        
                        if (!empty($mun)){
                            $sql2.= " AND l.MUN_IDMunicipio = '".$mun."' ";    
                        }else{
                            if (!empty($tipo)){
                                if ($flag == "RD") $sql2.= " AND m.RDE_ID = '".$tipo."'"; else $sql2.= " AND m.REG_ID = '".$tipo."'";
                            }
                        }
                        if (!empty($meta)) $sql2.= " AND p.PRJ_Opcao = '".$meta."' ";	
                        if (!empty($ano)) $sql2.= " AND l.PLA_Ano = '".$ano."' ";          
                        $sql2.= " GROUP BY ";
                        if (!empty($tipo)){
                            if ($flag == "RD") $sql2.= " m.RDE_ID, "; else $sql2.= " m.REG_ID, ";
                        }
                        $sql2.= " l.ATV_IDAtividade ORDER BY a.ATV_Tipoficha, a.ATV_Descricao ";
                        $row2 = $banco->listarArray($sql2);
                        $a    = 0;

                        foreach($row2 as $l2){
            
                            if($a % 2 == 0) $corX = "#EEE"; else $corX = "#FFF";
            
                            $sqlEX = "SELECT SUM(e.PLE_Qtd) AS PLE_Qtd, COUNT(DISTINCT(e.FAM_IDFamilia)) AS FAM_IDFamilia, SUM(e.PLE_Qtd2) AS PLE_Qtd2, SUM(e.PLE_Familias) AS PLE_Familias FROM tb_plano_execucao e ";
                            $sqlEX.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
                            $sqlEX.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = e.PRJ_IDProjeto) ";
                            $sqlEX.= "INNER JOIN tb_usuarios s ON (s.USU_IDUsuario = e.USU_IDUsuario) ";
                            if ($l2["ATV_Tipoficha"] == "S"){
                                $sqlEX.= "WHERE e.PRJ_IDProjeto = '".$l2["PRJ_IDProjeto"]."' AND e.ATV_IDAtividade = '".$l2["ATV_IDAtividade"]."' ";                                                                
                            }else{                                
                                $sqlEX.= "INNER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND e.MUN_IDMunicipio = c.IDMUNICIPIO) ";
                                $sqlEX.= "WHERE e.PRJ_IDProjeto = '".$l2["PRJ_IDProjeto"]."' AND e.ATV_IDAtividade = '".$l2["ATV_IDAtividade"]."' ";
                                $sqlEX.= "AND c.STATUS = 'A' ";
                            }
                            $sqlEX.= "AND s.USU_Status = 'A' AND p.PRJ_Status = 'A' AND m.MUN_Status = 'A' ";
                            $sqlEX.= "AND (SELECT COUNT(*) FROM tb_planoanual WHERE PLA_Ano = e.PLE_Ano AND MUN_IDMunicipio = e.MUN_IDMunicipio AND ATV_IDAtividade = e.ATV_IDAtividade) > 0 ";

                            if (!empty($mun)){
                                $sqlEX.= " AND e.MUN_IDMunicipio = '".$mun."' AND e.FAM_IDFamilia <> 0 ";    
                            }else{    		      
                                if (!empty($tipo)){
                                    if ($flag == "RD") $sqlEX.= " AND m.RDE_ID = '".$tipo."' "; else $sqlEX.= " AND m.REG_ID = '".$tipo."' ";
                                }
                            }

                            if (!empty($meta)) $sqlEX.= " AND p.PRJ_Opcao = '".$meta."' ";
                            $sqlEX.= " AND e.PLE_Ano = '".$ano."'";
                            //$sqlEX.= " e.ATV_IDAtividade";
                            //echo $sqlEX.'<br><br>';
                            $rowEX = $banco->listarArray($sqlEX);
                        
                            if (empty($l2["ATV_Prevfam"])) $l2["ATV_Prevfam"] = 0;
                            if (empty($l2["ATV_Prevqtd"])) $l2["ATV_Prevqtd"] = 0;
                            if (empty($rowEX[0]["PLE_Qtd"])) $rowEX[0]["PLE_Qtd"] = 0;
                            if (empty($rowEX[0]["FAM_IDFamilia"])) $rowEX[0]["FAM_IDFamilia"] = 0;

                            if ($rowEX[0]["PLE_Qtd2"]>0) $rowEX[0]["PLE_Qtd"] = $rowEX[0]["PLE_Qtd2"];
                            if ($rowEX[0]["PLE_Qtd2"]>0) $rowEX[0]["FAM_IDFamilia"] = $rowEX[0]["PLE_Familias"];

                            $perc_q = 0;
                            $perc_f = 0;
                            if ($l2["ATV_Prevqtd"]>0){
                                $perc_q = ($rowEX[0]["PLE_Qtd"] * 100) / $l2["ATV_Prevqtd"];    
                            }
                            if ($l2["ATV_Prevfam"]>0){
                                $perc_f = ($rowEX[0]["FAM_IDFamilia"] * 100 ) / $l2["ATV_Prevfam"];    
                            }
                            /**/

                            $z = $l2["ATV_IDAtividade"];
                            $class = "fontText2";

                            if ($l2["ATV_Tipoficha"] == "S"){
                                
                                $sqlFAM3 = "SELECT COUNT(DISTINCT(e.FAM_IDFamilia)) AS TOTAL FROM tb_plano_execucao e ";
                                $sqlFAM3.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
                                $sqlFAM3.= "INNER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND c.IDMUNICIPIO = e.MUN_IDMunicipio) ";
                                $sqlFAM3.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = e.PRJ_IDProjeto) ";
                                $sqlFAM3.= "INNER JOIN tb_atividades a ON (a.ATV_IDAtividade = e.ATV_IDAtividade) ";
                                $sqlFAM3.= "INNER JOIN tb_planoanual l ON (l.PLA_Ano = e.PLE_Ano AND l.MUN_IDMunicipio = e.MUN_IDMunicipio AND l.ATV_IDAtividade = e.ATV_IDAtividade) ";
                                $sqlFAM3.= "WHERE e.PLE_Ano = '".$ano."' AND e.FAM_IDFamilia <> 0 AND p.PRJ_Status = 'A' AND m.MUN_Status = 'A' AND a.ATV_Status = 'A' ";                
                                if (!empty($mun)){
                                    $sqlFAM3.= " AND e.MUN_IDMunicipio = ".$mun;    
                                }else{    		      
                                    if (!empty($tipo)){
                                        if ($flag == "RD"){
                                            $sqlFAM3.= " AND m.RDE_ID = '".$tipo."' ";    
                                        }else{
                                            $sqlFAM3.= " AND m.REG_ID = '".$tipo."' ";   
                                        }
                                    }
                                }
            
                                if (!empty($meta)) $sqlFAM3.= " AND p.PRJ_Opcao = '".$meta."' ";
                                $sqlFAM3.= " AND a.ATV_Tipoficha <> 'S' AND e.PRJ_IDProjeto = '".$l["PRJ_IDProjeto"]."'";
            //                    echo $sqlFAM3.'<br><br>';
                                $rowFAM3 = $banco->listarArray($sqlFAM3);
                                
                                $rowEX[0]["FAM_IDFamilia"] = $rowFAM3[0]["TOTAL"];
                                unset($rowFAM3);
                                $conta+= $rowFAM3[0]["TOTAL"];

                                $l2["ATV_Prevqtd"]   = "";
                                $rowEX[0]["PLE_Qtd"] = "";
                                $l2["ATV_Prevfam"]   = "";
                                $perc_q              = 0;
                                $perc_f              = 0;
                                $class               = "fontText";
                            }
                            
                            $sqlG = "";
                            
                            $sqlAV = "SELECT SUM(e.PLE_Familias) AS PLE_Familias, SUM(e.PLE_Qtd2) AS PLE_Qtd2 FROM tb_plano_execucao e ";
                            $sqlAV.= "LEFT OUTER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND c.IDMUNICIPIO = e.MUN_IDMunicipio) ";
                            $sqlAV.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
                            $sqlAV.= "WHERE e.PRJ_IDProjeto = '".$l["PRJ_IDProjeto"]."' AND e.ATV_IDAtividade = '".$l2["ATV_IDAtividade"]."' ";
                            $sqlAV.= "AND e.PLE_Ano = '".$l["PLA_Ano"]."'AND m.MUN_Status = 'A' ";
                            if (!empty($mun)){
                                $sqlAV.= " AND e.MUN_IDMunicipio = '".$l["MUN_IDMunicipio"]."' ";    
                            }else{    		      
                                if (!empty($tipo)){
                                    if ($flag == "RD"){                                        
                                        $sqlAV.= " AND m.RDE_ID = '".$tipo."' ";
                                        $sqlG = "GROUP BY m.RDE_ID";
                                    }else{                                        
                                        $sqlAV.= " AND m.REG_ID = '".$tipo."' ";
                                        $sqlG = "GROUP BY m.REG_ID";   
                                    }
                                }    
                            }
                            $sqlAV.= "AND e.FAM_IDFamilia = '0' ".$sqlG." ORDER BY PLE_Familias DESC";
                            //echo $sqlAV.'<br>';
                            $rowAV = $banco->listarArray($sqlAV);
                            $b = false;
                            if (count($rowAV) > 0){ 
                                $b = true;
                                
                                if ($l["PRJ_Opcao"] == "S") {
                                    
                                    if ($rowEX[0]["FAM_IDFamilia"]<=0){
                                        if ($rowAV[0]["PLE_Familias"]>0) $rowEX[0]["FAM_IDFamilia"] = $rowAV[0]["PLE_Familias"];
                                    }
                                    
                                    if ($rowAV[0]["PLE_Qtd2"]>0) $rowEX[0]["PLE_Qtd"] = $rowAV[0]["PLE_Qtd2"];
                                    if ($l2["ATV_Prevfam"]>0) $perc_f = ($rowEX[0]["FAM_IDFamilia"] * 100 ) / $l2["ATV_Prevfam"];
                                    if ($l2["ATV_Prevqtd"]>0) $perc_q = ($rowEX[0]["PLE_Qtd"] * 100) / $l2["ATV_Prevqtd"];

                                }    
                            }
                            unset($rowAV);
                            if (empty($rowEX[0]["PLE_Qtd"])) $rowEX[0]["PLE_Qtd"] = 0;
            
                            $html.= '                
                            <tr>
                                <td style="background-color:'.$corX.';" align="center">&nbsp;</td>
                                <td style="background-color:'.$corX.';" align="left" class="fontText2">'.completarComZero($l2["ATV_IDAtividade"]).'</td>
                                <td style="background-color:'.$corX.';" align="left" class="fontText2">'.$l2["ATV_Descricao"].'</td>
                                <td style="background-color:'.$corX.';" align="center" class="fontText2">'.$l2["UND_Descricao"].'</td>
                                <td style="background-color:'.$corX.';" align="right" class="fontText2">';
                                if ($l2["ATV_Tipoficha"] != "S") $html.= organiza_moeda($l2["ATV_Prevqtd"]);
                                $html.= '</td>
                                <td style="background-color:'.$corX.';" align="center" class="fontText2" colspan="2">'.$l2["ATV_Prevfam"].'</td>
                                <td style="background-color:'.$corX.';" align="right" class="fontText2">';
                                if ($l2["ATV_Tipoficha"] != "S") $html.= organiza_moeda($rowEX[0]["PLE_Qtd"]);
                                $html.='</td>
                                <td style="background-color:'.$corX.';" align="center" class="'.$class.'" colspan="2">'.$rowEX[0]["FAM_IDFamilia"].'</td>
                                <td style="background-color:'.$corX.';" align="right" class="fontText2">';
                                if ($l2["ATV_Tipoficha"] != "S") $html.= organiza_moeda($perc_q).'%';
                                $html.= '</td>                                
                                <td style="background-color:'.$corX.';" align="center" class="fontText2" colspan="2">';
                                if ($l2["ATV_Tipoficha"] != "S") $html.= organiza_moeda($perc_f).'%';
                                $html.= '</td>
                            </tr>';
                            unset($rowEX);

                        $a++;
                            if ($b == false){
                              if ( ($perc_f>0) || ($perc_q>0)){
                                
                                $html.= '
                                <tr>
                                    <td colspan="13">
                                        <table width="750px" border="0" cellpadding="5" cellspacing="0" align="center">
                                            <tr>
                                                <td align="left" class="fontText" colspan="2">USUÁRIO</td>
                                                <td align="left" width="65%" class="fontText">MUNICÍPIO</td>                          
                                                <td align="center" class="fontText" width="15px">QTD.</td>
                                                <td align="center" class="fontText" width="15px" colspan="2">FAMÍLIAS</td>                  
                                                <td align="right" class="fontText" width="15px">&nbsp;</td>
                                                <td align="center" class="fontText" width="15px" colspan="2"></td>
                                                <td align="right" class="fontText" width="15px">&nbsp;</td>
                                                <td align="center" class="fontText" width="15px" colspan="2">&nbsp;</td>
                                            </tr>';
                                
                                    // LISTA DETALHE...
                                    $sqlD = "SELECT m.MUN_Descricao, SUM(e.PLE_Qtd) AS PLE_Qtd, COUNT(DISTINCT(e.FAM_IDFamilia)) AS FAM_IDFamilia, s.USU_Login FROM tb_plano_execucao e ";
                                    $sqlD.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = e.PRJ_IDProjeto) ";
                                    $sqlD.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
                                    $sqlD.= "INNER JOIN tb_atividades a ON (a.ATV_IDAtividade = e.ATV_IDAtividade) ";
                                    $sqlD.= "INNER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND e.MUN_IDMunicipio = c.IDMUNICIPIO) ";
                                    $sqlD.= "INNER JOIN tb_usuarios s ON (s.USU_IDUsuario = e.USU_IDUsuario) ";
                                    $sqlD.= "AND (SELECT COUNT(*) FROM tb_planoanual WHERE PLA_Ano = e.PLE_Ano AND MUN_IDMunicipio = e.MUN_IDMunicipio AND ATV_IDAtividade = e.ATV_IDAtividade) > 0 ";
                                    $sqlD.= "WHERE e.PLE_Ano = '".$ano."' ";                            
                                    if (!empty($mun)){
                                        $sqlD .= " AND e.MUN_IDMunicipio = '".$mun."' ";    
                                    }else{    		      
                                        if (!empty($tipo)){
                                            if ($flag == "RD") $sqlD.= " AND m.RDE_ID = '".$tipo."' "; else $sqlD.= " AND m.REG_ID = '".$tipo."' ";
                                        }
                                    }
                                    if (!empty($meta)) $sqlD.= " AND p.PRJ_Opcao = '".$meta."' ";
                                    $sqlD .= " AND e.PRJ_IDProjeto = ".$l["PRJ_IDProjeto"]." AND e.ATV_IDAtividade = ".$l2["ATV_IDAtividade"];
                                    $sqlD.= " GROUP BY m.MUN_Descricao ORDER BY s.USU_Login";
                                    $rowD = $banco->listarArray($sqlD);
            
                                    $n    = 0;
                                    $totf = 0;  
                                    foreach($rowD as $d){
                        
                                        if($n % 2 == 0) $corD = "#EEE"; else $corD = "#FFF";
                                        $totf+= $d["FAM_IDFamilia"];
            
                                        $html.= '
                                        <tr>
                                            <td style="background-color:'.$corD.';" align="left" class="fontText2" colspan="2">'.$d["USU_Login"].'</td>                                                    
                                            <td style="background-color:'.$corD.';" align="left" width="65%" class="fontText2">'.$d["MUN_Descricao"].'</td>                                                              
                                            <td style="background-color:'.$corD.';" align="right" class="fontText2">'.organiza_moeda($d["PLE_Qtd"]).'</td>
                                            <td style="background-color:'.$corD.';" align="center" class="fontText2" colspan="2">'.$d["FAM_IDFamilia"].'</td>
                                            <td style="background-color:'.$corD.';" align="center" class="fontText2">&nbsp;</td>
                                            <td style="background-color:'.$corD.';" align="center" class="fontText2" colspan="2">&nbsp;</td>
                                            <td style="background-color:'.$corD.';" align="right" class="fontText2">&nbsp;</td>
                                            <td style="background-color:'.$corD.';" align="center" class="fontText2" colspan="3">&nbsp;</td>
                                        </tr>';
                        
                                        $n++;
                                    }

                                    $html.= '
                                   <tr class="titulo_ok6">
                                      <td align="left" class="fontText2" colspan="4">&nbsp;</td>                      
                                      <td align="center" class="fontText" colspan="2">'.$totf.'</td>                      
                                      <td align="center" class="fontText2" colspan="8">&nbsp;</td>
                                   </tr>       
                                </table><hr />';
                                }
}
                                $i++;

                              }
                              
                              $html.= '
                                    </td>
                                </tr>                                
                                <tr class="titulo_ok6">
                                    <td align="right" colspan="13" class="fontText2"><b>Total de Atividade(s) por Projeto : '.count($row2).'</b></td>
                                </tr>';

                            }

                            $html.= '
                            <tr class="titulo_ok4">
                                <td align="right" colspan="13" class="fontText2"><b>Total de Projetos por Município : '.count($row).'</b></td>
                            </tr>';
                         }

                         $html.= '</table></body></html>';
                         exit($html);                        
                         $dompdf->load_html($html);
                         $dompdf->render();
                         $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
		
		case "gerarXLSBases":

			$nome = antInjection($_POST["nome"]);

			$sql = "SELECT e.EMP_Descricao, b.BAS_Host, b.BAS_PAA FROM tb_empresas e ";
			$sql.= "INNER JOIN tb_bases_empresas b ON (b.EMP_IDEmpresa = e.EMP_IDEmpresa) WHERE e.EMP_Status = 'A' ";
			if (!empty($nome)) $sql.= " AND b.EMP_IDEmpresa = ".$nome;
			$sql.= " ORDER BY e.EMP_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização.');
						window.close();
					</script>";
				exit;	
			}

			$html = "	
	  		<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td align=\"left\" colspan='3'><b>SISPLAN - LISTAGEM DE BASES DE EMPRESAS - FIREBIRD</b></td>
			   </tr>   
			   <tr>
				 <td align='left'><b>Empresa</b></td>
				 <td align='left' width='300px'><b>Host</b></td>
				 <td align='left' width='100px'><b>PAA</b></td>
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

			   	  if ($l["BAS_PAA"] == "S") $l["BAS_PAA"] = "Sim"; else $l["BAS_PAA"] = "Não";

				  $html.= "
				  	<tr>
					  <td align='left'>".$l["EMP_Descricao"]."</td>
					  <td align='left'>".$l["BAS_Host"]."</td>
					  <td align='left'>".$l["BAS_PAA"]."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
				
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;		
		
		case "gerarXLSEnquetes":

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT e.*, c.CAT_Nome FROM tb_enquetes e ";
			$sql.= "INNER JOIN tb_categorias c ON (c.CAT_ID = e.CAT_ID) ";
			$sql.= "WHERE c.CAT_Status = 'A' ";
			if (!empty($nome)){
				$sql.= " AND (e.ENQ_Nome LIKE '%".$nome."%' OR e.ENQ_CPF LIKE '%".$nome."%' OR e.ENQ_Email LIKE '%".$nome."%'";
				$sql.= " OR e.ENQ_Frase LIKE '%".$nome."%'";
			}	
			if (!empty($status)) $sql.= " AND e.ENQ_Status = '".$status."' ";
			$sql.= " ORDER BY e.ENQ_Data";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = "	
			<style type='text/css'>
			
				.fontMenu {
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size:12px;
					font-weight:bold;
					font-style:normal;
					color:#000066;
					text-decoration:none;
				}
				
				.fontText {
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size:11px;
					font-weight:bold;
					color:#000066;
					font-style:normal;
					text-decoration:none;
				}
				
				.fontText2 {
					font-family:Verdana, Arial, Helvetica, sans-serif;
					font-size:11px;
					font-weight:normal;
					color:#000066;
					font-style:normal;
					text-decoration:none;
				}
			</style>
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td align=\"left\" colspan=\"3\" width=\"500px\" class=\"fontMenu\">CIPA - LISTAGEM DE FRASES</td>
			   </tr>   
			   <tr>
			   	 <td align='left' width='30px' class='fontText'>CPF</td>
				 <td align='left' width='180px' class='fontText'>FRASE</td>
				 <td align='left' width='100px' class='fontText'>DATA/HORA</td>				 
			   </tr>";

			   $i = 0;
			   foreach($row as $l){
				  
				  if($i % 2 == 0) $cor = "#CCCCCC"; else $cor = "#FFF";
				  
				  $html.= "
				  	<tr style='cursor:hand;background-color:".$cor.";'>
					  <td align='left' class='fontText2'>".$l["ENQ_CPF"]."</td>
					  <td align='left' class='fontText2'>".$l["ENQ_Frase"]."</td>
					  <td align='left' class='fontText2'>".$conv->desconverteData($l["ENQ_Data"])."/".substr($l["ENQ_Data"], 10, 6)."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";
                
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
	
		break;		
		
		case "gerarXLSAgente":

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sql = "SELECT * FROM tb_agentes_financeiros WHERE AGE_ID IS NOT NULL ";
			if (!empty($nome)) $sql.= " AND (AGE_IDFEBRABAN LIKE '%".$nome."%' OR AGE_Descricao LIKE '%".$nome."%') ";	
			if (!empty($status)) $sql.= " AND AGE_Status = '".$status."' ";				
			$sql.= " ORDER BY AGE_Descricao";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}

			$html = "
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan=\"4\" width=\"500px\" class=\"fontMenu\">SISPLAN - LISTAGEM DE AGENTE FINANCEIRO</td>
			   </tr>   
			   <tr>
			   	 <td align='left' class='fontText'><b>ID.</b></td>
				 <td align='left' class='fontText'><b>ID. FEBRABAN</b></td>
				 <td align='left' class='fontText'><b>DESCRIÇÃO</b></td>				 
				 <td align='left' class='fontText'><b>STATUS</b></td>				 
			   </tr>";

			   $i = 0;
			   foreach($row as $l){

				  if ($l["AGE_Status"] == "I") $l["AGE_Status"] = "Inativo"; else $l["AGE_Status"] = "Ativo";
				  
				  $html.= "
				  	<tr style='cursor:hand;background-color:".$cor.";'>
					  <td align='left' class='fontText2'>".$l["AGE_ID"]."</td>
					  <td align='left' class='fontText2'>".$l["AGE_IDFEBRABAN"]."</td>
					  <td align='left' class='fontText2'>".$l["AGE_Descricao"]."</td>
					  <td align='left' class='fontText2'>".$l["AGE_Status"]."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='4' align='right' class='fontText'>Total de Registro(s): ".count($row)."</td>
					</tr>
				</table>";

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
	
		break;		
        
		case "gerarXLSAtividadeNFE":
            
            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

        	$sql = "SELECT * FROM ATIVIDADE WHERE COD_ATIVIDADE <> 0 AND EMPRESA = '".$IDCONEXAONFE."' ";	
        	if (!empty($_GET["nome"])) $sql.= " AND ATIVIDADE LIKE '%".$_GET["nome"]."%' ";
            $sql.= " ORDER BY ATIVIDADE";
            $qry = ibase_query($res, $sql);

			$html = "
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan=\"2\" class=\"fontMenu\">SISPLAN - LISTAGEM ATIVIDADES (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</td>
			   </tr>   
			   <tr>
			   	 <td align='left' class='fontText'><b>ID.</b></td>
				 <td align='left' width='300px' class='fontText'><b>DESCRIÇÃO</b></td>				 
			   </tr>";

			   $i = 0;
			   while ($l = ibase_fetch_object($qry)){
				  
				  $html.= "
				  	<tr>
					  <td align='left' class='fontText2'>".completarComZero($l->COD_ATIVIDADE)."</td>
					  <td align='left' class='fontText2'>".$l->ATIVIDADE."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='2' align='right' class='fontText'>Total de Registro(s): ".$i."</td>
					</tr>
				</table>";
                
                if ($i == 0){
                    echo "
                        <script>
                            alert('Não há dados para visualização');
                            window.close();
                        </script>";
                    exit;
                    	
                }else{
    
                    $dompdf->load_html($html);
                    $dompdf->render();
                    $dompdf->stream("sisplan_".date("dmY_his").".pdf");
                }
		break;
        
		case "gerarXLSGruposProd":

            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

        	$sql = "SELECT * FROM GRUPO_PROD WHERE COD_GRUPO <> 0 AND EMPRESA = '".$IDCONEXAONFE."' ";	
        	if (!empty($_GET["nome"])) $sql.= " AND DESCRICAO LIKE '%".$_GET["nome"]."%' ";
            $sql.= " ORDER BY DESCRICAO";
            $qry = ibase_query($res, $sql);

			$html = "
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
			   <tr>
			     <td align=\"left\" colspan=\"3\" width=\"500px\" class=\"fontMenu\">SISPLAN - LISTAGEM GRUPOS DE PRODUTO (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</td>
			   </tr>   
			   <tr>
			   	 <td align='left' class='fontText'><b>ID.</b></td>
				 <td align='left' width='300px' class='fontText'><b>DESCRIÇÃO</b></td>				 
			   </tr>";

			   $i = 0;
			   while ($l = ibase_fetch_object($qry)){
				  
				  $html.= "
				  	<tr>
					  <td align='left' class='fontText2'>".completarComZero($l->COD_GRUPO)."</td>
					  <td align='left' class='fontText2'>".$l->DESCRICAO."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='2' align='right' class='fontText'>Total de Registro(s): ".$i."</td>
					</tr>
				</table>";
                
                if ($i == 0){
                    echo "
                        <script>
                            alert('Não há dados para visualização');
                            window.close();
                        </script>";
                    exit;
                    	
                }else{
    
                    $dompdf->load_html($html);
                    $dompdf->render();
                    $dompdf->stream("sisplan_".date("dmY_his").".pdf");
                }
		break;
        
		case "gerarXLSSubGruposProd":

            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

        	$sql = "SELECT s.*, g.DESCRICAO AS GRUPO FROM SUBGRUPO_PROD s ";
            $sql.= "INNER JOIN GRUPO_PROD g ON (g.COD_GRUPO = s.COD_GRUPO) ";
            $sql.= "WHERE s.COD_SUBGRUPO <> 0 AND s.EMPRESA = '".$IDCONEXAONFE."' ";	
        	if (!empty($_GET["nome"])) $sql.= " AND s.DESCRICAO LIKE '%".$_GET["nome"]."%' ";
            $sql.= " ORDER BY g.DESCRICAO, s.DESCRICAO";
            $qry = ibase_query($res, $sql);

			$html = "
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='4'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>		   
			   <tr>
			     <td align=\"left\" colspan=\"4\" class=\"fontMenu\">SISPLAN - LISTAGEM SUB-GRUPOS DE PRODUTO (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</td>
			   </tr>   
			   <tr>
			   	 <td align='left' class='fontText'><b>ID.</b></td>
                 <td align='left' width='300px' class='fontText'><b>GRUPO</b></td>
				 <td align='left' width='300px' class='fontText'><b>DESCRIÇÃO</b></td>
                 <td align='left' width='100px' class='fontText'><b>MARKUP</b></td>				 
			   </tr>";

			   $i = 0;
			   while ($l = ibase_fetch_object($qry)){
				  
				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l->COD_SUBGRUPO)."</td>
                      <td align='left'>".$l->GRUPO."</td>
					  <td align='left'>".$l->DESCRICAO."</td>
                      <td align='left'>".organiza_moeda($l->MARKUP)."</td>
					</tr>";
					$i++;
				}

				$html.= "
					<tr>
					  <td colspan='4' align='right' class='fontText'>Total de Registro(s): ".$i."</td>
					</tr>
				</table>";

                if ($i == 0){
                    echo "
                        <script>
                            alert('Não há dados para visualização');
                            window.close();
                        </script>";
                    exit;
                    	
                }else{

                    $dompdf->load_html($html);
                    $dompdf->render();
                    $dompdf->stream("sisplan_".date("dmY_his").".pdf");
    	
                }
		break;
        
		case "gerarXLSLinhaProd":
            
            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$nome   = antInjection($_POST["nome"]);

            $sql = "SELECT l.*, s.DESCRICAO AS SUBGRP FROM LINHA_PROD l ";
            $sql.= "INNER JOIN SUBGRUPO_PROD s ON (s.COD_SUBGRUPO = l.COD_SUBGRUPO) ";
            $sql.= "WHERE l.COD_LINHA <> 0 AND l.EMPRESA = '".$IDCONEXAONFE."' ";	
        	if (!empty($nome)) $sql.= " AND l.DESCRICAO LIKE '%".strtoupper($nome)."%' ".$order;
            $sql.= " ORDER BY s.DESCRICAO, l.DESCRICAO";
            $qry = ibase_query($res, $sql);

			$html = "
			<table width='763px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
			     <td align=\"center\" colspan='3'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>  
			   <tr>
			     <td align=\"left\" colspan='3'>SISPLAN - LISTAGEM LINHA DE PRODUTO (".strtoupper($rowBASE[0]["EMP_Descricao"]).")</td>
			   </tr>   
			   <tr>
			   	 <td align='left' class='fontText'><b>ID.</b></td>
                 <td align='left' width='300px' class='fontText'><b>SUB-GRUPO</b></td>
				 <td align='left' width='300px' class='fontText'><b>DESCRIÇÃO</b></td>				 
			   </tr>";

			   $i = 0;
			   while ($l = ibase_fetch_object($qry)){
				  
				  $html.= "
				  	<tr>
					  <td align='left'>".completarComZero($l->COD_LINHA)."</td>
                      <td align='left'>".$l->SUBGRP."</td>
					  <td align='left'>".$l->DESCRICAO."</td>
					</tr>";
					$i++;

				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'>Total de Registro(s): ".$i."</td>
					</tr>
				</table>";
                
                if ($i == 0){
                    echo "
                        <script>
                            alert('Não há dados para visualização');
                            window.close();
                        </script>";
                    exit;
                    	
                }else{

                    $dompdf->load_html($html);
                    $dompdf->render();
                    $dompdf->stream("sisplan_".date("dmY_his").".pdf");
                    
                }
		break;
        
		case "gerarXLSAcompanhamentoEnvio":

            $ano  = antInjection($_POST["ano"]);
            $mun  = antInjection($_POST["muni"]);
			$gere = antInjection($_POST["gere"]);
            $mes  = antInjection($_POST["mes"]);
            $mes2 = antInjection($_POST["mes2"]);

            $html    = "";
            $filtro  = "";

            /* MONTANDO OS DADOS DO FILTRO */
            $filtro.= "<b>DADOS DO FILTRO:</b><br>";
            $filtro.= "<b>Ano Competência:</b> ".$ano."<br>";
            if (!empty($mun)){

                $sqlF = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                $rowF = $banco->listarArray($sqlF);

                $filtro.= "<b>Município:</b> ".$rowF[0]["MUN_Descricao"]."<br>";

            }else{
                
                if (!empty($gere)){

                    $sqlF = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$gere."'";                    
                    $rowF = $banco->listarArray($sqlF);
                    
                    $filtro.= "<b>Gerência Regional:</b> ".$rowF[0]["REG_Descricao"]."<br>";
                }
            }
            $filtro.= "<b>Período Semana:</b> de Semana ".$mes." até Semana ".$mes2;
            /**/
            $titulo = "Listagem de Lançamentos Plano Anual (PDF)";

            //MONTA ARRAY DE SEMANA...
            $b = 0;
            for($i=$mes;$i<=$mes2;$i++){
                $lista[$i] = $i;
                $b++;
            }
            
            $b++;
//            exit($b.' teste');

            $sql = "SELECT MUN_IDMunicipio, MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio IS NOT NULL ";
            if (!empty($mun)){
                $sql.= " AND MUN_IDMunicipio = '".$mun."' ";        
            }else{
                if (!empty($gere)) $sql.= " AND REG_ID = '".$gere."' ";
            }
            $sql.= "GROUP BY MUN_IDMunicipio, MUN_Descricao ORDER BY MUN_Descricao";
            $qry = $banco->executarQuery($sql);
            $tot = $banco->totalLinhas($qry);

            $html.= '<html>'.$css.'
            <body>
            <table width="750px" border="0" cellpadding="1" cellspacing="1" align="center">
			    <tr>
                    <td align=\'left\' colspan='.$b.'>
                        <img src=\'../img/logo_cliente.jpg\' border=\'0\' width="750px">
                    </td>
			    </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fontText2" align=\"center\" colspan='.$b.'>'.$filtro.'</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"  class="menuTitle" colspan='.$b.'>'.$titulo.'</td>
                </tr>
                <tr>  
                  <td width="250px" align="left" class="fontText">Municipio</td>';
                     
                    foreach($lista as $s){
                        $html.= "<td align=\"center\" class=\"fontText\" width=\"120px\">Sem. ".$s."</td>";
                    }
                  $html.= "
                  <td align='center' class='fontText'>Ano</td>
                </tr>";
                
                $i = $mes;
                while($l = $banco->criaArray($qry)){

                    if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                    
                    $html.= "
                        <tr>
                          <td align=\"left\" class=\"fontText2\" style=\"background-color:".$cor.";\">".$l["MUN_Descricao"]."</td>";
            
                    $x = $mes;
                    
                    foreach($lista as $s){
                        
                        $sql2 = "SELECT pe.MUN_IDMunicipio FROM tb_plano_execucao pe ";
                        $sql2.= "INNER JOIN tb_municipios m ON m.MUN_IDMunicipio = pe.MUN_IDMunicipio ";
                        $sql2.= "WHERE pe.PLE_Semana = '".$lista[$x]."' AND pe.PLE_Ano= '".$ano."' ";
                        if (!empty($mun)){
                            $sql2.= " AND pe.MUN_IDMunicipio = '".$mun."' ";        
                        }else{
                            if (!empty($gere)) $sql2.= " AND m.REG_ID = '".$gere."' ";
                            $sql2.= " AND pe.MUN_IDMunicipio = '".$l["MUN_IDMunicipio"]."' ";
                        }
                        $sql2.= "GROUP BY pe.PLE_Ano, ";
                        if (!empty($mun)) $sql2.= "pe.MUN_IDMunicipio "; else $sql2.= " m.REG_ID ";
                        $sql2.= " ORDER BY m.MUN_Descricao";
                        $row2 = $banco->listarArray($sql2);

                        if (count($row2) > 0){
                            $html.= "<td align=\"center\" class=\"fontText2\" width=\"70px\" style=\"background-color:".$cor.";\">Enviado</td>";
                        }else{
                            $html.= "<td align=\"center\" class=\"fontText2\" width=\"70px\" style=\"background-color:".$cor.";\">Não Enviado</td>";
                        }
                        $x++;
            
                    }
                    $html.= "
                        <td align=\"center\" class=\"fontText2\" style=\"background-color:".$cor.";\" width=\"60px\">".$ano."</td>
                    </tr>";
                    $i++;
                 }
                 $html.= "
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan='".$b."' class='menuTitle' align='right'>Total de Municípios: ".$tot."</td>
                    </tr>                 
                 </table></body></html>";
                 exit($html);

                 $dompdf->load_html($html);
                 $dompdf->render();
                 $dompdf->stream("sisplan_".date("dmY_his").".pdf");
		break;

		case "gerarPDFCadastros":

			$row = $banco->listarArray($_SESSION["sSQLFAM"]);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
			
			$html = "<html><body onLoad='javascript: print();'>".$css."
	  		<table width='600px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td class=\"fontText2\" colspan=\"2\" align=\"left\">".$_SESSION["sSQLFILTRO"]."</td>
               </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td> 
			     <td align=\"left\" colspan='3' class='menuTitle'><b>SISPLAN - LISTAGEM DE FAMÍLIAS</b></td>
			   </tr>   
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
				 <td align='left' class='fontText' width='50px'><b>CPF</b></td>
				 <td align='left' class='fontText' width='500px'><b>Nome</b></td>	
			   </tr>";
			
			   $i = 0;
			   foreach($row as $l){

                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                    
				  $html.= "
				  	<tr>
                      <td style='background-color:".$cor.";' class='fontText2' align='left'></td>   
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["CPF"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["NOME"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>
                </body>
                </html>";
                
                /*
                echo $html;
                
                echo "
				<script>
                    window.opener.parent.location = '../lib/Fachada.php?acao=".base64_encode("filtrarRDE")."&mod=MTQ=';
				</script>";                
                exit;
                */

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
        
        case "gerarPDFVeiculos":
        
			$nome   = antInjection($_POST["nome"]);
            $idmuni = antInjection($_POST["idmuni"]);
            $status = antInjection($_POST["status"]);
            $ano    = antInjection($_POST["ano"]);
            $xips   = antInjection($_POST["xips"]);

        	$sql = "SELECT v.*, m.MUN_Descricao FROM tb_veiculos v ";
            $sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = v.IDMUNICIPIO) ";
            $sql.= "WHERE v.IDMUNICIPIO IS NOT NULL ";	
        	if (!empty($nome)){ 
        	   $sql.= " AND (v.PLACA LIKE '%".$nome."%' OR v.DESCRICAO LIKE '%".$nome."%' OR v.ANO LIKE '%".$nome."%' ";
               $sql.= " OR v.OBSERVACAO LIKE '%".$nome."%') ";       
        	}
        	if (!empty($status)) $sql.= " AND v.STATUS = '".$status."' ";
            if (!empty($idmuni)){ 
                $sql.= " AND v.IDMUNICIPIO = '".$idmuni."' ";
            }else{
                $sql.= " and m.REG_ID = '".$_SESSION["sIDRegional"]."' ";
            }
            if (!empty($ano)) $sql.= " AND v.ANO = '".$ano."' ";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
            
            /* MONTANDO OS DADOS DO FILTRO */
            $filtro = "";
            if (empty($ano)) $ano = "TODOS";
            if (!empty($idmuni)){
                $sql4 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$idmuni."'";
                $row4 = $banco->listarArray($sql4);
                
                $idmuni = $row4[0]["MUN_Descricao"];   
            }else{
                $idmuni = "TODOS";
            }                             
            
            $filtro.= "<b>DADOS DO FILTRO:</b><br>";
            $filtro.= "<b>Ano Competência:</b> ".$ano."<br>";            
            $filtro.= "<b>Município:</b> ".$idmuni;
            
            if ($xips == "2"){
                
                $sql33 = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$_SESSION["sIDRegional"]."'";
                $row33 = $banco->listarArray($sql33);
                
                $filtro.= " (".$row33[0]["REG_Descricao"].")";
                
            }
            $filtro.= "<br>";
            
            if (!empty($nome)){
                $filtro.= "<b>Placa, Descrição, Obs. ou Responsável:</b> ".$nome."<br>";    
            }
			
			$html = $css."
	  		<table width='400px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
			     <td align=\"center\" colspan='7'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td class=\"fontText2\" colspan=\"7\" align=\"left\">".$filtro."</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td> 
			     <td align=\"left\" colspan='7' class='menuTitle'><b>SISPLAN - LISTAGEM DE VÉICULOS</b></td>
			   </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
				 <td align='left' class='fontText' width='8%'><b>ID.</b></td>
                 <td align='left' class='fontText' width='30%'><b>Município</b></td>
				 <td align='left' class='fontText' width='8%'><b>Placa</b></td>
				 <td align='left' class='fontText' width='20%'><b>Descrição</b></td>
				 <td align='center' class='fontText' width='5%'><b>Ano</b></td>
                 <td align='left' class='fontText' width='40%'><b>Responsável</b></td>
				 <td align='center' class='fontText' width='8%'><b>Status</b></td>	
			   </tr>";
			
			   $i = 0;
			   foreach($row as $l){

                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                  
        		  if ($l["STATUS"] == "A") $l["STATUS"] = "Ativo"; else $l["STATUS"] = "Inativo"; 
                  
                    
				  $html.= "
				  	<tr>
                      <td style='background-color:".$cor.";' class='fontText2' align='left'></td>   
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".completarComZero($l["IDVEICULO"], 5)."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["MUN_Descricao"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["PLACA"]."</td>
                      <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["DESCRICAO"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='center'>".$l["ANO"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["RESPONSAVEL"]."</td>
                      <td style='background-color:".$cor.";' class='fontText2' align='center'>".$l["STATUS"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
					<tr>
					  <td colspan='8' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
                
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

        break;
        
        case "gerarPDFMovVeiculos":
        
			$nome   = antInjection($_POST["nome"]);
            $xips   = antInjection($_POST["xips"]);
            $status = antInjection($_POST["status"]);
            $ano    = antInjection($_POST["ano"]);
            $usu    = antInjection($_POST["usu"]);
            $dtini  = antInjection($_POST["dtini"]);
            $dtfim  = antInjection($_POST["dtfim"]);
            
        	$sql = "SELECT t.*, m.MUN_Descricao, u.USU_Login FROM tb_municipios m ";
            $sql.= "INNER JOIN tb_veiculos v ON (v.IDMUNICIPIO = m.MUN_IDMunicipio) ";
            $sql.= "INNER JOIN tb_veiculos_mov t ON (t.PLACA = v.PLACA) ";
            $sql.= "INNER JOIN tb_usuarios u ON (u.USU_IDUsuario = t.IDUSUARIO) ";
            $sql.= "WHERE t.PLACA IS NOT NULL ";	
        	if (!empty($nome)){ 
        	   $sql.= " AND (t.PLACA LIKE '%".$nome."%' OR t.SERVICOS_DESC LIKE '%".$nome."%') ";       
        	}
            if ($xips == "2") $sql.= " AND m.REG_ID = '".$_SESSION["sIDRegional"]."' ";        
            if (!empty($status)) $sql.= " AND t.STATUS = '".$status."' ";
            if (!empty($idmuni)) $sql.= " AND v.IDMUNICIPIO = '".$idmuni."' ";
        	if (!empty($usu)) $sql.= " AND t.IDUSUARIO = '".$usu."' ";
        
            if ( (!empty($dtini)) && (!empty($dtfim)) ){
                $sql.= " AND t.DATA BETWEEN '".$conv->conData($dtini)."' AND '".$conv->conData($dtfim)."' ";
            }
            
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
            
            /* MONTANDO OS DADOS DO FILTRO */
            $filtro = "";
            if (empty($ano)) $ano = "TODOS";
            if (!empty($idmuni)){
                $sql4 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$idmuni."'";
                $row4 = $banco->listarArray($sql4);
                
                $idmuni = $row4[0]["MUN_Descricao"];   
            }else{
                $idmuni = "TODOS";
            }                             
            
            $filtro.= "<b>DADOS DO FILTRO:</b><br>";
            $filtro.= "<b>Ano Competência:</b> ".$ano."<br>";            
            $filtro.= "<b>Município:</b> ".$idmuni."<br>";
            if (!empty($nome)){
                $filtro.= "<b>Placa, Descrição, Obs. ou Responsável:</b> ".$nome."<br>";    
            }
			
			$html = $css."
	  		<table width='400px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
			     <td align=\"center\" colspan='7'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td class=\"fontText2\" colspan=\"7\" align=\"left\">".$filtro."</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td> 
			     <td align=\"left\" colspan='8' class='menuTitle'><b>SISPLAN - LISTAGEM DE MOVIMENTOS DOS VÉICULOS</b></td>
			   </tr>
			   <tr>
                 <td class='fontText' align='center'>&nbsp;</td>
                 <td class='fontText' align='left' width='6%'>ID.</td>
                 <td class='fontText' align='left'>Município</td>
                 <td class='fontText' align='center' width='8%'>Placa</td>
                 <td class='fontText' align='center' width='8%'>Data</td>
                 <td class='fontText' align='center'>KM</td>    
                 <td class='fontText' align='center'>LT</td>
                 <td class='fontText' align='right' width='15%'>R$ Combustível</td>	
                 <td class='fontText' align='center'>Status</td>	
			   </tr>";
			
			   $i    = 0;
               $soma = 0;
			   foreach($row as $l){

                   $soma+= $l["COMBUSTIVEL_RS"];

                   if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                   if ($l["STATUS"] == "A") $l["STATUS"] = "Ativo"; else $l["STATUS"] = "Inativo";
                    
				  $html.= "
				  	<tr>
                        <td style='background-color:".$cor.";' align='center'>&nbsp;</td>
        				<td style='background-color:".$cor.";' align='left' class='fontText2'>".completarComZero($l["IDVEICULOMOV"])."</td>
                        <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MUN_Descricao"]."</td>
        				<td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["PLACA"]."</td>
                        <td style='background-color:".$cor.";' align='center' class='fontText2'>".$conv->desconverteData($l["DATA"])."</td>
                        <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["KILOMETROINI"]." / ".$l["KILOMETROFIN"]."</td>
                        <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["COMBUSTIVEL_LT"]."</td>
                        <td style='background-color:".$cor.";' align='right' class='fontText2'>".organiza_moeda($l["COMBUSTIVEL_RS"])."</td>
                        <td style='background-color:".$cor.";' align='center' class='fontText2'>".$l["STATUS"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
            		<tr>
                      <td align='right' colspan='7' class='titulo_ok6'>&nbsp;</td>  
            		  <td align='right' class='titulo_ok6'>".organiza_moeda($soma)."</td>
                      <td align='right' class='titulo_ok6'>&nbsp;</td>
            		</tr>
					<tr>
					  <td colspan='9' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";

//                exit($html);
                
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

        break;
        
        case "gerarPDFTecnicos":

			$tipo  = antInjection(base64_decode($_GET["tipo"]));
            $flag  = antInjection(base64_decode($_GET["flag"]));
            $mun   = antInjection(base64_decode($_GET["mun"]));

        	$sql = "SELECT u.USU_Nome, u.USU_Login, u.USU_Email, m.MUN_Descricao, e.EMP_Descricao FROM tb_usuarios u ";
            $sql.= "INNER JOIN tb_mod_usuarios mu ON (mu.USU_IDUsuario = u.USU_IDUsuario) ";
            $sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = u.MUN_IDMunicipio) ";
            $sql.= "INNER JOIN tb_empresas e ON (e.EMP_IDEmpresa = u.EMP_IDEmpresa) ";
            $sql.= "WHERE mu.MOD_ID = '9' AND u.USU_Status = 'A' ";
            //montando o filtro...    
            if (!empty($mun)) $sql.= " AND m.MUN_IDMunicipio = '".$mun."' ";
            if (!empty($tipo)){
        
                if ($flag == "RD"){
                    $sql.= " AND m.RDE_ID = '".$tipo."' ";    
                }else{
                    $sql.= " AND m.REG_ID = '".$tipo."' ";
                }
            }
            $sql.= " ORDER BY u.USU_Nome";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
            
            /* MONTANDO OS DADOS DO FILTRO */
            $filtro = "";
            $filtro.= "<b>DADOS DO FILTRO:</b><br>";
            if (!empty($tipo)){

                if ($flag == "RD"){
                    $sql3 = "SELECT RDE_Descricao FROM tb_regiaodesen WHERE RDE_ID = '".$tipo."'";
                    $row3 = $banco->listarArray($sql3);
                    
                    $filtro.= "<b>Região de Desenvolvimento:</b> ".$row3[0]["RDE_Descricao"]."<br>";     
                }else{
                    $sql3 = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$tipo."'";
                    $row3 = $banco->listarArray($sql3);
                    
                    $filtro.= "<b>Gerência Regional:</b> ".$row3[0]["REG_Descricao"]."<br>";
                }
            }
            
            if (!empty($mun)){
                $sql4 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$mun."'";
                $row4 = $banco->listarArray($sql4);
                
                $mun = $row4[0]["MUN_Descricao"];   
            }else{
                $mun = "TODOS";
            }           

            $filtro.= "<b>Município:</b> ".$mun."<br>";

            if (!empty($nome)){
                $filtro.= "<b>Placa, Descrição, Obs. ou Responsável:</b> ".$nome."<br>";    
            }

			$html = $css."
	  		<table width='400px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
			     <td align=\"center\" colspan='5'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td class=\"fontText2\" colspan=\"5\" align=\"left\">".$filtro."</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td> 
			     <td align=\"left\" colspan='5' class='menuTitle'><b>SISPLAN - LISTAGEM DE MOVIMENTOS DOS VÉICULOS</b></td>
			   </tr>
			   <tr>
                 <td class='fontText' align='left'>&nbsp;</td>
                 <td class='fontText' align='left'>Nome</td>
                 <td class='fontText' align='left'>Login</td>
                 <td class='fontText' align='left'>E-mail</td>
                 <td class='fontText' align='left'>Município</td>
                 <td class='fontText' align='left' width='30%'>Unidade</td>	
			   </tr>";
			
			   $i = 0;
			   foreach($row as $l){

                   if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                    
				   $html.= "
				  	<tr>
                        <td style='background-color:".$cor.";' align='left'>&nbsp;</td>
        				<td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["USU_Nome"]."</td>
                        <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["USU_Login"]."</td>
                        <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["USU_Email"]."&nbsp;</td>
                        <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["MUN_Descricao"]."</td>
                        <td style='background-color:".$cor.";' align='left' class='fontText2'>".$l["EMP_Descricao"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
					<tr>
					  <td colspan='6' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>";
                
                exit($html);
                
                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;
        
		case "gerarPDFCadastrosTotal":
            
			$row = $banco->listarArray($_SESSION["sSQLFAMTOTAL"]);			
			if (count($row) == 0){
				echo "
					<script>
						alert('Não há dados para visualização');
						window.close();
					</script>";
				exit;	
			}
			
			$html = "<html><body onLoad='javascript: print();'>".$css."
	  		<table width='600px' border='0' cellpadding='0' cellspacing='0' align='center'>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
			     <td align=\"center\" colspan='2'>
			       <img src='../img/logo_cliente.jpg' border='0'>
			     </td>
			   </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td class=\"fontText2\" colspan=\"2\" align=\"left\">".$_SESSION["sSQLFILTROTOTAL"]."</td>
               </tr>
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td> 
			     <td align=\"left\" colspan='3' class='menuTitle'><b>SISPLAN - LISTAGEM DE FAMÍLIAS</b></td>
			   </tr>   
			   <tr>
                 <td align='left' class='fontText'>&nbsp;</td>
				 <td align='left' class='fontText' width='50px'><b>CPF</b></td>
				 <td align='left' class='fontText' width='500px'><b>Nome</b></td>	
			   </tr>";
			
			   $i = 0;
			   foreach($row as $l){

                  if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
                    
				  $html.= "
				  	<tr>
                      <td style='background-color:".$cor.";' class='fontText2' align='left'></td>   
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["CPF"]."</td>
					  <td style='background-color:".$cor.";' class='fontText2' align='left'>".$l["NOME"]."</td>
					</tr>";

				  $i++;
				}

				$html.= "
					<tr>
					  <td colspan='3' align='right' class='fontText'><b>Total de Registro(s): ".count($row)."</b></td>
					</tr>
				</table>
                </body>
                </html>";

                //echo $html; exit;

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");

		break;

        case "gerarPDFFamiliaRDE":
            
            $gere = antInjection($_POST["gere"]);
            $muni = antInjection($_POST["muni"]);
            $mes  = antInjection($_POST["mes"]);
            $mes2 = antInjection($_POST["mes2"]);
            $pesq = antInjection($_POST["pesq"]);
            $proj = antInjection($_POST["proj"]);
            $ano  = antInjection($_POST["ano"]);
            $meta = $_POST["meta"];

            $titulo = "Relatório Diário do Extensionista (RDE)";
            $html   = "";
            $filtro = "<b>DADOS DO FILTRO:</b><br>";

            $sql = "SELECT DISTINCT(c.CPF) AS CPF, c.NOME, m.MUN_Descricao FROM tb_plano_execucao e ";
            $sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
            $sql.= "INNER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND c.IDMUNICIPIO = e.MUN_IDMunicipio) ";
            $sql.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = e.PRJ_IDProjeto) ";
            $sql.= "INNER JOIN tb_atividades a ON (a.ATV_IDAtividade = e.ATV_IDAtividade) ";
            $sql.= "INNER JOIN tb_unidades u ON (u.UND_IDUnidade = a.UND_IDUnidade) ";
            // monta filtro...
            $sql.= "WHERE e.PLE_Ano = '".$ano."' AND a.ATV_Tipoficha <> 'S' ";
            
            $filtro.= "<b>Ano Competência:</b> ".$ano."<br>";
            
            if (!empty($muni)){
                $sql.= " AND e.MUN_IDMunicipio = '".$muni."' ";
                
                $sql11 = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = '".$muni."'";
                $row11 = $banco->listarArray($sql11);
                
                $filtro.= "<b>Município:</b> ".$row11[0]["MUN_Descricao"]."<br>";
                    
            }else{
                if (!empty($gere)){ 
                    $sql.= " AND m.RDE_ID = '".$gere."' ";
                    
                    $sql11 = "SELECT REG_Descricao FROM tb_regional WHERE REG_ID = '".$gere."'";
                    $row11 = $banco->listarArray($sql11);
                    
                    $filtro.= "<b>Gerência Regional:</b> ".$row11[0]["REG_Descricao"]."<br>";
                    
                }else{
                    $filtro.= "<b>Gerência Regional:</b> TODOS<br>";
                }
            }
            if (isset($meta)){
                $sql.= " AND p.PRJ_Opcao = 'S' ";
                $filtro.= "<b>Meta Prioritária:</b> SIM<br>";
                
            }
            if (!empty($proj)){
                $sql.= " AND p.PRJ_IDProjeto = '".$proj."' ";
                
                $sql11 = "SELECT PRJ_Descricao FROM tb_projetos WHERE PRJ_IDProjeto = '".$proj."'";
                $row11 = $banco->listarArray($sql11);

                $filtro.= "<b>Projeto:</b> ".$row11[0]["PRJ_Descricao"]."<br>";
            }

            if (!empty($pesq)){
                $sql.= " AND (c.NOME LIKE '%".$pesq."%' OR c.CPF LIKE '%".$pesq."%') ";
                
                $filtro.= "<b>Nome ou CPF:</b> ".$pesq."<br>";                
            }

            if ( (!empty($mes)) && (!empty($mes2)) ){
                $sql.= " AND e.PLE_Data BETWEEN '".$conv->conData($mes)."' AND '".$conv->conData($mes2)."' ";
                
                $filtro.= "<b>Período:</b> ".$mes." até ".$mes2."<br>";
            }
            $sql.= " ORDER BY c.NOME";
//            exit($sql);
            $row = $banco->listarArray($sql);
            if (count($row) == 0){
        
                echo "
                <script>
                    alert('Não há dados para visualização.');
                    window.close();
                </script>";

            }else{
                
                $html.= $css.'
                  <table width="760px" border="0" cellpadding="1" cellspacing="1" align="center">
    			    <tr>
    			      <td align="center" colspan="5">
    			        <img src="../img/logo_cliente.jpg" border="0">
    			      </td>
    			    </tr>
                    <tr>
                     <td class="fontText2" colspan="5" align="left">'.$filtro.'</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="5" align="left" class="menuTitle">'.$titulo.'</td>
                    </tr>
                    <tr>
                        <td class="fontText" align="left">CPF</td>
                        <td class="fontText" align="left">NOME</td>
                        <td class="fontText" align="left" colspan="3">&nbsp;</td>
                    </tr>';
        
        	$i = 0;
            $conta = 0;    
        	foreach($row as $l){
        
                    if($i % 2 == 0) $cor = "#EEE"; else $cor = "#FFF";
        
                    $html.= '
                        <tr>
                          <td style="background-color:'.$cor.';" align="left" width="18%" class="fontText">'.$l["CPF"].'</td>
                          <td style="background-color:'.$cor.';" align="left" class="fontText" width="80%" colspan="2">'.$l["NOME"].' ('.$l["MUN_Descricao"].')</td>
                          <td style="background-color:'.$cor.';" class="fontText" align="center" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="left" width="65%" class="fontText" colspan="3">DESCRIÇÃO</td>
                          <td class="fontText" align="center" colspan="2">&nbsp;</td>
                        </tr>';
        
                    $sql2 = "SELECT DISTINCT(p.PRJ_IDProjeto) AS PRJ_IDProjeto, p.PRJ_Descricao, p.PRJ_Opcao FROM tb_plano_execucao e ";
                    $sql2.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
                    $sql2.= "INNER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND c.IDMUNICIPIO = e.MUN_IDMunicipio) ";
                    $sql2.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = e.PRJ_IDProjeto) ";
                    $sql2.= "INNER JOIN tb_atividades a ON (a.ATV_IDAtividade = e.ATV_IDAtividade) ";
                    // monta filtro...
                    $sql2.= "WHERE c.CPF = '".$l["CPF"]."' AND e.PLE_Ano = '".$ano."' AND a.ATV_Tipoficha <> 'S' ";    
                    if (!empty($muni)){
                        $sql2.= " AND e.MUN_IDMunicipio = '".$muni."' ";    
                    }else{
                        if (!empty($gere)) $sql2.= " AND m.RDE_ID = '".$gere."' ";        
                    }
                    if (isset($meta)) $sql2.= " AND p.PRJ_Opcao = 'S' ";
                    if (!empty($proj)) $sql2.= " AND p.PRJ_IDProjeto = '".$proj."' ";
                    if ( (!empty($mes)) && (!empty($mes2)) ){
                        $sql2.= " AND e.PLE_Data BETWEEN '".$conv->conData($mes)."' AND '".$conv->conData($mes2)."' ";
                    }
                    $sql2.= " ORDER BY p.PRJ_Descricao";
                    //exit($sql2);
                    $row2 = $banco->listarArray($sql2);
                    $a = 0;
                    foreach($row2 as $l2){	
        
                        if($a % 2 == 0) $corI = "#EEE"; else $corI = "#FFF";
                        
                        $metaobg = "";
                        if ($l2["PRJ_Opcao"] == "S") $metaobg = "<span class='fontText'>(Meta Prioritária)</span>";                
                        
                        $html.= '                
                        <tr>
                            <td style="background-color:'.$corI.';" align="left" class="fontText" colspan="6">'.$l2["PRJ_Descricao"].' '.$metaobg.'</td>                                        
                        </tr>';
                        $a++;

            			// LISTA ATIVIDADES DO PROJETO ACIMA...
                        $sqlD = "SELECT DISTINCT(a.ATV_IDAtividade) AS ATV_IDAtividade, a.ATV_Descricao, e.PLE_Data, u.UND_Descricao, SUM(e.PLE_Qtd) AS PLE_Qtd FROM tb_plano_execucao e ";
                        $sqlD.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = e.MUN_IDMunicipio) ";
                        $sqlD.= "INNER JOIN tb_cadastro c ON (c.IDCADASTRO = e.FAM_IDFamilia AND c.IDMUNICIPIO = e.MUN_IDMunicipio) ";
                        $sqlD.= "INNER JOIN tb_projetos p ON (p.PRJ_IDProjeto = e.PRJ_IDProjeto) ";
                        $sqlD.= "INNER JOIN tb_atividades a ON (a.ATV_IDAtividade = e.ATV_IDAtividade) ";
                        $sqlD.= "INNER JOIN tb_unidades u ON (u.UND_IDUnidade = a.UND_IDUnidade) "; 
                        // monta filtro...
                        $sqlD.= "WHERE c.CPF = '".$l["CPF"]."'  AND e.PLE_Ano = '".$ano."' AND e.PRJ_IDProjeto = '".$l2["PRJ_IDProjeto"]."' ";
                        $sqlD.= "AND a.ATV_Tipoficha <> 'S' ";    
                        if (!empty($muni)){
                            $sqlD.= " AND e.MUN_IDMunicipio = '".$muni."' ";    
                        }else{
                            if (!empty($gere)) $sqlD.= " AND m.RDE_ID = '".$gere."' ";        
                        }
                        if (isset($meta)) $sqlD.= " AND p.PRJ_Opcao = 'S' ";
                        if (!empty($pesq)) $sqlD.= " AND (c.NOME LIKE '%".$pesq."%' OR c.CPF LIKE '%".$pesq."%') ";
                        if ( (!empty($mes)) && (!empty($mes2)) ){
                            $sqlD.= " AND e.PLE_Data BETWEEN '".$conv->conData($mes)."' AND '".$conv->conData($mes2)."' ";
                        }
                        $sqlD.= " GROUP BY a.ATV_IDAtividade ORDER BY a.ATV_Descricao";
        //                exit($sqlD);
                        $rowD = $banco->listarArray($sqlD);
        
                        $html.= '
                        <tr>
                          <td align="left" class="fontText">ID.</td>
                          <td align="left" width="65%" class="fontText">DESCRIÇÃO</td>                          
                          <td align="center" class="fontText" width="5%">UND.</td>
                          <td align="right" class="fontText" width="15%">QTD.</td>
                          <td align="center" class="fontText">DATA</td>
                        </tr>';
                        $p = 0;
                        foreach($rowD as $d){
                                
                            if($p % 2 == 0) $corP = "#EEE"; else $corP = "#FFF";
        
                            $html.= '
                            <tr>
                              <td style="background-color:'.$corP.';" align="left" class="fontText2">'.completarComZero($d["ATV_IDAtividade"]).'</td>
                              <td style="background-color:'.$corP.';" align="left" width="65%" class="fontText2">'.$d["ATV_Descricao"].'</td>
                              <td style="background-color:'.$corP.';" align="center" class="fontText2">'.$d["UND_Descricao"].'</td>                                                
                              <td style="background-color:'.$corP.';" align="right" class="fontText2">'.organiza_moeda($d["PLE_Qtd"]).'</td>        
                              <td style="background-color:'.$corP.';" align="center" class="fontText2">'.$conv->desconverteData($d["PLE_Data"]).'</td>                      
                            </tr>';
                            
                            $p++;
                        }
                        unset($rowD);
                        
                       $html.= '                 
                       <tr>                      
                         <td align="right" class="fontText" colspan="5">Total de Atividade(s) por Projeto: '.$p.'</td>                 
                       </tr>'; 
                    }
                    unset($row2);
                    $html.= '
                    <tr>                      
                        <td align="right" class="fontText" colspan="5"><font style=\"font-size: 13px;\">Total de Projetos por Agricultor: '.$a.'</font></td>                 
                    </tr>
                    <tr>
                        <td align="center" class="fontText" colspan="5"><hr /></td>
                    </tr>';

                  }

                  $i++;
        
                  $html.= '                    
                    <tr>
                      <td align="right" colspan="5" class="fontText"><b>Total de Agricultores : '.count($row).'</b></td>
                    </tr>
                  </table>';
                
                    unset($row);
                }
                
//                exit($html);

                $dompdf->load_html($html);
                $dompdf->render();
                $dompdf->stream("sisplan_".date("dmY_his").".pdf");
        
        break;
        
        case "gerarLOGFilesPDF":
            
            pa($_POST);
        
		break;

		default:
			//Erro de parâmetro invalido
			header("location: index.html");
		break;		
	}
?>  