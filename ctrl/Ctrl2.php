<?php
	//importando...
	require_once("../lib/verifica.php");
	require_once("../class/Conexao.php");
	require_once("../class/Convertedata.php");
    require_once("../lib/log.php");
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

        /* FABRICANTES */
		case "cadastrarFabricantes":
            require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_fabricantes.php");
		break;
        
		case "incluirFabricantes":
			require_once("../class/ConexaoFirebird.php");
            
            $nome = antInjection(strtoupper($_POST["nome"]));

            $sql = "SELECT FABRICANTE FROM FABRICANTE WHERE FABRICANTE = '".$nome."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
            $qry = ibase_query($res, $sql);
            $row = ibase_fetch_object($qry);
            $id = $row->FABRICANTE;
            
            if (!empty($id)){
                
                alert(strtoupper($_SESSION["sLOGIN_USUARIO"]).", Já existe FABRICANTE cadastrado com o NOME ".$nome);
                anterior(-1);
                
            }else{
                
        	    $sql3 = "SELECT MAX(COD_FABRICANTE) + 1 AS COD_FABRICANTE FROM FABRICANTE";	  
        	    $qry3 = ibase_query($res, $sql3);
        	    $row3 = ibase_fetch_object($qry3);
                $id = $row3->COD_FABRICANTE;
                if (empty($id)) $id = 1;
                
                //INCLUINDO...
                $sql2 = "INSERT INTO FABRICANTE (EMPRESA, COD_FABRICANTE, FABRICANTE) VALUES ('".$_SESSION["sEMP_IDEmpresa"]."', '".$id."', '".$nome."')";                
                $qry2 = ibase_query($res, $sql2);
                if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
                
                goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarFabricantes")."&cad=0&mod=".$_GET["mod"]);
            }
            
		break;
        
		case "excluirFabricantes":

            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT IDPRODUTO FROM TB_PEDIDOS_ITENS WHERE IDPRODUTO = '".$id."'"; 
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);
                
                if (!empty($row2->IDPRODUTO)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM PRODUTO WHERE COD_INTERNO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                    $qry2 = ibase_query($res, $sql2);
                }
                
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProdutos")."&exc=".$totError."&mod=".$_GET["mod"]);
            
		break;
        /**/
	   
        /* PRODUTOS */
        case "alterarProdutos":
        
            $IDCONEXAONFE = antInjection(base64_decode($_POST["empresa"])); 
        
			require_once("../class/ConexaoFirebird.php");
            
            pa($_POST);
            
            $id          = antInjection(base64_decode($_POST["id"]));
            $grupo       = antInjection($_POST["grupo"]);
            $subgrupo    = antInjection($_POST["subgrupo"]);
            $linha       = antInjection($_POST["linha"]);
            $fabri       = antInjection($_POST["fabri"]);
            $codigo      = antInjection($_POST["codigo"]);
            $nome        = antInjection(strtoupper($_POST["nome"]));
            $descricao   = antInjection(strtoupper($_POST["descricao"]));
            $codncm      = antInjection($_POST["codncm"]);
            $fabricacao  = antInjection($_POST["fabricacao"]);
            $balanca     = antInjection($_POST["balanca"]);
            $und         = antInjection($_POST["und"]);
            $tipo        = antInjection($_POST["tipo"]);
            $piscofins   = antInjection($_POST["piscofins"]);
            $origem      = antInjection($_POST["origem"]);
            $icms        = antInjection($_POST["icms"]);
            $federal     = antInjection($_POST["federal"]);
            $mod1        = antInjection($_POST["mod1"]);
            $mod2        = antInjection($_POST["mod2"]);
            $mvain       = antInjection($_POST["mvain"]);            
            $mvaout      = antInjection($_POST["mvaout"]);
            $recbc       = antInjection($_POST["recbc"]);
            $pauta       = antInjection($_POST["pauta"]);
            $icmsin      = antInjection($_POST["icmsin"]);
            $icmsout     = antInjection($_POST["icmsout"]);
            $markup      = antInjection($_POST["markup"]);
            $compra      = antInjection($_POST["compra"]);
            $markupvenda = antInjection($_POST["markupvenda"]);
            $venda       = antInjection($_POST["venda"]);
            $defla1      = antInjection($_POST["defla1"]);
            $defla2      = antInjection($_POST["defla2"]);
            $defla3      = antInjection($_POST["defla3"]);
            $preco1      = antInjection($_POST["preco1"]);
            $preco2      = antInjection($_POST["preco2"]);
            $preco3      = antInjection($_POST["preco3"]);
            $undemb      = antInjection($_POST["undemb"]);
            $baremb      = antInjection($_POST["baremb"]);
            $qtdemb      = antInjection($_POST["qtdemb"]);
            $precoemb    = antInjection($_POST["precoemb"]);
            $custo       = antInjection($_POST["custo"]);
            $ativo       = antInjection($_POST["ativo"]);
            $controla    = antInjection($_POST["controla"]);
            $qtdmax      = antInjection($_POST["qtdmax"]);
            $minimo      = antInjection($_POST["minimo"]);
            $mulcomissao = antInjection($_POST["mulcomissao"]);
            $estoque     = antInjection($_POST["estoque"]);
            $gondola     = antInjection($_POST["gondola"]);
            $reservado   = antInjection($_POST["reservado"]);
            $promo       = antInjection($_POST["promo"]);
            $descpro     = antInjection(strtoupper($_POST["descpro"]));
            $dtini       = antInjection($_POST["dtini"]);
            $dtfim       = antInjection($_POST["dtfim"]);
            $precopro    = antInjection($_POST["precopro"]);
            $qtdata      = antInjection($_POST["qtdata"]);
            $precoata    = antInjection($_POST["precoata"]);
            $bruto       = antInjection($_POST["bruto"]);
            $liquido     = antInjection($_POST["liquido"]);
            $ipi         = antInjection($_POST["ipi"]);
            $aliquota    = antInjection($_POST["aliquota"]);
            $comissao    = antInjection($_POST["comissao"]);
            $maximo      = antInjection($_POST["maximo"]);
            
            if ($fabricacao == "on") $fabricacao = "P"; else $fabricacao = "T";
            if ($balanca == "on") $balanca = "S"; else $balanca = "N";
            if (empty($mvain)) $mvain = 0;
            if (empty($mvaout)) $mvaout = 0;
            if (empty($recbc)) $recbc = 0;
            if (empty($pauta)) $pauta = 0;
            if (empty($icms)) $icms = 0;
            if (empty($origem)) $origem = 0;
            if (empty($icmsin)) $icmsin = 0;
            if (empty($federal)) $federal = 0;
            if (empty($icmsout)) $icmsout = 0;
            if (empty($markup)) $markup = 0;
            if (empty($compra)) $compra = 0;
            if (empty($markupvenda)) $markupvenda = 0;
            if (empty($venda)) $venda = 0;
            if (empty($defla1)) $defla1 = 0;
            if (empty($defla2)) $defla2 = 0;
            if (empty($defla3)) $defla3 = 0;
            if (empty($preco1)) $preco1 = 0;
            if (empty($preco2)) $preco2 = 0;
            if (empty($preco3)) $preco3 = 0;
            if (empty($qtdemb)) $qtdemb = 0;
            if (empty($precoemb)) $precoemb = 0;
            if (empty($custo)) $custo = 0;
            if (empty($maximo)) $maximo = 0;
            if (empty($minimo)) $minimo = 0;
            if (empty($mulcomissao)) $mulcomissao = 0;
            if (empty($estoque)) $estoque = 0;
            if (empty($gondola)) $gondola = 0;
            if (empty($gondola)) $gondola = 0;
            if (!empty($dtini)) $dtini = "'".$conv->conData($dtini)."'"; else $dtini = "NULL";
            if (!empty($dtfim)) $dtfim = "'".$conv->conData($dtfim)."'"; else $dtfim = "NULL";
            if (empty($precopro)) $precopro = 0;
            if (empty($qtdata)) $qtdata = 0;
            if (empty($precoata)) $precoata = 0;
            if (empty($bruto)) $bruto = 0;
            if (empty($liquido)) $liquido = 0;
            if (empty($comissao)) $comissao = 0;
            if (empty($reservado)) $reservado = 0;

            $sql = "SELECT COD_PRODUTO FROM PRODUTO WHERE COD_PRODUTO = '".$codigo."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."' ";
            $sql.= "AND COD_INTERNO <> '".$id."'";
            $qry = ibase_query($res, $sql);
            $row = ibase_fetch_object($qry);
            $idp = $row->COD_PRODUTO;
            if (!empty($idp)){

                alert(strtoupper($_SESSION["sLOGIN_USUARIO"]).", Já existe PRODUTO cadastrado com o CÓDIGO ".$codigo);
                anterior(-1);

            }else{

                $sql4 = "SELECT VALOR FROM ALIQUOTA WHERE COD_ALIQUOTA = '".$aliquota."'";
                $qry4 = ibase_query($res, $sql4);
        	    $row4 = ibase_fetch_object($qry4);
                if (empty($row4->VALOR)) $row4->VALOR = 0;
                
                //INCLUINDO...
                $sql2 = "UPDATE PRODUTO SET COD_PRODUTO = '".$codigo."', DESCRICAO = '".$nome."', COD_GRUPO = '".$grupo."', ";
                $sql2.= "COD_SUBGRUPO = '".$subgrupo."', COD_LINHA = '".$linha."', BRUTO = '".$bruto."', LIQUIDO = '".$liquido."', ";
                $sql2.= "ESTOQUE = '".$estoque."', CUSTO = '".$custo."', ICMS_IN = '".$icmsin."', ST = '".$origem.$icms."', UNIDADE = '".$und."', ";
                $sql2.= "COMPRA = '".$compra."', MINIMO = '".$minimo."', ALIQUOTA = '".$aliquota."', TIPO = '".$tipo."', ICMS_OUT = '".$icmsout."', ";
                $sql2.= "COFINS = '".$cofins."', FEDERAL = '".$federal."', MARKUP = '".$markup."', VENDA_MARKUP = '".$markupvenda."', ";
                $sql2.= "COMISSAO = '".$comissao."', GONDOLA = '".$gondola."', RESERVADO = '".$reservado."', ";
                $sql2.= "TIPO_VENDA = '".$tipo."', MAXIMO = '".$maximo."', COD_FABRICANTE = '".$fabri."', MULCOMISSAO = '".$mulcomissao."', ";
                $sql2.= "ATACADO = '".$defla1."', ATACADO1 = '".$defla2."', ATACADO2 = '".$defla3."', VENDA = '".$venda."', ";
                $sql2.= "DATA_ALTERACAO = '".date("Y-m-d")."', COD_COLABORADORALTERACAO = '".$_SESSION["sIDUSUARIO"]."', QTD_EMBALAGEM = '".$qtdemb."', ";
                $sql2.= "PRECO_EMBALAGEM = '".$precoemb."', PAUTA = '".$pauta."', DESC_ATACADO = '".$descricao."', QTD_ULT_COMPRA = '0', ";
                $sql2.= "PRECO_ULT_COMPRA = '0', UND_EMBALAGEM = '".$undemb."', QTD_ANTES_ULT_COMPRA = '0', ST_PROMOCAO = '".$promo."', ";
                $sql2.= "DATA_INICIO_PROM = ".$dtini.", DATA_FINAL_PROM = ".$dtfim.", PRECO_PROM = '".$precopro."', COD_BARRA_EMBALAGEM = '".$baremb."', ";
                $sql2.= "BALANCA = '".$balanca."', COD_NCM = '".$codncm."', CSTPC = '". $piscofins."', MOD_BCICMS = '".$mod1."', ";
                $sql2.= "MOD_BCICMSST = '".$mod2."', FABRICACAO = '".$fabricacao."', QTD_VOL = '".$qtdata ."', ";
                $sql2.= "PRECO_VOL = '".$precoata."', INATIVO = '".$ativo."', CONTROLA_ESTOQUE = '".$controla."', DESCRICAO_PROMOCAO = '".$descpro."', ";
                $sql2.= "MVA_IN = '".$mvain."', MVA_OUT = '".$mvaout."', ALI_IPI = '".$row4->VALOR."', CSTIPI = '".$ipi."' ";
                $sql2.= "WHERE COD_INTERNO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                //exit($sql2);
                $qry2 = ibase_query($res, $sql2);
                pa($rowBASE); exit($sql2);
                
                if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
                
                goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProdutos")."&cad=0&mod=".$_GET["mod"]."&empresa=".$_POST["empresa"]);
                
            }
            
		break;
        
		case "filtrarProdutos":
			require_once("../class/ConexaoFirebird.php");
            include_once("../consultas/filtro_produtos.php");
		break;
        
		case "cadastrarProdutos":

            $IDCONEXAONFE = $_SESSION["sEMP_IDEmpresa"]; //antInjection(base64_decode($_REQUEST["empresa"]));

            require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_produtos.php");

		break;
        
		case "incluirProdutos":
			require_once("../class/ConexaoFirebird.php");
            
            $IDCONEXAONFE = antInjection(base64_decode($_POST["empresa"]));
            $grupo        = antInjection($_POST["grupo"]);
            $subgrupo     = antInjection($_POST["subgrupo"]);
            $linha        = antInjection($_POST["linha"]);
            $fabri        = antInjection($_POST["fabri"]);
            $codigo       = antInjection($_POST["codigo"]);
            $nome         = antInjection(strtoupper($_POST["nome"]));
            $descricao    = antInjection(strtoupper($_POST["descricao"]));
            $codncm       = antInjection($_POST["codncm"]);
            $fabricacao   = antInjection($_POST["fabricacao"]);
            $balanca      = antInjection($_POST["balanca"]);
            $und          = antInjection($_POST["und"]);
            $tipo         = antInjection($_POST["tipo"]);
            $piscofins    = antInjection($_POST["piscofins"]);
            $origem       = antInjection($_POST["origem"]);
            $icms         = antInjection($_POST["icms"]);
            $federal      = antInjection($_POST["federal"]);
            $mod1         = antInjection($_POST["mod1"]);
            $mod2         = antInjection($_POST["mod2"]);
            $mvain        = antInjection($_POST["mvain"]);            
            $mvaout       = antInjection($_POST["mvaout"]);
            $recbc        = antInjection($_POST["recbc"]);
            $pauta        = antInjection($_POST["pauta"]);
            $icmsin       = antInjection($_POST["icmsin"]);
            $icmsout      = antInjection($_POST["icmsout"]);
            $markup       = antInjection($_POST["markup"]);
            $compra       = antInjection($_POST["compra"]);
            $markupvenda  = antInjection($_POST["markupvenda"]);
            $venda        = antInjection($_POST["venda"]);
            $defla1       = antInjection($_POST["defla1"]);
            $defla2       = antInjection($_POST["defla2"]);
            $defla3       = antInjection($_POST["defla3"]);
            $preco1       = antInjection($_POST["preco1"]);
            $preco2       = antInjection($_POST["preco2"]);
            $preco3       = antInjection($_POST["preco3"]);
            $undemb       = antInjection($_POST["undemb"]);
            $baremb       = antInjection($_POST["baremb"]);
            $qtdemb       = antInjection($_POST["qtdemb"]);
            $precoemb     = antInjection($_POST["precoemb"]);
            $custo        = antInjection($_POST["custo"]);
            $ativo        = antInjection($_POST["ativo"]);
            $controla     = antInjection($_POST["controla"]);
            $qtdmax       = antInjection($_POST["qtdmax"]);
            $minimo       = antInjection($_POST["minimo"]);
            $mulcomissao  = antInjection($_POST["mulcomissao"]);
            $estoque      = antInjection($_POST["estoque"]);
            $gondola      = antInjection($_POST["gondola"]);
            $reservado    = antInjection($_POST["reservado"]);
            $promo        = antInjection($_POST["promo"]);
            $descpro      = antInjection(strtoupper($_POST["descpro"]));
            $dtini        = antInjection($_POST["dtini"]);
            $dtfim        = antInjection($_POST["dtfim"]);
            $precopro     = antInjection($_POST["precopro"]);
            $qtdata       = antInjection($_POST["qtdata"]);
            $precoata     = antInjection($_POST["precoata"]);
            $bruto        = antInjection($_POST["bruto"]);
            $liquido      = antInjection($_POST["liquido"]);
            $ipi          =  antInjection($_POST["ipi"]);
            $aliquota     =  antInjection($_POST["aliquota"]);
            $comissao     = antInjection($_POST["comissao"]);
            $maximo       = antInjection($_POST["maximo"]);
            
            if ($fabricacao == "on") $fabricacao = "P"; else $fabricacao = "T";
            if ($balanca == "on") $balanca = "S"; else $balanca = "N";
            if (empty($mvain)) $mvain = 0;
            if (empty($mvaout)) $mvaout = 0;
            if (empty($recbc)) $recbc = 0;
            if (empty($pauta)) $pauta = 0;
            if (empty($icms)) $icms = 0;
            if (empty($origem)) $origem = 0;
            if (empty($icmsin)) $icmsin = 0;
            if (empty($federal)) $federal = 0;
            if (empty($icmsout)) $icmsout = 0;
            if (empty($markup)) $markup = 0;
            if (empty($compra)) $compra = 0;
            if (empty($markupvenda)) $markupvenda = 0;
            if (empty($venda)) $venda = 0;
            if (empty($defla1)) $defla1 = 0;
            if (empty($defla2)) $defla2 = 0;
            if (empty($defla3)) $defla3 = 0;
            if (empty($preco1)) $preco1 = 0;
            if (empty($preco2)) $preco2 = 0;
            if (empty($preco3)) $preco3 = 0;
            if (empty($qtdemb)) $qtdemb = 0;
            if (empty($precoemb)) $precoemb = 0;
            if (empty($custo)) $custo = 0;
            if (empty($maximo)) $maximo = 0;
            if (empty($minimo)) $minimo = 0;
            if (empty($mulcomissao)) $mulcomissao = 0;
            if (empty($estoque)) $estoque = 0;
            if (empty($gondola)) $gondola = 0;
            if (empty($gondola)) $gondola = 0;
            if (!empty($dtini)) $dtini = "'".$conv->conData($dtini)."'"; else $dtini = "NULL";
            if (!empty($dtfim)) $dtfim = "'".$conv->conData($dtfim)."'"; else $dtfim = "NULL";
            if (empty($precopro)) $precopro = 0;
            if (empty($qtdata)) $qtdata = 0;
            if (empty($precoata)) $precoata = 0;
            if (empty($bruto)) $bruto = 0;
            if (empty($liquido)) $liquido = 0;
            if (empty($comissao)) $comissao = 0;
            if (empty($reservado)) $reservado = 0;

            $sql = "SELECT COD_PRODUTO FROM PRODUTO WHERE COD_PRODUTO = '".$codigo."' AND EMPRESA = '".$IDCONEXAONFE."'";
            $qry = ibase_query($res, $sql);
            $row = ibase_fetch_object($qry);
            $id = $row->COD_PRODUTO;
            
            if (!empty($id)){
                
                alert(strtoupper($_SESSION["sLOGIN_USUARIO"]).", Já existe PRODUTO cadastrado com o CÓDIGO ".$codigo);
                anterior(-1);
                
            }else{
                
        	    $sql3 = "SELECT MAX(COD_INTERNO) + 1 AS COD_INTERNO FROM PRODUTO";	  
        	    $qry3 = ibase_query($res, $sql3);
        	    $row3 = ibase_fetch_object($qry3);
                $id = $row3->COD_INTERNO;
                if (empty($id)) $id = 1;

                $sql4 = "SELECT VALOR FROM ALIQUOTA WHERE COD_ALIQUOTA = '".$aliquota."'";
                $qry4 = ibase_query($res, $sql4);
        	    $row4 = ibase_fetch_object($qry4);
                if (empty($row4->VALOR)) $row4->VALOR = 0;
                
                //INCLUINDO...
                $sql2 = "INSERT INTO PRODUTO (EMPRESA, COD_INTERNO, COD_PRODUTO, DESCRICAO, COD_GRUPO, COD_SUBGRUPO, ";
                $sql2.= "COD_LINHA, BRUTO, LIQUIDO, ESTOQUE, CUSTO, ICMS_IN, ST, UNIDADE, COMPRA, MINIMO, ";
                $sql2.= "ALIQUOTA, TIPO, ICMS_OUT, COFINS, FEDERAL, MARKUP, VENDA_MARKUP, COMISSAO, ";
                $sql2.= "GONDOLA, RESERVADO, ULT_COMPRA, TIPO_VENDA, MAXIMO, COD_FABRICANTE, MULCOMISSAO, ";
                $sql2.= "ATACADO, ATACADO1, ATACADO2, VENDA, DATA_ULTCOMPRA, DATA_CADASTRO, COD_COLABORADORCADASTRO, ";
                $sql2.= "DATA_ALTERACAO, COD_COLABORADORALTERACAO, QTD_EMBALAGEM, PRECO_EMBALAGEM, ";
                $sql2.= "PAUTA, DESC_ATACADO, QTD_ULT_COMPRA, PRECO_ULT_COMPRA, UND_EMBALAGEM, QTD_ANTES_ULT_COMPRA, ST_PROMOCAO, ";
                $sql2.= "DATA_INICIO_PROM, DATA_FINAL_PROM, PRECO_PROM, DATA_ULT_COMPRA, COD_BARRA_EMBALAGEM, BALANCA, COD_NCM, ";
                $sql2.= "EX_IPI, COD_GEN, COD_LST, INDARRTRUN, CSTPC, MOD_BCICMS, MOD_BCICMSST, ALI_ICMSST, FABRICACAO, QTD_VOL, ";
                $sql2.= "PRECO_VOL, INATIVO, CONTROLA_ESTOQUE, DESCRICAO_PROMOCAO, PRED_BCST, CREDITO_IN, CREDITO_OUT, MVA_IN, ";
                $sql2.= "MVA_OUT, ALI_IPI, CSTIPI) VALUES ('".$IDCONEXAONFE."', '".$id."', '".$codigo."', ";
                $sql2.= "'".$nome."', '".$grupo."', '".$subgrupo."', '".$linha."', '".$bruto."', '".$liquido."', '".$estoque."', ";
                $sql2.= "'".$custo."', '".$icmsin."', '".$origem.$icms."', '".$und."', '".$compra."', '".$minimo."', ";
                $sql2.= "'".$aliquota."', '".$tipo."', '".$icmsout."', '".$cofins."', '".$federal."', '".$markup."', '".$markupvenda."', ";
                $sql2.= "'".$comissao."', '".$gondola."', '".$reservado."', NULL, '".$tipo."', '".$maximo."', '".$fabri."', ";
                $sql2.= "'".$mulcomissao."', '".$defla1."', '".$defla2."', '".$defla3."', '".$venda."', NULL, '".date("Y-m-d")."',  ";
                $sql2.= "'".$_SESSION["sIDUSUARIO"]."', NULL, '0', '".$qtdemb."', '".$precoemb."', '".$pauta."', '".$descricao."', ";
                $sql2.= "'0', '0', '".$undemb."', '0', '0', ".$dtini.", ".$dtfim.", '".$precopro."', NULL, '".$baremb."', '".$balanca."',  ";
                $sql2.= "'".$codncm."', NULL, NULL, NULL, 'A', '". $piscofins."', '".$mod1."', '".$mod2."', '0', '".$fabricacao."', '".$qtdata ."', '".$precoata."', ";
                $sql2.= "'".$ativo."', '".$controla."', '".$descpro."', '7', '0', '0', '".$mvain."', '".$mvaout."', ";
                $sql2.= "'".$row4->VALOR."', '".$ipi."')";
                exit($sql2);
                $qry2 = ibase_query($res, $sql2);
                //exit($sql2);
                if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
                
                goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProdutos")."&cad=0&mod=".$_GET["mod"]."&empresa=".$_POST["empresa"]);

            }
            
		break;
        
		case "excluirProdutos":

            $IDCONEXAONFE = antInjection(base64_decode($_POST["empresa"]));
            
            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT IDPRODUTO FROM TB_PEDIDOS_ITENS WHERE IDPRODUTO = '".$id."'"; 
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);
                
                if (!empty($row2->IDPRODUTO)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM PRODUTO WHERE COD_INTERNO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                    $qry2 = ibase_query($res, $sql2);
                }
                
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProdutos")."&exc=".$totError."&mod=".$_GET["mod"]."&empresa=".$_POST["empresa"]);
            
		break;
        
		case "alterarProdutos":
			require_once("../class/ConexaoFirebird.php");
            
            $id          = antInjection(base64_decode($_POST["id"]));
            $grupo       = antInjection($_POST["grupo"]);
            $subgrupo    = antInjection($_POST["subgrupo"]);
            $linha       = antInjection($_POST["linha"]);
            $fabri       = antInjection($_POST["fabri"]);
            $codigo      = antInjection($_POST["codigo"]);
            $nome        = antInjection(strtoupper($_POST["nome"]));
            $descricao   = antInjection(strtoupper($_POST["descricao"]));
            $codncm      = antInjection($_POST["codncm"]);
            $fabricacao  = antInjection($_POST["fabricacao"]);
            $balanca     = antInjection($_POST["balanca"]);
            $und         = antInjection($_POST["und"]);
            $tipo        = antInjection($_POST["tipo"]);
            $piscofins   = antInjection($_POST["piscofins"]);
            $origem      = antInjection($_POST["origem"]);
            $icms        = antInjection($_POST["icms"]);
            $federal     = antInjection($_POST["federal"]);
            $mod1        = antInjection($_POST["mod1"]);
            $mod2        = antInjection($_POST["mod2"]);
            $mvain       = antInjection($_POST["mvain"]);            
            $mvaout      = antInjection($_POST["mvaout"]);
            $recbc       = antInjection($_POST["recbc"]);
            $pauta       = antInjection($_POST["pauta"]);
            $icmsin      = antInjection($_POST["icmsin"]);
            $icmsout     = antInjection($_POST["icmsout"]);
            $markup      = antInjection($_POST["markup"]);
            $compra      = antInjection($_POST["compra"]);
            $markupvenda = antInjection($_POST["markupvenda"]);
            $venda       = antInjection($_POST["venda"]);
            $defla1      = antInjection($_POST["defla1"]);
            $defla2      = antInjection($_POST["defla2"]);
            $defla3      = antInjection($_POST["defla3"]);
            $preco1      = antInjection($_POST["preco1"]);
            $preco2      = antInjection($_POST["preco2"]);
            $preco3      = antInjection($_POST["preco3"]);
            $undemb      = antInjection($_POST["undemb"]);
            $baremb      = antInjection($_POST["baremb"]);
            $qtdemb      = antInjection($_POST["qtdemb"]);
            $precoemb    = antInjection($_POST["precoemb"]);
            $custo       = antInjection($_POST["custo"]);
            $ativo       = antInjection($_POST["ativo"]);
            $controla    = antInjection($_POST["controla"]);
            $qtdmax      = antInjection($_POST["qtdmax"]);
            $minimo      = antInjection($_POST["minimo"]);
            $mulcomissao = antInjection($_POST["mulcomissao"]);
            $estoque     = antInjection($_POST["estoque"]);
            $gondola     = antInjection($_POST["gondola"]);
            $reservado   = antInjection($_POST["reservado"]);
            $promo       = antInjection($_POST["promo"]);
            $descpro     = antInjection(strtoupper($_POST["descpro"]));
            $dtini       = antInjection($_POST["dtini"]);
            $dtfim       = antInjection($_POST["dtfim"]);
            $precopro    = antInjection($_POST["precopro"]);
            $qtdata      = antInjection($_POST["qtdata"]);
            $precoata    = antInjection($_POST["precoata"]);
            $bruto       = antInjection($_POST["bruto"]);
            $liquido     = antInjection($_POST["liquido"]);
            $ipi         = antInjection($_POST["ipi"]);
            $aliquota    = antInjection($_POST["aliquota"]);
            $comissao    = antInjection($_POST["comissao"]);
            $maximo      = antInjection($_POST["maximo"]);
            
            if ($fabricacao == "on") $fabricacao = "P"; else $fabricacao = "T";
            if ($balanca == "on") $balanca = "S"; else $balanca = "N";
            if (empty($mvain)) $mvain = 0;
            if (empty($mvaout)) $mvaout = 0;
            if (empty($recbc)) $recbc = 0;
            if (empty($pauta)) $pauta = 0;
            if (empty($icms)) $icms = 0;
            if (empty($origem)) $origem = 0;
            if (empty($icmsin)) $icmsin = 0;
            if (empty($federal)) $federal = 0;
            if (empty($icmsout)) $icmsout = 0;
            if (empty($markup)) $markup = 0;
            if (empty($compra)) $compra = 0;
            if (empty($markupvenda)) $markupvenda = 0;
            if (empty($venda)) $venda = 0;
            if (empty($defla1)) $defla1 = 0;
            if (empty($defla2)) $defla2 = 0;
            if (empty($defla3)) $defla3 = 0;
            if (empty($preco1)) $preco1 = 0;
            if (empty($preco2)) $preco2 = 0;
            if (empty($preco3)) $preco3 = 0;
            if (empty($qtdemb)) $qtdemb = 0;
            if (empty($precoemb)) $precoemb = 0;
            if (empty($custo)) $custo = 0;
            if (empty($maximo)) $maximo = 0;
            if (empty($minimo)) $minimo = 0;
            if (empty($mulcomissao)) $mulcomissao = 0;
            if (empty($estoque)) $estoque = 0;
            if (empty($gondola)) $gondola = 0;
            if (empty($gondola)) $gondola = 0;
            if (!empty($dtini)) $dtini = "'".$conv->conData($dtini)."'"; else $dtini = "NULL";
            if (!empty($dtfim)) $dtfim = "'".$conv->conData($dtfim)."'"; else $dtfim = "NULL";
            if (empty($precopro)) $precopro = 0;
            if (empty($qtdata)) $qtdata = 0;
            if (empty($precoata)) $precoata = 0;
            if (empty($bruto)) $bruto = 0;
            if (empty($liquido)) $liquido = 0;
            if (empty($comissao)) $comissao = 0;
            if (empty($reservado)) $reservado = 0;

            $sql = "SELECT COD_PRODUTO FROM PRODUTO WHERE COD_PRODUTO = '".$codigo."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."' ";
            $sql.= "AND COD_INTERNO <> '".$id."'";
            $qry = ibase_query($res, $sql);
            $row = ibase_fetch_object($qry);
            $idp = $row->COD_PRODUTO;
            if (!empty($idp)){

                alert(strtoupper($_SESSION["sLOGIN_USUARIO"]).", Já existe PRODUTO cadastrado com o CÓDIGO ".$codigo);
                anterior(-1);

            }else{

                $sql4 = "SELECT VALOR FROM ALIQUOTA WHERE COD_ALIQUOTA = '".$aliquota."'";
                $qry4 = ibase_query($res, $sql4);
        	    $row4 = ibase_fetch_object($qry4);
                if (empty($row4->VALOR)) $row4->VALOR = 0;
                
                //INCLUINDO...
                $sql2 = "UPDATE PRODUTO SET COD_PRODUTO = '".$codigo."', DESCRICAO = '".$nome."', COD_GRUPO = '".$grupo."', ";
                $sql2.= "COD_SUBGRUPO = '".$subgrupo."', COD_LINHA = '".$linha."', BRUTO = '".$bruto."', LIQUIDO = '".$liquido."', ";
                $sql2.= "ESTOQUE = '".$estoque."', CUSTO = '".$custo."', ICMS_IN = '".$icmsin."', ST = '".$origem.$icms."', UNIDADE = '".$und."', ";
                $sql2.= "COMPRA = '".$compra."', MINIMO = '".$minimo."', ALIQUOTA = '".$aliquota."', TIPO = '".$tipo."', ICMS_OUT = '".$icmsout."', ";
                $sql2.= "COFINS = '".$cofins."', FEDERAL = '".$federal."', MARKUP = '".$markup."', VENDA_MARKUP = '".$markupvenda."', ";
                $sql2.= "COMISSAO = '".$comissao."', GONDOLA = '".$gondola."', RESERVADO = '".$reservado."', ";
                $sql2.= "TIPO_VENDA = '".$tipo."', MAXIMO = '".$maximo."', COD_FABRICANTE = '".$fabri."', MULCOMISSAO = '".$mulcomissao."', ";
                $sql2.= "ATACADO = '".$defla1."', ATACADO1 = '".$defla2."', ATACADO2 = '".$defla3."', VENDA = '".$venda."', ";
                $sql2.= "DATA_ALTERACAO = '".date("Y-m-d")."', COD_COLABORADORALTERACAO = '".$_SESSION["sIDUSUARIO"]."', QTD_EMBALAGEM = '".$qtdemb."', ";
                $sql2.= "PRECO_EMBALAGEM = '".$precoemb."', PAUTA = '".$pauta."', DESC_ATACADO = '".$descricao."', QTD_ULT_COMPRA = '0', ";
                $sql2.= "PRECO_ULT_COMPRA = '0', UND_EMBALAGEM = '".$undemb."', QTD_ANTES_ULT_COMPRA = '0', ST_PROMOCAO = '".$promo."', ";
                $sql2.= "DATA_INICIO_PROM = ".$dtini.", DATA_FINAL_PROM = ".$dtfim.", PRECO_PROM = '".$precopro."', COD_BARRA_EMBALAGEM = '".$baremb."', ";
                $sql2.= "BALANCA = '".$balanca."', COD_NCM = '".$codncm."', CSTPC = '". $piscofins."', MOD_BCICMS = '".$mod1."', ";
                $sql2.= "MOD_BCICMSST = '".$mod2."', FABRICACAO = '".$fabricacao."', QTD_VOL = '".$qtdata ."', ";
                $sql2.= "PRECO_VOL = '".$precoata."', INATIVO = '".$ativo."', CONTROLA_ESTOQUE = '".$controla."', DESCRICAO_PROMOCAO = '".$descpro."', ";
                $sql2.= "MVA_IN = '".$mvain."', MVA_OUT = '".$mvaout."', ALI_IPI = '".$row4->VALOR."', CSTIPI = '".$ipi."' ";
                $sql2.= "WHERE COD_INTERNO = '".$id."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
                exit($sql2);
                $qry2 = ibase_query($res, $sql2);
                
                if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
                
                goto2("../lib/Fachada.php?acao=".base64_encode("filtrarProdutos")."&cad=0&mod=".$_GET["mod"]);
                
            }
            
		break;
        
		case "filtrarProdutos":
			require_once("../class/ConexaoFirebird.php");
            include_once("../consultas/filtro_produtos.php");
		break;
        /**/

        /* ATIVIDADE NFE */
		case "cadastrarAtividadeNFE":
        
            $IDCONEXAONFE = antInjection(base64_decode($_REQUEST["empresa"]));
            
            pa($_REQUEST); 

            require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_atividades_nfe.php");
		break;
        
		case "incluirAtividadeNFE":
        
            $IDCONEXAONFE = antInjection(base64_decode($_POST["empresa"]));
            
            require_once("../class/ConexaoFirebird.php");
            
            $nome = antInjection(strtoupper($_POST["nome"]));
			
    	    $sql = "SELECT MAX(COD_ATIVIDADE) + 1 AS COD_ATIVIDADE FROM ATIVIDADE";	  
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            if ($row->COD_ATIVIDADE == 0) $row->COD_ATIVIDADE = 1;
            
            $sql2 = "INSERT INTO ATIVIDADE (EMPRESA, COD_ATIVIDADE, ATIVIDADE) ";
            $sql2.= "VALUES ('".$IDCONEXAONFE."', '".$row->COD_ATIVIDADE."', '".$nome."')";
