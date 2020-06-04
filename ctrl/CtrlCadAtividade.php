<?php
    session_start();    
    require_once("../class/UsuarioException.php");
    require_once("../class/TecnicoException.php");	
    require_once("../class/Conexao.php");
    
    // retorno no formato json para o javascript que está
    // realizando a solicitação ao PHP
    $json["sucesso"] = "false";    
    $banco = Conexao::singleton();    
    $acao     = $_POST["acao"];
    
    $sqlPessoa="select PES_ID from tb_pessoas where USU_ID = ".$_SESSION["sIDUSUARIO"];    
    $rsPessoa = $banco->executarQuery($sqlPessoa);
    $pessoaArray   = mysql_fetch_assoc($rsPessoa);
    $pessoa = $pessoaArray["PES_ID"];
    
    
   
    if($acao == "Cadastrar")
    {
        
        $acoes    = $_POST["acoes"];        
        $tipos    = $_POST["tipos"];
        $ret;
        
        $sqlResp = "select ACO_ID from tb_acoes_pessoas where ACO_PES_TIPO = 'R' ";
        $retResp = $banco->executarQuery($sqlResp);
        $responsaveis = mysql_fetch_array($retResp);
    
                
        for ($i=0;$i<count($acoes);$i++)
        {
            for($j=0;$j<count($responsaveis);$j++)
            {
                if($acoes[$i] != $responsaveis[$j])
                {
                    $sql = "insert into tb_acoes_pessoas values($acoes[$i],$pessoa,'$tipos[$i]')";
                    $ret = $banco->executarQuery($sql);
                    
                }
              
                
            }
        }
        
        $json["sucesso"] = "true";
    }
    elseif($acao == "Alterar")
    {           
        $cods     = $_POST["cods"];
        $tipos    = $_POST["tipos"];
        $ret2     = false;        
        
        $delete = "DELETE FROM tb_acoes_pessoas WHERE PES_ID=".$pessoa;
        $ret2 = $banco->executarQuery($delete);   
        
        if($ret2){            
            for ($i=0;$i<count($cods);$i++)
            {                
                // só é permitido inserir os diferentes da opção NENHUM
                if($tipos[$i][0] != ""){
                    $insert  = "INSERT INTO tb_acoes_pessoas (ACO_ID, PES_ID, ACO_PES_TIPO) ";
                    $insert .= "VALUES ";
                    $insert .= "(".$cods[$i][0].", ".$pessoa.", '".$tipos[$i][0]."')";
                    
                    $ret2 = $banco->executarQuery($insert);            
                }
            }
        }
        
        $json["sucesso"] = "true";         
    }elseif($acao == "ChecarResponsavel"){        
        $acoes    = $_POST["acoes"];        
        $tipos    = $_POST["tipos"];
        $ret;
          
        
        
        for ($i=0;$i<count($acoes);$i++)
        {
           // $select = "SELECT ";
            
            $sql = "insert into tb_acoes_pessoas values($acoes[$i],$pessoa,'$tipos[$i]')";
            $ret = $banco->executarQuery($sql);            
        }
        
        $json["sucesso"] = "true";
    }    
    
    echo json_encode($json);       
?>
