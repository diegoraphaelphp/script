<?php
	//importando...
	require_once("../class/Conexao.php");
	require_once("../class/Convertedata.php");	
	require_once("../class/Config.php");	
	require_once("../lib/util.php");

	//instancias...
	$banco = Conexao::singleton();
	$conf  = Config::singleton();
	$conv  = Convertedata::singleton();
	
	clearBrowserCache();
	
	date_default_timezone_set("America/Recife");	
	
	$acao = base64_decode($_GET["acao"]);

	if (empty($acao)){
		header("location: ../index.php");
		exit;
	}

	switch($acao){
		//GERAIS...
		case "frmAcesso":						
			require_once("../lib/verifica.php");
//			require_once("../lib/log.php");
			require_once("../acesso.php");
		break;		
		
		case "MenuPrincipal":						
			require_once("../lib/verifica.php");
//			require_once("../lib/log.php");
			require_once("../menu.php");
		break;
		
		case "frmAlterarSenha":
			require_once("../lib/verifica.php");
//			require_once("../lib/log.php");
			require_once("../cadastros/frm_senha.php");
		break;

		case "AlterarSenha":

			require_once("../lib/verifica.php");
	//		require_once("../lib/log.php");

			$senha  = antInjection($_POST["senha"]);

			$sql = "UPDATE tb_usuarios SET USU_Senha = MD5('".$senha."') WHERE USU_IDUsuario = ".$_SESSION["sIDUSUARIO"];
			$qry = $banco->executarQuery($sql);

			alert("Registro atualizado com sucesso.");

			goto2("../lib/Fachada.php?acao=".base64_encode("MenuPrincipal")."&mod=".$_GET["mod"]);
			
		break;		
		
		case "acessoNegado":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../acesso.php");
		break;
		
		case "formGerarTXT":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../cadastros/frm_gerar_txt.php");
		break;
		
		case "gerarTXT":
        
            $mun = antInjection($_POST["mun"]);
			$tab = antInjection($_POST["tabela"]);
            
			$txt = "";
            
            set_time_limit(0);
            ini_set("memory_limit", "400M");
            ini_set("max_execution_time", "1000");

			$diego ="http://".$_SERVER["SERVER_NAME"]."/".$_SERVER["PHP_SELF"];
			$diego = str_replace("lib/Fachada.php", "", $diego);

			if ($tab == "A"){
				$sqlA  = "select * from tb_atividades order by ATV_IDAtividade";
				$arq   = "atividades";			
			}elseif ($tab == "M"){
				$sqlA  = "SELECT * FROM tb_municipios ORDER BY MUN_IDMunicipio";
				$arq   = "municipios";
			}elseif ($tab == "P"){
				$sqlA  = "SELECT * FROM tb_projetos ORDER BY PRJ_IDProjeto";
				$arq   = "projetos";
			}elseif ($tab == "U"){
				$sqlA  = "SELECT * FROM tb_unidades ORDER BY UND_IDUnidade";
				$arq   = "unidades";
			}elseif ($tab == "I"){
				$sqlA  = "SELECT * FROM tb_indicadores ORDER BY IND_ID";
				$arq   = "indicadores";
			}elseif ($tab == "D"){
				$sqlA  = "SELECT * FROM tb_metodologia ORDER BY MET_IDMetodologia";
				$arq   = "metodologias";
			}elseif ($tab == "L"){
				$sqlA  = "SELECT * FROM tb_planoanual WHERE MUN_IDMunicipio = '".$mun."' ORDER BY PLA_IDAnual";
				$arq   = completarComZero($mun, 3)."_planoanual";
			}elseif ($tab == "S"){
				$sqlA  = "SELECT * FROM tb_segmentos_agro ORDER BY SAG_ID";
				$arq   = "segmentos_agro";
			}elseif ($tab == "O"){
				$sqlA  = "SELECT * FROM tb_orientacoes ORDER BY ORI_ID";
				$arq   = "orientacoes";				
			}elseif ($tab == "F"){
				$sqlA  = "SELECT * FROM tb_fontes_financiamentos ORDER BY FIN_ID";
				$arq   = "fontes_financiamentos";
			}elseif ($tab == "J"){
				$sqlA  = "SELECT * FROM tb_orientacoes_x_projetos ORDER BY ORI_ID";
				$arq   = "orientacoes_x_projetos";
			}elseif ($tab == "X"){
				$sqlA  = "SELECT * FROM tb_usuarios WHERE MUN_IDMunicipio = '".$mun."' ORDER BY USU_IDUsuario";
				$arq   = completarComZero($mun, 3)."_usuarios";				
			}elseif ($tab == "W"){
				$sqlA  = "SELECT * FROM tb_agentes_financeiros ORDER BY AGE_ID";
				$arq   = "agentes_financeiros";
			}elseif ($tab == "C"){
				$sqlA  = "SELECT * FROM tb_cadastro WHERE IDMUNICIPIO = '".$mun."' ORDER BY IDCADASTRO";
				$arq   = completarComZero($mun, 3)."_agricultores";

			}elseif ($tab == "E"){
				$sqlA  = "SELECT * FROM tb_plano_execucao WHERE MUN_IDMunicipio = '".$mun."' ORDER BY PLE_IDExecucao";
				$arq   = completarComZero($mun, 3)."_plano_execucao";
			}
			$rowA  = $banco->listarArray($sqlA);			
			if (count($rowA) == "0"){
				echo "
					<script>
						alert('Não há dados para visualização.');
						window.close();
					</script>";
				exit;
			}
			
			$file  = "../lib/txt/".$arq.".txt";
			$nfile = str_replace("../", "", removeStrings($file, ""));						
			$url   = $diego.$nfile;
			//$url   = "http://localhost/pam/".$nfile;
			$abrir = fopen($file,"w+");

			define("CRLF", chr(13).chr(10));			

			if ($tab == "A"){
			
				foreach($rowA as $a){
					$txt = completarComZero($a["ATV_IDAtividade"])." ".completarComZero($a["PRJ_IDProjeto"])." ".completarComZero($a["UND_IDUnidade"])." ".addEspaco($a["ATV_Descricao"])." ".$a["ATV_Status"]." ".$a["ATV_Tipoficha"].CRLF;					
					fwrite($abrir, $txt);				
				}
			
			}elseif ($tab == "M"){
			
				foreach($rowA as $a){
					$txt = completarComZero($a["MUN_IDMunicipio"])." ".addEspaco($a["MUN_Codigo"], 2)." ".addEspaco($a["MUN_Descricao"])." ".completarComZero($a["RDE_ID"])." ".$a["MUN_Status"]." ".completarComZero($a["REG_ID"]).CRLF;
					fwrite($abrir, $txt);				
				}

			}elseif ($tab == "P"){
			
				foreach($rowA as $a){
					$txt = completarComZero($a["PRJ_IDProjeto"])." ".addEspaco($a["PRJ_Descricao"])." ".$a["PRJ_Opcao"]." ".$a["PRJ_Status"].CRLF;
					fwrite($abrir, $txt);				
				}

			}elseif ($tab == "U"){
			
				foreach($rowA as $a){
					$txt = completarComZero($a["UND_IDUnidade"])." ".addEspaco($a["UND_Descricao"])." ".$a["UND_Status"].CRLF;
					fwrite($abrir, $txt);				
				}

			}elseif ($tab == "I"){
			
				foreach($rowA as $a){
					$txt = completarComZero($a["IND_ID"])." ".addEspaco($a["IND_Descricao"])." ".$a["IND_Status"].CRLF;
					fwrite($abrir, $txt);				
				}

			}elseif ($tab == "D"){
			
				foreach($rowA as $a){
					$txt = completarComZero($a["MET_IDMetodologia"])." ".addEspaco($a["MET_Descricao"])." ".$a["MET_Status"].CRLF;
					fwrite($abrir, $txt);
				}
			}elseif ($tab == "L"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["PLA_IDAnual"])." ".completarComZero($a["PLA_Ano"], 4)." ".completarComZero($a["PRJ_IDProjeto"])." ".completarComZero($a["ATV_IDAtividade"])." ".completarComZero($a["ATV_Prevqtd"], 10)." ".completarComZero($a["ATV_Prevfam"], 10)." ".completarComZero($a["USU_IDUsuario"])." ".completarComZero($a["MUN_IDMunicipio"])." ".$a["PLA_Data"].CRLF;
					fwrite($abrir, $txt);
				}
			}elseif ($tab == "S"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["SAG_ID"])." ".addEspaco($a["SAG_Descricao"])." ".$a["SAG_Status"].CRLF;
					fwrite($abrir, $txt);
				}
			}elseif ($tab == "O"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["ORI_ID"])." ".addEspaco($a["ORI_Descricao"])." ".$a["ORI_Status"].CRLF;
					fwrite($abrir, $txt);
				}				
			}elseif ($tab == "F"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["FIN_ID"])." ".addEspaco($a["FIN_Descricao"], 255)." ".$a["FIN_Status"].CRLF;
					fwrite($abrir, $txt);
				}
			}elseif ($tab == "J"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["ORI_ID"])." ".completarComZero($a["PRJ_IDProjeto"]).CRLF;
					fwrite($abrir, $txt);
				}
			}elseif ($tab == "X"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["USU_IDUsuario"])." ".completarComZero($a["MUN_IDMunicipio"])." ".addEspaco($a["USU_Nome"], 100)." ".$a["USU_Status"].CRLF;
					fwrite($abrir, $txt);
				}				
			}elseif ($tab == "W"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["AGE_ID"])." ".addEspaco($a["AGE_IDFEBRABAN"], 3)." ".addEspaco($a["AGE_Descricao"], 50)." ".$a["AGE_Status"].CRLF;
					fwrite($abrir, $txt);                    
				}	
                
			}elseif ($tab == "C"){
			
				foreach($rowA as $a){

					$txt = completarComZero($a["IDCADASTRO"])." ".completarComZero($a["IDMUNICIPIO"])." ".addEspaco($a["NOME"], 100)." ".addEspaco($a["ENDERECO"], 100)." ".addEspaco($a["CPF"], 11)." ".addEspaco($a["RG"], 20)." ".addEspaco($a["DATA"], 19)." ".addEspaco($a["ESTADOCIVEL"], 1)." ".addEspaco($a["CONJUGE"], 100)." ".addEspaco($a["CPFCONJUGE"], 11)." ".addEspaco($a["RGCONJUGE"], 20)." ".addEspaco($a["RENDAGRICOLA"], 12)." ".completarComZero($a["INDIVIDUOS"])." ".addEspaco($a["GRAUINSTPROD"], 1)." ".addEspaco($a["GRAUINSTCONJ"], 1)." ".addEspaco($a["TPPROPIEDADE"], 1)." ".addEspaco($a["AREA"], 10)." ".addEspaco($a["APELIDO"], 30)." ".addEspaco($a["TIPORENDA"], 1)." ".completarComZero($a["DEPENDENTES"])." ".addEspaco($a["TIPORENDA"], 1)." ".addEspaco($a["DEPENDENTES"], 11)." ".addEspaco($a["IDENTIDADE"], 11)." ".addEspaco($a["TITPOSSE"], 1)." ".addEspaco($a["AGUA"], 1)." ".addEspaco($a["CAPTACAO"], 1)." ".addEspaco($a["ESTRADA"], 1)." ".addEspaco($a["ELETRICIDADE"], 1)." ".addEspaco($a["DISTANCIA"], 10)." ".completarComZero($a["IDUSUARIO"])." ".addEspaco($a["PROPRIEDADE"], 100)." ".addEspaco($a["SEXO"], 1)." ".addEspaco($a["RENDAPECUARIA"], 12)." ".addEspaco($a["RGEMISSOR"], 12)." ".addEspaco($a["RGEMISSOR"], 12)." ".addEspaco($a["RGDATA"], 10)." ".addEspaco($a["NATURALIDADE"], 30)." ".addEspaco($a["NOMEPAI"], 100)." ".addEspaco($a["NOMEMAE"], 100)." ".addEspaco($a["CONJNATURAL"], 30)." ".addEspaco($a["CONJRGEMISSOR"], 12)." ".addEspaco($a["CONJRGDATA"], 10)." ".addEspaco($a["NASCIMENTO"], 10)." ".addEspaco($a["CONJNASCIMENTO"], 10)." ".addEspaco($a["CONJAPELIDO"], 30)." ".addEspaco($a["CONJNOMEPAI"], 100)." ".addEspaco($a["CONJNOMEMAE"], 100)." ".addEspaco($a["NOMEPROPRIETARIO"], 100)." ".addEspaco($a["CPFPROPRIETARIO"], 14)." ".addEspaco($a["RESPFAMILIA"], 1)." ".addEspaco($a["LATITUDE"], 6)." ".addEspaco($a["LONGITUDE"], 6)." ".addEspaco($a["NUMPORTDEF"], 6)." ".addEspaco($a["STATUS"], 1)." ".addEspaco($a["CATEGORIA"], 1).CRLF;
					fwrite($abrir, $txt);
				}
                
			}elseif ($tab == "E"){

				foreach($rowA as $a){

					$txt = completarComZero($a["PLE_IDExecucao"])." ".addEspaco($a["PLE_Ano"], 4)." ".completarComZero($a["PRJ_IDProjeto"])." ".completarComZero($a["ATV_IDAtividade"])." ".addEspaco($a["PLE_Semana"], 2)." ".addEspaco($a["PLE_Qtd"], 15)." ".addEspaco($a["PLE_Data"], 10)." ".completarComZero($a["USU_IDUsuario"])." ".completarComZero($a["MUN_IDMunicipio"])." ".completarComZero($a["FAM_IDFamilia"])." ".addEspaco($a["PLE_Familias"], 11)." ".completarComZero($a["ORI_IDOrientacao"])." ".addEspaco($a["PLE_Desc_Atv"], 100)." ".addEspaco($a["PLE_Origem"], 100)." ".addEspaco($a["PLE_Qtd2"], 15).CRLF;      
                     fwrite($abrir, $txt);
				}

			}

			fclose($abrir);
			goto2($url);

		break;		

		case "formPlanoAno":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../cadastros/frm_plano_ano.php");
		break;
		
		case "filtrarLOG":						
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_log.php");
		break;
		
		case "incluirPlanoAno":						
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$data    = antInjection($_POST["data"]);
			$data    = explode("/", $data);
			$message = $_POST["message"];

			for($i=0;$i<count($_POST["mun"]);$i++){
			
				$ano = $_POST["ano"][$i];
				$mun = $_POST["mun"][$i];
			
				if ( (!empty($ano)) && (!empty($mun)) ){
			
					$sqlE = "SELECT PAN_Ano, MUN_IDMunicipio FROM tb_plano_ano ";
					$sqlE.= "WHERE PAN_Ano = '".$ano."' AND MUN_IDMunicipio = '".$mun."'";
					$rowE = $banco->listarArray($sqlE);
					$dataex = $ano."-".$data[1]."-".$data[0];
					if (count($rowE) == 0){
						$sql = "INSERT INTO tb_plano_ano (PAN_Ano, MUN_IDMunicipio, PAN_MsgInicial, PAN_DataExpira) ";
						$sql.= "VALUES ('".$ano."', ".$mun.", '".$message."', '".$dataex."')";
					}else{
						$sql = "UPDATE tb_plano_ano SET PAN_Ano = '".$ano."', PAN_MsgInicial = '".$message."', ";
						$sql.= "PAN_DataExpira = '".$dataex."' WHERE MUN_IDMunicipio = ".$mun." AND PAN_Ano = '".$rowE[0]["PAN_Ano"]."'";
					}
					$qry = $banco->executarQuery($sql);
				}
			}

			alert("Registro(s) atualizado(s) com sucesso.");
			goto2("../lib/Fachada.php?acao=".base64_encode("MenuPrincipal")."&mod=".$_GET["mod"]);

		break;
		
		
		//UNIDADES...
		case "cadastrarUnidades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		 
			include_once("../cadastros/frm_unidades.php");
		break;
		
		case "incluirUnidades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);			
			$status = antInjection($_POST["status"]);
			
			$sqlE   = "select UND_IDUnidade from tb_unidades where UND_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			
			if ($existe){
				alert("ATENÇÃO: Já existe Unidade cadastrada com o nome ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_unidades (UND_Descricao, UND_Status) values ('".$nome."', '".$status."')";				
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarUnidades")."&cad=0&mod=".$_GET["mod"]);
			}
		break;
		
		case "alterarUnidades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);			
			$status = antInjection($_POST["status"]);
			
			$sqlE   = "select UND_IDUnidade from tb_unidades where UND_Descricao = '".$nome."' and UND_IDUnidade <> ".$id;
			$existe = $banco->existe($sqlE);
			
			if ($existe){
				alert("ATENÇÃO: Já existe Unidade cadastrada com o nome ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "update tb_unidades set UND_Descricao = '".$nome."', UND_Status = '".$status."' ";
				$sql.= "where UND_IDUnidade = ".$id;				
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarUnidades")."&alt=0&mod=".$_GET["mod"]);
			}
						
		break;
		
		case "filtrarUnidades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
			include_once("../consultas/filtro_unidades.php");
		break;
		
		case "excluirUnidades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql1 = "SELECT UND_IDUnidade FROM tb_atividades WHERE UND_IDUnidade = ".$id;
				$row1 = $banco->listarArray($sql1);
				
				$sql2 = "SELECT UND_IDUnidade FROM tb_indicadores_segmentos WHERE UND_IDUnidade = ".$id;
				$row2 = $banco->listarArray($sql2);
				
				$sql3 = "SELECT UND_IDUnidade FROM tb_producao_x_familia WHERE UND_IDUnidade = ".$id;
				$row3 = $banco->listarArray($sql3);
				$tot  = 0;
				$tot  = count($row1) + count($row2) + count($row3);
				if ($tot == 0){
					$sql = "DELETE FROM tb_unidades WHERE UND_IDUnidade = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarUnidades")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		/**/
	
		/* USUARIOS */
        case "validaLoginbtn":
            
            session_start();
            
            $sql = "SELECT USU_IDUsuario FROM tb_usuarios WHERE USU_Login = '".$_GET["id"]."' and USU_Status = 'A'";
            $sql.= " AND USU_IDUsuario <> ".$_SESSION["sIDUSUARIO"];
            $qry = $banco->executarQuery($sql);
            $tot = $banco->totalLinhas($qry);	
            if ($tot == 0){
                echo "<input type='button' name='btn1' value='Salvar' onClick=\"javascript: pega_valor2('', 'pega_dados_muni_unid');\" class='button'>";
            }else{
                echo "<input type='button' name='btn1' value='Salvar' class='button' style='border:1px solid #999;background-color: #ddd; color:#999999;'>";
            }
            
		break;
        	
        case "validaLogineditar":
        
            session_start();
        
        	$sql = "SELECT USU_IDUsuario FROM tb_usuarios WHERE USU_Login = '".$_GET["id"]."'";
        	$sql.= " AND USU_IDUsuario <> ".$_SESSION["sIDUSUARIO"];	
        	$qry = $banco->executarQuery($sql);
        	$tot = $banco->totalLinhas($qry);

        	if ($tot > 0){
        	  echo "<font size='1' color='red'>Login j&aacute; existe.<img src='../img/bolaVerm.gif' border='0'></font>";	
        	}else{
        	  echo "<font size='1' color=\"#006600\">Login válido.</font><img src='../img/bolaVerde.gif' border='0'>";		
        	}
        
		break;
        
		case "validaLogin":		
		    //start na sessao...
			session_start();
		
			//verifica se usuario existe
			$login = antInjection($_POST["login"]);
			$senha = antInjection($_POST["senha"]);
            
			$sql = "SELECT u.*, m.MUN_Descricao, m.REG_ID, m.RDE_ID, e.EMP_Descricao FROM tb_usuarios u ";
			$sql.= "INNER JOIN tb_municipios m ON (m.MUN_IDMunicipio = u.MUN_IDMunicipio) ";
			$sql.= "INNER JOIN tb_empresas e ON (e.EMP_IDEmpresa = u.EMP_IDEmpresa) ";
			$sql.= "WHERE u.USU_Login = '".$login."' AND u.USU_Senha = MD5('".$senha."')";
			$row = $banco->listarArray($sql);
			if (count($row) == "0"){			
			  header("location: ../index.php?erro=0");
			  exit;
			}else{

			  if ($row[0]["USU_Status"] == "I"){
			  	header("location: ../index.php?erro2=sim");
				exit;			  
			  }
			  //GRAVA DADOS DO USUARIO NA SESSAO...
			  $_SESSION["sIDUSUARIO"]         = $row[0]["USU_IDUsuario"];
			  $_SESSION["sNOME_USUARIO"]      = $row[0]["USU_Nome"];
			  $_SESSION["sLOGIN_USUARIO"]     = $row[0]["USU_Login"];
			  $_SESSION["sIDMunicipio"]       = $row[0]["MUN_IDMunicipio"];
			  $_SESSION["sMUN_Descricao"]     = $row[0]["MUN_Descricao"];			  
   			  $_SESSION["sIDRegional"]        = $row[0]["REG_ID"];
              $_SESSION["sIDRD"]              = $row[0]["RDE_ID"];              
              $_SESSION["sEMAIL"]             = $row[0]["USU_Email"];
              $_SESSION["sTELEFONE"]          = $row[0]["USU_Telefone"];
			  $_SESSION["sEMP_IDEmpresa"]     = $row[0]["EMP_IDEmpresa"];
			  $_SESSION["sEMP_Descricao"]     = $row[0]["EMP_Descricao"];
			  $_SESSION["sPAGINACAO_USUARIO"] = $row[0]["USU_Paginacao"];
              $_SESSION["sNFE"]               = $row[0]["USU_NFE"];
              $_SESSION["sEMPENHAR"]          = $row[0]["USU_Empenhar"];

   			  require_once("../lib/log.php");
              
              if ($senha == "123456"){

                goto2("../lib/Fachada.php?acao=".base64_encode("frmAlterarSenha")."&mod=".base64_encode(5));
                
              }else{
                
                goto2("../lib/Fachada.php?acao=".base64_encode("MenuPrincipal"));
                  
              }
			  
			}
			
		break;
		
		case "cadastrarUsuarios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_usuarios.php");
		break;
		
		case "filtrarUsuarios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_usuarios.php");
		break;
		
		case "incluirUsuarios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome     = antInjection($_POST["nome"]);
			$und      = antInjection($_POST["empresa"]);
			$mun      = antInjection($_POST["municipio2"]);
			$email    = antInjection($_POST["email"]);
			$fone     = antInjection($_POST["fone"]);			
			$login    = antInjection($_POST["login"]);
			$senha    = antInjection(md5($_POST["senha"]));
			$pagina   = antInjection($_POST["pagina"]);
			$status   = antInjection($_POST["status"]);
            $nfe      = antInjection($_POST["nfe"]);
            $empenhar = antInjection($_POST["empenhar"]);

            if ($nfe == "on") $nfe = "S"; else $nfe = "N";
            if ($empenhar == "on") $empenhar = "S"; else $empenhar = "N";

			$sqlE   = "select USU_Login from tb_usuarios where USU_Login = '".$login."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Usuário cadastrado com o login ".$login.".");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_usuarios (EMP_IDEmpresa, MUN_IDMunicipio, USU_Nome, USU_Datacad, ";
				$sql.= "USU_Login, USU_Senha, USU_Status, USU_Paginacao, USU_Email, USU_Telefone, USU_NFE, USU_Empenhar) VALUES ('".$und."', '".$mun."', ";
				$sql.= "'".$nome."', '".date("Y-m-d")."', '".$login."', '".$senha."', '".$status."', '".$pagina."', '".$email."', '".$fone."', ";
                $sql.= "'".$nfe."', '".$empenhar."')";
				$idU = $banco->ultimoId($sql);
				
				//INCLUINDO OS MODULOS DOS USUARIOS...
				$sqlA = "SELECT MOD_ID, MOD_Nome FROM tb_modulos WHERE MOD_Status = 'A' ORDER BY MOD_Nome";
				$rowA = $banco->listarArray($sqlA);
				$a    = 0; 
				foreach($rowA as $l){
					$id = $_POST["modu"][$a];				
					if (isset($id)){
						$sqlE = "SELECT MOD_ID FROM tb_mod_usuarios WHERE USU_IDUsuario = ".$idU." AND MOD_ID = ".$id;
						$existe = $banco->existe($sqlE);
						if (!$existe){
							//incluindo...
							$sql = "INSERT INTO tb_mod_usuarios (MOD_ID, USU_IDUsuario) VALUES (".$id.", ".$idU.")";
							$qry = $banco->executarQuery($sql);
						}
					}
					$a++;
				}
				/**/
				
				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarUsuarios")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirUsuarios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql1 = "SELECT USU_IDUsuario FROM tb_log WHERE USU_IDUsuario = ".$id;
				$row1 = $banco->listarArray($sql1);
				
				$sql2 = "SELECT USU_IDUsuario FROM tb_plano_execucao WHERE USU_IDUsuario = ".$id;
				$row2 = $banco->listarArray($sql2);
				
				$sql3 = "SELECT USU_IDUsuario FROM tb_plano_real WHERE USU_IDUsuario = ".$id;
				$row3 = $banco->listarArray($sql3);
				$tot  = 0;
				$tot  = count($row1) + count($row2) + count($row3);
				if ($tot == 0){
					$sql = "DELETE FROM tb_usuarios WHERE USU_IDUsuario = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarUsuarios")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
        
		case "alterarMuniUnid":
			require_once("../lib/verifica.php");

			$und          = antInjection($_GET["unidade"]);
			$mun          = antInjection($_GET["municipio"]);            
            $nomeusuario  = antInjection($_GET["nomeusuario"]);
            $loginusuario = antInjection($_GET["loginusuario"]);
            $emailusuario = antInjection($_GET["emailusuario"]);
            $foneusuario  = antInjection($_GET["foneusuario"]);            
            
			$sql = "UPDATE tb_usuarios SET EMP_IDEmpresa = '".$und."', MUN_IDMunicipio = '".$mun."', USU_Nome = '".$nomeusuario."', ";
            $sql.= "USU_Login = '".$loginusuario."', USU_Email = '".$emailusuario."', USU_Telefone = '".$foneusuario."' ";
            $sql.= "WHERE USU_IDUsuario = '".$_SESSION["sIDUSUARIO"]."'";
            $qry = $banco->executarQuery($sql);

			$sqlM = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = ".$mun;
			$rowM   = $banco->listarArray($sqlM);
            
            $sqlE = "SELECT EMP_Descricao FROM tb_empresas WHERE EMP_IDEmpresa = ".$und;
			$rowE   = $banco->listarArray($sqlE);
            
			$_SESSION["sIDMunicipio"]   = $mun;
			$_SESSION["sMUN_Descricao"] = $rowM[0]["MUN_Descricao"];
			$_SESSION["sIDRegional"]    = $und;
			$_SESSION["sEMP_IDEmpresa"] = $und;
			$_SESSION["sEMP_Descricao"] = $rowE[0]["EMP_Descricao"];            
            $_SESSION["sNOME_USUARIO"]  = $nomeusuario;
			$_SESSION["sLOGIN_USUARIO"] = $loginusuario;
			$_SESSION["sEMAIL"]         = $emailusuario;
            $_SESSION["sTELEFONE"]      = $foneusuario;
            
		break;
                
		case "alterarUsuarios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
				
			//verifica se existe...		
			$idU      = antInjection(base64_decode($_POST["id"]));
			$nome     = antInjection($_POST["nome"]);
			$und      = antInjection($_POST["empresa"]);
			$mun      = antInjection($_POST["municipio2"]);
			$pagina   = antInjection($_POST["pagina"]);
			$login    = antInjection($_POST["login"]);
			$status   = antInjection($_POST["status"]);			
			$email    = antInjection($_POST["email"]);
			$fone     = antInjection($_POST["fone"]);
            $nfe      = antInjection($_POST["nfe"]);
            $empenhar = antInjection($_POST["empenhar"]);

            if ($nfe == "on") $nfe = "S"; else $nfe = "N";
            if ($empenhar == "on") $empenhar = "S"; else $empenhar = "N";      

			$sqlE   = "SELECT USU_Login FROM tb_usuarios WHERE USU_Login = '".$login."' AND USU_IDUsuario <> ".$idU;
			$existe = $banco->existe($sqlE);
			if ($existe){
				
				alert("ATENÇÃO: Já existe USUÁRIO cadastrado com o login ".$login.".");
				anterior(-1);
				
			}else{
				
				$sql = "UPDATE tb_usuarios SET EMP_IDEmpresa = '".$und."', MUN_IDMunicipio = '".$mun."', USU_Email = '".$email."', ";
				$sql.= "USU_Nome = '".$nome."', USU_Status = '".$status."', USU_Login = '".$login."', USU_Telefone = '".$fone."', ";
				$sql.= "USU_Paginacao = '".$pagina."', USU_NFE = '".$nfe."', USU_Empenhar = '".$empenhar."' WHERE USU_IDUsuario = ".$idU;
				$qry = $banco->executarQuery($sql);

				$sqlM = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_IDMunicipio = ".$mun;
				$rowM   = $banco->listarArray($sqlM);
				
				$sqlE = "SELECT EMP_Descricao FROM tb_empresas WHERE EMP_IDEmpresa = ".$und;
				$rowE   = $banco->listarArray($sqlE);
				
				if ($_SESSION["sIDUSUARIO"] == $idU){
				
					$_SESSION["sNOME_USUARIO"]      = $nome;
					$_SESSION["sIDMunicipio"]       = $mun;
					$_SESSION["sMUN_Descricao"]     = $rowM[0]["MUN_Descricao"];
					$_SESSION["sIDRegional"]        = $und;
					$_SESSION["sPAGINACAO_USUARIO"] = $pagina;
					$_SESSION["sEMP_IDEmpresa"]     = $und;
					$_SESSION["sEMP_Descricao"]     = $rowE[0]["EMP_Descricao"];
                    $_SESSION["sNFE"]               = $nfe;
                    $_SESSION["sEMPENHAR"]          = $empenhar;

				}
				
				//INCLUINDO OS MODULOS DOS USUARIOS...
				$sqlU = "DELETE FROM tb_mod_usuarios WHERE USU_IDUsuario = ".$idU;
				$qryU = $banco->executarQuery($sqlU);
				
				$sqlA = "SELECT MOD_ID, MOD_Nome FROM tb_modulos WHERE MOD_Status = 'A' ORDER BY MOD_Nome";
				$rowA = $banco->listarArray($sqlA);
				$a    = 0; 
				foreach($rowA as $l){
					$id = $_POST["modu"][$a];				
					if (isset($id)){
						$sqlE = "SELECT MOD_ID FROM tb_mod_usuarios WHERE USU_IDUsuario = ".$idU." AND MOD_ID = ".$id;
						$existe = $banco->existe($sqlE);
						if (!$existe){
							//incluindo...
							$sql = "INSERT INTO tb_mod_usuarios (MOD_ID, USU_IDUsuario) VALUES (".$id.", ".$idU.")";
							$qry = $banco->executarQuery($sql);
						}
					}
					$a++;
				}
				/**/

				alert("Registro atualizado com sucesso.");

				goto2("../lib/Fachada.php?acao=".base64_encode("filtrarUsuarios")."&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* REGIONAL */
		case "cadastrarRegional":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_regional.php");
		break;
		
		case "filtrarRegional":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_regional.php");
		break;
		
		case "incluirRegional":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select REG_Descricao from tb_regional where REG_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Gerência Regional cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_regional (REG_Descricao, REG_Status) values ('".$nome."', '".$status."')";				
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarRegional")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirRegional":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql1 = "SELECT REG_ID FROM tb_empresas WHERE REG_ID = ".$id;
				$row1 = $banco->listarArray($sql1);
				
				 
				$sql2 = "SELECT REG_ID FROM tb_municipios WHERE REG_ID = ".$id;
				$row2 = $banco->listarArray($sql2);

				$tot  = 0;
				$tot  = count($row1) + count($row2);
				if ($tot == 0){
					$sql = "DELETE FROM tb_regional WHERE REG_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarRegional")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarRegional":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select REG_Descricao from tb_regional where REG_Descricao = '".$nome."' and REG_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Gerência Regional cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "update tb_regional set REG_Descricao = '".$nome."', REG_Status = '".$status."' where REG_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarRegional")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* REGIÃO DESENVOLVIMENTO */
		case "cadastrarDesenv":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_desenv.php");
		break;
		
		case "filtrarDesenv":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_desenv.php");
		break;
		
		case "incluirDesenv":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select RDE_Descricao from tb_regiaodesen where RDE_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Região de Desenvolvimento cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_regiaodesen (RDE_Descricao, RDE_Status) values ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarDesenv")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirDesenv":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql1 = "SELECT RDE_ID FROM tb_municipios WHERE RDE_ID = ".$id;
				$row1 = $banco->listarArray($sql1);

				$tot  = 0;
				$tot  = count($row1);
				if ($tot == 0){
					$sql = "DELETE FROM tb_regiaodesen WHERE RDE_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarDesenv")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarDesenv":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select RDE_Descricao from tb_regiaodesen where RDE_Descricao = '".$nome."' and RDE_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Região de Desenvolvimento cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "update tb_regiaodesen set RDE_Descricao = '".$nome."', RDE_Status = '".$status."' where RDE_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarDesenv")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* MESSOREGIÃO */
		case "cadastrarMeso":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_meso.php");
		break;
		
		case "filtrarMeso":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_meso.php");
		break;
		
		case "incluirMeso":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select MES_Descricao from tb_mesoregioes where MES_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Messoregião cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_mesoregioes (MES_Descricao, MES_Status) values ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMeso")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirMeso":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_mesoregioes WHERE MES_IDMeso = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMeso")."&exc=0");
		break;
		
		case "alterarMeso":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select MES_Descricao from tb_mesoregioes where MES_Descricao = '".$nome."' and MES_IDMeso <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Região de Desenvolvimento cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "update tb_mesoregioes set MES_Descricao = '".$nome."', MES_Status = '".$status."' where MES_IDMeso = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMeso")."&alt=0&mod=".$_GET["mod"]);
			}
		break;
		/**/
		
		/* MICRORREGIÃO */
		case "cadastrarMicro":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_micro.php");
		break;
		
		case "filtrarMicro":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_micro.php");
		break;
		
		case "incluirMicro":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select MIC_Descricao from tb_microregioes where MIC_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Microrregião cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_microregioes (MIC_Descricao, MIC_Status) values ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMicro")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirMicro":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_microregioes WHERE MIC_IDMicro = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMicro")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarMicro":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select MIC_Descricao from tb_microregioes where MIC_Descricao = '".$nome."' and MIC_IDMicro <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Região de Desenvolvimento cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "update tb_microregioes set MIC_Descricao = '".$nome."', MIC_Status = '".$status."' where MIC_IDMicro = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMicro")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* PROJETOS */
		case "cadastrarProjetos":
			require_once("../lib/verifica.php");
