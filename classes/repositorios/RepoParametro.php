<?php    
    class RepoParametro{
        private $objDb;
        
        public function RepoParametro(){
            $this->objDb = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL  = "SELECT * FROM CAD_PAR_PARAMETROS ";            
            $strSQL .= "WHERE PAR_ID IS NOT NULL ";
            
            if(!empty ($arrStrFiltros["parametro"])){
                $strSQL .= "AND PAR_Parametro = '".$arrStrFiltros["parametro"]."' ";
            }
            
            if(!empty ($arrStrFiltros["tipo"])){
                $strSQL .= "AND PAR_Tipo = '".$arrStrFiltros["tipo"]."' ";
            }
            
            return $this->objDb->Select($strSQL);
        }
    }
?>
