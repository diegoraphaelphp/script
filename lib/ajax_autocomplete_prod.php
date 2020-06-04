<?php
	header('Content-type: text/html; charset=UTF-8');
	
	//importando...
	require_once("../class/UsuarioException.php");
	require_once("../class/TecnicoException.php");		
	require_once("../class/Conexao.php");
	require_once("../class/ConexaoFirebird.php");
	require_once("../lib/util.php");
	
	//instancias...
	$banco = Conexao::singleton();
    
	if( isset($_REQUEST['query']) && $_REQUEST['query'] != ""){
		$q = strtoupper($_REQUEST['query']);
		
		if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "nome"){	
		
			$sql2 = "SELECT PRD_ID FROM tb_prod_empenhados WHERE PEM_Ano = '".date("Y")."'"; // WHERE PRT_ID = '".base64_decode($_GET["idcli"])."' ";
			$row2 = $banco->listarArray($sql2);
			$in   = "";
			foreach($row2 as $l2){
				$in.= $l2["PRD_ID"].",";
			}
			$tam = strlen($in);
			$in = substr($in, 0, --$tam);
            
            //echo $sql2.' ---- '.$in;
            
            if ($rowBASE[0]["BAS_PAA"] == "N"){
                
				$sql = "SELECT FIRST 10 DISTINCT(COD_INTERNO) AS COD_INTERNO, DESCRICAO, VENDA FROM PRODUTO ";
				$sql.= "WHERE COD_PRODUTO LIKE '%".$q."%' OR DESCRICAO LIKE '%".$q."%'";
				$qry = ibase_query($res, $sql);
				if ($qry){
					echo '<ul>'."\n";
					while ($row = ibase_fetch_object($qry)){
					
						$p = $row->DESCRICAO;
						$p = preg_replace('/('.$q.')/i', '<span style="font-weight:bold;">$1</span>', $p);
						
						echo "\t".'<li id="autocomplete_'.$row->COD_INTERNO.'" rel="'.$row->COD_INTERNO.'_'.$row->DESCRICAO.'_'.$row->VENDA.'">'.utf8_encode($p).'</li>'."\n";
					}
					echo '</ul>';
				}

            }else{
                
    			if (!empty($in)){
    				$sql = "SELECT FIRST 10 DISTINCT(COD_INTERNO) AS COD_INTERNO, DESCRICAO, VENDA FROM PRODUTO ";
    				$sql.= "WHERE COD_INTERNO IN (".$in.") AND (COD_PRODUTO LIKE '%".$q."%' OR DESCRICAO LIKE '%".$q."%')";           
    				$qry = ibase_query($res, $sql);
    				if ($qry){
    					echo '<ul>'."\n";
    					while ($row = ibase_fetch_object($qry)){
    					
    						$p = $row->DESCRICAO;
    						$p = preg_replace('/('.$q.')/i', '<span style="font-weight:bold;">$1</span>', $p);
    						
    						echo "\t".'<li id="autocomplete_'.$row->COD_INTERNO.'" rel="'.$row->COD_INTERNO.'_'.$row->DESCRICAO.'_'.$row->VENDA.'">'.utf8_encode($p).'</li>'."\n";
    					}
    					echo '</ul>';
    				}
                }
            }    
		}
	}
?>