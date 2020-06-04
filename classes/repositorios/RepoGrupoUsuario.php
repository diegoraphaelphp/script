<?php
    class RepoGrupoUsuario{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL  = "SELECT * FROM CAD_GRU_GRUPOS_USUARIOS ";
            $strSQL .= "WHERE GRU_ID IS NOT NULL AND GRU_ID != 1 "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND GRU_ID = ".$arrStrFiltros["codigo"]." ";
            }
            
            if(!empty ($arrStrFiltros["descricao"])){
                $strSQL .= "AND GRU_Descricao LIKE '%".$arrStrFiltros["descricao"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND GRU_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY GRU_Descricao ASC";
            
            return $this->objDbMysql->Select($strSQL);
        }
        
        public function Salvar($objGrupoUsuario){
            
            $strSQL  = "INSERT INTO CAD_GRU_GRUPOS_USUARIOS(";
            $strSQL .= "GRU_Descricao, GRU_Status, GRU_PermissaoVisualizar, GRU_PermissaoIncluir, GRU_PermissaoAlterar, GRU_PermissaoRemover";
            $strSQL .= ") VALUES (";
            $strSQL .= "'".$objGrupoUsuario->GetDescricao()."', '".$objGrupoUsuario->GetStatus()."', '".$objGrupoUsuario->GetVisualizar()."' , '".$objGrupoUsuario->GetIncluir()."' , '".$objGrupoUsuario->GetAlterar()."' , '".$objGrupoUsuario->GetRemover()."' )";
            
            return $this->objDbMysql->Insert($strSQL);
        }
        
        public function Alterar($objGrupoUsuario){
            $strSQL  = "UPDATE CAD_GRU_GRUPOS_USUARIOS SET ";
            $strSQL .= "GRU_Descricao='".$objGrupoUsuario->GetDescricao()."', GRU_Status='".$objGrupoUsuario->GetStatus()."' , GRU_PermissaoVisualizar='".$objGrupoUsuario->GetVisualizar()."' , GRU_PermissaoIncluir='".$objGrupoUsuario->GetIncluir()."' , GRU_PermissaoAlterar='".$objGrupoUsuario->GetAlterar()."' , GRU_PermissaoRemover='".$objGrupoUsuario->GetRemover()."' ";
            $strSQL .= "WHERE GRU_ID=".$objGrupoUsuario->GetId();
              
            return $this->objDbMysql->Update($strSQL);
        }
    }
?>