//			require_once("../lib/log.php");
			include_once("../cadastros/frm_projetos.php");
		break;
		
		case "filtrarProjetos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_projetos.php");
		break;
		
		case "incluirProjetos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$opcao  = antInjection($_POST["opcao"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select PRJ_Descricao from tb_projetos where PRJ_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Projeto cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_projetos (PRJ_Descricao, PRJ_Opcao, PRJ_Status) values ('".$nome."', '".$opcao."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProjetos")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirProjetos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				//excluindo...
				$sql1 = "SELECT PRJ_IDProjeto FROM tb_atividades WHERE PRJ_IDProjeto = ".$id;
				$row1 = $banco->listarArray($sql1);
				
				$sql2 = "SELECT PRJ_IDProjeto FROM tb_cronoexec WHERE PRJ_IDProjeto = ".$id;
				$row2 = $banco->listarArray($sql2);
				
				$sql3 = "SELECT PRJ_IDProjeto FROM tb_plano_execucao WHERE PRJ_IDProjeto = ".$id;
				$row3 = $banco->listarArray($sql3);
				
				$sql4 = "SELECT PRJ_IDProjeto FROM tb_planoanual WHERE PRJ_IDProjeto = ".$id;
				$row4 = $banco->listarArray($sql4);

				$tot  = 0;
				$tot  = count($row1) + count($row2) + count($row3) + count($row4);
				if ($tot == 0){
					$sql = "DELETE FROM tb_projetos WHERE PRJ_IDProjeto = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProjetos")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarProjetos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$opcao  = antInjection($_POST["opcao"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select PRJ_Descricao from tb_projetos where PRJ_Descricao = '".$nome."' and PRJ_IDProjeto <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROJETO cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "UPDATE tb_projetos SET PRJ_Descricao = '".$nome."', PRJ_Status = '".$status."', PRJ_Opcao = '".$opcao."' ";
				$sql.= "WHERE PRJ_IDProjeto = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProjetos")."&alt=0&mod=".$_GET["mod"]);
			}
		break;
		/**/
		
		/* ATIVIDADES */
		case "cadastrarAtividades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_atividades.php");
		break;
		
		case "filtrarAtividades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_atividades.php");
		break;
		
		case "incluirAtividades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$id     = antInjection($_GET["id"]);
			$nome   = antInjection($_GET["nome"]);
			$und    = antInjection($_GET["und"]);
			$tipo   = antInjection($_GET["tipo"]);
			$status = antInjection($_GET["status"]);

			$sql = "INSERT INTO tb_atividades (PRJ_IDProjeto, UND_IDUnidade, ATV_Descricao, ATV_Status, ATV_Tipoficha) ";
			$sql.= "VALUES ('".$id."', '".$und."', '".$nome."', '".$status."', '".$tipo."')";
			$qry = $banco->executarQuery($sql);

		break;
        
		case "alterarAtividades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

            $idp    = antInjection($_POST["idp"]);
			$id     = antInjection($_POST["id"]);
			$nome   = antInjection($_POST["nome"]);
			$und    = antInjection($_POST["und"]);
			$tipo   = antInjection($_POST["tipo"]);
			$status = antInjection($_POST["status"]);
            
            //verifica se ja existe com este nome...
            /*
            $sqlE   = "SELECT ATV_Descricao FROM tb_atividades WHERE ATV_Descricao = '".$nome."' AND ATV_IDAtividade <> '".$id."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe ATIVIDADE cadastrada com o nome ".$nome.".");
				anterior(-1);
			}else{
			 */
			 
    			$sql = "UPDATE tb_atividades SET PRJ_IDProjeto = '".$idp."', UND_IDUnidade = '".$und."', ATV_Descricao = '".$nome."', ";
                $sql.= "ATV_Status = '".$status."', ATV_Tipoficha = '".$tipo."' WHERE ATV_IDAtividade = '".$id."'";
    			$qry = $banco->executarQuery($sql);
			//}
            
            goto2("../lib/Fachada.php?acao=".base64_encode("filtrarAtividades")."&alt=0&mod=".$_POST["mod"]);

		break;
		
		case "excluirAtividades":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$id  = base64_decode($_GET["id"]);
            
            //VERIFICA SE TEM RELACIONAMENTO COM OUTRA TABELA...
            $sql2 = "SELECT ATV_IDAtividade FROM tb_planoanual WHERE ATV_IDAtividade = '".$id."'";
            $row2 = $banco->listarArray($sql2);
            
            $sql3 = "SELECT ATV_IDAtividade FROM tb_plano_execucao WHERE ATV_IDAtividade = '".$id."'";
            $row3 = $banco->listarArray($sql3);
            $tot  = 0;
            $tot  = count($row2) + count($row3);
            
            if ($tot == "0"){
    			$sql = "DELETE FROM tb_atividades WHERE ATV_IDAtividade = ".$id;
    			$qry = $banco->executarQuery($sql);
                
    			echo "
    			<tr>
    				<td align=\"center\" colspan=\"6\">		
    					<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"30%\" align=\"center\">
    						<tr>
    							<td colspan=\"6\" style=\"background-color: #78B0F4;background-repeat: repeat-x;border-color: #406080;border-style: solid;border-width: 1px 0px 1px;color: #FFFFFF;font-family: Verdana, Arial, sans-serif;font-size: 11px;font-weight:bold;padding: 2px 5px 2px;\">Informa&ccedil;&atilde;o</td>
    						</tr>
    						<tr>	
    							<td colspan=\"6\" style=\"background-color: #E4E8F0;border-color: #406080;border-style: solid;border-width: 1px;color: #404040;font-family: Tahoma, Arial, sans-serif;font-size: 11px;padding: 2px 5px 2px;\">Registro(s) excluído(s) com sucesso.</td>
    						</tr>
    					</table>
    				</td>
    			</tr>";
                
            }else{
                
                echo "
                <tr>		                
                    <td align=\"center\" colspan=\"2\">
                        <table width=\"50%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td align=\"center\" style=\"background-color:#FF4040;color:#FFFFFF;font-family:Verdana, Arial, sans-serif;font-size:11px;font-weight:bold;\">ATEN&Ccedil;&Atilde;O!</td>
                            </tr> 
            	                <tr>
                                <td align=\"center\" style=\"background-color:#FFE0E0;border-color:#406080;border-style:solid;border-width:1px;color:#000000;font-family:Tahoma, Arial, sans-serif;font-size:11px;\"><b>ATIVIDADE(S)</b> não pode ser excluída por que tem relacionamento com:<br><b>PLANO DE EXECUÇÃO OU PLANO ANUAL</b>.</td>
                            </tr>
                        </table>
                    </td>
                </tr>";                
            }
            
            
            
            				
 
			
		  	

		break;
		/**/
		
		/* ELABORAÇÃO */
		case "cadastrarElaboracao":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_elaboracao.php");
		break;
		
		case "filtrarElaboracao":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_elaboracao.php");
		break;
		
		case "incluirElaboracao":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

		    $sqlPAR = "SELECT PAN_Ano FROM tb_plano_ano WHERE MUN_IDMunicipio = ".$_SESSION["sIDMunicipio"]." ORDER BY PAN_Ano DESC";
		    $rowPAR = $banco->listarArray($sqlPAR);

			$proj = antInjection($_POST["proj"]);
			
                        $idMunicipio = $_SESSION["sIDMunicipio"];
                        
                        // gambi Neritonio e Giuberto
                        if($_SESSION["sIDUSUARIO"] == "420" || $_SESSION["sIDUSUARIO"] == "900" || $_SESSION["sIDUSUARIO"] == "918"){
                            $idMunicipio = $_POST["idmunsel"];
                        }
                        
			for($i=0;$i<count($_POST["cod"]);$i++){
				$atv = base64_decode($_POST["cod"][$i]);
				$qtd = str_replace(",", ".", removeStrings($_POST["qtd"][$i], "."));
				$fam = $_POST["fam"][$i];
				
				if ( (!empty($atv)) && (!empty($qtd)) && (!empty($fam)) ){
					//incluindo no PLANO ANUAL...
					$sql = "INSERT INTO tb_planoanual (PLA_Ano, PRJ_IDProjeto, ATV_IDAtividade, ATV_Prevqtd, ATV_Prevfam, ";
					$sql.= "USU_IDUsuario, MUN_IDMunicipio, PLA_Data) VALUES ('".$rowPAR[0]["PAN_Ano"]."', '".$proj."', '".$atv."', ";
					$sql.= "'".$qtd."', '".$fam."', '".$_SESSION["sIDUSUARIO"]."', '".$idMunicipio."', '".date("Y-m-d H:i:s")."')";
					$qry = $banco->executarQuery($sql);
				}
			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarElaboracao")."&cad=0&mod=".$_GET["mod"]);

		break;
		
		case "excluirElaboracao":

			require_once("../lib/verifica.php");
            
            pa($_GET);
            $id = explode("@", base64_decode($_GET["id"]));
			
			//excluindo a plano anual..
            $sql2 = "DELETE FROM tb_planoanual WHERE PLA_IDAnual = '".$id[0]."' AND USU_IDUsuario = '".$id[1]."'";
            $qry2 = $banco->executarQuery($sql2);

		break;
		
		case "excluirElaboracao2":

			require_once("../lib/verifica.php");

			$sql2 = "DELETE FROM tb_planoanual WHERE PLA_IDAnual = ".base64_decode($_GET["id"]);
			$qry2 = $banco->executarQuery($sql2);
		
		break;
		/**/
		
		/* EQUIPES */
		case "cadastrarEquipes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_equipes.php");
		break;
		
		case "filtrarEquipes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_equipes.php");
		break;
		
		case "incluirEquipes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$aca    = antInjection($_POST["aca"]);
			$pessoa = antInjection($_POST["pessoa"]);			
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT ACO_ID FROM tb_equipes WHERE ACO_ID = ".$aca." AND PES_ID = ".$pessoa;
			//exit($sqlE);
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe EQUIPE com este PROJETO PESQUISA e PESSOA cadastrado.");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_equipes (ACO_ID, PES_ID, EQP_Tipo, EQP_Status) ";
				$sql.= "VALUES (".$aca.", ".$pessoa.", '".$nome."', '".$status."')";								
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarEquipes")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirEquipes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id = base64_decode($_POST["cod"][$i]);				
				$id = explode("#", $id); 
				
				//excluindo...
				$sql = "DELETE FROM tb_equipes WHERE ACO_ID = ".$id[0]." AND PES_ID = ".$id[1];
				//exit($sql);
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarEquipes")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarEquipes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$aca    = antInjection($_POST["aca"]);
			$pessoa = antInjection($_POST["pessoa"]);
			
			$sqlE  = "select ACO_ID from tb_equipes where ACO_ID = ".$id[0]." and PES_ID = ".$id[1];
			$count = $banco->listarArray($sqlE);
			if (count($count) > 1){
				alert("ATENÇÃO: Já existe EQUIPE cadastrado com esse AÇÃO e PESSOA informado.");
				anterior(-1);
			}else{
			
				$sql = "UPDATE tb_equipes SET EQP_Tipo = '".$nome."', EQP_Status = '".$status."', ";
				$sql.= "ACO_ID = ".$aca.", PES_ID = ".$pessoa." WHERE ACO_ID = ".$aca." AND PES_ID = ".$pessoa;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarEquipes")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/

		/* LINHA DE PESQUISA */
		case "cadastrarLinhap":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_linha.php");
		break;
		
		case "filtrarLinhap":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_linha.php");
		break;
		
		case "incluirLinhap":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);			
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT LIN_ID FROM tb_linhas_pesquisa WHERE LIN_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe LINHA DE PESQUISA cadastrada com o nome ".strtoupper($nome).".");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_linhas_pesquisa (LIN_Descricao, LIN_Status) ";
				$sql.= "VALUES ('".$nome."', '".$status."')";								
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarLinhap")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirLinhap":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql = "DELETE FROM tb_linhas_pesquisa WHERE LIN_ID = ".$id;
				//exit($sql);
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarLinhap")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarLinhap":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select LIN_Descricao from tb_linhas_pesquisa where LIN_Descricao = '".$nome."' and LIN_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe LINHA DE PESQUISA cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{			
				$sql = "update tb_linhas_pesquisa SET LIN_Descricao = '".$nome."', LIN_Status = '".$status."' where LIN_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarLinhap")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* PROJETO PESQUISA */
		case "cadastrarPPE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_ppe.php");
		break;
		
		case "filtrarPPE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_ppe.php");
		break;
		
		case "incluirPPE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$prog      = antInjection($_POST["prog"]);
			$nome      = antInjection($_POST["nome"]);
			$dtprevini = antInjection($_POST["anoprevini"]."-".$_POST["mesprevini"]."-01");
			$dtini     = antInjection($_POST["anoini"]."-".$_POST["mesini"]."-01");			
			$dtprevfim = antInjection($_POST["anoprevfim"]."-".$_POST["mesprevfim"]."-01");
			$dtfim     = antInjection($_POST["anofim"]."-".$_POST["mesfim"]."-01");			
			$fonte  = antInjection($_POST["fonte"]);
			$fonte2 = antInjection($_POST["fonte2"]);
			//$pessoa = antInjection($_POST["pessoa"]);
			$linha  = antInjection($_POST["linha"]);
			$obs    = antInjection($_POST["obs"]);
			$status = antInjection($_POST["status"]);

			//verifica se ja existe registro com este nome...	
			$sqlE   = "SELECT PPE_Descricao FROM tb_projetos_pesquisas WHERE PPE_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROJETO PESQUISA cadastrada com o nome ".strtoupper($nome).".");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_projetos_pesquisas (PRG_IDPrograma, PPE_Descricao, PPE_DataPrevInicio, PPE_DataInicio, PPE_DataPrevFim, ";
				$sql.= "PPE_DataFinal, PPE_IDFontePrin, PPE_IDFonteSec, LIN_ID, PPE_Obs, PPE_Status) ";
				$sql.= "VALUES (".$prog.", '".$nome."', '".$dtprevini."', '".$dtini."', '".$dtprevfim."', '".$dtfim."', ".$fonte.", ".$fonte2.", " ;
				$sql.= $linha.", '".$obs."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarPPE")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirPPE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql = "DELETE FROM tb_projetos_pesquisas WHERE PPE_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarPPE")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarPPE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id        = antInjection(base64_decode($_POST["id"]));
			$prog      = antInjection($_POST["prog"]);
			$nome      = antInjection($_POST["nome"]);
			$dtprevini = antInjection($_POST["anoprevini"]."-".$_POST["mesprevini"]."-01");
			$dtini     = antInjection($_POST["anoini"]."-".$_POST["mesini"]."-01");			
			$dtprevfim = antInjection($_POST["anoprevfim"]."-".$_POST["mesprevfim"]."-01");
			$dtfim     = antInjection($_POST["anofim"]."-".$_POST["mesfim"]."-01");
			$fonte     = antInjection($_POST["fonte"]);
			$fonte2    = antInjection($_POST["fonte2"]);
			//$pessoa    = antInjection($_POST["pessoa"]);
			$linha     = antInjection($_POST["linha"]);
			$obs       = antInjection($_POST["obs"]);
			$status    = antInjection($_POST["status"]);

			$sqlE   = "select PPE_Descricao from tb_projetos_pesquisas where PPE_Descricao = '".$nome."' and PPE_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROJETO PESQUISA cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_projetos_pesquisas SET PRG_IDPrograma = ".$prog.", PPE_Descricao = '".$nome."', PPE_DataPrevInicio = '".$dtprevini."', ";
				$sql.= "PPE_DataInicio = '".$dtini."', PPE_DataPrevFim = '".$dtprevfim."', PPE_DataFinal = '".$dtfim."', ";
				$sql.= "PPE_IDFontePrin = ".$fonte.", PPE_IDFonteSec = ".$fonte2.", LIN_ID = ".$linha.", ";
				$sql.= "PPE_Obs = '".$obs."', PPE_Status = '".$status."' WHERE PPE_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarPPE")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* AÇÕES */
		case "cadastrarAcoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_acoes.php");
		break;
		
		case "filtrarAcoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_acoes.php");
		break;
		
		case "incluirAcoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$prog      = antInjection($_POST["prog"]);
			$proj      = antInjection($_POST["proj"]);
			$nome      = antInjection($_POST["nome"]);
			$dtprevini = antInjection($_POST["anoprevini"]."-".$_POST["mesprevini"]."-01");
			$dtini     = antInjection($_POST["anoini"]."-".$_POST["mesini"]."-01");			
			$dtprevfim = antInjection($_POST["anoprevfim"]."-".$_POST["mesprevfim"]."-01");
			$dtfim     = antInjection($_POST["anofim"]."-".$_POST["mesfim"]."-01");			
			//$pessoa    = antInjection($_POST["pessoa"]);
			$desen     = antInjection($_POST["desen"]);
			$unid      = antInjection($_POST["unid"]);
			$muni      = antInjection($_POST["muni"]);			
			$local     = antInjection($_POST["local"]);
			$obs       = antInjection($_POST["obs"]);
			$status    = antInjection($_POST["status"]);			

			//verifica se ja existe registro com este nome...	
			$sqlE   = "SELECT ACO_Descricao FROM tb_acoes WHERE ACO_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){

				alert("ATENÇÃO: Já existe AÇÃO cadastrada com a descrição ".strtoupper($nome).".");
				anterior(-1);
				
			}else{
                            /*insert modicficado  campo  PES_ID retirado
                             * autor : alan melo
                             */

				$sql = "INSERT INTO tb_acoes (PPE_ID, ACO_Descricao, ACO_DataPrevInicio, ACO_DataInicio, ACO_DataPrevFinal, ACO_DataFinal, ";
				$sql.= "RDE_ID, UND_ID, MUN_IDMunicipio, ACO_Status, ACO_Obs, ACO_Local, PRG_IDPrograma) ";
				$sql.= "VALUES (".$proj.", '".$nome."', '".$dtprevini."', '".$dtini."', '".$dtprevfim."', '".$dtfim."', ".$desen.", ".$unid.", ";
				$sql.= "'".$muni."', '".$status."', '".$obs."', '".$local."', '".$prog."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAcoes")."&cad=0&mod=".$_GET["mod"]);
			}

		break;

		case "excluirAcoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql = "DELETE FROM tb_acoes WHERE ACO_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarAcoes")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarAcoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id        = antInjection(base64_decode($_POST["id"]));
			$prog      = antInjection($_POST["prog"]);
			$proj      = antInjection($_POST["proj"]);
			$nome      = antInjection($_POST["nome"]);
			$dtprevini = antInjection($_POST["anoprevini"]."-".$_POST["mesprevini"]."-01");
			$dtini     = antInjection($_POST["anoini"]."-".$_POST["mesini"]."-01");			
			$dtprevfim = antInjection($_POST["anoprevfim"]."-".$_POST["mesprevfim"]."-01");
			$dtfim     = antInjection($_POST["anofim"]."-".$_POST["mesfim"]."-01");
			//$pessoa    = antInjection($_POST["pessoa"]);
			$local     = antInjection($_POST["local"]);
			$desen     = antInjection($_POST["desen"]);
			$unid      = antInjection($_POST["unid"]);
			$muni      = antInjection($_POST["muni"]);			
			$status    = antInjection($_POST["status"]);
			$obs       = $_POST["obs"];			
			
			//verifica se ja existe registro com este nome...	
			$sqlE   = "SELECT ACO_Descricao FROM tb_acoes WHERE ACO_Descricao = '".$nome."' AND ACO_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){

				alert("ATENÇÃO: Já existe AÇÃO cadastrada com a descrição ".strtoupper($nome).".");
				anterior(-1);

			}else{
                                 /*update modicficado  campo  PES_ID retirado 
                                  * autor : alan melo
                                  */
				//atualizando...
				$sql = "UPDATE tb_acoes SET PPE_ID = '".$proj."', ACO_Descricao = '".$nome."', ACO_DataPrevInicio = '".$dtprevini."', ACO_DataInicio = '".$dtini."', ";
				$sql.= "ACO_DataPrevFinal = '".$dtprevfim."', ACO_DataFinal = '".$dtfim."', RDE_ID = ".$desen.", UND_ID = '".$unid."', ";
				$sql.= "ACO_Local= '".$local."', MUN_IDMunicipio = '".$muni."', ACO_Status = '".$status."', PRG_IDPrograma = '".$prog."', ";
				$sql.= "ACO_Obs = '".$obs."' WHERE ACO_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAcoes")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* SEGMENTO AGROPECUARIO */
		case "cadastrarSAG":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_sag.php");
		break;
		
		case "filtrarSAG":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_sag.php");
		break;
		
		case "incluirSAG":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT SAG_Descricao FROM tb_segmentos_agro WHERE SAG_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe SEGMENTO AGROPECUÁRIO cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_segmentos_agro (SAG_Descricao, SAG_Status) VALUES ('".$nome."', '".$status."')";										
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarSAG")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirSAG":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				$sqlE   = "SELECT SAG_ID FROM tb_indicadores_segmentos WHERE SAG_ID = ".$id;
				$existe = $banco->existe($sqlE);
				if ($existe){
					$totError++;
				}else{
					$sql = "DELETE FROM tb_segmentos_agro WHERE SAG_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarSAG")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarSAG":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select SAG_Descricao from tb_segmentos_agro where SAG_Descricao = '".$nome."' and SAG_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe SEGMENTO AGROPECUÁRIO cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_segmentos_agro SET SAG_Descricao = '".$nome."', SAG_Status = '".$status."' WHERE SAG_ID = ".$id;				
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarSAG")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* EMPRESAS */
		case "cadastrarEmpresa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_empresas.php");
		break;
		
		case "filtrarEmpresa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_empresas.php");
		break;
		
		case "incluirEmpresa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$reg	= antInjection($_POST["reg"]);
			
			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT EMP_Descricao FROM tb_empresas WHERE EMP_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe EMPRESA cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_empresas (REG_ID, EMP_Descricao, EMP_Status) ";
					$sql.= "VALUES ('".$reg."', '".$nome."', '".$status."')";		
					$id = $banco->ultimoId($sql);

					//criando a pasta...
					mkdir("../danfe/".completarComZero($id), 0777);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarEmpresa")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirEmpresa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				$sqlE   = "SELECT EMP_IDEmpresa FROM tb_usuarios WHERE EMP_IDEmpresa = ".$id;
				$existe = $banco->existe($sqlE);
				if ($existe){
					$totError++;
				}else{
					$sql = "DELETE FROM tb_empresas WHERE EMP_IDEmpresa = ".$id;
					$qry = $banco->executarQuery($sql);
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarEmpresa")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarEmpresa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$reg	= antInjection($_POST["reg"]);

			$sqlE   = "select EMP_Descricao from tb_empresas where EMP_Descricao = '".$nome."' and EMP_IDEmpresa <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe EMPRESA cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_empresas SET EMP_Descricao = '".$nome."', EMP_Status = '".$status."', ";
				$sql.= "REG_ID = '".$reg."' WHERE EMP_IDEmpresa = ".$id;								
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarEmpresa")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* MUNICIPIOS */
		case "cadastrarMunicipios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_municipios.php");
		break;
		
		case "filtrarMunicipios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_municipios.php");
		break;
		
		case "incluirMunicipios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$reg	= antInjection($_POST["reg"]);
			$dese   = antInjection($_POST["dese"]);
			$codigo = antInjection($_POST["codigo"]);
            $qtd    = antInjection($_POST["qtd"]);
			
			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT MUN_Descricao FROM tb_municipios WHERE MUN_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
				    
					alert("ATENÇÃO: Já existe MUNICÍPIO cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
                    
				}else{
				    
					$sql = "INSERT INTO tb_municipios (MUN_Descricao, RDE_ID, MUN_Codigo, MUN_Status, REG_ID, MUN_NumeroAgricultor) ";
					$sql.= "VALUES ('".$nome."', '".$dese."', '".$codigo."', '".$status."', '".$reg."', '".$qtd."')";
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMunicipios")."&cad=0&mod=".$_GET["mod"]);
				}

			}catch(usuarioException $sne){
			 
				$msg = $sne->getMessage();
                
			}catch(tecnicoException $te){
			 
				print $te;

			}
			
		break;

		case "excluirMunicipios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				$sql1 = "SELECT MUN_IDMunicipio FROM tb_familias WHERE MUN_IDMunicipio = ".$id;
				$row1 = $banco->listarArray($sql1);
				
				$sql2 = "SELECT MUN_IDMunicipio FROM tb_plano_ano WHERE MUN_IDMunicipio = ".$id;
				$row2 = $banco->listarArray($sql2);
				
				$sql3 = "SELECT MUN_IDMunicipio FROM tb_planoanual WHERE MUN_IDMunicipio = ".$id;
				$row3 = $banco->listarArray($sql3);
				
				$sql4 = "SELECT MUN_IDMunicipio FROM tb_usuarios WHERE MUN_IDMunicipio = ".$id;
				$row4 = $banco->listarArray($sql4);
				
				$tot  = count($row1) + count($row2) + count($row3) + count($row4);
				if ($tot > 0){
					$totError++;
				}else{
					$sql = "DELETE FROM tb_municipios WHERE MUN_IDMunicipio = ".$id;
					$qry = $banco->executarQuery($sql);
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMunicipios")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;

		case "alterarMunicipios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$reg	= antInjection($_POST["reg"]);
			$dese   = antInjection($_POST["dese"]);
			$codigo = antInjection($_POST["codigo"]);
            $qtd    = antInjection($_POST["qtd"]);

			$sqlE   = "select MUN_Descricao from tb_municipios where MUN_Descricao = '".$nome."' and MUN_IDMunicipio <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){

				alert("ATENÇÃO: Já existe MUNICÍPIO cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
                
			}else{

				$sql = "UPDATE tb_municipios SET MUN_Descricao = '".$nome."', MUN_Status = '".$status."', MUN_NumeroAgricultor = '".$qtd."', ";
				$sql.= "RDE_ID = '".$dese."', REG_ID = '".$reg."', MUN_Codigo = '".$codigo."' WHERE MUN_IDMunicipio = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMunicipios")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* PROGRAMAS */
		case "cadastrarProgramas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_programas.php");
		break;
		
		case "filtrarProgramas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_programas.php");
		break;
		
		case "cadastrarADDLP":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_linha_prog.php");
		break;
		
		case "filtrarADDLP":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_linha_prog.php");
		break;

		case "incluirADDLP":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$prog = antInjection($_POST["prog"]);
			
			//verifica se ja existe registro com este nome...
			for($i=0;$i<count($_POST["linha"]);$i++){
				
				$l = $_POST["linha"][$i];
				
				if (!empty($l)){
					$sqlE   = "SELECT PRG_IDPrograma FROM tb_programa_linha WHERE PRG_IDPrograma = ".$prog." AND LIN_ID = ".$l;				
					$existe = $banco->existe($sqlE);
					if (!$existe){ 
						$sql = "INSERT INTO tb_programa_linha (PRG_IDPrograma, LIN_ID) VALUES ('".$prog."', '".$l."')";
						$qry = $banco->executarQuery($sql);						
					}
				}
			}	

			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarADDLP")."&cad=0&mod=".$_GET["mod"]);
			
		break;
		
		case "excluirADDLP":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$id  = explode("#", $id);				
				
				$sql = "DELETE FROM tb_programa_linha WHERE PRG_IDPrograma = ".$id[0]." AND LIN_ID = ".$id[1];
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarADDLP")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "incluirProgramas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$obj    = antInjection($_POST["obj"]);
			$pessoa = antInjection($_POST["pessoa"]);
			$status = antInjection($_POST["status"]);
			
			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT PRG_Descricao FROM tb_programas WHERE PRG_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe PROGRAMA cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_programas (PRG_Descricao, PRG_Status, PRG_Objetivo, PES_ID) ";
					$sql.= "VALUES ('".$nome."', '".$status."', '".$obj."', ".$pessoa.")";
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProgramas")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirProgramas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				$sqlE = "SELECT PRG_IDPrograma FROM tb_projetos_pesquisas WHERE PRG_IDPrograma = ".$id;
				$existe = $banco->existe($sqlE);
				if ($existe){
					$totError++;
				}else{
					$sql = "DELETE FROM tb_programas WHERE PRG_IDPrograma = ".$id;
					$qry = $banco->executarQuery($sql);
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProgramas")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarProgramas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$obj    = antInjection($_POST["obj"]);
			$pessoa = antInjection($_POST["pessoa"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select PRG_Descricao from tb_programas where PRG_Descricao = '".$nome."' and PRG_IDPrograma <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROGRAMA cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_programas SET PRG_Descricao = '".$nome."', PRG_Status = '".$status."', PRG_Objetivo = '".$obj."', ";
				$sql.= "PES_ID = ".$pessoa." WHERE PRG_IDPrograma = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProgramas")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* INSTITUIÇÕES */
		case "cadastrarInstituicoes":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_instituicoes.php");
		break;
		
		case "filtrarInstituicoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_instituicoes.php");
		break;
		
		case "incluirInstituicoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT INS_Descricao FROM tb_instituicoes WHERE INS_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe PROGRAMA cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_instituicoes (INS_Descricao, INS_Status) VALUES ('".$nome."', '".$status."')";
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarInstituicoes")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirInstituicoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				$sqlE = "SELECT INS_ID FROM tb_pessoas WHERE INS_ID = ".$id;
				$existe = $banco->existe($sqlE);
				if ($existe){
					$totError++;
				}else{
					$sql = "DELETE FROM tb_instituicoes WHERE INS_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarInstituicoes")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarInstituicoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select INS_Descricao from tb_instituicoes where INS_Descricao = '".$nome."' and INS_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe INSTITUIÇÃO cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_instituicoes SET INS_Descricao = '".$nome."', INS_Status = '".$status."' WHERE INS_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarInstituicoes")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* PESSOAS */
		case "cadastrarPessoas":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_pessoas.php");
		break;
		
		case "filtrarPessoas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_pessoas.php");
		break;
		
		case "incluirPessoas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome    = antInjection($_POST["nome"]);
			$status  = antInjection($_POST["status"]);
			$prof    = antInjection($_POST["prof"]);
			$fone    = antInjection($_POST["fone"]);
			$celular = antInjection($_POST["celular"]);
			$email   = antInjection($_POST["email"]);
			$inst    = antInjection($_POST["inst"]);
                        $usuario = antInjection($_POST["usuario"]);
			$cargo   = antInjection($_POST["cargo"]);

			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT PES_Nome FROM tb_pessoas WHERE PES_Nome = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe PESSOA cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_pessoas (PES_Nome, PES_Profissao, PES_Fone, PES_Celular, INS_ID, PES_Email, PES_Status, CARGO_ID,USU_ID) ";
					$sql.= "VALUES ('".$nome."', '".$prof."', '".$fone."', '".$celular."', '".$inst."', '".$email."', '".$status."', ".$cargo.", ".$usuario.")";
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarPessoas")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirPessoas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_pessoas WHERE PES_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarPessoas")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarPessoas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id      = antInjection(base64_decode($_POST["id"]));
			$nome    = antInjection($_POST["nome"]);
			$status  = antInjection($_POST["status"]);
			$prof    = antInjection($_POST["prof"]);
			$fone    = antInjection($_POST["fone"]);
			$celular = antInjection($_POST["celular"]);
			$email   = antInjection($_POST["email"]);
			$inst    = antInjection($_POST["inst"]);
                        $usuario = antInjection($_POST["usuario"]);
			$cargo   = antInjection($_POST["cargo"]);

			//verifica se ja existe registro com este nome cadastrado...
			$sqlE   = "select PES_Nome from tb_pessoas where PES_Nome = '".$nome."' and PES_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PESSOA cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_pessoas SET PES_Nome = '".$nome."', PES_Profissao = '".$prof."', PES_Fone = '".$fone."', CARGO_ID = ".$cargo.", ";
				$sql.= "PES_Celular = '".$celular."', INS_ID = ".$inst.", PES_Email = '".$email."', PES_Status = '".$status."',USU_ID = ".$usuario;
				$sql.= " WHERE PES_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarPessoas")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* GRUPOS */
		case "cadastrarGrupos":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_grupos.php");
		break;
		
		case "filtrarGrupos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_grupos.php");
		break;
		
		case "incluirGrupos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT GRP_Descricao FROM tb_grupos WHERE GRP_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe GRUPO cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_grupos (GRP_Descricao, GRP_Status) VALUES ('".$nome."', '".$status."')";
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarGrupos")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirGrupos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_grupos WHERE GRP_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarGrupos")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarGrupos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			//verifica se ja existe registro com este nome cadastrado...
			$sqlE   = "select GRP_Descricao from tb_grupos where GRP_Descricao = '".$nome."' and GRP_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe GRUPO cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_grupos SET GRP_Descricao = '".$nome."', GRP_Status = '".$status."' WHERE GRP_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarGrupos")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* DESPESAS */
		case "cadastrarDespesas":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_despesas.php");
		break;
		
		case "filtrarDespesas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_despesas.php");
		break;
		
		case "incluirDespesas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$tipo   = antInjection($_POST["tipo"]);

			try{
				//verifica se ja existe registro com este nome...	
				$sqlE   = "SELECT DES_Descricao FROM tb_despesas WHERE DES_Descricao = '".$nome."'";				
				$existe = $banco->existe($sqlE);
				if ($existe){
					alert("ATENÇÃO: Já existe DESPESA cadastrado com a descrição ".strtoupper($nome).".");
					anterior(-1);
				}else{				
					$sql = "INSERT INTO tb_despesas (DES_Descricao, DES_Status, DES_Flag) VALUES ('".$nome."', '".$status."', '".$tipo."')";
					$qry = $banco->executarQuery($sql);
	
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarDespesas")."&cad=0&mod=".$_GET["mod"]);
				}
			}catch(usuarioException $sne){
				$msg = $sne->getMessage();
			}catch(tecnicoException $te){
				print $te;
			}
			
		break;

		case "excluirDespesas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_despesas WHERE DES_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarDespesas")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarDespesas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$tipo   = antInjection($_POST["tipo"]);

			//verifica se ja existe registro com este nome cadastrado...
			$sqlE   = "select DES_Descricao from tb_despesas where DES_Descricao = '".$nome."' and DES_ID <> ".$id;			
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe DESPESA cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_grupos SET DES_Descricao = '".$nome."', DES_Status = '".$status."', DES_Flag = '".$tipo."' WHERE DES_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarDespesas")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* CONTAS */
		case "cadastrarContas":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_contas.php");
		break;
		
		case "filtrarContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_contas.php");
		break;
		
		case "incluirContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$muni   = antInjection($_POST["muni"]);
			$nome   = antInjection($_POST["nome"]);
			$desp   = antInjection($_POST["desp"]);
			$grp    = antInjection($_POST["grp"]);
			$cont   = antInjection($_POST["cont"]);
			$venc   = antInjection($_POST["venc"]);
			$status = antInjection($_POST["status"]);

			$sql = "INSERT INTO tb_contas (MUN_IDMunicipio, DES_ID, GRP_ID, CON_IDContrato, CON_NomeCli, CON_Vencimento, CON_Status) ";
			$sql.= "VALUES (".$muni.", ".$desp.", ".$grp.", '".$cont."', '".$nome."', '".$venc."', '".$status."')";
			$qry = $banco->executarQuery($sql);

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarContas")."&cad=0&codigo=".base64_encode($muni)."&mod=".$_GET["mod"]);
			
		break;

		case "excluirContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_contas WHERE CON_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarContas")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$muni   = antInjection($_POST["muni"]);
			$nome   = antInjection($_POST["nome"]);
			$desp   = antInjection($_POST["desp"]);
			$grp    = antInjection($_POST["grp"]);
			$cont   = antInjection($_POST["cont"]);
			$venc   = antInjection($_POST["venc"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "UPDATE tb_contas SET MUN_IDMunicipio = ".$muni.", DES_ID = ".$desp.", GRP_ID = ".$grp.", CON_IDContrato = '".$cont."', ";
			$sql.= "CON_NomeCli = '".$nome."', CON_Vencimento = '".$venc."', CON_Status = '".$status."' WHERE CON_ID = ".$id;
			$qry = $banco->executarQuery($sql);

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarContas")."&alt=0&codigo=".base64_encode($muni)."&mod=".$_GET["mod"]);

		break;
		/**/
		
		/* LOCAL */
		case "cadastrarLocal":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_local.php");
		break;
		
		case "filtrarLocal":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_local.php");
		break;
		
		case "incluirLocal":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$mun    = antInjection($_POST["mun"]);
			$tipo   = antInjection($_POST["tipo"]);
			$status = antInjection($_POST["status"]);
			
			$sqlE = "SELECT LOC_ID FROM tb_local where LOC_Nome = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe LOCAL cadastrado com o nome ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "INSERT INTO tb_local (LOC_Nome, MUN_IDMunicipio, LOC_Tipo, LOC_Status) ";
				$sql.= "VALUES ('".$nome."', '".$mun."', '".$tipo."', '".$status."')";
				$qry = $banco->executarQuery($sql);
			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarLocal")."&cad=0&mod=".$_GET["mod"]);
		break;

		case "excluirLocal":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_local WHERE LOC_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarLocal")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarLocal":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$mun    = antInjection($_POST["mun"]);
			$tipo   = antInjection($_POST["tipo"]);
			$status = antInjection($_POST["status"]);
			
			
			$sqlE = "SELECT LOC_ID FROM tb_local where LOC_Nome = '".$nome."' AND LOC_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe LOCAL cadastrado com o nome ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_local SET LOC_Nome = '".$nome."', MUN_IDMunicipio = ".$mun.", LOC_Tipo = '".$tipo."', ";
				$sql.= "LOC_Status = '".$status."' WHERE LOC_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarLocal")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* MODULOS */
		case "cadastrarModulos":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_modulos.php");
		break;
		
		case "filtrarModulos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_modulos.php");
		break;
		
		case "incluirModulos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = ($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			$img = "";		
			
			$sqlE = "SELECT MOD_ID FROM tb_modulos where MOD_Nome = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe MODULO cadastrado com o nome ".strtoupper($nome).".");
				anterior(-1);
			}else{
				//upload da imagem do modulo...
				if (!empty($_FILES["upload"]["name"])) $img = uploadImg($_FILES["upload"], "../img/modulos/");
				
				$sql = "INSERT INTO tb_modulos (MOD_Nome, MOD_Status, MOD_Imagem) VALUES ('".$nome."', '".$status."', '".$img."')";
				$qry = $banco->executarQuery($sql);
			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarModulos")."&cad=0&mod=".$_GET["mod"]);
		break;

		case "excluirModulos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sqlE   = "SELECT MOD_ID FROM tb_apl_modulo WHERE MOD_ID = ".$id;
				$existe = $banco->existe($sqlE);
				if (!$existe){				
					//verifica se tem foto para excluir...
					$sql2 = "SELECT MOD_Imagem from tb_modulos WHERE MOD_ID = ".$id;
					$row2  = $banco->listarArray($sql2);
					if (!empty($row2[0]["MOD_Imagem"])) @unlink("../img/modulos/".$row2[0]["MOD_Imagem"]);
				
					$sql = "DELETE FROM tb_modulos WHERE MOD_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarModulos")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarModulos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = ($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			
			$sqlE = "SELECT MOD_ID FROM tb_modulos where MOD_Nome = '".$nome."' AND MOD_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe MODULO cadastrado com o nome ".strtoupper($nome).".");
				anterior(-1);
			}else{
				$sql2 = "SELECT MOD_Imagem from tb_modulos WHERE MOD_ID = ".$id;
				$row2  = $banco->listarArray($sql2);
			
				//upload da imagem do modulo...
				if (!empty($_FILES["upload"]["name"])){
					@unlink("../img/modulos/".$row2[0]["MOD_Imagem"]);
					$img = uploadImg($_FILES["upload"], "../img/modulos/");
				
					$sql = "UPDATE tb_modulos SET MOD_Nome = '".$nome."', MOD_Status = '".$status."', MOD_Imagem = '".$img."' WHERE MOD_ID = ".$id;
				}else{
					$sql = "UPDATE tb_modulos SET MOD_Nome = '".$nome."', MOD_Status = '".$status."' WHERE MOD_ID = ".$id;
				}
				$qry = $banco->executarQuery($sql);
	
				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarModulos")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/	
		
		/* APLICAÇÕES */
		case "cadastrarApl":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_apl.php");
		break;
		
		case "filtrarApl":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_apl.php");
		break;
		
		case "incluirApl":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome     = antInjection($_POST["nome"]);
			$tipo     = antInjection($_POST["tipo"]);
			$nomeacao = antInjection($_POST["nomeacao"]);
			$status   = antInjection($_POST["status"]);
			$pagina   = antInjection($_POST["pagina"]);
			
			$sqlE = "SELECT APL_ID FROM tb_aplicacoes where APL_Acao = '".$nomeacao."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				
				alert("ATENÇÃO: Já existe AÇÃO DA APLICAÇÃO cadastrado com o nome ".strtoupper($nomeacao).".");
				anterior(-1);
				
			}else{
				
				$sql = "INSERT INTO tb_aplicacoes (APL_Nome, APL_Tipo, APL_Acao, APL_Status, APL_Target) ";
				$sql.= "VALUES ('".$nome."', '".$tipo."', '".$nomeacao."', '".$status."', '".$pagina."')";
				$qry = $banco->executarQuery($sql);

			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarApl")."&cad=0&mod=".$_GET["mod"]);
		break;

		case "excluirApl":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sqlE   = "SELECT APL_ID FROM tb_apl_modulo WHERE APL_ID = ".$id;
				$existe = $banco->existe($sqlE);
				if (!$existe){				
					$sql = "DELETE FROM tb_aplicacoes WHERE APL_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarApl")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarApl":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id       = antInjection(base64_decode($_POST["id"]));
			$nome     = antInjection($_POST["nome"]);
			$tipo     = antInjection($_POST["tipo"]);			
			$nomeacao = antInjection($_POST["nomeacao"]);
			$status   = antInjection($_POST["status"]);
			$pagina   = antInjection($_POST["pagina"]);			
			
			$sqlE = "SELECT APL_ID FROM tb_aplicacoes where APL_Acao = '".$nomeacao."' AND APL_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe AÇÃO DA APLICAÇÃO cadastrado com o nome ".strtoupper($nomeacao).".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_aplicacoes SET APL_Nome = '".$nome."', APL_Tipo = '".$tipo."', APL_Acao = '".$nomeacao."', ";
				$sql.= "APL_Status = '".$status."', APL_Target = '".$pagina."' WHERE APL_ID = ".$id;
				$qry = $banco->executarQuery($sql);
	
				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarApl")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/

		/* APLICAÇÕES DOS MÓDULOS */
		case "cadastrarAplMod":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_apl_mod.php");
		break;
		
		case "filtrarAplMod":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_apl_mod.php");
		break;
		
		case "incluirAplMod":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome = antInjection($_POST["nome"]);
			
			$sqlA = "SELECT APL_ID, APL_Nome FROM tb_aplicacoes WHERE APL_Status = 'A' ORDER BY APL_Nome";
            $rowA = $banco->listarArray($sqlA);
            $a    = 0; 
            foreach($rowA as $l){
            	$id = $_POST["apl"][$a];
            	if (isset($id)){
            		$sqlE = "SELECT MOD_ID FROM tb_apl_modulo where MOD_ID = ".$nome." AND APL_ID = ".$id;
					$existe = $banco->existe($sqlE);
					if (!$existe){
						//incluindo...
						$sql = "INSERT INTO tb_apl_modulo (MOD_ID, APL_ID) VALUES (".$nome.", ".$id.")";
						$qry = $banco->executarQuery($sql);
					}
            	}
            	$a++;
            }
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAplMod")."&cad=0&mod=".$_GET["mod"]);
		break;

		case "excluirAplMod":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = explode("#", base64_decode($_POST["cod"][$i]));				
			
				$sql = "DELETE FROM tb_apl_modulo WHERE MOD_ID = ".$id[0]." AND APL_ID = ".$id[1];
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarAplMod")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		/**/
		
		/* MÓDULOS DOS USUÁRIOS */
		case "cadastrarModUsu":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_mod_usu.php");
		break;
		
		case "filtrarModUsu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_mod_usu.php");
		break;
		
		case "incluirModUsu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome = antInjection($_POST["nome"]);
			
			$sqlA = "SELECT MOD_ID, MOD_Nome FROM tb_modulos WHERE MOD_Status = 'A' ORDER BY MOD_Nome";
            $rowA = $banco->listarArray($sqlA);
            $a    = 0; 
            foreach($rowA as $l){
            	$id = $_POST["modu"][$a];				
            	if (isset($id)){
            		$sqlE = "SELECT MOD_ID FROM tb_mod_usuarios WHERE USU_IDUsuario = ".$nome." AND MOD_ID = ".$id;
					$existe = $banco->existe($sqlE);
					if (!$existe){
						//incluindo...
						$sql = "INSERT INTO tb_mod_usuarios (MOD_ID, USU_IDUsuario) VALUES (".$id.", ".$nome.")";
						$qry = $banco->executarQuery($sql);
					}
            	}
            	$a++;
            }
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarModUsu")."&cad=0&mod=".$_GET["mod"]);
		break;

		case "excluirModUsu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = explode("#", base64_decode($_POST["cod"][$i]));				
			
				$sql = "DELETE FROM tb_mod_usuarios WHERE MOD_ID = ".$id[0]." AND USU_IDUsuario = ".$id[1];
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarModUsu")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		/**/		
		
		case "cadastrarMovContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_mov_contas.php");
		break;
		
		case "filtrarMovContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_mov_contas.php");
		break;
		
		case "incluirMovContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
                        
			$con_id    	  = antinjection($_POST["cod_ident"]);
			$venc   	  = antInjection($conv->conData($_POST["venc"]));
			$pagto        = antInjection($conv->conData($_POST["pagto"]));
			$vlrprincipal = str_replace(",", ".", removeStrings($_POST["vlrprincipal"], "."));
			$vlrjuros 	  = str_replace(",", ".", removeStrings($_POST["vlrjuros"], "."));			
			$vlrmulta     = str_replace(",", ".", removeStrings($_POST["vlrmulta"], "."));											
			$status 	  = antInjection($_POST["status"]);
						
			$sql = "INSERT INTO tb_mov_contas (CON_ID, MOV_Vencimento, MOV_Pagamento, MOV_ValorPrincipal,MOV_ValorJuros,MOV_ValorMulta, MOV_Status) ";
			$sql.= "VALUES (".$con_id.", '".$venc."', '".$pagto."', ".$vlrprincipal.", ".$vlrjuros.", ".$vlrmulta.", '".$status."')";
			$qry = $banco->executarQuery($sql);
			
			$sql2 = "SELECT m.MUN_IDMunicipio FROM tb_municipios m ";
			$sql2.= "INNER JOIN tb_contas c ON (c.MUN_IDMunicipio = m.MUN_IDMunicipio) ";
			$sql2.= "INNER JOIN tb_mov_contas t ON (t.CON_ID = c.CON_ID) ";
			$sql2.= "WHERE c.CON_ID = ".$con_id;
		    $row2 = $banco->listarArray($sql2);

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMovContas")."&cad=0&codigo=".base64_encode($row2[0]["MUN_IDMunicipio"])."&mod=".$_GET["mod"]);
			
		break;

		case "excluirMovContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_mov_contas WHERE MOV_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//A??ES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMovContas")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarMovContas":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
				
			//verifica se existe...		
			$id           = antInjection(base64_decode($_POST["id"]));			
			$con_id       = antInjection($conv->conData($_POST["con_id"]));
			$venc   	  = antInjection($conv->conData($_POST["venc"]));
			$pagto        = antInjection($conv->conData($_POST["pagto"]));
			$vlrprincipal = str_replace(",", ".", removeStrings($_POST["vlrprincipal"], "."));
			$vlrjuros 	  = str_replace(",", ".", removeStrings($_POST["vlrjuros"], "."));			
			$vlrmulta     = str_replace(",", ".", removeStrings($_POST["vlrmulta"], "."));											
			$status 	  = antInjection($_POST["status"]);
			
			$sql = "UPDATE tb_mov_contas SET  MOV_Vencimento= '".$venc."', MOV_Pagamento = '".$pagto."', MOV_ValorPrincipal = '".$vlrprincipal."', ";
			$sql.= "MOV_ValorJuros = '".$vlrjuros."', MOV_ValorMulta = '".$vlrmulta."', MOV_Status = '".$status."' WHERE MOV_ID = ".$id;
			$qry = $banco->executarQuery($sql);
			
			$sql2 = "SELECT m.MUN_IDMunicipio FROM tb_municipios m ";
			$sql2.= "INNER JOIN tb_contas c ON (c.MUN_IDMunicipio = m.MUN_IDMunicipio) ";
			$sql2.= "INNER JOIN tb_mov_contas t ON (t.CON_ID = c.CON_ID) ";
			$sql2.= "WHERE c.CON_ID = ".$con_id;
		    $row2 = $banco->listarArray($sql2);

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMovContas")."&alt=0&codigo=".base64_encode($row2[0]["MUN_IDMunicipio"])."&mod=".$_GET["mod"]);

		break;
		
		/* CONVENIOS */
		case "cadastrarConvenios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_convenios.php");
		break;
		
		case "filtrarConvenios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_convenios.php");
		break;
		
		case "incluirConvenios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
                        
			$fonte  = antInjection($_POST["fonte"]);
			$nome   = antInjection($_POST["nome"]);
			$obs    = antInjection($_POST["obs"]);
			$status = antInjection($_POST["status"]);
						
			$sql = "INSERT INTO tb_convenios (IDFONTERECURSOS, CON_Descricao, CON_Obs, CON_Status) ";
			$sql.= "VALUES (".$fonte.", '".$nome."', '".$obs."', '".$status."')";
			$qry = $banco->executarQuery($sql);
			
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarConvenios")."&cad=0&mod=".$_GET["mod"]);
			
		break;

		case "excluirConvenios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			exit(" Manutençao consultar Diego Raphael 31847308! ");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_mov_contas WHERE MOV_ID = ".$id;				

				$qry = $banco->executarQuery($sql);
			}

			//A??ES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarConvenios")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarConvenios":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$id     = antInjection(base64_decode($_POST["id"]));			
			$fonte  = antInjection($_POST["fonte"]);
			$nome   = antInjection($_POST["nome"]);
			$obs    = antInjection($_POST["obs"]);
			$status = antInjection($_POST["status"]);
			
			$sql = "UPDATE tb_convenios SET IDFONTERECURSOS = '".$fonte."', CON_Descricao = '".$nome."', CON_Obs = '".$obs."', CON_Status = '".$status."' ";
			$sql.= "WHERE CON_ID = ".$id;
			$qry = $banco->executarQuery($sql);

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarConvenios")."&alt=0&mod=".$_GET["mod"]);

		break;
		/**/
		
		/* CONTAS SOLICITAÇÕES */
		case "cadastrarContasSOL":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_contas_sol.php");
		break;
		
		case "filtrarContasSOL":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_contas_sol.php");
		break;
		
		case "incluirContasSOL":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
                        
			$conta  = antInjection($_POST["conta"]);
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sqlE = "SELECT CON_Conta FROM tb_contas_solicitacoes WHERE CON_Conta = '".$conta."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe CONTA cadastrada com o CÓDIGO DA CONTA ".$conta.".");
				anterior(-1);
			}else{
				$sql = "INSERT INTO tb_contas_solicitacoes (CON_Conta, CON_Descricao, CON_Status) ";
				$sql.= "VALUES ('".$conta."', '".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);
			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarContasSOL")."&cad=0&mod=".$_GET["mod"]);
			
		break;

		case "excluirContasSOL":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			exit(" Manutençao consultar Diego Raphael 31847308! ");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_mov_contas WHERE MOV_ID = ".$id;				
				$qry = $banco->executarQuery($sql);
			}

			//A??ES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarContasSOL")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarContasSOL":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$id     = antInjection(base64_decode($_POST["id"]));			
			$conta  = antInjection($_POST["conta"]);
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sqlE = "SELECT CON_Conta FROM tb_contas_solicitacoes WHERE CON_Conta = '".$conta."' AND CON_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe CONTA cadastrada com o CÓDIGO DA CONTA ".$conta.".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_contas_solicitacoes SET CON_Conta = '".$conta."', CON_Descricao = '".$nome."', CON_Status = '".$status."' ";
				$sql.= "WHERE CON_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarContasSOL")."&alt=0&mod=".$_GET["mod"]);

		break;
		/**/
		
		/* COMPRAS */
		case "cadastrarCompras":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_compras.php");
		break;
		
		case "filtrarCompras":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_compras.php");
		break;
		
		case "incluirItensCompra":

			require_once("../lib/verifica.php");		
			/*	require_once("../lib/log.php");	*/	

			$id    = antInjection($_GET["id"]);
			$desc  = antInjection($_GET["desc"]);
			$valor = antInjection($_GET["valor"]);
			$qtd   = antInjection($_GET["qtd"]);
			$und   = antInjection($_GET["und"]);

 			$sql2 = "SELECT MAX(ITE_Item + 1) AS ITE_Item FROM tb_itens_solicitacoes WHERE DOC_ID = '".$id."'";
			$row2 = $banco->listarArray($sql2);
			$seq  = 1;
			if (count($row2) > 0) $seq = $row2[0]["ITE_Item"];
			
			$sql = "INSERT INTO tb_itens_solicitacoes (DOC_ID, ITE_Item, UND_ID, ITE_Descricao, ITE_QtdPedida, ITE_ValorPrevisto,ITE_StatusItem,ITE_Data,ITE_Hora) ";
			$sql.= "VALUES ('".$id."', '".$seq."', '".$und."', '".$desc."', '".$qtd."', '".$valor."', '4', '".date("Y-m-d")."', '".date("H:i:s")."')";
			$qry = $banco->executarQuery($sql);

		break;
		
		case "incluirCompras":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$und 			   = antInjection($_POST["und"]);
			$solicitante 	   = antInjection($_POST["solicitante"]);
			$fonte 			   = antInjection($_POST["fonte"]);
			$aca 			   = antInjection($_POST["aca"]);
			$meta 			   = antInjection($_POST["meta"]);
			$prazoentrega 	   = antInjection($_POST["prazo-entrega"]);
			$undtempoprazo     = antInjection($_POST["und-tempo-prazo"]);
			$formapgto  	   = antInjection($_POST["forma-pgto"]);
			$undtempoformapgto = antInjection($_POST["und-tempo-forma-pgto"]);
			$garantia    	   = antInjection($_POST["garantia"]);																								
			$undtempogarantia  = antInjection($_POST["und-tempo-garantia"]);
			$localentrega      = antInjection($_POST["local-entrega"]);
			$obs               = antInjection($_POST["local-entrega"]);			
			$fin               = antInjection($_POST["disp-financeira"]);
			$tipo              = antInjection($_POST["tipo"]);
			/**/

			$sql = "SELECT * FROM tb_controle_numeracao ";
			$sql.= "WHERE UND_IDUnidade = '".$und."' AND CON_Tipodoc = '1'";
		    $row = $banco->listarArray($sql);
			$numero_armazenado = intval($row[0]["CON_Numero"]);
			$numero_novo       = 0;
			$numero_exibir     = "";
			if ($numero_armazenado !== 0) {
				$recomecar = false;
				if (substr(date('Y'), 0, 4) == $row[0]["CON_Ano"]){
					$numero_armazenado++;
					$numero_novo = $numero_armazenado;
					$tam_numero = strlen(strval($numero_armazenado));
					//print $tam_numero."<br>";
					$zeros_numero = "000000";
					if ($tam_numero == 2) {
						$zeros_numero = "00000";
					}
					if ($tam_numero == 3) {
						$zeros_numero = "0000";
					}
					if ($tam_numero == 4) {
						$zeros_numero = "000";
					}
					if ($tam_numero == 5) {
						$zeros_numero = "00";
					}
					if ($tam_numero == 6) {
						$zeros_numero = "0";
					}
					if ($tam_numero == 7) {
						$zeros_numero = "";
					}
					$numero_exibir = substr(date('Y'), 2, 2).$zeros_numero.$numero_armazenado;
				} else {
					$recomecar = true;
				}
		
				$ano_registrar = "";
				if ($recomecar) {
					$numero_exibir = substr(date('Y'), 2, 2)."0000001";
					$ano_registrar = substr(date('Y'), 0, 4);
					$numero_novo = 1;
				}
				if ($ano_registrar != "") {
					$ano_registrar = "CON_Ano = ".$ano_registrar.",";
				}
				$sql2 = "UPDATE tb_controle_numeracao SET ".$ano_registrar." CON_Numero = ".$numero_novo;
				$sql2.= " WHERE UND_IDUnidade = ".$und." AND CON_Tipodoc = '1'";

			}else{
				$numero_novo = 1;
				$ano_registrar = substr(date('Y'), 0, 4);
				$numero_exibir = substr(date('Y'), 2, 2)."0000001";
				$sql2 = "INSERT INTO tb_controle_numeracao (UND_IDUnidade, CON_Tipodoc, CON_Ano, CON_Numero) ";
				$sql2.= "VALUES (".$und.", '1', '".$ano_registrar."', ".$numero_novo.")";
			}
			$qry2 = $banco->executarQuery($sql2);

			$data_atual       = date('Y-m-d');
			$hora_atual       = date('G:i:s');
			$empenhavel       = "1";
			$leitura          = "1";
			$status_documento = "3";

			$id_documento       = 0;
			$resultado_insercao = false;

			$sql3 = "INSERT INTO tb_documentos (USU_IDUsuario, DOC_IDSolicitante, DOC_Undsolicitante, DOC_Tipo, DOC_Numero, DOC_Data, DOC_Hora, ";
			$sql3.= "DOC_Leitura, DOC_StatusDoc) VALUES (".$_SESSION["sIDUSUARIO"].", ".$solicitante.", ".$und.", '1', '".$numero_exibir."', ";
			$sql3.= "'".$data_atual."', '".$hora_atual."', '".$leitura."', '".$status_documento."')";
			$idDOC= $banco->ultimoId($sql3);
			
			//INCLUINDO A SOLICITAÇÃO...			
			$sql4 = "INSERT INTO tb_solicitacoes (USU_IDUsuario, SOL_UndSolicitante, SOL_IDSolicitante, SOL_Tipo, DOC_ID, SOL_Numero, SOL_Data, ";
			$sql4.= "SOL_Hora, IDFONTERECURSOS, ACA_ID, SOL_Meta, SOL_OBSAplicMaterial, SOL_LocalEntrega, SOL_PrazoEntrega, SOL_UndTempoPrazoEntrega, ";
			$sql4.= "SOL_Garantia, SOL_UndTempoGarantia, SOL_FormaPagto, SOL_UndTempoFormaPagto, SOL_DispFinanceira, SOL_Empenhavel, SOL_Leitura, SOL_Status, SOL_Aquisicao) ";
			$sql4.= "VALUES (".$_SESSION["sIDUSUARIO"].", ".$und.", ".$solicitante.", '1', ".$idDOC.", '".$numero_exibir."', '".$data_atual."', '".$hora_atual."', ";
			$sql4.= "'".$fonte."',  '".$aca."','".$meta."', '".$obs."', '".$localentrega."', '".$prazoentrega."', '".$undtempoprazo."', '".$garantia."', ";
			$sql4.= "'".$undtempogarantia."', '".$formapgto."', '".$undtempoformapgto."', '".$fin."', '".$empenhavel."', '".$leitura."', '".$status_documento."', '".$tipo."')";
			$qry4 = $banco->executarQuery($sql4);
			
			//INCLUINDO AS AVALIAÇÕES DOS DOCUMENTOS...
			$sql5 = "INSERT INTO tb_avaliacoes_documentos (DOC_ID, USU_IDUsuario, AVA_Data, AVA_Hora, AVA_Avaliacao, AVA_Status) ";
			$sql5.= "VALUES (".$idDOC.", ".$_SESSION["sIDUSUARIO"].", '".$data_atual."', '".$hora_atual."', ".$status_documento.", 'A')";
			$idAVA= $banco->ultimoId($sql3);
