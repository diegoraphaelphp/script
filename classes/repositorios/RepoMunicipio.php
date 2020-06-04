<?php
    class RepoMunicipio{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL  = "SELECT * FROM CAD_MUN_MUNICIPIOS ";
            $strSQL .= "WHERE MUN_ID IS NOT NULL "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND MUN_ID = ".$arrStrFiltros["codigo"]." ";
            }
            
            if(!empty ($arrStrFiltros["descricao"])){
                $strSQL .= "AND MUN_Descricao LIKE '%".$arrStrFiltros["descricao"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND MUN_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY MUN_Descricao ASC";
            
            return $this->objDbMysql->Select($strSQL);
        } 
    }
?>