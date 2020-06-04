<?php
	header('Content-type: text/html; charset=UTF-8');

	//importando...
    $IDCONEXAONFE = base64_encode($_SESSION["sEMP_IDEmpresa"]);
    
    require_once("../class/ConexaoFirebird.php");
	
	if( isset( $_REQUEST['query'] ) && $_REQUEST['query'] != ""){
		$q = strtoupper($_REQUEST['query']);
		
		if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "nome"){
			$sql = "SELECT FIRST 10 COD_CADASTRO, FANTASIA, RAZAO, CNPJ, LIMITE FROM CADASTRO WHERE ATIVO = 'T' AND TIPO = 'F' ";
            $sql.= "AND EMPRESA = '".$IDCONEXAONFE."' AND (FANTASIA LIKE '%".$q."%' OR RAZAO LIKE '%".$q."%')";
			$qry = ibase_query($res, $sql);
			if ($qry){
				echo '<ul>'."\n";
				while ($row = ibase_fetch_object($qry)){
				
					$p = $row->FANTASIA;
                    $r = $row->RAZAO;
                    
					$p = preg_replace('/('.$q.')/i', '<span style="font-weight:bold;">$1</span>', $p);
                    $r = preg_replace('/('.$q.')/i', '<span style="font-weight:bold;">$1</span>', $r);                    
                    
					echo "\t".'<li id="autocomplete_'.$row->COD_CADASTRO.'" rel="'.$row->COD_CADASTRO.'_'.$row->FANTASIA.'_'.$row->LIMITE.'">'.utf8_encode($p).' ('.utf8_encode($r).') - '.$row->CNPJ.'</li>'."\n";
				}
				echo '</ul>';
			}
		}
	}
?>