//			exit($sql5);			
			
			$sql6 = "SELECT UND_ID, UND_Codigo FROM tb_unidades_solicitacoes ";
			$sql6.= "WHERE UND_ID = ".$und;
		    $row6 = $banco->listarArray($sql6);
			if (substr($row6[0]["UND_Codigo"], 6, 2) != "00"){
				$sqlUND = "SELECT UN.UND_ID, UN.UND_Descricao FROM tb_unidades_solicitacoes UN
								  INNER JOIN tb_hierarquia_unidades HI 
								  ON (HI.CODUNDSUPERIOR = UN.UND_Codigo)
								  WHERE HI.CODUNDSUBORDINADA = '".$row6[0]["UND_Codigo"]."'
								  AND SUBSTR(HI.CODUNDSUPERIOR,1,5) = SUBSTR('".$row6[0]["UND_Codigo"]."', 1, 5) "; 
			}elseif (substr($row6[0]["UND_Codigo"], 3, 5) != "00.00"){
				$sqlUND = "SELECT UN.UND_ID, UN.UND_Descricao FROM tb_unidades_solicitacoes UN
								  INNER JOIN tb_hierarquia_unidades HI 
								  ON (HI.CODUNDSUPERIOR = UN.UND_Codigo)
								  WHERE HI.CODUNDSUBORDINADA = '".$row6[0]["UND_Codigo"]."'
								  AND CODUNDSUPERIOR = CONCAT(SUBSTR('".$row6[0]["UND_Codigo"]."',1,2),'.00.00') ";
			}else{
				$sqlUND = "SELECT UN.UND_ID, UN.UND_Descricao FROM tb_unidades_solicitacoes UN
								  INNER JOIN tb_hierarquia_unidades HI 
								  ON (HI.CODUNDSUPERIOR = UN.UND_Codigo)
								  WHERE HI.CODUNDSUBORDINADA = '".$row6[0]["UND_Codigo"]."'
								  AND HI.CODUNDSUPERIOR <> '".$row6[0]["UND_Codigo"]."' ORDER BY UN.UND_ID "; 
			}
		
			$unddes = 2;
			$locdes = "SUPERINTENDÊNCIA DE ADMINISTRAÇÃO E FINANÇAS";
		
			$rowUND = $banco->listarArray($sqlUND);
			
			//INCLUINDO DESPACHOS...
			$sql7 = "INSERT INTO tb_despachos (DOC_ID, USU_IDUsuario, DES_DataEmissao, DES_HoraEmissao, DES_IDUndOrigem, ";
			$sql7.= "DES_IDUsrDestino, DES_IDUndDestino, DES_Propriedade, DES_Despacho, DES_Leitura) ";
			$sql7.= "VALUES (".$idDOC.", ".$_SESSION["sIDUSUARIO"].", '".$data_atual."', '".$hora_atual."', ".$und.", ";
			$sql7.= "'', '".$unddes."', 'A', 'Solicitação de $palavra_texto submetida a avaliação do(a) $locdes.', '1')";
			$idDES= $banco->ultimoId($sql7);
			
			/*			
			$sql8 = "UPDATE tb_documentos SET DES_ID = ".$idDES." WHERE DOC_ID = ".$idDOC;
			$qry8 = $banco->executarQuery($sql8);
			*/
			
			//redireciona para os detalhes da compra...
			header("location: ../lib/Fachada.php?acao=".base64_encode("detalhesCompras")."&id=".base64_encode($idDOC)."&mod=".$_GET["mod"]);
			exit;
		break;

		case "detalhesCompras":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");			
			require_once("../cadastros/frm_detalhes_compras.php");
		break;		

		case "excluirCompras":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			exit(" Manutençao consultar Diego Raphael 31847308! ");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				$sql = "DELETE FROM tb_mov_contas WHERE MOV_ID = ".$id;				

				$qry = $banco->executarQuery($sql);
			}

			//A??ES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarCompras")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarCompras":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			print_r($_POST); exit;			
			
			$id     = antInjection(base64_decode($_POST["id"]));			
			$conta  = antInjection($_POST["conta"]);
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sqlE = "SELECT CON_Conta FROM tb_contas_solicitacoes WHERE CON_Conta = '".$conta."' AND CON_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe CONTA cadastrada com o CÓDIGO DA CONTA ".$conta.".");
				anterior(-1);
			}else{
				$sql = "UPDATE tb_contas_solicitacoes SET CON_Conta = '".$conta."', CON_Descricao = '".$nome."', CON_Status = '".$status."' ";
				$sql.= "WHERE CON_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarCompras")."&alt=0&mod=".$_GET["mod"]);

		break;
		/**/				
		
		/* COMPRAS */
		case "cadastrarProgPessoa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_prog_pessoas.php");
		break;
		
		case "filtrarProgPessoa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_prog_pessoas.php");
		break;
		
		case "incluirProgPessoa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$prog 	= antInjection($_POST["prog"]);
			$flag   = antInjection($_POST["flag"]);
			
			for($i=0;$i<count($_POST["pessoa"]);$i++){
			
				$pes = $_POST["pessoa"][$i];
				if ( (!empty($pes)) && (!empty($flag)) && (!empty($prog)) ){
				
					$sqlE = "SELECT PES_ID FROM tb_programa_pessoa WHERE PES_ID = ".$pes." AND PRG_IDPrograma = ".$prog." AND PPE_Flag = '".$flag."'";				
					$existe = $banco->existe($sqlE);
					if (!$existe){
						//incluindo...
						$sql = "INSERT INTO tb_programa_pessoa (PES_ID, PRG_IDPrograma, PPE_Flag) VALUES (".$pes.", ".$prog.", '".$flag."')";
						$qry = $banco->executarQuery($sql);				
					}
				}	
			}
			//redirecionamento...
			header("location: ../lib/Fachada.php?acao=". base64_encode("cadastrarProgPessoa")."&cad=0");
			exit;
			
		break;

		case "excluirProgPessoa":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id = base64_decode($_POST["cod"][$i]);
				$id = explode("@", $id);

				$sql = "DELETE FROM tb_programa_pessoa WHERE PRG_IDPrograma = ".$id[0]." AND PES_ID = ".$id[1]." AND PPE_Flag = '".$id[2]."'";
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProgPessoa")."&exc=0&mod=".$_GET["mod"]);
		break;
		/**/
		
		/* MENUS */
		case "cadastrarMenu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_menu.php");
		break;
		
		case "filtrarMenu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_menu.php");
		break;
		
		case "incluirMenu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome    = antInjection($_POST["nome"]);
			$status  = antInjection($_POST["status"]);
			$menucss = $_POST["menucss"];
			
			$sqlE = "SELECT MEN_ID FROM tb_menu WHERE MEN_Nome = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if (!$existe){
				//incluindo...
				$sql = "INSERT INTO tb_menu (MEN_Nome, MEN_CSS, MEN_Status) VALUES ('".$nome."', '".$menucss."', '".$status."')";
				$qry = $banco->executarQuery($sql);				
			}
			//redirecionamento...
			header("location: ../lib/Fachada.php?acao=". base64_encode("cadastrarMenu")."&cad=0&mod=".$_GET["mod"]);
			exit;
			
		break;
		
		case "alterarMenu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$id      = antInjection(base64_decode($_POST["id"]));
			$nome    = antInjection($_POST["nome"]);
			$status  = antInjection($_POST["status"]);
			$menucss = $_POST["menucss"];
			
			$sqlE   = "select MEN_ID from tb_menu where MEN_Nome = '".$nome."' and MEN_ID <> ".$id;
			$existe = $banco->existe($sqlE);			
			if ($existe){
				alert("ATENÇÃO: Já existe MENU cadastrado com o nome ".$nome.".");
				anterior(-1);
			}else{			
				$sql = "UPDATE tb_menu SET MEN_Nome = '".$nome."', MEN_Status = '".$status."', MEN_CSS = '".$menucss."' WHERE MEN_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMenu")."&alt=0&mod=".$_GET["mod"]);
			}

		break;


		case "excluirMenu":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id   = base64_decode($_POST["cod"][$i]);
				$sqlE = "SELECT MEN_ID FROM tb_menu_modulo WHERE MEN_ID = ".$id;
				$existe = $banco->existe($sqlE);
				if (!$existe){
					$sql = "DELETE FROM tb_menu WHERE MEN_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMenu")."&exc=0&mod=".$_GET["mod"]);
		break;
		/**/
		
		/* MENU DOS MÓDULOS */
		case "cadastrarMenuMod":		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_menu_mod.php");
		break;
		
		case "filtrarMenuMod":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_menu_mod.php");
		break;
		
		case "incluirMenuMod":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$nome = antInjection($_POST["nome"]);
			$sqlA = "SELECT * FROM tb_modulos WHERE MOD_Status = 'A' ORDER BY MOD_Nome";
            $rowA = $banco->listarArray($sqlA);
            $a    = 0;
            foreach($rowA as $l){
            	$id = $_POST["apl"][$a];
            	if (isset($id)){
            		$sqlE = "SELECT MOD_ID FROM tb_menu_modulo where MOD_ID = ".$id." AND MEN_ID = ".$nome;
					$existe = $banco->existe($sqlE);
					if (!$existe){
						//incluindo...
						$sql = "INSERT INTO tb_menu_modulo (MEN_ID, MOD_ID) VALUES (".$nome.", ".$id.")";
						$qry = $banco->executarQuery($sql);
					}
            	}
            	$a++;
            }
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMenuMod")."&cad=0&mod=".$_GET["mod"]);
		break;

		case "excluirMenuMod":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = explode("#", base64_decode($_POST["cod"][$i]));
				
				$sql = "DELETE FROM tb_menu_modulo WHERE MOD_ID = ".$id[0]." AND MEN_ID = ".$id[1];
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMenuMod")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		/**/
		
		/* METODOLOGIAS */
		case "cadastrarMeto":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_metodologias.php");
		break;
		
		case "filtrarMeto":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_metodologias.php");
		break;
		
		case "incluirMeto":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select MET_Descricao from tb_metodologia where MET_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe METODOLOGIA cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_metodologia (MET_Descricao, MET_Status) values ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMeto")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirMeto":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_metodologia WHERE MET_IDMetodologia = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarMeto")."&mod=".$_GET["mod"]);
		break;

		case "alterarMeto":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select MET_Descricao from tb_metodologia where MET_Descricao = '".$nome."' and MET_IDMetodologia <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe METODOLOGIA cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "UPDATE tb_metodologia set MET_Descricao = '".$nome."', MET_Status = '".$status."' where MET_IDMetodologia = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarMeto")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* ORIENTACOES */
		case "cadastrarOrientacoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_orientacoes.php");
		break;
		
		case "filtrarOrientacoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_orientacoes.php");
		break;
		
		case "incluirOrientacoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select ORI_Descricao from tb_orientacoes WHERE ORI_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe ORIENTAÇÕES cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_orientacoes (ORI_Descricao, ORI_Status) values ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarOrientacoes")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirOrientacoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_orientacoes WHERE ORI_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarOrientacoes")."&mod=".$_GET["mod"]);
		break;

		case "alterarOrientacoes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT ORI_Descricao FROM tb_orientacoes WHERE ORI_Descricao = '".$nome."' and ORI_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe ORIENTAÇÕES cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{

				$sql = "UPDATE tb_orientacoes set ORI_Descricao = '".$nome."', ORI_Status = '".$status."' where ORI_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarOrientacoes")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* FONTES FINANCIAMENTOS */
		case "cadastrarFinanciamentos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_fontes_fin.php");
		break;
		
		case "filtrarFinanciamentos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_fontes_fin.php");
		break;
		
		case "incluirFinanciamentos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select FIN_Descricao from tb_fontes_financiamentos WHERE FIN_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe FONTES DE FINANCIAMENTOS cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{			
				$sql = "INSERT INTO tb_fontes_financiamentos (FIN_Descricao, FIN_Status) VALUES ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarFinanciamentos")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "alterarFinanciamentos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);
			
			$sqlE   = "select FIN_Descricao from tb_fontes_financiamentos WHERE FIN_Descricao = '".$nome."' AND FIN_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe FONTES DE FINANCIAMENTOS cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{			
				$sql = "UPDATE tb_fontes_financiamentos SET FIN_Descricao = '".$nome."', FIN_Status = '".$status."' WHERE FIN_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarFinanciamentos")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirFinanciamentos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_fontes_financiamentos WHERE FIN_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarFinanciamentos")."&mod=".$_GET["mod"]);
		break;
		/**/
		
		/* ORIENTAÇÕES X PROJETOS*/
		case "cadastrarOriProj":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_ori_proj.php");
		break;
		
		case "filtrarOriProj":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_ori_proj.php");
		break;
		
		case "incluirOriProj":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome = antInjection($_POST["nome"]);

			for($i=0;$i<count($_POST["apl"]);$i++){
				$id  = $_POST["apl"][$i];
				
				$sqlE   = "SELECT * FROM tb_orientacoes_x_projetos WHERE ORI_ID = ".$id." AND PRJ_IDProjeto = ".$nome;
				$existe = $banco->existe($sqlE);
				if (!$existe){
					$sql = "INSERT INTO tb_orientacoes_x_projetos (ORI_ID, PRJ_IDProjeto) VALUES ('".$id."', ".$nome.")";
					$qry = $banco->executarQuery($sql);
				}
			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarOriProj")."&cad=0&mod=".$_GET["mod"]);

		break;
		
		case "excluirOriProj":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = explode("@", base64_decode($_POST["cod"][$i]));
				
				//excluindo...
				$sql = "DELETE FROM tb_orientacoes_x_projetos WHERE ORI_ID = ".$id[0]." AND PRJ_IDProjeto = ".$id[1];
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarOriProj")."&mod=".$_GET["mod"]);
		break;

		case "alterarOriProj":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT FIN_Descricao FROM tb_fontes_financiamentos WHERE FIN_Descricao = '".$nome."' and FIN_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe FONTES DE FINANCIAMENTOS cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{

				$sql = "UPDATE tb_fontes_financiamentos SET FIN_Descricao = '".$nome."', FIN_Status = '".$status."' WHERE FIN_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarOriProj")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
		
		/* PROCESSOS */
		case "cadastrarProcessos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_processos.php");
		break;
		
		case "filtrarProcessos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_processos.php");
		break;
		
		case "incluirProcessos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$prazo  = antInjection($_POST["prazo"]);
			$und    = antInjection($_POST["und"]);
			$tipo   = antInjection($_POST["tipo"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT PRO_Descricao FROM tb_processos WHERE PRO_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROCESSO cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{			
				$sql = "INSERT INTO tb_processos (PRO_Descricao, UND_ID, PRO_Prazo, STA_ID, PRO_Tipo) ";
				$sql.= "VALUES ('".$nome."', '".$und."', '".$prazo."', '".$status."', '".$tipo."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProcessos")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "alterarProcessos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$prazo  = antInjection($_POST["prazo"]);
			$und    = antInjection($_POST["und"]);
			$tipo   = antInjection($_POST["tipo"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT PRO_Descricao FROM tb_processos WHERE PRO_Descricao = '".$nome."' AND PRO_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROCESSO cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{

				$sql = "UPDATE tb_processos SET PRO_Descricao = '".$nome."', STA_ID = '".$status."', UND_ID = ".$und.", PRO_Prazo = ".$prazo;
				$sql.= " AND PRO_Tipo = ".$tipo." WHERE PRO_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProcessos")."&alt=0&mod=".$_GET["mod"]);
			}

		break;

		case "excluirProcessos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);				
				
				//verifica se existe...
				$sql = "SELECT PRO_ID FROM tb_status_solicitacao WHERE PRO_ID = ".$id;
				$row = $banco->listarArray($sql);				
				if (count($row) == 0){
					//excluindo...
					$sql2 = "DELETE FROM tb_processos WHERE PRO_ID = ".$id;
					$qry2 = $banco->executarQuery($sql2);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProcessos")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		/**/
		
		//FAMILIAS...
		case "filtrarFamilias":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_familias.php");
		break;
		/**/

		/* PEDIDOS */
		case "filtrarNFE":
			require_once("../class/ConexaoFirebird.php");
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_nfe.php");
		break;
		
		case "filtrarNFE2":
			require_once("../class/ConexaoFirebird.php");
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_nfe2.php");
		break;

		case "cadastrarPedidos":
			require_once("../class/ConexaoFirebird.php");
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			include_once("../cadastros/frm_pedidos.php");
		break;
		
		case "filtrarPedidos":
			require_once("../class/ConexaoFirebird.php");		
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_pedidos.php");
		break;
		
		case "detalhePedido":
        
            $_GET["empresa"] = $_GET["empresa"];
			require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_detalhes_pedidos.php");
		break;

		case "incluirPedidos":
            $_GET["empresa"] = $_POST["empresa"];
            
			require_once("../class/ConexaoFirebird.php");
			require_once("../lib/log.php");

			$data      = antInjection($conv->conData($_POST["data"]));
			$tipo      = antInjection($_POST["tipo"]);
			$idc       = antInjection($_POST["id_nome"]);
            $trans     = antInjection($_POST["trans"]);
            $placa     = antInjection(substr(strtoupper($_POST["placa"]), 0, 7));
            $uf        = antInjection($_POST["uf"]);
            $tipofrete = antInjection($_POST["tipofrete"]);
            $frete     = antInjection($_POST["frete"]);
            $seguro    = antInjection($_POST["seguro"]);
            $outros    = antInjection($_POST["outros"]);
            $cidade    = antInjection($_POST["cidade"]);
            $obs       = $_POST["obs"];
            if (empty($frete)) $frete   = 0;
            if (empty($outros)) $outros = 0;
            if (empty($seguro)) $seguro = 0;

			/* CALCULA O IDPEDIDO */
			$sqlP = "SELECT MAX(IDPEDIDO + 1) AS IDPEDIDO FROM TB_PEDIDOS_HEADER";
			$qryP = ibase_query($res, $sqlP);
			$rowP = ibase_fetch_assoc($qryP);
			if (!$rowP["IDPEDIDO"]) $rowP["IDPEDIDO"] = 1;
			$idPED = 0;
			$idPED = $rowP["IDPEDIDO"];
			/**/

			$sql = "INSERT INTO TB_PEDIDOS_HEADER (IDPEDIDO, DATA, TIPO, IDMUNICIPIO, SEQUENCIA, IDCLIFOR, DANFE, IDUSUARIO, STATUS, OBS, ";
            $sql.= "TRANSPORTADORA, PLACA, UF, TIPOFRETE, VALORFRETE, VALORSEGURO, OUTRASDESPESAS, CIDADE) ";
            $sql.= "VALUES ('".$idPED."', '".$data."', '".$tipo."', '".$_SESSION["sIDMunicipio"]."', '0', '".$idc."', '', ";
			$sql.= "'".$_SESSION["sIDUSUARIO"]."', 'I', '".$obs."', '".$trans."', '".$placa."', ";
            $sql.= "'".$uf."', '".$tipofrete."', '".$frete."', '".$seguro."', '".$outros."', '".$cidade."')";
            //exit($sql);
			$qry = ibase_query($res, $sql);
			ibase_commit($res);

			goto2("../lib/Fachada.php?acao=".base64_encode("detalhePedido")."&cad=0&mod=".$_GET["mod"]."&idped=".base64_encode($idPED));

		break;
		
		case "finalizarPedido":
			require_once("../lib/verifica.php");
			require_once("../class/ConexaoFirebird.php");
			
			$id = antInjection($_POST["id"]);
			
			$sqlP = "SELECT IDPEDIDO FROM TB_PEDIDOS_ITENS WHERE IDPEDIDO = ".$id;
			$qryP = ibase_query($res, $sqlP);
			$rowP = ibase_fetch_assoc($qryP);
			if (!empty($rowP["IDPEDIDO"])){
			
				/* CALCULA O SEQUENCIAL */
				$sqlC = "SELECT MAX(SEQUENCIA + 1) AS SEQUENCIA FROM TB_PEDIDOS_HEADER WHERE IDMUNICIPIO = '".$_SESSION["sIDMunicipio"]."'";
				$qryC = ibase_query($res, $sqlC);
				$rowC = ibase_fetch_assoc($qryC);
				if (!$rowC["SEQUENCIA"]) $rowC["SEQUENCIA"] = 1;
				$seq = 0;
				$seq = $rowC["SEQUENCIA"];
				
				/* ATUALIZA O SEQUENCIAL DO PEDIDO DO MUNICIPIO */
				$sql = "UPDATE TB_PEDIDOS_HEADER SET SEQUENCIA = ".$seq.", STATUS = 'A' WHERE IDPEDIDO = ".$id;
				$qry = ibase_query($res, $sql);
				
				alert(strtoupper($_SESSION["sNOME_USUARIO"]).", Pedido finalizado com sucesso.");

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarPedidos")."&mod=".$_POST["mod"]);			

			}else{
			
				alert(strtoupper($_SESSION["sNOME_USUARIO"]).", Pedido precisa ter 1 (UM) ou mais itens para ser finalizado.");
				anterior(-1);

			}

		break;
		
		case "alterarPedidos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$prazo  = antInjection($_POST["prazo"]);
			$und    = antInjection($_POST["und"]);
			$tipo   = antInjection($_POST["tipo"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT PRO_Descricao FROM tb_processos WHERE PRO_Descricao = '".$nome."' AND PRO_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe PROCESSO cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{

				$sql = "UPDATE tb_processos SET PRO_Descricao = '".$nome."', PRO_Status = '".$status."', UND_ID = ".$und.", PRO_Prazo = ".$prazo;
				$sql.= " AND PRO_Tipo = ".$tipo." WHERE PRO_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarPedidos")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "incluirItensPedido":
		
			require_once("../class/ConexaoFirebird.php");			
			require_once("../lib/verifica.php");
//			 require_once("../lib/log.php");

			$id    = antInjection($_GET["id"]);
			$idprd = antInjection($_GET["idprd"]);
			$valor = antInjection($_GET["valor"]);
			$qtd   = antInjection($_GET["qtd"]);
			$mod   = antInjection($_POST["mod"]);
			
			//VERIFICA SE JA EXISTE ITEM ADICIONADO AO PEDIDO...
			$sql = "SELECT IDPEDIDO FROM TB_PEDIDOS_ITENS WHERE IDPEDIDO = ".$id." AND IDPRODUTO = '".$idprd."'";
			$qry = ibase_query($res, $sql);
			$row = ibase_fetch_object($qry);
			if (empty($row->IDPEDIDO)){

				//INCLUINDO...
				$sql2 = "INSERT INTO TB_PEDIDOS_ITENS (IDPEDIDO, IDPRODUTO, QUANTIDADE, VLRUNITARIO) ";
				$sql2.= "VALUES ('".$id."', '".$idprd."', '".$qtd."', '".$valor."')";
				$qry2 = ibase_query($res, $sql2);				
				ibase_commit($res);
				
			}else{
				echo "
				<td align=\"center\" colspan=\"2\">
					<table width=\"250px\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
							<td align=\"center\" class=\"titulo_erro\">ATEN&Ccedil;&Atilde;O!</td>
						</tr> 
						<tr>
							<td align=\"center\" class=\"msg_erro\">Item j&aacute; existe nesse Pedido.</td>
						</tr>
					</table>
				</td>"; 
			}

		break;

		case "excluirPedidos":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);				
				
				//excluindo...
				$sql = "DELETE FROM TB_PEDIDOS_ITENS WHERE IDPEDIDO = ".$id;
				$qry = ibase_query($res, $sql);	
				
				$sql2 = "DELETE FROM TB_PEDIDOS_HEADER WHERE IDPEDIDO = ".$id;
				$qry2 = ibase_query($res, $sql2);
				
				ibase_commit($res);
				
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarPedidos")."&exc=".$totError."&mod=".$_GET["mod"]);
			
		break;
		/**/

		case "importarSigater":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_importar_sigater.php");
		break;
        
		case "frmuploadArquivosSigater":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
            require_once("../consultas/filtro_upload_sigater.php");

		break;
        
        case "uploadArquivosSigater": 

            if (!empty($_FILES)) {

                $tempFile   = $_FILES['Filedata']['tmp_name'];
                $targetPath = "../tmp/sigater/"; //$_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/';
                $targetFile =  str_replace('//','/',$targetPath) .$_FILES['Filedata']['name'];
                move_uploaded_file($tempFile,$targetFile);
            }

        break;        
		
		case "importandoSigater":

            session_start();
            ini_set("memory_limit", "128M");
            ini_set("max_execution_time", "100");
            set_time_limit(0);
            
            if (empty($_SESSION["sIDUSUARIO"])) $_SESSION["sIDUSUARIO"] = "0";
            
            $diretorio = "../tmp/sigater/"; //getcwd(); 
            $ponteiro  = opendir($diretorio);

            while ($nome_itens = readdir($ponteiro)) {                
                if ( ($nome_itens != ".") && ($nome_itens != "..") ) $contents[] = $nome_itens;
            }
            sort($contents);

			$contArquivos = 0;
            $arqComErros  = 0;

			foreach ($contents as $arquivo)	{

				//	abre o arquivo para serem feitos os inserts/updates no banco
				$handle = @fopen($diretorio.$arquivo, "r");
                $valida = explode("_cad", $arquivo);
                
                pa($valida);
                exit;

				if ($handle){
				
					$l = 0;
					while (!feof($handle)) {
						$buffer = fgets($handle, 4096);					
						$campo  = explode(';', $buffer);
                        $s      = "";
                        
						//	elimina a 1ª e a última linha (caso a última linha esteja vazia).
						if ((++$l == 1) || !$buffer) continue;
                        
                        $sqlC = "";
                        if ($valida[1] == "cad.txt"){
                            
                            //pa($campo).'<br>';                            
    						$idmunicipio		= antInjection(intval($campo[1]));
    						$nome				= antInjection($campo[2]);
    						$endereco		    = antInjection($campo[3]);
    						$cpf				= antInjection($campo[4]);
    						$rg				    = antInjection($campo[5]);
                            
                            $d                  = explode("/", trim($campo[6]));                            
                            if (strlen($d[0])== "1") $d[0] = "0".$d[0];
                            if (strlen($d[1])== "1") $d[1] = "0".$d[1];                            
    						$data		 		= $d[2]."-".$d[1]."-".$d[0];
                             
    						$estadocivel		= antInjection($campo[7]);
    						$conjuge			= antInjection($campo[8]);
    						$cpfconjuge		    = antInjection($campo[9]);
    						$rgconjuge		    = antInjection($campo[10]);
    						$rendagricola		= antInjection($campo[11]);
    						$individuos		    = antInjection(intval($campo[12]));
    						$grauinstprod		= antInjection($campo[13]);
    						$grauinstconj		= antInjection($campo[14]);
    						$tppropriedade	    = antInjection($campo[15]);
    						$area				= antInjection($campo[16]);
    						$apelido			= antInjection($campo[17]);
    						$identidade		    = antInjection(soNumeros($campo[18]));
    						$titposse			= antInjection($campo[19]);
    						$agua				= antInjection($campo[20]);
    						$captacao			= antInjection($campo[21]);
    						$estrada			= antInjection($campo[22]);
    						$eletricidade		= antInjection($campo[23]);
    						$distancia			= antInjection($campo[24]);
    						$idusuario			= antInjection(intval($campo[25]));
    						$propriedade		= antInjection($campo[26]);
    						$sexo				= antInjection($campo[27]);
    						$rendapecuaria	    = antInjection($campo[28]);
    						$rgemissor		    = antInjection($campo[29]);
                            
                            $d                  = explode("/", antInjection($campo[30]));                            
                            if (strlen($d[0])== "1") $d[0] = "0".$d[0];
                            if (strlen($d[1])== "1") $d[1] = "0".$d[1];
                            $rgdata		 	    = $d[2]."-".$d[1]."-".$d[0];
                            
    						$naturalidade		= antInjection($campo[31]);
    						$nomepai			= antInjection($campo[32]);
    						$nomemae		    = antInjection($campo[33]);
    						$conjnatural		= antInjection($campo[34]);
    						$conjrgemissor	    = antInjection($campo[35]);                            
                            
                            $d                  = explode("/", antInjection($campo[36]));                            
                            if (strlen($d[0])== "1") $d[0] = "0".$d[0];
                            if (strlen($d[1])== "1") $d[1] = "0".$d[1];
                            $conjrgdata         = $d[2]."-".$d[1]."-".$d[0];                            
    						
                            $d                  = explode("/", antInjection($campo[37]));                            
                            if (strlen($d[0])== "1") $d[0] = "0".$d[0];
                            if (strlen($d[1])== "1") $d[1] = "0".$d[1];
    						$nascimento 		= $d[2]."-".$d[1]."-".$d[0];
                            
                            $d                  = explode("/", antInjection($campo[38]));                            
                            if (strlen($d[0])== "1") $d[0] = "0".$d[0];
                            if (strlen($d[1])== "1") $d[1] = "0".$d[1];
    						$conjnascimento	    = $d[2]."-".$d[1]."-".$d[0];
                            
    						$conjapelido		= antInjection($campo[39]);
    						$conjnomepai		= antInjection($campo[40]);
    						$conjnomemae	    = antInjection($campo[41]);
    						$nomeproprietario   = antInjection($campo[42]);
    						$cpfproprietario	= antInjection($campo[43]);
    						$respfamilia		= antInjection($campo[44]);
    						$latitude			= antInjection($campo[45]);
    						$longitude			= antInjection($campo[46]);
    						$numportdef		    = antInjection(intval($campo[47]));
    						$categoria		    = antInjection($campo[48]);
                                                     
                            if (empty($rgconjuge)) $rgconjuge = " ";
                            if (empty($apelido)) $apelido = " ";
                            if (empty($rendagricola)) $rendagricola = " ";
                            if (empty($identidade)) $identidade = " ";
                            if (empty($eletricidade)) $eletricidade = " ";
                            if (empty($distancia)) $distancia = "0";
                            if (empty($rendagricola)) $rendagricola = "0";
                            if (empty($rendapecuaria)) $rendapecuaria = "0";
                            if (empty($area)) $area = "0";

    						$sql = "SELECT * FROM tb_cadastro WHERE CPF = '".trim($cpf)."'";                                                	
    						if($banco->existe($sql)){
    							$sqlC.= "UPDATE tb_cadastro SET IDMUNICIPIO = '".$idmunicipio."', NOME = '".$nome."', ENDERECO = '".$endereco."', ";
                                $sqlC.= "RG = '".$rg."', ";
                                if ($data != "--") $sqlC.= "DATA = '".$data."', ";
                                $sqlC.= " ESTADOCIVEL = '".$estadocivel."', CONJUGE = '".$conjuge."', CPFCONJUGE = '".$cpfconjuge."', RGCONJUGE = '".$rgconjuge."', RENDAGRICOLA = '".$rendagricola."', ";
                                $sqlC.= "INDIVIDUOS = '".$individuos."', GRAUINSTPROD = '".$grauinstprod."', GRAUINSTCONJ = '".$grauinstconj."', ";
                                $sqlC.= "TPPROPIEDADE = '".$tppropriedade."', AREA = '".$area."', APELIDO = '".$apelido."',  IDENTIDADE = '".$identidade."', ";
                                $sqlC.= "TITPOSSE = '".$titposse."', AGUA = '".$agua."', CAPTACAO = '".$captacao."', ESTRADA = '".$estrada."', ";
                                $sqlC.= "ELETRICIDADE = '".$eletricidade."', DISTANCIA = '".$distancia."', IDUSUARIO = '".$idusuario."', ";
                                $sqlC.= "PROPRIEDADE = '".$propriedade."', SEXO = '".$sexo."', RENDAPECUARIA = '".$rendapecuaria."', RGEMISSOR = '".$rgemissor."', ";
                                if ($rgdata != "--") $sqlC.= "RGDATA = '".$rgdata."', ";                                
                                $sqlC.= "NATURALIDADE = '".$naturalidade."', NOMEPAI = '".$nomepai."', NOMEMAE = '".$nomemae."', ";
                                $sqlC.= "CONJNATURAL = '".$conjnatural."', CONJRGEMISSOR = '".$conjrgemissor."', ";
                                if ($conjrgdata != "--") $sqlC.= "CONJRGDATA = '".$conjrgdata."', ";
                                if ($nascimento != "--") $sqlC.= "NASCIMENTO = '".$nascimento."', ";
                                if ($conjnascimento != "--") $sqlC.= "CONJNASCIMENTO = '".$conjnascimento."', "; 
                                
                                $sqlC.= "CONJAPELIDO = '".$conjapelido."', ";
                                $sqlC.= "CONJNOMEPAI = '".$conjnomepai."', CONJNOMEMAE = '".$conjnomemae."', NOMEPROPRIETARIO = '".$nomeproprietario."', ";
                                $sqlC.= "CPFPROPRIETARIO = '".$cpfproprietario."', RESPFAMILIA = '".$respfamilia."', LATITUDE = '".$latitude."', LONGITUDE = '".$longitude."', ";
                                $sqlC.= "NUMPORTDEF = '".$numportdef."', CATEGORIA = '".$categoria."' WHERE CPF = '".$cpf."'";
    						}else{
    						  
                                if ($data == "--") $data = "NULL"; else $data = "'".$data."'";
                                if ($rgdata == "--") $rgdata = "NULL"; else $rgdata = "'".$rgdata."'";
                                if ($conjrgdata == "--") $conjrgdata = "NULL"; else $conjrgdata = "'".$conjrgdata."'";
                                if ($nascimento == "--") $nascimento = "NULL"; else $nascimento = "'".$nascimento."'";
                                if ($conjnascimento == "--") $conjnascimento = "NULL"; else $conjnascimento = "'".$conjnascimento."'";

    							$sqlC.= "INSERT INTO tb_cadastro (IDMUNICIPIO, NOME, ENDERECO, CPF, RG, DATA, ESTADOCIVEL, CONJUGE, CPFCONJUGE, ";
                                $sqlC.= "RGCONJUGE, RENDAGRICOLA, INDIVIDUOS, GRAUINSTPROD, GRAUINSTCONJ, TPPROPIEDADE, AREA, APELIDO, IDENTIDADE, ";
                                $sqlC.= "TITPOSSE, AGUA, CAPTACAO, ESTRADA, ELETRICIDADE, DISTANCIA, IDUSUARIO, PROPRIEDADE, SEXO, RENDAPECUARIA, ";
                                $sqlC.= "RGEMISSOR, RGDATA, NATURALIDADE, NOMEPAI, NOMEMAE, CONJNATURAL, CONJRGEMISSOR, CONJRGDATA, NASCIMENTO, ";
                                $sqlC.= "CONJNASCIMENTO, CONJAPELIDO, CONJNOMEPAI, CONJNOMEMAE, NOMEPROPRIETARIO, CPFPROPRIETARIO, RESPFAMILIA, ";
                                $sqlC.= "LATITUDE, LONGITUDE, NUMPORTDEF, CATEGORIA) VALUES ('".$idmunicipio."', '".$nome."', '".$endereco."', ";
                                $sqlC.= "'".$cpf."', '".$rg."', ".$data.", '".$estadocivel."', '".$conjuge."', '".$cpfconjuge."', '".$rgconjuge."', ";
                                $sqlC.= "'".$rendagricola."', '".$individuos."', '".$grauinstprod."', '".$grauinstconj."', '".$tppropriedade."', '".$area."', ";
                                $sqlC.= "'".$apelido."', '".$identidade."', '".$titposse."', '".$agua."', '".$captacao."', '".$estrada."', '".$eletricidade."', ";
                                $sqlC.= "'".$distancia."', '".$idusuario."', '".$propriedade."', '".$sexo."', '".$rendapecuaria."', '".$rgemissor."', ".$rgdata.", ";
                                $sqlC.= "'".$naturalidade."', '".$nomepai."', '".$nomemae."', '".$conjnatural."', '".$conjrgemissor."', ".$conjrgdata.", ";
                                $sqlC.= "".$nascimento.", ".$conjnascimento.", '".$conjapelido."', '".$conjnomepai."', '".$conjnomemae."', ";
                                $sqlC.= "'".$nomeproprietario."', '".$cpfproprietario."', '".$respfamilia."', '".$latitude."', '".$longitude."', ";
                                $sqlC.= "'".$numportdef."', '".$categoria."')";
    						}
                            $qryC = $banco->execQRY($sqlC);
                            $s = "I";
                            if ($qryC) $s = "O";

                        }elseif ($valida[1] == "gru.txt"){

                            $cpf		 = antInjection($campo[0]);
                            
                            $data = explode("/", $campo[1]);
                            if (strlen($data[0]) == "1") $data[0] = "0".$data[0];
                            if (strlen($data[1]) == "1") $data[1] = "0".$data[1];
                            $data = $data[2]."-".$data[1]."-".$data[0];
                            
                            $usuario	 = antInjection(intval($campo[2]));
                            $projeto	 = antInjection(intval($campo[3]));
                            $atividade	 = antInjection(intval($campo[4]));
                            $indicador	 = antInjection(intval($campo[5]));
                            $unidade	 = antInjection(intval($campo[6]));
                            $quantidade  = str_replace(",", ".", removeStrings($campo[7], "."));
                            if (empty($quantidade)) $quantidade = "0";                            
                            $familias	 = antInjection($campo[8]);
                            $desc        = antInjection($campo[9]);                            
                            
                            $sqlM = "SELECT IDCADASTRO FROM tb_cadastro WHERE CPF = '".$cpf."'";
                            $rowM = $banco->listarArray($sqlM);

                            //pega municipio do usuario...                            
                            $sql8 = "SELECT MUN_IDMunicipio FROM tb_usuarios WHERE USU_IDUsuario = '".intval($usuario)."'";
                            $row8 = $banco->listarArray($sql8);                            
                            $idmunicipio = $row8[0]["MUN_IDMunicipio"];

                            //VERIFICA SE REGISTRO JÁ EXISTE...
                            $sqlV = "SELECT PLE_IDExecucao FROM tb_plano_execucao WHERE MUN_IDMunicipio = '".intval($idmun)."' ";
                            $sqlV.= "AND PLE_Ano = '".$idmunicipio."' AND PRJ_IDProjeto = '".intval($projeto)."' ";
                            $sqlV.= "AND ATV_IDAtividade = '".intval($atividade)."' AND PLE_Semana = '".intval(substr($arquivo, 6, 2))."' ";
                            $sqlV.= "AND USU_IDUsuario = '".intval($usuario)."' AND ORI_IDOrientacao = '".intval($indicador)."' ";
                            if ($rowM[0]["IDCADASTRO"] > 0) $sqlV.= " AND FAM_IDFamilia = '".$rowM[0]["IDCADASTRO"]."'";
                            $rowV = $banco->listarArray($sqlV);

                            if (count($rowV) == "0"){

                        		$data = explode("/", antInjection($campo[1]));
                                if (strlen($data[0]) == "1") $data[0] = "0".$data[0];
                                if (strlen($data[1]) == "1") $data[1] = "0".$data[1];
                                $data = $data[2]."-".$data[1]."-".$data[0];
                                
                                $sema = semana_do_ano($data[0], $data[1], $data[2]);
                                
                                $sqlC = "INSERT INTO tb_plano_execucao (PLE_Ano, PRJ_IDProjeto, ATV_IDAtividade, PLE_Semana, PLE_Qtd, PLE_Data,";
                                $sqlC.= "USU_IDUsuario, MUN_IDMunicipio, FAM_IDFamilia, PLE_Familias, ORI_IDOrientacao, PLE_Origem, PLE_Desc_Atv) ";
                                $sqlC.= "VALUES (".$idmunicipio.", '".$projeto."', '".$atividade."', '".$sema."', ";
                                $sqlC.= "'".$quantidade."', '".$data."', '".$usuario."', '".$idmunicipio."', '".$rowM[0]["IDCADASTRO"]."', ";
                                $sqlC.= "'".$familias."', '".$indicador."', '1', '".$desc."');";
                                $qryC = $banco->execQRY($sqlC);
                                if (empty($rowM[0]["IDCADASTRO"])){
                                    echo $sqlM.' === '.$sqlC.'<br><br>';    
                                }
                                $s = "I";
                                if ($qryC) $s = "O";                                
                            }

                        }elseif ($valida[1] == "rde.txt"){
                            
                            $cpf		= antInjection($campo[0]);

                            $data = explode("/", antInjection($campo[1]));
                            if (strlen($data[0]) == "1") $data[0] = "0".$data[0];
                            if (strlen($data[1]) == "1") $data[1] = "0".$data[1];
                            $data = $data[2]."-".$data[1]."-".$data[0];

                            $usuario	= antInjection(intval($campo[2]));
                            $projeto	= antInjection(intval($campo[3]));
                            $atividade	= antInjection(intval($campo[4]));
                            $indicador	= antInjection(intval($campo[5]));
                            $unidade	= antInjection(intval($campo[6]));
                            $quantidade = str_replace(",", ".", removeStrings(antInjection($campo[7]), "."));
                            $familias	= antInjection(intval($campo[8]));
                            
                            //pega municipio do usuario...                            
                            $sql8 = "SELECT MUN_IDMunicipio FROM tb_usuarios WHERE USU_IDUsuario = '".$usuario."'";
                            $row8 = $banco->listarArray($sql8);                            
                            $idmunicipio = $row8[0]["MUN_IDMunicipio"];

                            $sqlM = "SELECT IDCADASTRO FROM tb_cadastro WHERE CPF = '".$cpf."'";
                            $rowM = $banco->listarArray($sqlM);
                            
                            //VERIFICA SE REGISTRO JÁ EXISTE...
                            $sqlV = "SELECT PLE_IDExecucao FROM tb_plano_execucao WHERE MUN_IDMunicipio = '".$idmunicipio."' ";
                            $sqlV.= "AND PLE_Ano = '".$idmunicipio."' AND PRJ_IDProjeto = '".$projeto."' AND ATV_IDAtividade = '".$atividade."' ";
                            $sqlV.= "AND PLE_Semana = '".intval(substr($arquivo, 6, 2))."' AND USU_IDUsuario = '".$usuario."' ";
                            $sqlV.= "AND ORI_IDOrientacao = '".$indicador."' ";
                            if ($rowM[0]["IDCADASTRO"] > 0) $sqlV.= " AND FAM_IDFamilia = '".$rowM[0]["IDCADASTRO"]."'";
                            //exit($sqlV);                            
                            $rowV = $banco->listarArray($sqlV);

                            if (count($rowV) == "0"){
                                
                        		$data = explode("/", antInjection($campo[1]));
                                if (strlen($data[0]) == "1") $data[0] = "0".$data[0];
                                if (strlen($data[1]) == "1") $data[1] = "0".$data[1];
                                $data = $data[2]."-".$data[1]."-".$data[0];
                                
                                $sema = semana_do_ano($data[0], $data[1], $data[2]);
                                
                                $sqlC = "INSERT INTO tb_plano_execucao (PLE_Ano, PRJ_IDProjeto, ATV_IDAtividade, PLE_Semana, PLE_Qtd, PLE_Data,";
                                $sqlC.= "USU_IDUsuario, MUN_IDMunicipio, FAM_IDFamilia, PLE_Familias, ORI_IDOrientacao, PLE_Origem) ";
                                $sqlC.= "VALUES ('".substr($arquivo, 8, 4)."', '".$projeto."', '".$atividade."', '".$sema."', ";
                                $sqlC.= "'".$quantidade."', '".$data."', '".$usuario."', '".$idmunicipio."', '".$rowM[0]["IDCADASTRO"]."', ";
                                $sqlC.= "'".$familias."', '".$indicador."', '0');";
                                $qryC = $banco->execQRY($sqlC);
                                if (empty($rowM[0]["IDCADASTRO"])){
                                    echo $sqlM.' === '.$sqlC.'<br><br>';    
                                }
                                $s = "I";
                                if ($qryC) $s = "O";
                                
                            }
                            
                        }elseif ($valida[1] == "vei.txt"){

                            $sqlV = "SELECT IDVEICULO FROM tb_veiculos WHERE PLACA = '".antInjection($campo[0])."'";
                            $rowV = $banco->listarArray($sqlV);
                            
                            $idmunicipio = antInjection(intval($campo[8]));

                            if (count($rowV) == "0"){
                                
                                $sqlC = "INSERT INTO tb_veiculos (IDMUNICIPIO, PLACA, DESCRICAO, ANO, COMBUSTIVEL, KILOMETRAGEM, OBSERVACAO, STATUS, CARTAO, RESPONSAVEL) ";
                                $sqlC.= "VALUES ('".$idmunicipio."', '".antInjection($campo[0])."', '".antInjection($campo[1])."', '".antInjection($campo[2])."', '".trim($campo[3])."', ";
                                $sqlC.= "'".antInjection($campo[4])."', '".antInjection($campo[5])."', 'A', '".antInjection($campo[6])."', '".antInjection($campo[7])."')";
                                
                            }else{
                                
                                $sqlC = "UPDATE tb_veiculos SET IDMUNICIPIO = '".$idmunicipio."', DESCRICAO = '".antInjection($campo[1])."', ";
                                $sqlC.= "ANO = '".antInjection($campo[2])."', COMBUSTIVEL = '".antInjection($campo[3])."', KILOMETRAGEM = '".antInjection($campo[4])."', OBSERVACAO = '".antInjection($campo[5])."', ";
                                $sqlC.= "STATUS = 'A', CARTAO = '".antInjection($campo[6])."', RESPONSAVEL = '".antInjection($campo[7])."' WHERE PLACA = '".antInjection($campo[0])."'";
                            }

                            $qryC =  $banco->execQRY($sqlC);
                            $s = "I";
                            if ($qryC) $s = "O";

                        }elseif ($valida[1] == "vmv.txt"){
                            
                            $data = explode("/", antInjection($campo[2]));
                            if (strlen($data[0]) == "1") $data[0] = "0".$data[0];
                            if (strlen($data[1]) == "1") $data[1] = "0".$data[1];
                            $data = $data[2]."-".$data[1]."-".$data[0];
                            
                            $sqlV = "SELECT IDMUNICIPIO FROM tb_veiculos WHERE PLACA = '".antInjection($campo[1])."'";
                            $rowV = $banco->listarArray($sqlV);
                            $idmunicipio = $rowV[0]["IDMUNICIPIO"];
                            if (empty($idmunicipio)) $idmunicipio = "0";
                            
                            $sqlC = "INSERT INTO tb_veiculos_mov (PLACA, DATA, KILOMETROINI, KILOMETROFIN, COMBUSTIVEL_LT, COMBUSTIVEL_RS, SERVICOS, "; 
                            $sqlC.= "IDUSUARIO, STATUS, SERVICOS_DESC, ENVIAR, DTENVIO) ";
                            $sqlC.= "VALUES ('".antInjection($campo[1])."', '".antInjection($data)."', '".antInjection(intval($campo[3]))."', ";
                            $sqlC.= "'".antInjection(intval($campo[4]))."', '".antInjection(intval($campo[5]))."', '".antInjection(intval($campo[6]))."', '".antInjection(intval($campo[7]))."', ";
                            $sqlC.= "'".antInjection(intval($campo[8]))."', 'A', '".antInjection($campo[9])."', 'S', '".date("Y-m-d H:i:s")."')";
                            $qryC =  $banco->execQRY($sqlC);
                            $s = "I";
                            if ($qryC) $s = "O";

                        }elseif ($valida[1] == "ent.txt"){
                            
    						$idmunicipio    = antInjection(intval($campo[0]));
    						$razaoSocial	= antInjection($campo[1]);
    						$cnpj			= antInjection($campo[2]);
    						$endereco	    = antInjection($campo[3]);
    						$bairro		    = antInjection($campo[4]);
    						$uf				= antInjection($campo[5]);
    						$cep			= antInjection($campo[6]);
    						$telefone		= antInjection($campo[7]);
    						$responsavel	= antInjection($campo[8]);
    						$cpf			= antInjection($campo[9]);
                            $usuario        = antInjection(intval(substr($arquivo, 3, 3)));
                            
                            $data = explode("/", antInjection($campo[10]));
                            if (strlen($data[0]) == "1") $data[0] = "0".$data[0];
                            if (strlen($data[1]) == "1") $data[1] = "0".$data[1];
                            $data = $data[2]."-".$data[1]."-".$data[0];
                            
    						$paa			= antInjection($campo[11]);

                            $sqlV  = "SELECT ENT_IDEntidade FROM tb_entidade WHERE MUN_IDMunicipio = '".$idmunicipio."' AND ENT_Razao_Social = '".$razaoSocial."'";
                            $rowV = $banco->listarArray($sqlV);
                            if (count($rowV) == "0"){

                                $sqlC = "INSERT INTO tb_entidade (MUN_IDMunicipio, ENT_Razao_Social, ENT_Cnpj, ENT_Endereco, ENT_Bairro, ENT_UF, ";
                                $sqlC.= "ENT_Cep, ENT_Telefone, ENT_Responsavel, ENT_Cpf, ENT_Data, USU_IDUsuario, ENT_Paa, data_importacao) ";
                                $sqlC.= "VALUES (".$idmunicipio.", '".$razaoSocial."', '".$cnpj."', '".$endereco."', '".$bairro."', '".$uf."', ";
                                $sqlC.= "'".$cep."', '".$telefone."', '".$responsavel."', '".$cpf."', '".$data."', '".$usuario."', '".$paa."', NOW());";

                            }else{

                                $sqlC = "UPDATE tb_entidade SET ENT_Razao_Social = '".$razaoSocial."', ENT_Cnpj = '".$cnpj."', ENT_Endereco = '".$endereco."', ";
                                $sqlC.= "ENT_Bairro = '".$bairro."', ENT_UF = '".$uf."', ENT_Cep = '".$cep."', ENT_Telefone = '".$telefone."', ENT_Responsavel = '".$responsavel."', ";
                                $sqlC.= "ENT_Cpf = '".$cpf."', ENT_Data = '".$data."', USU_IDUsuario = '".$usuario."', ENT_Paa = '".$paa."', data_importacao = '".date("Y-m-d H:i:s")."' ";                                
                                $sqlC.= "WHERE ENT_IDEntidade = '".$rowV[0]["ENT_IDEntidade"]."'";

                            }
                            //exit($sqlC);
                            @$qryC = $banco->execQRY($sqlC);
                            $s = "I";
                            if ($qryC) $s = "O";

                        }
                        
                        if (!empty($s)){
                            
                            $sqlLOF = "INSERT INTO tb_log_files (USU_ID, LOF_DataHora, MUN_ID, LOF_Arquivo, LOF_Linha, LOF_Status) ";
                            $sqlLOF.= "VALUES ('".$_SESSION["sIDUSUARIO"]."', '".date("Y-m-d H:i:s")."', '".$idmunicipio."', '".$arquivo."', '".$l."', '".$s."')";
                            @$qryLOF = $banco->executarQuery($sqlLOF);
                        }
					}
                    $contArquivos++;
					//fecha o arquivo
					fclose($handle);
				}            
			}

            $ind = 0;
			foreach ($contents as $arquivo)	{
			  //deleta o arquivo do servidor...               
              if (!empty($arquivo)){
                 //   @rename($diretorio.$arquivo, $diretorio.'importados/'.$arquivo);
                    $ind++;
                }else{
                    $arqComErros++;                    
                }
			}
			$retorno = 1;
            
            //goto2("../lib/Fachada.php?acao=".base64_encode("MenuPrincipal")."&retorno=".$retorno."&tots=".$ind."&totn=".$arqComErros."&mod=".$_REQUEST["mod"]);
			
		break;
		
		case "filtrarSigater":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_sigater.php");
		break;    

		case "importarRDE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_importar_rde.php");
		break;

		case "importandoRDE":		
			$msg = "";
			$retorno = 0;	//	0 é ERRO no upload do arquivos; 1 é sucesso.
			
			// Pasta onde o arquivo vai ser salvo
			$_UP['pasta'] = '../tmp/';
			// Tamanho máximo do arquivo (em Bytes)
			$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
			// Array com as extensões permitidas
			//$_UP['extensao'] = array('txt', 'png', 'gif');
			$_UP['extensao'] = array('txt');
			
			// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
			$nome_final = $_FILES['arquivo']['name'];
			$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
			
			if($_FILES['arquivo']['error'] == 4){
				$msg = "Nenhum arquivo foi selecionado para importação.";
			}
			
			// Faz a verificação da extensão do arquivo
			else if (array_search($extensao, $_UP['extensao']) === false) {
				$msg = "Falha na importação. Por favor, envie um arquivo com a extensão .txt";
			}

			// Faz a verificação do tamanho do arquivo
			else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
				$msg = "Falha na importação. O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
			}
				 
			// Faz a verificação do nome do arquivo
			else if (((substr($nome_final, 12, 4) != "_rde") && (substr($nome_final, 12, 10) != "_rde_grupo"))
				|| substr($nome_final, 6, 2) > 52
				|| substr($nome_final, 8, 4) > date("Y")){					
					$msg = "Falha na importação. O nome do arquivo enviado não coincide com o nome de um arquivo gerado pelo RDE.";
			}else{
				// Depois verifica se é possível mover o arquivo para a pasta escolhida
				if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {

					$origem = (substr($nome_final, -8, 4) == "_rde") ? "I" : "C";
										
					//	abre o arquivo para serem feitos os inserts/updates no banco
					$handle = @fopen($_UP['pasta'] . $nome_final, "r");	
					if ($handle) {			
						$l = 0;
						
					    while (!feof($handle)) {		
					        $buffer = fgets($handle, 4096);	 					

					        //	elimina a 1ª e a última linha
					        if ((++$l != 1) && $buffer)	{
						        $campo = explode(';', $buffer);
	
						        $cpf		= trim($campo[0]);
						        $data		= ($campo[1]) ? inverterFormatoData(trim($campo[1]), '/', '-') : null;
						        $usuario	= trim($campo[2]);
						        $projeto	= trim($campo[3]);
						        $atividade	= trim($campo[4]);
						        $indicador	= trim($campo[5]);
						        $unidade	= trim($campo[6]);
						        $quantidade	= ($campo[7]) ? str_replace(',', '.', trim($campo[7])): null;
						        $familias	= trim($campo[8]);
/*
						        $sql = "INSERT INTO tb_plano_execucao
									(PLE_Ano, PRJ_IDProjeto, ATV_IDAtividade, 
									PLE_Semana, PLE_Qtd, PLE_Data, 
									USU_IDUsuario, MUN_IDMunicipio, FAM_IDFamilia,
									PLE_Familias, ORI_IDOrientacao, PLE_Origem)
									VALUES
									(".substr($nome_final, 8, 4).", ".$projeto.", ".$atividade.", 
									".substr($nome_final, 6, 2).", ".$quantidade.", '".$data."', 
									".$usuario.", ".substr($nome_final, 0, 3).", (SELECT IDCADASTRO FROM tb_cadastro WHERE CPF = '".$cpf."'), 
									".$familias.", ".$indicador.", '".$origem."');"; 
						        
*/						        
						        $sql = "INSERT INTO tb_plano_execucao
									(PLE_Ano, PRJ_IDProjeto, ATV_IDAtividade, 
									PLE_Semana, PLE_Qtd, PLE_Data, 
									USU_IDUsuario, MUN_IDMunicipio, FAM_IDFamilia,
									PLE_Familias, ORI_IDOrientacao, PLE_Origem)
									VALUES
									(".substr($nome_final, 8, 4).", ".$projeto.", ".$atividade.", 
									".substr($nome_final, 6, 2).", ".$quantidade.", '".$data."', 
									".$usuario.", (SELECT IDMUNICIPIO FROM tb_cadastro WHERE CPF = '".$cpf."'), (SELECT IDCADASTRO FROM tb_cadastro WHERE CPF = '".$cpf."'), 
									".$familias.", ".$indicador.", '".$origem."');";
                                    
						        try{
									$qry = $banco->executarQuery($sql);
								}catch (Exception $e)	{
									$msg = $e->getMessage();
									goto2("../lib/Fachada.php?acao=".base64_encode("importarRDE")."&retorno=".$retorno."&msg=".$msg."&mod=".$_POST["mod"]);
									die;
								}							
					        }else{
					        	continue;
					        }								
					    }

					    fclose($handle);
					    
				    	//	deleta o arquivo do servidor.
				    	unlink($_UP['pasta'] . $nome_final);
					}
				
			    	$msg = "Arquivo importado com sucesso.";
			    	$retorno = 1;
			    	
				} else {
					// Não foi possível fazer o upload.
					$msg =  "Não foi possível enviar o arquivo, tente novamente";
				}	
				
			}			
			goto2("../lib/Fachada.php?acao=".base64_encode("importarRDE")."&retorno=".$retorno."&msg=".$msg."&mod=".$_POST["mod"]);
						
		break;

		case "filtrarRDE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
            require_once("../lib/acao.php");
			require_once("../consultas/filtro_rde.php");
		break;

	      case "filtrarACE":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
            require_once("../lib/acao.php");
			require_once("../consultas/filtro_ace.php");
		break;
				
		case "importarEntidade":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			require_once("../consultas/filtro_importar_entidade.php");
		break;

		case "importandoEntidade":
			$msg = "";
			$retorno = 0;	//	0 é ERRO no upload do arquivos; 1 é sucesso.
			
			// Pasta onde o arquivo vai ser salvo
			$_UP['pasta'] = '../tmp/';
			// Tamanho máximo do arquivo (em Bytes)
			$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
			// Array com as extensões permitidas
			//$_UP['extensao'] = array('txt', 'png', 'gif');
			$_UP['extensao'] = array('txt');
			
			// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
			$nome_final = $_FILES['arquivo']['name'];
			$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
			
			if($_FILES['arquivo']['error'] == 4){
				$msg = "Nenhum arquivo foi selecionado para importação.";
			}
			
			// Faz a verificação da extensão do arquivo
			else if (array_search($extensao, $_UP['extensao']) === false) {
				$msg = "Falha na importação. Por favor, envie um arquivo com a extensão .txt";
			}

			// Faz a verificação do tamanho do arquivo
			else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
				$msg = "Falha na importação. O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
			}
				 
			// Faz a verificação do nome do arquivo
			else if ((substr($nome_final, 12, 4) != "_ent")
				|| substr($nome_final, 6, 2) > 52
				|| substr($nome_final, 8, 4) > date("Y")){					
					$msg = "Falha na importação. O nome do arquivo enviado não coincide com o nome de um arquivo de Entidades.";
			}else{
				// Depois verifica se é possível mover o arquivo para a pasta escolhida
				if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {

					//$origem = (substr($nome_final, -8, 4) == "_rde") ? "I" : "C";
										
					//	abre o arquivo para serem feitos os inserts/updates no banco
					$handle = @fopen($_UP['pasta'] . $nome_final, "r");	
					if ($handle) {			
						$l = 0;
						
					    while (!feof($handle)) {		
					        $buffer = fgets($handle, 4096);	 					

					        //	elimina a 1ª e a última linha
					        if ((++$l != 1) && $buffer)	{
						        $campo = explode(';', $buffer);
	
						        $municipio	= trim($campo[0]);
						        $razaoSocial= trim($campo[1]);
						        $cnpj		= trim($campo[2]);
						        $endereco	= trim($campo[3]);
						        $bairro		= trim($campo[4]);
						        $uf			= trim($campo[5]);
						        $cep		= trim($campo[6]);
						        $telefone	= trim($campo[7]);
								$responsavel= trim($campo[8]);
						        $cpf		= trim($campo[9]);
						        $data		= ($campo[10]) ? inverterFormatoData(trim($campo[10]), '/', '-') : null;
						        
						        $sql = "INSERT INTO tb_entidade
									(MUN_IDMunicipio, ENT_Razao_Social, ENT_Cnpj, 
									ENT_Endereco, ENT_Bairro, ENT_UF, 
									ENT_Cep, ENT_Telefone, ENT_Responsavel,
									ENT_Cpf, ENT_Data, USU_IDUsuario,
									data_importacao)
									VALUES
									(".$municipio.", '".$razaoSocial."', '".$cnpj."', 
									'".$endereco."', '".$bairro."', '".$uf."', 
									'".$cep."', '".$telefone."', '".$responsavel."', 
									'".$cpf."', '".$data."', ".substr($nome_final, 3, 3).",
									NOW());"; 
						        
								try{
									$qry = $banco->executarQuery($sql);
								}catch (Exception $e)	{
									$msg = $e->getMessage();
									goto2("../lib/Fachada.php?acao=".base64_encode("importarEntidade")."&retorno=".$retorno."&msg=".$msg."&mod=".$_POST["mod"]);
									die;
								}
															
					        }else{
					        	continue;
					        }								
					    }

					    fclose($handle);
					    
				    	//	deleta o arquivo do servidor.
				    	unlink($_UP['pasta'] . $nome_final);
					}
				
			    	$msg = "Arquivo importado com sucesso.";
			    	$retorno = 1;
			    	
				} else {
					// Não foi possível fazer o upload.
					$msg =  "Não foi possível enviar o arquivo, tente novamente";
				}	
				
			}			
			goto2("../lib/Fachada.php?acao=".base64_encode("importarEntidade")."&retorno=".$retorno."&msg=".$msg."&mod=".$_POST["mod"]);
						
		break;
		/**/
		
		/* BASES EMPRESAS */
		case "cadastrarBases":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_bases.php");
		break;
		
		case "filtrarBases":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_bases.php");
		break;
		
		case "incluirBases":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$paa     = antInjection($_POST["paa"]);
			$empresa = antInjection($_POST["nome"]);
			$host    = $_POST["host"];
			
			if ($paa) $paa = "S"; else $paa = "N";

			$sqlE   = "SELECT EMP_IDEmpresa FROM tb_bases_empresas WHERE EMP_IDEmpresa = ".$empresa;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe BASE DE EMPRESA cadastrado com esta UNIDADE.");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_bases_empresas (EMP_IDEmpresa, BAS_Host, BAS_PAA) VALUES (".$empresa.", '".$host."', '".$paa."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarBases")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirBases":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_bases_empresas WHERE EMP_IDEmpresa = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarBases")."&exc=".$totError."&mod=".$_GET["mod"]);
			
		break;
		/**/
		
		/* ENQUETES */
		case "cadastrarEnquetes":
			session_start();
			include_once("../cadastros/frm_enquetes.php");
		break;
		
		case "filtrarEnquetes":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_enquetes.php");
		break;
		
		case "incluirEnquetes":
			session_start();

			$nome   = antInjection($_POST["nome"]);
			$cpf    = antInjection($_POST["cpf"]);
			$fone   = antInjection($_POST["fone"]);
			$func   = antInjection($_POST["func"]);
			$cate   = antInjection($_POST["cate"]);
			$email  = antInjection($_POST["email"]);
			$status = antInjection($_POST["status"]);			
			$frase  = $_POST["frase"];
			
			//registrando na sessão...
			$_SESSION["ENQ_nome"]   = $nome;
			$_SESSION["ENQ_cpf"]    = $cpf;
			$_SESSION["ENQ_fone"]   = $fone;
			$_SESSION["ENQ_func"]   = $func;
			$_SESSION["ENQ_cate"]   = $cate;
			$_SESSION["ENQ_email"]  = $email;
			$_SESSION["ENQ_frase"]  = $frase;
			
			if (strlen($frase) > 150){
				alert(strtoupper($nome).", a frase precisa ter no máximo 150 caracteres.");
				anterior(-1);
			}
			
			//verifica se tem a palavra no ARRAY...
			$lista = array("meio ambiente", "saúde do trabalhador", "saude do trabalhador");
	
			$x = 0;	
			$q = 0;	
			for($i=0;$i<count($lista);$i++){
				$l = $lista[$i];
				if (!empty($l)){
					$x = str_ireplace($l, $l, $frase, $qtd);
					$q+= $qtd;
				}
			}
			
			if ($q>=2){

				$sql = "INSERT INTO tb_enquetes (ENQ_Nome, ENQ_CPF, ENQ_Fone, ENQ_Funcao, CAT_ID, ENQ_Email, ENQ_Frase, ENQ_Data, ENQ_Status) ";
				$sql.= "VALUES ('".$nome."', '".$cpf."', '".$fone."', '".$func."', ".$cate.", '".$email."', '".$frase."', '".date("Y-m-d H:i:s")."', '".$status."')";
				$qry = $banco->executarQuery($sql);
				
				unset($_SESSION["ENQ_nome"]);
				unset($_SESSION["ENQ_cpf"]);
				unset($_SESSION["ENQ_fone"]);
				unset($_SESSION["ENQ_func"]);
				unset($_SESSION["ENQ_cate"]);
				unset($_SESSION["ENQ_email"]);
				unset($_SESSION["ENQ_frase"]);
				
				if (!empty($_SESSION["sIDUSUARIO"])){
				
					alert(strtoupper($nome).", o IPA agracede a sua participação.");
					goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarEnquetes")."&cad=0&mod=".$_GET["mod"]);
					
				}else{
				
					echo "
					<script>
						alert('".strtoupper($nome).", o IPA agracede a sua participação."."');
						parent.location = 'http://www.ipa.br';
					</script>";
					
				}

			}else{
			
				alert(strtoupper($nome).", frase precisa tem os seguintes termos: Meio Ambiente e Saúde do Trabalhador.");
				anterior(-1);
				
			}
			exit;
			
		break;
		
		case "aprovarEnquetes":

			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				//excluindo...
				$sql = "UPDATE tb_enquetes SET ENQ_Status = 'A' WHERE ENQ_ID = ".base64_decode($_POST["cod"][$i]);
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarEnquetes")."&ap=0&mod=".$_GET["mod"]);

		break;
		
		case "reprovarEnquetes":

			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				//excluindo...
				$sql = "UPDATE tb_enquetes SET ENQ_Status = 'R' WHERE ENQ_ID = ".base64_decode($_POST["cod"][$i]);
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarEnquetes")."&rp=0&mod=".$_GET["mod"]);

		break;
		
		case "excluirEnquetes":

			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql = "DELETE FROM tb_enquetes WHERE ENQ_ID = ".$id;
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarEnquetes")."&exc=0&mod=".$_GET["mod"]);

		break;
		/**/
		
		/* STATUS */
		case "cadastrarStatus":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../cadastros/frm_status.php");
		break;
		
		case "filtrarStatus":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			include_once("../consultas/filtro_status.php");
		break;
		
		case "incluirStatus":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");

			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select RDE_Descricao from tb_regiaodesen where RDE_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Região de Desenvolvimento cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "insert into tb_regiaodesen (RDE_Descricao, RDE_Status) values ('".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarStatus")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirStatus":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);
				
				//excluindo...
				$sql1 = "SELECT RDE_ID FROM tb_municipios WHERE RDE_ID = ".$id;
				$row1 = $banco->listarArray($sql1);

				$tot  = 0;
				$tot  = count($row1);
				if ($tot == 0){
					$sql = "DELETE FROM tb_regiaodesen WHERE RDE_ID = ".$id;
					$qry = $banco->executarQuery($sql);
				}else{
					$totError++;
				}
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarStatus")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarStatus":
			require_once("../lib/verifica.php");
			require_once("../lib/log.php");		
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$status = antInjection($_POST["status"]);

			$sqlE   = "select RDE_Descricao from tb_regiaodesen where RDE_Descricao = '".$nome."' and RDE_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe Região de Desenvolvimento cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "update tb_regiaodesen set RDE_Descricao = '".$nome."', RDE_Status = '".$status."' where RDE_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarStatus")."&alt=0&mod=".$_GET["mod"]);
			}

		break;
		/**/
                // CADASTRO DE ATIVIDADES PESQUISA
		case "cadastrarAtividadesPesquisa":
                            
            require_once("../lib/verifica.php");
            require_once("../lib/log.php");
            require_once("../cadastros/frm_cad_atividades.php");
                                
        break;             
            
		case "consultarAtividadesPesquisa":
                            
            require_once("../lib/verifica.php");
            require_once("../lib/log.php");
            require_once("../consultas/filtro_atividades_pes.php");
                    
        break;
        
        case "incluirPlanoAnual":
            
            $idmuni   = antInjection($_GET["idmuni"]);
            $idproj   = antInjection($_GET["idproj"]);
            $idatv    = antInjection($_GET["idatv"]);
            $idusu    = antInjection($_GET["idusu"]);
            $anoplano = antInjection($_GET["anoplano"]);
            $qtd1     = antInjection($_GET["qtd1"]);
            $qtd2     = antInjection($_GET["qtd2"]);
            
            $sql = "INSERT INTO tb_planoanual (PLA_Ano, PRJ_IDProjeto, ATV_IDAtividade, ATV_Prevqtd, ATV_Prevfam, USU_IDUsuario, MUN_IDMunicipio, PLA_Data) ";
            $sql.= "VALUES ('".$anoplano."', '".$idproj."', '".$idatv."', '".$qtd1."', '".$qtd2."', '".$idusu."', '".$idmuni."', '".date("Y-m-d H:i:s")."')";
            $qry = $banco->executarQuery($sql);
            
		break;
        
        case "alterrPlanoAnual":

            $idplanoanual = antInjection(base64_decode($_GET["idplanoanual"]));
            $idmuni       = antInjection($_GET["idmuni"]);
            $idproj       = antInjection($_GET["idproj"]);
            $idatv        = antInjection($_GET["idatv"]);
            $idusu        = antInjection($_GET["idusu"]);
            $anoplano     = antInjection($_GET["anoplano"]);
            $qtd1         = antInjection($_GET["qtd1"]);
            $qtd2         = antInjection($_GET["qtd2"]);
            
            $sql = "UPDATE tb_planoanual SET PLA_Ano = '".$anoplano."', PRJ_IDProjeto = '".$idproj."', ATV_IDAtividade = '".$idatv."', ";
            $sql.= "ATV_Prevqtd = '".$qtd1."', ATV_Prevfam = '".$qtd2."', USU_IDUsuario = '".$idusu."', MUN_IDMunicipio = '".$idmuni."', ";
            $sql.= "PLA_Data = '".date("Y-m-d H:i:s")."' WHERE PLA_IDAnual = '".$idplanoanual."'";
            $qry = $banco->executarQuery($sql);
        
		break;
        
        case "filtrarACE":
			require_once("../lib/verifica.php");
		    require_once("../lib/acao.php");
			require_once("../consultas/filtro_ace.php");
		break;

        case "validaUsuario":
        
            if (!empty($_GET["id"])){
            
                require_once("../lib/verifica.php");
                
                $sql = "SELECT * FROM tb_usuarios WHERE USU_Login = '".$_GET["id"]."'";
                if (!empty($_GET["id2"])) $sql.= " AND USU_IDUsuario <> '".base64_decode($_GET["id2"])."'";	
                $qry = $banco->executarQuery($sql);
                $tot = $banco->totalLinhas($qry);
                
                if ($tot > 0){
                   echo "<span class='fontError'>Login j&aacute; existe.<img src='../img/bolaVerm.gif' border='0'></span>";	
                }else{
                    echo "<span><font color=\"#006600\">Login válido.</font><img src='../img/bolaVerde.gif' border='0'></span>";		
                }  
            }
        

		break;

        case "validaBTNUsuario":

            if (!empty($_GET["id"])){
            
                require_once("../lib/verifica.php");
                
                $sql = "select * from tb_usuarios where USU_Login = '".$_GET["id"]."' and USU_Status = 'A'";
                if (!empty($_GET["id2"])) $sql.= " AND USU_IDUsuario <> '".base64_decode($_GET["id2"])."'";
                $qry = $banco->executarQuery($sql);
                $tot = $banco->totalLinhas($qry);	
                if ($tot == 0){
                  echo "<input type='button' name='btn1' value='Salvar' onClick='valida_form();' class='button'>";
                }else{
                  echo "<input type='button' name='btn1' value='Salvar' class='button' style='border:1px solid #999;background-color: #ddd; color:#999999;'>";
                }
            }
            
		break;
        
        case "atualizaQTDExecucao":
        
            $r = explode("@", $_GET["lista"]);

            //VERIFICA SE EXISTE (INCLUSÃO / ATUALIZAÇÃO)...
            $sql = "SELECT PLE_IDExecucao FROM tb_plano_execucao WHERE PLE_Ano = '".$r[1]."' AND PRJ_IDProjeto = '".$r[0]."' ";
            $sql.= "AND ATV_IDAtividade = '".$r[2]."' AND PLE_Semana = '0' AND USU_IDUsuario = '0' AND MUN_IDMunicipio = '".$r[3]."' ";
            $sql.= "AND ORI_IDOrientacao = '0'";
            $row = $banco->listarArray($sql);
            if (count($row) == "0"){
                
                $sql2 = "INSERT INTO tb_plano_execucao (PLE_Ano, PRJ_IDProjeto, ATV_IDAtividade, PLE_Semana, PLE_Qtd, PLE_Data, USU_IDUsuario, ";
                $sql2.= "MUN_IDMunicipio, FAM_IDFamilia, PLE_Familias, ORI_IDOrientacao, PLE_Desc_Atv, PLE_Origem, PLE_Qtd2) ";
                $sql2.= "VALUES ('".$r[1]."', '".$r[0]."', '".$r[2]."', '0', '0', '2012-01-01 00:00:00', '0', '".$r[3]."', '0', '".$_GET["qtd"]."', ";
                $sql2.= "'0', '', '', '0')";
            }else{
                $sql2 = "UPDATE tb_plano_execucao SET PLE_Familias = '".$_GET["qtd"]."' WHERE PLE_IDExecucao = '".$row[0]["PLE_IDExecucao"]."'";                
            }
            //echo $sql2;
            $qry2 = $banco->executarQuery($sql2);
        
		break;
        
        case "atualizaQTDExecucao2":
        
            $r   = explode("@", $_GET["lista"]);
            $qtd = str_replace(",", ".", removeStrings($_GET["qtd"], "."));

            //VERIFICA SE EXISTE (INCLUSÃO / ATUALIZAÇÃO)...
            $sql = "SELECT PLE_IDExecucao FROM tb_plano_execucao WHERE PLE_Ano = '".$r[1]."' AND PRJ_IDProjeto = '".$r[0]."' ";
            $sql.= "AND ATV_IDAtividade = '".$r[2]."' AND PLE_Semana = '0' AND USU_IDUsuario = '0' AND MUN_IDMunicipio = '".$r[3]."' ";
            $sql.= "AND ORI_IDOrientacao = '0'";
            $row = $banco->listarArray($sql);
            if (count($row) == "0"){

                $sql2 = "INSERT INTO tb_plano_execucao (PLE_Ano, PRJ_IDProjeto, ATV_IDAtividade, PLE_Semana, PLE_Qtd, PLE_Data, USU_IDUsuario, ";
                $sql2.= "MUN_IDMunicipio, FAM_IDFamilia, PLE_Familias, ORI_IDOrientacao, PLE_Desc_Atv, PLE_Origem, PLE_Qtd2) ";
                $sql2.= "VALUES ('".$r[1]."', '".$r[0]."', '".$r[2]."', '0', '0', '2012-01-01 00:00:00', '0', '".$r[3]."', '0', '0', ";
                $sql2.= "'0', '', '', '".$qtd."')";

            }else{

                $sql2 = "UPDATE tb_plano_execucao SET PLE_Qtd2 = '".$qtd."', FAM_IDFamilia = '0' WHERE PLE_IDExecucao = '".$row[0]["PLE_IDExecucao"]."'";
                
            }
            //echo $sql2;
            $qry2 = $banco->executarQuery($sql2);
            //$concat = $l["PRJ_IDProjeto"]."@".$l["PLA_Ano"]."@".$l2["ATV_IDAtividade"]."@".$l["MUN_IDMunicipio"]."@".$rowEX[0]["FAM_IDFamilia"];
        
		break;
        
		case "mudaStatusUsuario":

			$sql = "SELECT USU_Status FROM tb_usuarios WHERE USU_IDUsuario = ".base64_decode($_GET["id"]);
			$row = $banco->listarArray($sql);
			if (count($row) > 0){
				
				if ($row[0]["USU_Status"] == "A") $row[0]["USU_Status"] = "I"; else $row[0]["USU_Status"] = "A";
				
				$sql2 = "UPDATE tb_usuarios SET USU_Status = '".$row[0]["USU_Status"]."' WHERE USU_IDUsuario = ".base64_decode($_GET["id"]);
				$qry2 = $banco->executarQuery($sql2);
			}
			
		  if ($row[0]["USU_Status"] == "A"){
			  
			  echo "
				  <a href='#' onClick=\"pega_valor2('".$_GET["id"]."', 'pega_muda_status', '".$_GET["parm"]."');\">
				  	<img src='../img/bola_verde.png' border='0' title='Usuario ATIVO, clique aqui para mudar o status.'>
				  </a>";
					
		  }else{
			  echo "
				  <a href='#' onClick=\"pega_valor2('".$_GET["id"]."', 'pega_muda_status', '".$_GET["parm"]."');\">
				  	<img src='../img/bola_vermelha.png' border='0' title='Usuario INATIVO, clique aqui para mudar o status.'>
				  </a>";

		  }

		break;
  
		default:
			//Erro de parâmetro invalido
			header("location: index.html");
		break;		
	}
?>