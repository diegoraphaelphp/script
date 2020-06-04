<?php
    class RepoModulo{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL  = "SELECT * FROM CAD_MOD_MODULOS ";
            $strSQL .= "WHERE MOD_ID IS NOT NULL "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND MOD_ID = ".$arrStrFiltros["codigo"]." ";
            }
            
            if(!empty ($arrStrFiltros["descricao"])){
                $strSQL .= "AND MOD_Descricao LIKE '%".$arrStrFiltros["descricao"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND MOD_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY MOD_Descricao ASC";
            
            return $this->objDbMysql->Select($strSQL);
        }
        
        public function Salvar($objModulo){
            
            $strSQL  = "INSERT INTO CAD_MOD_MODULOS(";
            $strSQL .= "MOD_Descricao, MOD_Status";
            $strSQL .= ") VALUES (";
            $strSQL .= "'".$objModulo->GetDescricao()."', '".$objModulo->GetStatus()."' )";
          
            return $this->objDbMysql->Insert($strSQL);
        }
        
        public function Alterar($objModulo){
            $strSQL  = "UPDATE CAD_MOD_MODULOS SET ";
            $strSQL .= "MOD_Descricao='".$objModulo->GetDescricao()."', MOD_Status='".$objModulo->GetStatus()."' ";
            $strSQL .= "WHERE MOD_ID=".$objModulo->GetId();
              
            return $this->objDbMysql->Update($strSQL);
        }
    }
?>