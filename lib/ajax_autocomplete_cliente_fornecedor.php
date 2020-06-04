<?php
	header('Content-type: text/html; charset=UTF-8');

	//importando...
    require_once("../class/ConexaoFirebird.php");
    //$rowBASE[0]["BAS_PAA"]
    //VERIFICA SE É ENTRADA OU SAIDA...
    if (!empty($_GET["tipo"])){

        $sqlV = "SELECT TIPO_MOVIMENTO FROM CFOP WHERE CFOP = '".$_GET["tipo"]."'";
        $qryV = ibase_query($res, $sqlV);
		$rowV = ibase_fetch_object($qryV);
        
        if ($rowV->TIPO_MOVIMENTO == 1) $rowV->TIPO_MOVIMENTO = "F"; else $rowV->TIPO_MOVIMENTO = "C";

    	if( isset( $_REQUEST['query'] ) && $_REQUEST['query'] != ""){
    		$q = strtoupper($_REQUEST['query']);
    		
    		if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "nome"){
    			$sql = "SELECT FIRST 10 COD_CADASTRO, FANTASIA, RAZAO, CNPJ, LIMITE FROM CADASTRO WHERE ATIVO = 'T' AND TIPO = '".$rowV->TIPO_MOVIMENTO."' ";
                $sql.= "AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."' AND (FANTASIA LIKE '%".$q."%' OR RAZAO LIKE '%".$q."%')";
//                echo $sql.'<br><br>';
    			$qry = ibase_query($res, $sql);
    			if ($qry){

    				echo '<ul>'."\n";
    				while ($row = ibase_fetch_object($qry)){

    					$p = $row->FANTASIA;
                        $r = $row->RAZAO;

    					
                        $p = preg_replace('/('.$q.')/i', '<span style="font-weight:bold;">$1</span>', $p);
                        $r = preg_replace('/('.$q.')/i', '<span style="font-weight:bold;">$1</span>', $r);

    					echo "\t".'<li id="autocomplete_'.$row->COD_CADASTRO.'" rel="'.$row->COD_CADASTRO.'_'.$row->FANTASIA.'_'.$row->LIMITE.'">'.utf8_encode($r).' ('.utf8_encode($p).') - '.$row->CNPJ.'</li>'."\n";

    				}
    				echo '</ul>';
    			}
    		}
    	}
    }      
?>