<?php
    class RepoUnidade{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL  = "SELECT * FROM CAD_UNI_UNIDADES ";
            $strSQL .= "WHERE UNI_ID IS NOT NULL "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND UNI_ID = ".$arrStrFiltros["codigo"]." ";
            }
            
            if(!empty ($arrStrFiltros["descricao"])){
                $strSQL .= "AND UNI_Descricao LIKE '%".$arrStrFiltros["descricao"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND UNI_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY UNI_Descricao ASC";
            
            return $this->objDbMysql->Select($strSQL);
        } 
    }
?>