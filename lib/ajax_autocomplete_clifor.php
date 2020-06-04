<?php
	header('Content-type: text/html; charset=UTF-8');
	
	//importando...
    require_once("../class/ConexaoFirebird.php");
	
	if( isset( $_REQUEST['query'] ) && $_REQUEST['query'] != ""){
		$q = strtoupper($_REQUEST['query']);
		
		if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "nome"){
			$sql = "SELECT FIRST 10 COD_CADASTRO, FANTASIA, CNPJ, LIMITE FROM CADASTRO WHERE ATIVO = 'T' AND TIPO = 'C' ";
            $sql.= "AND EMPRESA = '".$_SESSION["sEMP_IDEmpresa"]."' AND (FANTASIA LIKE '%".$q."%' OR RAZAO LIKE '%".$q."%')";
			$qry = ibase_query($res, $sql);
			if ($qry){
				echo '<ul>'."\n";
				while ($row = ibase_fetch_object($qry)){
				
					$p = $row->FANTASIA;
					$p = preg_replace('/(' . $q . ')/i', '<span style="font-weight:bold;">$1</span>', $p);
					echo "\t".'<li id="autocomplete_'.$row->COD_CADASTRO.'" rel="'.$row->COD_CADASTRO.'_'.$row->FANTASIA.'_'.$row->LIMITE.'">'.utf8_encode($p).' - '.$row->CNPJ.'</li>'."\n";
				}
				echo '</ul>';
			}
		}
	}
?>