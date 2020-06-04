<?php
    class RepoMenuNivel3{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL   = "SELECT cmmn.MEN3_ID, cmmn.MEN2_ID, cmmn.MEN3_Descricao, cmmn.MEN3_Caminho, cmm.MEN2_Descricao,";
            $strSQL  .= " cmmn.MEN3_Status FROM CAD_MEN_MENUS_NIVEL3 as cmmn ";
            $strSQL  .= " LEFT JOIN CAD_MEN_MENUS_NIVEL2 AS cmm ON cmm.MEN2_ID = cmmn.MEN2_ID ";
            $strSQL  .= " WHERE cmmn.MEN3_ID IS NOT NULL "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND cmmn.MEN3_ID = ".$arrStrFiltros["codigo"]." ";
            }

            if(!empty ($arrStrFiltros["nome_menu2"])){
                $strSQL .= "AND cmm.MEN2_Descricao LIKE '%".$arrStrFiltros["nome_menu2"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["nome"])){
                $strSQL .= "AND cmmn.MEN3_Descricao LIKE '%".$arrStrFiltros["nome"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND cmmn.MEN3_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY cmmn.MEN3_Descricao ASC";
        
            return $this->objDbMysql->Select($strSQL);
        }
        
        public function Salvar($objMenuNivel3){
            
            $strSQL  = "INSERT INTO CAD_MEN_MENUS_NIVEL3(";
            $strSQL .= " MEN2_ID, MEN3_Descricao, MEN3_Caminho, MEN3_Status) VALUES( ";
            $strSQL .= $objMenuNivel3->GetMenuNivel2().", ";
            $strSQL .= " '".$objMenuNivel3->GetNome()."', '".$objMenuNivel3->GetCaminho()."', '".$objMenuNivel3->GetStatus()."' )";
          
            return $this->objDbMysql->Insert($strSQL);
        }
        
        public function Alterar($objMenuNivel3){
            $strSQL  = "UPDATE CAD_MEN_MENUS_NIVEL3 SET ";
            $strSQL .= "MEN2_ID=".$objMenuNivel3->GetMenuNivel2().", MEN3_Descricao='".$objMenuNivel3->GetNome()."', ";
            $strSQL .= "MEN3_Caminho='".$objMenuNivel3->GetCaminho()."', MEN3_Status='".$objMenuNivel3->GetStatus()."' ";
            $strSQL .= "WHERE MEN3_ID=".$objMenuNivel3->GetId();
              
            return $this->objDbMysql->Update($strSQL);
        }
    }
?>