<?php
    class RepoUsuario{
        private $objDb;
        
        public function RepoUsuario(){
            $this->objDb = new DbMysql();
        }
        
        public function Consultar($arrStrFiltro){
            $strSQL  = "SELECT * FROM tb_usuarios ";            
            $strSQL .= "WHERE USU_IDUsuario IS NOT NULL ";
                        
            if (!empty($arrStrFiltro["codigo"])){ 
                $strSQL .= " AND USU_IDUsuario = ".$arrStrFiltro["codigo"]." ";
            }
            
            if (!empty($arrStrFiltro["nome"])){ 
                $strSQL .= " AND USU_Nome LIKE '%".$arrStrFiltro["nome"]."%' ";
            }
            
            if (!empty($arrStrFiltro["login"])){ 
                $strSQL .= " AND USU_Login LIKE '%".$arrStrFiltro["login"]."%' ";
            }
            
            if (!empty($arrStrFiltro["status"])) {
                $strSQL .= " AND USU_Status = '".$arrStrFiltro["status"]."' ";
            }
            
            $strSQL .= " ORDER BY USU_Nome ASC";
            
            // cláusula limit
            if(!empty ($arrStrFiltro["limit"])){
                $strSQL .= " ".$arrStrFiltro["limit"];   
            }
            
            return $this->objDb->Select($strSQL);
        }
    }
?>