//            echo '<br>'.base64_decode($_POST["empresa"]).' sssaaaa'; pa($_POST); exit($sql2);
            $qry2 = ibase_query($res, $sql2);
            
            if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
            
            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAtividadeNFE")."&cad=0&mod=".$_GET["mod"]."&empresa=".$_POST["empresa"]);

		break;
        
		case "excluirAtividadeNFE":
			
            $IDCONEXAONFE = antInjection(base64_decode($_POST["empresa"]));
            
            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT ATIVIDADE FROM CADASTRO WHERE ATIVIDADE = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                exit($sql2); 
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);
                
                if (!empty($row2->ATIVIDADE)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM ATIVIDADE WHERE COD_ATIVIDADE = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                    $qry2 = ibase_query($res, $sql2);
                }
                
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarAtividadeNFE")."&exc=".$totError."&mod=".$_GET["mod"]."&empresa=".$_POST["empresa"]);
            
		break;

		case "alterarAtividadeNFE":
        
            pa($_POST); exit;

            $IDCONEXAONFE = antInjection(base64_decode($_POST["empresa"]));

            require_once("../class/ConexaoFirebird.php");

            $id   = antInjection(base64_decode($_POST["id"]));
            $nome = antInjection(strtoupper($_POST["nome"]));
			
            //verifica se ja existe com o mesmo nome
    	    $sql = "SELECT COD_ATIVIDADE FROM ATIVIDADE WHERE COD_ATIVIDADE <> '".$id."' AND ATIVIDADE = '".$nome."' ";	  
            $sql.= "AND EMPRESA = '".$IDCONEXAONFE."'";
            exit($sql);
            $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            $sql2 = "UPDATE ATIVIDADE SET ATIVIDADE = '".$nome."' WHERE COD_ATIVIDADE = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
            exit($sql2);
            $qry2 = ibase_query($res, $sql2);
            
            if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
            
            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAtividadeNFE")."&cad=0&mod=".$_GET["mod"]."&empresa=".$_POST["empresa"]);

		break;

		case "filtrarAtividadeNFE":
            require_once("../class/ConexaoFirebird.php");
			include_once("../consultas/filtro_atividades_nfe.php");
		break;
        /**/

       /* PARAMETROS NFE */
		case "formularioParametrosNFE":
            
            require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_parametros_nfe.php");
		break;

		case "atualizarParametrosNFE":
            require_once("../class/ConexaoFirebird.php");
              
            $sql = "UPDATE CONFIG_PARAMENTROS SET FTPLOCAL = '".$_POST["nome"]."'";
            $qry = ibase_query($res, $sql);
            
            if ($qry) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");

            goto2("../lib/Fachada.php?acao=".base64_encode("formularioParametrosNFE")."&cad=0&mod=".$_GET["mod"]);

		break;
       /**/

		/* CLIENTES */
		case "cadastrarClientes":
            require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_clientes.php");
		break;
		
		case "filtrarClientes":
			include_once("../consultas/filtro_clientes.php");
		break;
		
		case "incluirClientes":
            require_once("../class/ConexaoFirebird.php");

			$div  = antInjection($_POST["div"]);
            $tipo = antInjection($_POST["tipo"]);
            
            if ($div == "F"){ // PESSOA FISICA
            
                $razao     = antInjection(strtoupper($_POST["razaoSocial"]));
                $fanta     = antInjection(strtoupper($_POST["fanta"]));
                $cnpj      = antInjection(strtoupper($_POST["cnpj"]));
                $municipal = antInjection(strtoupper($_POST["municipal"]));
                $ie        = antInjection(strtoupper($_POST["ie"]));                
                $cnae      = antInjection(strtoupper($_POST["cnae"]));
                $ende      = antInjection(strtoupper($_POST["ende"]));
                $bairro    = antInjection(strtoupper($_POST["bairro"]));
                $nrend1    = antInjection(strtoupper($_POST["nrend1"]));
                $cep       = antInjection(strtoupper($_POST["cep"]));
                $uf        = antInjection(strtoupper($_POST["uf"]));
                $cidade    = antInjection(strtoupper($_POST["cidade"]));            
                
            }else{ //PESSOA JURIDICA
            
                $razao     = antInjection(strtoupper($_POST["razaoSocial2"]));
                $fanta     = antInjection(strtoupper($_POST["fanta2"]));
                $cnpj      = antInjection(strtoupper($_POST["cnpj2"]));
                $municipal = antInjection(strtoupper($_POST["municipal2"]));
                $ie        = antInjection(strtoupper($_POST["ie2"]));
                $cnae      = antInjection(strtoupper($_POST["cnae2"]));
                $ende      = antInjection(strtoupper($_POST["ende2"]));
                $bairro    = antInjection(strtoupper($_POST["bairro2"]));
                $nrend1    = antInjection(strtoupper($_POST["nrend11"]));
                $cep       = antInjection(strtoupper($_POST["cep2"]));
                $uf        = antInjection(strtoupper($_POST["uf2"]));
                $cidade    = antInjection(strtoupper($_POST["cidade2"]));
            }

            $foneFisica      = antInjection(strtoupper($_POST["foneFisica"]));
            $celuFisica      = antInjection(strtoupper($_POST["celuFisica"]));
            $faxFisica       = antInjection(strtoupper($_POST["faxFisica"]));
            $contaFisica     = antInjection(strtoupper($_POST["contaFisica"]));
            $nasc            = antInjection(strtoupper($_POST["nasc"]));
            if (!empty($nasc)) $nasc = "'".$conv->conData($nasc)."'"; else $nasc = "NULL";
            
            $areaFisica      = antInjection(strtoupper($_POST["areaFisica"]));
            $distanciaFisica = antInjection(strtoupper($_POST["distanciaFisica"]));
            $comissaoFisica  = antInjection(strtoupper($_POST["comissaoFisica"]));
            if (empty($comissaoFisica)) $comissaoFisica = 0;
            
            $atividade       = antInjection(strtoupper($_POST["atividade"]));
            $foneJuridica    = antInjection(strtoupper($_POST["foneJuridica"]));
            $celuJuridica    = antInjection(strtoupper($_POST["celuJuridica"]));
            $faxJuridica     = antInjection(strtoupper($_POST["faxJuridica"]));
            $conta           = antInjection(strtoupper($_POST["conta"]));
            $endcob          = antInjection(strtoupper($_POST["endcob"]));
            $cepcob          = antInjection(strtoupper($_POST["cepcob"]));
            $bairrocob       = antInjection(strtoupper($_POST["bairrocob"]));
            $estadocob       = antInjection(strtoupper($_POST["estadocob"]));
            $cidadecob       = antInjection(strtoupper($_POST["cidadecob"]));
            $obs             = antInjection(strtoupper($_POST["obs"]));
            $ultimo_venc     = antInjection(strtoupper($_POST["ultimo_venc"]));
            if (!empty($ultimo_venc)){
                $ultimo_venc = "'".$conv->conData($ultimo_venc)."'";
            }else{
                $ultimo_venc = "NULL";
            }

            $atual_venc      = antInjection($_POST["atual_venc"]);
            if (!empty($atual_venc)){
                $atual_venc = "'".$conv->conData($atual_venc)."'";
            }else{
                $atual_venc = "NULL";
            }
            
            $prazo           = antInjection(strtoupper($_POST["prazo"]));
            if (empty($prazo)) $prazo = 0;

            $tipo_fatura     = antInjection(strtoupper($_POST["tipo_fatura"]));
            $dia_fatura      = antInjection(strtoupper($_POST["dia_fatura"]));
            $venc_cartao     = antInjection(strtoupper($_POST["venc_cartao"]));
            if (!empty($venc_cartao)){
                $venc_cartao = "'".$conv->conData($venc_cartao)."'";
            }else{
                $venc_cartao = "NULL";
            } 
            
            $cartao_proprio  = antInjection(strtoupper($_POST["cartao_proprio"]));
            $senhacred       = antInjection(strtoupper($_POST["senhacred"]));
            $nrend2          = antInjection(strtoupper($_POST["nrend2"]));
            $emailcob        = antInjection($_POST["emailcob"]);
            $cod_reg_trib    = antInjection(strtoupper($_POST["cod_reg_trib"]));
            $trib            = antInjection(strtoupper($_POST["trib"]));
            $limite          = antInjection(strtoupper($_POST["limite"]));
            if (empty($limite)) $limite = 0;

            $desloca         = antInjection(strtoupper($_POST["desloca"]));
            if (empty($desloca)) $desloca = 0;

            $vendedor        = antInjection(strtoupper($_POST["vendedor"]));
        
			$sql = "SELECT CNPJ FROM CADASTRO WHERE CNPJ = '".$cnpj."'";
            $qry = ibase_query($res, $sql);   
            $row = ibase_fetch_row($qry);
            if (!empty($row[0])){

				alert("ATENÇÃO: Já existe CLIENTE cadastro com o CPF/CNPJ ".$cnpj.".");
				anterior(-1);

            }else{

                $sql2 = "SELECT MAX(COD_CADASTRO + 1) AS COD_CADASTRO FROM CADASTRO";
                $qry2 = ibase_query($res, $sql2);   
                $row2 = ibase_fetch_row($qry2);
                $id = $row2[0];
                if (empty($id)) $id = 1;

                $sql = "INSERT INTO CADASTRO (EMPRESA, COD_CADASTRO, RAZAO, FANTASIA, CNPJ, TIPO, ESTADUAL, MUNICIPAL, CAE, ";
                $sql.= "ENDERECO, BAIRRO, CEP, CIDADE, ESTADO, TELEFONE, FAX, CELULAR, CONTATO, ENDCOB, BAIRROCOB, CEPCOB, ";
                $sql.= "CIDADECOB, ESTADOCOB, OBS, ATIVIDADE, TRIBUTACAO, COMISSAO, VENDEDOR, ";
                $sql.= "ATIVO, MULTDISTANCIA, MULTATIVIDADE, CADASTRADO_POR, CADASTRADO_EM, ";
                $sql.= "AREA, LIMITE, ULTIMO_VENC, ATUAL_VENC, PRAZO, TIPO_FATURA, DATANASC, DIA_FATUTA, VENC_CARTAO, CARTAO_PROPRIO, ";
                $sql.= "SENHACRED, NREND1, NREND2, E_MAIL, COD_REG_TRIB, TIPOCAD, DESLOCAMENTO) VALUES ('".$_SESSION["sEMP_IDEmpresa"]."', '".$id."', ";
                $sql.= "'".$razao."', '".$fanta."', '".$cnpj."', '".$tipo."', '".$ie."', '".$municipal."',  '".$cnae."', '".$ende." ".$nume."', ";
                $sql.= "'".$bairro."', '".$cep."', '".$cidade."', '".$uf."', '". $foneFisica ."', '".$faxFisica."', '".$celuFisica."', ";
                $sql.= "'".$conta."', '".$endcob."', '".$bairrocob."', '".$cepcob."', '". $cidadecob."', '".$estadocob."', '".$obs."', ";
                $sql.= "'".$atividade."', '".$trib."', '".$comissaoFisica."', '".$vendedor."', 'T', '".$distanciaFisica."', ";
                $sql.= "'0', '".$_SESSION["sIDUSUARIO"]."', '".date("Y-m-d")."', '".$areaFisica."', '".$limite."', ".$ultimo_venc.", ";
                $sql.= "".$atual_venc.", '".$prazo."', '".$tipo_fatura."', ".$nasc.", '".$dia_fatura."', ".$venc_cartao.", ";
                $sql.= "'".$cartao_proprio."', '".$senhacred."', '".$nrend1."', '".$nrend2."', '". $emailcob."', '".$cod_reg_trib."', '".$div."', '".$desloca."')";
                $qry = ibase_query($res, $sql);
                if ($qry){
                    $erro = 0;
                    ibase_query($res, "COMMIT");
                }else{
                    $erro = 1;
                    ibase_query($res, "ROLLBACK");
                }
            }

            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarClientes")."&cad=".$erro."&mod=".$_GET["mod"]);

		break;
		
		case "excluirClientes": 

            $IDCONEXAONFE = antInjection( base64_decode($_POST["empresa"]));

            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT IDCLIFOR FROM TB_PEDIDOS_HEADER WHERE IDCLIFOR = ".$id;
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);

                if (!empty($row2->IDCLIFOR)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM CADASTRO WHERE COD_CADASTRO = ".$id." AND EMPRESA = ".$IDCONEXAONFE;
                    $qry2 = ibase_query($res, $sql2);
                }
                
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarClientes")."&exc=".$totError."&mod=".$_GET["mod"]);
		break;
		
		case "alterarClientes":
            header("Content-Type: text/html; charset=ISO-8859-1", true);
            
            require_once("../class/ConexaoFirebird.php");

            $id   = antInjection(base64_decode($_POST["id"]));
			$div  = antInjection($_POST["div"]);
            $tipo = antInjection($_POST["tipo"]);
            
            if ($div == "F"){ // PESSOA FISICA
            
                $razao     = antInjection(strtoupper($_POST["razaoSocial"]));
                $fanta     = antInjection(strtoupper($_POST["fanta"]));
                $cnpj      = antInjection(strtoupper($_POST["cnpj"]));
                $municipal = antInjection(strtoupper($_POST["municipal"]));
                $ie        = antInjection(strtoupper($_POST["ie"]));                
                $cnae      = antInjection(strtoupper($_POST["cnae"]));
                $ende      = antInjection(strtoupper($_POST["ende"]));
                $bairro    = antInjection(strtoupper($_POST["bairro"]));
                $nume      = antInjection(strtoupper($_POST["nume"]));
                $cep       = antInjection(strtoupper($_POST["cep"]));
                $uf        = antInjection(strtoupper($_POST["uf"]));
                $cidade    = antInjection(strtoupper($_POST["cidade"]));            
                
            }else{ //PESSOA JURIDICA
            
                $razao     = antInjection(strtoupper($_POST["razaoSocial2"]));
                $fanta     = antInjection(strtoupper($_POST["fanta2"]));
                $cnpj      = antInjection(strtoupper($_POST["cnpj2"]));
                $municipal = antInjection(strtoupper($_POST["municipal2"]));
                $ie        = antInjection(strtoupper($_POST["ie2"]));
                $cnae      = antInjection(strtoupper($_POST["cnae2"]));
                $ende      = antInjection(strtoupper($_POST["ende2"]));
                $bairro    = antInjection(strtoupper($_POST["bairro2"]));
                $nume      = antInjection(strtoupper($_POST["nume2"]));
                $cep       = antInjection(strtoupper($_POST["cep2"]));
                $uf        = antInjection(strtoupper($_POST["uf2"]));
                $cidade    = antInjection(strtoupper($_POST["cidade2"]));
            }

            $foneFisica      = antInjection(strtoupper($_POST["foneFisica"]));
            $celuFisica      = antInjection(strtoupper($_POST["celuFisica"]));
            $faxFisica       = antInjection(strtoupper($_POST["faxFisica"]));
            $contaFisica     = antInjection(strtoupper($_POST["contaFisica"]));
            $nasc            = antInjection(strtoupper($_POST["nasc"]));
            if (!empty($nasc)) $nasc = "'".$conv->conData($nasc)."'"; else $nasc = "NULL";

            $areaFisica      = antInjection(strtoupper($_POST["areaFisica"]));
            $distanciaFisica = antInjection(strtoupper($_POST["distanciaFisica"]));
            $comissaoFisica  = antInjection(strtoupper($_POST["comissaoFisica"]));
            if (empty($comissaoFisica)) $comissaoFisica = 0;

            $atividade       = antInjection(strtoupper($_POST["atividade"]));
            $foneJuridica    = antInjection(strtoupper($_POST["foneJuridica"]));
            $celuJuridica    = antInjection(strtoupper($_POST["celuJuridica"]));
            $faxJuridica     = antInjection(strtoupper($_POST["faxJuridica"]));
            $conta           = antInjection(strtoupper($_POST["conta"]));
            $endcob          = antInjection(strtoupper($_POST["endcob"]));
            $cepcob          = antInjection(strtoupper($_POST["cepcob"]));
            $bairrocob       = antInjection(strtoupper($_POST["bairrocob"]));
            $estadocob       = antInjection(strtoupper($_POST["estadocob"]));
            $cidadecob       = antInjection(strtoupper($_POST["cidadecob"]));
            $obs             = antInjection(strtoupper($_POST["obs"]));
            $ultimo_venc     = antInjection(strtoupper($_POST["ultimo_venc"]));
            if (!empty($ultimo_venc)) $ultimo_venc = "'".$conv->conData($ultimo_venc)."'"; else $ultimo_venc = "NULL";
            
            $atual_venc      = antInjection($_POST["atual_venc"]);
            if (!empty($atual_venc)) $atual_venc = "'".$conv->conData($atual_venc)."'"; else $atual_venc = "NULL";

            $prazo           = antInjection(strtoupper($_POST["prazo"]));
            if (empty($prazo)) $prazo = 0;

            $tipo_fatura     = antInjection(strtoupper($_POST["tipo_fatura"]));
            $dia_fatura      = antInjection(strtoupper($_POST["dia_fatura"]));
            $venc_cartao     = antInjection(strtoupper($_POST["venc_cartao"]));
            if (!empty($venc_cartao)) $venc_cartao = "'".$conv->conData($venc_cartao)."'"; else $venc_cartao = "NULL";
            
            $cartao_proprio  = antInjection(strtoupper($_POST["cartao_proprio"]));
            $senhacred       = antInjection(strtoupper($_POST["senhacred"]));
            $nrend1          = antInjection(strtoupper($_POST["nrend1"]));
            $nrend2          = antInjection(strtoupper($_POST["nrend2"]));
            $emailcob        = antInjection($_POST["emailcob"]);
            $cod_reg_trib    = antInjection(strtoupper($_POST["cod_reg_trib"]));
            $trib            = antInjection(strtoupper($_POST["trib"]));
            $limite          = antInjection(strtoupper($_POST["limite"]));
            if (empty($limite)) $limite = 0;

            $desloca         = antInjection(strtoupper($_POST["desloca"]));
            if (empty($desloca)) $desloca = 0;

            $vendedor        = antInjection(strtoupper($_POST["vendedor"]));
        
			$sql = "SELECT CNPJ FROM CADASTRO WHERE CNPJ = '".$cnpj."' AND COD_CADASTRO <> ".$id;
            $qry = ibase_query($res, $sql);   
            $row = ibase_fetch_row($qry);
            if (!empty($row[0])){

				alert("ATENÇÃO: Já existe CLIENTE cadastro com o CPF/CNPJ ".$cnpj.".");
				anterior(-1);

            }else{

                $sql = "UPDATE CADASTRO SET RAZAO = '".$razao."', FANTASIA = '".$fanta."', CNPJ = '".$cnpj."', TIPO = '".$tipo."', ";
                $sql.= "ESTADUAL = '".$ie."', MUNICIPAL = '".$municipal."', CAE = '".$cnae."', ENDERECO = '".$ende." ".$nume."', "; 
                $sql.= "BAIRRO = '".$bairro."', CEP = '".$cep."', CIDADE = '".$cidade."', ESTADO = '".$uf."', TELEFONE = '".$foneFisica ."', ";
                $sql.= "FAX = '".$faxFisica."' , CELULAR = '".$celuFisica."', CONTATO = '".$conta."', ENDCOB = '".$endcob."', ";
                $sql.= "BAIRROCOB = '".$bairrocob."', CEPCOB = '".$cepcob."', CIDADECOB = '". $cidadecob."', ESTADOCOB = '".$estadocob."', ";
                $sql.= "OBS = '".$obs."', ATIVIDADE = '".$atividade."', TRIBUTACAO = '".$trib."', COMISSAO = '".$comissaoFisica."', ";
                $sql.= "VENDEDOR = '".$vendedor."', MULTDISTANCIA = '".$distanciaFisica."', AREA = '".$areaFisica."', LIMITE = '".$limite."', ";
                $sql.= "ULTIMO_VENC = ".$ultimo_venc.", ATUAL_VENC = ".$atual_venc.", ";
                $sql.= "PRAZO = '".$prazo."', TIPO_FATURA = '".$tipo_fatura."', DATANASC = ".$nasc.", "; 
                $sql.= "DIA_FATUTA = '".$dia_fatura."', VENC_CARTAO = ".$venc_cartao.", CARTAO_PROPRIO = '".$cartao_proprio."', ";
                $sql.= "SENHACRED = '".$senhacred."', NREND1 = '".$nrend1."', NREND2 = '".$nrend2."', E_MAIL = '".$emailcob."', ";
                $sql.= "COD_REG_TRIB = '".$cod_reg_trib."', TIPOCAD = '".$div."', DESLOCAMENTO = '".$desloca."', ALTERADO_POR = ".$_SESSION["sIDUSUARIO"].", ";                
                $sql.= " ALTERADO_EM = '".date("Y-m-d")."' WHERE EMPRESA = ".$_SESSION["sEMP_IDEmpresa"]." AND COD_CADASTRO = ".$id;
                $qry = ibase_query($res, $sql);
                if ($qry){
                    $erro = 0;
                    ibase_query($res, "COMMIT");
                }else{
                    $erro = 1;
                    ibase_query($res, "ROLLBACK");
                }
            }

            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarClientes")."&alt=".$erro."&mod=".$_GET["mod"]);

		break;		
		/**/

		/**/		
		case "gerarBKPBD":				

			$sql = "SELECT * FROM tb_mod_usuarios WHERE USU_IDUsuario = ".$_SESSION["sIDUSUARIO"]." AND MOD_ID = 5";
			$row = $banco->listarArray($sql);
			if (count($row) == 0){
				
				alert(utf8_decode(strtoupper($_SESSION["sNOME_USUARIO"]).", você não tem acesso a esta operação."));
				fechar();
				
			}else{

				require_once("../class/Backup_Banco.php");
				require("../class/Banco.php");

				$mysql = new mysql_backup();

				$mysql->connect($PRO_host, $PRO_usuario, $PRO_senha, $PRO_basededados);
				$mysql->structure();
				$nomearq = "../backup/pam_bkp_".date("dmYHis");
				$mysql->export_server($nomearq);
				
				alert(utf8_decode(strtoupper($_SESSION["sNOME_USUARIO"]).", Backup Gerado com sucesso! Baixe Agora o Backup."));
				goto2($nomearq.".zip");
			}
			
		break;		
		
		case "filtrarBKPBD":					
			require_once("../consultas/filtro_backup.php");		
		break;
		
		case "filtrarAcessoUSU":		
			require_once("../consultas/filtro_acessos_usuarios.php");		
		break;
		
		/* PRODUTOS EMPENHADOS */
		case "cadastrarProdEmp":
			require_once("../class/ConexaoFirebird.php");			
			include_once("../cadastros/frm_prod_empenhados.php");
		break;
		
		case "filtrarProdEmp":
			require_once("../class/ConexaoFirebird.php");
			include_once("../consultas/filtro_prod_empenhados.php");
		break;
		
		case "incluirProdEmp":
			require_once("../class/ConexaoFirebird.php");
			
			$ano    = antInjection($_POST["ano"]);			
			$dtini  = antInjection($conv->conData($_POST["dtini"]));			
			$prod   = antInjection($_POST["prod"]);
			$nome   = antInjection($_POST["id_nome"]);
			$saldo  = str_replace(",", ".", removeStrings(antInjection($_POST["saldo"]), "."));
			$status = "I"; //STATUS INICIAL INATIVO...

			//VERIFICA SE EXISTE...
			$tote = 0;
			for($i=0;$i<count($_POST["prod"]);$i++){
				
				$idp = $_POST["prod"][$i];
				$qtd = $_POST["qtd"][$i];
				
				$sqlE   = "SELECT PRT_ID FROM tb_prod_empenhados WHERE PRT_ID = ".$nome." AND PRD_ID = ".$idp." AND PEM_Ano = '".$ano."'";
				$existe = $banco->existe($sqlE);
				if ($existe){
					$tote++;				
				}else{
					
					$sql = "SELECT VENDA FROM PRODUTO WHERE COD_INTERNO = ".$idp;
					$qry = ibase_query($res, $sql);
					$row = ibase_fetch_object($qry);
					if (empty($row->VENDA)) $row->VENDA = 0;
					
					$sql = "INSERT INTO tb_prod_empenhados (PEM_Ano, PRT_ID, PRD_ID, PEM_Status, PEM_Saldo, PEM_Qtd, PEM_Data, USU_IDUsuario) ";
					$sql.= "VALUES ('".$ano."', '".$nome."', '".$idp."', '".$status."', '".$row->VENDA."', '".$qtd."', '".$dtini."', ".$_SESSION["sIDUSUARIO"].")";
					$qry = $banco->executarQuery($sql);
				}
			}
			
			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProdEmp")."&cad=0&mod=".$_GET["mod"]."&tote=".$tote);

		break;
		
		case "excluirProdEmp":
			
			clearBrowserCache();
			
			//excluindo...
			$sql = "DELETE FROM tb_prod_empenhados WHERE PEM_ID = ".base64_decode($_GET["id"]);
			$qry = $banco->executarQuery($sql);
			
		break;
		
		case "cancelarProdEmp":
			
			clearBrowserCache();
			
			//excluindo...
			$sql = "UPDATE tb_prod_empenhados SET PEM_Status = 'I', PEM_DataCancela = '".date("Y-m-d")."' WHERE PEM_ID = ".base64_decode($_GET["id"]);
			$qry = $banco->executarQuery($sql);
			
		break;
		
		case "alterarProdEmp":
			require_once("../class/ConexaoFirebird.php");
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$ano    = antInjection($_POST["ano"]);
			$numero = antInjection($_POST["numero"]);
			
			for($i=0;$i<count($_POST["idprod"]);$i++){
				
				$idp = $_POST["idprod"][$i];
				$qtd = $_POST["qtd"][$i];
				if (empty($qtd)) $qtd = 0;
				if ($qtd == 0) $st = "I"; else $st = "A";
				
				$sql = "UPDATE tb_prod_empenhados SET PEM_Status = '".$st."', PEM_Qtd2 = ".$qtd.", PEM_Numero = '".$numero."', ";
				$sql.= "PEM_DataLibera = '".date("Y-m-d")."', USU_IDUsuario = '".$_SESSION["sIDUSUARIO"]."' WHERE PEM_Ano = '".$ano."' AND PRT_ID = ".$id." AND PRD_ID = ".$idp;
				$qry = $banco->executarQuery($sql);
			}

			goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarProdEmp")."&alt=0&mod=".$_GET["mod"]);

		break;
		/**/
		
		/* AGENTE FINANCEIRO */
		case "cadastrarAgente":
			include_once("../cadastros/frm_agentes.php");
		break;
		
		case "filtrarAgente":
			include_once("../consultas/filtro_agentes.php");
		break;
		
		case "incluirAgente":

			$nome   = antInjection($_POST["nome"]);
			$feb    = antInjection($_POST["febraban"]);			
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT AGE_IDFEBRABAN from tb_agentes_financeiros WHERE AGE_Descricao = '".$nome."'";
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe AGENTE FINANCEIRO cadastrado com a descrição ".strtoupper($nome).".");
				anterior(-1);
			}else{
			
				$sql = "INSERT INTO tb_agentes_financeiros (AGE_IDFEBRABAN, AGE_Descricao, AGE_Status) ";
				$sql.= "VALUES ('".$feb."', '".$nome."', '".$status."')";
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAgente")."&cad=0&mod=".$_GET["mod"]);
			}

		break;
		
		case "excluirAgente":
			
			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				
				$sql = "DELETE FROM tb_agentes_financeiros WHERE AGE_ID = ".base64_decode($_POST["cod"][$i]);
				$qry = $banco->executarQuery($sql);
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarAgente")."&exc=0&mod=".$_GET["mod"]);
		break;
		
		case "alterarAgente":
				
			//verifica se existe...		
			$id     = antInjection(base64_decode($_POST["id"]));
			$nome   = antInjection($_POST["nome"]);
			$feb    = antInjection($_POST["febraban"]);			
			$status = antInjection($_POST["status"]);

			$sqlE   = "SELECT AGE_Descricao FROM tb_agentes_financeiros WHERE AGE_Descricao = '".$nome."' and AGE_ID <> ".$id;
			$existe = $banco->existe($sqlE);
			if ($existe){
				alert("ATENÇÃO: Já existe AGENTE FINANCEIRO cadastrado com a descrição ".$nome.".");
				anterior(-1);
			}else{
			
				$sql = "UPDATE tb_agentes_financeiros SET AGE_Descricao = '".$nome."', AGE_Status = '".$status."', AGE_IDFEBRABAN = '".$feb."' ";
				$sql.= "WHERE AGE_ID = ".$id;
				$qry = $banco->executarQuery($sql);

				goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarAgente")."&alt=0&mod=".$_GET["mod"]);
			}

		break;		
		/**/
        
        /* GRUPOS PRODUTO */
		case "cadastrarGruposProd":
            require_once("../class/ConexaoFirebird.php");
			include_once("../cadastros/frm_grupos_prod.php");
		break;
        
		case "incluirGruposProd":
			require_once("../class/ConexaoFirebird.php");
            
            $nome = antInjection(strtoupper($_POST["nome"]));
			
    	    $sql = "SELECT MAX(COD_GRUPO) + 1 AS COD_GRUPO FROM GRUPO_PROD";	  
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            if ($row->COD_GRUPO == 0) $row->COD_GRUPO = 1;
            
            $sql2 = "INSERT INTO GRUPO_PROD (EMPRESA, COD_GRUPO, DESCRICAO) ";
            $sql2.= "VALUES ('".$_SESSION["sEMP_IDEmpresa"]."', '".$row->COD_GRUPO."', '".$nome."')";
            $qry2 = ibase_query($res, $sql2);
            
            if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
            
            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarGruposProd")."&cad=0&mod=".$_GET["mod"]);
            
		break;
        
		case "excluirGruposProd":
			
            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT COD_GRUPO FROM PRODUTO WHERE COD_GRUPO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'"; 
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);
                
                if (!empty($row2->ATIVIDADE)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM GRUPO_PROD WHERE COD_GRUPO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                    $qry2 = ibase_query($res, $sql2);
                }
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarGruposProd")."&exc=".$totError."&mod=".$_GET["mod"]);

		break;
        
		case "alterarGruposProd":

            require_once("../class/ConexaoFirebird.php");
            
            $id   = antInjection(base64_decode($_POST["id"]));
            $nome = antInjection(strtoupper($_POST["nome"]));
			
            //verifica se ja existe com o mesmo nome
    	    $sql = "SELECT COD_GRUPO FROM GRUPO_PROD WHERE COD_GRUPO <> '".$id."' AND DESCRICAO = '".$nome."' ";	  
            $sql.= "AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            $sql2 = "UPDATE GRUPO_PROD SET DESCRICAO = '".$nome."' WHERE COD_GRUPO = '".$id."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
            $qry2 = ibase_query($res, $sql2);
            
            if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
            
            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarGruposProd")."&cad=0&mod=".$_GET["mod"]);

		break;

		case "filtrarGruposProd":
			require_once("../class/ConexaoFirebird.php");
            include_once("../consultas/filtro_grupos_prod.php");
		break;
        /**/
        
        /* SUB-GRUPOS PRODUTO */
		case "cadastrarSubGruposProd":
			require_once("../class/ConexaoFirebird.php");
            include_once("../cadastros/frm_sub_grupos_prod.php");
		break;
        
		case "incluirSubGruposProd":
			require_once("../class/ConexaoFirebird.php");
            
            $nome   = antInjection(strtoupper($_POST["nome"]));
            $markup = antInjection($_POST["markup"]);
            $grupo  = antInjection($_POST["grupo"]);
            
    	    $sql = "SELECT MAX(COD_SUBGRUPO) + 1 AS COD_SUBGRUPO FROM SUBGRUPO_PROD";	  
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            if ($row->COD_GRUPO == 0) $row->COD_GRUPO = 1;
            
            $sql2 = "INSERT INTO SUBGRUPO_PROD (EMPRESA, COD_GRUPO, COD_SUBGRUPO, DESCRICAO, MARKUP) ";
            $sql2.= "VALUES ('".$_SESSION["sEMP_IDEmpresa"]."', '".$grupo."', '".$row->COD_SUBGRUPO."', '".$nome."', '".$markup."')";
            $qry2 = ibase_query($res, $sql2);
            
            if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
            
            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarSubGruposProd")."&cad=0&mod=".$_GET["mod"]);
            
		break;
        
		case "excluirSubGruposProd":
        
            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT COD_SUBGRUPO FROM PRODUTO WHERE COD_SUBGRUPO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'"; 
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);
                
                if (!empty($row2->ATIVIDADE)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM SUBGRUPO_PROD WHERE COD_SUBGRUPO = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                    $qry2 = ibase_query($res, $sql2);
                }
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarSubGruposProd")."&exc=".$totError."&mod=".$_GET["mod"]);

		break;
        
		case "alterarSubGruposProd":

            require_once("../class/ConexaoFirebird.php");
            
            $id     = antInjection(base64_decode($_POST["id"]));
            $nome   = antInjection(strtoupper($_POST["nome"]));
            $markup = antInjection($_POST["markup"]);
            $grupo  = antInjection($_POST["grupo"]);            
			
            //verifica se ja existe com o mesmo nome
    	    $sql = "SELECT COD_SUBGRUPO FROM SUBGRUPO_PROD WHERE COD_SUBGRUPO <> '".$id."' AND DESCRICAO = '".$nome."' ";	  
            $sql.= "AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            if (empty($row->COD_SUBGRUPO)){
                
                $sql2 = "UPDATE SUBGRUPO_PROD SET DESCRICAO = '".$nome."', MARKUP = '".$markup."', COD_GRUPO = '".$grupo."' ";
                $sql2.= "WHERE COD_SUBGRUPO = '".$id."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
                //exit($sql2);
                $qry2 = ibase_query($res, $sql2);
                
                if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
                
                goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarSubGruposProd")."&cad=0&mod=".$_GET["mod"]);
                
            }else{
                    
                alert(strtoupper($_SESSION["sNOME_USUARIO"]).", já existe SUB-GRUPO cadastrado com esta descrição.");
                anterior(-1);
                
            }
            
		break;
        
		case "filtrarSubGruposProd":
			require_once("../class/ConexaoFirebird.php");
            include_once("../consultas/filtro_sub_grupos_prod.php");
		break;
        /**/
        
        /* LINHA DE PRODUTO */
		case "cadastrarLinhaProd":
			require_once("../class/ConexaoFirebird.php");
            include_once("../cadastros/frm_linha_prod.php");
		break;
        
		case "incluirLinhaProd":
			require_once("../class/ConexaoFirebird.php");
            
            $nome   = antInjection(strtoupper($_POST["nome"]));
            $grupo  = antInjection($_POST["grupo"]);
            
    	    $sql = "SELECT MAX(COD_LINHA) + 1 AS COD_LINHA FROM LINHA_PROD";	  
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);

            if ($row->COD_LINHA == 0) $row->COD_LINHA = 1;

            $sql2 = "INSERT INTO LINHA_PROD (EMPRESA, COD_LINHA, COD_SUBGRUPO, DESCRICAO) ";
            $sql2.= "VALUES ('".$_SESSION["sEMP_IDEmpresa"]."', '".$row->COD_LINHA."', '".$grupo."', '".$nome."')";
            $qry2 = ibase_query($res, $sql2);
            
            if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
            
            goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarLinhaProd")."&cad=0&mod=".$_GET["mod"]);
            
		break;
        
		case "excluirLinhaProd":

            $IDCONEXAONFE = antInjection($_POST["empresa"]);
            
            require_once("../class/ConexaoFirebird.php");

			$totError = 0;
			for($i=0;$i<count($_POST["cod"]);$i++){
				$id  = base64_decode($_POST["cod"][$i]);

				$sql2 = "SELECT COD_LINHA FROM PRODUTO WHERE COD_LINHA = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'"; 
                $qry2 = ibase_query($res, $sql2);
                $row2 = ibase_fetch_object($qry2);
                
                if (!empty($row2->ATIVIDADE)){
                    $totError++;
                }else{
    				$sql2 = "DELETE FROM LINHA_PROD WHERE COD_LINHA = '".$id."' AND EMPRESA = '".$IDCONEXAONFE."'";
                    $qry2 = ibase_query($res, $sql2);
                }
                ibase_query($res, "COMMIT");
			}

			//AÇÕES...
			goto2("../lib/Fachada.php?acao=".base64_encode("filtrarLinhaProd")."&exc=".$totError."&mod=".$_GET["mod"]);
            
		break;
        
		case "alterarLinhaProd":

            require_once("../class/ConexaoFirebird.php");
            
            $id     = antInjection(base64_decode($_POST["id"]));
            $nome   = antInjection(strtoupper($_POST["nome"]));
            $grupo  = antInjection($_POST["grupo"]);            
			
            //verifica se ja existe com o mesmo nome
    	    $sql = "SELECT COD_LINHA FROM LINHA_PROD WHERE COD_LINHA <> '".$id."' AND DESCRICAO = '".$nome."' ";	  
            $sql.= "AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
    	    $qry = ibase_query($res, $sql);
    	    $row = ibase_fetch_object($qry);
            
            if (empty($row->COD_LINHA)){
                
                $sql2 = "UPDATE LINHA_PROD SET DESCRICAO = '".$nome."', COD_SUBGRUPO = '".$grupo."' ";
                $sql2.= "WHERE COD_LINHA = '".$id."' AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."'";
                $qry2 = ibase_query($res, $sql2);
                
                if ($qry2) ibase_query($res, "COMMIT"); else ibase_query($res, "ROLLBACK");
                
                goto2("../lib/Fachada.php?acao=".base64_encode("cadastrarLinhaProd")."&cad=0&mod=".$_GET["mod"]);
                
            }else{
                    
                alert(strtoupper($_SESSION["sNOME_USUARIO"]).", já existe LINHA DE PRODUTO cadastrado com esta descrição.");
                anterior(-1);
                
            }

		break;
        
		case "filtrarLinhaProd":
			require_once("../class/ConexaoFirebird.php");
            include_once("../consultas/filtro_linha_prod.php");
		break;
        
        case "filtrarVeiculos":
    	   include_once("../consultas/filtro_veiculos.php");
    	break;
        
        case "filtrarMovVeiculos":
			include_once("../consultas/filtro_mov_veiculos.php");
		break;
        
        case "filtrarFamiliaRDE":
			include_once("../consultas/filtro_familia_rde.php");
		break;
        /**/
        
        case "filtrarLOGFiles":
			include_once("../consultas/filtro_log_files.php");
		break;

		default:
			//Erro de parâmetro invalido
			header("location: index.html");
		break;		
	}
?>