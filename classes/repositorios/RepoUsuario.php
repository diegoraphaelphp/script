<?php    
    class RepoUsuario{
        private $objDb;
        
        public function RepoUsuario(){
            $this->objDb = new DbMysql();
        }
        
        public function Salvar($objUsuario){            
            $strSQL  = "INSERT INTO CAD_USU_USUARIOS(";
            $strSQL .= " GRU_ID,MUN_ID,	UNI_ID,	USU_Nome, USU_Login, USU_Senha,	USU_Email, USU_Telefone, USU_DataCadastro, USU_Status";
            $strSQL .= ") VALUES (";
            $strSQL .= " ".$objUsuario->GetGrupoUsuario()->GetId().", ".$objUsuario->GetMunicipio()->GetId().", ".$objUsuario->GetUnidade()->GetId()." , ";
            $strSQL .= " '".$objUsuario->GetNome()."' , '".$objUsuario->GetLogin()."' , '".$objUsuario->GetSenha()."', '".$objUsuario->GetEmail(). "', ";
            $strSQL .= " '".$objUsuario->GetTelefone()."', '".date("Y-m-d H:i:s")."', '".$objUsuario->GetStatus()."' )";
 
            return $this->objDb->Insert($strSQL);
        }
        
        public function Alterar($objUsuario){            
            $strSQL  = "UPDATE CAD_USU_USUARIOS ";
            $strSQL .= "SET GRU_ID=".$objUsuario->GetGrupoUsuario()->GetId().", MUN_ID=".$objUsuario->GetMunicipio()->GetId().", UNI_ID=".$objUsuario->GetUnidade()->GetId().", ";
            $strSQL .= "USU_Nome='".$objUsuario->GetNome()."' , USU_Login='".$objUsuario->GetLogin()."' , USU_Email='".$objUsuario->GetEmail(). "', ";
            $strSQL .= "USU_Telefone='".$objUsuario->GetTelefone()."', USU_Status='".$objUsuario->GetStatus()."' WHERE USU_ID=".$objUsuario->GetId()." ";
       
            return $this->objDb->Insert($strSQL);
        }
        
        public function AlterarSenha($objUsuario){            
            $strSQL  = "UPDATE CAD_USU_USUARIOS ";
            $strSQL .= "SET USU_Senha = '".$objUsuario->GetSenha()."' WHERE USU_ID=".$objUsuario->GetId()." ";
       
            return $this->objDb->Insert($strSQL);
        }
        
        public function Consultar($arrStrFiltro){            
            $strSQL  = "SELECT *, g.GRU_Descricao, g.GRU_Status, un.UNI_Descricao, m.MUN_Descricao FROM CAD_USU_USUARIOS AS u ";            
            $strSQL .= "INNER JOIN CAD_UNI_UNIDADES AS un ON (un.UNI_ID = u.UNI_ID) ";
            $strSQL .= "INNER JOIN CAD_MUN_MUNICIPIOS AS m ON (m.MUN_ID = u.MUN_ID) ";
            $strSQL .= "LEFT JOIN CAD_GRU_GRUPOS_USUARIOS AS g ON (g.GRU_ID = u.GRU_ID) ";
            $strSQL .= "WHERE u.USU_ID IS NOT NULL ";
                                    
            if (!empty($arrStrFiltro["codigo"])){ 
                $strSQL .= " AND u.USU_ID  = ".$arrStrFiltro["codigo"]." ";
            }
            
            if (!empty($arrStrFiltro["nome"])){ 
                $strSQL .= " AND u.USU_Nome LIKE '%".$arrStrFiltro["nome"]."%' ";
            }
            
            if (!empty($arrStrFiltro["login"])){ 
                $strSQL .= " AND u.USU_Login LIKE '%".$arrStrFiltro["login"]."%' ";
            }
            
            if (!empty($arrStrFiltro["checarLogin"])){ 
                $strSQL .= " AND u.USU_Login = '".$arrStrFiltro["checarLogin"]."' ";
            }
            
            if (!empty($arrStrFiltro["email"])){ 
                $strSQL .= " AND u.USU_Email = '".$arrStrFiltro["email"]."' ";
            }
            
            if (!empty($arrStrFiltro["senha"])){ 
                $strSQL .= " AND u.USU_Senha = '".$arrStrFiltro["senha"]."' ";
            }
            if (!empty($arrStrFiltro["grupo"])){ 
                $strSQL .= " AND g.GRU_Descricao LIKE '%".$arrStrFiltro["grupo"]."%' ";
            }            
            if (!empty($arrStrFiltro["grupo"])){ 
                $strSQL .= " AND g.GRU_Descricao LIKE '%".$arrStrFiltro["grupo"]."%' ";
            }
            
            if (!empty($arrStrFiltro["status"])) {
                $strSQL .= " AND u.USU_Status = '".$arrStrFiltro["status"]."' ";
            }
            
            $strSQL .= " ORDER BY u.USU_Nome ASC";
          
            // cláusula limit
            if(!empty ($arrStrFiltro["limit"])){
                $strSQL .= " ".$arrStrFiltro["limit"];   
            }
           
            return $this->objDb->Select($strSQL);
        }
    }
?>
