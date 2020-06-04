<?php
    class RepoMenuNivel2{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL   = "SELECT cmmn.MEN2_ID, cmmn.MEN_ID, cmmn.MEN2_Descricao, cmmn.MEN2_Caminho, cmm.MEN_Descricao,";
            $strSQL  .= " cmmn.MEN2_Status FROM CAD_MEN_MENUS_NIVEL2 as cmmn ";
            $strSQL  .= " LEFT JOIN CAD_MEN_MENUS_NIVEL1 AS cmm ON cmm.MEN_ID = cmmn.MEN_ID ";
            $strSQL  .= " WHERE cmmn.MEN2_ID IS NOT NULL "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND cmmn.MEN2_ID = ".$arrStrFiltros["codigo"]." ";
            }

            if(!empty ($arrStrFiltros["nome_menu1"])){
                $strSQL .= "AND cmm.MEN_Descricao LIKE '%".$arrStrFiltros["nome_menu1"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["nome"])){
                $strSQL .= "AND cmmn.MEN2_Descricao LIKE '%".$arrStrFiltros["nome"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND cmmn.MEN2_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY cmmn.MEN2_Descricao ASC";
        
            return $this->objDbMysql->Select($strSQL);
        }
        
        public function Salvar($objMenuNivel2){
            
            $strSQL  = "INSERT INTO CAD_MEN_MENUS_NIVEL2(";
            $strSQL .= " MEN_ID, MEN2_Descricao, MEN2_Caminho, MEN2_Status) VALUES( ";
            $strSQL .= $objMenuNivel2->GetMenuNivel1().", ";
            $strSQL .= " '".$objMenuNivel2->GetNome()."', '".$objMenuNivel2->GetCaminho()."', '".$objMenuNivel2->GetStatus()."' )";
          
            return $this->objDbMysql->Insert($strSQL);
        }
        
        public function Alterar($objMenuNivel2){
            $strSQL  = "UPDATE CAD_MEN_MENUS_NIVEL2 SET ";
            $strSQL .= "MEN_ID=".$objMenuNivel2->GetMenuNivel1().", MEN2_Descricao='".$objMenuNivel2->GetNome()."', ";
            $strSQL .= "MEN2_Caminho='".$objMenuNivel2->GetCaminho()."', MEN2_Status='".$objMenuNivel2->GetStatus()."' ";
            $strSQL .= "WHERE MEN2_ID=".$objMenuNivel2->GetId();
              
            return $this->objDbMysql->Update($strSQL);
        }
    }
?>